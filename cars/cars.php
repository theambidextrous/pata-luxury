<?php
    session_start();
    if(!isset($_REQUEST['htl']) && !isset($_REQUEST['search'])){
        exit('<h1>Not Found..... Sorry</h1>');
    }
    $product_id_get = $_REQUEST['item'];
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    require_once '../mail/autoload.php';
    require_once '../lib/BladeSMS.php';
    require_once '../lib/Util.php';
    require_once '../lib/Setting.php';
    require_once '../lib/Car.php';
    require_once '../lib/Pack.php';
    require_once '../lib/Product.php';
    $util = new Util();
    // $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    // require_once '../lib/RoomCategory.php';
    require_once '../lib/Category.php';
    require_once '../lib/Gallery.php';
    require_once '../lib/Blog.php';
    require_once '../lib/User.php';
    $blog = new Blog($conn);
    $category = new Category($conn);
    // $roomcategory = new RoomCategory($conn);
    $car = new Car($conn);
    $user = new User($conn);
    $product = new Product($conn);
    $gallery = new Gallery($conn);
    $pack = new Pack($conn);
    $megas = $category->FindAllMegaCategory();
    $car_users_with_fleets = $car->FindCarUserIds();
    $r_count = count($car_users_with_fleets);
            
  // $util->Show($util->ComputeDistance());
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
                            <li>Hotels</li>
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
                                <h2>Listed Car Hires</h2>
                                <!-- nav sidebar -->
                                <ul class="offcanvas_main_menu side-menu">
                                    <?php foreach( $car_users_with_fleets as $indiv ):?>   
                                        <li class="menu-item-has-children menu-open">
                                            <a href="cars.php?htl=<?=$indiv['CarOwner']?>"><?=$user->FindById($indiv['CarOwner'])['UserFullName']?></a>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                                <!-- end -->
                            </div>
                            <div class="widget_list compare_p">
                                <h2>Recent Luxury Cars</h2>
                                <?php
                                foreach( $car->FindNewArrival(5) as $recentp ):
                                    if($car->isReady($recentp['CarId'])){
                                ?>
                                <div class="compare_item">
                                   <div class="compare_img">
                                       <a href="#"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($recentp['CarId'],'5003')['GalleryPath']?>" alt=""></a>
                                   </div>
                                    <div class="compare_info">
                                        <h3><a href="car.php?item=<?=$recentp['CarId']?>"><?=$recentp['CarName']?></a></h3>   
                                        <div class="product_ratings">
                                            <ul>
                                            <?=$util->ShowRating($car->FindProductRate($recentp['CarId']))?>
                                            </ul>
                                        </div>
                                        <div class="price_box">
                                            <?php if($recentp['CarDiscountValue'] > 0){?>
                                            <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($recentp['CarPrice'])?></span>
                                            <?php } ?>
                                            <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($recentp))?></span>
                                        </div>               
                                    </div>
                                </div>
                                <?php 
                                    }
                                    endforeach;
                                ?>
                            </div>
                            <div class="widget_list Featured_p">
                                <h2>Featured Vehicles</h2>   
                                <?php
                                foreach( $car->FindFeatured(5) as $featured ):
                                    if($car->isReady($featured['CarId'])){
                                ?>
                                <div class="Featured_item">
                                   <div class="Featured_img">
                                       <a href="#"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($featured['CarId'],'5003')['GalleryPath']?>" alt=""></a>
                                   </div>
                                    <div class="Featured_info">
                                        <h3><a href="car.php?item=<?=$featured['CarId']?>"><?=$featured['CarName']?></a></h3>   
                                        <div class="product_ratings">
                                            <ul>
                                            <?=$util->ShowRating($car->FindProductRate($featured['CarId']))?>
                                            </ul>
                                        </div>
                                        <div class="price_box">
                                            <?php if($featured['CarDiscountValue'] > 0){?>
                                            <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($featured['CarPrice'])?></span>
                                            <?php } ?>
                                            <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($featured))?></span>
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
                    if(isset($_REQUEST['htl'])){
                        if(!isset($_REQUEST['page'])){
                            $page_curr = 1;
                        }
                        if($_REQUEST['htl'] == 'all'){
                            $offset = ($limit*$page_curr)-($limit);
                            $total_items_count = $car->CountAllActive();
                            $shop_items_to_show = $car->FindAllActive($limit, $offset);
                        }else{
                            $offset = ($limit*$page_curr)-($limit);
                            $total_items_count = $car->CountAllByVendor($_REQUEST['htl']);
                            $shop_items_to_show = $car->FindByVendor($_REQUEST['htl'],$limit, $offset);
                        }
                    }elseif(isset($_POST['searchqsbt']) && !empty($_POST['searchq'])){
                        if(!isset($_REQUEST['page'])){
                            $page_curr = 1;
                        }
                        // print $_POST['searchq'];
                        $offset = ($limit*$page_curr)-($limit);
                        $shop_items_to_show = $car->SearchAll($_POST['searchq'], $limit, $offset);
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
                                    if( $car->isReady($sits['CarId'])){
                                ?>
                                   <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                                <a class="primary_img" href="car.php?item=<?=$sits['CarId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits['CarId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="car.php?item=<?=$sits['CarId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits['CarId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a href="car.php?item=<?=$sits['CarId']?>" data-placement="top" data-original-title="c mo"> See more</a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <div class="tag_cate">
                                                    <a style="font-size: 11px;font-weight: 100;" href="cars.php?htl=<?=$sits['CarOwner']?>"><?=$user->FindById($sits['CarOwner'])['UserFullName']?></a>
                                                </div>
                                                <h3><a href="car.php?item=<?=$sits['CarId']?>"><?=$sits['CarName']?></a></h3>
                                                <div class="price_box">
                                                    <?php if($sits['CarDiscountValue'] > 0){?>
                                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($sits['CarPrice'])?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($sits))?></span>
                                                </div>
                                                <div class="product_hover">
                                                    <div class="product_ratings">
                                                        <ul>
                                                        <?=$util->ShowRating($car->FindProductRate($sits['CarId']))?>
                                                        </ul>
                                                    </div>
                                                    <div class="product_desc">
                                                        <p><?=$util->Slice($sits['CarMetaDescription'], 97)?></p>
                                                    </div>
                                                    <div class="action_links">
                                                        <form action="" name="sp_<?=$sits['CarId']?>" method="post">
                                                            <ul>
                                                                <li><a href="car.php?item=<?=$sits['CarId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                <li class="add_to_cart"><a href="car.php?item=<?=$sits['CarId']?>" title="reserve now ">Reserve Now</a></li>
                                                                <li><a onclick="StarProduct('<?=$sits['CarId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
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
                            <!-- List view begin -->
                            <div class="tab-pane list_view fade" id="list" role="tabpanel">
                                <?php 
                                 foreach( $shop_items_to_show as $sits_list ):
                                    if( $car->isReady($sits_list['CarId'])){
                                ?>
                               <div class="single_product product_list_item">
                                   <div class="row">
                                       <div class="col-lg-4 col-md-5">
                                           <div class="product_thumb">
                                                <a class="primary_img" href="car.php?item=<?=$sits_list['CarId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits_list['CarId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="car.php?item=<?=$sits_list['CarId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sits_list['CarId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a href="car.php?item=<?=$sits_list['CarId']?>" data-placement="top" data-original-title="c mo"> See more</a>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-lg-8 col-md-7">
                                            <div class="product_content">               
                                              <h3><a href="car.php?item=<?=$sits_list['CarId']?>"><?=$sits_list['CarName']?></a></h3>
                                              <div class="tag_cate">
                                                    <a style="font-size: 11px;font-weight: 100;" href="cars.php?htl=<?=$sits_list['CarOwner']?>"><?=$user->FindById($sits_list['CarOwner'])['UserFullName']?></a>
                                              </div>
                                              <div class="product_ratings">
                                                    <ul>
                                                    <?=$util->ShowRating($car->FindProductRate($sits_list['CarId']))?>
                                                    </ul>
                                                </div>
                                                <div class="product_desc">
                                                    <p><?=$util->Slice($sits_list['CarMetaDescription'], 260)?>...</p>
                                                </div>
                                               <div class="price_box">
                                                <?php if($sits_list['CarDiscountValue'] > 0){?>
                                                    <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($sits_list['CarPrice'])?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($sits_list))?></span>
                                                </div>
                                                
                                                <div class="action_links">
                                                    <form action="" name="sp_<?=$sits_list['CarId']?>" method="post">
                                                        <ul>
                                                            <li><a href="car.php?item=<?=$sits_list['CarId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                            <li class="add_to_cart"><a href="car.php?item=<?=$sits_list['CarId']?>" title="reserve now ">Reserve Now</a></li>
                                                            <li><a onclick="StarProduct('<?=$sits_list['CarId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
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
<!-- Plugins JS -->
<script src="<?=APP_HOME?>/assets/js/plugins.js"></script>
<script src="<?=APP_HOME?>/assets/js/wt.js"></script>
<script src="<?=APP_HOME?>/assets/js/jquery.ez-plus.js"></script>
<!-- Main JS -->
<script src="<?=APP_HOME?>/assets/js/main.js"></script>
<script>
$(document).ready(function(){
    $("#zoom1").ezPlus({
        gallery:'gallery_01',
        responsive : true,
        cursor: 'crosshair'
    }); 
})
</script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>