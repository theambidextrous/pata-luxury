<?php
/**
 * @filename: Setting.php
 * @role: Setting object
 * @author: avatar
 * @license : Proriatery
 */
class Setting{
    private $Connection;
    private $SettingId;
    private $SiteName;
    private $SiteAddress;
    private $SiteRichText;
    private $SiteKeyWords;
    private $SiteGoogleCode;
    private $SiteFacebookCode;
    private $SiteYandexCode;
    private $SiteBingCode;
    private $SiteContact;
    private $SiteContactAlt;
    private $SiteEmail;
    private $SiteFaceBook;
    private $SiteTwitter;
    private $SiteInstagram;
    private $SiteRss;
    private $SiteYouTube;
    private $SiteAboutPage;
    private $SiteServicesPage;
    private $SiteContactPage;
    private $SiteShareButtons;
    private $PrivacyPolicy;
    private $TermsOfUse;
    private $SiteMap;
    
    function __construct($Connection=null,$SettingId=null,$SiteName=null,$SiteAddress=null,$SiteRichText=null,$SiteKeyWords=null,$SiteGoogleCode=null,$SiteFacebookCode=null,$SiteYandexCode=null,$SiteBingCode=null,$SiteContact=null,$SiteContactAlt=null,$SiteEmail=null,$SiteFaceBook=null,$SiteTwitter=null,$SiteInstagram=null,$SiteRss=null,$SiteYouTube=null,$SiteAboutPage=null,$SiteServicesPage=null,$SiteContactPage=null,$SiteShareButtons=null,$PrivacyPolicy=null,$TermsOfUse=null,$SiteMap=null){
        $this->Connection = $Connection;
        $this->SettingId = $SettingId;
        $this->SiteName = $SiteName;
        $this->SiteAddress = $SiteAddress;
        $this->SiteRichText = $SiteRichText;
        $this->SiteKeyWords = $SiteKeyWords;
        $this->SiteGoogleCode = $SiteGoogleCode;
        $this->SiteFacebookCode = $SiteFacebookCode;
        $this->SiteYandexCode = $SiteYandexCode;
        $this->SiteBingCode = $SiteBingCode;
        $this->SiteContact = $SiteContact;
        $this->SiteContactAlt = $SiteContactAlt;
        $this->SiteEmail = $SiteEmail;
        $this->SiteFaceBook = $SiteFaceBook;
        $this->SiteTwitter = $SiteTwitter;
        $this->SiteInstagram = $SiteInstagram;
        $this->SiteRss = $SiteRss;
        $this->SiteYouTube = $SiteYouTube;
        $this->SiteAboutPage = $SiteAboutPage;
        $this->SiteServicesPage = $SiteServicesPage;
        $this->SiteContactPage = $SiteContactPage;
        $this->SiteShareButtons = $SiteShareButtons;
        $this->PrivacyPolicy = $PrivacyPolicy;
        $this->TermsOfUse = $TermsOfUse;
        $this->SiteMap = $SiteMap;
    }
    function ValidateFields(){
        $util = new Util();
        if(empty($this->SettingId)){
            throw new Exception("Setting ID Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteName)){
            throw new Exception("Site Name Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteAddress)){
            throw new Exception("Site Name Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteRichText)){
            throw new Exception("Site Rich Text Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteKeyWords)){
            throw new Exception("Site Key Words Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteGoogleCode)){
            throw new Exception("Site Google Code Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteFacebookCode)){
            throw new Exception("Site Facebook Code Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteYandexCode)){
            throw new Exception("Site Yandex Code Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteBingCode)){
            throw new Exception("Site Bing Code Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteContact)){
            throw new Exception("Site Contact Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteContactAlt)){
            throw new Exception("Site Alternative Contact Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteEmail)){
            throw new Exception("Site Email Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteFaceBook) || $util->isUrl($this->SiteFaceBook)){
            throw new Exception("Site FaceBook Field Is Blank Or Invalid! ");
            return false;
        }
        if(empty($this->SiteTwitter) || $util->isUrl($this->SiteTwitter)){
            throw new Exception("Site Twitter Field Is Blank Or Invalid! ");
            return false;
        }
        if(empty($this->SiteInstagram) || $util->isUrl($this->SiteInstagram)){
            throw new Exception("Site Instagram Field Is Blank Or Invalid! ");
            return false;
        }
        if(empty($this->SiteRss)){
            throw new Exception("Site RSS Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteYouTube) || $util->isUrl($this->SiteYouTube)){
            throw new Exception("Site YouTube Field Is Blank Or Invalid! ");
            return false;
        }
        if(empty($this->SiteAboutPage)){
            throw new Exception("Site About Page Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteServicesPage)){
            throw new Exception("Site Services Page Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteContactPage)){
            throw new Exception("Site Contact Page Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteShareButtons)){
            throw new Exception("Site Share Buttons Field Is Blank! ");
            return false;
        }
        if(empty($this->PrivacyPolicy)){
            throw new Exception("Privacy Policy Field Is Blank! ");
            return false;
        }
        if(empty($this->TermsOfUse)){
            throw new Exception("Terms Of Use Field Is Blank! ");
            return false;
        }
        if(empty($this->SiteMap)){
            throw new Exception("Sitemap Field Is Blank! ");
            return false;
        }
        return true;
    }
    function Create(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("INSERT INTO `p_setting`(`SettingId`, `SiteName`, `SiteAddress`, `SiteRichText`, `SiteKeyWords`, `SiteGoogleCode`, `SiteFacebookCode`, `SiteYandexCode`, `SiteBingCode`, `SiteContact`, `SiteContactAlt`, `SiteEmail`, `SiteFaceBook`, `SiteTwitter`, `SiteInstagram`, `SiteRss`, `SiteYouTube`, `SiteAboutPage`, `SiteServicesPage`, `SiteContactPage`, `SiteShareButtons`, `PrivacyPolicy`, `TermsOfUse`, `SiteMap`) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o,:p,:q,:r,:s,:t,:u,:v,:w,:x)");
            $statement->bindParam(':a', $this->SettingId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->SiteName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->SiteAddress, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->SiteRichText, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->SiteKeyWords, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->SiteGoogleCode, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->SiteFacebookCode, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->SiteYandexCode, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->SiteBingCode, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->SiteContact, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->SiteContactAlt, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->SiteEmail, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->SiteFaceBook, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->SiteTwitter, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->SiteInstagram, PDO::PARAM_STR);
            $statement->bindParam(':p', $this->SiteRss, PDO::PARAM_STR);
            $statement->bindParam(':q', $this->SiteYouTube, PDO::PARAM_STR);
            $statement->bindParam(':r', $this->SiteAboutPage, PDO::PARAM_STR);
            $statement->bindParam(':s', $this->SiteServicesPage, PDO::PARAM_STR);
            $statement->bindParam(':t', $this->SiteContactPage, PDO::PARAM_STR);
            $statement->bindParam(':u', $this->SiteShareButtons, PDO::PARAM_STR);
            $statement->bindParam(':v', $this->PrivacyPolicy, PDO::PARAM_STR);
            $statement->bindParam(':w', $this->TermsOfUse, PDO::PARAM_STR);
            $statement->bindParam(':x', $this->SiteMap, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not create Setting Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function Update(){
        $util = new Util();
        if( $this->ValidateFields() ){
            $statement = $this->Connection->prepare("UPDATE `p_setting` SET `SiteName`=:b, `SiteAddress`=:c,`SiteRichText`=:d,`SiteKeyWords`=:e,`SiteGoogleCode`=:f,`SiteFacebookCode`=:g,`SiteYandexCode`=:h,`SiteBingCode`=:i,`SiteContact`=:j,`SiteContactAlt`=:k,`SiteEmail`=:l,`SiteFaceBook`=:m,`SiteTwitter`=:n,`SiteInstagram`=:o,`SiteRss`=:p,`SiteYouTube`=:q,`SiteAboutPage`=:r,`SiteServicesPage`=:s,`SiteContactPage`=:t,`SiteShareButtons`=:u,`PrivacyPolicy`=:v,`TermsOfUse`=:w,`SiteMap`=:x WHERE `SettingId`=:a");
            $statement->bindParam(':a', $this->SettingId, PDO::PARAM_STR);
            $statement->bindParam(':b', $this->SiteName, PDO::PARAM_STR);
            $statement->bindParam(':c', $this->SiteAddress, PDO::PARAM_STR);
            $statement->bindParam(':d', $this->SiteRichText, PDO::PARAM_STR);
            $statement->bindParam(':e', $this->SiteKeyWords, PDO::PARAM_STR);
            $statement->bindParam(':f', $this->SiteGoogleCode, PDO::PARAM_STR);
            $statement->bindParam(':g', $this->SiteFacebookCode, PDO::PARAM_STR);
            $statement->bindParam(':h', $this->SiteYandexCode, PDO::PARAM_STR);
            $statement->bindParam(':i', $this->SiteBingCode, PDO::PARAM_STR);
            $statement->bindParam(':j', $this->SiteContact, PDO::PARAM_STR);
            $statement->bindParam(':k', $this->SiteContactAlt, PDO::PARAM_STR);
            $statement->bindParam(':l', $this->SiteEmail, PDO::PARAM_STR);
            $statement->bindParam(':m', $this->SiteFaceBook, PDO::PARAM_STR);
            $statement->bindParam(':n', $this->SiteTwitter, PDO::PARAM_STR);
            $statement->bindParam(':o', $this->SiteInstagram, PDO::PARAM_STR);
            $statement->bindParam(':p', $this->SiteRss, PDO::PARAM_STR);
            $statement->bindParam(':q', $this->SiteYouTube, PDO::PARAM_STR);
            $statement->bindParam(':r', $this->SiteAboutPage, PDO::PARAM_STR);
            $statement->bindParam(':s', $this->SiteServicesPage, PDO::PARAM_STR);
            $statement->bindParam(':t', $this->SiteContactPage, PDO::PARAM_STR);
            $statement->bindParam(':u', $this->SiteShareButtons, PDO::PARAM_STR);
            $statement->bindParam(':v', $this->PrivacyPolicy, PDO::PARAM_STR);
            $statement->bindParam(':w', $this->TermsOfUse, PDO::PARAM_STR);
            $statement->bindParam(':x', $this->SiteMap, PDO::PARAM_STR);
            $statement->execute();
            $rs = $statement->errorInfo();
            if($rs[0] != '00000'){
                $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
                throw new Exception('Error occured. System could not update Setting Item. Contact Admins');
                return false;
            }
            return true;
        }
        return false;
    }
    function FindAll(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT * FROM `p_setting` LIMIT 1");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not get Settings. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function isAvailable(){
        $util = new Util();
        $statement = $this->Connection->prepare("SELECT count(*) as cnt FROM `p_setting`");
        $statement->execute();
        $rs = $statement->errorInfo();
        if($rs[0] != '00000'){
            $util->log('File: '.__FILE__.' at line '.__LINE__.' Err:- '.json_encode($rs));
            throw new Exception('Error occured. System could not validate setting existance. Contact Admins');
            return false;
        }
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if($res['cnt'] > 0){
            return true;
        }
        return false;
    }
}
?>