<?php
/**
 * @filename: RoomOrder.php
 * @role: RoomOrder object
 * @author: avatar
 * @license : Proriatery
 */
class RoomOrder{
    private $Connection;
    private $OrderId;
    private $UserId;
    private $RoomId;
    private $Adults;
    private $Children;
    private $Checkin;
    private $Checkout;
    private $NightsCharged;
    private $Amount;
    private $CommAmount;

    function __construct($Connection = null, $OrderId = null, $UserId = null, $RoomId = null, $Adults = null, $Children = null, $Checkin = null, $Checkout = null, $NightsCharged = null, $Amount = null, $CommAmount = null){
        $this->Connection = $Connection;
        $this->OrderId = $OrderId;
        $this->UserId = $UserId;
        $this->RoomId = $RoomId;
        $this->Adults = $Adults;
        $this->Children = $Children;
        $this->Checkin = $Checkin;
        $this->Checkout = $Checkout;
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
        if(empty($this->RoomId)){
            throw new Exception("Room Field Is Blank! ");
            return false;
        }
        if(empty($this->Adults)){
            throw new Exception("Adults Qty Field Is Blank! ");
            return false;
        }
        if($this->Children == ''){
            throw new Exception("Children Qty Field Is Blank! ");
            return false;
        }
        if(empty($this->Checkin)){
            throw new Exception("Checkin Date Field Is Blank! ");
            return false;
        }
        if(empty($this->Checkout)){
            throw new Exception("Checkout Date Field Is Blank! ");
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
        // if(empty($this->CommAmount)){
        //     throw new Exception("Commission amount Field Is Blank! ");
        //     return false;
        // }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `orders_room`(`OrderId`, `UserId`, `RoomId`, `Adults`, `Children`, `Checkin`, `Checkout`, `NightsCharged`, `Amount`, `CommAmount`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j)");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Adults, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Children, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Checkin, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->Checkout, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->NightsCharged, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->CommAmount, PDO::PARAM_STR);
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
            $statement = $this->Connection->prepare("UPDATE `orders_room` SET `UserId`=:b, `RoomId`=:c,`Adults`=:d,`Children`=:e,`Checkin`=:f,`Checkout`=:g,`NightsCharged`=:h, `Amount`=:i, `CommAmount`=:j WHERE `OrderId`=:a");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->RoomId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Adults, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Children, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Checkin, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->Checkout, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->NightsCharged, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->Amount, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->CommAmount, PDO::PARAM_STR);
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
        $statement = $this->Connection->prepare("UPDATE `orders_room` SET `PaymentStatus`= 1 WHERE `OrderId`=:a");
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
        $order_item_id = $order_meta['RoomId'];
        $order_item_meta = $this->FindItemMeta($order_item_id);
        $order_item_merchant_meta = $util->FindUserById($order_item_meta['RoomOwner']);
        $order_user_meta = $util->FindUserById($order_user_id);
        //if($order_amt == $paid_amount){}
        $sms_to_seller = 'New order(Room Reservation) ' . $OrderId . ' was placed. We acknowledge reception of KES '. $paid_amount. ' on your behalf. Login to your portal for more details';
        $sms_to_admin = 'New order(Room Reservation) ' . $OrderId . ' of KES '. $paid_amount. ' was successfully received. Login to your portal for more details';
        $sms_to_buyer = 'Dear ' . $order_user_meta['UserFullName'] . ', Your order ' . $OrderId . ' was successful. We acknowledge reception of KES '. $paid_amount.'. Login to your portal for more information.';
        //confirm order sms
        $util->Sms($order_user_meta['UserPhone'], $sms_to_buyer);
        $util->Sms($order_item_merchant_meta['UserPhone'], $sms_to_seller);
        $util->Sms(SUPPORT_TEL, $sms_to_admin);
        //confirm order email - to buyer
        $_template = $util->FetchMailTemplates(3);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['RoomName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_buyer]);
        $_template = $util->Fill($_template, ['[CHECKIN]',$order_meta['Checkin']]);
        $_template = $util->Fill($_template, ['[CHECKOUT]',$order_meta['Checkout']]);
        $_template = $util->Fill($_template, ['[ADULT]',$order_meta['Adults']]);
        $_template = $util->Fill($_template, ['[CHILDREN]',$order_meta['Children']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Hotel Reservation', $order_user_meta['UserEmail'], $order_user_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to seller
        $_template = $util->FetchMailTemplates(3);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['RoomName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_seller]);
        $_template = $util->Fill($_template, ['[CHECKIN]',$order_meta['Checkin']]);
        $_template = $util->Fill($_template, ['[CHECKOUT]',$order_meta['Checkout']]);
        $_template = $util->Fill($_template, ['[ADULT]',$order_meta['Adults']]);
        $_template = $util->Fill($_template, ['[CHILDREN]',$order_meta['Children']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Hotel Reservation', $order_item_merchant_meta['UserEmail'], $order_item_merchant_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to admin
        $_template = $util->FetchMailTemplates(3);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['RoomName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_buyer]);
        $_template = $util->Fill($_template, ['[CHECKIN]',$order_meta['Checkin']]);
        $_template = $util->Fill($_template, ['[CHECKOUT]',$order_meta['Checkout']]);
        $_template = $util->Fill($_template, ['[ADULT]',$order_meta['Adults']]);
        $_template = $util->Fill($_template, ['[CHILDREN]',$order_meta['Children']]);
        $_template = $util->Fill($_template, ['[QTY]',$order_meta['NightsCharged']]);

        $MailParams = ['PataShop New Hotel Reservation', SMTP_USER, 'Patashop Internals', $_template];
        $util->Mail($MailParams);
    }
    function FindItemMeta($RoomId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_rooms` WHERE `RoomId` = :a");
        $statement->execute([ ':a' => $RoomId ]);
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
        $statement = $this->Connection->prepare("SELECT * FROM `orders_room` WHERE `OrderId` = :a");
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
        $statement = $this->Connection->prepare("SELECT * FROM `orders_room` WHERE `UserId` = :a");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `orders_room` WHERE `OrderId` = :a");
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