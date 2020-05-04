<?php
// 1. Define
class C2B {
	private $consumer_key;
	private $consumer_secret;
	private $shortcode;
	private $msisdn;
	private $amount;
	private $billrefnumber;

	function __construct($consumer_key, $consumer_secret, $shortcode, $msisdn, $amount, $billrefnumber){
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->shortcode = $shortcode;
		$this->msisdn = $msisdn;
		$this->amount = $amount;
		$this->billrefnumber = $billrefnumber;
	}
	function CreateToken(){
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($this->consumer_key.':'.$this->consumer_secret)));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $res = curl_exec($curl);
        if ( array_key_exists('access_token', json_decode($res, true)) ){
        	return json_decode($res)->access_token;
        }else{
        	return null;
        }
	}
	function RegisterUrl(){
	  $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'Content-Type:application/json',
          'Authorization:Bearer ' . $this->CreateToken())
          ); 
      $c_data = [
        'ShortCode'=>$this->shortcode,
        'ResponseType'=>'Completed',
        'ConfirmationURL'=>'https://patahapa.com/dd/test/confirmation.php',
        'ValidationURL'=>'https://patahapa.com/dd/test/validation.php'
      ];
      
      $data_string = json_encode($c_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    //   print($data_string);
      $curl_response = curl_exec($curl);
      return $curl_response;
	}
	function Simulate(){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->CreateToken()));
        $curl_post_data = array(
            'ShortCode' => $this->shortcode,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $this->amount,
            'Msisdn' => $this->msisdn,
            'BillRefNumber' => $this->billrefnumber
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $res = curl_exec($curl);
        return $res;
	}
}
// 2. Initialize
$mpesa = new C2B(
	'cY3vCLgquSYLtEQN9d9JP36MTA5DGfgF',
	'AGx2zCE89mqeWkxK',
	'600000',
	'254708374149',
	10,
	'10000216'
);
// 3. Use
$mpesa->CreateToken();
$mpesa->RegisterUrl();
$mpesa->Simulate();














// $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
// $curl = curl_init();
// $credentials = base64_encode('cY3vCLgquSYLtEQN9d9JP36MTA5DGfgF:AGx2zCE89mqeWkxK');

// curl_setopt_array(
//     $curl,
//     [
//         CURLOPT_URL => $url,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => "",
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 30,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => "GET",
//         CURLOPT_SSL_VERIFYHOST => 0,
//         CURLOPT_SSL_VERIFYPEER => 0,
//         CURLOPT_HTTPHEADER => array('Authorization: Basic ' . $credentials),
//     ]
// );
// $curl_response = curl_exec($curl);
// $err = curl_error($curl);
// curl_close($curl);
// $finalRes = json_decode($curl_response, true);
// $accessToken = '';
// if ($err) {
//     return false;
// } else {
//     $accessToken = $finalRes['access_token'];
// }

// echo "Access Token";
// echo $accessToken;
// echo '<br><br><br><br>';
// //$accessToken = '28HkGfw6cex75FHVnTZO1AbidRl4';

// if ($accessToken != '') {


//     $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

//     $ShortCode = '601426';

//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_URL, $url);
//     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:Bearer $accessToken"));


//     $curl_post_data = array(
//         'ShortCode' => $ShortCode,
//         'ResponseType' => 'Completed',
//         'ConfirmationURL' => 'https://meetanshi.in/m1d1/mpes/confirmation.php',
//         'ValidationURL' => 'https://meetanshi.in/m1d1/mpes/validation.php'
//     );

//     $data_string = json_encode($curl_post_data);

//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl, CURLOPT_POST, true);
//     curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

//     $curl_response = curl_exec($curl);
//     echo "Register Res";
//     echo '<pre>';
//     print_r($curl_response);
//     echo '</pre>';


//     $CommandID = 'CustomerPayBillOnline';
//     $Amount = '10';
//     $Msisdn = '254708374149';
//     $BillRefNumber = '10000216';

//     $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_URL, $url);
//     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:Bearer $accessToken"));

//     $curl_post_data = array(
//         'ShortCode' => $ShortCode,
//         'CommandID' => 'CustomerPayBillOnline',
//         'Amount' => $Amount,
//         'Msisdn' => $Msisdn,
//         'BillRefNumber' => $BillRefNumber
//     );

//     $data_string = json_encode($curl_post_data);

//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl, CURLOPT_POST, true);
//     curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

//     $curl_response = curl_exec($curl);

//     echo '<pre>';
//     echo 'Final responce is <br>';

//     print_r($curl_response);
//     echo '</pre>';
// }
