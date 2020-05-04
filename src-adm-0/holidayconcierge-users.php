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
require_once '../lib/Pack.php';
require_once '../lib/Package.php';
// $util->ShowErrors();
$user = new User($util->CreateConnection());
$pack = new Pack($util->CreateConnection());
$packs = $pack->FindAll();
if($util->isCustomer()){
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
                        <?php 
                        switch($_REQUEST['action']){
                            default:
                                require_once 'inc/h_landing.php';
                            break;
                            case 'mrchnt-details':
                                require_once 'inc/h_manager.php';
                            break;
                        }
                        ?>
                        <!-- short cuts -->
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                               
                            </div>
                        </div>

                        <!-- end shortcuts -->
                        

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
		
		
		<!-- <script src="assets/pages/jquery.dashboard_ecommerce.js"></script> -->
		<script>
    TableManageButtons.init();
    jQuery(document).ready(function(){
        // create
        postPackage = function(formid){
            tinymce.triggerSave();
            waitingDialog.show('Updating database... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = new FormData($('#' + formid)[0]);
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=create-user-Package',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function(res){
                    var rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#succ').text(rtn.MSG);
                        $('#succ').show(500);
                        waitingDialog.hide();
                        location.reload();
                        return;
                    }
                    if(rtn.hasOwnProperty("ERR")){
                        $('#err').text(rtn.ERR);
                        $('#err').show(500);
                        waitingDialog.hide();
                        return;
                    }
                    console.log(res);
                    waitingDialog.hide();
                    return;
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                    return;
                }
            });
        }
        //details
        PackageDetailsModal = function(PackageId){
            waitingDialog.show('Loading data... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = "PackageId=" + PackageId;
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=show-package-modal',
                data: data,
                success: function(res){
                    // console.log(res);
                    rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#updateitem').html(rtn.data.tab1);
                        tinymce.init({
                            selector: "#PDescription",
                            // theme: "modern",
                            height:150
                        });
                        $('#galleries').html(rtn.data.tab2);
                        $('#itemtitle').text('Manage ' + rtn.data.item)
                        $('#booking').html(rtn.data.tab3);
                        $('#Package_manager_btn').trigger('click');
                        waitingDialog.hide()
                    }
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                }
            })
        }
        // create
        updatePackage = function(formid){
            tinymce.triggerSave();
            waitingDialog.show('Updating Package info... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = new FormData($('#' + formid)[0]);
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=update-package-item',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function(res){
                    var rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#succc').text(rtn.MSG);
                        $('#succc').show(500);
                        waitingDialog.hide();
                        location.reload();
                        return;
                    }
                    if(rtn.hasOwnProperty("ERR")){
                        $('#errr').text(rtn.ERR);
                        $('#errr').show(500);
                        waitingDialog.hide();
                        return;
                    }
                    console.log(res);
                    waitingDialog.hide();
                    return;
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                    return;
                }
            });
        }
        //create gallery
        CreateGallery = function(formid){
            waitingDialog.show('Updating database... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = new FormData($('#' + formid)[0]);
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=create-gallery-item',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function(res){
                    var rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#sc').text(rtn.MSG);
                        $('#sc').show(500);
                        waitingDialog.hide();
                        return;
                    }
                    if(rtn.hasOwnProperty("ERR")){
                        $('#er').text(rtn.ERR);
                        $('#er').show(500);
                        waitingDialog.hide();
                        return;
                    }
                    console.log(res);
                    waitingDialog.hide();
                    return;
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                    return;
                }
            });
        }
        DeleteGallery = function(GalleryId){
            waitingDialog.show('Updating database... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = "GalleryId=" + GalleryId;
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=delete-gallery-item',
                data: data,
                success: function(res){
                    var rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#' + GalleryId).remove();
                        waitingDialog.hide();
                        return;
                    }
                    if(rtn.hasOwnProperty("ERR")){
                        console.log(rtn.ERR)
                        waitingDialog.hide();
                        return;
                    }
                    console.log(res);
                    waitingDialog.hide();
                    return;
                }
            });
        }

        modalClose = function(id){
            $('#' + id).modal('hide');
            location.reload();
        }
        // editors
        tinymce.init({
            selector: "#PackageDescription",
            theme: "modern",
            height:150
        }); 
        //
        // $('.inline-editor').summernote({
        //     airMode: true            
        // });

        $('.multiple-categories').select2({
            minimumResultsForSearch: -1,
            placeholder: function(){
                $(this).data('placeholder');
            }
        })

    });
    $( document ).ready(function() {

        $(".select2").select2();
        var handleDataTableButtons = function() {
            "use strict";
        },
        TableManageButtons = function() {
            "use strict";
            return {
                init: function() {
                    handleDataTableButtons()
                }
            }
        }();
    })
