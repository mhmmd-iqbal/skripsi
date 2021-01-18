<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('admin/admin-css') ?>
</head>

<body>

    <section id="container">
        <?= $this->include('admin/admin-header') ?>
        <?= $this->include('admin/admin-sidebar') ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ">
                <?= $this->renderSection('konten') ?>
            </section>
        </section>
        <!--main content end-->
        <?php //echo $this->include('admin/admin-footer')
        ?>
    </section>

    <?= $this->renderSection('modal') ?>
    <?= $this->include('admin/admin-js') ?>

</body>

</html>