<?php 
/**
 * @filename: ShippingCalc.php
 * @role: ShippingCalc object
 * @author: avatar
 * @license : Proriatery
 */
class ShippingCalc{
	private $conn;
	function __construct($conn){
		$this->conn = $conn;
	}
    function Payload(){
    	$i = 0;
    	$payload = [];
        foreach( $_SESSION['curr_usr_cart'] as $crt ){
        	$vendor_id = $this->Vendor($crt[0])['ProductOwner'];
        	$payload[$vendor_id][$i] = $crt[0];
        	$i++;
        }
        return $payload;
    }
    function User($id){
    	$util = new Util();
        $statement = $this->conn->prepare("SELECT * FROM `p_users` WHERE `UserId`=:a AND `UserStatus` = '1'");
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
    function Vendor($id){
    	$util = new Util();
        $statement = $this->conn->prepare("SELECT * FROM `p_items` WHERE `ProductId` = :a");
        $statement->execute([':a' => $id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Shop Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
}
?>