</script>
        
<!-- Create Package modal -->
<div class="modal fade" id="Package_create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                <h4 class="m-t-0 header-title"><b>Create New Package</b></h4>
            </div>
            <!-- m-body -->
            <form method="post" id="create_Package_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="Packaged-box">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-success" id="succ" style="display:none;"></div>
                                        <div class="alert alert-danger" id="err" style="display:none;"></div>
                                    </div>
                                    <!-- Begin left box -->
                                    <div class="col-lg-4">
                                        <!-- row -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="text" value="" class="form-control" id="PackageName" name="PackageName" placeholder="Package name">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="text" value="" class="form-control" id="PackagePrice" name="PackagePrice" placeholder="Package Price">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <select class="form-control select2" name="PackageType" id="PackageType">
                                                        <option value="nn">Package Type</option>
                                                        <?php 
                                                        foreach( $util->PackageTypes() as $k => $v ):
                                                            print '<option value="'.$k.'">'.$v.'</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="date" value="" class="form-control" id="PackageValidTill" name="PackageValidTill" placeholder="Package Valid Till date">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- End left, begin middle -->
                                    <div class="col-lg-8">  
                                        <!-- row -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <select class="form-control select2" name="PackageCommisionType" id="PackageCommisionType">
                                                        <option value="nn">Commision Type</option>
                                                        <?php 
                                                        foreach( $util->CommissionTypes() as $k => $v ):
                                                            print '<option value="'.$k.'">'.$v.'</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="text" value="" class="form-control" id="PackageCommisionValue" name="PackageCommisionValue" placeholder="Commission Value">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <select class="form-control select2" name="PackageDiscountType" id="PackageDiscountType">
                                                        <option value="nn">Discount Type</option>
                                                        <?php 
                                                        foreach( $util->DiscountTypes() as $k => $v ):
                                                            print '<option value="'.$k.'">'.$v.'</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div> 
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="text" value="" class="form-control" id="PackageDiscountValue" name="PackageDiscountValue" placeholder="Discount Value">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End middle begin right -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <input type="text" value="" class="form-control" id="PackageMetaDescription" name="PackageMetaDescription" placeholder="Meta Description">
                                        </div>
                                        <!-- inner row -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <input type="text" value="" class="form-control" id="PackageLocation" name="PackageLocation" placeholder="Holiday location">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-b-20">
                                                    <select class="form-control select2" name="PackageOwner" id="PackageOwner">
                                                        <option value="nn">Vendor/Merchant</option>
                                                        <?php 
                                                        if($util->isAdmin()){
                                                            foreach( $user->FindByType(4004) as $kv ):
                                                                print '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                                                            endforeach;
                                                        }else{
                                                            foreach( $user->FindByType(4004) as $kv ):
                                                                if($kv == $_SESSION['usr']['UserId']){
                                                                    print '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                                                                }
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end inner -->
                                        <div class="form-group m-b-20">
                                            <select class="form-control select2" name="PackagePack[]" id="PackagePack" multiple="multiple" data-placeholder="Select Package Includes">
                                                <?php 
                                                foreach( $packs as $pk ):
                                                    print '<option value="'.$pk['PackId'].'">'.$pk['PackName'].'</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group m-b-20">
                                            <button type="button" name="save-Package" onclick="postPackage('create_Package_form')" class="btn w-sm btn-default waves-effect waves-light">Create item</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <label>Package Description<span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="PackageDescription" name="PackageDescription"></textarea>
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
            <!-- end body -->
        </div>
    </div>
</div>

<!-- manage item modal -->
<a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" id="Package_manager_btn" style="display:none;" data-target="#manage_item"></a>
<div class="modal fade" id="manage_item">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <a href="#" onclick="modalClose('manage_item')" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                <h4 class="m-t-0 header-title"><b id="itemtitle"></b></h4>
            </div>
            <!-- m-body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- tabs -->
                        <div class="panel with-nav-tabs panel-primary">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#updateitem" data-toggle="tab">Update Package</a></li>
                                    <li><a href="#galleries" data-toggle="tab">Package Gallery</a></li>
                                    <li><a href="#booking" data-toggle="tab">Package Bookings</a></li>
                                </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="updateitem">
                                    </div>
                                    <div class="tab-pane fade" id="galleries">
                                    </div>
                                    <div class="tab-pane fade" id="booking">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end tabs -->  
                    </div>
                </div>
                <style>
                    .select2-search__field{
                        width:100%!important;
                    }
                </style>
            </div>
            <!-- end body -->
        </div>
    </div>
</div>
<!-- END MODALS -->
</body>
</html>