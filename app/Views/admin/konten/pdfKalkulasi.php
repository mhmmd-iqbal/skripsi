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
                <tr style="background-color:#DCDCDC">
                    <th width="5%">No</th>
                    <th>Item</th>
                    <?php foreach ($confidence['dataKecamatan'][0]->hargaRata as $hargaRata) : ?>
                        <th><?= $hargaRata['tahun'] ?> </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($support['dataKecamatan'] as $data) :
                ?>
                    <tr>
                        <td rowspan="3"><?= $no++ ?></td>
                        <td><?= $data->kecamatan1['kecamatan'] ?> </td>
                        <?php foreach ($data->kecamatan1['hargaRata'] as $hargaRata) : ?>
                            <td style="text-align: right;">Rp. <?= number_format($hargaRata, 2, ",", ".")  ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td><?= $data->kecamatan2['kecamatan'] ?> </td>
                        <?php foreach ($data->kecamatan2['hargaRata'] as $hargaRata) : ?>
                            <td style="text-align: right;">Rp. <?= number_format($hargaRata, 2, ",", ".") ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php
                    $color = null;
                    switch ($data->resultOfPrice) {
                        case 'minus':
                            $color = 'background-color: #CD5C5C; color: white';
                            break;
                        case 'stable':
                            $color = 'background-color: #ADD8E6; color: white';
                            break;
                        case 'plus':
                            $color = 'background-color: #00FF7F; color: white';
                            break;
                    }
                    ?>
                    <tr style="<?= $color ?>">
                        <th>Rata-Rata</th>
                        <?php foreach ($data->hargaRata as $hargaRata) : ?>
                            <th style="text-align: right;">Rp. <?= number_format($hargaRata['produksi'], 2, ",", ".") ?></th>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4 style="font-weight: bold">KETERANGAN WARNA</h4>
        <table class="table table-bordered">
            <tr>
                <td style="width: 200px; background-color: #00FF7F"></td>
                <th>Harga Rata-rata Meningkat</th>
            </tr>
            <tr>
                <td style="background-color: #ADD8E6;"></td>
                <th>Harga Rata-rata Tidak Ada Perubahan</th>
            </tr>
            <tr>
                <td style="background-color: #CD5C5C;"></td>
                <th>Harga Rata-rata Menurun</th>
            </tr>
        </table>
    </div>
</body>

</html>