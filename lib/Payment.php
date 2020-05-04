<?php
use Utils\RandomStringGenerator;
class skyler {
   function testjson($data){
        return json_decode($data, TRUE);
    }
    //MISCL FUNCS
   function credentials(){
        // 1.Terminal ID PATA0001
        // 2. Merchant Code PATAKE0001
        // 3. Merchant ID PATAKE0001
        //""
        return $rtn = [
            //'id' => 'IKIAAC9B8A4F20CAC3F1863BC41ED359F42EE46DE3B9',
            'id' => 'IKIAF8236D934A8C9403A69C61E98985241066284662',
            //'secret' => 'ich1xOIf2bKqLbHkRoiJNadeBndFliD64liMJIFRvrg=',
            'secret' => 'LYGrYEKHmF68GdgP35NuTUI3S28O2E0weqgsrSdVt6g=',
            'merchantid' => 'PATAKE0001',
            'merchantcode' => 'PATAKE0001',
            'terminalid' => 'PATA0001'
        ];
    }
   function apiContext(){
        return $rtn  = [
            'req' => 'https://testids.interswitch.co.ke:9080/',
            'auth' => 'https://sandbox.interswitchng.com/',
            // 'btn' => 'https://testmerchant.interswitch-ke.com/webpay/button/functions.js',
            // 'btn2' => 'https://testmerchant.interswitch-ke.com/webpay/button/functions.js',
            'btn' => 'https://merchant.interswitch-ke.com/webpay/button/functions.js',
            'btn2' => 'https://merchant.interswitch-ke.com/webpay/button/functions.js',
            '3d' => 'https://testids.interswitch.co.ke:9080/'
        ];
    }
   function apiV(){
        return $rtn  = [
            'v1' => 'api/v1/',
            'v2' => 'api/v1/'
        ];
    }
   function getImgs(){
        return [
            "p_icon" => "https://pata.shopping/auth/icons/p_icon.png"
        ];
    }
   function cleanAmt($amt, $action = 0){
        if( $action == 0 ){
            return $amt * 100;
        }
        return $amt / 100;
    }
   function httpTokenOA($e = 'passport/oauth/token?scope=profile&grant_type=client_credentials'){
        $id = self::credentials()['id'];
        $sec = self::credentials()['secret'];
        $headers = [];
        $headers[] = 'Authorization: Basic '.base64_encode($id.':'.$sec);
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::apiContext()['auth'].$e);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        return json_decode($reply);
    }
   function httpTokenISW(){
        return base64_encode(self::credentials()['id']);
    }
   function authSignature($resource, $method = "POST"){
        $string = $method.$resource.time().self::authNonce().self::credentials()['id'].self::credentials()['secret'];
        return base64_encode(hash('sha512', $string));
    }
   function authNonce($tokenLength=60, $chunk = 12){
        $generator = new RandomStringGenerator;
        $token = $generator->generate($tokenLength);
        return $rtn = rtrim(strtoupper(chunk_split($token, $chunk, '-')), '- ');
    }
   function pre($d){
        print '<pre>';
        print_r($d);
        print '</pre>';
    }
   function amtToPay($currency, $kes, $usd){
        if( $currency == 'KES'){
            return $kes;
        }
        return $usd;
    }
   function getAuthType($t = 0 ){
        if($t == 0 ){
            return $res = 'InterswitchAuth '. self::httpTokenISW();
        }else{
            return $res = 'Bearer '. self::httpTokenOA()->access_token;
        }
    }
   function authHeaders($resource, $method){
        $headers = [];
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Signature: ' . self::authSignature($resource, $method);
        $headers[] = 'Timestamp: ' . time();
        return $headers;
    }
   function httpPingRes($http_ping){
        $http_status_code = $http_ping['http_code'];
        switch($http_status_code){
            case '200':
                return $rtn = [
                    "HTTP" => "Ok",
                    "MSG" => 1
                ];
            break;
            case '403':
                return $rtn = [
                    "ERR" => "E24",
                    "MSG" => "Invalid authentication credentials: Timestamp out of window"
                ];
            break;
            case '500':
                return $rtn = [
                    "ERR" => "1",
                    "MSG" => "Invalid Request Body"
                ];
            break;
            case '404':
                return $rtn = [
                    "ERR" => "1",
                    "MSG" => "Invalid Request Body"
                ];
            break;
            default:
            print_r($http_ping);
            break;
        }
    }
    //API calls
   function StatusCheck($e = 'merchant/status'){
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('status', json_decode($reply, true)) && json_decode($reply)->status == 0){
            return true;
        }
        return false;
    }
    //4. Payment Transactions
    //Merchant Paybill Request (Sent from ThirdParty)
   function MPaybillRequest($postfields, $e = 'merchant/transact/bills'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "provider" => $postfields[1],
            "merchantId" => $postfields[2],
            "orderId" => $postfields[3],
            "phone" => $postfields[4],
            "transactionRef" => $postfields[5],
            "terminalType" => $postfields[6],
            "terminalId" => $postfields[7],
            "paymentItem" => $postfields[8],
            "isLog" => 0,
            "currency" => $postfields[9],
            "narration" => $postfields[10],
            "domain" => "ISWKE"
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //Merchant Purchase Request
   function MPurchaseRequest($postfields, $e = 'merchant/transact/purchases'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "provider" => $postfields[1],
            "merchantId" => $postfields[2],
            "orderId" => $postfields[3],
            "phone" => $postfields[4],
            "transactionRef" => $postfields[5],
            "terminalType" => $postfields[6],
            "terminalId" => $postfields[7],
            "paymentItem" => $postfields[8],
            "isLog" => 0,
            "currency" => $postfields[9],
            "narration" => $postfields[10],
            "domain" => "ISWKE"
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //Merchant Cashout Request
   function MCashoutRequest($postfields, $e = 'merchant/transact/cashouts'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "provider" => $postfields[1],
            "merchantId" => $postfields[2],
            "orderId" => $postfields[3],
            "phone" => $postfields[4],
            "transactionRef" => $postfields[5],
            "terminalType" => $postfields[6],
            "terminalId" => $postfields[7],
            "paymentItem" => $postfields[8],
            "callBackUrl" => $postfields[9],
            "isLog" => 0,
            "currency" => $postfields[10],
            "narration" => $postfields[11],
            "domain" => "ISWKE"
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //Merchant CallBack Request from iswt
   function MCallBackRequest(){
        $callBackRes = file_get_contents("php://input");
        return json_decode($callBackRes)->responseCode;
    }
    //4.8 Merchant Paycode Request (Sent from ThirdParty)
   function MPaycodeRequest($postfields, $e = 'merchant/transact/paycodes'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "provider" => $postfields[1],
            "merchantId" => $postfields[2],
            "orderId" => $postfields[3],
            "token" => $postfields[4],
            "phone" => $postfields[5],
            "transactionRef" => $postfields[6],
            "terminalType" => $postfields[7],
            "terminalId" => $postfields[8],
            "paymentItem" => "PYC",
            "callBackUrl" => $postfields[9],
            "isLog" => 0,
            "currency" => $postfields[10],
            "narration" => $postfields[11],
            "domain" => "ISWKE"
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //4.10 Merchant Card Request (Sent from ThirdParty)
   function MCardRequest($postfields, $e = 'merchant/transact/cards'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "orderId" => $postfields[1],
            "transactionRef" => $postfields[2],
            "terminalType" => $postfields[3],
            "terminalId" => $postfields[4],
            "paymentItem" => "CRD",
            "provider" => $postfields[5],
            "merchantId" => $postfields[6],
            "authData" => self::hashPan($postfields[7]),//pan,cvv,expiry,token=bool
            "customerInfor" => self::StringfyData($postfields[8], '|'),
            "currency" => $postfields[9],
            "country" => $postfields[10],
            "city" => $postfields[11],
            "narration" => $postfields[12],
            "domain" => "ISWKE"

        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //4.12 Merchant Card Token Request (Sent from ThirdParty
   function MCardTokenRequest($postfields, $e = 'merchant/transact/tokens'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "orderId" => $postfields[1],
            "transactionRef" => $postfields[2],
            "terminalType" => $postfields[3],
            "terminalId" => $postfields[4],
            "paymentItem" => "CRD",
            "provider" => $postfields[5],
            "merchantId" => $postfields[6],
            "authData" => self::hashPan($postfields[7]),//token,cvv,expiry
            "customerInfor" => self::StringfyData($postfields[8], '|'),//customer
            "currency" => $postfields[9],
            "country" => $postfields[10],
            "city" => $postfields[11],
            "narration" => $postfields[12],
            "domain" => "ISWKE"

        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    ///4.14 Merchant Card Preauth Request (Sent from ThirdParty)
   function MCardPreauthRequest(){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "orderId" => $postfields[1],
            "transactionRef" => $postfields[2],
            "terminalType" => $postfields[3],
            "terminalId" => $postfields[4],
            "paymentItem" => "CRD",
            "provider" => $postfields[5],
            "merchantId" => $postfields[6],
            "authData" => self::hashPan($postfields[7]),//pan,cvv,expiry,token=bool
            "customerInfor" => self::StringfyData($postfields[8], '|'),//customer
            "currency" => $postfields[9],
            "country" => $postfields[10],
            "city" => $postfields[11],
            "narration" => $postfields[12],
            "domain" => "ISWKE",
            "preauth" => 1

        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //4.16 Merchant Card Preauth Complete Request (Sent from ThirdParty)
   function MCardPreauthCompleteRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/complete/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //4.18 Merchant Card Preauth Reverse Request (Sent from ThirdParty)
   function MCardPreauthReverseRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/reverse/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //4.20 Merchant Refund Request (Sent from ThirdParty)
   function MRefundRequest($transactionRef, $amount, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/refund/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        $headers[] = 'amount: ' . $amount;
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //5 Payment Transaction Query
    //A payment transaction query message is a request to retrieve the status of a previous payment transaction request that may have timed out. Please request the context URL from Interswitch
    //5.1 Payment paybill transaction Query Request (Sent from ThirdParty)
   function PPtransactionQueryRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/bills/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //5.3 Merchant purchase transaction Query Request (Sent from ThirdParty)
   function MPtransactionQueryRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/purchases/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //5.5 Merchant cashout transaction Query Request (Sent from ThirdParty)
   function MCtransactionQueryRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/cashouts/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //5.7 Merchant paycode transaction Query Request (Sent from ThirdParty)
   function MPYtransactionQueryRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/paycodes/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //5.9 Merchant card transaction Query Request (Sent from ThirdParty)
   function MCRDtransactionQueryRequest($transactionRef, $merchant, $provider){
        $headers = [];
        $resource = self::apiContext()['req'].self::apiV()['v1'].'merchant/cards/transactions/'.$transactionRef.' HTTP/1.1';
        $headers[] = 'Host: '. self::getHost();
        $headers[] = 'Authorization: ' . self::getAuthType(0);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Signature: ' . self::authSignature($resource, 'GET');
        $headers[] = 'SignatureMethod: SHA512';
        $headers[] = 'Timestamp: ' . time();
        $headers[] = 'Nonce: ' . self::authNonce();
        $headers[] = 'merchantId: ' . $merchant;
        $headers[] = 'provider: ' . $provider;
        $headers[] = 'domain: ISWKE';
        //
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }
    //6 Merchant Payment Complete Notify Request (Sent to ThirdParty) The following describes the important element required to be sent for a merchant complete payment notify request.
   function MPaymentCompleteNotifyPush(){
        $callBackRes = file_get_contents("php://input");
        //$callBackRes = json_decode($callBackRes);
        if(file_put_contents('log.txt', $callBackRes.PHP_EOL.'===================================================================
        ==========================='.PHP_EOL, FILE_APPEND)){
            self::CompleteMPayment($callBackRes);
            return true;
        }
        return false;
    }
   function getOrderShipAddrr($orderid){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `delivery_address` FROM `trans_orders` WHERE `order_id`=:order");
            $stmt->execute([':order'=>$orderid]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result[0]['delivery_address'];
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function getOrderItemsMeta($orderid){
        $resp = [];
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `order_id`, GROUP_CONCAT(`order_item_id`) as item_id, GROUP_CONCAT(`order_item`) as item, GROUP_CONCAT(`order_qty`) as qty, `order_user`, `order_vendor`, `delivery_address` FROM `trans_orders` WHERE `order_id`=:order GROUP BY `order_vendor`");
            $stmt->execute([':order'=>$orderid]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ( $result = $stmt->fetchAll() )
            {
                array_push($resp, $result);
            }
            return $rtn = !empty($resp[0])?$resp[0]:[];
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function getTransType($orderid){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `trans_Type`, `trans_user` FROM `transactions_acc` WHERE `trans_order` = :orderid");
            $stmt->execute([':orderid'=>$orderid]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $res = !empty($result[0])?$result[0]:[];
        }
        catch(PDOException $pe){
            return false;
            //self::writeError($pe->getMessage());
        }
    }
   function updateOrderToComplete($data){
        return true; //remove this when live
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("UPDATE `transactions_acc` SET `iswt_token`=:token,`trans_status` = 0,`trans_provider`=:provider,`trans_paid_amt`=:paid WHERE `trans_order` = :order AND `trans_ref` = :ref");
            $stmt->execute([':token'=>$data[4], ':provider'=>$data[1], ':paid'=>$data[2], ':order'=>$data[0],':ref'=>$data[3] ]);
            if( $stmt->rowCount() > 0 )
            {
                return true;
            }   
            return false;        
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function updateUserVerificationStatus($userid){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("UPDATE `members` SET `verification_status` = 1 WHERE `id` = :userid");
            $stmt->execute([':userid'=>$userid]);
            if( $stmt->rowCount() > 0 ){
                return true;
            }
            return false;
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function updateOrderEntries($orderid, $user){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("UPDATE `trans_orders` SET `order_pstatus`= 2 WHERE `order_id` = :orderid AND `order_user` = :user");
            $stmt->execute([':orderid'=>$orderid, ':user'=>$user]);
            if( $stmt->rowCount() > 0 ){
                return true;
            }
            return false;
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function pickFrom($product){
        $vendor_address_data = getProductVendorData($product);
        $api = new maps(EPI_CENTER, $vendor_address_data['store_location'], MAPS_KEY);
        $api_res = $api->extractLatLng();
        $lat = $api_res[0];
        $lng = $api_res[1];
        return [
            $vendor_address_data['store_location'],
            $lat,
            $lng,
            $vendor_address_data['store_location']
        ];

    }
   function sendySender(){
        return [
            SYS_NAME,
            S_CONTACT,
            S_MAIL,
            'Deliver to customer, shipping already paid for. Please take note of batch items'
        ];
    }
   function getEstPackSize($data){
        $prodid = explode(',',$data['item_id']);
        $prods = explode(',',$data['item']);
        $prod_qty = explode(',',$data['qty']);
        $item_name_string = [];
        $loop = 0;
        foreach($prodid as $pid ):
            array_push($item_name_string, $prods[$loop].'('.$prod_qty[$loop].')');
        endforeach;
        $item_name_string = implode(', ', $item_name_string);
        $productid = $prodid[0];
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `weight`, `height`, `width`, `length` FROM `prod_params` WHERE `id` = :id");
            $stmt->execute([':id'=>$productid]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return [
                $result[0]['weight'],
                $result[0]['height'],
                $result[0]['width'],
                $result[0]['length'],
                $item_name_string
            ];
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function sendyRecipient($userid){
        $user = userAccounts::getMemberDetails($userid)[0];
        return [
            $user['name'],
            $user['phone'],
            $user['email'],
            'Call me if really necessary, Thanks'
        ];
    }
   function deliverTo($order){
        $address = self::getOrderShipAddrr($order);
        $mapi = new maps(EPI_CENTER, $address, MAPS_KEY);
        $api_res = $mapi->extractLatLng();
        $lat = $api_res[0];
        $lng = $api_res[1];
        return [
            $address,
            $lat,
            $lng,
            $address
        ];

    }
   function CompleteMPayment($jsonp){
        if ( json_decode($jsonp)->status == 0 )
        {
            $data_arr = json_decode( $jsonp, true);
            $transaction_data = [
                $data_arr['orderId'],
                $data_arr['paymentItem'].'~'.$data_arr['provider'],
                $data_arr['transactionAmount']/100,
                $data_arr['transactionRef'],
                $data_arr['token'],
            ];
            $trans_type_data = self::getTransType($data_arr['orderId']);
            $customer_data = userAccounts::getMemberDetails($trans_type_data['trans_user']);
            $order_data = userAccounts::getuserOrders($trans_type_data['trans_user'],1,$data_arr['orderId'],'~')[0];
            // self::pre($order_data);
            // self::pre($customer_data);
            // self::pre($data_arr);
            $data_string = [$customer_data, $order_data, $data_arr];
            $ebody = self::receiptMailTemp($data_string);
            //return 0;
            if( self::updateOrderToComplete($transaction_data) )
            {
                if( $trans_type_data['trans_Type'] == 1 ) {
                    //update user membership
                    self::updateUserVerificationStatus($trans_type_data['trans_user']);
                }else{
                    //update order entries in trans_orders
                    if( self::updateOrderEntries($data_arr['orderId'], $trans_type_data['trans_user']) )
                    {
                        //post to sendy
                        $sendy_items_meta = self::getOrderItemsMeta($data_arr['orderId']);
                        foreach( $sendy_items_meta as $oim ):
                            $single_sendy_items = explode(',',$oim['item_id'])[0];
                            if(isShipperSendy($single_sendy_items)){
                                $from_data = self::pickFrom($single_sendy_items);
                                $deliver_to = self::deliverTo($oim['order_id']);
                                $recipient = self::sendyRecipient($oim['order_user']);
                                $sender = self::sendySender();
                                $note = 'Kindly keep note of batch items, some locations might have more than 1 item to be picked up, Note that all batch items are picked from single location';
                                $package_size = self::getEstPackSize($oim);
                                $request_token_id = $oim['order_id'];
                                //
                                $sendy_postfields = [
                                    $from_data,
                                    $deliver_to,
                                    $recipient,
                                    $sender,
                                    $note,
                                    $package_size,
                                    $request_token_id
                                ];
                                $sendyResp = requestDelivery($sendy_postfields);
                                self::sendyLog($sendyResp);
                                if(is_array($sendyResp) && $sendyResp['data']['amount'] > 0 ){
                                    $data_a = [
                                        $sendyResp['request_token_id'],
                                        $sendyResp['data']['order_no'],
                                        $sendyResp['data']['amount'],
                                        $sendyResp['data']['distance'],
                                        $sendyResp['data']['eta']
                                    ];
                                    self::recordSendyFeedback($data_a);
                                }else{
                                    print_r($sendyResp);
                                }
                            }
                        endforeach;
                        //email/txt user
                        $success = 'Dear '.$customer_data[0]['name'].', payment has been received and your order information has been emailed to you.';
                        sms::sendSingleSMS($customer_data[0]['phone'], $success);
                        userAccounts::umail(['Payment & Order Confirmation', $customer_data[0]['email'], $customer_data[0]['name'], $ebody]);


                    }

                }
            }
            //self::pre($data_arr);
        }
        return false;
    }
   function sendyLog($error){
        $error = json_encode($error).PHP_EOL.PHP_EOL;
        file_put_contents('sendylogs.txt', $error, FILE_APPEND | LOCK_EX);
    }
    //IMPORTANT 3D card preauth #####################################################################
    //##############################################################
    //Merchant Card 3D Preauth Request
   function MCard3DPreauthRequest($postfields, $e = 'webpay/js/initdev.js'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "orderId" => $postfields[1],
            "transactionRef" => $postfields[2],
            "terminalType" => $postfields[3],
            "terminalId" => $postfields[4],
            "paymentItem" => "CRD",
            "provider" => $postfields[5],
            "merchantId" => $postfields[6],
            "authData" => self::hashPan($postfields[7]),//pan,cvv,expiry,token=bool
            "customerInfor" => self::StringfyData($postfields[8], '|'),//customer
            "currency" => $postfields[9],
            "country" => $postfields[10],
            "city" => $postfields[11],
            "narration" => $postfields[12],
            "domain" => "ISWKE",
            "preauth" => 0,
            "paca" => 1

        ]);
        $resource = self::apiContext()['3d'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        $pingRes = self::httpPingRes(curl_getinfo($curl));
        if(array_key_exists('ERR', $pingRes)){
            print_r($reply);
            return false;
        }
        return json_decode($reply);
    }


    //IMPORTANT Button service ################################################################
    //##############################################################
    //Merchant Card 3D Preauth Request - button
   function btnTest(){
        // <P>All options</P><br />
		// <a data-isw-payment-button="" data-isw-ref="MW4Hmp7p4">
        // <script data-isw-amount="100" data-isw-channel="WEB" data-isw-currencyCode="KES" data-isw-customerInfor="1002|kelvin|mwangi| kelvin.mwangi@interswitchgroup.com |0714171282|NBI|KE|00200|wstlnds|NBI" data-isw-dateOfPayment="2016-09-05T10:20:26" data-isw-domain="ISWKE" data-isw-fee="0" data-isw-merchantCode="ISWKEN0001" data-isw-narration="Payment" data-isw-orderId="OID123453" data-isw-preauth="0" data-isw-redirectUrl="https://testids.interswitch.co.ke:7784" data-isw-terminalId="3TLP0001" data-isw-transactionReference="1234890" src="https://testmerchant.interswitch-ke.com/webpay/button/functions.js" type="text/javascript">
        // </script>
        // </a>
        print '
            <a data-isw-payment-button="" data-isw-ref="'.self::credentials()['merchantid'].'">
                <script 
                data-isw-amount="'.$postfields[2].'" 
                data-isw-channel="WEB" 
                data-isw-currencyCode="'.$postfields[1].'" 
                data-isw-customerInfor="'.$postfields[4].'" 
                data-isw-dateOfPayment="'.date('Y-m-d', strtotime('Now')).'T'.date('h:i:s', strtotime('Now')).'" 
                data-isw-domain="ISWKE" 
                data-isw-fee="0" 
                data-isw-merchantCode="'.self::credentials()['merchantcode'].'" 
                data-isw-narration="'.$postfields[6].'" 
                data-isw-orderId="'.$postfields[3].'" 
                data-isw-preauth="0" 
                data-isw-redirectUrl="'.$postfields[5].'" 
                data-isw-terminalId="'.self::credentials()['terminalid'].'" 
                data-isw-transactionReference="'.$postfields[0].'" 
                src="'.self::apiContext()['btn'].'" type="text/javascript">
                </script>
            </a>';
    }
   function PayServiceButton($postfields){
        return '
        <a data-isw-payment-button="" data-isw-ref="'.self::credentials()['merchantid'].'">
                <script 
                data-isw-amount="'.$postfields[2].'" 
                data-isw-channel="WEB" 
                data-isw-currencyCode="'.$postfields[1].'" 
                data-isw-customerInfor="'.$postfields[4].'" 
                data-isw-dateOfPayment="'.date('Y-m-d', strtotime('Now')).'T'.date('h:i:s', strtotime('Now')).'" 
                data-isw-domain="ISWKE" 
                data-isw-fee="0" 
                data-isw-merchantCode="'.self::credentials()['merchantcode'].'" 
                data-isw-narration="'.$postfields[6].'" 
                data-isw-orderId="'.$postfields[3].'" 
                data-isw-preauth="0" 
                data-isw-redirectUrl="'.$postfields[5].'" 
                data-isw-terminalId="'.self::credentials()['terminalid'].'" 
                data-isw-transactionReference="'.$postfields[0].'" 
                src="'.self::apiContext()['btn'].'" type="text/javascript">
                </script>
            </a>';
    }
    //
   function getPaymentConstants(){
        return [
            "TType" => "WEB"
        ];
    }
    ###########################################################################################################
    //utility functions
    #########################
   function PaymentItems(){
        return [
            "CRD" => "Card transactions",
            "MMO" => "Mobile money transactions",
            "PYC" => "Paycode transactions",
            "BNK" => "Bank transactions",
            "QRC" => "QR code transactions"
        ];
    }
   function getProductShipper($item){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `shipping`, `vendorid` FROM `products` WHERE `id` = :item");
            $stmt->execute([':item'=>$item]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $res = !empty($result[0])?$result[0]:[];
            if($res['shipping'] == 0){
                return [0, 'Sendy Ltd'];
            }elseif( $res['shipping'] == 2 ){
                return [2, getVendorName($res['vendorid'])];
            }else{
                return [3, 'No shipping'];
            }
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function recordSendyFeedback($data){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("INSERT INTO `sendy_costs`(`order_id`, `order_no`, `amount`, `distance`, `eta`) VALUES (:order_id,:order_no,:amount,:distance,:eta)");
            $stmt->execute([':order_id'=>$data[0],':order_no'=>$data[1],':amount'=>$data[2],':distance'=>$data[3],':eta'=>$data[4]]);
            if( $stmt->rowCount() > 0 ){
                return true;
            }
            return false;
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function getProviderIcon($provider){
        try{
            $io = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER,DB_PASS);
            $io->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $io->prepare("SELECT `icon` FROM `pay_providers` WHERE `paymentprovider` = :provider");
            $stmt->execute([':provider'=>$provider]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $res = !empty($result[0]['icon'])?$result[0]['icon']:'';
        }
        catch(PDOException $pe){
            return false;
        }
    }
   function PayProviders($paymentItem){
        try {
            $skyler = new PDO("mysql:host=".DB_HOST.";dbname=".DB."", DB_USER, DB_PASS);
            $skyler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $skyler->prepare("SELECT `id`, `paymentprovider`, `description` FROM `pay_providers` WHERE `isActive` = 1 AND `paymentitem` = :paymentitem");
            $stmt->execute([':paymentitem'=>$paymentItem]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $pdo_resp = [];
            while($res = $stmt->fetchAll()){
                array_push($pdo_resp, $res);
            }
            if(is_array($pdo_resp[0])){
                return $pdo_resp[0];
            }
            return [];
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
        $skyler = null;//close conn
    }
    //WALLET APIS
   function WalletStatusCheck($e = 'wallet/status'){
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == 0){
            return true;
        }
        return false;
    }
    //createw
   function createWallet($postfields, $e = 'wallet/wallets'){
        $json_req_body = json_encode([
            "customerNumber" => $postfields[0],
            "product" => $postfields[1],
            "firstName" => $postfields[2],
            "midName" => $postfields[3],
            "mobile" => $postfields[4],
            "embossingName" => $postfields[5],
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
    //get wallet balance
   function getWalletBalance($postfields, $e = 'wallet/wallets/balance/'){
        return [0,0];
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e.$postfields[0];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
    //get wallet details by account
   function getWalletAccountNumber($postfields, $e = 'wallet/wallets/'){
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e.$postfields[0];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
    //Get wallets by customer number.
   function getWalletByCNumber($postfields, $e = 'wallet/customer/wallets/'){
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e.$postfields[0];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
   function walletTransferFunds($postfields, $e = 'wallet/wallets/transactions'){
        $json_req_body = json_encode([
            "amount" => $postfields[0],
            "beneficiaryAccount" => $postfields[1],
            "currency" => $postfields[2],
            "narration" => $postfields[3],
            "processingCode" => $postfields[4],
            "provider" => $postfields[5],
            "providerID" => $postfields[6],
            "reference" => $postfields[7],
            "senderName" => $postfields[8],
            "transactionType" => $postfields[9],
            "walletAccount" => $postfields[10]
        ]);
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'POST')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_req_body);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
    //Get check wallet transaction by reference.
   function getWalletTransByRef($postfields, $e = 'wallet/wallets/transactions/'){
        $resource = self::apiContext()['req'].self::apiV()['v1'].$e.$postfields[0];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $resource);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::authHeaders($resource, 'GET')); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($curl);
        //return curl_getinfo($curl);
        if(array_key_exists('responseCode', json_decode($reply, true)) && json_decode($reply)->responseCode == '00'){
            return json_decode($reply);
        }
        return false;
    }
    //Provider Account Lookup API
   function ProviderAccountLookup(){
        //considered undesirable due to limied coverage.
    }
}

?>