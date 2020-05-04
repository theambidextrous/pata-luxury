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
$util = new Util();
$user = new User($util->CreateConnection());
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
            .table>tbody>tr>td{
                padding:8px!important;
                padding-left: 8px!important;
                padding-right: 4px!important;
                line-height: 0.428571!important;
                vertical-align: middle!important;
            }
        </style>
        <style>
            .m-b-20 {
                margin-bottom: 7px !important;
            }
            @media (min-width: 768px){
                .modal-dialog {
                width: 80%!important;
                margin: 30px auto;
            }
            }
            .modal-sm{
                max-width: 50%!important;
                background: goldenrod!important;
            }
            .modal-sm > .modal-content{
                background: black!important;
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
                            <h4 class="page-title">Hotel Room/Apartment Categories</h4>
                            <p class="text-muted page-title-alt">Welcome to PataShop administration panel</p>
                        </div>
                    </div>

                    <div class="row">
                        <?php 
                            $pack = new Pack($util->CreateConnection());
                            $packs = $pack->FindAll();
                            $PackId = $util->KeyGen(18);
                            if(isset($_POST['save-ctgrs'])){
                                /** manage user */
                                try{
                                    $Connection = $util->CreateConnection();
                                    $c = new Pack($Connection, $PackId, $_POST['PackName'], 1);
                                    $c->ValidateFields();
                                    if($c->Create()){
                                        $util->FlashMessage('Pack Created Successfully!');
                                        $util->ClearPost('hotelbooking-packs.php');
                                    }
                                }catch(Exception $e ){
                                    $util->FlashMessage($e->getMessage(), 0);
                                }
                            }
                        ?>
                        <div class="card-box">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group m-b-20">
                                            <label>Service Pack name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="PackName" name="PackName">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group m-b-20">
                                            <button type="submit" name="save-ctgrs" class="btn w-sm btn-default waves-effect waves-light">Create Pack Now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Cartegories -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <h4 class="m-t-0 header-title"><b>Services Packs - hotels</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Manage service packs for hotels. Service packs are those items that will be bundled with a hotel romm, apartment etc. E.G. WIFI, 
                                </p>

                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                    <?php 
                                    foreach($packs as $p): 
                                    ?>
                                        <tr>
                                            <td><?=$p['PackId']?></td>
                                            <td><?=$p['PackName']?></td>
                                            <td><a onclick="loadModal('<?=$p['PackId']?>')" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                                        </tr>
                                    <?php 
                                    endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
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

<!-- pack manager modal -->
<a href="#" data-toggle="modal" id="pack_manager_btn" style="display:none;" data-target="#pack_manager">Create New </a>
<div class="modal fade" id="pack_manager">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                <h4 class="m-t-0 header-title"><b>Manage Category</b></h4>
            </div>
            <!-- m-body -->
            <div class="modal-body">
                <div class="card-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- tabs -->
                            <div class="panel with-nav-tabs panel-primary">
                                <div class="panel-heading">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#updatepack" data-toggle="tab">Manage Pack</a></li>
                                        <li><a href="#more" data-toggle="tab">More</a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="updatepack">
                                        </div>
                                        <div class="tab-pane fade" id="more">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tabs -->  
                        </div>
                    </div>
                </div>
            </div>
            <!-- end body -->
        </div>
    </div>
</div>
<!-- end modal -->
    
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

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>
		
		
		<!-- <script src="assets/pages/jquery.dashboard_ecommerce.js"></script> -->
		<script>
        // modal ansd ajax code
        var updatePack = function(formid){
            waitingDialog.show('Updating database... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = new FormData($('#updatePackForm')[0]);
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=update-pack',
                data: data,
                contentType: false,
                processData: false,
                success: function(res){
                    var rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#succ').text(rtn.MSG);
                        $('#succ').show(500);
                        waitingDialog.hide();
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
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                }

            });
        }
        var loadModal = function(PackId){
            waitingDialog.show('Loading data... Please wait',{headerText:'PataShop Notifications',headerSize: 6,dialogSize:'sm'});
            var data = "PackId=" + PackId;
            // var DataString = new FormData(document.getElementById(''))
            $.ajax({
                type: 'post',
                url: '<?=APP_AJAX_RT?>?activity=show-pack-modal',
                data: data,
                success: function(res){
                    rtn = JSON.parse(res);
                    if(rtn.hasOwnProperty("MSG")){
                        $('#updatepack').html(rtn.data.tab1);
                        $('#more').html(rtn.data.tab2);
                        $('#pack_manager_btn').trigger('click');
                        waitingDialog.hide()
                    }
                },
                error: function(xhr, status, err){
                    console.log(err);
                    waitingDialog.hide();
                }
            })
        }
    TableManageButtons.init();
    jQuery(document).ready(function(){

        $('.summernote').summernote({
            height: 150,                 
            minHeight: null,             
            maxHeight: null,            
            focus: false       
        });
        
        $('.inline-editor').summernote({
            airMode: true            
        });

        $('.multiple-categories').select2({
            placeholder: "Select a categories",
            allowClear: true
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
        

    </body>
</html>