<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<!-- <link rel="shortcut icon" href="<?= base_url() ?>/logo/logo.png"> -->

<title><?= $judul ?></title>

<!-- Bootstrap core from main template, because original is too old one -->
<!-- <link href="<?= base_url() ?>/assets/template/main/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- Bootstrap core CSS -->
<link href="<?= base_url() ?>/assets/template/cms/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/template/cms/css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<!-- <link href="<?= base_url() ?>/assets/template/cms/assets/font-awesome/css/font-awesome.css" rel="stylesheet" /> -->
<link href="<?= base_url() ?>/assets/template/cms/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="<?= base_url() ?>/assets/template/cms/css/owl.carousel.css" type="text/css">
<!-- Custom styles for this template -->
<link href="<?= base_url() ?>/assets/template/cms/css/style.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/template/cms/css/style-responsive.css" rel="stylesheet" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
<script src="<?= base_url() ?>/assets/template/cms/js/html5shiv.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/respond.min.js"></script>
<![endif]-->

<!-- Another CSS For Plugin -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/toaster/jquery.toast.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/confirm-button/jquery-confirm.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/datatables/css/jquery.dataTables.min.css">

<style type="text/css">
    .gambar {
        max-width: 400px;
        /*width: 400px;*/
        height: auto;
        float: none;
        margin-right: auto;
        margin-left: auto;
        margin-bottom: none;
    }

    .gambar img {
        object-fit: cover;
        width: 100%;
    }

    .loader {
        border: 10px solid #f3f3f3;
        /* Light grey */
        border-top: 10px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* .sidebar .nav-item .nav-link {
padding: 0.7rem;
} */
</style>
<style type="text/css">
    #imgView {
        padding: 5px;
    }

    .loadAnimate {
        animation: setAnimate ease 2.5s infinite;
    }

    @keyframes setAnimate {
        0% {
            color: #000;
        }

        50% {
            color: transparent;
        }

        99% {
            color: transparent;
        }

        100% {
            color: #000;
        }
    }

    .custom-file-label {
        cursor: pointer;
    }
</style>
<!-- my css -->
<link rel="stylesheet" href="<?= base_url() ?>/assets/admin/admin.css">
<?= $this->renderSection('css') ?>