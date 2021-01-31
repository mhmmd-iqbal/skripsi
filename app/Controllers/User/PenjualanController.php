<?php

namespace App\Controllers\User;

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

        return view('user/konten/penjualan', $data);
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
