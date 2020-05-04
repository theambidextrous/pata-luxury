<?php
/**
 * @filename: Stock.php
 * @role: stock object
 * @author: avatar
 * @license : Proriatery
 */
class Stock{
    private $Connection;
    private $StockId;
    private $StockProduct;
    private $Stock;
    private $StockWarnLevel;
    private $StockOutOfStockLevel;
    private $StockStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $StockId = null, $StockProduct = null, $Stock = null, $StockWarnLevel = null, $StockOutOfStockLevel = null, $StockStatus = null){
        $this->Connection = $Connection;
        $this->StockId = $StockId;
        $this->StockProduct = $StockProduct;
        $this->Stock = $Stock;
        $this->StockWarnLevel = $StockWarnLevel;
        $this->StockOutOfStockLevel = $StockOutOfStockLevel;
        $this->StockStatus = $StockStatus;
    }
    function ValidateFields(){
        if(empty($this->StockId)){
            throw new Exception("Stock ID Field Is Blank! ");
            return false;
        }
        if(empty($this->StockProduct)){
            throw new Exception("Stock Product Field Is Blank! ");
            return false;
        }
        if(empty($this->Stock)){
            throw new Exception("Stock Value Field Is Blank! ");
            return false;
        }
        if(empty($this->StockWarnLevel)){
            throw new Exception("Stock warn level Field Is Blank! ");
            return false;
        }
        if($this->StockOutOfStockLevel == ''){
            throw new Exception("Stock Outofstock Field Is Blank! ");
            return false;
        }
        if(empty($this->StockStatus)){
            throw new Exception("Stock Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_stock`(`StockId`, `StockProduct`, `Stock`, `StockWarnLevel`, `StockOutOfStockLevel`, `StockStatus`) VALUES(:a,:b,:c,:d,:e,:f)");
            $statement->bindParam(':a', $this->StockId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->StockProduct, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Stock, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->StockWarnLevel, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->StockOutOfStockLevel, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->StockStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Stock Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_stock` SET `StockProduct`=:b,`Stock`=:c,`StockWarnLevel`=:d,`StockOutOfStockLevel`=:e,`StockStatus`=:f WHERE `StockId`=:a");
            $statement->bindParam(':a', $this->StockId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->StockProduct, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Stock, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->StockWarnLevel, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->StockOutOfStockLevel, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->StockStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Stock Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_stock` WHERE `StockId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_stock` WHERE `StockStatus`= '1'");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByProduct($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_stock` WHERE `StockStatus`= '1' AND `StockProduct` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items_stock` SET `StockStatus`= '00' WHERE `StockId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete item. Contact Admins');
            return false;
        }
        return true;
    }
    function Disable($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items_stock` SET `StockStatus`= '0' WHERE `StockId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not disable item. Contact Admins');
            return false;
        }
        return true;
    }
}
?>