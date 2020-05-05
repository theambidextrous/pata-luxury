<?php
$allData = file_get_contents('php://input');
file_put_contents("allData.valid.log", $allData, FILE_APPEND | LOCK_EX);
// require '../app/Mage.php';
// Mage::app();
// Mage::log('validation file is call',null,'validation.log');
// $allData = $_POST;
// Mage::log($allData,null,'validation.log');
// $allData = $_GET;
// Mage::log($allData,null,'validation.log');
?>