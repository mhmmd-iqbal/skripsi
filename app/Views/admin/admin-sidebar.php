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
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li>
                <a class="<?= $active == 'dashboard' ? 'active' : '' ?>" href="<?= base_url() . $menu ?>">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if ($session->level === 'admin') : ?>
                <li class="sub-menu">
                    <a href="javascript:;" class="<?= $active == 'masterdata' ? 'active' : '' ?>">
                        <i class="fa fa-cogs"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub">
                        <li class="master" id="<?= base_url() . $menu ?>/user"><a href="<?= base_url() . $menu ?>/master/user">Data User</a></li>
                        <li class="master" id="<?= base_url() . $menu ?>/kecamatan"><a href="<?= base_url() . $menu ?>/master/kecamatan">Data Kecamatan</a></li>
                        <li class="master" id="<?= base_url() . $menu ?>/desa"><a href="<?= base_url() . $menu ?>/master/desa">Data Desa</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?= base_url() . $menu ?>/lahan" class="<?= $active == 'lahan' ? 'active' : ''  ?>">
                    <i class="fa fa-database"></i>
                    <span>Data Lahan Kecamatan</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url() . $menu ?>/penjualan" class="<?= $active == 'penjualan' ? 'active' : ''  ?>">
                    <i class="fa fa-database"></i>
                    <span>Data Penjualan Karet</span>
                </a>
            </li>


            <li>
                <a href="<?= base_url() . $menu ?>/calculate" class="<?= $active == 'calculate' ? 'active' : '' ?>">
                    <i class="fa fa-calculator"></i>
                    <span>Perhitungan Algoritma</span>
                </a>
            </li>



            <li>
                <a href="<?= base_url() . $menu ?>/account" class="<?= $active == 'account' ? 'active' : '' ?>">
                    <i class="fa fa-user-circle"></i>
                    <span>My Account</span>
                </a>
            </li>

            <!-- <li>
                <a href="">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Tentang Aplikasi</span>
                </a>
            </li> -->

            <li>
                <a href="/logout">
                    <i class="fa fa-power-off text-danger"></i>
                    <span>Keluar</span>
                </a>
            </li>



        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->