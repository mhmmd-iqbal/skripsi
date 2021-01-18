<?= $this->extend('admin/admin-template') ?>

<?= $this->section('css') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .title {
        font-size: 15px;
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

    .nopadding {
        padding: 0 !important;
        margin: 0 !important;
    }
</style>
<?= $this->endSection('css') ?>
<?= $this->section('konten') ?>
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-10">
        <div class="panel">
            <div class="row"></div>
            <div class="panel-footer">
                <p class="title">Tambah Data Penjualan Karet <br>Kabupaten Aceh Tenggara</p>
            </div>
            <div class="panel-body">
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-lg-12">
                        <!-- <a href="/admin/export/desa" class="btn btn-white btn-sm"><i class="fa fa-download"></i> Download Data ID Desa</a> -->
                        <a href="/admin/export/penjualan" class="btn btn-white btn-sm"><i class="fa fa-download"></i> Download Format Excel</a>
                        <button class="btn btn-white btn-sm" data-toggle="modal" href='#modal-id' data-backdrop="static"><i class="fa fa-file-excel"></i> Import Dari Excel</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <b><i class="fa fa-exclamation"></i> INFORMASI</b>
                            <p>Gunakan menu <b>Import Dari Ecxel</b> untuk menambahkan data menggunakan file excel</p>
                            <p><b>Download Form Excel</b> untuk mendowload template excel</p>
                        </div>
                    </div>
                </div>
                <form action="<?= base_url() ?>/admin/penjualan" method="POST" enctype="multipart/form-data" id="add">
                    <?= csrf_field() ?>

                    <div class="row form-group" style="padding-left: 10%">
                        <div class="col-lg-3">
                            <label>Tahun Penjualan</label>
                            <select name="tahun" id="tahun" class="form-control select2">
                                <?php for ($i = date('Y'); $i > date('Y') - 15; $i--) : ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" style="padding-left: 10%">
                        <div class="col-lg-4">
                            <label>Kecamatan</label>
                            <select name="" id="kecamatan" class="form-control select2" onchange="selectKecamatan()">
                                <option value="" disabled selected>-- Pilih Kecamatan --</option>
                                <?php foreach ($kecamatan as $d) : ?>
                                    <option value="<?= $d->id ?>"> <?= $d->kecamatan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label for="">Nama Desa</label>
                            <select name="id_desa" id="desa" class="form-control select2" disabled>
                                <option value="" disabled selected>-- Pilih Desa --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" style="padding-left: 10%">
                        <div class="col-lg-3">
                            <label for="">Total Produksi (ton)</label>
                            <input type="number" name="total_produksi" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Rata-rata Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Rata-Rata Pendapatan (Rp)</label>
                            <input type="number" class="form-control" name="total_pendapatan">
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-lg-12 text-right" id="button-hide">
                        <button class="btn btn-danger btn-sm" onclick="window.location.href = '/admin/penjualan'"><i class="fa fa-chevron-left"></i> Batal dan Kembali</button>
                        <button class="btn btn-success btn-sm" id="simpan"><i class="fa fa-save"></i> Simpan Data</button>
                    </div>
                    <div class="col-lg-12 text-right" id="load-hide" hidden>
                        <button type="button" class="btn btn-primary btn-sm" disabled><i class="fa fa-spinner fa-spin"></i></button>
                    </div>
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
        getDesaByParentId(id)
    }

    function getDesaByParentId(id) {
        $.ajax({
            type: "GET",
            url: "/admin/master/get/desa/" + id,
            dataType: "JSON",
            success: function(response) {
                let option = ''
                if (response.length > 0) {
                    response.forEach(d => {
                        option += '<option value="' + d.id + '" >' + d.desa + '</option>';
                    });
                    $('#desa').html(option).attr('disabled', false)
                } else {
                    $('#desa').html('<option value="" disabled selected>Desa tidak ditemukan</option>').attr('disabled', true)
                    toaster("Perhatian", "Data desa pada kecamatan ini belum tersedia", "warning")
                }
            }
        });
    }

    $('#simpan').on('click', function() {
        $('#add').trigger('submit')
    })

    $('#add').on('submit', function(e) {
        e.preventDefault()
        var post_url = $(this).attr("action"); //get form action url
        var method = $(this).attr("method"); //get form GET/POST method
        var form_data = new FormData(this);
        var kecamatan = document.getElementById('kecamatan').value;
        document.getElementById('button-hide').hidden = true
        document.getElementById('load-hide').hidden = false
        $.ajax({
            url: post_url,
            type: method,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                if (!res.err) {
                    toaster("Berhasil", "Data Telah Disimpan", "success")
                    $(this).trigger("reset");
                    setTimeout(() => {
                        window.location.href = '/admin/penjualan'
                    }, 1500);
                } else {
                    document.getElementById('button-hide').hidden = false
                    document.getElementById('load-hide').hidden = true
                    toaster("Gagal", "Gagal menyimpan data", "error")
                }
                return console.log(res.msg)
            }
        })
    })


    $('#upload').on('click', function() {
        console.log('asd')
        $('#form-submit').trigger('submit');
    })
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>
<a class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" href='#modal-id'>Trigger modal</a>
<div class="modal fade" id="modal-id">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Upload File Excel</h4>
            </div>
            <div class="modal-body">
                <form action="/admin/import/penjualan" id="form-submit" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="">Import File Excel</label>
                        <input type="file" name="file" class="form-control" require>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="upload">Import Data</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('modal') ?>