<?php
/**
 * @filename: RoomCategory.php
 * @role: RoomCategory object
 * @author: avatar
 * @license : Proriatery
 */
class RoomCategory{
    private $Connection;
    private $RoomCategoryId;
    private $RoomCategoryName;
    private $RoomCategoryDescription;
    private $RoomCategoryStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $RoomCategoryId = null, $RoomCategoryName = null, $RoomCategoryDescription = null, $RoomCategoryStatus = null){
        $this->Connection = $Connection;
        $this->RoomCategoryId = $RoomCategoryId;
        $this->RoomCategoryName = $RoomCategoryName;
        $this->RoomCategoryDescription = $RoomCategoryDescription;
        $this->RoomCategoryStatus = $RoomCategoryStatus;
    }
    function ValidateFields(){
        if(empty($this->RoomCategoryId)){
            throw new Exception("RoomCategory ID Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomCategoryName)){
            throw new Exception("RoomCategory Name Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomCategoryDescription)){
            throw new Exception("RoomCategory Description Field Is Blank! ");
            return false;
        }
        if(empty($this->RoomCategoryStatus)){
            throw new Exception("RoomCategory Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_rooms_categories`(`RoomCategoryId`, `RoomCategoryName`, `RoomCategoryDescription`, `RoomCategoryStatus`) VALUES (:a,:b,:c,:d)");
            $statement->bindParam(':a', $this->RoomCategoryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->RoomCategoryName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomCategoryDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->RoomCategoryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create RoomCategory Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_rooms_categories` SET `RoomCategoryName`=:b, `RoomCategoryDescription`=:c,`RoomCategoryStatus`=:d WHERE `RoomCategoryId`=:a");
            $statement->bindParam(':a', $this->RoomCategoryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->RoomCategoryName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomCategoryDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->RoomCategoryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update RoomCategory Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms_categories` WHERE `RoomCategoryId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get RoomCategory Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll($limit = 5){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms_categories` WHERE `RoomCategoryStatus`= '1' ORDER BY RoomCategorySequence DESC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get RoomCategory Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_rooms_categories` SET `RoomCategoryStatus`= '00' WHERE `RoomCategoryId` = :a");
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