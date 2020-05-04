<?php
/**
 * @filename: CarOrder.php
 * @role: CarOrder object
 * @author: avatar
 * @license : Proriatery
 */
class CarOrder{
    private $Connection;
    private $OrderId;
    private $UserId;
    private $CarId;
    private $Pickup;
    private $HireFrom;
    private $HireTill;
    private $NightsCharged;
    private $Amount;
    private $CommAmount;

    function __construct($Connection = null, $OrderId = null, $UserId = null, $CarId = null, $Pickup = null, $HireFrom = null, $HireTill = null, $NightsCharged = null, $Amount = null, $CommAmount = null){
        $this->Connection = $Connection;
        $this->OrderId = $OrderId;
        $this->UserId = $UserId;
        $this->CarId = $CarId;
        $this->Pickup = $Pickup;
        $this->HireFrom = $HireFrom;
        $this->HireTill = $HireTill;
        $this->NightsCharged = $NightsCharged;
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
        if(empty($this->CarId)){
            throw new Exception("Car Field Is Blank! ");
            return false;
        }
        if(empty($this->Pickup)){
            throw new Exception("Pickup  Field Is Blank! ");
            return false;
        }
        if(empty($this->HireFrom)){
            throw new Exception("Hire From Date Field Is Blank! ");
            return false;
        }
        if(empty($this->HireTill)){
            throw new Exception("Hire Till Date Field Is Blank! ");
            return false;
        }
        if(empty($this->NightsCharged)){
            throw new Exception("No. of Nights Field Is Blank! ");
            return false;
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
            $statement = $this->Connection->prepare("INSERT INTO `orders_car`(`OrderId`, `UserId`, `CarId`, `Pickup`, `HireFrom`, `HireTill`, `NightsCharged`, `Amount`, `CommAmount`) VALUES(:a,:b,:c,:d,:e,:f,:g,:h,:i)");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CarId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Pickup, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->HireFrom, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->HireTill, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->NightsCharged, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->CommAmount, PDO::PARAM_STR);
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
            $statement = $this->Connection->prepare("UPDATE `orders_car` SET `UserId`=:b, `CarId`=:c,`Pickup`=:d,`HireFrom`=:e,`HireTill`=:f, `NightsCharged`=:g, `Amount`=:h, `CommAmount`=:i WHERE `OrderId`=:a");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->CarId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Pickup, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->HireFrom, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->HireTill, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->NightsCharged, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->CommAmount, PDO::PARAM_STR);
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
        $statement = $this->Connection->prepare("UPDATE `orders_car` SET `PaymentStatus`= 1 WHERE `OrderId`=:a");
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
        $order_user_id = $order_meta['UserId'];
        $order_amt = $order_meta['Amount'];
        $order_item_id = $order_meta['CarId'];
        $order_item_meta = $this->FindItemMeta($order_item_id);
        $order_item_merchant_meta = $util->FindUserById($order_item_meta['CarOwner']);
        $order_user_meta = $util->FindUserById($order_user_id);
        //if($order_amt == $paid_amount){}
        $sms_to_seller = 'New order(Car Hire Booking) ' . $OrderId . ' was placed. We acknowledge reception of KES '. $paid_amount. ' on your behalf. Login to your portal for more details';
        $sms_to_admin = 'New order(Car Hire Booking) ' . $OrderId . ' of KES '. $paid_amount. ' was successfully received. Login to your portal for more details';
        $sms_to_buyer = 'Dear ' . $order_user_meta['UserFullName'] . ', Your order ' . $OrderId . ' was successful. We acknowledge reception of KES '. $paid_amount.'. Login to your portal for more information.';
        //confirm order sms
        $util->Sms($order_user_meta['UserPhone'], $sms_to_buyer);
        $util->Sms($order_item_merchant_meta['UserPhone'], $sms_to_seller);
        $util->Sms(SUPPORT_TEL, $sms_to_admin);
        //confirm order email - to buyer
        $_template = $util->FetchMailTemplates(4);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['CarName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_buyer]);
        $_template = $util->Fill($_template, ['[FROM]',$order_meta['HireFrom']]);
        $_template = $util->Fill($_template, ['[TILL]',$order_meta['HireTill']]);
        $_template = $util->Fill($_template, ['[LOCATION]',$order_meta['Pickup']]);
        $_template = $util->Fill($_template, ['[PLATE]',$order_item_meta['CarPlateNumber']]);
        $_template = $util->Fill($_template, ['[MODEL]',$order_item_meta['CarBrand']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Care Hire Reservation', $order_user_meta['UserEmail'], $order_user_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to seller
        $_template = $util->FetchMailTemplates(4);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['CarName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_seller]);
        $_template = $util->Fill($_template, ['[FROM]',$order_meta['HireFrom']]);
        $_template = $util->Fill($_template, ['[TILL]',$order_meta['HireTill']]);
        $_template = $util->Fill($_template, ['[LOCATION]',$order_meta['Pickup']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Care Hire Reservation', $order_item_merchant_meta['UserEmail'], $order_item_merchant_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to admin
        $_template = $util->FetchMailTemplates(4);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['CarName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_seller]);
        $_template = $util->Fill($_template, ['[FROM]',$order_meta['HireFrom']]);
        $_template = $util->Fill($_template, ['[TILL]',$order_meta['HireTill']]);
        $_template = $util->Fill($_template, ['[LOCATION]',$order_meta['Pickup']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Care Hire Reservation', SMTP_USER, 'Patashop Internals', $_template];
        $util->Mail($MailParams);
    }
    function FindItemMeta($CarId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_cars` WHERE `CarId` = :a");
        $statement->execute([ ':a' => $CarId ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($OrderId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `orders_car` WHERE `OrderId` = :a");
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
        $statement = $this->Connection->prepare("SELECT * FROM `orders_car` WHERE `UserId` = :a");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `orders_car` WHERE `OrderId` = :a");
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