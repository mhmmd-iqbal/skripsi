<?php

namespace App\Controllers\Traits;

use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;

trait AlgoritmaTrait
{
    public function tableData()
    {
        // get start year from first data
        $db            = new ModelPenjualan();
        $dbDesa        = new ModelDesa();
        $dbKecamatan   = new ModelKecamatan();

        $data['tahunMulai'] = $db->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');
        $data['totalData'] = null;

        $data['kecamatan'] = $dbKecamatan
            ->asObject()
            ->findAll();

        for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
            $data['totalData'][$tahun] = 0;
        }
        // get data desa base on each kecamatan
        foreach ($data['kecamatan'] as $kecamatan) {
            // initialize total penjualan in each tahun for data kecamatan until today year
            for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
                $kecamatan->total[$tahun] = 0;
            }

            $kecamatan->support = 100;

            // get data penjualan base on each desa
            $allDesa = $dbDesa
                ->where('id_kecamatan', $kecamatan->id)
                ->asObject()
                ->findAll();

            foreach ($allDesa as $desa) {
                for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
                    $desa->penjualan['tahun'][$tahun] = $db
                        ->where([
                            'id_desa' => $desa->id,
                            'tahun' => $tahun
                        ])
                        ->first();
                    $total = (int) $desa->penjualan['tahun'][$tahun]['total_produksi'];

                    // sum each data on same year's period but difference kecamatan to kecamatan data
                    $kecamatan->total[$tahun] += $total;
                    $data['totalData'][$tahun] += $total;
                }
            }
        }
        $data['totalKecamatan'] = count($data['kecamatan']);
        return $data;
    }

    public function itemSet($data)
    {
        $data = $data;
        foreach ($data['kecamatan'] as $item) {
            $item->totalTransaksi = null;
            foreach ($item->total as $tahun => $total) {
                $item->transaksi[$tahun] = $total < 100 ? 0 : 1;
                $item->totalTransaksi     += $item->transaksi[$tahun];
            }
        }
        $result = [
            'totalKecamatan'    => $data['totalKecamatan'],
            'dataKecamatan'     => $data['kecamatan']
        ];
        return $result;
    }

    public function support($data)
    {
        $result = $data;
        foreach ($result['dataKecamatan'] as $item) {
            $item->support = $item->totalTransaksi / $result['totalKecamatan'] * 100;
        }

        $x = 0;
        $newResult = array();
        $newItem = array();
        foreach ($result['dataKecamatan'] as $d) {
            if ($d->support >= 30) {
                $newItem[$x] = $d;
                $x++;
            }
        }
        $newResult = [
            'totalKecamatan'    => $result['totalKecamatan'],
            'dataKecamatan'     => $newItem,
        ];
        return $newResult;
    }

    public function newItemSet($data)
    {
        $count = count($data['dataKecamatan']);
        $i = 0;
        foreach ($data['dataKecamatan'] as $x => $data1) {
            foreach ($data['dataKecamatan'] as $y => $data2) {
                if (
                    $x !== $y
                    && $x < $y
                    && $data1->totalTransaksi === $data2->totalTransaksi
                ) {
                    $result['dataKecamatan'][$i] = (object) [
                        'kecamatan1'    => [
                            'uid'       => $data1->uid,
                            'kecamatan' => $data1->kecamatan,
                            'totalTransaksi' => $data1->totalTransaksi
                        ],
                        'kecamatan2'    => [
                            'uid'       => $data2->uid,
                            'kecamatan' => $data2->kecamatan,
                            'totalTransaksi' => $data2->totalTransaksi
                        ],
                        'totalTransaksi'     => $data1->totalTransaksi,
                    ];
                    $i++;
                }
            }
        }
        $result['totalKecamatan'] = $data['totalKecamatan'];
        return $result;
    }

    public function confidence($data)
    {
        $data = $data;
        foreach ($data['dataKecamatan'] as $kecamatan) {
            $kecamatan->confidence = $kecamatan->totalTransaksi /  $kecamatan->kecamatan1['totalTransaksi'] * 100;
        }

        return $data;
    }
}
