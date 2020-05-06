<?php
include_once '/home2/fazalbou/public_html/patashopdir/lib/Util.php';
include_once '/home2/fazalbou/public_html/patashopdir/lib/RoomOrder.php';
include_once '/home2/fazalbou/public_html/patashopdir/lib/PackageOrder.php';
include_once '/home2/fazalbou/public_html/patashopdir/lib/CarOrder.php';
include_once '/home2/fazalbou/public_html/patashopdir/lib/BladeSMS.php';
include_once '/home2/fazalbou/public_html/patashopdir/mail/autoload.php';
class Pay {
    public static function Credentials(){
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
    public static function ArchivePay($data){
        $util = new Util();
        $connection = $util->CreateConnection();
        $statement = $connection->prepare("INSERT INTO `p_payment_archives`(`OrderId`, `PaymentItem`, `Provider`, `Status`, `StatusMessage`, `TransactionAmount`, `TransactionRef`, `TransactionType`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h)");
        $data['transactionAmount'] = floor($data['transactionAmount']/100);
        $statement->bindParam(':a', $data['orderId'], PDO::PARAM_STR);
        $statement->bindParam(':b', $data['paymentItem'], PDO::PARAM_STR);
        $statement->bindParam(':c', $data['provider'], PDO::PARAM_STR);
        $statement->bindParam(':d', $data['status'], PDO::PARAM_STR);
        $statement->bindParam(':e', $data['statusMessage'], PDO::PARAM_STR);
        $statement->bindParam(':f', $data['transactionAmount'], PDO::PARAM_STR);
        $statement->bindParam(':g', $data['transactionRef'], PDO::PARAM_STR);
        $statement->bindParam(':h', $data['transactionType'], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Payment Error occured. Could not log payment. Contact Admins');
            return false;
        }
        return true;
    }
    public static function ArchivePayMobile($data){
        $util = new Util();
        $connection = $util->CreateConnection();
        $statement = $connection->prepare("INSERT INTO `p_payment_archives_mobile`(`OrderId`, `IinitiatorAccount`, `MpesaTransactionId`, `PaymentItem`, `Provider`, `Status`, `StatusMessage`, `TransactionAmount`, `TransactionRef`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i)");
        $data['transactionAmount'] = floor($data['transactionAmount']/100);
        $statement->bindParam(':a', $data['orderId'], PDO::PARAM_STR);
        $statement->bindParam(':b', $data['initiatorAccount'], PDO::PARAM_STR);
        $statement->bindParam(':c', $data['mpesaTransactionId'], PDO::PARAM_STR);
        $statement->bindParam(':d', $data['paymentItem'], PDO::PARAM_STR);
        $statement->bindParam(':e', $data['provider'], PDO::PARAM_STR);
        $statement->bindParam(':f', $data['status'], PDO::PARAM_STR);
        $statement->bindParam(':g', $data['statusMessage'], PDO::PARAM_STR);
        $statement->bindParam(':h', $data['transactionAmount'], PDO::PARAM_STR);
        $statement->bindParam(':i', $data['transactionRef'], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Payment Error occured. Could not log payment. Contact Admins');
            return false;
        }
        return true;
    }
    public static function apiContext(){
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
    /** Merchant CallBack Request from iswt */
    public static function MCallBackRequest(){
        $callBackRes = file_get_contents("php://input");
        return json_decode($callBackRes)->responseCode;
    }
    /** 6 Merchant Payment Complete Notify Request */
    public static function MPaymentCompleteNotifyPush(){
        $callBackRes = file_get_contents("php://input");
        // $callBackRes = '{
        //           "initiatorAccount": "0713666564",
        //           "mpesaTransactionId": "ODG7J9WTCL",
        //           "orderId": "CH430617",
        //           "paymentItem": "MMO",
        //           "provider": "702",
        //           "status": "0",
        //           "statusMessage": "The service request is processed successfully.",
        //           "transactionAmount": "10000",
        //           "transactionRef": "CmX7JvOSVqTIo9L"
        //         }';
        if(file_put_contents('log.txt', $callBackRes.PHP_EOL.'next>>>'.PHP_EOL, FILE_APPEND)){
            self::CompleteMPayment($callBackRes);
            return true;
        }
        return false;
    }
    public static function CompleteMPayment($jsonp){
        /** archive into logs**/
        if ( json_decode($jsonp)->paymentItem == 'MMO' ){
            self::ArchivePayMobile(json_decode($jsonp, true));
        }else{
            self::ArchivePay(json_decode($jsonp, true));
        }
        if ( json_decode($jsonp)->status == 0 )
        {
            $data_arr = json_decode( $jsonp, true);
            $order_id = $data_arr['orderId'];
            $paid_amount = floor($data_arr['transactionAmount']/100);
            $order_type = substr($order_id, 0,2);
            $util = new Util();
            $connection = $util->CreateConnection();
            switch ($order_type) {
                case 'EC':
                    # ecommerce
                    $o = new ProductOrder($connection);
                    $o->UpdatePay($order_id, $paid_amount);
                break;
                case 'CH':
                    # car hire
                    $o = new CarOrder($connection);
                    $o->UpdatePay($order_id, $paid_amount);
                break;
                case 'RM':
                    # hotels
                    $o = new RoomOrder($connection);
                    $o->UpdatePay($order_id, $paid_amount);
                break;
                case 'PK':
                    # holiday
                    $o = new PackageOrder($connection);
                    $o->UpdatePay($order_id, $paid_amount);
                break;
                default:
                    # invalid case...
                break;
            }
        }
        return false;
    }
    /** IMPORTANT Button service ####################### */
    public static function PayServiceButton($postfields){
        print '
            <a data-isw-payment-button="" data-isw-ref="'.self::Credentials()['merchantid'].'">
                <script 
                data-isw-amount="'.$postfields[2].'" 
                data-isw-channel="WEB" 
                data-isw-currencyCode="'.$postfields[1].'" 
                data-isw-customerInfor="'.$postfields[4].'" 
                data-isw-dateOfPayment="'.date('Y-m-d', strtotime('Now')).'T'.date('h:i:s', strtotime('Now')).'" 
                data-isw-domain="ISWKE" 
                data-isw-fee="0" 
                data-isw-merchantCode="'.self::Credentials()['merchantcode'].'" 
                data-isw-narration="'.$postfields[6].'" 
                data-isw-orderId="'.$postfields[3].'" 
                data-isw-preauth="0" 
                data-isw-redirectUrl="'.$postfields[5].'" 
                data-isw-terminalId="'.self::Credentials()['terminalid'].'" 
                data-isw-transactionReference="'.$postfields[0].'" 
                src="'.self::apiContext()['btn'].'" type="text/javascript">
                </script>
            </a>';
    }
}

?>