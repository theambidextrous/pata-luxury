<?php
/**
 * @filename: User.php
 * @role: user object
 * @author: avatar
 * @license : Proriatery
 */
class User{
    private $Connection;
    private $UserId;
    private $UserFullName;
    private $UserEmail;
    private $UserPhone;
    private $UserPhoneAlt;
    private $UserCurrency;
    private $UserCountry;
    private $UserCounty;
    private $UserCity;
    private $UserType;
    private $UserShippingAddress;
    private $UserPassword;
    private $UserProfilePhoto;
    private $UserStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $UserId = null, $UserFullName = null, $UserEmail = null, $UserPhone = null, $UserPhoneAlt = null, $UserCurrency = null, $UserCountry = null, $UserCounty = null, $UserCity = null, $UserType = null, $UserShippingAddress = null, $UserPassword = null, $UserProfilePhoto = null, $UserStatus = null){
        $this->Connection = $Connection;
        $this->UserId = $UserId;
        $this->UserFullName = $UserFullName;
        $this->UserEmail = $UserEmail;
        $this->UserPhone = $UserPhone;
        $this->UserPhoneAlt = $UserPhoneAlt;
        $this->UserCurrency = $UserCurrency;
        $this->UserCountry = $UserCountry;
        $this->UserCounty = $UserCounty;
        $this->UserCity = $UserCity;
        $this->UserType = $UserType;
        $this->UserShippingAddress = $UserShippingAddress;
        $this->UserPassword = $UserPassword;
        $this->UserProfilePhoto = $UserProfilePhoto;
        $this->UserStatus = $UserStatus;
    }
    function ValidateFields(){
        if(empty($this->UserId)){
            throw new Exception("User ID Field Is Blank! ");
            return false;
        }
        if(empty($this->UserFullName)){
            throw new Exception("User Full Name Field Is Blank! ");
            return false;
        }
        if(empty($this->UserEmail)){
            throw new Exception("User Email Field Is Blank! ");
            return false;
        }
        if(empty($this->UserPhone)){
            throw new Exception("User Phone 1 Field Is Blank! ");
            return false;
        }
        if(empty($this->UserPhoneAlt)){
            throw new Exception("User Phone 2 Field Is Blank! ");
            return false;
        }

        if(empty($this->UserCurrency || $this->UserCurrency == 'nn')){
            throw new Exception("User Currency Field Is Blank! ");
            return false;
        }
        if(empty($this->UserCountry || $this->UserCountry == 'nn')){
            throw new Exception("User Country Field Is Blank! ");
            return false;
        }
        if(empty($this->UserCounty)){
            throw new Exception("User County Field Is Blank! ");
            return false;
        }
        if(empty($this->UserCity)){
            throw new Exception("User City Field Is Blank! ");
            return false;
        }

        if(empty($this->UserType || $this->UserType == 'nn')){
            throw new Exception("User Type Field Is Blank! ");
            return false;
        }
        if(empty($this->UserShippingAddress)){
            throw new Exception("User address Field Is Blank! ");
            return false;
        }
        if(empty($this->UserPassword)){
            throw new Exception("User Password Field Is Blank! ");
            return false;
        }
        if(empty($this->UserProfilePhoto)){
            throw new Exception("User Profile Photo Field Is Blank! ");
            return false;
        }
        if(empty($this->UserStatus)){
            throw new Exception("User Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_users`(`UserId`, `UserFullName`, `UserEmail`, `UserPhone`, `UserPhoneAlt`, `UserCurrency`, `UserCountry`, `UserCounty`, `UserCity`, `UserType`, `UserShippingAddress`, `UserPassword`,`UserProfilePhoto`, `UserStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n)");
            $statement->bindParam(':a', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserFullName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->UserEmail, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->UserPhone, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->UserPhoneAlt, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->UserCurrency, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->UserCountry, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->UserCounty, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->UserCity, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->UserType, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->UserShippingAddress, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->UserPassword, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->UserProfilePhoto, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->UserStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create User. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_users` SET `UserFullName`=:b,`UserEmail`=:c,`UserPhone`=:d,`UserPhoneAlt`=:e,`UserCurrency`=:f,`UserCountry`=:g,`UserCounty`=:h,`UserCity`=:i,`UserType`=:j,`UserShippingAddress`=:k,`UserPassword`=:l, `UserProfilePhoto`=:m, `UserStatus`=:n WHERE `UserId`=:a");
            $statement->bindParam(':a', $this->UserId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->UserFullName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->UserEmail, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->UserPhone, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->UserPhoneAlt, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->UserCurrency, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->UserCountry, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->UserCounty, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->UserCity, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->UserType, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->UserShippingAddress, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->UserPassword, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->UserProfilePhoto, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->UserStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update User. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserStatus` = '1'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Users. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserId`=:a AND `UserStatus` = '1'");
        $statement->execute([':a' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get User. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindMerchantById($id, $type){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserId`=:a AND `UserType` = :b");
        $statement->execute([':a' => $id, ':b' => $type]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get User. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByEmail($em){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserEmail`=:a AND `UserStatus` = '1'");
        $statement->execute([':a' => $em]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get User. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByType($ty){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserType`=:a AND `UserStatus` = '1'");
        $statement->execute([':a' => $ty]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Users. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindUserNotes($UserId){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `user_notes` WHERE `NoteUserId`=:a ORDER BY `NoteSequence` DESC limit 10");
        $statement->execute([':a' => $UserId]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get User Notes. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindDeleted(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserStatus` = '00'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Users. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindDisabled(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_users` WHERE `UserStatus` = '0'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Users. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_users` SET `UserStatus`= '00' WHERE `UserId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete user. Contact Admins');
            return false;
        }
        return true;
    }
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_users` SET `UserStatus`= '0' WHERE `UserId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not disable user. Contact Admins');
            return false;
        }
        return true;
    }
    function Enable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_users` SET `UserStatus`= '1' WHERE `UserId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not enable user. Contact Admins');
            return false;
        }
        return true;
    }
    function Notify($User, $UserPassDefault, $kind = 0){//$kind = message type
        $UserMeta = $this->FindById($User);
        $util = new Util();
        $template = $util->FetchMailTemplates($kind);
        $template = $util->Fill($template, ['[NAME]',$UserMeta['UserFullName']]);
        $template = $util->Fill($template, ['[USERNAME]',$UserMeta['UserEmail']]);
        $template = $util->Fill($template, ['[PASSWORD]',$UserPassDefault]);
        $template = $util->Fill($template, ['[LOGIN_URL]',APP_ADMIN]);
        $MailParams = ['PataShop Account Creation', $UserMeta['UserEmail'], $UserMeta['UserFullName'], $template];
        $sms = 'PataShop Account Created. Login using your email and default password: '.$UserPassDefault;
        $util->Sms($UserMeta['UserPhone'], $sms);
        $util->Mail($MailParams);
    }
    function SendOtp($UserEmail, $otp, $kind = 1){
        $UserMeta = $this->FindByEmail($UserEmail);
        $util = new Util();
        $template = $util->FetchMailTemplates($kind);
        $template = $util->Fill($template, ['[NAME]',$UserMeta['UserFullName']]);
        $template = $util->Fill($template, ['[OTP]',$otp]);
        $MailParams = ['PataShop Account OTP', $UserMeta['UserEmail'], $UserMeta['UserFullName'], $template];
        $sms = 'Use OTP: '.$otp. ' to access your account';
        $util->Sms($UserMeta['UserPhone'], $sms);
        $util->Mail($MailParams);
    }
    function ChangePassword($data){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_users` SET `UserPassword`= :b WHERE `UserEmail` = :a");
        $statement->execute([ ':a' => $data[0], ':b' => $data[1] ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not update user password. Contact Admins');
            return false;
        }
        return true;
    }
}
?>