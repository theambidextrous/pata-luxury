<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once '../../lib/Pay.php';
Pay::MPaymentCompleteNotifyPush();
print '<h1>HTTP:200 </h1>//push notifications<br>';
print '//ISWT API notifications playground';

?>