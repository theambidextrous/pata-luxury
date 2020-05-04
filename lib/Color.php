<?php
/**
 * @filename: Color.php
 * @role: color object
 * @author: avatar
 * @license : Proriatery
 */
class Color{
    private $Connection;
    private $ColorId;
    private $ColorName;
    private $ColorDescription;
    private $ColorStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $ColorId = null, $ColorName = null, $ColorDescription = null, $ColorStatus = null){
        $this->Connection = $Connection;
        $this->ColorId = $ColorId;
        $this->ColorName = $ColorName;
        $this->ColorDescription = $ColorDescription;
        $this->ColorStatus = $ColorStatus;
    }
    function ValidateFields(){
        if(empty($this->ColorId)){
            throw new Exception("Color ID Field Is Blank! ");
            return false;
        }
        if(empty($this->ColorName)){
            throw new Exception("Color Name Field Is Blank! ");
            return false;
        }
        if(empty($this->ColorDescription)){
            throw new Exception("Color Description Field Is Blank! ");
            return false;
        }
        if(empty($this->ColorStatus)){
            throw new Exception("Color Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_color`(`ColorId`, `ColorName`, `ColorDescription`, `ColorStatus`) VALUES (:a,:b,:c,:d)");
            $statement->bindParam(':a', $this->ColorId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ColorName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ColorDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ColorStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Color Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_color` SET `ColorName`=:b,`ColorDescription`=:c,`ColorStatus`=:d WHERE `ColorId`=:a");
            $statement->bindParam(':a', $this->ColorId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->ColorName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->ColorDescription, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->ColorStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update color Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_color` WHERE `ColorStatus` = '1' ORDER BY ColorSequence DESC");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get color Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_color` WHERE `ColorId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get color Item. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items_color` SET `ColorStatus`= '00' WHERE `ColorId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete item. Contact Admins');
            return false;
        }
        return true;
    }
}
?>