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
        .breadcrumb_content {
            border-bottom: 1px solid #ebebeb;
        }
        .shop_toolbar {
            border: 1px solid #444444;
        }
        .orderby_wrapper h3,.page_amount p {
            color: #faa618;
        }
        .widget_list.widget_categories > ul > li {
            border-bottom: 0px solid #444444;
            line-height: 18px;
        }
        .offcanvas_main_menu li a {
            border-bottom: 0px solid #ebebeb;
        }
        .widget_list {
            padding-bottom: 0px;
            margin-bottom: 20px;
            border-bottom: 1px solid #444444;
        }
        .fa-angle-down{
            color: #faa618;
        }
        .side-menu li a {
            color: #faa618;
        }
        .widget_list.widget_filter form button {
            background:#faa618;
            margin-bottom:20px;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        .widget_list.compare_p .compare_item {
            border-bottom: 1px solid #444444;
        }
        .stb{
            color: #f2f2f2;
            background-color: transparent;
            border: 1px solid #444444;
        }
        .stb:focus{
            color: #f2f2f2;
            background-color: transparent;
            border: 1px solid #444444;
            border-color: #faa618;
        }
        .sbt-btn{
            margin-left: 6px;
            background: #faa618;
        }
        </style>
        <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="<?=APP_HOME?>">home</a></li>
                            <li>></li>
                            <li>shop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--shop  area start-->
    <div class="product_section p_section1 product_black_section">
        <div class="container">
            <div class="row">
                    <div class="sidebar_black_version col-lg-3 col-md-12">
                       <!--sidebar widget start-->
                        <div class="sidebar_widget">
                            <div class="widget_list widget_categories">
                                <h2>Categories</h2>
                                <!-- nav sidebar -->
                                <ul class="offcanvas_main_menu side-menu">
                                    <?php foreach( $megas as $megao ): ?>   
                                        <li class="menu-item-has-children menu-open">
                                            <a href="shop.php?category=<?=$megao['CategoryId']?>"><?=$megao['CategoryName']?></a>
                                            <ul class="sub-menu" style="display:block!important;">
                                            <?php 
                                                $megaChildreno = $category->FindByParent($megao['CategoryId']);
                                                foreach( $megaChildreno as $mgco ):
                                            ?>
                                                <li class="menu-item-has-children">
                                                    <a href="shop.php?category=<?=$mgco['CategoryId']?>"><?=$mgco['CategoryName']?></a>
                                                    <ul class="sub-menu">
                                                        <?php 
                                                            $childChildreno = $category->FindByParent($mgco['CategoryId']);
                                                            foreach( $childChildreno as $ccho ):
                                                        ?>
                                                        <li><a href="shop.php?category=<?=$ccho['CategoryId']?>"><?=$ccho['CategoryName']?></a></li>
                                                        <?php endforeach;?>
                                                    </ul>
                                                </li>
                                            <?php endforeach;?>
                                            </ul>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                                <!-- end -->
                            </div>
                            <div class="widget_list widget_filter">
                                <h2>Filter by price</h2>
                                <form action="?filter=price" method="post"> 
                                    <div id="slider-range"></div>   
                                    <input type="text" name="text" id="amount" />   
                                    <button class="mfilter" name="pfiltersbt" type="submit">Filter</button>
                                </form>
                            </div>
                                                        
                            <div class="widget_list tag-cloud">
                                <h2>Tags</h2>
                                <div class="tag_widget">
                                    <ul>
                                        <?php 
                                        foreach ( $product->FindTagsAll() as $tag ):
                                        ?>
                                        <li><a href="shop.php?tag=<?=$tag?>"><?=$tag?></a></li>
                                        <?php 
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget_list compare_p">
                                <h2>Recent Products</h2>
                                <?php
                                foreach( $product->FindNewArrival(8) as $recentp ):
                                    if($product->isReady($recentp['ProductId'])){
                                ?>
                                <div class="compare_item">
                                   <div class="compare_img">
                                       <a href="#"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($recentp['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                   </div>
                                    <div class="compare_info">
                                        <h3><a href="product.php?item=<?=$recentp['ProductId']?>"><?=$recentp['ProductName']?></a></h3>   
                                        <div class="product_ratings">
                                            <ul>
                                            <?=$util->ShowRating($product->FindProductRate($recentp['ProductId']))?>
                                            </ul>
                                        </div>
                                        <div class="price_box">
                                            <?php if($recentp['ProductDiscountValue'] > 0){?>
                                            <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($recentp['ProductPrice'])?></span>
                                            <?php } ?>
                                            <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($recentp))?></span>
                                        </div>               
                                    </div>
                                </div>
                                <?php 
                                    }
                                    endforeach;
                                ?>
                            </div>
                            <div class="widget_list Featured_p">
                                <h2>Featured Products</h2>   
                                <?php
                                foreach( $product->FindFeatured(8) as $featuredp ):
                                    if($product->isReady($featuredp['ProductId'])){
                                ?>
                                <div class="Featured_item-">
                                   <div class="Featured_img">
                                       <a href="#"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($featuredp['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                   </div>
                                    <div class="Featured_info">
                                        <h3><a href="product.php?item=<?=$featuredp['ProductId']?>"><?=$featuredp['ProductName']?></a></h3>   
                                        <div class="product_ratings">
                                            <ul>
                                            <?=$util->ShowRating($product->FindProductRate($featuredp['ProductId']))?>
                                            </ul>
                                        </div>
                                        <div class="price_box">
                                            <?php if($featuredp['ProductDiscountValue'] > 0){?>
                                            <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($featuredp['ProductPrice'])?></span>
                                            <?php } ?>
                                            <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($featuredp))?></span>
                                        </div>               
                                    </div>
                                </div>
                                <?php 
                                    }
                                    endforeach;
                                ?>                       
                            </div>

                        </div>
                        <!--sidebar widget end-->
                    </div>
                    <?php 
                    $page_curr = $_REQUEST['page']; 
                    $limit = 15;
                    $total_items_count = 0;
                    $shop_items_to_show = [];
                    if(isset($_REQUEST['category'])){
                        if(!isset($_REQUEST['page'])){
                            $page_curr = 1;
                        }
                        if($_REQUEST['category'] == 'all'){
                            $offset = ($limit*$page_curr)-($limit);
                            $total_items_count = $product->CountAllActive();
                            $shop_items_to_show = $product->FindAllActive($limit, $offset);
                        }else{
                            $offset = ($limit*$page_curr)-($limit);
                            $total_items_count = $product->CountAllByCategory($_REQUEST['category']);
                            $shop_items_to_show = $product->FindByCategory($_REQUEST['category'],$limit, $offset);
                        }
                    }elseif(isset($_POST['searchqsbt']) && !empty($_POST['searchq'])){
                        if(!isset($_REQUEST['page'])){
                            $page_curr = 1;
                        }
                        $offset = ($limit*$page_curr)-($limit);
                        $shop_items_to_show = $product->SearchAll($_POST['searchq'], $limit, $offset);
                        $total_items_count = count($shop_items_to_show);
                    }elseif(isset($_POST['pfiltersbt']) && !empty($_POST['text'])){
                        if(!isset($_REQUEST['page'])){
                            $page_curr = 1;
                        }
                        $offset = ($limit*$page_curr)-($limit);
                        $price_range_arr = explode('-', $_POST['text']);
                        $price_range_a = trim($price_range_arr[0]);
                        $price_range_b = trim($price_range_arr[1]);
                        $currency_amt_arr = explode(' ', $price_range_a);
                        $range_lower = $currency_amt_arr[1];
                        $currency_value = $currency_amt_arr[0];
                        $range_upper = explode(' ', $price_range_b)[1];
                        $payload = [$range_lower, $range_upper, $currency_value];

                        $shop_items_to_show = $product->SearchAllByPrice($payload, $limit, $offset);
                        $total_items_count = count($shop_items_to_show);
                    }
                    // $util->Show($payload);
                    ?>
                    <div class="col-lg-9 col-md-12">
                        <!--shop wrapper start-->
                        <!--shop toolbar start-->
                        <div class="shop_toolbar">
                            <div class="list_button">
                                <ul class="nav" role="tablist">
                                    <li>
                                        <a class="active" data-toggle="tab" href="#large" role="tab" aria-controls="large" aria-selected="true"><i class="ion-grid"></i></a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="false"><i class="ion-ios-list-outline"></i> </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-check form-check-inline">
                                <h4 class="form-check-input">Search By: </h4>
                                <form class="form-inline" method="post" action="?search=all">
                                    <input type="text" name="searchq" class="form-control stb" placeholder="Enter something to search"/>
                                    <input type="submit" name="searchqsbt" class="btn btn-outline sbt-btn" value="Go"/>
                                </form>
                            </div>
                            <div class="orderby_wrapper">
                                <div class="page_amount">
                                    <p>Showing 1–<?=$limit?> of <?=$total_items_count?> results</p>
                                </div>
                            </div>
                        </div>
                        <!--shop toolbar end-->

                        <!--shop tab product start-->
                         <div class="tab-content">
                            <div class="tab-pane grid_view fade show active" id="large" role="tabpanel">
                                <div class="row">
                                <?php 
                                foreach( $shop_items_to_show as $sits ):
                                    if( $product->isReady($sits['ProductId'])){
                                ?>
                                   <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                                <a class="primary_img" href="product.php?item=<?=$sits['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="product.php?item=<?=$sits['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a onclick="LoadPreview('<?=$sits['ProductId']?>')" data-placement="top" data-original-title="quick view"> quick view</a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <div class="tag_cate">
                                                <?=$util->CategoryLinks($category, $sits['ProductCategories'])?>
                                                </div>
                                                <h3><a href="product.php?item=<?=$sits['ProductId']?>"><?=$sits['ProductName']?></a></h3>
                                                <div class="price_box">
                                                    <?php if($sits['ProductDiscountValue'] > 0){?>
                                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($sits['ProductPrice'])?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($sits))?></span>
                                                </div>
                                                <div class="product_hover">
                                                    <div class="product_ratings">
                                                        <ul>
                                                        <?=$util->ShowRating($product->FindProductRate($sits['ProductId']))?>
                                                        </ul>
                                                    </div>
                                                    <div class="product_desc">
                                                        <p><?=$util->Slice($sits['ProductShortDescription'], 97)?></p>
                                                    </div>
                                                    <div class="action_links">
                                                        <ul>
                                                            <li><a href="product.php?item=<?=$sits['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                            <?=$util->CreateCartForm($sits['ProductId'], 'sp_')?>
                                                            <li><a onclick="StarProduct('<?=$sits['ProductId']?>',3.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                        </ul>
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
                            <!-- List view begin -->
                            <div class="tab-pane list_view fade" id="list" role="tabpanel">
                                <?php 
                                 foreach( $shop_items_to_show as $sits_list ):
                                    if( $product->isReady($sits_list['ProductId'])){
                                ?>
                               <div class="single_product product_list_item">
                                   <div class="row">
                                       <div class="col-lg-4 col-md-5">
                                           <div class="product_thumb">
                                                <a class="primary_img" href="product.php?item=<?=$sits_list['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits_list['ProductId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="product.php?item=<?=$sits_list['ProductId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits_list['ProductId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a onclick="LoadPreview('<?=$sits_list['ProductId']?>')" data-original-title="quick view"> quick view</a>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-lg-8 col-md-7">
                                            <div class="product_content">               
                                              <h3><a href="product.php?item=<?=$sits_list['ProductId']?>"><?=$sits_list['ProductName']?></a></h3>
                                              <div class="tag_cate">
                                                <?=$util->CategoryLinks($category, $sits_list['ProductCategories'])?>
                                              </div>
                                              <div class="product_ratings">
                                                    <ul>
                                                    <?=$util->ShowRating($product->FindProductRate($sits_list['ProductId']))?>
                                                    </ul>
                                                </div>
                                                <div class="product_desc">
                                                    <p><?=$util->Slice($sits_list['ProductShortDescription'], 260)?>...</p>
                                                </div>
                                               <div class="price_box">
                                                <?php if($sits_list['ProductDiscountValue'] > 0){?>
                                                    <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($sits_list['ProductPrice'])?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($sits_list))?></span>
                                                </div>
                                                
                                                <div class="action_links">
                                                    <ul>
                                                        <?=$util->CreateCartForm($sits_list['ProductId'], 'spl_')?>
                                                        <li><a href="product.php?item=<?=$sits_list['ProductId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                        
                                                        <li><a onclick="StarProduct('<?=$sits_list['ProductId']?>',3.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                    </ul>
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
                        <!--shop tab product end-->
                        <!--shop toolbar start-->
                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                <ul>
                                    <?php
                                         if(empty( $shop_items_to_show )){
                                            print '<h2>No items found for your query</h2>';
                                        }
                                    ?>
                                    <?=$util->Paginator([$total_items_count, $limit])?>
                                </ul>
                            </div>
                        </div>
                        <!--shop toolbar end-->
                        <!--shop wrapper end-->
                    </div>
                </div>    
        </div>
    </div>
    <!--shop  area end-->
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
<script src="assets/js/plugins.js"></script>
<script src="assets/js/wt.js"></script>
<!-- Main JS -->
<script src="assets/js/main.js"></script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>