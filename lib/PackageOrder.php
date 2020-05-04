<?php
/**
 * @filename: PackageOrder.php
 * @role: PackageOrder object
 * @author: avatar
 * @license : Proriatery
 */
class PackageOrder{
    private $Connection;
    private $OrderId;
    private $UserId;
    private $PackageId;
    private $Adults;
    private $Children;
    private $Checkin;
    private $QtyCharged;
    private $Amount;
    private $CommAmount;

    function __construct($Connection = null, $OrderId = null, $UserId = null, $PackageId = null, $Adults = null, $Children = null, $Checkin = null, $QtyCharged = null, $Amount = null, $CommAmount = null){
        $this->Connection = $Connection;
        $this->OrderId = $OrderId;
        $this->UserId = $UserId;
        $this->PackageId = $PackageId;
        $this->Adults = $Adults;
        $this->Children = $Children;
        $this->Checkin = $Checkin;
        $this->QtyCharged = $QtyCharged;
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
        if(empty($this->PackageId)){
            throw new Exception("Package ID Field Is Blank! ");
            return false;
        }
        if(empty($this->Adults)){
            throw new Exception("Adults  Field Is Blank! ");
            return false;
        }
        // if(empty($this->Children)){
        //     throw new Exception("Hire From Date Field Is Blank! ");
        //     return false;
        // }
        if(empty($this->Checkin)){
            throw new Exception("Checkin Date Field Is Blank! ");
            return false;
        }
        if(empty($this->QtyCharged)){
            throw new Exception("Qty Field Is Blank! ");
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
            $statement = $this->Connection->prepare("INSERT INTO `orders_holiday`(`OrderId`, `UserId`, `PackageId`, `Adults`, `Children`, `Checkin`, `QtyCharged`, `Amount`, `CommAmount`) VALUES(:a,:b,:c,:d,:e,:f,:g,:h,:i)");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackageId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Adults, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Children, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Checkin, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->QtyCharged, PDO::PARAM_STR);
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
            $statement = $this->Connection->prepare("UPDATE `orders_holiday` SET `UserId`=:b, `PackageId`=:c,`Adults`=:d,`Children`=:e,`Checkin`=:f, `QtyCharged`=:g, `Amount`=:h, `CommAmount`=:i WHERE `OrderId`=:a");
            $statement->bindParam(':a', $this->OrderId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->PackageId, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->Adults, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->Children, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->Checkin, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->QtyCharged, PDO::PARAM_STR);
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
        $statement = $this->Connection->prepare("UPDATE `orders_holiday` SET `PaymentStatus`= 1 WHERE `OrderId`=:a");
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
        $order_item_id = $order_meta['PackageId'];
        $order_item_meta = $this->FindItemMeta($order_item_id);
        $order_item_merchant_meta = $util->FindUserById($order_item_meta['PackageOwner']);
        $order_user_meta = $util->FindUserById($order_user_id);
        //if($order_amt == $paid_amount){}
        $sms_to_seller = 'New order(Holiday Package) ' . $OrderId . ' was placed. We acknowledge reception of KES '. $paid_amount. ' on your behalf. Login to your portal for more details';
        $sms_to_admin = 'New order(Holiday Package) ' . $OrderId . ' of KES '. $paid_amount. ' was successfully received. Login to your portal for more details';
        $sms_to_buyer = 'Dear ' . $order_user_meta['UserFullName'] . ', Your order ' . $OrderId . ' was successful. We acknowledge reception of KES '. $paid_amount;
        //confirm order sms
        $util->Sms($order_user_meta['UserPhone'], $sms_to_buyer);
        $util->Sms($order_item_merchant_meta['UserPhone'], $sms_to_seller);
        $util->Sms(SUPPORT_TEL, $sms_to_admin);
        //confirm order email - to buyer
        $_template = $util->FetchMailTemplates(2);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['PackageName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_user_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_buyer]);
        $MailParams = ['PataShop New Order', $order_user_meta['UserEmail'], $order_user_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to seller
        $_template = $util->FetchMailTemplates(2);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['PackageName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]',$order_item_merchant_meta['UserFullName']]);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_seller]);
        $MailParams = ['PataShop New Order', $order_item_merchant_meta['UserEmail'], $order_item_merchant_meta['UserFullName'], $_template];
        $util->Mail($MailParams);
        
        //confirm order to admin
        $_template = $util->FetchMailTemplates(2);
        $_template = $util->Fill($_template, ['[ORDER]',$OrderId]);
        $_template = $util->Fill($_template, ['[ITEM]',$order_item_meta['PackageName']]);
        $_template = $util->Fill($_template, ['[CAMOUNT]',$order_meta['Amount']]);
        $_template = $util->Fill($_template, ['[PAMOUNT]',$paid_amount]);
        $_template = $util->Fill($_template, ['[NAME]','Admin']);
        $_template = $util->Fill($_template, ['[MSG]',$sms_to_admin]);
        $MailParams = ['PataShop New Order', SMTP_USER, 'Patashop Internals', $_template];
        $util->Mail($MailParams);
    }
    function FindItemMeta($PackageId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_packages` WHERE `PackageId` = :a");
        $statement->execute([ ':a' => $PackageId ]);
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
        $statement = $this->Connection->prepare("SELECT * FROM `orders_holiday` WHERE `OrderId` = :a");
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
        $statement = $this->Connection->prepare("SELECT * FROM `orders_holiday` WHERE `UserId` = :a");
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
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `orders_holiday` WHERE `OrderId` = :a");
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