<!DOCTYPE html>
<html lang="en">

<?= $this->include('login/login-head') ?>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt">
                    <a href="/">
                        <img src="<?= base_url() ?>/assets/login/images/img-01.png" alt="IMG">

                    </a>
                </div>

                <?= $this->renderSection('konten') ?>
            </div>
        </div>
    </div>



    <?= $this->include('login/login-script') ?>
</body>

</html>