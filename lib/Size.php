<?php
/**
 * @filename: Size.php
 * @role: size object
 * @author: avatar
 * @license : Proriatery
 */
class Size{
    private $Connection;
    private $SizeId;
    private $SizeName;
    private $SizeValue;
    private $SizeProduct;
    private $SizeStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $SizeId = null, $SizeName = null, $SizeValue = null, $SizeProduct = null, $SizeStatus = null){
        $this->Connection = $Connection;
        $this->SizeId = $SizeId;
        $this->SizeName = $SizeName;
        $this->SizeValue = $SizeValue;
        $this->SizeProduct = $SizeProduct;
        $this->SizeStatus = $SizeStatus;
    }
    function ValidateFields(){
        if(empty($this->SizeId)){
            throw new Exception("Size ID Field Is Blank! ");
            return false;
        }
        if(empty($this->SizeName)){
            throw new Exception("Size Name Field Is Blank! ");
            return false;
        }
        if(empty($this->SizeValue)){
            throw new Exception("Size Value Field Is Blank! ");
            return false;
        }
        if(empty($this->SizeProduct)){
            throw new Exception("Size Product Field Is Blank! ");
            return false;
        }
        if(empty($this->SizeStatus)){
            throw new Exception("Size Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_sizes`(`SizeId`, `SizeName`, `SizeValue`, `SizeProduct`, `SizeStatus`) VALUES(:a,:b,:c,:d,:e)");
            $statement->bindParam(':a', $this->SizeId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->SizeName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->SizeValue, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->SizeProduct, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->SizeStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Size Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_sizes` SET `SizeName`=:b,`SizeValue`=:c,`SizeProduct`=:d,`SizeStatus`=:e WHERE `SizeId`=:a");
            $statement->bindParam(':a', $this->SizeId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->SizeName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->SizeValue, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->SizeProduct, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->SizeStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not Update Size Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_sizes` WHERE `SizeId` = :a");
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
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_sizes` WHERE `SizeStatus`= '1'");
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
    function FindOneByProduct($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_sizes` WHERE `SizeStatus`= '1' AND `SizeProduct` = :a ORDER BY SizeSequence DESC LIMIT 1");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByProduct($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_sizes` WHERE `SizeStatus`= '1' AND `SizeProduct` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_items_sizes` SET `SizeStatus`= '00' WHERE `SizeId` = :a");
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
        $statement = $this->Connection->prepare("UPDATE `p_items_sizes` SET `SizeStatus`= '0' WHERE `SizeId` = :a");
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