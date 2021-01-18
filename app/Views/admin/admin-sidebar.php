 <!--sidebar start-->
 <aside>
     <div id="sidebar" class="nav-collapse ">
         <!-- sidebar menu start-->
         <ul class="sidebar-menu" id="nav-accordion">
             <li>
                 <a class="<?= $active == 'dashboard' ? 'active' : '' ?>" href="/admin">
                     <i class="fa fa-home"></i>
                     <span>Dashboard</span>
                 </a>
             </li>


             <li class="sub-menu">
                 <a href="javascript:;" class="<?= $active == 'masterdata' ? 'active' : '' ?>">
                     <i class="fa fa-cogs"></i>
                     <span>Master Data</span>
                 </a>
                 <ul class="sub">
                     <li class="master" id="/admin/user"><a href="/admin/master/user">Data User</a></li>
                     <li class="master" id="/admin/kecamatan"><a href="/admin/master/kecamatan">Data Kecamatan</a></li>
                     <li class="master" id="/admin/desa"><a href="/admin/master/desa">Data Desa</a></li>
                 </ul>
             </li>

             <li>
                 <a href="/admin/penjualan" class="<?= $active == 'penjualan' ? 'active' : ''  ?>">
                     <i class="fa fa-database"></i>
                     <span>Data Penjualan Karet</span>
                 </a>
             </li>

             <!-- <li>
                 <a href="/admin/laporan" class="<?= $active == 'laporan' ? 'active' : '' ?>">
                     <i class="fa fa-table"></i>
                     <span>Laporan Data Penjualan</span>
                 </a>
             </li> -->

             <li>
                 <a href="/admin/calculate" class="<?= $active == 'calculate' ? 'active' : '' ?>">
                     <i class="fa fa-calculator"></i>
                     <span>Perhitungan Algoritma</span>
                 </a>
             </li>



             <li>
                 <a href="/admin/account" class="<?= $active == 'account' ? 'active' : '' ?>">
                     <i class="fa fa-user-circle"></i>
                     <span>My Account</span>
                 </a>
             </li>

             <li>
                 <a href="">
                     <i class="fa fa-exclamation-triangle"></i>
                     <span>Tentang Aplikasi</span>
                 </a>
             </li>

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