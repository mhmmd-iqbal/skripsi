<?= $this->extend('admin/admin-template') ?>

<?= $this->section('css') ?>
<style>
    .title {
        font-size: 2.5vmin;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }

    table {
        margin: 10px 0;
    }

    thead tr th {
        text-align: center;
    }

    .filter {
        margin: 0 0 10px 0;
    }

    .filter ul li {
        display: inline;
    }

    .aksi {
        text-align: center;
    }

    .aksi button {
        margin-right: 5px;
    }
</style>
<?= $this->endSection('css') ?>

<?= $this->section('konten') ?>
<!--state overview start-->
<!-- <div class="d-flex justify-content-center"></div> -->
<div class="row">
    <div class="col-lg-8" align="center">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Perhitungan Algoritma Apriori</p>
            </div>
            <div class="panel-body" style="margin-left: 10%;">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-lg-5 form-group">
                            <label for="">Batas Nilai Support Dicari (%)</label>
                            <input type="number" min="0" max="100" value="<?= $supportSearch === 0 ? '' : $supportSearch ?>" name="support" id="support" class="form-control">
                        </div>
                        <div class="col-lg-5 form-group">
                            <label for="">Batas Ambang Data</label>
                            <input type="number" min="0" value="<?= $limitSearch === 0 ? '' : $limitSearch ?>" name="limit" id="limit" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 form-group">
                            <button class="btn btn-success btn-block" type="submit"> <i class="fa fa-search"></i> Mulai Analisa Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row state-overview">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Data Set Awal</p>
            </div>
            <div class="panel-body">
                <br>
                <div class="responsive-table">
                    <table class="table table-bordered table-hover" id="">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%">No</th>
                                <th rowspan="2">Kecamatan</th>
                                <th colspan="<?= (date('Y') - $raw['tahunMulai']) + 1 ?>"> Produksi Ton dan Harga</th>
                            </tr>
                            <tr>
                                <?php for ($year = $raw['tahunMulai']; $year <= date('Y'); $year++) : ?>
                                    <th><?= $year ?> </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($raw['kecamatan'] as $i => $d) :
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><a href="kecamatan/<?= $d->uid ?>" class="text-success"><?= $d->kecamatan ?></a> </td>
                                    <?php
                                    for ($year = $raw['tahunMulai']; $year <= date('Y'); $year++) :
                                        foreach ($d->total as $ii => $dd) :
                                            if ($ii == $year) :
                                    ?>
                                                <td><?= $dd ?> </td>
                                    <?php
                                            endif;
                                        endforeach;
                                    endfor;
                                    ?>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php if ($analist === true) : ?>

    <div class="row state-overview">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-footer">
                    <p class="title">Tabular Data</p>
                </div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <table class="table table-bordered table-hover" id="">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Item</th>
                                    <?php for ($year = $raw['tahunMulai']; $year <= date('Y'); $year++) : ?>
                                        <th><?= $year ?> </th>
                                    <?php endfor; ?>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($itemSet['dataKecamatan'] as $item) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?> </td>
                                        <td><?= $item->kecamatan ?> </td>
                                        <?php foreach ($item->transaksi as $itemValue) { ?>
                                            <td><?= $itemValue ?> </td>
                                        <?php } ?>
                                        <td><?= $item->totalTransaksi ?> </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row state-overview">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-footer">
                    <p class="title">1-Itemset</p>
                </div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <table class="table table-bordered table-hover" id="">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Item</th>
                                    <?php for ($year = $raw['tahunMulai']; $year <= date('Y'); $year++) : ?>
                                        <th><?= $year ?> </th>
                                    <?php endfor; ?>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // if (count($itemSet['dataKecamatan'] !== 0)) :
                                foreach ($itemSet['dataKecamatan'] as $item) :
                                    if ($item->support > 30) :
                                ?>
                                        <tr>
                                            <td><?= $no++ ?> </td>
                                            <td><?= $item->kecamatan ?> </td>
                                            <?php foreach ($item->transaksi as $itemValue) { ?>
                                                <td><?= $itemValue ?> </td>
                                            <?php } ?>
                                            <td><?= $item->totalTransaksi ?> </td>
                                        </tr>
                                <?php
                                    endif;
                                endforeach;
                                // endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row state-overview">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-footer">
                    <p class="title">Data Mining Algoritma Apriori</p>
                </div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <table class="table table-bordered table-hover" id="">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Item</th>
                                    <th>Transaksi</th>
                                    <th>Support</th>
                                    <th>Confidence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($support['dataKecamatan'] as $data) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <ul>
                                                <?= $data->kecamatan1['kecamatan'] ?>, <?= $data->kecamatan2['kecamatan'] ?>
                                            </ul>
                                        </td>
                                        <td><?= $data->totalTransaksi ?> </td>
                                        <td><?= $data->support ?> % </td>
                                        <td><?= $data->confidence ?> % </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row state-overview">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-footer">
                    <p class="title">Prediksi Rata-rata Harga</p>
                </div>
                <div class="panel-body">
                    <button onclick="cetakKalkulasi()" class="btn btn-success"><i class="fa fa-print"></i> Cetak Data</button>
                    <div class="responsive-table">
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Item</th>
                                    <?php if (!empty($confidence['dataKecamatan'][0])) : ?>
                                        <?php foreach ($confidence['dataKecamatan'][0]->hargaRata as $hargaRata) : ?>
                                            <th><?= $hargaRata['tahun'] ?> </th>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
                                            <td>Rp. <?= number_format($hargaRata, 2, ",", ".")  ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td><?= $data->kecamatan2['kecamatan'] ?> </td>
                                        <?php foreach ($data->kecamatan2['hargaRata'] as $hargaRata) : ?>
                                            <td>Rp. <?= number_format($hargaRata, 2, ",", ".") ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php
                                    $color = null;
                                    switch ($data->resultOfPrice) {
                                        case 'minus':
                                            $color = 'background-color: #B22222; color: white';
                                            break;
                                        case 'stable':
                                            $color = 'background-color: #1E90FF; color: white';
                                            break;
                                        case 'plus':
                                            $color = 'background-color: #FFD700; color: white';
                                            break;
                                    }
                                    ?>
                                    <tr style="<?= $color ?>">
                                        <th>Rata-Rata</th>
                                        <?php foreach ($data->hargaRata as $hargaRata) : ?>
                                            <th>Rp. <?= number_format($hargaRata['produksi'], 2, ",", ".") ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <h4 style="font-weight: bold">KETERANGAN WARNA</h4>
                        <table class="table table-bordered">
                            <tr>
                                <td style="background-color: #FFD700"></td>
                                <th>Harga Rata-rata Meningkat</th>
                            </tr>
                            <tr>
                                <td style="background-color: #1E90FF;"></td>
                                <th>Harga Rata-rata Tidak Ada Perubahan</th>
                            </tr>
                            <tr>
                                <td style="background-color: #B22222;"></td>
                                <th>Harga Rata-rata Menurun</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<?= $this->endSection('konten') ?>

<?= $this->section('js') ?>
<script>
    function cetakKalkulasi() {
        let limit = $('#limit').val()
        let support = $('#support').val()
        let url = '/user/pdf/calculate?limit=' + limit + '&support=' + support
        // $.ajax({
        //     type: "GET",
        //     url: url,
        //     dataType: "JSON",
        //     beforeSend: function() {
        //         loading()
        //     },
        //     complete: function() {
        //         swal.close()
        //     }
        // });
        window.location.href = url
    }
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>

<?= $this->endSection('modal') ?>