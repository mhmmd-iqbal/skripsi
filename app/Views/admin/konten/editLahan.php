v<?= $this->extend('admin/admin-template') ?>

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
                <p class="title">Edit Data Lahan Pada Penjualan Karet <br>Kabupaten Aceh Tenggara</p>
            </div>
            <div class="panel-body">
                <form action="<?= base_url() ?>/admin/lahan/<?= $lahan->uid ?>" method="POST" enctype="multipart/form-data" id="add">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" id="_method" value="PUT" />
                    <div class="row form-group" style="padding-left: 10%">
                        <div class="col-lg-3">
                            <label>Tahun Penjualan</label>
                            <select name="tahun" id="tahun" class="form-control select2">
                                <?php for ($i = date('Y'); $i > date('Y') - 15; $i--) : ?>
                                    <option value="<?= $i ?>" <?= $lahan->tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
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
                                    <option value="<?= $d->id ?>" <?= $lahan->id_kecamatan == $d->id ? 'selected' : '' ?>> <?= $d->kecamatan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label for="">Nama Desa</label>
                            <select name="id_desa" id="desa" class="form-control select2" disabled>
                                <option value="" selected>-- Pilih Desa --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" style="padding-left: 10%">
                        <div class="col-lg-12">
                            <label for="">Luas Areal (Ha)</label>
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="">TBM</label>
                                    <input type="number" class="form-control" min="0" value="<?= $lahan->tbm ?>" id="tbm" name="tbm">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">TM</label>
                                    <input type="number" class="form-control" min="0" value="<?= $lahan->tm ?>" id="tm" name="tm">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">TTTR/TR</label>
                                    <input type="number" class="form-control" min="0" value="<?= $lahan->ttm ?>" id="ttm" name="ttm">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">Total</label>
                                    <input type="number" readonly min="0" value="<?= $lahan->jumlah ?>" class="form-control" id="total" name="total">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12" style="padding-top: 20px;">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="">Produksi (Ton)</label>
                                    <input type="number" min="0" value="<?= $lahan->produksi ?>" class="form-control" name="produksi">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">Produktivitas (Kg/Ha)</label>
                                    <input type="number" min="0" value="<?= $lahan->produktivitas ?>" class="form-control" name="produktivitas">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">Jumlah Petani (KK)</label>
                                    <input type="number" min="0" value="<?= $lahan->jml_petani ?>" class="form-control" name="jml_petani">
                                </div>
                            </div>
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

    const idDesa = '<?= $lahan->id_desa ?>'
    const idKecamatan = '<?= $lahan->id_kecamatan ?>'

    function selectKecamatan() {
        let id = document.getElementById('kecamatan').value
        getDesaByParentId(id)
    }

    getDesaByParentId(idKecamatan)

    function getDesaByParentId(id) {
        $.ajax({
            type: "GET",
            url: "/admin/master/get/desa/" + id,
            dataType: "JSON",
            success: function(response) {
                let option = ''
                if (response.length > 0) {
                    response.forEach(d => {
                        let selected = d.id === idDesa ? "selected" : ""
                        option += '<option value="' + d.id + '" ' + selected + '  >' + d.desa + '</option>';
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
                        window.location.href = '/admin/lahan'
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


    $('#tbm').on('keyup', function() {
        let tbm = parseInt($('#tbm').val())
        let tm = parseInt($('#tm').val())
        let ttm = parseInt($('#ttm').val())

        let total = tbm + tm + ttm
        $('#total').val(total)
    });

    $('#tm').on('keyup', function() {
        let tbm = parseInt($('#tbm').val())
        let tm = parseInt($('#tm').val())
        let ttm = parseInt($('#ttm').val())

        let total = tbm + tm + ttm
        $('#total').val(total)

    });

    $('#ttm').on('keyup', function() {
        let tbm = parseInt($('#tbm').val())
        let tm = parseInt($('#tm').val())
        let ttm = parseInt($('#ttm').val())

        let total = tbm + tm + ttm
        $('#total').val(total)

    });
</script>
<?= $this->endSection('js') ?>