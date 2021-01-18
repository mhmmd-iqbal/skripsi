<!-- js placed at the end of the document so the pages load faster -->
<script src="<?= base_url() ?>/assets/template/cms/js/jquery.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/jquery-1.8.3.min.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/bootstrap.min.js"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<script class="include" type="text/javascript" src="<?= base_url() ?>/assets/template/cms/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/jquery.scrollTo.min.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/assets/template/cms/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/owl.carousel.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/jquery.customSelect.min.js"></script>
<script src="<?= base_url() ?>/assets/template/cms/js/respond.min.js"></script>

<!--common script for all pages-->
<script src="<?= base_url() ?>/assets/template/cms/js/common-scripts.js"></script>

<!-- SCRIPT FOR PLUGIN -->
<script src="<?= base_url() ?>/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>

<script>
    //owl carousel

    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
            navigation: true,
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true

        });
    });

    //custom select box

    $(function() {
        $('select.styled').customSelect();
    });
</script>

<!-- Another Js From Outside Web -->
<script src="<?= base_url() ?>/assets/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>/assets/toaster/jquery.toast.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
    const baseUrl = '<?= base_url() ?>'

    function notif(title, text, icon) {
        swal({
            title: title,
            text: text,
            icon: icon,
            buttons: false,
            timer: 1500,
        });
    }

    function loading() {
        swal({
            title: "Memeriksa...",
            text: "Sedang diproses. Harap menunggu...",
            icon: baseUrl + "/assets/sweetalert/loader.gif",
            button: false,
            closeOnClickOutside: false
        });
    }

    function toaster(head, text, icon) {
        $.toast({
            text: text, // Text that is to be shown in the toast
            heading: head, // Optional heading to be shown on the toast
            icon: icon, // Type of toast icon
            showHideTransition: 'plain', // fade, slide or plain
            allowToastClose: true, // Boolean value true or false
            hideAfter: 4000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
            stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
            position: 'top-right',


            textAlign: 'left', // Text alignment i.e. left, right or center
            loader: false, // Whether to show loader or not. True by default
            loaderBg: '#9EC600', // Background color of the toast loader
            beforeShow: function() {}, // will be triggered before the toast is shown
            afterShown: function() {}, // will be triggered after the toat has been shown
            beforeHide: function() {}, // will be triggered before the toast gets hidden
            afterHidden: function() {} // will be triggered after the toast has been hidden
        });
    }
</script>

<?= $this->renderSection('js') ?>