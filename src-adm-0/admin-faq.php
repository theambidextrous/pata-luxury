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
require_once '../lib/Faq.php';
// $util->ShowErrors();
$user = new User($util->CreateConnection());
$faq = new Faq($util->CreateConnection());
$faqs = $faq->FindAll();
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
                                <h4 class="page-title">FAQs</h4>
                                <p class="text-muted page-title-alt">Welcome to PataShop administration panel. Here you can manage frequently asked/answered questions.</p>
                            </div>
                        </div>
                        <!-- FORM -->
                        <?php 
                        if(isset($_POST['save-stngs'])){
                            try{
                                // print("is====bvghvgh==>" . json_encode(htmlentities($_POST['SiteBingCode'])));
                                $_SESSION['frm'] = $_POST;
                                $conn = $util->CreateConnection();
                                $Id = $util->KeyGen(20);
                                $f = new Faq($conn,$Id,$_POST['Question'],$_POST['Answer']);
                                if($f->Create()){
                                    $util->FlashMessage("Item Created Successfully!");
                                    unset($_SESSION['frm']);
                                    $util->RedirectTo('admin-faq.php');
                                }else{
                                    $util->FlashMessage("Item Creation Failed!", 2);
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 2);
                            }
                        }
                        if(isset($_POST['update-stngs'])){
                            try{
                                $conn = $util->CreateConnection();
                                $Id = $_POST['Id'];
                                $f = new Faq($conn,$Id,$_POST['Question'],$_POST['Answer']);
                                if($f->Update()){
                                    $util->FlashMessage("Item Updated Successfully!");
                                    $util->RedirectTo('admin-faq.php');
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
                                    <h4 class="m-t-0 header-title"><b>FAQs</b></h4>
                                    <?php 
                                        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && !empty($_REQUEST['faq-id'])){
                                            $meta = $faq->FindById($_REQUEST['faq-id']);
                                            ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="card-box">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="Packaged-box">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group m-b-20">
                                                                        <label>Question<span class="text-danger">*</span></label>
                                                                        <input type="hidden" value="<?=$meta['Id']?>" id="Id" name="Id">
                                                                        <input type="text" value="<?=$meta['Question']?>" class="form-control" id="Question" name="Question">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group m-b-20">
                                                                        <label>Answer<span class="text-danger">*</span></label>
                                                                        <input type="text" value="<?=$meta['Answer']?>" class="form-control" id="Answer" name="Answer">
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
                                                            <div class="col-lg-6">
                                                                <div class="form-group m-b-20">
                                                                    <label>Question<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$_SESSION['frm']['Question']?>" class="form-control" id="Question" name="Question">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group m-b-20">
                                                                    <label>Answer<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$_SESSION['frm']['Answer']?>" class="form-control" id="Answer" name="Answer">
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
                                    <h4 class="m-t-0 header-title"><b>List of FAQs available</b></h4>
                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    foreach($faqs as $fq): if($fq['Id']!= $_REQUEST['faq-id']){ ?>
                                        <tr>
                                            <td><?=$fq['Question']?></td>
                                            <td><?=$fq['Answer']?></td>
                                            <td><a href="?action=edit&faq-id=<?=$fq['Id']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Edit</a></td>
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
                selector: "#SiteAboutPage",
                theme: "modern",
                height:150
            });
            tinymce.init({
                selector: "#SiteServicesPage",
                theme: "modern",
                height:150
            });
            tinymce.init({
                selector: "#SiteContactPage",
                theme: "modern",
                height:150
            });

            tinymce.init({
                selector: "#PrivacyPolicy",
                theme: "modern",
                height:150
            });
            tinymce.init({
                selector: "#TermsOfUse",
                theme: "modern",
                height:150
            });
            tinymce.init({
                selector: "#SiteMap",
                theme: "modern",
                height:150
            });
        })
        </script>
</body>
</html>