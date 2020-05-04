
<?php
    session_start();
    if(empty($_REQUEST['blog-item']) || !isset($_REQUEST['blog-item'])){exit('<h1>404</h1>');}
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    require_once 'lib/Util.php';
    require_once 'lib/BladeSMS.php';
    require_once 'lib/Setting.php';
    require_once 'lib/Product.php';
    require_once 'lib/User.php';
    $util = new Util();
    $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    require_once 'lib/Category.php';
    require_once 'lib/Gallery.php';
    require_once 'lib/Blog.php';
    try{
        $blog = new Blog($conn);
        $user = new User($conn);
        $meta = $blog->FindById($_REQUEST['blog-item']);
        $category = new Category($conn);
        $product = new Product($conn);
        $gallery = new Gallery($conn);
        $megas = $category->FindAllMegaCategory();
    }catch(Exception $ex ){
        exit($ex->getMessage());
    }
    // $util->Show($blog->FindRelated(6));
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
    <title><?=$settings['SiteName']?> | <?=$meta['BlogTitle']?> </title>
    <meta name="description" content="<?=$meta['BlogExercept']?>">
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
        .blog_sidebar .sidebar_post .post_text h3 a,.comments_box h3 {
            color: #fff;
        }
        .blog_details_wrapper .post_content > p {
            color: #fff;
        }
        .post_meta span a{
            color: #faa618;
            padding: 4px 8px 4px 8px;
            border: solid 1px #504e4e;
            border-radius: 8px;
        }
        .post_meta span a:hover {
            color: #fff;
        }
        .related_posts h3,.related_content h3 a,.comments_form h3,.comments_form form label,.comment_list .comment_content p{
            color: #fff;
        }
        .entry_content {
            border-bottom: 1px solid #444444;
        }
        .comments_box {
            border-top: 1px solid #444444;
        }
        .comment_list .comment_content {
            border: 1px solid #444444;
        }
        .comments_form form textarea {
            background: #242424;
            border: 1px solid #444444;
            color: #fff;
            height:80px;
        }
        .button {
            text-transform: none;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3><?=$meta['BlogTitle']?></h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li><a href="<?=APP_HOME?>/blog.php">blog</a></li>
                                <li>></li>
                                <li>blog details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="blog_area blog_details product_black_section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <!--blog details area start-->
                        <div class="blog_details_wrapper">
                            <div class="blog_thumb">
                                <a href="#"><img src="<?=APP_IMG_PATH?>misc/<?=$meta['BlogBannerPath']?>" alt=""></a>
                            </div>
                            <div class="blog_content">
                                <h3 class="post_title"><?=$meta['BlogTitle']?></h3>
                                <div class="post_meta">
                                    <span><i class="ion-person"></i> Posted by </span>
                                    <span><a href="#">admin</a></span>
                                    <span>|</span>
                                    <span><i class="fa fa-calendar" aria-hidden="true"></i>  Posted on  <?=date('M dS, Y', strtotime($meta['created_at']))?>	</span> 
                                </div>
                                <div class="post_content">
                                    <?=$util->HtmlDecode($meta['BlogDescription'])?>
                                </div>
                                <div class="entry_content">
                                    <div class="post_meta">
                                        <span>Tags: </span>
                                        <?php 
                                         $tags = explode(',',$meta['BlogTags']);
                                         foreach( $tags as $tgs ):
                                            print '<span><a href="#">'.$tgs.'</a></span>';
                                         endforeach;
                                        ?>
                                        <br><br>
                                        <div class="social_sharing">
                                            <?=$util->HtmlDecode(json_decode($settings['SiteShareButtons']))?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end content, start related -->
                            <div class="related_posts">
                                <h3>Related posts</h3>
                                <div class="row">
                                <?php 
                                    $related_p = $blog->FindRelated($meta['BlogSequence']);
                                    foreach( $related_p as $rlp ):
                                ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="single_related">
                                            <div class="related_thumb">
                                                <img src="<?=APP_IMG_PATH?>misc/<?=$rlp['BlogThumbPath']?>" alt="">
                                            </div>
                                            <div class="related_content">
                                            <h3><a href="<?=APP_HOME?>/blog-details.php?blog-item=<?=$rlp['BlogId']?>"><?=$rlp['BlogTitle']?></a></h3>
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i> <?=date('M dS, Y', strtotime($rlp['created_at']))?> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <!-- end related -->
                            <!-- comments box -->
                            <div class="comments_box">
                                    <?php 
                                    $p_comments = $blog->FindComments($meta['BlogId']);
                                    ?>
                                <h3><?=count($p_comments)?> Comments	</h3>
                                <?php 
                                    foreach( $p_comments as $pc ):
                                        $comment_usr = $user->FindById($pc['CommentUser'])['UserFullName'];
                                        if(empty($comment_usr)){
                                            $comment_usr = 'Anonymous';
                                        }
                                ?>
                                    <div class="comment_list">
                                        <div class="comment_thumb">
                                            <img src="assets/img/icon/usr-ico.jpg" alt="">
                                        </div>
                                        <div class="comment_content">
                                            <div class="comment_meta">
                                                <h5><a href="#"><?=$comment_usr?></a></h5>
                                                <span><?=date('M dS, Y h:i a', strtotime($pc['created']))?></span> 
                                            </div>
                                            <p><?=$pc['Comment']?></p>
                                            <div class="comment_reply">
                                                <a href="#">Reply</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach;
                                ?>
                            </div>
                            <!-- end comment box -->
                            <div class="comments_form">
                                <h3>Leave a Reply </h3>
                                <p>Your email address will not be published. Required fields are marked *</p>
                                <?php
                                    if(isset($_POST['comment-cnt'])){
                                        try{
                                            $data = [$_POST['comment'], $_REQUEST['blog-item'], $_SESSION['usr']['UserId']];
                                            print_r($data);
                                            $c = $blog->Comment($data);
                                            if($c){
                                                $util->RedirectTo($_SERVER['PHP_SELF'].'?blog-item='.$_REQUEST['blog-item']);
                                            }
                                        }catch(Exception $ex ){
                                            print $ex->getMessage();
                                        }
                                    }
                                ?>
                                <form method="post" action="">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="review_comment">Comment </label>
                                            <textarea name="comment" id="comment" ></textarea>
                                        </div> 
                                    </div>
                                    <?php
                                        if(!empty($_SESSION['usr'])){
                                            print '<button class="button" name="comment-cnt" type="submit">Comment As <i>'.strtolower($_SESSION['usr']['UserFullName']).'</i></button>';
                                        }else{
                                            print '<a class="button" href="'.APP_HOME.'/src-adm-0/">Login to Comment</a>';
                                        }
                                    ?>
                                </form>    
                            </div>
                            <!-- end comment form -->
                        </div>
                        <!--blog details area start-->
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