<div class="header_bottom sticky-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="main_menu_inner">
                    <div class="logo_sticky">
                        <a href="<?=APP_HOME?>"><img src="<?=APP_HOME?>/assets/img/logo/logo-3.png" alt=""></a>
                    </div>
                    <div class="main_menu"> 
                        <nav>  
                            <ul>

                                <?php foreach( $megas as $mega ): ?>
                                <li><a href="#"><?=$mega['CategoryName']?> <i class="fa fa-angle-down"></i></a>
                                    <ul class="mega_menu">
                                        <?php 
                                        $megaChildren = $category->FindByParent($mega['CategoryId']);
                                        foreach( $megaChildren as $mgc ):
                                        ?>
                                        <li><a href="#"><?=$mgc['CategoryName']?></a>
                                            <ul>
                                                <?php 
                                                    $childChildren = $category->FindByParent($mgc['CategoryId']);
                                                    foreach( $childChildren as $cch ):
                                                ?>
                                                <li><a href="<?=APP_HOME?>/shop.php?category=<?=$cch['CategoryId']?>"><?=$cch['CategoryName']?></a></li>
                                                    
                                                    <?php endforeach;?>
                                            </ul>
                                        </li>
                                        <?php endforeach;?>
                                    </ul>
                                </li>
                                <?php endforeach;?>
                                <li><a href="<?=APP_HOME?>/hotels/">Luxury Hotels</a></li>
                                <li><a href="<?=APP_HOME?>/cars/">Luxury Car Booking</a></li>
                                <li class="active"><a href="<?=APP_HOME?>/holidays/">Holidays</a></li>
                                <li><a href="<?=APP_HOME?>/src-adm-0/ecommerce-home.php?act=concierge">Concierge</a></li>
                                <li><a href="<?=APP_HOME?>/about.php">About</a></li>
                            </ul>  
                        </nav> 
                    </div>
                </div> 
            </div>

        </div>
    </div>
</div>