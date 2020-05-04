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
require_once '../lib/Setting.php';
$util->ShowErrors();
$user = new User($util->CreateConnection());
$setting = new Setting($util->CreateConnection());
$settings = $setting->FindAll();
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
                                <h4 class="page-title">System Settings</h4>
                                <p class="text-muted page-title-alt">Welcome to PataShop administration panel. Here you can manage system-wise Administrative settings.</p>
                            </div>
                        </div>
                        <!-- FORM -->
                        <?php 
                        $meta = $setting->FindAll();
                        if(empty($meta)){
                            $meta = $_SESSION['frm'];
                        }
                        $btn_txt = "Create Settings";
                        $SettingId = $util->KeyGen(30);
                        if($setting->isAvailable()){
                            $btn_txt = "Update Settings"; 
                            $SettingId = $meta['SettingId'];
                        }
                        if(isset($_POST['save-stngs'])){
                            try{
                                // print("is====bvghvgh==>" . json_encode(htmlentities($_POST['SiteBingCode'])));
                                $_SESSION['frm'] = $_POST;
                                $_POST['SiteAboutPage'] = $util->HtmlEncode($_POST['SiteAboutPage']);
                                $_POST['SiteServicesPage'] = $util->HtmlEncode($_POST['SiteServicesPage']);
                                $_POST['SiteContactPage'] = $util->HtmlEncode($_POST['SiteContactPage']);
                                $_POST['SiteGoogleCode'] = json_encode(htmlentities($_POST['SiteGoogleCode']));
                                $_POST['SiteFacebookCode'] = json_encode(htmlentities($_POST['SiteFacebookCode']));
                                $_POST['SiteYandexCode'] = json_encode(htmlentities($_POST['SiteYandexCode']));
                                $_POST['SiteBingCode'] = json_encode(htmlentities($_POST['SiteBingCode']));
                                $_POST['SiteShareButtons'] = json_encode(htmlentities($_POST['SiteShareButtons']));
                                $_POST['PrivacyPolicy'] = $util->HtmlEncode($_POST['PrivacyPolicy']);
                                $_POST['TermsOfUse'] = $util->HtmlEncode($_POST['TermsOfUse']);
                                $_POST['SiteMap'] = $util->HtmlEncode($_POST['SiteMap']);

                                $conn = $util->CreateConnection();
                                $s = new Setting($conn,$SettingId,$_POST['SiteName'],$_POST['SiteAddress'],$_POST['SiteRichText'],$_POST['SiteKeyWords'],$_POST['SiteGoogleCode'],$_POST['SiteFacebookCode'],$_POST['SiteYandexCode'],$_POST['SiteBingCode'],$_POST['SiteContact'],$_POST['SiteContactAlt'],$_POST['SiteEmail'],$_POST['SiteFaceBook'],$_POST['SiteTwitter'],$_POST['SiteInstagram'],$_POST['SiteRss'],$_POST['SiteYouTube'],$_POST['SiteAboutPage'],$_POST['SiteServicesPage'],$_POST['SiteContactPage'],$_POST['SiteShareButtons'],$_POST['PrivacyPolicy'],$_POST['TermsOfUse'],$_POST['SiteMap']);
                                // $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($_POST));
                                if(!$setting->isAvailable()){
                                    if($s->Create()){
                                        $util->FlashMessage("Settings Created Successfully!");
                                    }
                                }else{
                                    if($s->Update()){
                                        $util->FlashMessage("Settings Updated Successfully!");
                                    }
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 2);
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <h4 class="m-t-0 header-title"><b>System Configuration</b></h4>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="card-box">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="Packaged-box">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Name<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteName']?>" class="form-control" id="SiteName" name="SiteName">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Bussiness Address<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteAddress']?>" class="form-control" id="SiteAddress" name="SiteAddress">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Google Rich Text<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteRichText']?>" class="form-control" id="SiteRichText" name="SiteRichText">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">  
                                                                <div class="form-group m-b-20">
                                                                    <label>Website SEO Key words(separate using comma)<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteKeyWords']?>" class="form-control" data-role="tagsinput" id="SiteKeyWords" name="SiteKeyWords">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Google Analytics Code<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteGoogleCode" name="SiteGoogleCode"><?=json_decode($meta['SiteGoogleCode'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Facebook Analytics Code<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteFacebookCode" name="SiteFacebookCode"><?=json_decode($meta['SiteFacebookCode'])?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Yandex Analytics Code<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteYandexCode" name="SiteYandexCode"><?=json_decode($meta['SiteYandexCode'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Bing Analytics Code<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteBingCode" name="SiteBingCode"><?=json_decode($meta['SiteBingCode'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>HTML Share Buttons code<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteShareButtons" name="SiteShareButtons"><?=json_decode($meta['SiteShareButtons'])?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Contact Phone<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteContact']?>" class="form-control" id="SiteContact" name="SiteContact">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Contact Phone 2<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteContactAlt']?>" class="form-control" id="SiteContactAlt" name="SiteContactAlt">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Website Contact Email<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteEmail']?>" class="form-control" id="SiteEmail" name="SiteEmail">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <div class="form-group m-b-20">
                                                                    <label>Facebook handle<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteFaceBook']?>" class="form-control" id="SiteFaceBook" name="SiteFaceBook">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group m-b-20">
                                                                    <label>Twitter handle<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteTwitter']?>" class="form-control" id="SiteTwitter" name="SiteTwitter">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group m-b-20">
                                                                    <label>Instagram handle<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteInstagram']?>" class="form-control" id="SiteInstagram" name="SiteInstagram">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group m-b-20">
                                                                    <label>RSS Id<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteRss']?>" class="form-control" id="SiteRss" name="SiteRss">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group m-b-20">
                                                                    <label>YouTube Channel Id<span class="text-danger">*</span></label>
                                                                    <input type="text" value="<?=$meta['SiteYouTube']?>" class="form-control" id="SiteYouTube" name="SiteYouTube">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>About Us Page Content<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteAboutPage" name="SiteAboutPage"><?=$util->HtmlDecode($meta['SiteAboutPage'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Services Page Content<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteServicesPage" name="SiteServicesPage"><?=$util->HtmlDecode($meta['SiteServicesPage'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Contacts Page Content<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteContactPage" name="SiteContactPage"><?=$util->HtmlDecode($meta['SiteContactPage'])?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Privacy Policy<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="PrivacyPolicy" name="PrivacyPolicy"><?=$util->HtmlDecode($meta['PrivacyPolicy'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Terms of Use<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="TermsOfUse" name="TermsOfUse"><?=$util->HtmlDecode($meta['TermsOfUse'])?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-b-20">
                                                                    <label>Site Map<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" id="SiteMap" name="SiteMap"><?=$util->HtmlDecode($meta['SiteMap'])?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-b-20">
                                                                <button type="submit" name="save-stngs" class="btn w-sm btn-default waves-effect waves-light"><?=$btn_txt?></button>
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