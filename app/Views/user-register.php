<?= $this->extend('login/login-template') ?>
<?= $this->section('konten') ?>
<form class="login100-form validate-form" method="POST" action="/register">
    <span class="login100-form-title">
        Form Registrasi
    </span>

    <?= csrf_field(); ?>
    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
        <input class="input100 <?= $error->hasError('email') ? 'is-error' : '' ?>" type="text" name="email" placeholder="Masukkan Email Anda" value="<?= old('email')  ?>">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </span>
    </div>
    <?php if ($error->hasError('email')) : ?>
        <small class="text-danger" style="font-style: italic; margin: 0 20px"><?= $error->getError('email') ?></small>
    <?php endif; ?>

    <div class="wrap-input100 validate-input" data-validate="Nama is required">
        <input class="input100 <?= $error->hasError('name') ? 'is-error' : '' ?>" type="text" name="name" placeholder="Masukkan Nama Anda" value="<?= old('name') ?>">
        <span class=" focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-user" aria-hidden="true"></i>
        </span>
    </div>
    <?php if ($error->hasError('name')) : ?>
        <small class="text-danger" style="font-style: italic; margin: 0 20px"><?= $error->getError('name') ?></small>
    <?php endif; ?>

    <div class="wrap-input100 validate-input" data-validate="Password is required">
        <input class="input100 is-invalid <?= $error->hasError('password') ? 'is-error' : '' ?>" type="password" name="password" placeholder="Masukkan Password" value="<?= old('password')  ?>">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </span>
    </div>
    <?php if ($error->hasError('password')) : ?>
        <small class="text-danger" style="font-style: italic; margin: 0 20px"><?= $error->getError('password') ?></small>
    <?php endif; ?>

    <div class="wrap-input100 validate-input" data-validate="Password is required">
        <input class="input100 <?= $error->hasError('password_confirm') ? 'is-error' : '' ?>" type="password" name="password_confirm" placeholder="Ulangi Password" value="<?= old('password_confirm')  ?>">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </span>
    </div>
    <?php if ($error->hasError('password_confirm')) : ?>
        <small class="text-danger" style="font-style: italic; margin: 0 20px"><?= $error->getError('password_confirm') ?></small>
    <?php endif; ?>

    <label for="" class="wrap-input100">Daftar Sebagai: </label>
    <div class="wrap-input100">
        <div class="form-check form-check-inline" style="padding: 0 22px ;">
            <input class="form-check-input" type="radio" name="sign_as" id="inlineRadio2" value="customer" disabled>
            <label class="form-check-label" for="inlineRadio2">Penyewa</label>
        </div>
        <div class="form-check form-check-inline" style="padding: 0 0px ;">
            <input class="form-check-input" type="radio" name="sign_as" checked id="inlineRadio1" value="seller">
            <label class="form-check-label" for="inlineRadio1">Penyedia Jasa</label>
        </div>
        <div class="container-login100-form-btn">
            <button class="login100-form-btn">
                Daftar
            </button>
        </div>
    </div>


    <div class="text-center p-t-20">
        Sudah memiliki Akun ?
        <a class="txt2" href="/login">
            Login Disini <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
        </a>
    </div>
</form>
<?= $this->endSection('konten') ?>