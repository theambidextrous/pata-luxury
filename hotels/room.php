<?php
    session_start();
    if(!isset($_REQUEST['item']) || empty($_REQUEST['item'])){
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
    require_once '../lib/Room.php';
    require_once '../lib/Pack.php';
    require_once '../lib/Product.php';
    $util = new Util();
    // $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    require_once '../lib/RoomCategory.php';
    require_once '../lib/Category.php';
    require_once '../lib/Gallery.php';
    require_once '../lib/Blog.php';
    require_once '../lib/User.php';
    $blog = new Blog($conn);
    $category = new Category($conn);
    $roomcategory = new RoomCategory($conn);
    $room = new Room($conn);
    $user = new User($conn);
    $product = new Product($conn);
    $gallery = new Gallery($conn);
    $pack = new Pack($conn);
    $product_meta = $room->FindById($product_id_get);
    $megas = $category->FindAllMegaCategory();
    $roomcategories = $roomcategory->FindAll();
    $init_banner = $gallery->FindByTypeProduct($product_id_get, 5005)['GalleryPath'];
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
    <title><?=$settings['SiteName']?> | <?=$product_meta['RoomName']?></title>
    <meta name="description" content="<?=$product_meta['RoomMetaDescription']?>">
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
        .breadcrumb_content h3,.product_d_right h1,.product_variant.quantity label,.product_d_action ul li a,.product_meta span,.product_info_button ul li a{
            color: #ffffff;
        }
        .reviews_wrapper h2,.reviews_wrapper p,.reviews_wrapper .product_ratting h3,.reviews_meta span{
            color: #ffffff;
        }
        .reviews_meta p{
            color:#faa618 !important;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        .product_meta span a {
            color: #faa618;
        }
        .product_variant.quantity input {
            border: 1px solid #444444;
            color: #f2f2f2;
        }
        .product_info_content p {
            color: #f2f2f2;
            line-height: 28px;
        }
        .msg{
            color:#faa618 !important;
        }
        .product_d_right .product_desc {
            border-bottom: 1px solid #444444;
        }
        .single-zoom-thumb ul li {
            border: 1px solid #444444;
        }
        #img-1 {
            border: 1px solid #444444;
        }
        .product_info_button {
            border-bottom: 1px solid #444444;
        }
        .reviews_comment_box .comment_text {
            border: 1px solid #444444;
        }
        .product_review_form textarea {
            border: 1px solid #444444;
            color:#f2f2f2;
            height:60px;
        }
        .product_review_form button {
            background: #faa618;
            color: #000;
            text-transform: none;
            font-weight: 400;
        }
        .str i {
            font-size: 40px;
        }
        .str i:hover{
            color: #faa618;
        }
        .comment_title {
            margin-top: 20px;
            border-top: dotted 1px #444;
        }
        .product_info_content h4,.product_info_content span,.product_info_content h2,.product_info_content h3,.product_info_content h1 {
            color:#f2f2f2;
        }
        .nav-tabs .nav-link.active {
            color: #242424;
            background-color: #faa618;
            border-color: #faa618 #faa618 #faa618;
            font-size: 20px;
            font-weight: 400;
        }
        .nav-tabs .nav-link {
            color: #faa618;
            border-color: #faa618 #faa618 #faa618;
        }
        .nav-tabs {
            border-bottom: 1px solid #444444;
        }
        .product_variant.quantity {
            margin-bottom: 13px;
        }
        .product_variant.quantity input {
            width: 34%;
            height: 25px;
            padding: 0 12px;
        }
        .product_variant.quantity button {
            margin-left: 0px;
            width: 38%;
            min-width:38%;
        }
        </style>
        <?php 
        
        ?>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3><?=$product_meta['RoomName']?></h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li><a href="<?=APP_HOME?>/hotels">hotels</a></li>
                                <li>></li>
                                <li>room details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="product_details p_section1 product_black_section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <!-- tabs start -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Image Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Videos</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <!-- start gallery -->
                                <div class="product-details-tab">
                                    <div id="img-1" class="zoomWrapper single-zoom">
                                        <a href="#">
                                            <img id="zoom1" src="<?=APP_IMG_PATH?>items/<?=$init_banner?>" data-zoom-image="<?=APP_IMG_PATH?>items/<?=$init_banner?>" alt="big-1">
                                        </a>
                                    </div>
                                    <div class="single-zoom-thumb">
                                        <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                            <?php 
                                                foreach ( $gallery->FindForCarosell($product_id_get) as $pbanner => $pthumb ):
                                            ?>
                                            <li>
                                                <a href="#" class="elevatezoom-gallery active" data-update="" data-image="<?=APP_IMG_PATH?>items/<?=$pbanner?>" data-zoom-image="<?=APP_IMG_PATH?>items/<?=$pbanner?>">
                                                    <img src="<?=APP_IMG_PATH?>items/<?=$pthumb?>" alt="zo-th-1"/>
                                                </a>

                                            </li>
                                            <?php 
                                                endforeach;
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end gallery -->
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <!-- start 3d -->
                                <?=$util->ShowVideo()?>
                                <!-- end -->
                            </div>
                        </div>
                        <!-- tabs -->
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="product_d_right">
                                <h1><?=$product_meta['RoomName']?></h1>
                                <div class="product_nav">
                                        <?php 
                                            $seq = $product_meta['RoomSequence'];
                                        ?>
                                    <ul>
                                        <li class="prev"><a href="room.php?item=<?=$room->FindIdBySequence($seq)?>"><i class="fa fa-angle-left"></i></a></li>
                                        <li class="next"><a href="room.php?item=<?=$room->FindIdBySequence($seq, 1)?>"><i class="fa fa-angle-right"></i></a></li>
                                    </ul>
                                </div>
                                <div class=" product_ratting">
                                    <ul>
                                        <?=$util->ShowRating($room->FindProductRate($product_id_get))?>
                                        <li><a href="#"> ( customer rating ) </a></li>
                                    </ul>
                                </div>
                                <div class="product_price">
                                    <?php if($product_meta['RoomDiscountValue'] > 0){?>
                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($product_meta['RoomPrice'])?></span>
                                    <?php } ?>
                                        <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountHotel($product_meta))?></span>
                                </div>
                                <div class="product_desc">
                                    <p><?=$util->Slice($product_meta['RoomShortDescription'], 500)?></p>
                                </div>
                                <!-- packs -->
                                <span class="current_price" style="color:#f2f2f2;">Room Inclusive of:-</span>
                                <ul style="list-style: cambodian;margin-left:3%;margin-bottom:3%;">
                                    <?php
                                        $room_packs = explode(',', $product_meta['RoomPacks']);
                                        $room_packs = !empty($room_packs)?$room_packs:[];
                                        if(count($room_packs) > 0){
                                            foreach( $room_packs as $rmps ):
                                                print '<li><a class="msg">'.$pack->FindById($rmps)['PackName'].'</a></li>';
                                            endforeach;
                                        }else{
                                            print '<li><a class="msg">No packs included with this room</a></li>';
                                        }
                                       
                                        ?>
                                </ul>
                                <?php
                                    if(isset($_POST['reserver_pay']) && !empty($_POST['adultQty'])){
                                        $adults = $_POST['adultQty'];
                                        $children = $_POST['childQty'];
                                        $checkin = date_create($_POST['indate']);
                                        $checkout = date_create($_POST['outdate']);
                                        $romm_id = $_POST['ProductId'];
                                        $now = date_create();
                                        if($checkin < $now ){
                                            print '<span style="font-size: 30px;" class="alert alert-danger">Checkin date is invalid!</span><br><br><br>';
                                        }elseif($checkout <= $checkin ){
                                            print '<span style="font-size: 30px;" class="alert alert-danger">Checkin & Checkout dates are invalid!</span><br><br><br>';
                                        }else{
                                            $reservation_data = [
                                                'aqty' => $adults,
                                                'cqty' => $children,
                                                'in' => $_POST['indate'],
                                                'out' => $_POST['outdate'],
                                                'room' => $romm_id,
                                                'order' => 'RM'.$util->KeyGen(6,1)
                                            ];
                                            $_SESSION['hotel_order'] = $reservation_data;
                                            $util->RedirectTo(APP_HOME.'/hotels/pay.php');
                                        }
                                    }
                                ?>
                                <form action="" method="post">
                                <!-- end packs -->
                                        <!-- form -->
                                    <?=$util->HotelBookingForm($product_id_get, 'hr_')?>
                                    <!-- end form -->
                                </form>
                                <div class=" product_d_action">
                                <ul>
                                    <li>
                                    <a class="alert alert-success" id="<?=$product_id_get?>wrty_succ" style="display:none;"></a></li>
                                    <li>
                                    <a class="alert alert-danger" id="<?=$product_id_get?>wrty_err" style="display:none;"></a></li>
                                    <li><a class="msg" onclick="WishList('<?=$product_id_get?>--wrty', 1)">+ Add to Wishlist</a></li>
                                    <li><a class="msg" onclick="WishList('<?=$product_id_get?>--wrty', 2)">+ Like</a></li>
                                </ul>
                                </div>
                                <div class="product_meta">
                                    <a style="font-size: 11px;font-weight: 100;" href="hotels.php?htl=<?=$product_meta['RoomOwner']?>"><?=$user->FindById($product_meta['RoomOwner'])['UserFullName']?></a>
                                </div>
                            
                            <div class="priduct_social">
                            <?=$util->HtmlDecode(json_decode($settings['SiteShareButtons']))?>     
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- area end-->
        <?php
            $ratings = $room->FindRatings($product_id_get);
            $count_r = count($ratings);
            $rlabel = 'review';
            if($count_r > 1){
                $rlabel = 'reviews';
            }
        ?>
         <!-- description-->
        <section class="product_d_info p_section1 product_black_section bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="product_d_inner">   
                            <div class="product_info_button">    
                                <ul class="nav" role="tablist">
                                    <li >
                                        <a class="active" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Description</a>
                                    </li>
                                    <li>
                                    <a data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews (<?=$count_r?>)</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="info" role="tabpanel" >
                                    <div class="product_info_content">
                                    <?=$util->RemoveStyle($util->HtmlDecode($product_meta['RoomDescription']))?>
                                    </div>    
                                </div>

                                <div class="tab-pane fade" id="reviews" role="tabpanel" >
                                    <div class="reviews_wrapper">
                                        <h4 style="color:#faa618;"><?=$count_r?> <?=$rlabel?> for <?=$product_meta['RoomName']?></h4><br>
                                        <?php 
                                            foreach($ratings as $prating ):
                                        ?>
                                        <div class="reviews_comment_box">
                                            <div class="comment_thmb">
                                                <img src="assets/img/blog/comment2.jpg" alt="">
                                            </div>
                                            <div class="comment_text">
                                                <div class="reviews_meta">
                                                    <div class="star_rating">
                                                        <ul>
                                                        <?=$util->ShowRating($prating['Rating'])?>
                                                        </ul>   
                                                    </div>
                                                    <p><strong><?=$user->FindById($prating['RatedUser'])['UserFullName']?> </strong>- <?=date('d M Y', strtotime($prating['RateCreated']))?></p>
                                                    <span><?=$prating['Comment']?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            endforeach;
                                        ?>
                                        <div class="comment_title">
                                            <h2>Add a review </h2>
                                            <p>Your email address will not be published.  Required fields are marked </p>
                                        </div>
                                        <div class="product_review_form">
                                            <form method="post" id="rate_prod_form_id" action="#">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h3 style="font-size: 14px;color: #f2f2f2;font-weight: 700;text-transform: capitalize;font-family: 'Rubik', sans-serif;">Your Review Comment</h3>
                                                        <textarea placeholder="Enter your review comment here" name="comment" id="review_comment" ></textarea>
                                                    </div> 
                                                </div>
                                                <!-- <button type="submit">Save your review</button> -->
                                            </form>   
                                        </div> 
                                        <div class="product_ratting mb-10">
                                            <h3>Your rating( on a scale of 1 - 5 )</h3>
                                            <ul>
                                            <li>
                                                <a class="alert alert-success" id="pwrty_succ" style="display:none;"></a></li><li>
                                                <a class="alert alert-danger" id="pwrty_err" style="display:none;"></a></li><br>
                                                <li><a class="str" onclick="StarProduct('<?=$product_id_get?>',1)" title="1"><i class="ion-ios-star"></i></a></li>
                                                <li><a class="str" onclick="StarProduct('<?=$product_id_get?>',2)" title="2"><i class="ion-ios-star"></i></a></li>
                                                <li><a class="str" onclick="StarProduct('<?=$product_id_get?>',3)" title="3"><i class="ion-ios-star"></i></a></li>
                                                <li><a class="str" onclick="StarProduct('<?=$product_id_get?>',4)" title="4"><i class="ion-ios-star"></i></a></li>
                                                <li><a class="str" onclick="StarProduct('<?=$product_id_get?>',5)" title="5"><i class="ion-ios-star"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
            </div>
        </section>
        <!-- area end--> 
        <?php 
            $related_items_on_item = $room->FindByVendor($product_meta['RoomOwner']);
            // $util->Show($related_items_on_item);
        ?>
        <!--related product section area start-->
        <section class="product_section p_section1 product_black_section">
            <div class="container">
                <div class="row">
                <div class="col-12">
                        <div class="section_title">
                            <h2>More deals from <?=$user->FindById($product_meta['RoomOwner'])['UserFullName']?></h2>
                        </div> 
                    </div>  
                    <div class="col-12">
                        <div class="product_area ">
                            <div class="product_container bottom">
                                <div class="custom-row product_row1">
                                    <?php 
                                    foreach( $related_items_on_item as $riot ):
                                        if($room->isReady($riot['RoomId'])){
                                    ?>
                                    <div class="custom-col-5">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                                <a class="primary_img" href="room.php?item=<?=$riot['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($riot['RoomId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="room.php?item=<?=$riot['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($riot['RoomId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a href="room.php?item=<?=$riot['RoomId']?>" data-placement="top" data-original-title="quick view"> quick view</a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <h3><a href="room.php?item=<?=$riot['RoomId']?>"><?=$riot['RoomName']?></a></h3>
                                                <?php if($riot['RoomDiscountValue'] > 0){?>
                                                    <!-- <span class="old_price"><=$_SESSION['cry'] .' ' .$util->Forex($riot['RoomPrice'])?></span> -->
                                                <?php } ?>
                                                <span class="current_price" style="color: #faa618;"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountHotel($riot))?></span>
                                                <div class="product_hover">
                                                    <div class="product_ratings">
                                                        <ul>
                                                        <?=$util->ShowRating($room->FindProductRate($riot['RoomId']))?>
                                                        </ul>
                                                    </div>
                                                    <div class="product_desc">
                                                        <p><?=$util->Slice($riot['RoomShortDescription'], 80)?></p>
                                                    </div>
                                                    <div class="action_links">
                                                        <form action="" name="st_<?=$riot['RoomId']?>" method="post">
                                                        <ul>
                                                            <li><a href="room.php?item=<?=$riot['RoomId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                            <a href="room.php?item=<?=$riot['RoomId']?>" data-placement="top" data-original-title="c mo"> See more</a>
                                                            <li><a onclick="StarProduct('<?=$riot['RoomId']?>',3)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
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
        <!-- newsletter & footer -->
        <?php 
        require_once 'com/footer.php';
        ?>
        <!--footer area end-->
   </div>
<!-- JS
============================================ -->
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