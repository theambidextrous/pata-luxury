<?php
    session_start();
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    require_once 'lib/Util.php';
    require_once 'lib/BladeSMS.php';
    require_once 'lib/Setting.php';
    require_once 'lib/Product.php';
    $util = new Util();
    // $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    require_once 'lib/Category.php';
    require_once 'lib/Gallery.php';
    // require_once 'lib/Blog.php';
    // $blog = new Blog($conn);
    $category = new Category($conn);
    $product = new Product($conn);
    $gallery = new Gallery($conn);
    $megas = $category->FindAllMegaCategory();
    // $util->Show();
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
     <?php 
        print html_entity_decode(json_decode($settings['SiteGoogleCode']));
        print html_entity_decode(json_decode($settings['SiteFacebookCode']));
        print html_entity_decode(json_decode($settings['SiteYandexCode']));
    ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?=$settings['SiteName']?> | About us</title>
    <meta name="description" content="<?=$settings['SiteRichText']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <?php 
   require_once 'com/header.php';
   ?>

</head>
<body>
<!-- Main Wrapper Start -->
    <div class="home_black_version">
         <!--Offcanvas menu area start-->
        <div class="off_canvars_overlay">

        </div>
        <?php 
            // offcanvas
            require_once 'com/nav-offcan.php';
        ?>
        <!--Offcanvas menu area end-->
        
         <!--header area start-->
        <header class="header_area header_black">
            <!--header top start-->
                <?php
                    require_once 'com/nav-h-top.php';
                ?>
            <!--header middel start-->
                <?php
                    require_once 'com/nav-h-mid.php';
                ?>
            <!--header bottom satrt-->
                <?php
                    require_once 'com/nav-h-bottom.php';
                ?>
        </header>
        <!--header area end-->
        
        <!--slider area start-->
        <style>
        .breadcrumb_content h3,.about_content h1,.about_content p,.chose_title h1,.chose_content h3,.chose_content p {
            color: #ffffff;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>about us</h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li>about us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="product_section p_section1 product_black_section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">

                        <div class="about_content">
                            <h1>Welcome to <?=$settings['SiteName']?></h1>
                            <?=$util->HtmlDecode($settings['SiteAboutPage'])?>
                        </div>
                    </div>    
                </div>
            </div>
        </section>
        <!-- area end-->

         <!-- why choose us area start-->
        <section class="product_section p_section1 product_black_section bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="chose_title">
                            <h1>Why chose us?</h1>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single_chose">
                            <div class="chose_icone">
                                <img src="assets/img/about/abt_icon1.png" alt="">
                            </div>
                            <div class="chose_content">
                                <h3>AUTHENTIC</h3>
                                <p>At <?=$settings['SiteName']?>, we are committed to providing unquestionably authentic products</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single_chose">
                            <div class="chose_icone">
                                <img src="assets/img/about/abt_icon3.png" alt="">
                            </div>
                            <div class="chose_content">
                                <h3>LUXURY</h3>
                                <p><?=$settings['SiteName']?> target high-end consumers. All our products & services are for luxury consumers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single_chose">
                            <div class="chose_icone">
                                <img src="assets/img/about/abt_icon2.png" alt="">
                            </div>
                            <div class="chose_content">
                                <h3>LIFE STYLE</h3>
                                <p>Lifestyle. This is what we are all about. We are the only provider of high-end lifestlye products & services online.</p>

                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </section>
        <!-- area end--> 

        <!--blog section area start-->
        <section class="blog_section blog_black">
            <div class="container">
               
            </div>
        </section>
        <!--blog section area end-->
        
        <!-- newsletter & footer -->
        <?php 
        require_once 'com/footer.php';
        ?>
        <!--footer area end-->
   </div>
<!-- JS
============================================ -->
<!-- Plugins JS -->
<script src="assets/js/plugins.js"></script>
<script src="assets/js/wt.js"></script>
<!-- Main JS -->
<script src="assets/js/main.js"></script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>