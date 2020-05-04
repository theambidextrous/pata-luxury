 <!--Newsletter area start-->
 <div class="newsletter_area newsletter_black">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="newsletter_content">
                    <h2>Subscribe for Newsletter</h2>
                    <p>Get E-mail updates about our latest shop and special offers.</p>
                    <div class="subscribe_form">
                        <div class="alert alert-success" id="succ" style="display:none;"></div>
                        <div class="alert alert-danger" id="err" style="display:none;"></div><br>
                        <form id="newsletter_sub_form" class="mc-form footer-newsletter" >
                            <input id="email" name="email" type="email" autocomplete="off" placeholder="Email address..." />
                            <button onclick="subscribe_newsletter('newsletter_sub_form')" type="button">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Newsletter area start-->

<!--footer area start-->
<footer class="footer_widgets footer_black">
    <div class="container">  
        <div class="footer_top">
            <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-8">
                        <div class="widgets_container contact_us">
                            <h3>About <?=$util->CamelCase($settings['SiteName'])?></h3>
                            <div class="footer_contact">
                                <p>Address: <?=$settings['SiteAddress']?></p>
                                <p>Phone: <a href="tel:<?=$settings['SiteContact']?>"><?=$settings['SiteContact']?></a></p>
                                <p>Email: <?=$settings['SiteEmail']?></p>
                                <ul>
                                    <li><a href="https://facebook.com/<?=$settings['SiteFaceBook']?>"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/<?=$settings['SiteTwitter']?>"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="https://instagram.com/<?=$settings['SiteInstagram']?>"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="https://youtube.com/<?=$settings['SiteYouTube']?>"><i class="fa fa-youtube"></i></a></li>
                                    <li><a href="https://pata.shopping/<?=$settings['SiteRss']?>"><i class="ion-social-rss"></i></a></li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-4">
                        <div class="widgets_container widget_menu">
                            <h3>Information</h3>
                            <div class="footer_menu">
                                <ul>
                                    <li><a href="<?=APP_HOME?>/about.php">About Us</a></li>
                                    <li><a href="<?=APP_HOME?>/blog.php">blog</a></li>
                                    <li><a href="<?=APP_HOME?>/contact.php">Contact</a></li>
                                    <li><a href="<?=APP_HOME?>/faq.php">Frequently Questions</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-5">
                        <div class="widgets_container widget_menu">
                            <h3>My Account</h3>
                            <div class="footer_menu">
                                <ul>
                                    <li><a href="<?=APP_HOME?>/src-adm-0/">My Account</a></li>
                                    <li><a href="<?=APP_HOME?>/src-adm-0/wishlist.php">Wishlist</a></li>
                                    <li><a href="<?=APP_HOME?>/cart.php">Checkout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-7">
                        <div class="widgets_container product_widget">
                            <h3>Top Rated Products</h3>
                            <div class="simple_product">
                                <?php 
                                    $top_rated = $car->Featured(2);
                                    foreach( $top_rated as $tprtd ):
                                        if($car->isReady($tprtd['CarId'])){
                                ?>
                                <div class="simple_product_items">
                                    <div class="simple_product_thumb">
                                        <a href="<?=APP_HOME?>/product.php?item=<?=$tprtd['CarId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($tprtd['CarId'],'5003')['GalleryPath']?>" alt=""></a>
                                    </div>
                                    <div class="simple_product_content">
                                        <div class="product_name">
                                            <h3><a href="<?=APP_HOME?>/product.php?item=<?=$tprtd['CarId']?>"><?=$tprtd['CarName']?></a></h3>
                                        </div>
                                        <div class="product_price">
                                            <?php if($tprtd['CarDiscountValue'] > 0){?>
                                                <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($tprtd['CarPrice'])?></span>
                                            <?php } ?>
                                            <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($tprtd))?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                        }
                                    endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="footer_middel">
            <div class="row">
                <div class="col-12">
                    <div class="footer_middel_menu">
                        <ul>
                            <li><a href="<?=APP_HOME?>">Home</a></li>
                            <li><a href="<?=APP_HOME?>/shop.php?category=all">Shop</a></li>
                            <li><a href="<?=APP_HOME?>/privacy-policy.php">Privacy Policy</a></li>
                            <li><a href="<?=APP_HOME?>/terms-of-use.php">Terms Of Use</a></li>
                            <li><a href="<?=APP_HOME?>/contact.php">Contacts</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <div class="row">
                <div class="col-12">
                    <div class="copyright_area">
                        <p>Copyright &copy; <?=date('Y')?> <a href="#"><?=$settings['SiteName']?></a>  All Right Reserved.</p>
                        <img src="<?=APP_HOME?>/assets/img/icon/papyel2.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>     
</footer>