<?php
/**
 * @filename: Room.php
 * @role: Room object
 * @author: avatar
 * @license : Proriatery
 */
class Room{
    public $Connection;
    private $RoomId;
    private $RoomName;
    private $RoomShortDescription;
    private $RoomDescription;
    private $RoomPrice;
    private $RoomDiscountType;
    private $RoomDiscountValue;
    private $RoomPacks;
    private $RoomMetaDescription;
    private $RoomCategory;
    private $RoomCommisionType;
    private $RoomCommisionValue;
    private $RoomOwner;
    private $Room3D;
    private $RoomStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $RoomId = null, $RoomName = null, $RoomShortDescription = null, $RoomDescription = null, $RoomPrice = null, $RoomDiscountType = null, $RoomDiscountValue = null, $RoomPacks = null, $RoomMetaDescription = null, $RoomCategory = null, $RoomCommisionType = null, $RoomCommisionValue = null, $RoomOwner = null, $Room3D = null, $RoomStatus = null){
        $this->Connection = $Connection;
        $this->RoomId = $RoomId;
        $this->RoomName = $RoomName;
        $this->RoomShortDescription = $RoomShortDescription;
        $this->RoomDescription = $RoomDescription;
        $this->RoomPrice = $RoomPrice;
        $this->RoomDiscountType = $RoomDiscountType;
        $this->RoomDiscountValue = $RoomDiscountValue;
        $this->RoomPacks = $RoomPacks;
        $this->RoomMetaDescription = $RoomMetaDescription;
        $this->RoomCategory = $RoomCategory;
        $this->RoomCommisionType = $RoomCommisionType;
        $this->RoomCommisionValue = $RoomCommisionValue;
        $this->RoomOwner = $RoomOwner;
        $this->Room3D = $Room3D;
        $this->RoomStatus = $RoomStatus;
    }
    function ValidateFields(){
        if(empty($this->RoomId)){
            throw new Exception("Room ID Field Is Blank!");
            return false;
        }
        if(empty($this->RoomName)){
            throw new Exception("Room ID Field Is Blank!");
            return false;
        }
        if(empty($this->RoomShortDescription)){
            throw new Exception("Room Exercept Field Is Blank!");
            return false;
        }
        if(empty($this->RoomDescription)){
            throw new Exception("Room Description Field Is Blank!");
            return false;
        }
        if(empty($this->RoomPrice)){
            throw new Exception("Room Price Field Is Blank!");
            return false;
        }
        if(empty($this->RoomDiscountType) || $this->RoomDiscountType == 'nn'){
            throw new Exception("Room Discount Type Field Is Blank! ");
            return false;
        }
        if($this->RoomDiscountValue == ''){
            throw new Exception("Room Discount Value Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomPacks)){
            throw new Exception("Room Packs Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomMetaDescription)){
            throw new Exception("Room Meta Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomCategory) || $this->RoomCategory == 'nn'){
            throw new Exception("Room Category Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomCommisionType) || $this->RoomCommisionType == 'nn'){
            throw new Exception("Room Commission Type Field Is Blank! ");
            return false;
        }
        if($this->RoomCommisionValue == ''){
            throw new Exception("Room Commission Value Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomOwner) || $this->RoomOwner == 'nn'){
            throw new Exception("Room Owner Field Is Blank! ");
            return false;
        }
        if(empty($this->Room3D)){
            throw new Exception("Room Spinzam Field Is Blank!");
            return false;
        }
        if(empty($this->RoomStatus)){
            throw new Exception("Room Status flag Is Blank!");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_rooms`(`RoomId`, `RoomName`, `RoomShortDescription`, `RoomDescription`, `RoomPrice`, `RoomDiscountType`, `RoomDiscountValue`, `RoomPacks`, `RoomMetaDescription`, `RoomCategory`, `RoomCommisionType`, `RoomCommisionValue`, `RoomOwner`, `Room3D`, `RoomStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o)");
            $statement->bindParam(':a', $this->RoomId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->RoomName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomShortDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->RoomDescription, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->RoomPrice, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->RoomDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->RoomDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->RoomPacks, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->RoomMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->RoomCategory, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->RoomCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->RoomCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->RoomOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->Room3D, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->RoomStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create room Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_rooms` SET `RoomName`=:b,`RoomShortDescription`=:c,`RoomDescription`=:d,`RoomPrice`=:e,`RoomDiscountType`=:f,`RoomDiscountValue`=:g,`RoomPacks`=:h,`RoomMetaDescription`=:i,`RoomCategory`=:j,`RoomCommisionType`=:k,`RoomCommisionValue`=:l,`RoomOwner`=:m,`Room3D`=:n,`RoomStatus`=:o WHERE `RoomId`=:a");
            $statement->bindParam(':a', $this->RoomId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->RoomName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomShortDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->RoomDescription, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->RoomPrice, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->RoomDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->RoomDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->RoomPacks, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->RoomMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->RoomCategory, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->RoomCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->RoomCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->RoomOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->Room3D, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->RoomStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update room Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Rate($data){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `p_items_rating`(`Rating`, `Comment`, `Item`, `RatedUser`) VALUES(:a,:b,:c,:d)");
        $statement->bindParam(':a', $data[0], PDO::PARAM_STR);
        $statement->bindParam(':b', $data[1], PDO::PARAM_STR);
        $statement->bindParam(':c', $data[2], PDO::PARAM_STR);
        $statement->bindParam(':d', $data[3], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Could not rate this item. Contact Admins');
            return false;
        }
        return;
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
            throw new Exception('Error occured. Could not record view on this item. Contact Admins');
            return false;
        }
        return;
    }
    function FindFeatured($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE  `RoomStatus` = '1' ORDER BY RoomPrice DESC LIMIT $limit ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindNewArrival($l = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE  `RoomStatus` = '1' ORDER BY RoomSequence DESC LIMIT $l ");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindTopRated(){
        $util = new Util();

    }
    function FindMostViewed(){
        $util = new Util();

    }
    function TopSelling(){
        return [];
    }
    function FindOnsale(){
        return [];
    }
    function isReady($id){
        if( $this->HasStock($id) && 
            $this->HasThumb($id) && 
            $this->HasSlider($id) &&
            $this->HasBanner($id) &&
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
    function FindIdBySequence($seq, $l = 0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT RoomId FROM `p_rooms` WHERE `RoomSequence` < :a ORDER BY `RoomSequence`  DESC");
        if($l > 0){//next
            $statement = $this->Connection->prepare("SELECT RoomId FROM `p_rooms` WHERE `RoomSequence` > :a ORDER BY `RoomSequence`  ASC");
        }
        $statement->execute([':a' => $seq]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['RoomId'];
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
    function CountAllByVendor($hotel){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_rooms` WHERE `RoomOwner` = :a AND `RoomStatus` = '1'");
        $statement->execute([':a' => $hotel]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get count. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['cnt'];
    }
    function CountGallery($type, $id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `p_items_gallery` WHERE `GalleryType` = :a AND `GalleryProduct` = :b AND GalleryStatus = '1'");
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
    function FindSliders(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE  `RoomStatus` = '1' ORDER BY RoomSequence DESC LIMIT 20");
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
    function IsActive($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_rooms` WHERE `RoomStatus` = '1' AND RoomId = :a ");
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
    function FindHotelUserIds(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT DISTINCT RoomOwner FROM `p_rooms` WHERE RoomStatus = '1' ORDER BY RoomOwner DESC");
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
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE 1 ORDER BY RoomSequence DESC");
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
    function FindAllActive($limit, $offset){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomStatus` = '1' ORDER BY RoomSequence DESC LIMIT $offset, $limit");
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
    function SearchAll($str){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomStatus` = '1' AND `RoomName` LIKE '%$str%' ORDER BY RoomSequence DESC");
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
    function FindAllInactive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomStatus` = '0' ORDER BY RoomSequence DESC");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomStatus` = '00' ORDER BY RoomSequence DESC");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomId` = :a ");
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
    function FindByVendor($vid, $limit = 10, $offset = 0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomOwner` = :a ORDER BY RoomSequence DESC LIMIT $offset, $limit");
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
    function FindByCategory($category, $limit=10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomCategory` = '$category' ORDER BY RoomSequence DESC LIMIT $limit");
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
    function CountAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_rooms` WHERE 1");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_rooms` WHERE `RoomStatus` = '1'");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_rooms` WHERE `RoomStatus` = '0'");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_rooms` WHERE `RoomStatus` = '00'");
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
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_rooms` SET `RoomStatus`= '0' WHERE `RoomId` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_rooms` SET `RoomStatus`= '00' WHERE `RoomId` = :a");
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