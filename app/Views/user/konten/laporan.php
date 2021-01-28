<?= $this->extend('admin/admin-template') ?>

<?= $this->section('css') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

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
<div class="row state-overview">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Laporan Penjualan Karet Kabupaten Aceh Utara</p>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4">
                        <select name="" class="form-control select2" id="kecamatan" onchange="selectKecamatan()">
                            <?php foreach ($kecamatan as $d) : ?>
                                <option value="<?= $d->id ?>" <?= $id == $d->id ? 'selected' : '' ?>> Kecamatan <?= $d->kecamatan ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-8 text-right">
                        <button class="btn btn-white btn-sm"><i class="fa fa-print"></i> Cetak Dokumen</button>
                    </div>
                </div>
                <div class="responsive-table">
                    <table class="table table-bordered table-hover" id="">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%">No</th>
                                <th rowspan="2">Desa</th>
                                <th colspan="<?= (date('Y') - $tahunMulai) + 1 ?>"> Produksi Ton dan Harga</th>
                            </tr>
                            <tr>
                                <?php for ($year = $tahunMulai; $year <= date('Y'); $year++) : ?>
                                    <th><?= $year ?> </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data as $d) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $d['desa'] ?></td>
                                    <?php
                                    for ($year = $tahunMulai; $year <= date('Y'); $year++) :
                                        foreach ($d['tahun'] as $ii => $dd) :
                                            if ($ii == $year) :
                                    ?>
                                                <td><?= $dd['produksi'] ?> Ton / <?= "Rp " . number_format($dd['harga'], 2, ',', '.') ?> </td>
                                    <?php
                                            endif;
                                        endforeach;
                                    endfor;
                                    ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>

                                <?php foreach ($tahun as $iii => $ddd) : ?>
                                    <th><?= $ddd['produksi'] ?> Ton </th>
                                <?php endforeach; ?>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('konten') ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    $('.select2').select2({
        allowClear: true
    })

    function selectKecamatan() {
        let id = document.getElementById('kecamatan').value
        window.location.href = '/admin/laporan?id=' + id
    }
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>

<?= $this->endSection('modal') ?>