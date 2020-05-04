<div class="header_top">
    <div class="container">   
        <div class="row align-items-center">

            <div class="col-lg-6 col-md-6">
                <div class="social_icone">
                    <ul>
                        <li><a href="https://facebook.com/<?=$settings['SiteFaceBook']?>"><i class="ion-social-facebook"></i></a></li>
                        <li><a href="https://twitter.com/<?=$settings['SiteTwitter']?>"><i class="ion-social-twitter"></i></a></li>
                        <li><a href="https://instagram.com/<?=$settings['SiteInstagram']?>"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="https://youtube.com/<?=$settings['SiteYouTube']?>"><i class="fa fa-youtube"></i></a></li>
                        <li><a href="https://pata.shopping/<?=$settings['SiteRss']?>"><i class="ion-social-rss"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="top_right text-right">
                    <ul>
                        <li class="language"><a href="#"> English <i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_language">
                                 <li><a href="#"> English</a></li>
                            </ul>
                        </li>
                            <li class="currency"><a href="#"><?=$_SESSION['cry']?><i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_currency">
                                <li>
                                    <?php 
                                    if(isset($_POST['kes'])){
                                        $_SESSION['cry'] = 'KES';
                                        $util->RedirectTo($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                                    }
                                    ?>
                                    <form action="" method="post">
                                    <button style="background-color:transparent;color:white;font-size:13px;" class="btn btn-outline-default" type="submit" name="kes">KES </button>
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
                                    <button style="background-color:transparent;color:white;font-size:13px;" class="btn btn-outline-default" type="submit" name="usd">$ </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="top_links"><a href="#">My Account <i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_links">
                                <li><a href="<?=APP_HOME?>/cart.php">Checkout </a></li>
                                <li><a href="<?=APP_HOME?>/src-adm-0/">My Account </a></li>
                                <li><a href="<?=APP_HOME?>/cart.php">Shopping Cart</a></li>
                                <li><a href="<?=APP_HOME?>/src-adm-0/wishlist.php">Wishlist</a></li>
                            </ul>
                        </li> 
                    </ul>
                </div>   
            </div>
        </div>
    </div>
</div>