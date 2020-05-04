<?php
/**
 * @filename: Color.php
 * @role: color object
 * @author: avatar
 * @license : Proriatery
 */
class Otp{
    private $Connection;

    function __construct($Connection = null){
        $this->Connection = $Connection;
    }
    
    function Generate($l){
        $util = new Util();
        return $util->Otp($l);
    }

    function Create($l, $user){
        $this->Remove();
        $util = new Util();
        $otp = $this->Generate($l);
        $statement = $this->Connection->prepare("INSERT INTO `user_otps`(`UserEmail`, `Otp`) VALUES (:a, :b)");
        $statement->bindParam(':a', $user, PDO::PARAM_STR);
        $statement->bindParam(':b', $otp, PDO::PARAM_INT);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not generate OTP. Contact Admins');
            return false;
        }
        return $otp;
    }

    function Validate($user, $otp){
        $this->Remove();
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT `Otp` FROM `user_otps` WHERE `UserEmail`=:a AND `OtpStatus`='1' ORDER BY `OtpSequence` DESC LIMIT 1");
        $statement->bindParam(':a', $user, PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not validate OTP. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if($res['Otp'] == $otp ){
            return true;
        }
        return false;
    }
    function LogAccess($UserEmail, $act){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `user_logins`(`UserEmail`, `isLoggedIn`) VALUES (:a, :b)");
        $statement->bindParam(':a', $UserEmail, PDO::PARAM_STR);
        $statement->bindParam(':b', $act, PDO::PARAM_INT);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not verify user login history. Contact Admins');
            return false;
        }
        return true;
    }
    function FirstLogin($UserEmail){
        $statement = $this->Connection->prepare("SELECT COUNT(*) AS cnt FROM `user_logins` WHERE UserEmail = :a");
        $statement->bindParam(':a', $UserEmail, PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not verify user login history. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] < 1 ){
            return true;
        }
        return false;
    }
    function Remove(){
        $util = new Util();
        $s = $this->Connection->prepare("UPDATE `user_otps` SET `OtpStatus` = '00' WHERE `Created` < (NOW() - INTERVAL 10 MINUTE)");
        $s->execute();
        $rs = $s->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not clear OTP. Contact Admins');
            return false;
        }
        return true;
    }
}
?>