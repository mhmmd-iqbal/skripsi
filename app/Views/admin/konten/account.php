<?php
$session = \Config\Services::session();
switch ($session->level) {
    case 'admin':
        $menu = '/admin';
        break;
    case 'kepala':
        $menu = '/user';
        break;
    default:
        break;
}
?>
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
                <p class="title">My Profile</p>
            </div>
            <form action="<?= base_url() . $menu ?>/account/<?= $user->id ?>" method="POST" enctype="multipart/form-data" id="add">
                <div class="panel-body">
                    <div class="group" style="border: 1px solid #eee; border-radius: 5px; margin: 20px 5px; padding: 10px">
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
                        ?>
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" id="_method" value="PUT" />
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="kecamatan" class="">Username</label>
                                <input type="text" placeholder="Input Username..." required id="update-username" value="<?= $user->username ?>" name="username" class="form-control">
                            </div>
                            <div class="col-lg-7 form-group">
                                <label for="kecamatan" class="">Email</label>
                                <input type="email" required id="update-email" name="email" placeholder="Input Email..." value="<?= $user->email ?>" class="form-control">
                            </div>
                            <?php if ($session->level === 'admin') : ?>
                                <div class="col-lg-5 form-group">
                                    <label for="kecamatan" class="">Level User</label>
                                    <br>
                                    <input type="radio" id="update-level" name="level" value="admin" <?= $user->level == 'admin' ? 'checked' : '' ?> style="margin: 10px 20px;"> Admin
                                    <input type="radio" id="update-level" name="level" value="user" <?= $user->level == 'kepala' ? 'checked' : '' ?> style="margin: 10px 20px;"> Kepala Dinas
                                    <input type="radio" id="update-level" name="level" value="user" <?= $user->level == 'user' ? 'checked' : '' ?> style="margin: 10px 20px;"> User
                                </div>
                            <?php else : ?>
                                <input type="hidden" id="update-level" name="level" value="<?= $user->level ?>">
                            <?php endif; ?>
                            <div class="col-lg-7 form-group">
                                <label for="kecamatan" class="">Password</label>
                                <input type="password" id="update-password" name="password" placeholder="Password..." class="form-control">
                            </div>
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
                    <div class="row">
                        <div class="col-lg-12 text-right" id="button-hide">
                            <button class="btn btn-danger btn-sm" id="reset"><i class="fa fa-refresh"></i> Batal</button>
                            <button class="btn btn-success btn-sm" id="simpan"><i class="fa fa-save"></i> Simpan Data</button>
                        </div>
                    </div>
                </div>
            </form>
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
</script>
<?= $this->endSection('js') ?>