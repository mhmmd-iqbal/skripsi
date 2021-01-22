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
        $dbPenjualan   = new ModelPenjualan();
        $dbDesa        = new ModelDesa();
        $dbKecamatan   = new ModelKecamatan();

        $data['tahunMulai'] = $dbPenjualan->orderBy('tahun', 'ASC')->first();
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
                $kecamatan->harga[$tahun] = 0;
                $kecamatan->total[$tahun] = 0;
            }

            $kecamatan->support = 100;

            // get data penjualan base on each desa
            $allDesa = $dbDesa
                ->where('id_kecamatan', $kecamatan->id)
                ->asObject()
                ->findAll();

            foreach ($allDesa as $desa) {
                // total semua desa dalam satu kecamatan yang ada transaksi
                $harga = 0;
                for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
                    $penjualan = $dbPenjualan
                        ->where([
                            'id_desa' => $desa->id,
                            'tahun' => $tahun
                        ])
                        ->first();

                    // $db      = \Config\Database::connect();
                    // $table = $db->table('users');

                    $penjualanCount = $dbPenjualan
                        ->join('tb_desa', 'tb_desa.id = tb_penjualan.id_desa')
                        ->where([
                            'tb_desa.id_kecamatan' => $kecamatan->id,
                            'tahun' => $tahun,
                            'harga !=' => 0,
                        ])
                        ->countAllResults();

                    $penjualans = $dbPenjualan
                        ->join('tb_desa', 'tb_desa.id = tb_penjualan.id_desa')
                        ->where([
                            'tb_desa.id_kecamatan' => $kecamatan->id,
                            'tahun' => $tahun,
                            'harga !=' => 0,
                        ])
                        ->asObject()
                        ->findAll();

                    $penjualanHarga = 0;
                    foreach ($penjualans as $d) {
                        $penjualanHarga += (float) $d->harga;
                    }
                    $total = (int) $penjualan['total_produksi'];


                    // sum each data on same year's period but difference kecamatan to kecamatan data
                    if ($penjualanCount > 0) {
                        $kecamatan->harga[$tahun] = $penjualanHarga / $penjualanCount;
                    } else {
                        $kecamatan->harga[$tahun] = 0;
                    }
                    $kecamatan->total[$tahun] += $total;
                    $data['totalData'][$tahun] += $total;
                }
            }
        }
        $data['totalKecamatan'] = count($data['kecamatan']);
        return $data;
    }

    public function itemSet($data, $limit)
    {
        $data = $data;
        foreach ($data['kecamatan'] as $item) {
            $item->totalTransaksi = null;
            foreach ($item->total as $tahun => $total) {
                $item->transaksi[$tahun] = $total < $limit ? 0 : 1;
                $item->totalTransaksi     += $item->transaksi[$tahun];
            }
        }
        $result = [
            'totalKecamatan'    => $data['totalKecamatan'],
            'dataKecamatan'     => $data['kecamatan'],
        ];
        return $result;
    }

    public function support($data, $supportSearch)
    {
        $result = $data;
        foreach ($result['dataKecamatan'] as $item) {
            $item->support = $item->totalTransaksi / $result['totalKecamatan'] * 100;
        }

        $x = 0;
        $newResult = array();
        $newItem = array();
        foreach ($result['dataKecamatan'] as $d) {
            if ($d->support >= $supportSearch) {
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
        $dbPenjualan = new ModelPenjualan();
        $count = count($data['dataKecamatan']);
        $i = 0;
        if ($count > 0) {
            foreach ($data['dataKecamatan'] as $x => $data1) {
                foreach ($data['dataKecamatan'] as $y => $data2) {
                    if (
                        $x !== $y
                        && $x < $y
                        && $data1->totalTransaksi === $data2->totalTransaksi
                    ) {
                        $result['dataKecamatan'][$i] = (object) [
                            'kecamatan1'    => [
                                'id'             => $data1->id,
                                'kecamatan'      => $data1->kecamatan,
                                'totalTransaksi' => $data1->totalTransaksi,
                                'hargaRata'      => $data1->harga
                            ],
                            'kecamatan2'    => [
                                'id'             => $data2->id,
                                'kecamatan'      => $data2->kecamatan,
                                'totalTransaksi' => $data2->totalTransaksi,
                                'hargaRata'      => $data2->harga
                            ],
                            'totalTransaksi'     => $data1->totalTransaksi,
                        ];

                        foreach ($result['dataKecamatan'][$i]->kecamatan1['hargaRata'] as $tahun => $eachYear) {
                            $result['dataKecamatan'][$i]->hargaRata[$tahun] = ($result['dataKecamatan'][$i]->kecamatan1['hargaRata'][$tahun] + $result['dataKecamatan'][$i]->kecamatan2['hargaRata'][$tahun]) / 2;
                        }

                        foreach ($result['dataKecamatan'][$i]->kecamatan1['hargaRata'] as $year => $harga) {
                            if ($harga === 0) {
                                unset($result['dataKecamatan'][$i]->kecamatan1['hargaRata'][$year]);
                            }
                        }
                        foreach ($result['dataKecamatan'][$i]->kecamatan2['hargaRata'] as $year => $harga) {
                            if ($harga === 0) {
                                unset($result['dataKecamatan'][$i]->kecamatan2['hargaRata'][$year]);
                            }
                        }
                        foreach ($result['dataKecamatan'][$i]->hargaRata as $year => $harga) {
                            if ($harga === 0) {
                                unset($result['dataKecamatan'][$i]->hargaRata[$year]);
                            }
                        }
                        $i++;
                    }
                }
            }
        } else {
            $result['dataKecamatan'] = [];
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
