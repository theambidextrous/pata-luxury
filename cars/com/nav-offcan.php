<div class="Offcanvas_menu Offcanvas_five">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="canvas_open">
                    <a href="javascript:void(0)"><i class="ion-navicon"></i></a>
                </div>
                <div class="Offcanvas_menu_wrapper">
                    <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="ion-android-close"></i></a>  
                    </div>

                    <div class="top_right text-right">
                        <ul>
                            <li class="language"><a href="#"> English <i class="ion-chevron-down"></i></a>
                                <ul class="dropdown_language">
                                    <li><a href="#"> English</a></li>
                                    <!-- <li><a href="#">Germany</a></li>
                                    <li><a href="#">Japanese</a></li> -->
                                </ul>
                            </li>
                                <li class="currency"><a href="#"><?=$_SESSION['cry']?> <i class="ion-chevron-down"></i></a>
                                <ul class="dropdown_currency">
                                    <li>
                                        <?php 
                                        if(isset($_POST['kes'])){
                                            $_SESSION['cry'] = 'KES';
                                            $util->RedirectTo($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                                        }
                                        ?>
                                        <form action="" method="post">
                                        <button style="background-color:transparent;color:black;font-size:13px;" class="btn btn-outline-default" type="submit" name="kes">KES </button>
                                        </form>
                                    </li>
                                    <li>
                                        <?php 
                                        if(isset($_POST['usd'])){
                                            $_SESSION['cry'] = '$';
                                            $util->RedirectTo($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                                        }
                                        ?>
                                        <form action="" method="post">
                                        <button style="background-color:transparent;color:black;font-size:13px;" class="btn btn-outline-default" type="submit" name="usd">$ </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <li class="top_links"><a href="#">My Account <i class="ion-chevron-down"></i></a>
                                <ul class="dropdown_links">
                                    <li><a href="<?=APP_HOME?>/checkout.php">Checkout </a></li>
                                    <li><a href="<?=APP_HOME?>/src-adm-0/">My Account </a></li>
                                    <li><a href="<?=APP_HOME?>/cart.php">Shopping Cart</a></li>
                                    <li><a href="<?=APP_HOME?>/src-adm-0/wishlist.php">Wishlist</a></li>
                                </ul>
                            </li> 
                        </ul>
                    </div> 
                    <div class="contact_box">
                        <p>Free Support: <a href="tel:<?=$settings['SiteContact']?>"><?=$settings['SiteContact']?></a> </p>
                    </div>
                    <div class="middel_right" id="cart_items_refresh_div_mob">
                        <div class="search_btn">
                            <a href="#"><i class="ion-ios-search-strong"></i></a>
                            <div class="dropdown_search">
                                <form action="#">
                                    <input placeholder="Search product..." type="text">
                                    <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                </form>
                            </div>
                        </div>
                        <?php 
                            $full_cart_totals = [0];
                            foreach($_SESSION['curr_usr_cart'] as $c_item ):
                                $t_item_meta = $product->FindById($c_item[0]);
                                $t_item_cost = ($util->ApplyDiscount($t_item_meta)*$c_item[1]);
                                array_push($full_cart_totals, $t_item_cost);
                            endforeach;
                            ?>
                        <div class="wishlist_btn">
                            <a href="<?=APP_HOME?>/src-adm-0/wishlist.php"><i class="ion-heart"></i></a>
                        </div>
                        <div class="cart_link">
                            <a href="#"><i class="ion-android-cart"></i><?=$_SESSION['cry'].' ' .$util->Forex(array_sum($full_cart_totals))?> <i class="fa fa-angle-down"></i></a>
                            <span class="cart_quantity"><?=count($_SESSION['curr_usr_cart'])?></span>
                            <!--mini cart-->
                                <div class="mini_cart">
                                <div class="mini_cart_inner"> 
                                    <?php 
                                    $total_cart = [0];
                                    if(isset($_SESSION['curr_usr_cart']) && is_array($_SESSION['curr_usr_cart'])){
                                        $looper = 0;
                                        foreach($_SESSION['curr_usr_cart'] as $cart_item ):
                                            if($looper < 3 ){
                                            $this_item_meta = $product->FindById($cart_item[0]);
                                            $this_item_cost = ($util->ApplyDiscount($this_item_meta)*$cart_item[1]);
                                            array_push($total_cart, $this_item_cost);
                                    ?>
                                    <div class="cart_item">
                                        <div class="cart_img">
                                            <a href="<?=APP_HOME?>/product.php?item=<?=$cart_item[0]?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($cart_item[0],'5003')['GalleryPath']?>" alt=""></a>
                                        </div>
                                        <div class="cart_info">
                                            <a href="<?=APP_HOME?>/product.php?item=<?=$cart_item[0]?>"><?=$this_item_meta['ProductName']?></a>

                                            <span class="quantity">Qty: <?=$cart_item[1]?></span>
                                            <span class="price_cart"><?=$_SESSION['cry'] .' '. $util->Forex($this_item_cost)?></span>

                                        </div>
                                        <div class="cart_remove">
                                            <a onclick="removeFromCart('<?=$cart_item[0]?>')"><i class="ion-android-close"></i></a>
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        $looper++;
                                        endforeach;
                                    }
                                    ?>
                                    <div class="cart_total">
                                        <span>Subtotal:</span>
                                        <span><?=$_SESSION['cry'] .' '. $util->Forex(array_sum($total_cart))?></span>
                                    </div>
                                </div> 
                                <div class="mini_cart_footer">
                                    <div class="cart_button view_cart">
                                        <a href="<?=APP_HOME?>/cart.php">View cart</a>
                                    </div>
                                    <div class="cart_button checkout">
                                        <a href="<?=APP_HOME?>/cart.php">Checkout</a>
                                    </div>

                                </div>

                            </div>
                            <!--mini cart end-->
                        </div>
                    </div>
                    <div id="menu" class="text-left ">
                        <ul class="offcanvas_main_menu">
                            <li class="menu-item-has-children active">
                                <a href="<?=APP_HOME?>">Home</a>
                            </li>
                            <?php foreach( $megas as $megao ): ?>   
                                <li class="menu-item-has-children">
                                    <a href="#"><?=$megao['CategoryName']?></a>
                                    <ul class="sub-menu">
                                    <?php 
                                        $megaChildreno = $category->FindByParent($megao['CategoryId']);
                                        foreach( $megaChildreno as $mgco ):
                                    ?>
                                        <li class="menu-item-has-children">
                                            <a href="#"><?=$mgco['CategoryName']?></a>
                                            <ul class="sub-menu">
                                                <?php 
                                                    $childChildreno = $category->FindByParent($mgco['CategoryId']);
                                                    foreach( $childChildreno as $ccho ):
                                                ?>
                                                <li><a href="<?=APP_HOME?>/shop.php?category=<?=$ccho['CategoryId']?>"><?=$ccho['CategoryName']?></a></li>
                                                <?php endforeach;?>
                                            </ul>
                                        </li>
                                    <?php endforeach;?>
                                    </ul>
                                </li>
                            <?php endforeach;?>
                            
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/hotels/">Luxury Hotels</a></li>
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/cars/">Luxury Car Booking</a></li>
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/holidays/">Holidays</a></li>
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/concierge/">Concierge</a></li>
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/about.php">About</a></li>
                            <li class="menu-item-has-children"><a href="<?=APP_HOME?>/contact.php">Contacts</a></li>
                        </ul>
                    </div>
                    <div class="Offcanvas_footer">
                        <span><a href="#"><i class="fa fa-envelope-o"></i> <?=$settings['SiteEmail']?></a></span>
                        <ul>
                            <li class="facebook"><a href="https://facebook.com/<?=$settings['SiteFaceBook']?>"><i class="fa fa-facebook"></i></a></li>
                            <li class="twitter"><a href="https://twitter.com/<?=$settings['SiteTwitter']?>"><i class="fa fa-twitter"></i></a></li>
                            <li class="pinterest"><a href="https://instagram.com/<?=$settings['SiteInstagram']?>"><i class="fa fa-instagram"></i></a></li>
                            <li class="linkedin"><a href="https://pata.shopping/<?=$settings['SiteRss']?>"><i class="ion-social-rss"></i></a></li>
                            <li class="google-plus"><a href="https://youtube.com/<?=$settings['SiteYouTube']?>"><i class="fa fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>