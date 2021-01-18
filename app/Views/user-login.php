<?= $this->extend('login/login-template') ?>
<?= $this->section('konten') ?>
<form class="login100-form validate-form" method="POST" action="/login">
    <?php if ($error != NULL) : ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <small><?= $error ?></small>
        </div>
    <?php endif; ?>
    <?php if ($success != NULL) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <small><?= $success ?></small>
        </div>
    <?php endif; ?>
    <span class="login100-form-title">
        Member Login
    </span>

    <?= csrf_field(); ?>
    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
        <input class="input100" type="text" name="email" placeholder="Email" value="<?= old('email') ?>">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </span>
    </div>

    <div class="wrap-input100 validate-input" data-validate="Username is required">
        <input class="input100" type="text" name="username" placeholder="Username" value="<?= old('username') ?>">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-user" aria-hidden="true"></i>
        </span>
    </div>

    <div class="wrap-input100 validate-input" data-validate="Password is required">
        <input class="input100" type="password" name="password" placeholder="Password">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </span>
    </div>
    <div class="wrap-input100">
        <div class="form-check form-check-inline" style="padding: 0 22px ;">
            <input class="form-check-input" type="radio" name="login_as" id="inlineRadio2" value="customer" checked>
            <label class="form-check-label" for="inlineRadio2">Penyewa</label>
        </div>
        <div class="form-check form-check-inline" style="padding: 0 0px ;">
            <input class="form-check-input" type="radio" name="login_as" id="inlineRadio1" value="seller">
            <label class="form-check-label" for="inlineRadio1">Penyedia Jasa</label>
        </div>
        <div class="container-login100-form-btn">
            <button class="login100-form-btn">
                Login
            </button>
        </div>
    </div>


    <div class="text-center p-t-40">
        Belum memiliki Akun ?
        <a class="txt2" href="/register">
            Daftar Disini <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
        </a>
    </div>
</form>
<?= $this->endSection('konten') ?>