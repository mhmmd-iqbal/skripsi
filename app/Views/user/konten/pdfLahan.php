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
                    <th rowspan="2">KECAMATAN</th>
                    <th rowspan="2">DESA</th>
                    <th colspan="4">LUAS AREAL</th>
                    <th rowspan="2">PRODUKSI (TON)</th>
                    <th rowspan="2">PRODUKTIVITAS (KG/HA)</th>
                    <th rowspan="2">JUMLAH PETANI (KK)</th>
                </tr>
                <tr>
                    <th>TBM</th>
                    <th>TM</th>
                    <th>TTM/TR</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($allKecamatan as $kecamatan) : ?>
                    <?php if (!empty($kecamatan->allDesa)) : ?>
                        <?php
                        $tbmSum = 0;
                        $tmSum = 0;
                        $ttmSum = 0;
                        $jumlahSum = 0;
                        $produksiSum = 0;
                        $produktivitasSum = 0;
                        $jml_petaniSum = 0;
                        ?>
                        <?php foreach ($kecamatan->allDesa as $desa) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $kecamatan->kecamatan ?> </td>
                                <td><?= $desa->desa ?> </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $tbmSum += $desa->lahan['tbm'];
                                        echo $desa->lahan['tbm'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $tmSum += $desa->lahan['tm'];
                                        echo $desa->lahan['tm'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $ttmSum += $desa->lahan['ttm'];
                                        echo $desa->lahan['ttm'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $jumlahSum += $desa->lahan['jumlah'];
                                        echo $desa->lahan['jumlah'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $produksiSum += $desa->lahan['produksi'];
                                        echo $desa->lahan['produksi'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $produktivitasSum += $desa->lahan['produktivitas'];
                                        echo $desa->lahan['produktivitas'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($desa->lahan !== null) {
                                        $jml_petaniSum += $desa->lahan['jml_petani'];
                                        echo $desa->lahan['jml_petani'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach; ?>
                        <tr>
                            <th colspan="3" style="text-align: left;">TOTAL</th>
                            <th style="text-align: right;"><?= $tbmSum ?></th>
                            <th style="text-align: right;"><?= $tmSum ?></th>
                            <th style="text-align: right;"><?= $ttmSum ?></th>
                            <th style="text-align: right;"><?= $jumlahSum ?></th>
                            <th style="text-align: right;"><?= $produksiSum ?></th>
                            <th style="text-align: right;"><?= $produksiSum ?></th>
                            <th style="text-align: right;"><?= $jml_petaniSum ?></th>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</body>

</html>