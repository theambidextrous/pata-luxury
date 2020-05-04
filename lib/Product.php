<?php
/**
 * @filename: Product.php
 * @role: product object
 * @author: avatar
 * @license : Proriatery
 */
class Product{
    public $Connection;
    private $ProductId;
    private $ProductName;
    private $ProductShortDescription;
    private $ProductDescription;
    private $ProductPrice;
    private $ProductDiscountType;
    private $ProductDiscountValue;
    private $ProductTags;
    private $ProductMetaDescription;
    private $ProductCategories;
    private $ProductCommisionType;
    private $ProductCommisionValue;
    private $ProductOwner;
    private $ProductShipper;
    private $Product3D;
    private $ProductColors;
    private $ProductStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $ProductId = null, $ProductName = null, $ProductShortDescription = null, $ProductDescription = null, $ProductPrice = null, $ProductDiscountType = null, $ProductDiscountValue = null, $ProductTags = null, $ProductMetaDescription = null, $ProductCategories = null, $ProductCommisionType = null, $ProductCommisionValue = null, $ProductOwner = null, $ProductShipper = null, $Product3D = null, $ProductColors = null, $ProductStatus = null){
        $this->Connection = $Connection;
        $this->ProductId = $ProductId;
        $this->ProductName = $ProductName;
        $this->ProductShortDescription = $ProductShortDescription;
        $this->ProductDescription = $ProductDescription;
        $this->ProductPrice = $ProductPrice;
        $this->ProductDiscountType = $ProductDiscountType;
        $this->ProductDiscountValue = $ProductDiscountValue;
        $this->ProductTags = $ProductTags;
        $this->ProductMetaDescription = $ProductMetaDescription;
        $this->ProductCategories = $ProductCategories;
        $this->ProductCommisionType = $ProductCommisionType;
        $this->ProductCommisionValue = $ProductCommisionValue;
        $this->ProductOwner = $ProductOwner;
        $this->ProductShipper = $ProductShipper;
        $this->Product3D = $Product3D;
        $this->ProductColors = $ProductColors;
        $this->ProductStatus = $ProductStatus;
    }
    function ValidateFields(){
        if(empty($this->ProductId)){
            throw new Exception("Product ID Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductName)){
            throw new Exception("Product ID Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductShortDescription)){
            throw new Exception("Product Exercept Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductDescription)){
            throw new Exception("Product Description Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductPrice)){
            throw new Exception("Product Price Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductDiscountType) || $this->ProductDiscountType == 'nn'){
            throw new Exception("Product Discount Type Field Is Blank! ");
            return false;
        }
        if($this->ProductDiscountValue == ''){
            throw new Exception("Product Discount Value Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductTags)){
            throw new Exception("Product Tags Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductMetaDescription)){
            throw new Exception("Product Meta Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductCategories) || $this->ProductCategories == 'nn'){
            throw new Exception("Product Category Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductCommisionType) || $this->ProductCommisionType == 'nn'){
            throw new Exception("Product Commission Type Field Is Blank! ");
            return false;
        }
        if($this->ProductCommisionValue == ''){
            throw new Exception("Product Commission Value Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductOwner) || $this->ProductOwner == 'nn'){
            throw new Exception("Product Owner Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductShipper)){
            throw new Exception("Product Shipper Field Is Blank! ");
            return false;
        }
        if(empty($this->Product3D)){
            throw new Exception("Product Spinzam Field Is Blank!");
            return false;
        }
        if(empty($this->ProductColors) || $this->ProductColors == 'nn'){
            throw new Exception("Product Colors Field Is Blank!");
            return false;
        }
        if(empty($this->ProductStatus)){
            throw new Exception("Product Status flag Is Blank!");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items`(`ProductId`, `ProductName`, `ProductShortDescription`, `ProductDescription`, `ProductPrice`, `ProductDiscountType`, `ProductDiscountValue`, `ProductTags`, `ProductMetaDescription`, `ProductCategories`, `ProductCommisionType`, `ProductCommisionValue`, `ProductOwner`, `ProductShipper`, `Product3D`, `ProductColors`, `ProductStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o,:p,:q)");
            $statement->bindParam(':a', $this->ProductId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ProductName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ProductShortDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ProductDescription, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->ProductPrice, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->ProductDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->ProductDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->ProductTags, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->ProductMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->ProductCategories, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->ProductCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->ProductCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->ProductOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->ProductShipper, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->Product3D, PDO::PARAM_STR);
            $statement->bindParam(':p', $this->ProductColors, PDO::PARAM_STR);
            $statement->bindParam(':q', $this->ProductStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Shop Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items` SET `ProductName`=:b,`ProductShortDescription`=:c,`ProductDescription`=:d,`ProductPrice`=:e,`ProductDiscountType`=:f,`ProductDiscountValue`=:g,`ProductTags`=:h,`ProductMetaDescription`=:i,`ProductCategories`=:j,`ProductCommisionType`=:k,`ProductCommisionValue`=:l,`ProductOwner`=:m,`ProductShipper`=:n,`Product3D`=:o, `ProductColors`=:p, `ProductStatus`=:q WHERE `ProductId`=:a");
            $statement->bindParam(':a', $this->ProductId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ProductName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ProductShortDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ProductDescription, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->ProductPrice, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->ProductDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->ProductDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->ProductTags, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->ProductMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->ProductCategories, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->ProductCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->ProductCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->ProductOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->ProductShipper, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->Product3D, PDO::PARAM_STR);
            $statement->bindParam(':p', $this->ProductColors, PDO::PARAM_STR);
            $statement->bindParam(':q', $this->ProductStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Shop Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Rate($data){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `p_items_rating`(`Rating`, `Comment`, `Item`, `RatedUser`) VALUES(:a,:b,:c,:d) ON DUPLICATE KEY UPDATE Rating=:a,Comment=:b,Item=:c,RatedUser=:d");
        $statement->bindParam(':a', $data[0], PDO::PARAM_STR);
        $statement->bindParam(':b', $data[1], PDO::PARAM_STR);
        $statement->bindParam(':c', $data[2], PDO::PARAM_STR);
        $statement->bindParam(':d', $data[3], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not rate this item.');
            return false;
        }
        return true;
    }
    function FindRatings($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_rating` WHERE Item=:a");
        $statement->bindParam(':a', $id, PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not get ratings.');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function Wish($data){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `p_users_wishlist`(`Item`, `User`) VALUES(:a,:b)");
        $statement->bindParam(':a', $data[0], PDO::PARAM_STR);
        $statement->bindParam(':b', $data[1], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not wish-list this item.');
            return false;
        }
        return true;
    }
    function FindTagsAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT group_concat(`ProductTags` separator ',') AS tags FROM `p_items` WHERE 1 ORDER BY ProductSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC)['tags'];
        if(is_string($res)){
            $rtn = explode(',', $res);
            $r = array_unique($rtn);
            return array_slice ( $r, 0, 27);
        }
        return false;
    }
    function FindProductRate($item){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT AVG(`Rating`) AS rt FROM p_items_rating WHERE Item = :a");
        $statement->execute([':a' => $item]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not get rate. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if($res['rt'] > 0 ){
            return $res['rt'];
        }
        return 1.5;
    }
    function View($data){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `p_items_views`(`ItemViewed`, `UserViewed`) VALUES(:a,:b)");
        $statement->bindParam(':a', $data[0], PDO::PARAM_STR);
        $statement->bindParam(':b', $data[1], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not record view on this item.');
            return false;
        }
        return true;
    }
    function FindTopRated(){
        $util = new Util();
        return $this->FindNewArrival(2);
    }
    function FindMostViewed(){
        $util = new Util();

    }
    function TopSelling(){
        $util = new Util();
        return $this->FindNewArrival();
    }
    function isReady($id){
        if( $this->HasParam($id) && 
            $this->HasStock($id) && 
            $this->HasThumb($id) && 
            $this->HasSlider($id) &&
            $this->HasBanner($id) &&
            $this->HasSize($id) &&
            $this->HasBannerEqualToThumbs($id)){
            return true;
        }
        return false;
    }
    function HasSize($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT COUNT(*) as cnt FROM `p_items_sizes` WHERE `SizeProduct` = :a AND `SizeStatus` = '1'");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0 ){
            return true;
        }
        return false;

    }
    function HasParam($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT count(*) as cnt FROM `p_items_params` WHERE `ParamProduct`=:a");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0 ){
            return true;
        }
        return false;
    }
    function HasStock($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT `Stock` FROM `p_items_stock` WHERE `StockProduct`=:a");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['Stock'] > 0 ){
            return true;
        }
        return false;

    }
    function HasThumb($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_items_gallery` WHERE `GalleryProduct`=:a AND `GalleryType`='5003'");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0 ){
            return true;
        }
        return false;
    }
    function HasSlider($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_items_gallery` WHERE `GalleryProduct`=:a AND `GalleryType`='5004'");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0 ){
            return true;
        }
        return false;
    }
    function HasBanner($id){
        $util = new Util();
        $stmt = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_items_gallery` WHERE `GalleryProduct`=:a AND `GalleryType`='5005'");
        $stmt->execute([':a' => $id]);
        $rs = $stmt->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not validate. Contact Admins');
            return false;
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0 ){
            return true;
        }
        return false;
        
    }
    function HasBannerEqualToThumbs($id){
        if( $this->CountGallery(5003, $id) === $this->CountGallery(5005, $id) ){
            return true;
        }
        return false;
    }
    function CountGallery($type, $id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `p_items_gallery` WHERE `GalleryType` = :a AND `GalleryProduct` = :b AND GalleryStatus = '1' ");
        $statement->execute([':a' => $type, ':b' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if( $res['cnt'] > 0 ){
            return $res['cnt'];
        }
        return 0;
    }
    function IsActive($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_items` WHERE `ProductStatus` = '1' AND ProductId = :a ");
        $statement->execute([':a' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if( $res['cnt'] > 0 ){
            return true;
        }
        return false;
    }
    function RelatedItems($id){
        $util = new Util();

    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE 1 ORDER BY ProductSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAllActive($limit=15, $offset=0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductStatus` = '1' ORDER BY ProductSequence DESC LIMIT  $offset, $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function SearchAll($str, $limit=15, $offset=0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductStatus` = '1' AND `ProductName` LIKE '%$str%' ORDER BY ProductSequence DESC LIMIT $offset, $limit ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    // $payload = [$range_lower, $range_upper, $currency_value];
    function SearchAllByPrice($data, $limit=15, $offset=0){
        $util = new Util();
        if($_SESSION['cry'] == 'KES'){
            $rate = $util->getForexRates('KES');
            $data[0] = floor(($data[0]*$rate));
            $data[1] = floor(($data[1]*$rate));
        }elseif($_SESSION['cry'] == '$'){
            $rate = $util->getForexRates();
            $data[0] = floor(($data[0]*$rate));
            $data[1] = floor(($data[1]*$rate));
        }
        // $util->Show($data);
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductStatus` = '1' AND `ProductPrice` >= :a AND `ProductPrice` <= :b ORDER BY ProductSequence DESC LIMIT $offset, $limit ");
        $statement->execute([ ':a' => $data[0], ':b' => $data[1] ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAllInactive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductStatus` = '0' ORDER BY ProductSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAllDeleted(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductStatus` = '00' ORDER BY ProductSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindSliders(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE  `ProductStatus` = '1' ORDER BY ProductSequence DESC LIMIT 20");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindFeatured($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE  `ProductStatus` = '1' ORDER BY ProductPrice DESC LIMIT $limit ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindNewArrival($l = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE  `ProductStatus` = '1' ORDER BY ProductSequence DESC LIMIT $l ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindOnsale(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE  `ProductStatus` = '1' ORDER BY ProductPrice ASC LIMIT 10");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductId` = :a ");
        $statement->execute([':a' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindIdBySequence($seq, $l = 0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT ProductId FROM `p_items` WHERE `ProductSequence` < :a ORDER BY `ProductSequence`  DESC");
        if($l > 0){//next
            $statement = $this->Connection->prepare("SELECT ProductId FROM `p_items` WHERE `ProductSequence` > :a ORDER BY `ProductSequence`  ASC");
        }
        $statement->execute([':a' => $seq]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['ProductId'];
    }
    function FindByVendor($vid){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductOwner` = :a ORDER BY ProductSequence DESC");
        $statement->execute([':a' => $vid]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByCategory($category, $limit=15, $offset=0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductCategories` LIKE '%$category%' AND `ProductStatus` = '1' ORDER BY ProductSequence DESC LIMIT $offset, $limit ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item(s). Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function CountAllByCategory($category){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_items` WHERE `ProductCategories` LIKE '%$category%' AND `ProductStatus` = '1'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function CountAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_items` WHERE 1");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function CountAllActive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_items` WHERE `ProductStatus` = '1'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function CountAllInactive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_items` WHERE `ProductStatus` = '0'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function CountAllDeleted(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_items` WHERE `ProductStatus` = '00'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items` SET `ProductStatus`= '0' WHERE `ProductId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not disable item. Contact Admins');
            return false;
        }
        return true;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items` SET `ProductStatus`= '00' WHERE `ProductId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete item. Contact Admins');
            return false;
        }
        return true;
    }
}
?>