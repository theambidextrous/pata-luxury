
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
    require_once 'lib/Faq.php';
    $faq = new Faq($conn);
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
    <title><?=$settings['SiteName']?> | Frequently Asked Questions</title>
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
        .breadcrumb_content h3,.faq_content_wrapper h4,.faq_content_wrapper p {
            color: #ffffff;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        .contact_message ul li {
            border-top: 1px solid #444444;
        }
        .contact_message ul li {
            color: white;
        }
        .contact_message ul li a:hover {
            color: #fff;
        }
        .card-header.card_accor button.btn-link.collapsed {
            background: #444444;
            border: 1px solid #797575;
            color: #fff;
        }
        .card {
            background-color: transparent!important;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>Frequently Asked Questions</h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li>Frequently Asked Questions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="faq_content_area product_black_section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="faq_content_wrapper">
                            <h4>Below are frequently asked questions, you may find the answer for yourself</h4>
                            <p>These are some of the most common question and/or inquiries from our visitors. Most have found this helpful and sufficient to sort their issues out. We continually update the list as new queries arise. If this is not helpfull, do not worry, just proceed to contact page and talk to us directly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- area end-->
        <!--Accordion area-->
        <div class="accordion_area product_black_section">
            <div class="container">
                <div class="row">
                <div class="col-12"> 
                    <div id="accordion" class="card__accordion">
                    <?php 
                        $faqs = $faq->FindAll();
                        $loop = 0;
                        foreach ( $faqs as $fq ):
                            $class = 'collapse show';
                            $exp = 'true';
                            $btnn = '';
                            if($loop > 0){
                                $class = 'collapse';
                                $exp = 'false';
                                $btnn = 'collapsed';
                            }
                    ?>
                    <div class="card card_dipult">
                        <div class="card-header card_accor" id="headingOne<?=$loop?>">
                            <button class="btn btn-link <?=$btnn?>" data-toggle="collapse" data-target="#collapseOne<?=$loop?>" aria-expanded="true" aria-controls="collapseOne<?=$loop?>">
                            <?=$fq['Question']?>
                            <i class="fa fa-plus"></i>
                            <i class="fa fa-minus"></i>

                            </button>

                        </div>

                        <div id="collapseOne<?=$loop?>" class="<?=$class?>" aria-labelledby="headingOne<?=$loop?>" data-parent="#accordion">
                        <div class="card-body">
                            <p style="color:#f2f2f2;"><?=$fq['Answer']?></p>
                        </div>
                        </div>
                    </div>
                    <?php 
                    $loop++;
                    endforeach;
                    ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!--Accordion area end-->
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