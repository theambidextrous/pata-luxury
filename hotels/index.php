<?php
    session_start();
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    require_once '../lib/Util.php';
    require_once '../lib/BladeSMS.php';
    require_once '../lib/Setting.php';
    require_once '../lib/Room.php';
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
    $megas = $category->FindAllMegaCategory();
    $roomcategories = $roomcategory->FindAll();
    // $util->Show($room->FindHotelUserIds());
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
        .moreon{
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            color: #faa618;
            border: 2px solid #faa618;
            padding: 0 45px;
            border-radius: 3px;
            /* margin-top: 70px; */
            line-height: 46px;
            display: inline-block;
        }
        .section_title::before {
            content: "";
            height: 2px;
            background: #242424;
        }
        </style>
        <div class="slider_area slider_black owl-carousel">
            <?php 
            $sliders = $room->FindSliders();
            foreach( $sliders as $sld ):
                if($room->isReady($sld['RoomId'])){
            ?>
             <div class="single_slider" data-bgimg="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($sld['RoomId'],'5004')['GalleryPath']?>">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="slider_content">
                                <p>exclusive offer this week</p>
                                <h1><?=$sld['RoomName']?></h1>
                                <span><?=$util->Slice($sld['RoomShortDescription'], 75)?></span>
                                <p class="slider_price"><i>starting from</i> <span><?=$_SESSION['cry'].' ' .$util->Forex($util->ApplyDiscountHotel($sld))?></span></p>
                                <a class="button" href="room.php?item=<?=$sld['RoomId']?>">shop now</a>
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
                                    <?php
                                        $iter = 0;
                                        foreach( $roomcategories as $rmc ):
                                            $class='active';$aria='true';
                                            if( $iter > 0 ){ $class = ''; $aria='false'; }
                                     ?>
                                    <li>
                                        <a class="<?=$class?>" data-toggle="tab" href="#featured<?=$iter?>" role="tab" aria-controls="featured<?=$iter?>" aria-selected="<?=$aria?>"><?=$rmc['RoomCategoryName']?></a>
                                    </li>
                                    <?php
                                        $iter ++;
                                        endforeach;
                                    ?>
                                </ul>
                            </div>
                             <div class="tab-content">
                             <?php
                                $r_iter = 0;
                                foreach( $roomcategories as $rmc_c ):
                                    $class='show active';
                                    if( $r_iter > 0 ){ $class = '';}
                                ?>
                                    <div class="tab-pane fade <?=$class?>" id="featured<?=$r_iter?>" role="tabpanel">
                                         <div class="product_container">
                                            <!-- <div class="custom-row product_column3"> -->
                                            <div class="custom-row product_row2">
                                                <?php
                                                    $hotels_rooms_in_cat = $room->FindByCategory($rmc_c['RoomCategoryId']);
                                                    foreach( $hotels_rooms_in_cat as $hritc ):
                                                        if($room->isReady($hritc['RoomId'])){
                                                ?>
                                                    <!-- single item -->
                                                    <div class="custom-col-5">
                                                        <div class="single_product">
                                                            <div class="product_thumb">
                                                                <a class="primary_img" href="room.php?item=<?=$hritc['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($hritc['RoomId'],'5003')['GalleryPath']?>" alt=""></a>
                                                                <a class="secondary_img" href="room.php?item=<?=$hritc['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($hritc['RoomId'],'5005')['GalleryPath']?>" alt=""></a>
                                                                <div class="quick_button">
                                                                    <a href="room.php?item=<?=$hritc['RoomId']?>" data-placement="top" data-original-title="c mo"> See more</a>
                                                                </div>
                                                            </div>
                                                            <div class="product_content">
                                                                <div class="tag_cate">
                                                                    
                                                                    <a style="font-size: 11px;font-weight: 100;" href="hotels.php?htl=<?=$hritc['RoomOwner']?>"><?=$user->FindById($hritc['RoomOwner'])['UserFullName']?></a>

                                                                </div>
                                                                <h3><a href="room.php?item=<?=$hritc['RoomId']?>"><?=$hritc['RoomName']?></a></h3>
                                                                <div class="price_box">
                                                                    <?php if($hritc['RoomDiscountValue'] > 0){?>
                                                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($hritc['RoomPrice'])?></span>
                                                                    <?php } ?>
                                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountHotel($hritc))?></span>
                                                                </div>
                                                                <div class="product_hover">
                                                                    <div class="product_ratings">
                                                                        <ul>
                                                                        <?=$util->ShowRating($room->FindProductRate($hritc['RoomId']))?>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="product_desc">
                                                                        <p><?=$util->Slice($hritc['RoomShortDescription'], 97)?></p>
                                                                    </div>
                                                                    <div class="action_links">
                                                                        <form action="" name="st_<?=$hritc['RoomId']?>" method="post">
                                                                            <ul>
                                                                                <li><a href="room.php?item=<?=$hritc['RoomId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                                <li class="add_to_cart"><a href="room.php?item=<?=$hritc['RoomId']?>" title="reserve now ">Reserve Now</a></li>
                                                                                <li><a onclick="StarProduct('<?=$hritc['RoomId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                                            </ul>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- end single product -->
                                                <?php
                                                    }
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $r_iter++;
                                        endforeach;
                                        /** endforeach --- tabs creation */
                                    ?>
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
                            <p>Book your Hotel winth 2.5% Off</p>
                            <h2>Spacious Deluxe Suites</h2>
                            <span>We Believe That Good Design is Always in Season</span>
                            <a href="room.php?category=all">Reserve Today</a>

                       </div>

                    </div>
                </div>   
            </div>
        </section>
        <!--banner area end-->

         <!--product section area start-->
        <?php
            $hotel_users_with_rooms = $room->FindHotelUserIds();
            $r_count = count($hotel_users_with_rooms);
            foreach( $hotel_users_with_rooms as $indiv ):
                $hotel_rooms_for_this = $room->FindByVendor($indiv['RoomOwner']);
                $h_count = count($hotel_rooms_for_this);
        ?>
        <section class="product_section p_section1 product_black_section bottom" style="padding-bottom:2px;margin-bottom:4px;">
            <div class="container">
                <div class="row">  
                    <div class="col-12">
                        <div class="section_title">
                            <h2><a href="hotels.php?htl=<?=$indiv['RoomOwner']?>" class="moreon"><?=$user->FindById($indiv['RoomOwner'])['UserFullName']?></a></h2>
                        </div> 
                    </div> 
                    <div class="col-12">
                        <div class="product_area"> 
                            <div class="product_container bottom">
                                <div class="custom-row product_row1">
                                    <?php 
                                    if( $h_count > 0 ){
                                        $ri_iter = 1;
                                        foreach( $hotel_rooms_for_this as $tps ):
                                            if($room->isReady($tps['RoomId'])){
                                    ?>
                                    <div class="custom-col-5">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                                <a class="primary_img" href="room.php?item=<?=$tps['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($tps['RoomId'],'5003')['GalleryPath']?>" alt=""></a>
                                                <a class="secondary_img" href="room.php?item=<?=$tps['RoomId']?>"><img src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($tps['RoomId'],'5005')['GalleryPath']?>" alt=""></a>
                                                <div class="quick_button">
                                                    <a href="room.php?item=<?=$tps['RoomId']?>" data-placement="top" data-original-title="c mo"> See more</a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <div class="tag_cate">
                                                    <a style="font-size: 11px;font-weight: 100;" href="hotels.php?htl=<?=$tps['RoomOwner']?>"><?=$user->FindById($tps['RoomOwner'])['UserFullName']?></a>
                                                </div>
                                                <h3><a href="room.php?item=<?=$tps['RoomId']?>"><?=$tps['RoomName']?></a></h3>
                                                <div class="price_box">
                                                    <?php if($tps['RoomDiscountValue'] > 0){?>
                                                        <span class="old_price"><?=$_SESSION['cry'] .' ' .$util->Forex($tps['RoomPrice'])?></span>
                                                    <?php } ?>
                                                    <span class="current_price"><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountHotel($tps))?></span>
                                                </div>
                                                <div class="product_hover">
                                                    <div class="product_ratings">
                                                        <ul>
                                                        <?=$util->ShowRating($room->FindProductRate($tps['RoomId']))?>
                                                        </ul>
                                                    </div>
                                                    <div class="product_desc">
                                                        <p><?=$util->Slice($tps['RoomShortDescription'], 97)?></p>
                                                    </div>
                                                    <div class="action_links">
                                                        <form action="" name="sp_<?=$tps['RoomId']?>" method="post">
                                                            <ul>
                                                                <li><a href="room.php?item=<?=$tps['RoomId']?>" title="Wishlist"><span class="icon icon-Heart"></span></a></li>
                                                                <li class="add_to_cart"><a href="room.php?item=<?=$tps['RoomId']?>" title="reserve now ">Reserve Now</a></li>
                                                                <li><a onclick="StarProduct('<?=$tps['RoomId']?>',4.5)" title="Star"><i class="ion-ios-star-outline"></i></a></li>
                                                            </ul>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php 
                                                $ri_iter++;
                                            }
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </section>     
        <?php
            endforeach;
        ?>  



        <!--product section area end--> 
            <br>
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