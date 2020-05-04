<?php
/**
 * @filename: Faq.php
 * @role: Faq object
 * @author: avatar
 * @license : Proriatery
 */
class Faq{
    private $Connection;
    private $Id;
    private $Question;
    private $Answer;

    function __construct($Connection = null, $Id = null, $Question = null, $Answer = null){
        $this->Connection = $Connection;
        $this->Id = $Id;
        $this->Question = $Question;
        $this->Answer = $Answer;
    }
    function ValidateFields(){
        if(empty($this->Id)){
            throw new Exception("Id Field Is Blank! ");
            return false;
        }
        if(empty($this->Question)){
            throw new Exception("Question Field Is Blank! ");
            return false;
        }
        if(empty($this->Answer)){
            throw new Exception("Answer Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `faq`(`Id`, `Question`, `Answer`) VALUES(:a,:b,:c)");
            $statement->bindParam(':a', $this->Id, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->Question, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Answer, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `faq` SET `Question`=:b,`Answer`=:c WHERE `Id`=:a");
            $statement->bindParam(':a', $this->Id, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->Question, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->Answer, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `faq` WHERE `Id` = :a");
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
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `faq`");
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
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("DELETE FROM `faq` WHERE `Id` = :a");
        $statement->execute([':a'=>$id]);
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