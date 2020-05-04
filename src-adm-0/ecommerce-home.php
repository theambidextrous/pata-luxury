<?php 

session_start();

if(!isset($_SESSION['usr'])){

    header("Location: index.php");

}

require_once '../lib/Util.php';

require_once '../lib/User.php';

require_once '../lib/Preference.php';

require_once '../lib/RoomOrder.php';

$util = new Util();

$user = new User($util->CreateConnection());

$roomorder = new RoomOrder($util->CreateConnection());

// $util->ShowErrors();

// $util->CreateNote($_SESSION['usr']['UserId'], 'New product created.', 1);

// $util->CreateNote($_SESSION['usr']['UserId'], 'New Order has been.', 2);

// $util->CreateNote($_SESSION['usr']['UserId'], 'New customer just signed up.', 4);

// $util->CreateNote($_SESSION['usr']['UserId'], 'You logged in to your account.', 3);

// $util->Show($_SESSION['usr']);

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

        <!--Morris Chart CSS -->

		<link rel="stylesheet" href="assets/plugins/morris/morris.css">



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

            switch($_SESSION['usr']['UserType']){

                case '4003':

                    require_once 'inc/admin.side.nav.php';

                break;

                case '4004':

                    require_once 'inc/admin.side.nav.php';

                break;

                case '4005':

                    require_once 'inc/customer.side.nav.php';

                break;

            }

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

                            switch($_SESSION['usr']['UserType']){

                                case '4003':

                                    require_once 'inc/admin.center.php';

                                break;

                                case '4004':

                                    require_once 'inc/admin.center.php';

                                break;

                                case '4005':

                                    switch($_REQUEST['act']){

                                        default: require_once 'inc/customer.center.php'; break;



                                        case '9002': require_once 'inc/a.cust.9002.php'; break;

                                        case '9003': require_once 'inc/a.cust.9003.php'; break;

                                        case '9004': require_once 'inc/a.cust.9004.php'; break;

                                        case '9005': require_once 'inc/a.cust.9005.php'; break;

                                        case '9006': require_once 'inc/a.cust.9006.php'; break;



                                        case 'pref': require_once 'inc/customer.pref.php'; break;

                                        case 'prof': require_once 'inc/customer.prof.php'; break;

                                    }

                                break;

                            }

                        ?>

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

        <!-- tags and select 2 -->

        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

        <script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

        <!-- fools -->

        <script src="assets/js/bootstrap.min.js"></script>

        <script src="assets/js/detect.js"></script>

        <script src="assets/js/fastclick.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>

        <script src="assets/js/jquery.blockUI.js"></script>

        <script src="assets/js/waves.js"></script>

        <script src="assets/js/wow.min.js"></script>

        <script src="assets/js/jquery.nicescroll.js"></script>

        <script src="assets/js/jquery.scrollTo.min.js"></script>



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



        <!-- <script src="assets/plugins/morris/morris.min.js"></script>

        <script src="assets/plugins/raphael/raphael-min.js"></script> -->

				

		<!-- <script src="assets/pages/jquery.dashboard_ecommerce.js"></script> -->

		<script>

    TableManageButtons.init();



    $( document ).ready(function() {

        $('.thamani_svc').select2({

            allowClear: true

        })

        var handleDataTableButtons = function() {

            "use strict";

            0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({

                dom: "Bfrtip",

                pageLength: 2,

                buttons: [{

                    extend: "copy",

                    className: "btn-sm"

                }, {

                    extend: "csv",

                    className: "btn-sm"

                }, {

                    extend: "excel",

                    className: "btn-sm"

                }, {

                    extend: "pdf",

                    className: "btn-sm"

                }, {

                    extend: "print",

                    className: "btn-sm"

                }],

                responsive: !0

            })

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