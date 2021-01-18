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
    <div class="col-lg-10">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Data Desa Kabupaten Aceh Tenggara </p>
            </div>
            <div class="panel-body">
                <div class="filter">
                    <ul>
                        <li><a href="">All <span id="record-total"></span> |</a></li>
                        <li><a href="">Trash 0</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary btn-sm" id="add-data">Tambah Data</button>
                <table class="table table-bordered table-hover" id="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th width="20%">Created At</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                </table>
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
    $('#add-data').on('click', function() {
        $('#modal-id').modal('toggle')
        $('.form').prop({
            'action': baseUrl + "/admin/master/desa",
        }).trigger('reset')
        document.getElementById('_method').disabled = true
    })

    $('#simpan').on('click', function() {
        $('#add').trigger('submit')
    })

    $('#add').on('submit', function(e) {
        e.preventDefault()
        var post_url = $(this).attr("action"); //get form action url
        var method = $(this).attr("method"); //get form GET/POST method
        var form_data = new FormData(this);
        var desa = document.getElementById('desa').value;
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
                // for (var pair of form_data.entries()) {
                //     console.log(pair[0] + ': ' + pair[1]);
                // }
                document.getElementById('button-hide').hidden = false
                document.getElementById('load-hide').hidden = true
                if (!res.err) {
                    toaster("Berhasil", "Data Telah Disimpan", "success")
                    showData()
                    $(this).trigger("reset");
                    return $('#modal-id').modal('toggle')
                }
                toaster("Gagal", "Gagal menyimpan data", "error")
                return console.log(res.msg)
            }
        })
    })


    $('#data-table').on('click', '.ubah', function() {
        let data = table.row($(this).parents('tr')).data();
        let uid = $(this).data('uid')
        let desa = $(this).data('desa')
        let kecamatan = $(this).data('kecamatan')

        console.log(desa)
        console.log(kecamatan)

        $('#modal-id').modal('toggle')
        document.getElementById('desa').value = desa
        document.getElementById('id_kecamatan').value = kecamatan
        $('.form').prop({
            'action': baseUrl + "/admin/master/desa/" + uid,
        })
        document.getElementById('_method').disabled = false
    });


    showData()

    function showData() {
        return table = $("#data-table").DataTable({
            orderable: false,
            destroy: true,
            responsive: false,
            processing: true,
            serverSide: true,
            stateSave: true,
            order: [],

            ajax: {
                url: "/admin/master/get/desa",
                type: "POST",
            },

            initComplete: function() {
                // console.log(this.api().state())
                // console.log(this.api().data())
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
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>
<div class="modal fade" id="modal-id" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tambah Data Desa</h4>
            </div>
            <div class="modal-body">
                <form action="" class="form" method="POST" id="add">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" id="_method" value="PUT" disabled />
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <label for="">Kecamatan</label>
                            <select class="form-control select2" style="width: 100%;" name="id_kecamatan" id="id_kecamatan">
                                <?php foreach ($kecamatan as $d) : ?>
                                    <option value="<?= $d->id ?>"><?= $d->kecamatan ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-lg-7 col-md-7">
                            <label for="desa" class="">Nama Desa</label>
                            <input type="text" required id="desa" name="desa" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="button-hide">
                <button type="button" class="btn btn-default btn-sm" id="kembali" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary btn-sm" id="simpan">Simpan</button>
            </div>
            <div class="modal-footer" id="load-hide" hidden>
                <button type="button" class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i></button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('modal') ?>