<?php
/**
 * @filename: Util.php
 * @role: Utilities object
 * @author: avatar
 * @license : Proriatery
 */
require_once '/home2/fazalbou/public_html/patashopdir/lib/Constants.php';

class Util{
    private $pickup;
    private $dropoff;
    private $maps_key;
    
    function __construct($pickup = null, $dropoff = null, $maps_key = MAPS_KEY){
        $this->pickup = $pickup;
        $this->dropoff = $dropoff;
        $this->maps_key = $maps_key;
    }
    function key(){
        print $this->maps_key;
    }
    function Currencies(){
        return [
            'KES' => 'KES',
            'USD' => 'USD'
        ];
    }
    function Countries(){
        return [
            'KENYA' => 'KENYA',
            'UGANDA' => 'UGANDA',
            'TANZANIA' => 'TANZANIA',
            'INDIA' => 'INDIA',
            'USA' => 'USA',
            'SOMALIA' => 'SOMALIA'
        ];
    }
    function DiscountTypes(){
        return [
            1003 => 'Percentage',
            1004 => 'Fixed Amount'
        ];
    }
    function PackageTypes(){
        return [
            6003 => 'Holiday',
            6004 => 'Concierge'
        ];
    }
    function getPackageName($key){
        foreach( $this->PackageTypes() as $k => $v ){
            if($k == $key){
                return $v;
            }
        }
    }
    function Shippers(){
        return [
            2003 => 'Sendy',
            2004 => 'Owner',
            2005 => 'Not Shipped'
        ];
    }
    function CommissionTypes(){
        return [
            3003 => 'Percentage',
            3004 => 'Fixed Amount'
        ];
    }
    function UserTypes(){
        return [
            4003 => 'Admin',
            4004 => 'Merchant',
            4005 => 'Customer'
        ];
    }
    function UserModules(){
        return [
            9001 => 'All',
            9002 => 'E-commerce',
            9003 => 'Hotels',
            9004 => 'Cars',
            9005 => 'Holidays',
            9006 => 'Concierge'
        ];
    }
    function getThamaniItems(){
        $s = $this->CreateConnection()->prepare("SELECT * FROM `p_concierge`");
        $s->execute();
        $i = $s->errorInfo();
        if($i[0] != '00000'){
            throw new Exception('could not concierge services');
        }
        $res = $s->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function isAdmin(){
        $t = $_SESSION['usr']['UserType'];
        if($t == '4003'){
            return true;
        }
        return false;
    }
    function isMerchant(){
        $t = $_SESSION['usr']['UserType'];
        if($t == '4004'){
            return true;
        }
        return false;
    }
    function isCustomer(){
        $t = $_SESSION['usr']['UserType'];
        if($t == '4005'){
            return true;
        }
        return false;
    }
    function GalleryTypes(){
        return [
            5003 => 'Thumbnail (350X350)',
            5004 => 'Slider (1920X630)',
            5005 => 'Banner (600X600)',
            5006 => 'Video (Youtube code or full url)'
        ];
    }
    function GalleryName($type){
        foreach( $this->GalleryTypes() as $u => $v ){
            if($u == $type){
                return $v;
            }
        }
    }
    function SubscribeNews($data){
        $this->ShowErrors();
        $statement = $this->CreateConnection()->prepare("INSERT INTO `subscribers`(`email`) VALUES (:a)");
        $statement->execute([':a'=>$data[0]]);
        $err = $statement->errorInfo();
        if($err[0] != '00000'){
            throw new Exception("Could not save email");
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($err));
            return false;
        }
        return true;
    }
    function SizeNames(){
        return [
            'Waist',
            'Wrist',
            'Shoe',
            'Other'
        ];
    }
    function Show($toshow){
        print'<pre>';
        print_r($toshow);
        print'</pre>';
    }
    function Slice($i, $l){
        return substr($i, 0, $l);
    }
    function ToUSD($amt){
        if($_SESSION['cry'] == 'KES'){
            $rate = $this->getForexRates('KES');
            return number_format(($amt/$rate),0);
        }
    }
    function Forex($amt){
        if($_SESSION['cry'] == 'KES'){
            $rate = $this->getForexRates('KES');
            return number_format(($amt/$rate),0);
        }elseif($_SESSION['cry'] == '$'){
            $rate = $this->getForexRates();
            return number_format(($amt/$rate),0);
        }
    }
    function PagesCount($total_items_count, $limit){
        $no_of_pages = floor($total_items_count/$limit);
        $decimal_v = ($total_items_count/$limit) - round(floor($total_items_count/$limit));
        if($decimal_v > 0){
            return $no_of_pages = $no_of_pages + 1;
        }
        return $no_of_pages;
    }
    function Paginator($data){
        $p = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        if(!isset($_REQUEST['page'])){
            $_REQUEST['page'] = 1;
            $p = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&page=';
        }
        $no_of_pages = $this->PagesCount($data[0], $data[1]);
        $l = 0;
        $li = '';
        while($l < $no_of_pages ){
            //category=all&page=1
            $nnn = explode('=', $p);
            $p = $nnn[0].'='.$nnn[1].'=';
            $ll = $l+1;
            if($ll == $_REQUEST['page']){
                $li .= '<li class="current"><a href="'.$p.$ll.'">'.$ll.'</a></li>';
            }else{
                $li .= '<li><a href="'.$p.$ll.'">'.$ll.'</a></li>';
            }
        $l ++;
        }
        return $li;
    }
    function FilterRange($v){
        if($_SESSION['cry'] == 'KES'){
            $rate = $this->getForexRates('KES');
            return number_format(($amt/$rate),0);
        }elseif($_SESSION['cry'] == '$'){
            $rate = $this->getForexRates();
            return number_format(($amt/$rate),0);
        }
    }
    function getForexRates($c = 'USD' ){
        $s = $this->CreateConnection()->prepare("SELECT `ForexRate` FROM `forex` WHERE `ForexCurrency` = :a");
        $s->execute([':a'=>$c]);
        $i = $s->errorInfo();
        if($i[0] != '00000'){
            throw new Exception('could not get forex rates');
        }
        $res = $s->fetch(PDO::FETCH_ASSOC);
        return $res['ForexRate'];
    }
    function ValidatePhone($tel){
        $tel = str_replace(' ', '', $tel);
        $allowed = ['01','02','03','04','05','06','07','08','09'];
        if( !in_array(substr( $tel, 0, 2 ), $allowed) ){
            throw new Exception('Invalid phone number '.$tel.'. Phone must start with any of '.implode(',',$allowed).'');
        }
        if(strlen($tel) != 10){
            throw new Exception('Invalid phone number '.$tel.'. Phone must be 10 digits long. ignore the country code.');
        }
    }
    function CamelCase($in){
        return $out = ucwords(strtolower($in));
    }
    function ShowRating($rate){
        $uli = '';
        $whole = floor($rate);
        $decimal = $rate - $whole;
        if( $whole > 5){$whole = 5;}
        $l = 0;
        while($l < $whole){
            $uli .= '<li><a href="#"><i class="ion-ios-star"></i></a></li>';
            $l++;
        }
        if($whole < 5 && $decimal > 0){
            $uli .= '<li><a href="#"><i class="ion-ios-star-half"></i></a></li>';
        }
        $blanks = 5 - $whole;
        if($blanks > 0 ){
            $l2 = 0;
            if($decimal > 0)
                $blanks = $blanks - 1;
            while($l2 < $blanks){
                $uli .= '<li><a href="#"><i class="ion-ios-star-outline"></i></a></li>';
                $l2++;
            }
        }
        return $uli;
    }
    function CategoryNaming($obj, $c){
        $mother_d = $obj->FindById($c['CategoryParent']);
        $mother = $mother_d['CategoryName'];
        $mother_mother = $obj->FindById($mother_d['CategoryParent'])['CategoryName'];
        $nomenclecture = '';
        if(strlen($mother_mother)){
            $nomenclecture = $mother_mother.' > '.$mother.' > '.$c['CategoryName'];
        }else{
            if(strlen($mother)){
                $nomenclecture = $mother.' > '.$c['CategoryName'];
            }else{
                $nomenclecture = $c['CategoryName'];
            }
        }
        return $nomenclecture;
    }
    function CategoryLinks($obj, $categoryids){
        $categoryids = explode(',',$categoryids);
        foreach($categoryids as $categoryid ):
            $init_meta = $obj->FindById($categoryid);
            $mother_d = $obj->FindById($init_meta['CategoryParent']);
            $mother = $mother_d['CategoryName'];

            $mother_mother = $obj->FindById($mother_d['CategoryParent'])['CategoryName'];
            $nomenclecture = '';
            if(strlen($mother_mother)){
                $nomenclecture = $mother_mother.'->'.$mother.'->'.$init_meta['CategoryName'];
                $nomenclecture = $mother_mother.'->'.$init_meta['CategoryName'];
                $nomenclecture = '<a style="font-size: 11px;font-weight: 100;color: #faa618;" href="'.APP_HOME.'/shop.php?category='.$categoryid.'">'.$nomenclecture.'</a>';
                $rtn[] = $nomenclecture;
            }else{
                if(strlen($mother)){
                    $nomenclecture = $mother.'->'.$init_meta['CategoryName'];
                    $nomenclecture = '<a style="font-size: 11px;font-weight: 100;color: #faa618;" href="'.APP_HOME.'/shop.php?category='.$categoryid.'">'.$nomenclecture.'</a>';
                    $rtn[] = $nomenclecture;
                }else{
                    $nomenclecture = $init_meta['CategoryName'];
                    $nomenclecture = '<a style="font-size: 11px;font-weight: 100;color: #faa618;" href="'.APP_HOME.'/shop.php?category='.$categoryid.'">'.$nomenclecture.'</a>';
                    $rtn[] = $nomenclecture;
                }
            }
        endforeach;
        return implode(', ',$rtn);
    }
    function DiscountItem($meta, $key = 'Product'){
        $v = $meta[ $key . 'DiscountValue'];
        if($v < 1 ){
            return floor($meta[ $key . 'Price']);
        }
        if($meta[ $key . 'DiscountType'] == '1003'){//%
            return floor( ((100-$v) * $meta[ $key . 'Price'])/100 );
        }
        if($meta[ $key . 'DiscountType'] == '1004'){
            floor(($meta[ $key . 'Price'] - $v));
        }
    }
    function ApplyDiscount($meta){
        $v = $meta['ProductDiscountValue'];
        if($v < 1 ){
            return $this->ApplyMarkUp($meta, $meta['ProductPrice']);
        }
        if($meta['ProductDiscountType'] == '1003'){//%
            return $this->ApplyMarkUp($meta, (((100-$v)*$meta['ProductPrice'])/100));
        }elseif($meta['ProductDiscountType'] == '1004'){
            return $this->ApplyMarkUp($meta, ($meta['ProductPrice'] - $v));
        }
    }
    function ApplyDiscountHoliday($meta){
        $v = $meta['PackageDiscountValue'];
        if($v < 1 ){
            // return $meta['PackagePrice'];
            return $this->ApplyMarkUp($meta, $meta['PackagePrice'], 'Package');
        }
        if($meta['PackageDiscountType'] == '1003'){//%
            return $this->ApplyMarkUp($meta, (((100-$v)*$meta['PackagePrice'])/100), 'Package');
        }elseif($meta['PackageDiscountType'] == '1004'){
            return $this->ApplyMarkUp($meta, ($meta['PackagePrice'] - $v), 'Package');
        }
    }
    function ToMinor($in){
        return str_replace(',','', $in)*100;
    }
    function ApplyDiscountHotel($meta){
        $v = $meta['RoomDiscountValue'];
        if($v < 1 ){
            // return $meta['RoomPrice'];
            return $this->ApplyMarkUp($meta, $meta['RoomPrice'], 'Room');
        }
        if($meta['RoomDiscountType'] == '1003'){//%
            return $this->ApplyMarkUp($meta, (((100-$v)*$meta['RoomPrice'])/100), 'Room');
        }elseif($meta['RoomDiscountType'] == '1004'){
            return $this->ApplyMarkUp($meta, ($meta['RoomPrice'] - $v), 'Room');
        }
    }
    function RemoveStyle($in){
        return preg_replace('/(<[^>]+) style=".*?"/i', '$1', $in);
    }
    function ApplyDiscountCar($meta){
        $v = $meta['CarDiscountValue'];
        if($v < 1 ){
            // return $meta['CarPrice'];
            return $this->ApplyMarkUp($meta, $meta['CarPrice'], 'Car');
        }
        if($meta['CarDiscountType'] == '1003'){//%
            return $this->ApplyMarkUp($meta, (((100-$v)*$meta['CarPrice'])/100), 'Car');
        }elseif($meta['CarDiscountType'] == '1004'){
            return $this->ApplyMarkUp($meta, ($meta['CarPrice'] - $v), 'Car');
        }
    }
    function ApplyMarkUp($meta, $price, $key = 'Product'){
        $v = $meta[ $key . 'CommisionValue'];
        if($v < 1 ){
            return $price;
        }
        if($meta[ $key . 'CommisionType'] == '3003'){//%
            return (((100+$v)*$price)/100);
        }elseif($meta[ $key . 'CommisionType'] == '3004'){
            return ($price + $v);
        }
    }
    function ValidateUploadSize($input){
        if($_FILES[$input]["size"] < 1000000){
            return true;
        }else{
            throw new Exception('Invalid File size in KBs');
            return false;
        }
    }
    function PayCry($in){
        if($in == 'KES'){
            return $in;
        }
        return 'USD';
    }
    function HolidayBookingForm($item, $form_id){
        $form_id = "'".$form_id."".$item."'";
        $qtytype = 'text';
        return '
            <div class="product_variant quantity">
                <label>Adults</label>
                <input style="width: 30%;" name="adultQty" id="adultQty" min="1" max="50" value="1" type="number">
            </div>
            <div class="product_variant quantity">
                <label>Children(under 4)</label>
                <input style="width: 30%;" name="childQty" id="childQty" min="0" max="5" value="0" type="number">
            </div>
            <div class="product_variant quantity">
                <label>Checkin</label>
                <input name="indate" id="indate" min="0" type="date">
            </div>
            <div class="product_variant quantity">
                <input name="PackageId" id="PackageId" value="'.$item.'" type="hidden">
                <button name="reserver_pay" class="button" type="submit">Reserve & Pay</button>
            </div>
            ';
    }
    function CarBookingForm($item, $form_id){
        $form_id = "'".$form_id."".$item."'";
        $qtytype = 'text';
        return '
            <div class="product_variant quantity">
                <label>Your Location(where the car will be brought)</label>
            </div>
            <div class="product_variant quantity">
                <input style="width: 100%;" name="pickup" id="pickup" type="text">
                '.$this->placeAutocomplete('pickup').'
            </div>
            <div class="product_variant quantity">
                <label>Hire From</label>
                <input name="fromdate" id="fromdate" min="0" type="date">
                <label style="margin-left: 8px;">Hire Till</label>
                <input name="tilldate" id="tilldate" min="0"  type="date">
            </div>
            <div class="product_variant quantity">
                <input name="CarId" id="CarId" value="'.$item.'" type="hidden">
                <button name="reserver_pay" class="button" type="submit">Reserve & Pay</button>
            </div>
            ';
    }
    function HotelBookingForm($item, $form_id){
        $form_id = "'".$form_id."".$item."'";
        $qtytype = 'text';
        return '
            <div class="product_variant quantity">
                <label>Adults</label>
                <input style="width: 9%;" name="adultQty" id="adultQty" min="1" max="50" value="1" type="number">
            </div>
            <div class="product_variant quantity">
                <label>Children(under 4)</label>
                <input style="width: 9%;" name="childQty" id="childQty" min="0" max="5" value="0" type="number">
            </div>
            <div class="product_variant quantity">
                <label>Checkin</label>
                <input name="indate" id="indate" min="0" type="date">
                <label style="margin-left: 8px;">Checkout</label>
                <input name="outdate" id="outdate" min="0"  type="date">
            </div>
            <div class="product_variant quantity">
                <input name="ProductId" id="ProductId" value="'.$item.'" type="hidden">
                <button name="reserver_pay" class="button" type="submit">Reserve & Pay</button>
            </div>
            ';
    }
    function CreateCartFormDetails($item, $form_id){
        $label = '<label>quantity</label>';
        $form_id = "'".$form_id."".$item."'";
        return 
            $label . '
            <input name="ProductQty" id="ProductQty" min="1" max="100" value="1" type="'.$qtytype.'">
            <input name="ProductId" id="ProductId" value="'.$item.'" type="hidden">
            <button class="button" onclick="AddToCart('.$form_id.')" type="button">add to cart</button>
            ';
    }
    function CreateCartForm($item, $form_id){
        $form_id = "'".$form_id."".$item."'";
        return '
            <input name="ProductQty" id="ProductQty" value="1" type="hidden">
            <input name="ProductId" id="ProductId" value="'.$item.'" type="hidden">
            <li class="add_to_cart"><a onclick="AddToCart('.$form_id.')" title="add to cart">add to cart</a></li>
            ';
    }
    function UploadFile($input, $target){
        $moved = move_uploaded_file($_FILES[$input]["tmp_name"], $target);
        if($moved){
            return true;
        }
        $this->log($_FILES[$input]["error"]);
        throw new Exception('File Upload failed. Error code = ' .$_FILES[$input]["error"]);
    }
    function CodeToNames($obj, $ids, $identifier){
        $ids = explode(',', $ids);
        foreach( $ids as $id ){
            $names[] = $obj->FindById($id)[$identifier];
        }
        return implode(',', $names);
    }
    function ArrayUnique($e){
        if(is_array($e)){
            $a = array_unique($e);
            return implode(',',$a);
        }
        $e = explode(',',$e);
        $a = array_unique($e);
        return implode(',',$a);
    }
    function ValidateImageDimension($input,$w_required, $h_required){
        $fileinfo = @getimagesize($_FILES[$input]["tmp_name"]);
        $width = $fileinfo[0];
        $height = $fileinfo[1];
        if($width == $w_required && $height == $h_required){
            return true;
        }else{
            throw new Exception('Invalid File Dimensions');
            return false;
        }
    }
    function FindExtension($input){
        $ex =  strtolower(pathinfo($_FILES[$input]["name"],PATHINFO_EXTENSION));

        return $ex;
    }
    function ValidateEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid Email Address');
           return false; 
        }else{
            return true;
        }
    }
    function isUrl($i){
        if (strpos($i, 'http://') !== false) {
            return true;
        }
        if (strpos($i, 'https://') !== false) {
            return true;
        }
        if (strpos($i, 'www.') !== false) {
            return true;
        }
        if (strpos($i, '.org') !== false) {
            return true;
        }
        if (strpos($i, '.co.ke') !== false) {
            return true;
        }
        if (strpos($i, '.com') !== false) {
            return true;
        }
        if (strpos($i, '.net') !== false) {
            return true;
        }
        return false;
    }
    function ValidateExtension($ext){
        if (in_array($ext, ALLOWED_EXT)) {
           return true; 
        }else{
            throw new Exception('Invalid File type');
            return false;
        }
    }
    function NoteIcons($type){
        switch($type){
            case '1':return 'fa-shopping-cart';break;
            case '2':return 'fa-diamond';break;
            case '3':return 'fa-bell-o';break;
            case '4':return 'fa-user-plus';break;
        }
    }
    function CreateNote($User, $Note, $NoteType){
        $NoteId = $this->KeyGen(5);
        $statement = $this->CreateConnection()->prepare("INSERT INTO `user_notes`(`NoteId`, `Note`, `NoteUserId`, `NoteType`) VALUES (:a,:b,:c,:d)");
        $statement->bindParam(':a', $NoteId, PDO::PARAM_STR);
        $statement->bindParam(':b', $Note, PDO::PARAM_STR);
        $statement->bindParam(':c', $User, PDO::PARAM_STR);
        $statement->bindParam(':d', $NoteType, PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not create user note. Contact Admins');
            return false;
        }
        return true;
    }
    function placeAutocomplete($id){
        print '
        <script src="https://maps.googleapis.com/maps/api/js?key='.MAPS_KEY.'&libraries=places&sensor=false&callback=initialize" async defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>
        $(document).ready(function () {
            try{
                google.maps.event.addDomListener(window, \'load\', initialize);
            } catch (error) {
                console.log(error.message);
            }
        });
        function initialize() {
            var input = document.getElementById(\''.$id.'\');
            var autocomplete = new google.maps.places.Autocomplete(input);
        }
        </script>';
    }
    function RedirectTo($to, $t = 0){
        if( $t > 0 ){
            print '<script>if(window.location.href.substr(-2) !== "?r") {
                window.location = window.location.href + "?r";
              }</script>';
            return;
        }
        print '<script>window.location.replace("'.$to.'")</script>';
    }
    function FlashMessage($msg, $type = 1){
        if($type == 1){
            print '<div class="alert alert-success">'.$msg.'</div>';
        }else{
            print '<div style="background:red;color:white;" class="alert alert-danger">'.$msg.'</div>';
        }
    }
    function KeyGen($length = 10, $kind = 0){
        $y = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($kind > 0){
            $y = '0123456789';
        }
        return substr(str_shuffle(str_repeat($x=$y, ceil($length/strlen($x)) )),1,$length);
    }
    function Otp($length = 10){
        return substr(str_shuffle(str_repeat($x='123456789', ceil($length/strlen($x)) )),1,$length);
    }
    function HtmlEncode($string){
        return htmlentities($string, ENT_QUOTES);
    }
    function HtmlDecode($string){
        return html_entity_decode($string, ENT_QUOTES);
    }
    function ShowErrors($f=0){
        if($f == 0){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }
    function isInCart($item){
        foreach( $_SESSION['curr_usr_cart'] as $cartt ){
            if(in_array($item, $cartt)){
                return true;
            }
        }
        return false;
    }
    function UpdateCartItemQty($item, $qty, $t = 0){
        /** $item, $qty, $color, $size */
        $l=0;
        foreach( $_SESSION['curr_usr_cart'] as $cart ){
            if(in_array($item, $cart)){
                unset($_SESSION['curr_usr_cart'][$l]);
                $_SESSION['curr_usr_cart'] = array_values($_SESSION['curr_usr_cart']);
                $new_qty = $cart[1]+$qty;
                if( $t > 0 ){
                    $new_qty = $qty;
                }
                $new_cart = [$cart[0], $new_qty, $cart[2], $cart[3]];
                array_push($_SESSION['curr_usr_cart'], $new_cart);
                return true;
            }
            $l++;
        }
        return false;
    }
    function UpdateCartItemSize($item, $size){
        /** $item, $qty, $color, $size */
        $l=0;
        foreach( $_SESSION['curr_usr_cart'] as $cart ){
            if(in_array($item, $cart)){
                unset($_SESSION['curr_usr_cart'][$l]);
                $_SESSION['curr_usr_cart'] = array_values($_SESSION['curr_usr_cart']);
                $new_cart = [$cart[0], $cart[1], $cart[2], $size];
                array_push($_SESSION['curr_usr_cart'], $new_cart);
                return true;
            }
            $l++;
        }
        return false;
    }
    function UpdateCartItemColor($item, $color){
        /** $item, $qty, $color, $size */
        $l=0;
        foreach( $_SESSION['curr_usr_cart'] as $cart ){
            if(in_array($item, $cart)){
                unset($_SESSION['curr_usr_cart'][$l]);
                $_SESSION['curr_usr_cart'] = array_values($_SESSION['curr_usr_cart']);
                $new_cart = [$cart[0], $cart[1], $color, $cart[3]];
                array_push($_SESSION['curr_usr_cart'], $new_cart);
                return true;
            }
            $l++;
        }
        return false;
    }
    function RemoveFromCart($item){
        $l=0;
        foreach( $_SESSION['curr_usr_cart'] as $cart ){
            if(in_array($item, $cart)){
                unset($_SESSION['curr_usr_cart'][$l]);
                $_SESSION['curr_usr_cart'] = array_values($_SESSION['curr_usr_cart']);
                return true;
            }
            $l++;
        }
        return false;
    }
    function ClearApostrophe($data){
        if(is_array($data)){
            foreach( $data as $d ):
                $new_data[] = str_replace("'", "", $d);
            endforeach;
            return $new_data;
        }else{
            return str_replace("'", "", $data);
        }
    }
    function ClearPost($to){
        print "<script>window.onload = function() {window.location.href='".$to."';}</script>";
    }
    function ShowSpinzam($idx){
        //return $res = '<iframe src="https://spinzam.com/shot/embed/?idx=146283" width="640" height="640" frameborder="0" scrolling="no" style="max-width:100%; max-height:100vw;"></iframe>';
        return $rtn = '
        <iframe class="spinner" src="https://spinzam.com/shot/embed/?idx='.$idx.'" width="580" height="550" frameborder="0" scrolling="no"></iframe>
        ';
    }
    function ShowVideo($url = 'mFBJtuQ1Llc'){
        $start = substr($url, 0, 5);
        if( $start === 'https' || $start === 'http:' ){
            return $rtn = '
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="'.$url.'" allowfullscreen></iframe>
            </div>'; 
        }
        return $rtn = '
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$url.'" frameborder="0" allowfullscreen></iframe>
        </div>
        ';
    }
    function CreateConnection(){
        try{
            $conn = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME."", DBUSER, DBPASS);
            return $conn;
        }catch(Exception $e){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- ' . $e->getMessage());
            return null;
        }
    }
    function log($err){
        file_put_contents(APP_ERR_DIR . 'logs.log', $err . ' AT: ' . $this->DateFormat(date("Y/m/d h:i:s a")) . PHP_EOL, FILE_APPEND | LOCK_EX );
        return;
    }
    function ValidatePasswordStrength($password){
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            throw new Exception('Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
            return false;
        }
        return true;
    }
    function DateFormat($date, $format = 'Y-m-d h:i:s a'){
        $dt = new DateTime($date);
        return $dt->format($format);
    }
    function Fill($content, $data){
        return str_replace($data[0], $data[1], $content);
    }
    function ExtractLatLng(){
        return $r = !empty(explode(',',$this->ComputeDistance()['lat_lng']))?explode(',',$this->ComputeDistance()['lat_lng']):[];
    }
    function ComputeDistance(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, MAP_ENDPOINT . 'origins='.urlencode($this->pickup).'&destinations='.urlencode($this->dropoff).'&key='.$this->maps_key);
        $content = curl_exec($ch);
        $resp = json_decode($content, true);
        // print_r($resp);
        // return $resp;
        $return = [
            'd' => floor($resp['rows'][0]['elements'][0]['distance']['value']/1000),
            't' => floor($resp['rows'][0]['elements'][0]['duration']['value']/60)
        ];
        if(count($return) < 1){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($resp));
            throw new Exception('System could not understand your Address.');
            return false;
        }
        return  $return;
    }
    function GetLonLat($location_name){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, MAP_LOC_ENDPOINT . 'address='.urlencode($location_name).'&key='.$this->maps_key);
        $content = curl_exec($ch);
        $resp = json_decode($content, true);
        $return = !empty($resp['results'][0]['geometry']['location'])?$resp['results'][0]['geometry']['location']:[];
        if(count($return) < 1){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($resp));
            throw new Exception('System could not understand your Address.');
            return false;
        }
        return $return;
    }
    function FindUserById($id){
        $statement = $this->CreateConnection()->prepare("SELECT * FROM `p_users` WHERE `UserId`=:a AND `UserStatus` = '1'");
        $statement->execute([':a' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get User. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function Sms($r, $m){
        // $r = '+254'.substr(1, 9, $r);
        $sms_init = new BytebladeSMS(SMS_API_USER, SMS_API_KEY);
        try {
            $sms_init->sendMessage($r, $m, SMS_ID);
        }
        catch ( BytebladeSMSException $e ){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- ' . $e->getMessage());
            return $e->getMessage();
        }
    }
    function Mail($params, $attachment = ''){
        $subject = $params[0];
        $file_name = str_ireplace(' ', '-', $subject);
        $sendto = $params[1];
        $sendtoName = $params[2];
        $body = $params[3];
         try{
             $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
             ->setUsername(SMTP_USER)
             ->setPassword(SMTP_PASSWORD);
             $mailer = new Swift_Mailer($transport);
             $message = (new Swift_Message($subject))
             ->setFrom([SMTP_USER => SITENAME])
             ->setTo([$sendto => $sendtoName])
             ->setCc([NRB_MAIL => SITENAME])
             ->setReplyTo([INFO_MAIL => SITENAME])
             ->setBody($body)
             ->addPart($body, 'text/html');
             if(!empty($attachment)){
                 $message->attach(
                    Swift_Attachment::fromPath($attachment)->setFilename($file_name . '.pdf')
                 );
             }
             $result = $mailer->send($message);
         }catch(Swift_RfcComplianceException $e){
            $this->log('File: '.__FILE__.' at line '.__LINE__.' Err:- ' . $e->getMessage());
         }
    }
    function FetchMailTemplates($kind = 0){
        return file_get_contents(APP_DIR.'mail-templates/'.$kind.'.txt');
    }
    function HashPassword($pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }
    function ShowOtpForm($user){
        print '
        <form class="form-horizontal m-t-20" action="" method="post">
        <p>A one-time-password(otp) has been send to your email/Phone</p>
        <div class="form-group ">
            <div class="col-xs-12">
                <input name="UserEmail" id="UserEmail" type="hidden" value="'.$user.'">
                <input class="form-control" name="UserOtp" id="UserOtp" type="text" required="" placeholder="Enter OTP here">
            </div>
            <div class="col-xs-12">
            <br>
            <button name="xotp" class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">Submit OTP</button>
            </div>
        </div></form><br><br><br>
        ';
    }
    function ShowPwdForm($user){
        print '
        <form class="form-horizontal m-t-20" action="" method="post">
        <p>Set your own custom password</p>
        <div class="form-group ">
            <div class="col-xs-12">
                <input name="UserEmail" id="UserEmail" type="hidden" value="'.$user.'">
                <input class="form-control" name="NewPassword" id="NewPassword" type="password" required="" placeholder="Enter New Password">
            </div>
            <div class="col-xs-12">
                <input class="form-control" name="CNewPassword" id="CNewPassword" type="password" required="" placeholder="Re-nter New Password">
            </div>
            <div class="col-xs-12">
            <br>
            <button name="xpassword" class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">Submit OTP</button>
            </div>
        </div></form><br><br><br>
        ';
    }
    function ValidatePassword($userinput, $stored_hash){
        if(password_verify($userinput, $stored_hash)){
            return true;
        }else{
            throw new Exception('Invalid username and/or password! ');
            return false;
        }
    }
}
?>
