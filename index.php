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
    require_once 'lib/Blog.php';
    $blog = new Blog($conn);
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
    <title><?=$settings['SiteName']?> | Home</title>
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
        @media only screen and (max-width: 1600px) and (min-width: 1200px){
        .slider_black .single_slider .row {
            height: 500px!important;
        }
        .slider_black .slider_content h1 {
            font-size: 28px;
            line-height: 38px;
            margin-bottom: 8px;
        }
        .slider_black .slider_content span {
            font-size: 15px;
            margin-bottom: 25px;
        }
        }
        .slider_black .single_slider .row {
            height: 450px!important;
        }
        .slider_black .slider_content h1 {
            font-size: 38px;
            line-height: 40px;
            margin-bottom: 8px;
        }
        .slider_black .slider_content span {
            font-size: 15px;
            margin-bottom: 25px;
        }
        .close,.modal_title h2,.modal_social h2,.modal_description p {
            color:#f2f2f2;
        }
        
        .modal_add_to_cart form input {
            border: 1px solid #444444;
            color: #f2f2f2;
        }
        .modal_add_to_cart,.modal_description.mb-15 {
            border-bottom: 1px solid #444444;
        }
        .modal_add_to_cart form button {
            background: #faa618;
            color: #232323;
        }
        .modal_right .product_ratings ul li a {
            font-size:30px;
        }
        .see_all a {
            color: #faa618;
        }
        </style>
        <div class="slider_area slider_black owl-carousel">
            <?php 
            $sliders = $product->FindSliders();
            foreach( $sliders as $sld ):
                if($product->isReady($sld['ProductId'])){
            ?>
             <div class="single_slider" data-bgimg="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sld['ProductId'],'5004')['GalleryPath']?>">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="slider_content">
                                <p>exclusive offer this week</p>
                                <h1><?=$sld['ProductName']?></h1>
                                <span><?=$util->Slice($sld['ProductShortDescription'], 75)?></span>
                                <p class="slider_price"><i>starting from</i> <span><?=$_SESSION['cry'].' ' .$util->Forex($util->ApplyDiscount($sld))?></span></p>
                                <a class="button" href="product.php?item=<?=$sld['ProductId']?>">shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
                endforeach;
            ?>
        </div>
        <!--slider area end-->

        <!--product section area start-->
        <section class="product_section p_section1 product_black_section">
            <div class="container">
                <div class="row">   
                    <div class="col-12">
                        <div class="product_area"> 
                            <div class="product_tab_button">
                                <ul class="nav" role="tablist">
                                    <li>
                                        <a class="active" data-toggle="tab" href="#featured" role="tab" aria-controls="featured" aria-selected="true">Featured</a>
                                        <!-- most expensive -->
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#arrivals" role="tab" aria-controls="arrivals" aria-selected="false">New Arrivals</a>
                                        <!-- most recent -->
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#onsale" role="tab" aria-controls="onsale" aria-selected="false">Onsale</a>
                                        <!-- cheapeast -->
                                    </li>

                                </ul>
                            </div>
                             <div class="tab-content">
                                  <div class="tab-pane fade show active" id="featured" role="tabpanel">
                                         <div class="product_container">
                                            <div class="custom-row product_column3">
                                                <?php 
                                                $featured = $product->FindFeatured();
                                                foreach( $featured as $ftrd ):
                                                    if( $product->isReady($ftrd['ProductId'])){
                                                ?>
                                                <div class="custom-col-5">
                                                    <div class="single_product">
                                                        <div class="product_thumb">
                                                            <a class="primary_img" href="product.php?item=<?=$ftrd['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($ftrd['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                            <a class="secondary_img" href="product.php?item=<?=$ftrd['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($ftrd['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                            <div class="quick_button">
                                                                <a onclick="LoadPreview('<?=$ftrd['ProductId']?>')" data-placement="top" data-original-title="quick view"> quick view</a>
                                                            </div>
                                                        </div>
                                                        <div class="product_content">
                                                            <div class="tag_cate">
                                                                
                                                                <a style="font-size: 11px;font-weight: 100;" href="#"><?=$util->CategoryLinks($category, $ftrd['ProductCategories'])?></a>

                                                            </div>
                                                            <h3><a href="product.php?item=<?=$ftrd['ProductId']?>"><?=$ftrd['ProductName']?></a></h3>
                                                            <div class="price_box">
                                                                <?php if($ftrd['ProductDiscountValue'] > 0){?>
                                                                    <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyMarkUp($ftrd, $ftrd['ProductPrice']))?></span>
                                                                <?php } ?>
                                                                <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($ftrd))?></span>
                                                            </div>
                                                            <div class="product_hover">
                                                                <div class="product_ratings">
                                                                    <ul>
                                                                    <?=$util->ShowRating($product->FindProductRate($ftrd['ProductId']))?>
                                                                    </ul>
                                                                </div>
                                                                <div class="product_desc">
                                                                    <p><?=$util->Slice($ftrd['ProductShortDescription'], 97)?></p>
                                                                </div>
                                                                <div class="action_links">
                                                                    <form action="" name="st_<?=$ftrd['ProductId']?>" method="post">
                                                                        <ul>
                                                                            <li><a href="product.php?item=<?=$ftrd['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                            <?=$util->CreateCartForm($ftrd['ProductId'], 'st_')?>
                                                                            <li><a onclick="StarProduct('<?=$ftrd['ProductId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                                        </ul>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } endforeach;?>
                                            </div>
                                        </div>
                                  </div>
                                    <div class="tab-pane fade" id="arrivals" role="tabpanel">
                                        <div class="product_container">
                                            <div class="custom-row product_column3">
                                            <?php 
                                                $arrivals = $product->FindNewArrival();
                                                foreach( $arrivals as $arvv ):
                                                    if( $product->isReady($arvv['ProductId'])){
                                                ?>
                                                <div class="custom-col-5">
                                                    <div class="single_product">
                                                        <div class="product_thumb">
                                                            <a class="primary_img" href="product.php?item=<?=$arvv['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($arvv['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                            <a class="secondary_img" href="product.php?item=<?=$arvv['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($arvv['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                            <div class="quick_button">
                                                                <a onclick="LoadPreview('<?=$arvv['ProductId']?>')" data-placement="top" data-original-title="quick view"> quick view</a>
                                                            </div>
                                                        </div>
                                                        <div class="product_content">
                                                            <div class="tag_cate">
                                                                
                                                                <a style="font-size: 11px;font-weight: 100;" href="#"><?=$util->CategoryLinks($category, $arvv['ProductCategories'])?></a>

                                                            </div>
                                                            <h3><a href="product.php?item=<?=$arvv['ProductId']?>"><?=$arvv['ProductName']?></a></h3>
                                                            <div class="price_box">
                                                                <?php if($arvv['ProductDiscountValue'] > 0){?>
                                                                    <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyMarkUp($arvv, $arvv['ProductPrice']))?></span>
                                                                <?php } ?>
                                                                <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($arvv))?></span>
                                                            </div>
                                                            <div class="product_hover">
                                                                <div class="product_ratings">
                                                                    <ul>
                                                                    <?=$util->ShowRating($product->FindProductRate($arvv['ProductId']))?>
                                                                    </ul>
                                                                </div>
                                                                <div class="product_desc">
                                                                    <p><?=$util->Slice($arvv['ProductShortDescription'], 97)?></p>
                                                                </div>
                                                                <div class="action_links">
                                                                     <form action="" name="sv_<?=$arvv['ProductId']?>" method="post">
                                                                        <ul>
                                                                            <li><a href="product.php?item=<?=$arvv['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                            <?=$util->CreateCartForm($arvv['ProductId'], 'sv_')?>
                                                                            <li><a onclick="StarProduct('<?=$arvv['ProductId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                                        </ul>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } endforeach;?>
                                            </div>
                                        </div> 

                                   </div> 
                                    <div class="tab-pane fade" id="onsale" role="tabpanel">
                                         <div class="product_container">
                                            <div class="custom-row product_column3">
                                            <?php 
                                                $onsale = $product->FindOnsale();
                                                foreach( $onsale as $onsv ):
                                                    if( $product->isReady($onsv['ProductId'])){
                                                ?>
                                                <div class="custom-col-5">
                                                    <div class="single_product">
                                                        <div class="product_thumb">
                                                            <a class="primary_img" href="product.php?item=<?=$onsv['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($onsv['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                            <a class="secondary_img" href="product.php?item=<?=$onsv['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($onsv['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                            <div class="quick_button">
                                                                <a onclick="LoadPreview('<?=$onsv['ProductId']?>')" data-placement="top" data-original-title="quick view"> quick view</a>
                                                            </div>
                                                        </div>
                                                        <div class="product_content">
                                                            <div class="tag_cate">
                                                                
                                                                <a style="font-size: 11px;font-weight: 100;" href="#"><?=$util->CategoryLinks($category, $onsv['ProductCategories'])?></a>

                                                            </div>
                                                            <h3><a href="product.php?item=<?=$onsv['ProductId']?>"><?=$onsv['ProductName']?></a></h3>
                                                            <div class="price_box">
                                                                <?php if($onsv['ProductDiscountValue'] > 0){?>
                                                                    <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyMarkUp($onsv, $onsv['ProductPrice']))?></span>
                                                                <?php } ?>
                                                                <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($onsv))?></span>
                                                            </div>
                                                            <div class="product_hover">
                                                                <div class="product_ratings">
                                                                    <ul>
                                                                    <?=$util->ShowRating($product->FindProductRate($onsv['ProductId']))?>
                                                                    </ul>
                                                                </div>
                                                                <div class="product_desc">
                                                                    <p><?=$util->Slice($onsv['ProductShortDescription'], 97)?></p>
                                                                </div>
                                                                <div class="action_links">
                                                                     <form action="" name="stt_<?=$onsv['ProductId']?>" method="post">
                                                                        <ul>
                                                                            <li><a href="product.php?item=<?=$onsv['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                            <?=$util->CreateCartForm($onsv['ProductId'], 'stt_')?>
                                                                            <li><a onclick="StarProduct('<?=$onsv['ProductId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                                        </ul>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } endforeach;?>
                                            </div>
                                        </div>
                                  </div>  
                            </div>
                        </div>

                    </div>
                </div>    
            </div>
        </section>
        <!--product section area end-->

         <!--banner fullwidth start-->
        <section class="banner_fullwidth black_fullwidth">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                       <div class="banner_text">
                            <p>Sale Off 5% All Products</p>
                            <h2>New Deluxe Collection</h2>
                            <span>We Believe That Good Design is Always in Season</span>
                            <a href="shop.php?category=all">Shop now</a>

                       </div>

                    </div>
                </div>   
            </div>
        </section>
        <!--banner area end-->

         <!--product section area start-->
        <section class="product_section p_section1 product_black_section bottom">
            <div class="container">
                <div class="row">  
                    <div class="col-12">
                        <div class="section_title">
                            <h2>Top selling Products</h2>
                        </div> 
                    </div> 
                    <div class="col-12">
                        <div class="product_area"> 
                            <div class="product_container bottom">
                                <div class="custom-row product_row1">
                                    <?php 
                                        $top_selling = $product->TopSelling();
                                        foreach( $top_selling as $tps ):
                                            if($product->isReady($tps['ProductId'])){
                                    ?>
                                    <div class="custom-col-5">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                                <a class="primary_img" href="product.php?item=<?=$tps['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($tps['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="product.php?item=<?=$tps['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($tps['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a onclick="LoadPreview('<?=$tps['ProductId']?>')" data-placement="top" data-original-title="quick view"> quick view</a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <div class="tag_cate">
                                                    <?=$util->CategoryLinks($category, $tps['ProductCategories'])?>
                                                </div>
                                                <h3><a href="product.php?item=<?=$tps['ProductId']?>"><?=$tps['ProductName']?></a></h3>
                                                <div class="price_box">
                                                    <?php if($tps['ProductDiscountValue'] > 0){?>
                                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyMarkUp($tps, $tps['ProductPrice']))?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($tps))?></span>
                                                </div>
                                                <div class="product_hover">
                                                    <div class="product_ratings">
                                                        <ul>
                                                        <?=$util->ShowRating($product->FindProductRate($tps['ProductId']))?>
                                                        </ul>
                                                    </div>
                                                    <div class="product_desc">
                                                        <p><?=$util->Slice($tps['ProductShortDescription'], 97)?></p>
                                                    </div>
                                                    <div class="action_links">
                                                        <form action="" name="sp_<?=$tps['ProductId']?>" method="post">
                                                            <ul>
                                                                <li><a href="product.php?item=<?=$tps['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                <?=$util->CreateCartForm($tps['ProductId'], 'sp_')?>
                                                                <li><a onclick="StarProduct('<?=$tps['ProductId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                            </ul>
                                                        </form>
                                                    </div>
                                                </div>
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
            </div>
        </section>
        <!--product section area end--> 

        <!--blog section area start-->
        <section class="blog_section blog_black">
            <div class="container">
               <div class="row">
                   <div class="col-12">
                       <div class="section_title">
                           <h2><?=$util->CamelCase($settings['SiteName'])?> News</h2>
                       </div>
                   </div>
               </div>
                <div class="row">
                    <!-- <div class="blog_wrapper"> -->
                        <?php 
                            $blogs = $blog->FindRecent(4);
                            foreach($blogs as $blg ):
                        ?> 
                        <div class="col-lg-3">
                            <div class="single_blog">
                                <div class="blog_thumb">
                                    <a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>"><img src="<?=APP_IMG_PATH?>misc/<?=$blg['BlogThumbPath']?>" alt=""></a>
                                </div>
                                <div class="blog_content">
                                    <h3><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>"><?=$blg['BlogTitle']?></a></h3>
                                    <div class="author_name">
                                       <p> 
                                            <span>by</span>
                                            <span class="themes">admin</span>
                                            / <?=date('d M Y', strtotime($blg['created_at']))?>
                                       </p>

                                    </div>

                                    <div class="post_desc">
                                        <p><?=$util->Slice($blg['BlogExercept'],120)?>...</p>
                                    </div>
                                    <div class="read_more">
                                        <a style="background:#faa618;color:white;padding: 8px 10px 8px 10px;border-radius: 4px;" href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>">Continue reading</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        endforeach;
                        ?>
                    <!-- </div> -->
                </div>
            </div>
        </section>
        <!--blog section area end-->
        
        <!-- newsletter & footer -->
        <?php 
        require_once 'com/footer.php';
        ?>
        <!--footer area end-->
   </div>
<!-- 
===================================================== -->
<!-- modal area start-->
<a href="#" data-toggle="modal" id="btn_modal_box" data-target="#modal_box" style="display:none;"></a>
<div class="modal fade" id="modal_box" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered blog_black" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal_body">
                    <div class="container">
                        <div class="row" id="quickviewmodal">
                        </div>     
                    </div>
                </div>    
            </div>
        </div>
    </div> 
 <!-- end modal -->
<!-- JS
============================================ -->
<!-- Plugins JS -->
<script src="<?=APP_HOME?>/assets/js/plugins.js"></script>
<script src="<?=APP_HOME?>/assets/js/wt.js"></script>
<!-- Main JS -->
<script src="<?=APP_HOME?>/assets/js/main.js"></script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>