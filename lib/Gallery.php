<?php
/**
 * @filename: Gallery.php
 * @role: gallery object
 * @author: avatar
 * @license : Proriatery
 */
class Gallery{
    private $Connection;
    private $GalleryId;
    private $GalleryType;
    private $GalleryPath;
    private $GalleryProduct;
    private $GalleryStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $GalleryId = null, $GalleryType = null, $GalleryPath = null, $GalleryProduct = null, $GalleryStatus = null){
        $this->Connection = $Connection;
        $this->GalleryId = $GalleryId;
        $this->GalleryType = $GalleryType;
        $this->GalleryPath = $GalleryPath;
        $this->GalleryProduct = $GalleryProduct;
        $this->GalleryStatus = $GalleryStatus;
    }
    function ValidateFields(){
        if(empty($this->GalleryId)){
            throw new Exception("Gallery ID Field Is Blank! ");
            return false;
        }
        if(empty($this->GalleryType)){
            throw new Exception("Gallery Type Field Is Blank! ");
            return false;
        }
        if(empty($this->GalleryPath)){
            throw new Exception("Gallery Path Field Is Blank! ");
            return false;
        }
        if(empty($this->GalleryProduct)){
            throw new Exception("Gallery Product Field Is Blank! ");
            return false;
        }
        if(empty($this->GalleryStatus)){
            throw new Exception("Gallery Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_items_gallery`(`GalleryId`, `GalleryType`, `GalleryPath`, `GalleryProduct`, `GalleryStatus`) VALUES(:a,:b,:c,:d,:e)");
            $statement->bindParam(':a', $this->GalleryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->GalleryType, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->GalleryPath, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->GalleryProduct, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->GalleryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create gallery Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_items_gallery` SET `GalleryType`=:b,`GalleryPath`=:c,`GalleryProduct`=:d,`GalleryStatus`=:e WHERE `GalleryId`=:a");
            $statement->bindParam(':a', $this->GalleryId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->GalleryType, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->GalleryPath, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->GalleryProduct, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->GalleryStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update gallery Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_gallery` WHERE `GalleryId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get gallery Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByProduct($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_gallery` WHERE `GalleryStatus`= '1' AND `GalleryProduct` = :a  ORDER BY GallerySequence DESC");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get gallery Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindByTypeProduct($id, $type){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_gallery` WHERE `GalleryStatus`= '1' AND `GalleryProduct` = :a  AND `GalleryType` = :b ORDER BY GallerySequence DESC LIMIT 1");
        $statement->execute([ ':a' => $id, ':b' => $type ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get gallery Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAllByTypeProduct($id, $type){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_gallery` WHERE `GalleryStatus`= '1' AND `GalleryProduct` = :a  AND `GalleryType` = :b ORDER BY GallerySequence DESC");
        $statement->execute([ ':a' => $id, ':b' => $type ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get gallery Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindForCarosell($product_id){
        $util = new Util();
        $b = '5005';
        $t = '5003';
        $b_arr = $this->FindAllByTypeProduct($product_id, $b);
        $t_arr = $this->FindAllByTypeProduct($product_id, $t);
        foreach( $b_arr as $ba ){
            $banners[] = $ba['GalleryPath'];
        }
        foreach( $t_arr as $ta ){
            $thumbs[] = $ta['GalleryPath'];
        }
        $rtn = array_combine($banners, $thumbs);
        return $rtn;
    }
    function FindByType($type){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_items_gallery` WHERE `GalleryStatus`= '1' AND `GalleryType` = :a ORDER BY GallerySequence DESC");
        $statement->execute([ ':a' => $type ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get gallery Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function Delete($id){
        $util = new Util();
        $statement = $this->Connection->prepare("UPDATE `p_items_gallery` SET `GalleryStatus`= '00' WHERE `GalleryId` = :a");
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