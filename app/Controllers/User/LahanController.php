<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelLahan;
use CodeIgniter\API\ResponseTrait;
use Mpdf\Mpdf;
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

        return view('user/konten/lahan', $data);
    }

    function get()
    {
        $year = $this->request->getVar('year') ?? date('Y');
        $list = $this->dbLahan->get_datatables($year);
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            $aksi = '<div class="aksi">' .
                '<a href="' . base_url() . '/admin/lahan/' . $field->uid . '" class="btn btn-success btn-sm ubah" 
                    data-uid="' . $field->uid . '" 
                ><i class="fa fa-edit"></i> </a>' .
                '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->uid . '"><i class="fa fa-times"></i> </button>' .
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

    public function exportPdf()
    {
        $data['tahun'] = $this->request->getVar('tahun');

        $data['allKecamatan'] = $this->dbKecamatan
            ->orderBy('kecamatan', 'ASC')
            ->get()
            ->getResultObject();

        foreach ($data['allKecamatan'] as $kecamatan) {
            $kecamatan->allDesa = $this->dbDesa
                ->where('id_kecamatan', $kecamatan->id)
                ->orderBy('desa', 'ASC')
                ->get()
                ->getResultObject();

            foreach ($kecamatan->allDesa as $desa) {
                $desa->lahan = $this->dbLahan
                    ->where('id_desa', $desa->id)
                    ->where('tahun', $data['tahun'])
                    ->orderBy('created_at', 'ASC')
                    ->first();
            }
        }
        // return view('admin/konten/pdfLahan', $data);
        $mpdf = new Mpdf(['debug' => FALSE, 'mode' => 'utf-8', 'orientation' => 'L']);
        $mpdf->WriteHTML(view('admin/konten/pdfLahan', $data));
        $mpdf->Output('Laporan_Data_Lahan_Tahun_' . $data['tahun'] . '.pdf', 'I');
        exit;
    }
}
