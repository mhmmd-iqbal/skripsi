<html>

<head>
    <style>
        body {
            font-size: 10px;
        }

        table {
            font-size: 10px;
            border-collapse: collapse;
        }


        td,
        th {
            padding: 5px;
        }

        th {
            text-align: center
        }

        table,
        tr,
        td,
        th {
            border: 1px solid;
        }
    </style>
</head>

<body>
    <div class="group">
        <p>
            <b>Tanggal Dokumen <?= date('d M Y H:i:s') ?> </b>
        </p>
    </div>
    <div class="group">
        <table width="100%">
            <thead>
                <tr>
                    <th rowspan="3">NO</th>
                    <th colspan="9">TAHUN <?= $tahun ?></th>
                </tr>
                <tr>
                    <th style="width: 25%;" rowspan="2">KECAMATAN</th>
                    <th style="width: 30%;" rowspan="2">DESA</th>
                    <th colspan="3">DATA ITEM PENJUALAN</th>
                </tr>
                <tr>
                    <th>JUMLAH PRODUKSI (TON)</th>
                    <th>RATA-RATA HARGA</th>
                    <th>RATA-RATA PENDAPATAN</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($allKecamatan as $kecamatan) : ?>
                    <?php if (!empty($kecamatan->allDesa)) : ?>
                        <?php
                        $produksiSum = 0;
                        $hargaSum = 0;
                        $pendapatanSum = 0;
                        $jmlData = 0;
                        ?>
                        <?php foreach ($kecamatan->allDesa as $desa) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $kecamatan->kecamatan ?> </td>
                                <td><?= $desa->desa ?> </td>
                                <td align="right">
                                    <?php
                                    if ($desa->penjualan !== null) {
                                        $produksiSum += $desa->penjualan['total_produksi'];
                                        echo $desa->penjualan['total_produksi'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->penjualan !== null) {
                                        if ($desa->penjualan['harga'] !== '0') {
                                            $jmlData++;
                                        }
                                        $hargaSum += $desa->penjualan['harga'];
                                        echo 'Rp. ' . number_format($desa->penjualan['harga'], 2, '.', ',');
                                    } else {
                                        echo 'Rp. ' . 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->penjualan !== null) {
                                        $pendapatanSum += $desa->penjualan['total_pendapatan'];
                                        echo 'Rp. ' . number_format($desa->penjualan['total_pendapatan'], 2, '.', ',');
                                    } else {
                                        echo 'Rp. ' . 0;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php $no++ ?>
                        <?php endforeach; ?>
                        <tr>
                            <th colspan="3" style="text-align: left;">TOTAL</th>
                            <th style="text-align: right;"><?= $produksiSum ?></th>
                            <th style="text-align: right;">Rp. <?= $jmlData !== 0 ? number_format($hargaSum / $jmlData, 2, '.', ',') : '0,00' ?></th>
                            <th style="text-align: right;">Rp. <?= $jmlData !== 0 ? number_format($pendapatanSum / $jmlData, 2, '.', ',') : '0,00' ?></th>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</body>

</html>