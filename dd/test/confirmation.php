<?php
$allData = file_get_contents('php://input');
file_put_contents("allData.log", $allData, FILE_APPEND | LOCK_EX);
// require '../app/Mage.php';
// Mage::app();
// Mage::log('confirmation file is call',null,'confirmation.log');
// $allData = $_POST;
// Mage::log($allData,null,'confirmation.log');
// $allData = $_GET;
// Mage::log($allData,null,'confirmation.log');
?>