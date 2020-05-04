<?php
/**
 * @filename: Param.php
 * @role: category object
 * @author: avatar
 * @license : Proriatery
 */
class Param{
    private $Connection;
    private $ParamId;
    private $ParamProduct;
    private $Width;
    private $Weight;
    private $Length;
    private $Height;
    private $ParamStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $ParamId = null, $ParamProduct = null, $Width = null, $Weight = null, $Length = null, $Height = null, $ParamStatus = null){
        $this->Connection = $Connection;
        $this->ParamId = $ParamId;
        $this->ParamProduct = $ParamProduct;
        $this->Width = $Width;
        $this->Weight = $Weight;
        $this->Length = $Length;
        $this->Height = $Height;
        $this->ParamStatus = $ParamStatus;
    }
    function ValidateFields(){
        if(empty($this->ParamId)){
            throw new Exception("Param ID Field Is Blank! ");
            return false;
        }
        if(empty($this->ParamProduct)){
            throw new Exception("Param Product Field Is Blank! ");
            return false;
        }
        if(empty($this->Width)){
            throw new Exception("Param Width Field Is Blank! ");
            return false;
        }
        if(empty($this->Weight)){
            throw new Exception("Param Weight Field Is Blank! ");
            return false;
        }
        if(empty($this->Length)){
            throw new Exception("Param Length Field Is Blank! ");
            return false;
        }
        if(empty($this->Height)){
            throw new Exception("Param Height Field Is Blank! ");
            return false;
        }
        if(empty($this->ParamStatus)){
            throw new Exception("Param Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_params`(`ParamId`, `ParamProduct`, `Width`, `Weight`, `Length`, `Height`, `ParamStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g)");
            $statement->bindParam(':a', $this->ParamId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ParamProduct, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Width, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Weight, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Length, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Height, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->ParamStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Param Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_params` SET `ParamProduct`=:b,`Width`=:c,`Weight`=:d,`Length`=:e,`Height`=:f,`ParamStatus`=:g WHERE `ParamId`=:a");
            $statement->bindParam(':a', $this->ParamId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ParamProduct, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Width, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Weight, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Length, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Height, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->ParamStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Param Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_params` WHERE `ParamId` = :a");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_params` WHERE `ParamStatus`= '1'");
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
    function FindByProduct($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_params` WHERE `ParamStatus`= '1' AND `ParamProduct` = :a");
        $statement->execute([ ':a' => $id ]);
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
        $statement = $this->Connection->prepare("DELETE FROM `p_items_params` WHERE `ParamId` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_items_params` SET `ParamStatus`= '0' WHERE `ParamId` = :a");
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