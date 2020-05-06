<?php
/**
 * @filename: Size.php
 * @role: size object
 * @author: avatar
 * @license : Proriatery
 */
class Preference{
    private $Connection;
    private $UserId;
    private $PrefAltAddress;
    private $ReceiverAdsMails;
    private $ReceiveNews;
    private $ReceiveSuggestedItems;
    private $ShipToAltAddress;

    function __construct($Connection = null, $UserId = null, $PrefAltAddress = null, $ReceiverAdsMails = null, $ReceiveNews = null, $ReceiveSuggestedItems = null, $ShipToAltAddress = null){
        $this->Connection = $Connection;
        $this->UserId = $UserId;
        $this->PrefAltAddress = $PrefAltAddress;
        $this->ReceiverAdsMails = $ReceiverAdsMails;
        $this->ReceiveNews = $ReceiveNews;
        $this->ReceiveSuggestedItems = $ReceiveSuggestedItems;
        $this->ShipToAltAddress = $ShipToAltAddress;
    }
    function ValidateFields(){
        if(empty($this->UserId)){
            throw new Exception("User ID Field Is Blank! ");
            return false;
        }
        if(empty($this->PrefAltAddress)){
            throw new Exception("Address Field Is Blank! ");
            return false;
        }
        if($this->ReceiverAdsMails == ''){
            throw new Exception("Ads flag Field Is Blank! ");
            return false;
        }
        if($this->ReceiveNews = ''){
            throw new Exception("News flag Field Is Blank! ");
            return false;
        }
        if($this->ReceiveSuggestedItems == ''){
            throw new Exception("Suggested items flag Field Is Blank! ");
            return false;
        }
        if($this->ShipToAltAddress == ''){
            throw new Exception("Ship-to-Alt address flag Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `user_pref`(`PrefUserId`, `PrefAltAddress`, `ReceiverAdsMails`, `ReceiveNews`, `ReceiveSuggestedItems`, `ShipToAltAddress`) VALUES (:a,:b,:c,:d,:e,:f)");
            $statement->bindParam(':a', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PrefAltAddress, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ReceiverAdsMails, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ReceiveNews, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->ReceiveSuggestedItems, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->ShipToAltAddress, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create preference. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `user_pref` SET `PrefAltAddress`=:b,`ReceiverAdsMails`=:c,`ReceiveNews`=:d,`ReceiveSuggestedItems`=:e, `ShipToAltAddress`=:f WHERE `PrefUserId`=:a");
            $statement->bindParam(':a', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->PrefAltAddress, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ReceiverAdsMails, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ReceiveNews, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->ReceiveSuggestedItems, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->ShipToAltAddress, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update user preference. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindByUser($user){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `user_pref` WHERE `PrefUserId`=:a");
        $statement->execute([ ':a' => $user ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get user preferences. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
}
?>