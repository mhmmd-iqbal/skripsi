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

    .badge-info {
        background-color: #58c9f3;
        color: white;
    }

    .badge-danger {
        background-color: #FF6C60;
        color: white;
    }
</style>
<?= $this->endSection('css') ?>

<?= $this->section('konten') ?>
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-footer">
                <p class="title">Data User </p>
            </div>
            <div class="panel-body">
                <div class="filter">
                    <ul>
                        <!-- <li><a href="">All <span id="record-total"></span> |</a></li> -->
                        <!-- <li><a href="">Trash 0</a></li> -->
                    </ul>
                </div>

                <button class="btn btn-primary btn-sm" id="add-data">Tambah Data</button>
                <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 5px">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Level User</th>
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
<script>
    $('#add-data').on('click', function() {
        $('#modal-id').modal('toggle')
        $('#add').prop({
            // 'id': 'add',
            'action': baseUrl + "/admin/master/user",
            'method': 'POST'
        }).trigger('reset')
    })

    $('#simpan').on('click', function() {
        $('#add').trigger('submit')
    })

    $('#add').on('submit', function(e) {
        e.preventDefault()
        var post_url = $(this).attr("action"); //get form action url
        var method = $(this).attr("method"); //get form GET/POST method
        var form_data = new FormData(this);
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
                for (var pair of form_data.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
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

    $('#data-table').on('click', '.hapus', function() {
        let uid = $(this).data('uid')
        Swal.fire({
            title: 'Menghapus Data!',
            text: "Apa anda yakin akan menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan Hapus Data!',
            cancelButtonText: 'Batalkan'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: baseUrl + "/admin/master/user/" + uid,
                    dataType: "JSON",
                    beforeSend: function() {
                        loading()
                    },
                    success: function(response) {
                        swal.close()
                        if (response.err === false) {
                            toaster('Data Berhasil DIhapus', ' ', 'success')
                        }
                        showData()
                    }
                });
            }
        })
    })

    $('#data-table').on('click', '.ubah', function() {
        let uid = $(this).data('uid')
        let username = $(this).data('username')
        let email = $(this).data('email')
        let level = $(this).data('level')

        document.getElementById('update-username').value = username
        document.getElementById('update-email').value = email
        $('#update-level[value="' + level + '"]').prop('checked', true);

        $('#modal-update').modal('toggle')
        $('#update').prop({
            'id': 'update',
            'action': baseUrl + "/admin/master/user/" + uid,
            'method': 'POST'
        })
    });

    $('#simpan-update').on('click', function() {
        $('#update').trigger('submit')
    })

    $('#update').on('submit', function(e) {
        e.preventDefault()
        var post_url = $(this).attr("action"); //get form action url
        var method = $(this).attr("method"); //get form GET/POST method
        var form_data = new FormData(this);
        document.getElementById('update-button-hide').hidden = true
        document.getElementById('update-load-hide').hidden = false

        $.ajax({
            url: post_url,
            type: method,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function(res) {
                document.getElementById('update-button-hide').hidden = false
                document.getElementById('update-load-hide').hidden = true
                if (!res.err) {
                    toaster("Berhasil", "Data Telah Disimpan", "success")
                    showData()
                    $(this).trigger("reset");
                    return $('#modal-update').modal('toggle')
                }
                toaster("Gagal", "Gagal menyimpan data", "error")
                return console.log(res.msg)
            }
        })
    })

    $('#data-table').on('click', '.aktif', function() {
        let id = $(this).data('uid')
        $.ajax({
            type: "POST",
            url: "/admin/master/user/" + id,
            data: {
                'status': 1
            },
            dataType: "JSON",
            beforeSend: function() {
                loading()
            },
            success: function(response) {
                swal.close()
                showData()
            }
        });
    })

    $('#data-table').on('click', '.nonaktif', function() {
        let id = $(this).data('uid')
        $.ajax({
            type: "POST",
            url: "/admin/master/user/" + id,
            data: {
                'status': 0
            },
            dataType: "JSON",
            beforeSend: function() {
                loading()
            },
            success: function(response) {
                swal.close()
                showData()
            }
        });
    })

    showData()

    function showData() {
        let table = $("#data-table").DataTable({
            orderable: false,
            destroy: true,
            responsive: false,
            processing: true,
            serverSide: true,
            stateSave: true,
            order: [],

            ajax: {
                url: "/admin/master/get/user",
                type: "POST",
            },

            initComplete: function() {
                $('#record-total').html(this.api().data().length)
                // console.log(this.api().data())
            },
            dataSrc: function(response) {
                response.recordsTotal = response.info.length;
                console.log(response)
                // response.recordsFiltered = response.info.length;
                // atributte
                // response.draw = 1;
                // return response.info;
            },
            columnDefs: [{
                targets: [0],
                orderable: false,
            }, ],
        });

        // let info = table.page.info();
        // console.log(info)
    }
</script>
<?= $this->endSection('js') ?>

<?= $this->section('modal') ?>
<div class="modal fade" id="modal-id" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tambah Data User</h4>
            </div>
            <div class="modal-body">
                <form action="" class="form" method="" id="add">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="kecamatan" class="">Username</label>
                            <input type="text" placeholder="Input Username..." required id="username" name="username" class="form-control">
                        </div>
                        <div class="col-lg-7 form-group">
                            <label for="kecamatan" class="">Email</label>
                            <input type="email" required id="email" name="email" placeholder="Input Email..." class="form-control">
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="kecamatan" class="">Level User</label>
                            <br>
                            <input type="radio" id="update-level" name="level" value="admin" checked style="margin: 10px 20px;"> Admin
                            <input type="radio" id="update-level" name="level" value="kepala" style="margin: 10px 20px;"> Kepala Dinas
                            <input type="radio" id="update-level" name="level" value="user" style="margin: 10px 20px;"> Masyarakat
                        </div>
                        <div class="col-lg-7 form-group">
                            <label for="kecamatan" class="">Password</label>
                            <input type="password" required id="password" name="password" placeholder="Password..." class="form-control">
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

<div class="modal fade" id="modal-update" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data User</h4>
            </div>
            <div class="modal-body">
                <form action="" class="form" method="" id="update">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" id="_method" value="PUT" />
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="kecamatan" class="">Username</label>
                            <input type="text" placeholder="Input Username..." required id="update-username" name="username" class="form-control">
                        </div>
                        <div class="col-lg-7 form-group">
                            <label for="kecamatan" class="">Email</label>
                            <input type="email" required id="update-email" name="email" placeholder="Input Email..." class="form-control">
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="kecamatan" class="">Level User</label>
                            <br>
                            <input type="radio" id="update-level" name="level" value="admin" checked style="margin: 10px 20px;"> Admin
                            <input type="radio" id="update-level" name="level" value="kepala" style="margin: 10px 20px;"> Kepala Dinas
                            <input type="radio" id="update-level" name="level" value="user" style="margin: 10px 20px;"> Masyarakat
                        </div>
                        <div class="col-lg-7 form-group">
                            <label for="kecamatan" class="">Password</label>
                            <input type="password" id="update-password" name="password" placeholder="Password..." class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                <b><i class="fa fa-exclamation"></i> INFORMASI</b>
                                <p>Kosongkan input <b>Password</b> jika tidak ingin merubah password user</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="update-button-hide">
                <button type="button" class="btn btn-default btn-sm" id="kembali" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary btn-sm" id="simpan-update">Simpan</button>
            </div>
            <div class="modal-footer" id="update-load-hide" hidden>
                <button type="button" class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i></button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('modal') ?>