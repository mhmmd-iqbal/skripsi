<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('user/user-css') ?>
</head>

<body>

    <section id="container">
        <?= $this->include('user/user-header') ?>
        <?= $this->include('user/user-sidebar') ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <?= $this->renderSection('konten') ?>
            </section>
        </section>
        <?= $this->renderSection('modal') ?>
        <!--main content end-->
        <?php //echo $this->include('user/user-footer')
        ?>
    </section>


    <?= $this->include('user/user-js') ?>

</body>

</html>