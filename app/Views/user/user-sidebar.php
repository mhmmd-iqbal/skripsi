 <!--sidebar start-->
 <aside>
 	<div id="sidebar" class="nav-collapse ">
 		<!-- sidebar menu start-->
 		<ul class="sidebar-menu" id="nav-accordion">
 			<li>
 				<a class="<?= $active == 'dashboard' ? 'active' : ''  ?>" href="/user">
 					<i class="fa fa-dashboard"></i>
 					<span>Dashboard</span>
 				</a>
 			</li>

 			<li>
 				<a class="<?= $active == 'profile' ? 'active' : ''  ?>" href="/user/profile">
 					<i class="fa fa-user"></i>
 					<span>Profile</span>
 				</a>
 			</li>
 			<li>
 				<a class="<?= $active == 'product' ? 'active' : ''  ?>" href="/user/product">
 					<i class="fa fa-shopping-cart"></i>
 					<span>Data Produk</span>
 				</a>
 			</li>
 			<!-- <li class="sub-menu">
				 <a href="javascript:;">
					 <i class=" fa fa-bar-chart-o"></i>
					 <span>Report</span>
				 </a>
				 <ul class="sub">
					 <li><a href="">Data Paket Sewa</a></li>
					 <li><a href="">Data User</a></li>
					 <li><a href="">Data Penyewaan Iklan</a></li>
					 <li><a href="">Invoice</a></li>
				 </ul>
			 </li> -->
 			<li>
 				<a class="<?= $active == 'account' ? 'active' : ''  ?>" href="/user/account">
 					<i class="fa fa-cogs"></i>
 					<span>My Account</span>
 				</a>
 			</li>
 			<li>
 				<a href="/logout">
 					<i class="fa fa-power-off text-danger"></i>
 					<span>Log Out</span>
 				</a>
 			</li>

 			<!--multi level menu start-->
 			<!-- <li class="sub-menu">
				 <a href="javascript:;">
					 <i class="fa fa-sitemap"></i>
					 <span>Multi level Menu</span>
				 </a>
				 <ul class="sub">
					 <li><a href="javascript:;">Menu Item 1</a></li>
					 <li class="sub-menu">
						 <a href="boxed_page.html">Menu Item 2</a>
						 <ul class="sub">
							 <li><a href="javascript:;">Menu Item 2.1</a></li>
							 <li class="sub-menu">
								 <a href="javascript:;">Menu Item 3</a>
								 <ul class="sub">
									 <li><a href="javascript:;">Menu Item 3.1</a></li>
									 <li><a href="javascript:;">Menu Item 3.2</a></li>
								 </ul>
							 </li>
						 </ul>
					 </li>
				 </ul>
			 </li> -->
 			<!--multi level menu end-->

 		</ul>
 		<!-- sidebar menu end-->
 	</div>
 </aside>
 <!--sidebar end-->