<?php
/**
 * @filename: Pack.php
 * @role: Pack object
 * @author: avatar
 * @license : Proriatery
 */
class Pack{
    private $Connection;
    private $PackId;
    private $PackName;
    private $PackStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $PackId = null, $PackName = null, $PackStatus = null){
        $this->Connection = $Connection;
        $this->PackId = $PackId;
        $this->PackName = $PackName;
        $this->PackStatus = $PackStatus;
    }
    function ValidateFields(){
        if(empty($this->PackId)){
            throw new Exception("Pack ID Field Is Blank! ");
            return false;
        }
        if(empty($this->PackName)){
            throw new Exception("Pack Name Field Is Blank! ");
            return false;
        }
        if(empty($this->PackStatus)){
            throw new Exception("Pack Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `servicepacks`(`PackId`, `PackName`, `PackStatus`) VALUES(:a,:b,:c)");
            $statement->bindParam(':a', $this->PackId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PackName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Pack Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `servicepacks` SET `PackName`=:b, `PackStatus`=:c WHERE `PackId`=:a");
            $statement->bindParam(':a', $this->PackId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PackName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not Update Pack Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `servicepacks` WHERE `PackId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `servicepacks` WHERE `PackStatus`= '1'");
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
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `servicepacks` SET `PackStatus`= '00' WHERE `PackId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete item. Contact Admins');
            return false;
        }
        return true;
    }
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `servicepacks` SET `PackStatus`= '0' WHERE `PackId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not disable item. Contact Admins');
            return false;
        }
        return true;
    }
}
?>