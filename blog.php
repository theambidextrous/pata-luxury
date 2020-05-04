
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
    $blogs = $blog->FindAll();
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
    <title><?=$settings['SiteName']?> | Blog </title>
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
        .blog_grid .blog_content h3.post_title a {
            color: #ffffff;
        }
        .breadcrumb_content h3 {
            color: #fff;
        }
        .blog_content p.post_desc {
            color: #fff;
        }
        .breadcrumb_content,.blog_sidebar .sidebar_widget {
            border-bottom: 1px solid #444444;
        }
        .blog_sidebar h3.widget_title {
            color: #fff;
        }
        .blog_sidebar .sidebar_post .post_text h3 a {
            color: #fff;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>blog</h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li>blog</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="blog_area blog_page product_black_section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <!--blog grid area start-->
                        <div class="blog_grid_area">
                        <div class="row">
                            <?php foreach( $blogs as $blg ): ?>
                                <div class="col-lg-6 col-md-6">
                                    <div class="blog_grid">
                                        <div class="blog_thumb">
                                            <a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>"><img src="<?=APP_IMG_PATH?>misc/<?=$blg['BlogBannerPath']?>" alt=""></a>
                                        </div>
                                        <br>
                                        <div class="blog_content">
                                            <h3 class="post_title"><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>"><?=$blg['BlogTitle']?></a></h3>
                                            <div class="post_meta">
                                                <span><i class="ion-person"></i> Posted by </span>
                                                <span><a href="#">admin</a></span>
                                                <span>|</span>
                                                <span><i class="fa fa-calendar" aria-hidden="true"></i>  Posted on  
                                                <?=date('M dS, Y', strtotime($blg['created_at']))?></span> 
                                            </div>
                                            <p class="post_desc"><?=$util->Slice($blg['BlogExercept'],130)?>...</p>
                                            <a class="read_more" href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$blg['BlogId']?>">read more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        </div>
                        <!--blog grid area start-->
                    </div>

                    <div class="col-lg-3 col-md-12">
                        <!--blog sidebar start-->
                        <aside class="blog_sidebar">
                        <!--search form start-->
                            <div class="sidebar_widget search_form">
                                <form action="#">
                                    <input placeholder="Search..." type="text">
                                    <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                </form>
                            </div>
                            <!--search form end-->
                            
                            <!--categories start-->
                            <!-- <div class="sidebar_widget widget_categories">
                                <h3 class="widget_title">Categories</h3>
                                <ul>
                                    <li><a href="#">Fashion</a></li>
                                    <li><a href="#">Travel</a></li>
                                    <li><a href="#">Videos</a></li>
                                    <li><a href="#">Ecommerce</a></li>
                                </ul>
                            </div> -->
                            <!--categories end-->
                            
                            <!--recent post start-->
                            <div class="sidebar_widget recent_post">
                                <h3 class="widget_title">Recent Posts</h3>
                                <?php 
                                $recent_posts = $blog->FindRecent(8);
                                foreach( $recent_posts as $rblg ):
                                ?>
                                <div class="sidebar_post">
                                    <div class="post_img">
                                        <a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$rblg['BlogId']?>"><img src="<?=APP_IMG_PATH?>misc/<?=$rblg['BlogThumbPath']?>" alt=""></a>
                                    </div>
                                    <div class="post_text">
                                        <h3><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$rblg['BlogId']?>"><?=$rblg['BlogTitle']?></a></h3>
                                        <span><?=date('M dS, Y', strtotime($rblg['created_at']))?></span>
                                    </div>
                                    
                                </div>
                                <?php endforeach;?>
                            </div>
                            <!--recent post end-->
                            <!--recent post start-->
                            <div class="sidebar_widget popular_post">
                                <h3 class="widget_title">Popular Posts</h3>
                                <?php 
                                $popular_posts = $blog->FindPopular(5);
                                foreach( $popular_posts as $pblg ):
                                ?>
                                <div class="sidebar_post">
                                    <div class="post_img">
                                        <a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$pblg['BlogId']?>"><img src="<?=APP_IMG_PATH?>misc/<?=$pblg['BlogThumbPath']?>" alt=""></a>
                                    </div>
                                    <div class="post_text">
                                        <h3><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$pblg['BlogId']?>"><?=$pblg['BlogTitle']?></a></h3>
                                        <span><?=date('M dS, Y', strtotime($pblg['created_at']))?></span>
                                    </div>
                                    
                                </div>
                                <?php endforeach;?>
                            </div>
                            <!--recent post end-->
                            
                            <!--recent post start-->
                            <div class="sidebar_widget recent_comments">
                                <h3 class="widget_title">Recent Comments</h3>
                                
                                <?php 
                                $comment_posts = $blog->FindRecentComments(5);
                                foreach( $comment_posts as $cblg ):
                                ?>
                                <div class="sidebar_post">
                                    <div class="post_img">
                                        <a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$cblg['BlogId']?>"><img src="assets/img/icon/usr-ico.jpg" alt=""></a>
                                    </div>
                                    <div class="post_text">
                                        <h3><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$cblg['BlogId']?>"><?=$cblg['BlogTitle']?></a></h3>
                                        <span><?=date('M dS, Y', strtotime($cblg['created_at']))?></span>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                            <!--recent post end-->
            
                        </aside>
        
                        <!--blog sidebar start-->
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