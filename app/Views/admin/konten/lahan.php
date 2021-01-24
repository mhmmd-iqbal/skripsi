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
</style>
<?= $this->endSection('css') ?>

<?= $this->section('konten') ?>
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Data Lahan Pada Penjualan Kabupaten Aceh Tenggara </p>
            </div>
            <div class="panel-body">
                <div class="filter">
                    <ul>
                        <li><a href="">All <span id="record-total"></span> |</a></li>
                        <li><a href="">Trash 0</a></li>
                    </ul>
                </div>
                <div class="row">
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

                <div class="row">
                    <div class="col-lg-6">
                        <a href="/admin/lahan/new" class="btn btn-primary btn-sm" id="">Tambah Data</a>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button class="btn btn-danger btn-sm"><i class="fa fa-file-excel"></i> Export Data</button>
                    </div>
                </div>
                <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 5px">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Desa</th>
                                    <th>Tahun</th>
                                    <th>TBM</th>
                                    <th>TM</th>
                                    <th>TTM</th>
                                    <th>Jumlah</th>
                                    <th>Produksi (Ton)</th>
                                    <th>Produktivitas (Kg/Ha)</th>
                                    <th>Jumlah Petani (KK)</th>
                                    <th width="20%">Created At</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
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
                url: "/admin/get/lahan",
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
        window.location.href = '/admin/lahan/' + id + '/edit'
    })
</script>
<?= $this->endSection('js') ?>