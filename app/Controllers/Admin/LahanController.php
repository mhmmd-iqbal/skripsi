<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelLahan;
use CodeIgniter\API\ResponseTrait;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Uuid\Uuid;

class LahanController extends BaseController
{
    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session      = \Config\Services::session();
        $this->validation   = \Config\Services::validation();
        $this->session      = \Config\Services::session();
        $this->dbLahan      = new ModelLahan();
        $this->dbDesa       = new ModelDesa();
        $this->dbKecamatan  = new ModelKecamatan();
    }

    public function index()
    {
        $data['tahunMulai'] = $this->dbLahan->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');
        $data['judul'] = 'MASTER DATA | Data Penjualan';
        $data['username'] = $this->session->username;
        $data['active'] = 'lahan';
        $data['kecamatan'] = $this->dbKecamatan->get()->getResultObject();

        return view('admin/konten/lahan', $data);
    }

    public function new()
    {
        $data['judul']      = 'MASTER DATA | Data Penjualan';
        $data['username']   = $this->session->username;
        $data['active']     = 'penjualan';
        $data['kecamatan']  = $this->dbKecamatan->get()->getResultObject();

        return view('admin/konten/addlahan', $data);
    }

    public function create()
    {
        $id_desa    = $this->request->getVar('id_desa');
        $tahun      = $this->request->getVar('tahun');
        $tbm        = $this->request->getVar('tbm');
        $tm         = $this->request->getVar('tm');
        $ttr        = $this->request->getVar('ttr');
        $total     = $this->request->getVar('total');
        $produksi   = $this->request->getVar('produksi');
        $produktivitas   = $this->request->getVar('produktivitas');
        $jml_petani      = $this->request->getVar('jml_petani');

        $data = [
            'uid'               => Uuid::uuid4(),
            'id_desa'           => $id_desa,
            'tahun'             => $tahun,
            'tbm'               => $tbm,
            'tm'                => $tm,
            'ttr'               => $ttr,
            'total'            => $total,
            'produksi'          => $produksi,
            'produktivitas'     => $produktivitas,
            'jml_petani'        => $jml_petani,
        ];

        $validate   = $this->validation->run($data, 'lahan');
        if ($validate) {
            $this->dbLahan->save($data);
            $res    = [
                'err' => FALSE
            ];
        } else {
            $res    = [
                'err' => TRUE,
                'msg' => $this->validation->getErrors()
            ];
        }
        return $this->respond($res, 200);
    }

    function get()
    {
        $year = $this->request->getVar('year') ?? date('Y');
        $list = $this->dbLahan->get_datatables($year);
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            $aksi = '<div class="aksi">' .
                '<button class="btn btn-success btn-sm ubah" 
                    data-uid="' . $field->id . '" 
                ><i class="fa fa-edit"></i> </button>' .
                '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->id . '"><i class="fa fa-times"></i> </button>' .
                '</div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->desa;
            $row[] = $field->tahun;
            $row[] = $field->tbm;
            $row[] = $field->tm;
            $row[] = $field->ttm;
            $row[] = $field->jumlah;
            $row[] = $field->produksi;
            $row[] = $field->produktivitas;
            $row[] = $field->jml_petani;
            $row[] = date('d-m-Y H:i:s', strtotime($field->tahun));
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = array(
            "start" => $this->request->getPost('start'),
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->dbLahan->count_all($year),
            "recordsFiltered" => $this->dbLahan->count_filtered($year),
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
                $desa = $this->desa->where('desa', $d['B'])->first();
                if ($desa !== null) {
                    $tbm    = $d['D'] ?? 0;
                    $tm     = $d['E'] ?? 0;
                    $ttr    = $d['F'] ?? 0;
                    $data   = [
                        'uid'               => Uuid::uuid4(),
                        'id_desa'           => $desa['id'],
                        'tahun'             => $d['C'],
                        'tbm'               => $tbm,
                        'tm'                => $tm,
                        'ttr'               => $ttr,
                        'jumlah'            => (int) $tbm + $tm + $ttr,
                        'produksi'          => $d['G'] ?? 0,
                        'produktivitas'     => $d['H'] ?? 0,
                        'jml_petani'        => $d['I'] ?? 0,
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

                    $this->db->save($data);
                    $countSuccess++;
                } else {
                    $countError;
                }
            }
        }
        if ($countError > 0) {
            session()->setFlashdata('error', $countError . ' Data gagal di import');
        }
        session()->setFlashdata('success', 'Berhasil import ' . $countSuccess . ' Data');
        return redirect()->to('/admin/lahan');
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Nama Desa')
            ->setCellValue('C1', 'Tahun Produksi')
            ->setCellValue('D1', 'TBM')
            ->setCellValue('E1', 'TM')
            ->setCellValue('F1', 'TTM/TR')
            ->setCellValue('G1', 'Produksi (Ton)')
            ->setCellValue('H1', 'Produktivitas (Kg/Ha)')
            ->setCellValue('I1', 'Jumlah Petani (KK)')
            ->setCellValue('J1', 'Kecamatan')
            ->setCellValue('L2', '* Harap jangan mengubah format excel ini!');

        $column = 2;


        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Format Import Data Lahan Penjualan';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
