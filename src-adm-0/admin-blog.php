<?php 
session_start();
if(!isset($_SESSION['usr'])){
    header("Location: index.php");
}
require_once '../mail/autoload.php';
require_once '../lib/BladeSMS.php';
require_once '../lib/Util.php';
$util = new Util();
require_once '../lib/User.php';
require_once '../lib/Blog.php';
// $util->ShowErrors();
$user = new User($util->CreateConnection());
$blog = new Blog($util->CreateConnection());
$blogs = $blog->FindAll();
if(!$util->isAdmin()){
    exit('<h1>Not found</h1>');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?=SITEMETA_DESCRIPTION?>">
        <meta name="author" content="Idd Juma Otuya - 0705007984">

        <link rel="shortcut icon" href="<?=FAVICON?>">

        <title><?=SITENAME?></title>

        <!-- DataTables -->
        <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <!-- tags and select 2 -->
        <link href="assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
        <!-- summernote -->
        <link href="assets/plugins/summernote/summernote.css" rel="stylesheet" />

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <style>
            .navbar-default {
                background-color: #010202!important;
                border-radius: 0px;
                border: none;
                margin-bottom: 0px;
            }
            .widget-panel {
                padding: 30px 20px;
                padding-left: 30px;
                border-radius: 4px;
                position: relative;
                margin-bottom: 20px;
                padding-bottom: 49px!important;
            }
            .panel {
                border: none;
                margin-bottom: 1px!important;
                background-color: #323b44;
            }
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 2px;
                line-height: 1.428571;
                vertical-align: middle;
                border-top: 1px solid #ddd;
            }
            .bootstrap-tagsinput {
                color: #ebebeb!important;
            }
        </style>
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>
        
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">
        <?php 
            /** top nav files */
            require_once 'inc/general.top.nav.php';
            /** end nav */

            /** side nav files */
            require_once 'inc/admin.side.nav.php';
            /** end nav */
        ?>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Blog Posts</h4>
                                <p class="text-muted page-title-alt">Welcome to PataShop administration panel. Here you can manage Blog Posts.</p>
                            </div>
                        </div>
                        <!-- FORM -->
                        <?php 
                        if(isset($_POST['save-stngs'])){
                            try{
                                $_SESSION['frm'] = $_POST;
                                $conn = $util->CreateConnection();
                                $BlogId = $util->KeyGen(25);
                                $util->ValidateExtension($util->FindExtension('BlogBannerPath'));
                                $util->ValidateExtension($util->FindExtension('BlogThumbPath'));
                                $util->ValidateImageDimension('BlogBannerPath', 1200,640);
                                $util->ValidateImageDimension('BlogThumbPath', 400,400);
                                $util->ValidateUploadSize('BlogBannerPath');
                                $util->ValidateUploadSize('BlogThumbPath');
                                $BlogBannerPath = $BlogId.'-banner.'.$util->FindExtension('BlogBannerPath');
                                $BlogThumbPath = $BlogId.'-thumb.'.$util->FindExtension('BlogThumbPath');
                                $_POST['BlogDescription'] = $util->HtmlEncode($_POST['BlogDescription']);
                                $b = new Blog($conn,$BlogId,$_POST['BlogTitle'],$BlogBannerPath, $BlogThumbPath,$_POST['BlogExercept'],$_POST['BlogDescription'],0,$_POST['BlogTags'], 1);
                                $b->ValidateFields();
                                $util->UploadFile('BlogBannerPath', APP_IMG_DIR.'misc/'.$BlogBannerPath);
                                $util->UploadFile('BlogThumbPath', APP_IMG_DIR.'misc/'.$BlogThumbPath);
                                if($b->Create()){
                                    $util->FlashMessage("Item Created Successfully!");
                                    unset($_SESSION['frm']);
                                    $util->RedirectTo('admin-blog.php');
                                }else{
                                    $util->FlashMessage("Item Creation Failed!", 2);
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 2);
                            }
                        }
                        if(isset($_POST['update-stngs'])){
                            try{
                                $_SESSION['frm'] = $_POST;
                                $conn = $util->CreateConnection();
                                $BlogId = $_REQUEST['blog-id'];
                                $BlogBannerPath = $_POST['HBlogBannerPath'];
                                $BlogThumbPath = $_POST['HBlogThumbPath'];
                                if(isset($_FILES["BlogBannerPath"]["name"]) && !empty($_FILES["BlogBannerPath"]["name"])){
                                    $util->ValidateExtension($util->FindExtension('BlogBannerPath'));
                                    $util->ValidateImageDimension('BlogBannerPath', 1200,640);
                                    $util->ValidateUploadSize('BlogBannerPath');
                                    $BlogBannerPath = $BlogId.'-banner.'.$util->FindExtension('BlogBannerPath');
                                    $util->UploadFile('BlogBannerPath', APP_IMG_DIR.'misc/'.$BlogBannerPath);
                                }
                                if(isset($_FILES["BlogThumbPath"]["name"]) && !empty($_FILES["BlogThumbPath"]["name"])){
                                    $util->ValidateExtension($util->FindExtension('BlogThumbPath'));
                                    $util->ValidateImageDimension('BlogThumbPath', 400,400);
                                    $util->ValidateUploadSize('BlogThumbPath');
                                    $BlogThumbPath = $BlogId.'-thumb.'.$util->FindExtension('BlogThumbPath');
                                    $util->UploadFile('BlogThumbPath', APP_IMG_DIR.'misc/'.$BlogThumbPath);
                                }
                                $_POST['BlogDescription'] = $util->HtmlEncode($_POST['BlogDescription']);
                                $b = new Blog($conn,$BlogId,$_POST['BlogTitle'],$BlogBannerPath, $BlogThumbPath,$_POST['BlogExercept'],$_POST['BlogDescription'],0,$_POST['BlogTags'], 1);
                                $b->ValidateFields();
                                if($b->Update()){
                                    $util->FlashMessage("Item Updated Successfully!");
                                    unset($_SESSION['frm']);
                                    $util->RedirectTo('admin-blog.php');
                                }else{
                                    $util->FlashMessage("Item Update Failed!", 2);
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 2);
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <h4 class="m-t-0 header-title"><b>Blog Posts</b></h4>
                                    <?php 
                                        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && !empty($_REQUEST['blog-id'])){
                                            $meta = $blog->FindById($_REQUEST['blog-id']);
                                            ?>
                                        <form method="post" enctype="multipart/form-data">
                                        <div class="card-box">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="Packaged-box">
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Title<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['BlogTitle']?>" class="form-control" id="BlogTitle" name="BlogTitle">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Banner Image(1200px X 640px)
                                                                    <img src="<?=APP_IMG_PATH . 'misc/' .$meta['BlogBannerPath']?>" class="thumb-sm" alt="">
                                                                    <span class="text-danger">*</span></label>
                                                                    <input type="hidden" value="<?=$meta['BlogBannerPath']?>" id="HBlogBannerPath" name="HBlogBannerPath">
                                                                    <input type="file" class="form-control" id="BlogBannerPath" name="BlogBannerPath">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Thumbnail Image(400px X 400px)
                                                                    <img src="<?=APP_IMG_PATH . 'misc/' .$meta['BlogThumbPath']?>" class="thumb-sm" alt="">
                                                                    <span class="text-danger">*</span></label>
                                                                    <input type="hidden" value="<?=$meta['BlogThumbPath']?>" id="HBlogThumbPath" name="HBlogThumbPath">
                                                                    <input type="file" class="form-control" id="BlogThumbPath" name="BlogThumbPath">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Exercept<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['BlogExercept']?>" class="form-control" id="BlogExercept" name="BlogExercept">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Description<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="BlogDescription" name="BlogDescription"><?=$util->HtmlDecode($meta['BlogDescription'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Tags<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['BlogTags']?>" data-role="tagsinput" class="form-control" id="BlogTags" name="BlogTags">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                <button type="submit" name="update-stngs" class="btn w-sm btn-default waves-effect waves-light">Save Changes Now</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                .select2-search__field{
                                                    width:100%!important;
                                                }
                                            </style>
                                        </div>
                                    </form>
                                    <?php } else{ ?>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="card-box">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="Packaged-box">
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Title<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$_SESSION['frm']['BlogTitle']?>" class="form-control" id="BlogTitle" name="BlogTitle">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Banner Image(1200px X 640px)<span class="text-danger">*</span></label>
                                                                    <input type="file" class="form-control" id="BlogBannerPath" name="BlogBannerPath">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Thumbnail Image(400px X 400px)<span class="text-danger">*</span></label>
                                                                    <input type="file" class="form-control" id="BlogThumbPath" name="BlogThumbPath">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Exercept<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$_SESSION['frm']['BlogExercept']?>" class="form-control" id="BlogExercept" name="BlogExercept">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Description<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="BlogDescription" name="BlogDescription"><?=$util->HtmlDecode($_SESSION['frm']['BlogDescription'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                    <label>Blog Tags<span class="text-danger">*</span></label>
                                                                    <input type="text" data-role="tagsinput" class="form-control" id="BlogTags" name="BlogTags">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                <button type="submit" name="save-stngs" class="btn w-sm btn-default waves-effect waves-light">Create Now</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                .select2-search__field{
                                                    width:100%!important;
                                                }
                                            </style>
                                        </div>
                                    </form>
                                            <?php } ?>
                                </div>

                                <!-- faq table -->
                                <div class="card-box table-responsive">
                                    <h4 class="m-t-0 header-title"><b>List of Blog Posts</b></h4>
                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    foreach($blogs as $fq): if($fq['BlogId']!= $_REQUEST['blog-id']){ ?>
                                        <tr>
                                            <td><?=$fq['BlogTitle']?></td>
                                            <td><?=$fq['created_at']?></td>
                                            <td><a href="?action=edit&blog-id=<?=$fq['BlogId']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Edit</a></td>
                                        </tr>
                                    <?php 
                                    } endforeach; ?>
                                    </tbody>
                                </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- container -->
                </div> <!-- content -->
                <?php 
                /** footer files */
                require_once 'inc/general.footer.php';
                /** end footer */
                ?>

            </div>
            
            
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

    
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/waitingfor.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- tags and select 2 -->
        <script src="assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <!-- summernote -->
        <script src="assets/plugins/summernote/summernote.min.js"></script>

        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

        <script src="assets/pages/datatables.init.js"></script>
        <script src="assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>
        
        <script>
        $(document).ready(function(){
            //SiteKeyWords
            tinymce.init({
                selector: "#BlogDescription",
                theme: "modern",
                height:150
            });
        })
        </script>
</body>
</html>