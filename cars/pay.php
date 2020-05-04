<?php
    session_start();
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    if(!isset($_SESSION['car_order']['car'])){exit('<h1>404</h1>');}
    require_once '../mail/autoload.php';
    require_once '../lib/BladeSMS.php';
    require_once '../lib/Util.php';
    require_once '../lib/Setting.php';
    require_once '../lib/Car.php';
    require_once '../lib/Pack.php';
    require_once '../lib/Product.php';
    require_once '../lib/Pay.php';
    $util = new Util();
    // $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    require_once '../lib/Category.php';
    require_once '../lib/Gallery.php';
    require_once '../lib/Blog.php';
    require_once '../lib/User.php';
    require_once '../lib/CarOrder.php';
    $blog = new Blog($conn);
    $category = new Category($conn);
    $carorder = new CarOrder($conn);
    $car = new Car($conn);
    $user = new User($conn);
    $product = new Product($conn);
    $gallery = new Gallery($conn);
    $pack = new Pack($conn);
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
    <title><?=$settings['SiteName']?> | Checkout</title>
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
        .breadcrumb_content h3,p{
            color: #ffffff;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        .checkout_form h3 {
            text-transform: none;
            color: #000000;
            background: #faa618;
            font-weight: 300;
        }
        .checkout_form label {
            color: #f2f2f2;
            font-weight: 700;
        }
        .checkout_form input {
            border: 1px solid #444444;
            color: #fff;
        }
        .order-notes textarea {
            border: 1px solid #444444;
            color: #f2f2f2;
        }
        .table-responsive table tbody tr td {
            border-right: 1px solid #444444;
            color: #f2f2f2;
        }
        .order_table table tfoot tr th {
            color: #f2f2f2;
            border-bottom: 1px solid #444444;
        }
        .order_table table tfoot tr td {
            border-bottom: 1px solid #444444;
        }
        .order_table table tbody tr td {
            border-bottom: 1px solid #444444;
        }
        hr {
            margin-top: 4rem;
            margin-bottom: 4rem;
            border-top: 1px solid rgba(0,0,0,.1);
            background: #444444;
        }
        .table-responsive table thead {
            background: transparent;
        }
        .table-responsive table thead tr th {
            color: #f2f2f2;
            text-transform: capitalize!important;
        }
        .note{
            padding: 5px;
            background: black;
            border-radius: 5px;
            color: #e46262;
            font-weight: 300;
            font-size:18px;
        }
        .order-notes textarea {
            height: 85px;
            line-height: 23px;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>reservation</h3>
                            <ul>
                            <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li><a href="<?=APP_HOME?>/hotels">hotels</a></li>
                                <li>></li>
                                <li>reservation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <?php 
            $product_meta = $car->FindById($_SESSION['car_order']['car']);
            if(isset($_GET['pay'])){
                try{
                    $amount = intval(str_replace(',','', $_SESSION['car_order']['amt']));
                    $price = str_replace(',','',$util->Forex($product_meta['CarPrice']))*$_SESSION['car_order']['n'];
                    $comm_amt = $amount-$price;
                    $pay_btn_name = str_replace(" ", "|", $_SESSION['usr']['UserFullName']);
                    $customer_inf = $_SESSION['usr']['UserId'].'|'.$pay_btn_name.'|'.$_SESSION['usr']['UserEmail'].'|'.$_SESSION['usr']['UserPhone'];
                    $redirect_url = APP_HOME.'/src-adm-0';
                    $narration = 'Car Hire Booking via '. $settings['SiteName'];

                    if(!$carorder->exists($_SESSION['car_order']['order'])){
                        /** create order if not exists*/
                        $o = new CarOrder(
                            $conn, 
                            $_SESSION['car_order']['order'],
                            $_SESSION['usr']['UserId'],
                            $_SESSION['car_order']['car'], 
                            $_SESSION['car_order']['pickup'], 
                            $_SESSION['car_order']['fro'], 
                            $_SESSION['car_order']['to'], 
                            $_SESSION['car_order']['n'], 
                            $amount, 
                            $comm_amt
                        );
                        // $util->Show($_SESSION['car_order']);
                        $o->Create();
                    }
                    $amount = TEST_AMT;
                    $postfields = [
                        $util->KeyGen(15),
                        $util->PayCry($_SESSION['cry']),
                        $util->ToMinor($amount),
                        $_SESSION['car_order']['order'],
                        $customer_inf,
                        $redirect_url,
                        $narration
                    ];
                    ?>
                    <section class="shopping_cart_area  product_black_section">
                        <div class="container">
                            <div class="checkout_form">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4"></div>
                                    <div class="col-lg-4 col-md-4">
                                    <?=Pay::PayServiceButton($postfields)?>
                                    </div>
                                    <div class="col-lg-4 col-md-4"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php 
                        }catch(Exception $e){
                            print $e->getMessage();
                        }
                        
                    }else{                
                    ?>
        <section class="shopping_cart_area  product_black_section">
            <div class="container">
                <div class="checkout_form">
                    <?php if(isset($_SESSION['usr'])){?>
                        <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <form action="#">
                                <h3>Billing Details</h3>
                                <div class="row">
                                    <div class="col-lg-12 mb-20">
                                        <label>Full Name <span>*</span></label>
                                        <input readonly type="text" value="<?=$_SESSION['usr']['UserFullName']?>">    
                                    </div>
                                    <div class="col-lg-12 mb-20">
                                        <label>Phone<span>*</span></label>
                                        <input readonly type="text" value="<?=$_SESSION['usr']['UserPhone']?>"> 
                                    </div> 
                                    <div class="col-lg-12 mb-20">
                                        <label> Email Address   <span>*</span></label>
                                        <input readonly type="text" value="<?=$_SESSION['usr']['UserEmail']?>"> 

                                    </div> 
                                    <div class="col-12">
                                        <div class="order-notes">
                                            <label for="order_note">Order Notes</label>
                                            <textarea rows="5" id="order_note" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                        </div>    
                                    </div>     	    	    	    	    	    	    
                                </div>
                            </form>    
                        </div>
                        <div class="col-lg-8 col-md-8">
                        <?php
                            $hourdiff = round((strtotime($_SESSION['car_order']['to']) - strtotime($_SESSION['car_order']['fro']))/3600, 1);
                            $nights = floor($hourdiff/24);
                            $order_totals = $util->Forex($util->ApplyDiscountCar($product_meta)*$nights);
                            $_SESSION['car_order']['n'] = $nights;
                            $_SESSION['car_order']['amt'] = $order_totals;
                        ?>
                            <form action="#"> 
                                <h3>Your order Summary(cannot be edited)</h3> 
                                <div class="order_table table-responsive">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td> Car Name/Type <strong> </strong></td>
                                                <td><?= $product_meta['CarName']?></td>
                                            </tr>
                                            <tr>
                                                <td> Pick up Location</td>
                                                <td> <?= $_SESSION['car_order']['pickup']?></td>
                                            </tr>
                                            <tr>
                                                <td> Hire Start Date </td>
                                                <td> <?= $_SESSION['car_order']['fro']?></td>
                                            </tr>
                                            <tr>
                                                <td> Hire End Date</td>
                                                <td> <?= $_SESSION['car_order']['to']?></td>
                                            </tr>
                                            <tr>
                                                <td> No. of Days </td>
                                                <td> <?=$nights?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subtotal</strong></td>
                                                <td><strong><?=$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscountCar($product_meta))?></strong></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="order_total">
                                                <th>Order Total</th>
                                                <td><strong><?=$_SESSION['cry'] .' ' .$order_totals?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>     
                                </div>
                                <div class="panel-default">
                                    <div class="order_button">
                                        <a class="btn btn-warning" href="?pay=true">Pay Now</a> 
                                    </div>    
                                </div> 
                            </form>         
                        </div>
                    </div>
                    <?php }else{ ?>
                        <div class="col-lg-12 mb-20">
                            <p>We will use the information on your account to bill and complete your order.</p>
                            <p class="note">You <b>must <a href="<?=APP_HOME?>/src-adm-0/">LOGIN</a></b> to proceed</p>
                        </div>
                    <?php }?> 
                </div>
            </div>
        </section>
        <?php } ?>
        <hr>
        <!-- area end-->       

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
    ShowToast('Loading', 'loading payment gateway. wait', 'info');
})
</script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>