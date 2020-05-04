<?php
/**
 * @filename: Category.php
 * @role: category object
 * @author: avatar
 * @license : Proriatery
 */
class Category{
    private $Connection;
    private $CategoryId;
    private $CategoryName;
    private $CategoryParent;
    private $CategoryThumbPath;
    private $CategoryBannerPath;
    private $CategoryDescription;
    private $CategoryStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $CategoryId = null, $CategoryName = null, $CategoryParent = null, $CategoryThumbPath = null, $CategoryBannerPath = null, $CategoryDescription = null, $CategoryStatus = null){
        $this->Connection = $Connection;
        $this->CategoryId = $CategoryId;
        $this->CategoryName = $CategoryName;
        $this->CategoryParent = $CategoryParent;
        $this->CategoryThumbPath = $CategoryThumbPath;
        $this->CategoryBannerPath = $CategoryBannerPath;
        $this->CategoryDescription = $CategoryDescription;
        $this->CategoryStatus = $CategoryStatus;
    }
    function ValidateFields(){
        if(empty($this->CategoryId)){
            throw new Exception("Category ID Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryName)){
            throw new Exception("Category Name Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryParent)){
            throw new Exception("Category Parent Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryThumbPath)){
            throw new Exception("Category Thumb Path Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryBannerPath)){
            throw new Exception("Category Banner Path Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryDescription)){
            throw new Exception("Category Description Field Is Blank! ");
            return false;
        }
        if(empty($this->CategoryStatus)){
            throw new Exception("Category Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_categories`(`CategoryId`, `CategoryName`, `CategoryParent`, `CategoryThumbPath`, `CategoryBannerPath`, `CategoryDescription`, `CategoryStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g)");
            $statement->bindParam(':a', $this->CategoryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->CategoryName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CategoryParent, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CategoryThumbPath, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->CategoryBannerPath, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CategoryDescription, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->CategoryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create category Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_categories` SET `CategoryName`=:b,`CategoryParent`=:c,`CategoryThumbPath`=:d,`CategoryBannerPath`=:e,`CategoryDescription`=:f,`CategoryStatus`=:g WHERE `CategoryId`=:a");
            $statement->bindParam(':a', $this->CategoryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->CategoryName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CategoryParent, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CategoryThumbPath, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->CategoryBannerPath, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CategoryDescription, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->CategoryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update category Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_categories` WHERE `CategoryId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Category Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_categories` WHERE `CategoryStatus`= '1' ORDER BY CategorySequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Category Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAllMegaCategory($m = 'mega'){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_categories` WHERE `CategoryStatus`= '1' AND `CategoryParent` = :a  ORDER BY CategorySequence DESC");
        $statement->execute([ ':a' => $m ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Category Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByParent($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_categories` WHERE `CategoryStatus`= '1' AND `CategoryParent` = :a  ORDER BY CategorySequence DESC");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Category Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByNoChildren(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_categories` WHERE `CategoryStatus`= '1' AND `CategoryId` NOT IN (SELECT CategoryParent FROM `p_items_categories` WHERE 1)  ORDER BY CategorySequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Category Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items_categories` SET `CategoryStatus`= '00' WHERE `CategoryId` = :a");
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