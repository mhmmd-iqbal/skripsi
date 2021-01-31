<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use CodeIgniter\API\ResponseTrait;
use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DesaController extends BaseController
{
    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = new ModelDesa();
        $this->kecamatan = new ModelKecamatan();
    }

    public function index()
    {
        $data['judul'] = 'MASTER DATA | DESA';
        $data['username'] = $this->session->username;
        $data['kecamatan'] = $this->kecamatan->get()->getResultObject();
        $data['active'] = 'masterdata';
        return view('admin/konten/desa', $data);
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

    public function create()
    {
        $id_kecamatan = $this->request->getVar('id_kecamatan');
        $desa = $this->request->getVar('desa', FILTER_SANITIZE_STRING);
        $data = [
            'uid'   => Uuid::uuid4(),
            'id_kecamatan' => $id_kecamatan,
            'desa' => strtoupper($desa)
        ];
        $validate = $this->validation->run($data, 'desa');
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

    public function update($uid)
    {
        $id_kecamatan = $this->request->getVar('id_kecamatan');
        $desa = $this->request->getVar('desa', FILTER_SANITIZE_STRING);
        $data = [
            'id_kecamatan' => $id_kecamatan,
            'desa' => strtoupper($desa)
        ];
        $validate = $this->validation->run($data, 'desa');
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

    function get($id = null)
    {
        if (($this->request->getMethod() == 'post') || ($this->request->getMethod() == 'POST')) {
            $list = $this->db->get_datatables();
            $data = array();
            $no = $this->request->getPost('start');
            foreach ($list as $field) {
                $aksi = '<div class="aksi">' .
                    '<button class="btn btn-success btn-sm ubah" 
                        data-kecamatan="' . $field->id_kecamatan . '" 
                        data-desa="' . $field->desa . '"  
                        data-uid="' . $field->uid . '" 
                    ><i class="fa fa-edit"></i> </button>' .
                    '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->uid . '"><i class="fa fa-times"></i> </button>' .
                    '</div>';
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->kecamatan;
                $row[] = $field->desa;
                $row[] = date('d M Y h:i:s', strtotime($field->created_at));
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "start" => $this->request->getPost('start'),
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $this->db->count_all(),
                "recordsFiltered" => $this->db->count_filtered(),
                "data" => $data,
            );
        } else {
            $output = $this->db->where('id_kecamatan', $id)->get()->getResultObject();
        }
        return $this->respond($output, 200);
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Id Desa')
            ->setCellValue('C1', 'Nama Desa')
            ->setCellValue('D1', 'Kecamatan');

        $column = 2;

        // $desa = $this->db->get()->getResultObject();
        $desa = $this->kecamatan
            ->join('tb_desa', 'tb_kecamatan.id = tb_desa.id_kecamatan')
            ->get()
            ->getResultObject();
        $no = 1;
        foreach ($desa as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $no++)
                ->setCellValue('B' . $column, $data->id)
                ->setCellValue('C' . $column, $data->desa)
                ->setCellValue('D' . $column, $data->kecamatan);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Desa';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
