<?php
    session_start();
    if(!isset($_SESSION['cry'])){
        $_SESSION['cry'] = 'KES';
    }
    require_once 'lib/Util.php';
    require_once 'lib/BladeSMS.php';
    require_once 'lib/Setting.php';
    require_once 'lib/Product.php';
    require_once 'lib/Color.php';
    require_once 'lib/Size.php';
    $util = new Util();
    // $util->ShowErrors();
    $conn = $util->CreateConnection();
    $setting = new Setting($conn);
    $settings = $setting->FindAll();
    require_once 'lib/Category.php';
    require_once 'lib/Gallery.php';
    require_once 'lib/ShippingCalc.php';
    $category = new Category($conn);
    $product = new Product($conn);
    $gallery = new Gallery($conn);
    $color = new Color($conn);
    $size = new Size($conn);
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
    <title><?=$settings['SiteName']?> | Shopping Cart</title>
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
        .breadcrumb_content h3{
            color: #ffffff;
        }
        .breadcrumb_content {
            border-bottom: 1px solid #444444;
        }
        .table_desc .cart_page table tbody tr td {
            border-bottom: 1px solid #444444;
            border-right: 1px solid #444444;
        }
        .table-responsive table thead {
            background: transparent;
        }
        .table_desc {
            border: 1px solid #444444;
        }
        .table_desc .cart_page table thead tr th {
            border-bottom: 1px solid #faa618;
            border-right: 1px solid #444444;
            color: #f2f2f2;
        }
        .table_desc .cart_page table tbody tr td.product_name a {
            color: #f2f2f2;
        }
        .table_desc .cart_page table tbody tr td.product-price {
            color: #f2f2f2;
        }
        .table-responsive table tbody tr td {
            border-right: 1px solid #444444;
            color: #f2f2f2;
        }
        .table_desc .cart_page table tbody tr td.product_quantity input {
            width: 60px;
            height: 40px;
            padding: 0 5px 0 10px;
            background: none;
            border: 1px solid #faa618;
            color: white;
        }
        .coupon_inner p {
            color: #f2f2f2;
        }
        .cart_subtotal p {
            color: #f2f2f2;
        }
        .table_desc .cart_page table thead tr th {
            padding: 3px;
        }
        .table_desc .cart_page table tbody tr td {
            border-bottom: 1px solid #444444;
            border-right: 1px solid #444444;
            padding: 3px;
        }
        .table_desc .cart_page table tbody tr td.product_remove {
            min-width: 47px;
        }
        .table_desc .cart_page table tbody tr td.product_thumb {
            max-width: 85px;
            min-width: 40px;
        }
        .table_desc .cart_page table tbody tr td.product_quantity input {
            width: 60px;
            height: 40px;
            padding: 0 5px 0 10px;
            background: none;
            border: 1px solid #444444;
            color: white;
        }
        .table_desc .cart_page table tbody tr td.product_quantity .input {
            width: 90%;
            height: 40px;
            padding: 0 5px 0 10px;
            background: none;
            border: 1px solid #444444;
            color:#faa618;
            background:#444444;
        }
        .cart_submit button {
            background: #faa618;
            color: #242424;
        }
        .checkout_btn a {
            background: #faa618;
            color: #242424;
        }
        .cart_submit a {
            background: #faa618;
            border: 0;
            color: #444444;
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            height: 38px;
            line-height: 18px;
            padding: 10px 15px;
            text-transform: uppercase;
            cursor: pointer;
            -webkit-transition: 0.3s;
            transition: 0.3s;
            border-radius: 3px;
        }
        </style>
        <!--breadcrumbs area start-->
        <div class="breadcrumbs_area">
            <div class="container">   
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_content">
                            <h3>shopping cart</h3>
                            <ul>
                                <li><a href="<?=APP_HOME?>">home</a></li>
                                <li>></li>
                                <li>shopping cart</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <!--breadcrumbs area end-->

        <!--about us content area start-->
        <section class="shopping_cart_area  product_black_section" id="cart_items_refresh_div_f">
            <div class="container">
                <form action="#"> 
                    <div class="row">
                        <div class="col-12">
                            <div class="table_desc">
                                <div class="cart_page table-responsive" id="">
                                    <?php
                                        try{
                                            $calc = new ShippingCalc($conn);
                                            $util->Show($calc->Payload());
                                        }catch(Exception $e){
                                            print $e->getMessage();
                                        }
                                    ?>
                                    <table>
                                <thead>
                                    <tr>
                                        <th class="product_remove"></th>
                                        <th class="product_thumb">Image</th>
                                        <th class="product_name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product_quantity">Qty</th>
                                        <th class="product_quantity">Color</th>
                                        <th class="product_quantity">Size</th>
                                        <th class="product_total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $shipping_cost_for_logged_in_user = 0;
                                        $total_cart = [0];
                                        if(isset($_SESSION['curr_usr_cart']) && is_array($_SESSION['curr_usr_cart'])){
                                            $shipping_cost_for_logged_in_user = 1200;
                                            foreach($_SESSION['curr_usr_cart'] as $cart_item ):
                                                $this_item_meta = $product->FindById($cart_item[0]);
                                                $this_item_price = ($util->ApplyDiscount($this_item_meta));
                                                $this_item_cost = ($this_item_price*$cart_item[1]);
                                                array_push($total_cart, $this_item_cost);                        
                                    ?>
                                    <tr>
                                    <td class="product_remove"><a href="#"><i class="fa fa-trash-o"></i></a></td>
                                        <td class="product_thumb"><a href="product.php?item=<?=$cart_item[0]?>"><img style="max-width:100%;" src="<?=APP_IMG_PATH?>items/<?=$gallery->FindByTypeProduct($cart_item[0],'5003')['GalleryPath']?>" alt=""></a></td>
                                        <td class="product_name"><a href="product.php?item=<?=$cart_item[0]?>"><?=$this_item_meta['ProductName']?></a></td>
                                        <td class="product-price"><?=$_SESSION['cry'] .' '. $util->Forex($this_item_price)?></td>
                                        
                                        <td class="product_quantity">
                                            <input min="1" id="<?=$cart_item[0]?>pqty" max="100" value="<?=$cart_item[1]?>" type="number">
                                        </td>
                                        
                                        <td class="product_quantity">
                                            <select class="input" name="pcolor" id="<?=$cart_item[0]?>pcolor">
                                                <?php
                                                foreach( explode(',', $this_item_meta['ProductColors']) as $icolor ):
                                                    if( $icolor === $cart_item[2] ){
                                                        print '<option selected value="'.$icolor.'">'.$color->FindById($icolor)['ColorName'].'</option>';
                                                    }else{
                                                        print '<option value="'.$icolor.'">'.$color->FindById($icolor)['ColorName'].'</option>';
                                                    }
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                        
                                        <td class="product_quantity">
                                            <select class="input" name="psize" id="<?=$cart_item[0]?>psize">
                                                <?php
                                                foreach( $size->FindByProduct($cart_item[0]) as $isize ):
                                                    if( $isize['SizeId'] === $cart_item[3] ){
                                                        print '<option selected value="'.$isize['SizeId'].'">'.$isize['SizeValue'].'</option>';
                                                    }else{
                                                        print '<option value="'.$isize['SizeId'].'">'.$isize['SizeValue'].'</option>';
                                                    }
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                        
                                        <td class="product_total"><?=$_SESSION['cry'] .' '. $util->Forex($this_item_cost)?></td>
                                    </tr>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    <tr>
                                </tbody>
                            </table>   
                                </div>  
                                <p class="pull-left" style="padding:10px;padding:10px;color:white;background:#777;margin: 10px;border-radius: 8px;">Remember to update your cart after changes otherwise nothing will be effected</p>
                                <div class="cart_submit">
                                    <a class="checkout_btn" href="cart.php">update cart</a>
                                </div>      
                            </div>
                        </div>
                    </div>
                    <!--coupon code area start-->
                    <div class="coupon_area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code left">
                                    <h3>Coupon</h3>
                                    <div class="coupon_inner">   
                                        <p>Enter your coupon code if you have one.</p>                                
                                        <input placeholder="Coupon code" type="text">
                                        <button type="submit">Apply coupon</button>
                                    </div>    
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code right">
                                    <h3>Cart Totals</h3>
                                    <div class="coupon_inner">
                                    <div class="cart_subtotal">
                                        <p>Subtotal</p>
                                        <p class="cart_amount"><?=$_SESSION['cry'] .' '. $util->Forex(array_sum($total_cart))?></p>
                                    </div>
                                    <div class="cart_subtotal ">
                                        <p>Shipping</p>
                                        <p class="cart_amount"><span>Flat Rate:</span><?=$_SESSION['cry'] .' '. $util->Forex($shipping_cost_for_logged_in_user)?></p>
                                    </div>
                                    <a href="#">Calculate shipping</a>

                                    <div class="cart_subtotal">
                                        <p>Total</p>
                                        <p class="cart_amount"><?=$_SESSION['cry'] .' '. $util->Forex(array_sum($total_cart)+$shipping_cost_for_logged_in_user)?></p>
                                    </div>
                                    <div class="checkout_btn">
                                        <a href="checkout.php">Proceed to Checkout</a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--coupon code area end-->
                </form>
            </div>
        </section>
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
<?php 
$lopp = 0;
foreach($_SESSION['curr_usr_cart'] as $cti ): ?>
<script>
$(document).ready(function(){
    // start func
    updatef<?=$lopp?> = function(){
        qty = $('#<?=$cti[0]?>pqty').val();
        item = '<?=$cti[0]?>';
        dataString = "ProductId="+item+"&ProductQty="+qty;
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=update-qty-in-cart',
            data: dataString,
            success: function(res){
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $("#cart_items_refresh_div_f").load(window.location.href + " #cart_items_refresh_div_f" );
                    return;
                }
            }
        });
    }
    // end func
    $(document).on('change', '#<?=$cti[0]?>pqty', updatef<?=$lopp?>);
})
</script>
<script>
$(document).ready(function(){
    // start func
    updatecolorf<?=$lopp?> = function(){
        color = $('#<?=$cti[0]?>pcolor').val();
        item = '<?=$cti[0]?>';
        dataString = "ProductId="+item+"&ProductColor="+color;
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=update-color-in-cart',
            data: dataString,
            success: function(res){
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $("#cart_items_refresh_div_f").load(window.location.href + " #cart_items_refresh_div_f" );
                    return;
                }
            }
        });
    }
    // end func
    $(document).on('change', '#<?=$cti[0]?>pcolor', updatecolorf<?=$lopp?>);
})
</script>
<script>
$(document).ready(function(){
    // start func
    updatesizef<?=$lopp?> = function(){
        size = $('#<?=$cti[0]?>psize').val();
        item = '<?=$cti[0]?>';
        dataString = "ProductId="+item+"&ProductSize="+size;
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=update-size-in-cart',
            data: dataString,
            success: function(res){
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $("#cart_items_refresh_div_f").load(window.location.href + " #cart_items_refresh_div_f" );
                    return;
                }
            }
        });
    }
    // end func
    $(document).on('change', '#<?=$cti[0]?>psize', updatesizef<?=$lopp?>);
})
</script>
<?php 
$lopp++;
endforeach; ?>
</body>
</html>