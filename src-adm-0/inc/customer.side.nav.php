<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i><span class="ti-angle-double-down"></span><span> Services </span></a>
                    <ul class="list-unstyled">
                        <li><a href="ecommerce-home.php"> Home</a></li>
                        <?php foreach( $util->UserModules() as $k => $v ): if($k != '9001'){?>
                        <li><a href="ecommerce-home.php?act=<?=$k?>"> <?=$v?></a></li>
                        <?php } endforeach; ?>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i><span class="ti-angle-double-down"></span><span> Settings </span></a>
                    <ul class="list-unstyled">
                        <li><a href="ecommerce-home.php?act=pref"> Preferences</a></li>
                        <li><a href="ecommerce-home.php?act=prof"> Profile</a></li>
                    </ul>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End --> 