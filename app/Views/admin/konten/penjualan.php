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
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Data Penjualan Karet Tahun <span id="year"><?= date('Y') ?></span> <br>Kabupaten Aceh Tenggara</p>
            </div>
            <div class="panel-body">
                <div class="filter">
                    <ul>
                        <li><a href="">All <span id="record-total"></span> |</a></li>
                        <li><a href="">Trash 0</a></li>
                    </ul>
                </div>
                <div class="row" style="padding-bottom: 50px;">
                    <div class="col-lg-12">
                        <label for="">Cari berdasarkan</label>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <select name="" id="filter-year" class="form-control">
                                    <?php for ($year = date('Y'); $year >= $tahunMulai; $year--) : ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select name="" id="filter-kecamatan" class="form-control select2">
                                    <option value="" selected>-- Pilih Kecamatan --</option>
                                    <?php foreach ($kecamatan as $d) : ?>
                                        <option value="<?= $d->id ?>"> <?= $d->kecamatan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <button class="btn btn-info" onclick="filterData()"><i class="fa fa-search"></i> Filter Data...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (session()->getFlashdata('success')) :
                ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                endif;

                if (session()->getFlashdata('error')) :
                ?>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <br>
                        <b>
                            <a class="" id="exampleModalScrollable">Lihat Detail</a>
                        </b>
                    </div>
                <?php
                endif;
                ?>

                <div class="d-flex justify-content-lg-end">
                    <a href="/admin/penjualan/new" class="btn btn-primary btn-sm" id="">Tambah Data</a>
                    <div class="" style="float:right">
                        <button class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Cetak Excel</button>
                    </div>
                </div>
                <div class="responsive-table">
                    <table class="table table-bordered table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tahun</th>
                                <th>Kecamatan</th>
                                <th>Desa</th>
                                <th>Produksi (Ton)</th>
                                <th>Harga (Per/Kg)</th>
                                <th>Total Pendapatan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
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

    const date = new Date();
    var year = date.getFullYear();

    showData({
        year: year,
        kecamatan: null
    })

    function showData(params) {
        return table = $("#data-table").DataTable({
            orderable: false,
            destroy: true,
            responsive: false,
            processing: true,
            serverSide: true,
            stateSave: true,
            order: [],

            ajax: {
                url: "/admin/get/penjualan",
                type: "POST",
                data: {
                    'year': params.year,
                    'kecamatan': params.kecamatan
                }
            },

            initComplete: function() {
                // $('#record-total').html(this.api().data().length)
                // console.log(this.api().recordsTotal())
            },

            drawCallback: function() {
                var api = this.api();
                var num_rows = api.page.info().recordsTotal;
                var records_displayed = api.page.info().recordsDisplay;
                // now do something with those variables
                $('#record-total').html(num_rows)
            },

            columnDefs: [{
                targets: [0],
                orderable: false,
            }, ],
        });

    }
    $('#showErrorData').on('click', function() {
        $('#modalForErrorData').modal('toggle')
    })

    function filterData() {
        let year = $('#filter-year').val()
        let kecamatan = document.getElementById('filter-kecamatan').value
        $('#year').html(year)
        showData({
            year,
            kecamatan
        })
    }

    $('#data-table').on('click', '.ubah', function(e) {
        let id = $(this).data('uid')
        window.location.href = '/admin/penjualan/' + id + '/edit'
    })
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>
<div class="modal fade" id="modalForErrorData" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Daftar Data Yang Gagal Di Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('modal') ?>