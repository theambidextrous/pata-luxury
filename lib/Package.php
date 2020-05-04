<?php
/**
 * @filename: Package.php
 * @role: Package object
 * @author: avatar
 * @license : Proriatery
 */
class Package{
    private $Connection;
    private $PackageId;
    private $PackageName;
    private $PackagePrice;
    private $PackageType;//holiday or concierge
    private $PackageDiscountType;
    private $PackageDiscountValue;
    private $PackageCommisionType;
    private $PackageCommisionValue;
    private $PackageLocation;
    private $PackageValidTill;
    private $PackageMetaDescription;
    private $PackageDescription;
    private $PackageOwner;
    private $PackagePack;
    private $PackageStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection=null,$PackageId=null,$PackageName=null,$PackagePrice=null,$PackageType=null,$PackageDiscountType=null,$PackageDiscountValue=null,$PackageCommisionType=null,$PackageCommisionValue=null,$PackageLocation=null,$PackageValidTill=null,$PackageMetaDescription=null,$PackageDescription=null,$PackageOwner=null,$PackagePack=null,$PackageStatus=null){
        $this->Connection = $Connection;
        $this->PackageId = $PackageId;
        $this->PackageName = $PackageName;
        $this->PackagePrice = $PackagePrice;
        $this->PackageType = $PackageType;
        $this->PackageDiscountType = $PackageDiscountType;
        $this->PackageDiscountValue = $PackageDiscountValue;
        $this->PackageCommisionType = $PackageCommisionType;
        $this->PackageCommisionValue = $PackageCommisionValue;
        $this->PackageLocation = $PackageLocation;
        $this->PackageValidTill = $PackageValidTill;
        $this->PackageMetaDescription = $PackageMetaDescription;
        $this->PackageDescription = $PackageDescription;
        $this->PackageOwner = $PackageOwner;
        $this->PackagePack = $PackagePack;
        $this->PackageStatus = $PackageStatus;
    }
    function ValidateFields(){
        if(empty($this->PackageId)){
            throw new Exception("Package ID Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageName)){
            throw new Exception("Package Name Field Is Blank! ");
            return false;
        }
        if(empty($this->PackagePrice)){
            throw new Exception("Package Price Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageType) || $this->PackageType == 'nn'){
            throw new Exception("Package Type Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageDiscountType) || $this->PackageDiscountType == 'nn'){
            throw new Exception("Package Discount Type Field Is Blank! ");
            return false;
        }
        if($this->PackageDiscountValue==''){
            throw new Exception("Package Discount Value Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageCommisionType) || $this->PackageCommisionType == 'nn'){
            throw new Exception("Package Commision Type Field Is Blank! ");
            return false;
        }
        if($this->PackageCommisionValue==''){
            throw new Exception("Package Commision Value Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageLocation)){
            throw new Exception("Package Location Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageValidTill)){
            throw new Exception("Package Valid Date Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageMetaDescription)){
            throw new Exception("Package Meta Description Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageDescription)){
            throw new Exception("Package Description Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageOwner)){
            throw new Exception("Package Owner Field Is Blank! ");
            return false;
        }
        if(empty($this->PackagePack)){
            throw new Exception("Package Includes Field Is Blank! ");
            return false;
        }
        if(empty($this->PackageStatus)){
            throw new Exception("Package Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_packages`(`PackageId`, `PackageName`, `PackagePrice`, `PackageType`, `PackageDiscountType`, `PackageDiscountValue`, `PackageCommisionType`, `PackageCommisionValue`, `PackageLocation`, `PackageValidTill`, `PackageMetaDescription`, `PackageDescription`, `PackageOwner`, `PackagePack`, `PackageStatus`) VALUES(:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o)");
            $statement->bindParam(':a', $this->PackageId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PackageName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackagePrice, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->PackageType, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->PackageDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->PackageDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->PackageCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->PackageCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->PackageLocation, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->PackageValidTill, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->PackageMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->PackageDescription, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->PackageOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->PackagePack, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->PackageStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Package Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_packages` SET `PackageName`=:b,`PackagePrice`=:c,`PackageType`=:d,`PackageDiscountType`=:e,`PackageDiscountValue`=:f,`PackageCommisionType`=:g,`PackageCommisionValue`=:h,`PackageLocation`=:i,`PackageValidTill`=:j,`PackageMetaDescription`=:k,`PackageDescription`=:l,`PackageOwner`=:m, `PackagePack`=:n, `PackageStatus`=:o WHERE `PackageId`=:a");
            $statement->bindParam(':a', $this->PackageId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PackageName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackagePrice, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->PackageType, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->PackageDiscountType, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->PackageDiscountValue, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->PackageCommisionType, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->PackageCommisionValue, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->PackageLocation, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->PackageValidTill, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->PackageMetaDescription, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->PackageDescription, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->PackageOwner, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->PackagePack, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->PackageStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Package Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackageSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Package Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Package Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByType($PackageType){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageType` = :a");
        $statement->execute([ ':a' => $PackageType ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Package Item. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByVendor($PackageOwner){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageOwner` = :a");
        $statement->execute([ ':a' => $PackageOwner ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Package Item. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function SearchAll($str){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' AND `PackageName` LIKE '%$str%' ORDER BY PackageSequence DESC");
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
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_packages` SET `PackageStatus`= '0' WHERE `PackageId` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_packages` SET `PackageStatus`= '00' WHERE `PackageId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete item. Contact Admins');
            return false;
        }
        return true;
    }

    /** additional functions */
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
    function FindHotelUserIds(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT DISTINCT PackageOwner FROM `p_packages` WHERE PackageStatus = '1' ORDER BY PackageOwner DESC");
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
    function FindTopRated($limit=10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackagePrice DESC LIMIT $limit");
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
    function FindMostViewed(){
        $util = new Util();

    }
    function FindFeatured($limit=10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackagePrice DESC LIMIT $limit");
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
    function TopSelling($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackagePrice ASC LIMIT $limit");
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
    function FindOnsale(){
        return [];
    }
    function FindNewArrival($limit=10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackageCreated DESC LIMIT $limit");
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
    function isReady($id){
        if( $this->isValidDate($id) && 
            $this->HasThumb($id) && 
            $this->HasSlider($id) &&
            $this->HasBanner($id) &&
            $this->HasBannerEqualToThumbs($id)){
            return true;
        }
        return false;
    }
    function isValidDate($id){
        $util = new Util();
        $now = date('Y-m-d');
        $stmt = $this->Connection->prepare("SELECT COUNT(*) AS cnt FROM `p_packages` WHERE `PackageId`= :a AND `PackageValidTill` >= '$now'");
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
        $statement = $this->Connection->prepare("SELECT PackageId FROM `p_packages` WHERE `PackageSequence` < :a ORDER BY `PackageSequence`  DESC");
        if($l > 0){//next
            $statement = $this->Connection->prepare("SELECT PackageId FROM `p_packages` WHERE `PackageSequence` > :a ORDER BY `PackageSequence`  ASC");
        }
        $statement->execute([':a' => $seq]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['PackageId'];
    }
    function FindCarUserIds(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT DISTINCT PackageOwner FROM `p_packages` WHERE PackageStatus = '1' ORDER BY PackageOwner DESC");
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
    function CountAllActive(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt  FROM `p_packages` WHERE `PackageStatus` = '1'");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageStatus` = '1' ORDER BY PackageSequence DESC LIMIT $offset, $limit");
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
        $statement = $this->Connection->prepare("SELECT count(*) AS cnt FROM `p_packages` WHERE `PackageOwner` = :a AND `PackageStatus` = '1'");
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
    function FindSliders(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE  `PackageStatus` = '1' ORDER BY PackageSequence DESC LIMIT 20");
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
    function FindByCategory($group, $limit=10){
        switch($group){
            case '1':
                return $this->FindNewArrival($limit);
            break;
            case '2':
                return $this->FindFeatured($limit);
            break;
            case '3':
                return $this->TopSelling($limit);
            break;
        }
    }
    function displayGroups(){
        return [
            [
                'PackageCategoryId' => 1,
                'PackageCategoryName' => 'Latest Deals'
            ],
            [
                'PackageCategoryId' => 2,
                'PackageCategoryName' => 'Featured Packages'
            ],
            [
                'PackageCategoryId' => 3,
                'PackageCategoryName' => '2.5% Off'
            ]
        ];
    }
}
?>