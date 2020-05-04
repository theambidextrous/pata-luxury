<?php
/**
 * @filename: Car.php
 * @role: Car object
 * @author: avatar
 * @license : Proriatery
 */
class Car{
    private $Connection;
    private $CarId;
    private $CarName;
    private $CarPlateNumber;
    private $CarPrice;
    private $CarBrand;
    private $CarDiscountType;
    private $CarDiscountValue;
    private $CarCommisionType;
    private $CarCommisionValue;
    private $CarPack;
    private $CarMetaDescription;
    private $CarDescription;
    private $CarOwner;
    private $Car3D;
    private $CarStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection=null,$CarId=null,$CarName=null,$CarPlateNumber=null,$CarPrice=null,$CarBrand=null,$CarDiscountType=null,$CarDiscountValue=null,$CarCommisionType=null,$CarCommisionValue=null,$CarPack=null,$CarMetaDescription=null,$CarDescription=null,$CarOwner=null,$Car3D=null,$CarStatus=null){
        $this->Connection = $Connection;
        $this->CarId = $CarId;
        $this->CarName = $CarName;
        $this->CarPlateNumber = $CarPlateNumber;
        $this->CarPrice = $CarPrice;
        $this->CarBrand = $CarBrand;
        $this->CarDiscountType = $CarDiscountType;
        $this->CarDiscountValue = $CarDiscountValue;
        $this->CarCommisionType = $CarCommisionType;
        $this->CarCommisionValue = $CarCommisionValue;
        $this->CarPack = $CarPack;
        $this->CarMetaDescription = $CarMetaDescription;
        $this->CarDescription = $CarDescription;
        $this->CarOwner = $CarOwner;
        $this->Car3D = $Car3D;
        $this->CarStatus = $CarStatus;
    }
    function ValidateFields(){
        if(empty($this->CarId)){
            throw new Exception("Car ID Field Is Blank! ");
            return false;
        }
        if(empty($this->CarName)){
            throw new Exception("Car Name Field Is Blank! ");
            return false;
        }
        if(empty($this->CarPlateNumber)){
            throw new Exception("Car Plate Number Field Is Blank! ");
            return false;
        }
        if(empty($this->CarPrice)){
            throw new Exception("Car Price Field Is Blank! ");
            return false;
        }
        if(empty($this->CarBrand)){
            throw new Exception("Car Brand Field Is Blank! ");
            return false;
        }
        if(empty($this->CarDiscountType) || $this->CarDiscountType == 'nn'){
            throw new Exception("Car Discount Type Field Is Blank! ");
            return false;
        }
        if($this->CarDiscountValue==''){
            throw new Exception("Car Discount Value Field Is Blank! ");
            return false;
        }
        if(empty($this->CarCommisionType) || $this->CarCommisionType == 'nn'){
            throw new Exception("Car Commision Type Field Is Blank! ");
            return false;
        }
        if($this->CarCommisionValue==''){
            throw new Exception("Car Commision Value Field Is Blank! ");
            return false;
        }
        if(empty($this->CarPack)){
            throw new Exception("Car Tags Field Is Blank! ");
            return false;
        }
        if(empty($this->CarMetaDescription)){
            throw new Exception("Car Meta Description Field Is Blank! ");
            return false;
        }
        if(empty($this->CarDescription)){
            throw new Exception("Car Description Field Is Blank! ");
            return false;
        }
        if(empty($this->CarOwner)){
            throw new Exception("Car Owner Field Is Blank! ");
            return false;
        }
        if(empty($this->Car3D)){
            throw new Exception("Car spinzam 3D Field Is Blank! ");
            return false;
        }
        if(empty($this->CarStatus)){
            throw new Exception("Car Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_cars`(`CarId`, `CarName`, `CarPlateNumber`, `CarPrice`, `CarBrand`, `CarDiscountType`, `CarDiscountValue`, `CarCommisionType`, `CarCommisionValue`, `CarPack`, `CarMetaDescription`, `CarDescription`, `CarOwner`, `Car3D`, `CarStatus`) VALUES(:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o)");
            $statement->bindParam(':a', $this->CarId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->CarName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CarPlateNumber, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CarPrice, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->CarBrand, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CarDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->CarDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->CarCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->CarCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->CarPack, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->CarMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->CarDescription, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->CarOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->Car3D, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->CarStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Car Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_cars` SET `CarName`=:b,`CarPlateNumber`=:c,`CarPrice`=:d,`CarBrand`=:e,`CarDiscountType`=:f,`CarDiscountValue`=:g,`CarCommisionType`=:h,`CarCommisionValue`=:i,`CarPack`=:j,`CarMetaDescription`=:k,`CarDescription`=:l,`CarOwner`=:m,`Car3D`=:n,`CarStatus`=:o WHERE `CarId`=:a");
            $statement->bindParam(':a', $this->CarId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->CarName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CarPlateNumber, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CarPrice, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->CarBrand, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CarDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->CarDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->CarCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->CarCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->CarPack, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->CarMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->CarDescription, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->CarOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->Car3D, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->CarStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Car Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindFleetUserIds(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT DISTINCT CarOwner FROM `p_cars` WHERE CarStatus = '1' ORDER BY CarOwner DESC");
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
    function FindByCategory($case, $limit=10){
        switch($case){
            case '1':
                return $this->Featured($limit);
            break;
            case '2':
                return $this->TopSelling($limit);
            break;
        }
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
    function Featured($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarStatus` = '1' ORDER BY CarPrice DESC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;

    }
    function TopSelling($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarStatus` = '1' ORDER BY CarPrice ASC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarStatus` = '1' ORDER BY CarSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function isReady($id){
        if( $this->HasThumb($id) && 
            $this->HasSlider($id) &&
            $this->HasBanner($id) &&
            $this->HasBannerEqualToThumbs($id)){
            return true;
        }
        return false;
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
    function FindIdBySequence($seq, $l = 0){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT CarId FROM `p_cars` WHERE `CarSequence` < :a ORDER BY `CarSequence`  DESC");
        if($l > 0){//next
            $statement = $this->Connection->prepare("SELECT CarId FROM `p_cars` WHERE `CarSequence` > :a ORDER BY `CarSequence`  ASC");
        }
        $statement->execute([':a' => $seq]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['CarId'];
    }
    function FindSliders(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE  `CarStatus` = '1' ORDER BY CarSequence DESC LIMIT 20");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByPlateNumber($CarPlateNumber){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarPlateNumber` = :a");
        $statement->execute([ ':a' => $CarPlateNumber ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindCarUserIds(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT DISTINCT CarOwner FROM `p_cars` WHERE CarStatus = '1' ORDER BY CarOwner DESC");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE  `CarStatus` = '1' ORDER BY CarSequence DESC LIMIT $l ");
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
    function FindFeatured($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE  `CarStatus` = '1' ORDER BY CarPrice DESC LIMIT $limit ");
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
    function CountAllActive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_cars` WHERE `CarStatus` = '1'");
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
    function FindAllActive($limit, $offset){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarStatus` = '1' ORDER BY CarSequence DESC LIMIT $offset, $limit");
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
    function CountAllByVendor($hotel){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_cars` WHERE `CarOwner` = :a AND `CarStatus` = '1'");
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
    function FindByCarBrand($CarBrand){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarBrand` = :a");
        $statement->execute([ ':a' => $CarBrand ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Item. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByVendor($CarOwner){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarOwner` = :a");
        $statement->execute([ ':a' => $CarOwner ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Car Item. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function SearchAll($str){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarStatus` = '1' AND `CarName` LIKE '%$str%' ORDER BY CarSequence DESC");
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
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_cars` SET `CarStatus`= '0' WHERE `CarId` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_cars` SET `CarStatus`= '00' WHERE `CarId` = :a");
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