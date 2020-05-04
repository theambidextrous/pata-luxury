<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-add-shopping-cart"></i><span class="ti-angle-double-down"></span><span> eCommerce </span></a>
                    <ul class="list-unstyled">
                        <li><a href="ecommerce-home.php"> Dashboard</a></li>
                        <li><a href="ecommerce-users.php"> Sellers/Merchants</a></li>
                        <li><a href="ecommerce-categories.php"> Product Categories</a></li>
                        <li><a href="ecommerce-product-colors.php"> Product Colors</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-hotel"></i><span class="ti-angle-double-down"></span><span> Hotel Booking </span></a>
                    <ul class="list-unstyled">
                        <li><a href="hotelbooking-users.php"> Hotel Users</a></li>
                        <li><a href="hotelbooking-categories.php"> Room Categories</a></li>
                        <li><a href="hotelbooking-packs.php"> Service Packs</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-directions-car"></i><span class="ti-angle-double-down"></span><span> Car Hire/Booking </span></a>
                    <ul class="list-unstyled">
                        <li><a href="carbooking-users.php"> Fleet Users</a></li>
                    </ul>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-filter-hdr"></i><span class="ti-angle-double-down"></span><span> Holidays/Concierge </span></a>
                    <ul class="list-unstyled">
                        <li><a href="holidayconcierge-users.php"> Service Providers</a></li>
                    </ul>
                </li>

                <?php 
                if($util->isAdmin()){
                ?>
                 <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-settings"></i><span class="ti-angle-double-down"></span><span> System Config </span></a>
                    <ul class="list-unstyled">
                        <li><a href="admin-blog.php"> Blog</a></li> 
                        <li><a href="admin-faq.php"> Faq</a></li>
                        <li><a href="admin-settings.php"> Settings</a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End --> 