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
    function ShippingType($item){
        return '';
    }
    function isShipped($items){
        $is_shipped = [];
        foreach( $items as $item ):
            if($this->ShippingType($item[0]) == '2003'){
                array_push($is_shipped, 1);
            }
        endforeach;
        if( array_sum($is_shipped) > 0 ){
            return true;
        }
        return false;
    }
    function GroupByVendor(){
        $i = 0;
        $payload = [];
        foreach( $_SESSION['curr_usr_cart'] as $crt ){
          $vendor_id = $this->Vendor($crt[0])['ProductOwner'];
          // $cart_item = [$item, $qty, $color, $size];
        	$payload[$vendor_id][$i] = [
              $crt[0],
              $crt[1],
              $crt[2],
              $crt[3],
              $this->ShippingType($crt[0])
            ];
        	$i++;
        }
        return $payload;
    }
    function FindAddress(){
        $util = new Util();
        $statement = $this->conn->prepare("SELECT SiteAddress FROM `p_setting` LIMIT 1");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. Packaging Center Address Not Found. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res['SiteAddress'];
    }
    function ShippingCost($OrderUserId){
      if(empty($OrderUserId)){
        throw new Exception('Unknown user id supplied. No associated addresss!');
      }
      $customer_meta = $this->User($OrderUserId);
      $route_distance_arr = [];
      $payload = $this->GroupByVendor();
      foreach ( $payload as $vendor => $items ){
          $vendor_meta = $this->User($vendor);
          $vendor_warehouse_address = $vendor_meta['UserShippingAddress'];
          $packaging_address = $this->FindAddress();
          $customer_address = $customer_meta['UserShippingAddress'];
          
          $util = new Util($vendor_warehouse_address, $packaging_address, MAPS_KEY);
          $warehouse_to_packaging = $util->ComputeDistance()['d'];
          
          $util = new Util($packaging_address, $customer_address, MAPS_KEY);
          $packaging_to_customer = $util->ComputeDistance()['d'];
          $d = $warehouse_to_packaging + $packaging_to_customer;
          array_push($route_distance_arr, $d);
      }
      $total_distance = array_sum($route_distance_arr);
      return [
          'd' => $total_distance,
          'c' => floor($total_distance*S_RATE),
          'r' => S_RATE
      ];
    }
    function Payload($Order_User = '', $OrderId = ''){
        $util = new Util();
        $payload = $this->GroupByVendor();
        $post_fields = [];
        // (
        //     [lXNOgSeVjn6hJtWm] => Array
        //         (
        //             [0] => O27TtQMZomAIwE1PrxiV
        //             [3] => LcCYzsZ5SEKqnF4Pxh7N
        //             [5] => nCF7xebLsMjTZUwJ6mNW
        //         )

        //     [xkBELPJlHGrjhRcF] => Array
        //         (
        //             [1] => NR3lm6bFSH8dov5ZVBL1
        //             [2] => NR3lm6bFSHxdov5ZVdf5
        //             [4] => J9RHzU3sPT70uIDtdLX4
        //         )
        // )

        // [location] => Array
        // (
        //     [lat] => -1.278635
        //     [lng] => 36.7506891
        // )
        
        // return $util->GetLonLat('Kawangware 56');
        // return $payload;
        $unique_idfy = 0;
        foreach ( $payload as $vendor => $items ):
            $shipping_order_identifier = $OrderId . '__' . $unique_idfy;
            $vendor_meta = $this->User($vendor);
            $customer_meta = $this->User($Order_User);
            $ship_warehouse_address = $vendor_meta['UserShippingAddress'];
            $ship_packaging_address = $this->FindAddress();
            $ship_cust_address = $customer_meta['UserShippingAddress'];
            /** if this cart entry has any item that is shipped by sendy */
            if( $this->isShipped($items) == '2003'){

            }
            /** sendy postfields */
            $post_fields['command'] = 'request';
            $post_fields['data']['api_key'] = LS_KEY;
            $post_fields['data']['api_username'] = LS_USER;
            $post_fields['data']['vendor_type'] = 1;
            $post_fields['data']['rider_phone'] = LS_RIDER;
            $post_fields['data']['from']['from_name'] = '';
            $post_fields['data']['from']['from_lat'] = '';
            $post_fields['data']['from']['from_long'] = '';
            $post_fields['data']['from']['from_description'] = '';
            $post_fields['data']['to']['to_name'] = '';
            $post_fields['data']['to']['to_lat'] = '';
            $post_fields['data']['to']['to_long'] = '';
            $post_fields['data']['to']['to_description'] = '';
            $post_fields['data']['recepient']['recepient_name'] = '';
            $post_fields['data']['recepient']['recepient_phone'] = '';
            $post_fields['data']['recepient']['recepient_email'] = '';
            $post_fields['data']['recepient']['recepient_notes'] = '';
            $post_fields['data']['sender']['sender_name'] = '';
            $post_fields['data']['sender']['sender_phone'] = '';
            $post_fields['data']['sender']['sender_email'] = '';
            $post_fields['data']['sender']['sender_notes'] = '';
            $post_fields['data']['delivery_details']['pick_up_date'] = date('Y-m-d H:i:s');
            $post_fields['data']['delivery_details']['collect_payment']['status'] = false;
            $post_fields['data']['delivery_details']['collect_payment']['pay_method'] = 0;
            $post_fields['data']['delivery_details']['collect_payment']['amount'] = 0;
            $post_fields['data']['delivery_details']['return'] = false;
            $post_fields['data']['delivery_details']['note'] = '';
            $post_fields['data']['delivery_details']['note_status'] = true;
            $post_fields['data']['delivery_details']['request_type'] = 'delivery';
            $post_fields['data']['delivery_details']['order_type'] = 'ondemand_delivery';
            $post_fields['data']['delivery_details']['ecommerce_order'] = false;
            $post_fields['data']['delivery_details']['express'] = false;
            $post_fields['data']['delivery_details']['skew'] = 1;
            $post_fields['data']['delivery_details']['package_size'][0]['weight'] = '';
            $post_fields['data']['delivery_details']['package_size'][0]['height'] = '';
            $post_fields['data']['delivery_details']['package_size'][0]['width'] = '';
            $post_fields['data']['delivery_details']['package_size'][0]['length'] = '';
            $post_fields['data']['delivery_details']['package_size'][0]['item_name'] = 'some comma list if multiple from same pickup location';
            $post_fields['request_token_id'] = $shipping_order_identifier;
            
            $unique_idfy ++;
        endforeach;
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
    function SendyIt($payload){
      $json = '{
            "command": "request",
            "data": {
              "api_key": "mysendykey",
              "api_username": "mysendyusername",
              "vendor_type": 1,
              "rider_phone": "0728561783",
              "from": {
                "from_name": "Green House",
                "from_lat": -1.300577,
                "from_long": 36.78183,
                "from_description": ""
              },
              "to": {
                "to_name": "KICC",
                "to_lat": -1.28869,
                "to_long": 36.823363,
                "to_description": ""
              },
              "recepient": {
                "recepient_name": "Sender Name",
                "recepient_phone": "0709779779",
                "recepient_email": "sendyer@gmail.com",
                "recepient_notes": "recepient specific Notes"
              },
              "sender": {
                "sender_name": "Sendyer Name",
                "sender_phone": "0709 779 779",
                "sender_email": "sendyer@gmail.com",
                "sender_notes": "Sender specific notes"
              },
              "delivery_details": {
                "pick_up_date": "2016-04-20 12:12:12",
                "collect_payment": {
                  "status": false,
                  "pay_method": 0,
                  "amount": 10
                },
                "return": false,
                "note": " Sample note",
                "note_status": true,
                "request_type": "delivery",
                "order_type": "ondemand_delivery",
                "ecommerce_order": false,
                "express": false,
                "skew": 1,
                "package_size": [
                  {
                    "weight": 20,
                    "height": 10,
                    "width": 200,
                    "length": 30,
                    "item_name": "laptop"
                  }
                ]
              }
            },
            "request_token_id": "request_token_id"
          }';
          return json_decode($json, true);
    }
}
?>