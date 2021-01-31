<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use CodeIgniter\API\ResponseTrait;
use Mpdf\Mpdf;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Uuid\Uuid;

class PenjualanController extends BaseController
{
    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->kecamatan = new ModelKecamatan();
        $this->desa = new ModelDesa();
        $this->db = new ModelPenjualan();
    }

    public function index()
    {
        $data['tahunMulai'] = $this->db->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');
        $data['judul'] = 'MASTER DATA | Data Penjualan';
        $data['username'] = $this->session->username;
        $data['active'] = 'penjualan';
        $data['kecamatan'] = $this->kecamatan->get()->getResultObject();

        return view('admin/konten/penjualan', $data);
    }

    function new()
    {
        $data['judul'] = 'MASTER DATA | Data Lahan';
        $data['username'] = $this->session->username;
        $data['active'] = 'penjualan';
        $data['kecamatan'] = $this->kecamatan->get()->getResultObject();

        return view('admin/konten/addPenjualan', $data);
    }

    function edit($uid)
    {
        $data['penjualan'] = $this->desa
            ->join('tb_penjualan', 'tb_desa.id = tb_penjualan.id_desa')
            ->where('tb_penjualan.uid', $uid)
            ->first();
        $data['penjualan'] = (object) $data['penjualan'];
        $data['judul'] = 'MASTER DATA | Data Penjualan';
        $data['username'] = $this->session->username;
        $data['active'] = 'penjualan';
        $data['kecamatan'] = $this->kecamatan->get()->getResultObject();

        // return $this->respond($data, 200);
        return view('admin/konten/editPenjualan', $data);
    }

    function delete($uid)
    {
        $delete = $this->db->where([
            'uid' => $uid
        ])->delete();

        if ($delete) {
            return $this->respond([
                'err'   => FALSE,
                'uid'   => $uid
            ], 200);
        }
        return $this->respond([
            'err'   => TRUE
        ], 200);
    }

    function create()
    {
        $id_desa = $this->request->getVar('id_desa');
        $tahun = $this->request->getVar('tahun');
        $total_produksi = $this->request->getVar('total_produksi');
        $harga = $this->request->getVar('harga');
        $total_pendapatan = $this->request->getVar('total_pendapatan');

        $data = [
            'uid'   => Uuid::uuid4(),
            'id_desa' => $id_desa,
            'tahun' => $tahun,
            'total_produksi' => $total_produksi,
            'harga' => $harga,
            'total_pendapatan' => $total_pendapatan,
            'created_by' => $this->session->username
        ];
        $validate = $this->validation->run($data, 'penjualan');
        if ($validate) {
            $this->db->save($data);
            $res = [
                'err' => FALSE
            ];
        } else {
            $res = [
                'err' => TRUE,
                'msg' => $this->validation->getErrors()
            ];
        }
        return $this->respond($res, 200);
    }

    function update($uid)
    {
        $id_desa = $this->request->getVar('id_desa');
        $tahun = $this->request->getVar('tahun');
        $total_produksi = $this->request->getVar('total_produksi');
        $harga = $this->request->getVar('harga');
        $total_pendapatan = $this->request->getVar('total_pendapatan');

        $data = [
            'uid'   => Uuid::uuid4(),
            'id_desa' => $id_desa,
            'tahun' => $tahun,
            'total_produksi' => $total_produksi,
            'harga' => $harga,
            'total_pendapatan' => $total_pendapatan,
            'created_by' => $this->session->username
        ];
        $validate = $this->validation->run($data, 'penjualan');
        if ($validate) {
            $this->db->where('uid', $uid)
                ->set($data)
                ->update();
            $res = [
                'err' => FALSE
            ];
        } else {
            $res = [
                'err' => TRUE,
                'msg' => $this->validation->getErrors()
            ];
        }
        return $this->respond($res, 200);
    }

    function get()
    {
        $year = $this->request->getVar('year') ?? date('Y');
        $list = $this->db->get_datatables($year);
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            $aksi = '<div class="aksi">' .
                '<button class="btn btn-success btn-sm ubah" 
                    data-uid="' . $field->uid . '" 
                ><i class="fa fa-edit"></i> </button>' .
                '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->uid . '"><i class="fa fa-times"></i> </button>' .
                '</div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->tahun;
            $row[] = $field->kecamatan;
            $row[] = $field->desa;
            $row[] = number_format($field->total_produksi, 0, ',', '.');
            $row[] = "Rp " . number_format($field->harga, 2, ',', '.');
            $row[] = "Rp " . number_format($field->total_pendapatan, 2, ',', '.');
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = array(
            "start" => $this->request->getPost('start'),
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->db->count_all($year),
            "recordsFiltered" => $this->db->count_filtered($year),
            "data" => $data,
        );
        return $this->respond($output, 200);
    }

    public function import()
    {
        $file = $this->request->getFile('file');
        // dd($file);
        if ($file) {
            $excelReader  = new PHPExcel();
            //mengambil lokasi temp file
            $fileLocation = $file->getTempName();
            //baca file
            $objPHPExcel = PHPExcel_IOFactory::load($fileLocation);
            //ambil sheet active
            $sheet    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            //looping untuk mengambil data

            $countSuccess = 0;
            $countError = 0;
            foreach ($sheet as $idx => $d) {
                //skip index 1 karena title excel
                if ($idx == 1) {
                    continue;
                }
                $desa = $this->desa->where('desa', strtoupper($d['B']))->first();
                if ($desa !== null) {
                    $data = [
                        'uid'               => Uuid::uuid4(),
                        'id_desa'           => $desa['id'],
                        'tahun'             => $d['C'],
                        'total_produksi'    => $d['D'] ?? 0,
                        'harga'             => $d['E'] ?? 0,
                        'total_pendapatan'  => $d['F'] ?? 0,
                        'created_by'        => $this->session->username
                    ];

                    $validate = $this->validation->run($data, 'penjualan');
                    $checkPenjualan = $this->db->where('id_desa', $desa['id'])
                        ->where('tahun', $d['C']);
                    if ($checkPenjualan->countAllResults() === 0) {
                        $this->db->save($data);
                    } else {
                        $this->db->where('id_desa', $desa['id'])
                            ->where('tahun', $d['C'])
                            ->set($data)
                            ->update();
                    }

                    $countSuccess++;
                } else {
                    $countError++;
                }
            }
        }
        if ($countError > 0) {
            session()->setFlashdata('error', $countError . ' Data gagal di import');
        }
        session()->setFlashdata('success', 'Berhasil import ' . $countSuccess . ' Data');
        return redirect()->to('/admin/penjualan');
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Nama Desa')
            ->setCellValue('C1', 'Tahun Produksi')
            ->setCellValue('D1', 'Total Produksi')
            ->setCellValue('E1', 'Rata-rata Harga')
            ->setCellValue('F1', 'Rata-rata Total Pendapatan')
            ->setCellValue('G1', 'Kecamatan')
            ->setCellValue('H2', '* Harap jangan mengubah format excel ini!');

        $column = 2;


        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Format Import Data Penjualan';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function grafik($year = null)
    {
        if ($year === null)
            $year = date('Y');
        $response = $this->kecamatan
            ->asObject()
            ->findAll();

        foreach ($response as $kecamatan) {

            $kecamatan->totalPendapatan = 0;
            $kecamatan->totalProduksi = 0;
            $kecamatan->totalHarga = 0;
            $totalHarga = 0;
            $iterasi = 0;
            $allDesa = $this->desa
                ->where('id_kecamatan', $kecamatan->id)
                ->asObject()
                ->findAll();

            foreach ($allDesa as $desa) {
                $desa->penjualan = $this->db
                    ->where('id_desa', $desa->id)
                    ->where('tahun', $year)
                    ->first();

                if ($desa->penjualan['harga'] !== '0') {
                    $totalHarga +=  (float) $desa->penjualan['harga'];
                    $iterasi++;
                }

                $kecamatan->totalPendapatan += (float) $desa->penjualan['total_pendapatan'];
                $kecamatan->totalProduksi += (float) $desa->penjualan['total_produksi'];
            }
            $kecamatan->totalHarga = $iterasi !== 0 ? (float) $totalHarga / $iterasi : 0;
        }

        return $this->respond($response, 200);
    }

    public function exportPdf()
    {
        $data['tahun'] = $this->request->getVar('tahun');

        $data['allKecamatan'] = $this->kecamatan
            ->orderBy('kecamatan', 'ASC')
            ->get()
            ->getResultObject();

        foreach ($data['allKecamatan'] as $kecamatan) {
            $kecamatan->allDesa = $this->desa
                ->where('id_kecamatan', $kecamatan->id)
                ->orderBy('desa', 'ASC')
                ->get()
                ->getResultObject();

            foreach ($kecamatan->allDesa as $desa) {
                $desa->penjualan = $this->db
                    ->where('id_desa', $desa->id)
                    ->where('tahun', $data['tahun'])
                    ->orderBy('created_at', 'ASC')
                    ->first();
            }
        }
        // return $this->respond($data, 200);
        // return view('admin/konten/pdfPenjualan', $data);
        $mpdf = new Mpdf(['debug' => FALSE, 'mode' => 'utf-8', 'orientation' => 'L']);
        $mpdf->WriteHTML(view('admin/konten/pdfPenjualan', $data));
        $mpdf->Output('Laporan_Data_Penjualan_Tahun_' . $data['tahun'] . '.pdf', 'I');
        exit;
    }
}
