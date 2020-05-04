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
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>checkout</h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li>checkout</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="shopping_cart_area  product_black_section">
            <div class="container">
                <div class="checkout_form">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <form action="#">
                                <h3>Billing Details</h3>
                                <div class="row">
                                    <div class="col-lg-12 mb-20">
                                        <p>We will use the information on your account to bill and ship your order.</p>
                                        <p class="note">To ship to a <b>different address</b>, use the <u>"Alternative Address"</u> option in your dashboard( Dashboard->Preferences ).</p>
                                    </div>
                                    <div class="col-lg-12 mb-20">
                                        <label>Full Name <span>*</span></label>
                                        <input type="text">    
                                    </div>
                                    <div class="col-lg-12 mb-20">
                                        <label>Phone<span>*</span></label>
                                        <input type="text"> 
                                    </div> 
                                    <div class="col-lg-12 mb-20">
                                        <label> Email Address   <span>*</span></label>
                                        <input type="text"> 

                                    </div> 
                                    <div class="col-12">
                                        <div class="order-notes">
                                            <label for="order_note">Order Notes</label>
                                            <textarea id="order_note" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                        </div>    
                                    </div>     	    	    	    	    	    	    
                                </div>
                            </form>    
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <form action="#">    
                                <h3>Your order Summary(cannot be edited)</h3> 
                                <div class="order_table table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> Handbag  fringilla <strong> × 2</strong></td>
                                                <td> $165.00</td>
                                            </tr>
                                            <tr>
                                                <td>  Handbag  justo	 <strong> × 2</strong></td>
                                                <td> $50.00</td>
                                            </tr>
                                            <tr>
                                                <td>  Handbag elit	<strong> × 2</strong></td>
                                                <td> $50.00</td>
                                            </tr>
                                            <tr>
                                                <td> Handbag Rutrum	 <strong> × 1</strong></td>
                                                <td> $50.00</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Cart Subtotal</th>
                                                <td>$215.00</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td><strong>$5.00</strong></td>
                                            </tr>
                                            <tr class="order_total">
                                                <th>Order Total</th>
                                                <td><strong>$220.00</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>     
                                </div>
                                <div class="panel-default">
                                    <div class="order_button">
                                        <button  type="submit">Proceed to Payment</button> 
                                    </div>    
                                </div> 
                            </form>         
                        </div>
                    </div> 
                </div>
            </div>
        </section>
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
<script src="assets/js/plugins.js"></script>
<script src="assets/js/wt.js"></script>
<!-- Main JS -->
<script src="assets/js/main.js"></script>
<?php 
require_once 'com/custom-js-main.php';
?>
</body>
</html>