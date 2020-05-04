<div class="header_middel">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-4">
                <div class="home_contact">
                    <div class="contact_icone">
                        <img src="<?=APP_HOME?>/assets/img/icon/icon_phone.png" alt="">
                    </div>
                    <div class="contact_box">
                        <p>Free Support: <a href="tel:<?=$settings['SiteContact']?>"><?=$settings['SiteContact']?></a> </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="logo">
                    <a href="<?=APP_HOME?>"><img src="<?=APP_HOME?>/assets/img/logo/logo-3.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-5 col-md-9">
                <div class="middel_right" id="cart_items_refresh_div">
                    <div class="search_btn">
                        <a href="#"><i class="ion-ios-search-strong"></i></a>
                        <div class="dropdown_search">
                            <form action="#">
                                <input placeholder="Search product..." type="text">
                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="wishlist_btn">
                        <a href="<?=APP_HOME?>/src-adm-0/wishlist.php"><i class="ion-heart"></i></a>
                    </div>
                    <?php 
                    $full_cart_totals = [0];
                    foreach($_SESSION['curr_usr_cart'] as $c_item ):
                        $t_item_meta = $product->FindById($c_item[0]);
                        $t_item_cost = ($util->ApplyDiscount($t_item_meta)*$c_item[1]);
                        array_push($full_cart_totals, $t_item_cost);
                    endforeach;
                    ?>
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
                                    <a href="<?=APP_HOME?>/cart.php">View all in cart</a>
                                </div>
                                <div class="cart_button checkout">
                                    <a href="<?=APP_HOME?>/cart.php">Checkout</a>
                                </div>

                            </div>

                        </div>
                        <!--mini cart end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>