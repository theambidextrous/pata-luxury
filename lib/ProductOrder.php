<?php
/**
 * @filename: EcommerceOrder.php
 * @role: EcommerceOrder object
 * @author: avatar
 * @license : Proriatery
 */
class EcommerceOrder{
    private $Connection;
    private $OrderId;
    private $UserId;
    private $ProductString;
    private $CommString;
    private $Amount;
    private $CommAmount;

    function __construct($Connection = null, $OrderId = null, $UserId = null, $ProductString = null, $CommString = null, $Amount = null, $CommAmount = null){
        $this->Connection = $Connection;
        $this->OrderId = $OrderId;
        $this->UserId = $UserId;
        $this->ProductString = $ProductString;
        $this->CommString = $CommString;
        $this->Amount = $Amount;
        $this->CommAmount = $CommAmount;
    }
    function ValidateFields(){
        if(empty($this->OrderId)){
            throw new Exception("Order ID Field Is Blank! ");
            return false;
        }
        if(empty($this->UserId)){
            throw new Exception("User ID Field Is Blank! ");
            return false;
        }
        if(empty($this->ProductString)){
            throw new Exception("Order Items Field Is Blank! ");
            return false;
        }
        if(empty($this->CommString)){
            throw new Exception("Order String Is not complete");
            return;
        }
        if(empty($this->Amount)){
            throw new Exception("Amount Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `orders_ecommerce`(`OrderId`, `UserId`, `ProductString`, `CommString` `Amount`, `CommAmount`) VALUES (:a,:b,:c,:d,:e,:f)");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ProductString, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CommString, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CommAmount, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `orders_ecommerce` SET `UserId`=:b,`ProductString`=:c, `CommString`=:d, `Amount`=:e,`CommAmount`=:f WHERE `OrderId`=:a");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ProductString, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->CommString, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->CommAmount, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function UpdatePay($OrderId, $paid_amount = 0){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `orders_ecommerce` SET `PaymentStatus`= 1 WHERE `OrderId`=:a");
        $statement->bindParam(':a', $OrderId, PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        $updated_rows = $statement->rowCount();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not update Item. Contact Admins');
            return false;
        }
        $order_meta = $this->FindById($OrderId);
    }
    function FindItemMeta($ProductId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items` WHERE `ProductId` = :a");
        $statement->execute([ ':a' => $ProductId ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($OrderId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `orders_ecommerce` WHERE `OrderId` = :a");
        $statement->execute([ ':a' => $OrderId ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByUser($UserId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `orders_ecommerce` WHERE `UserId` = :a");
        $statement->execute([ ':a' => $UserId ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function exists($OrderId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `orders_ecommerce` WHERE `OrderId` = :a");
        $statement->execute([ ':a' => $OrderId ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not find Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if( $res['cnt'] > 0 ){
            return true;
        }
        return false;
    }
    
}
?>