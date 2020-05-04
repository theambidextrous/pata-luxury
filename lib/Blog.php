<?php
/**
 * @filename: Blog.php
 * @role: Blog object
 * @author: avatar
 * @license : Proriatery
 */
class Blog{
    private $Connection;
    private $BlogId;
    private $BlogTitle;
    private $BlogBannerPath;
    private $BlogThumbPath;
    private $BlogExercept;
    private $BlogDescription;
    private $BlogReads;
    private $BlogTags;
    private $BlogStatus;//1=active, 0=inactive, 00 = deleted

    function __construct($Connection = null, $BlogId = null, $BlogTitle = null, $BlogBannerPath = null, $BlogThumbPath = null, $BlogExercept = null, $BlogDescription = null, $BlogReads = null, $BlogTags = null, $BlogStatus = null){
        $this->Connection = $Connection;
        $this->BlogId = $BlogId;
        $this->BlogTitle = $BlogTitle;
        $this->BlogBannerPath = $BlogBannerPath;
        $this->BlogThumbPath = $BlogThumbPath;
        $this->BlogExercept = $BlogExercept;
        $this->BlogDescription = $BlogDescription;
        $this->BlogReads = $BlogReads;
        $this->BlogTags = $BlogTags;
        $this->BlogStatus = $BlogStatus;
    }
    function ValidateFields(){
        if(empty($this->BlogId)){
            throw new Exception("Blog ID Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogTitle)){
            throw new Exception("Blog Title Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogBannerPath)){
            throw new Exception("Blog Banner Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogThumbPath)){
            throw new Exception("Blog thumb Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogExercept)){
            throw new Exception("Blog Exercept Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogDescription)){
            throw new Exception("Blog Description Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogTags)){
            throw new Exception("Blog Tags Field Is Blank! ");
            return false;
        }
        if(empty($this->BlogStatus)){
            throw new Exception("Blog Status Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `blog_posts`(`BlogId`, `BlogTitle`, `BlogBannerPath`, `BlogThumbPath`, `BlogExercept`, `BlogDescription`, `BlogReads`, `BlogTags`, `BlogStatus`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i)");
            $statement->bindParam(':a', $this->BlogId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->BlogTitle, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->BlogBannerPath, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->BlogThumbPath, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->BlogExercept, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->BlogDescription, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->BlogReads, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->BlogTags, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->BlogStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Blog Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `blog_posts` SET `BlogTitle`=:b,`BlogBannerPath`=:c,`BlogThumbPath`=:d,`BlogExercept`=:e,`BlogDescription`=:f,`BlogTags`=:g,`BlogStatus`=:h WHERE `BlogId`=:a");
            $statement->bindParam(':a', $this->BlogId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->BlogTitle, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->BlogBannerPath, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->BlogThumbPath, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->BlogExercept, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->BlogDescription, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->BlogTags, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->BlogStatus, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Blog Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Comment($data){
        $util = new Util();
        $statement = $this->Connection->prepare("INSERT INTO `blog_comments`(`Comment`, `CommentPost`, `CommentUser`) VALUES (:a,:b,:c)");
        $statement->bindParam(':a', $data[0], PDO::PARAM_STR);
        $statement->bindParam(':b', $data[1], PDO::PARAM_STR);
        $statement->bindParam(':c', $data[2], PDO::PARAM_STR);
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not create Blog comment. Contact Admins');
            return false;
        }
        return true;
    }
    function DeleteComment($id){
        $util = new Util();
        $statement = $this->Connection->prepare("DELETE FROM `blog_comments` WHERE `CommentSequence`=:a");
        $statement->execute([':a'=>$id]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not delete Blog comment. Contact Admins');
            return false;
        }
        return true;
    }
    function FindComments($post){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `blog_comments` WHERE `CommentPost`=:a");
        $statement->execute([':a' => $post]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not create Blog comment. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindById($id){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `blog_posts` WHERE `BlogId` = :a");
        $statement->execute([ ':a' => $id ]);
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Blog Items. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindAll($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `blog_posts` WHERE `BlogStatus`= '1' ORDER BY BlogSequence DESC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Blog Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindPopular($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `blog_posts` WHERE `BlogStatus`= '1' ORDER BY BlogReads DESC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Blog Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindRelated($pk){
        $util = new Util();
        $statement = $this->Connection->prepare("(SELECT * FROM `blog_posts` WHERE `BlogStatus`= '1' AND `BlogSequence` < $pk ORDER BY `BlogSequence` DESC LIMIT 2) UNION (SELECT * FROM `blog_posts` WHERE `BlogStatus`= '1' AND `BlogSequence` > $pk LIMIT 1)");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not GET ITEMS. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindRecent($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `blog_posts` WHERE `BlogStatus`= '1' ORDER BY created_at DESC LIMIT $limit");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Blog Items. Contact Admins');
            return false;
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    function FindRecentComments($limit = 10){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT a.*,b.* FROM `blog_posts` a, `blog_comments` b WHERE a.BlogId = b.CommentPost ORDER BY b.created DESC LIMIT $limit ");
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
        $statement = $this->Connection->prepare("UPDATE `blog_posts` SET `BlogStatus`= '00' WHERE `BlogId` = :a");
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