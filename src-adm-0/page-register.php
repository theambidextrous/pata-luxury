<?php 
session_start();
require_once '../mail/autoload.php';
require_once '../lib/BladeSMS.php';
require_once '../lib/Util.php';
$util = new Util();
require_once '../lib/User.php';
require_once '../lib/Color.php';
$util = new Util();
$user = new User($util->CreateConnection());
?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?=SITEMETA_DESCRIPTION?>">
        <meta name="author" content="Idd Juma Otuya - 0705007984">

        <link rel="shortcut icon" href="<?=FAVICON?>">

        <title><?=SITENAME?></title>


		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>
            <style>
            .wrapper-page {
                width: 700px!important;
            }
            .card-box {
                padding: 2px;
                border: 2px solid rgba(255, 255, 255, 0.07);
                -webkit-border-radius: 5px;
                border-radius: 5px;
                -moz-border-radius: 5px;
                background-clip: padding-box;
                margin-bottom: 4px;
                background-color: #323b44;
            }
            </style>
	</head>
	<body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box">
				<div class="panel-heading">
					<h3 class="text-center"> Sign Up to <strong class="text-custom">PataShop</strong> </h3>
				</div>
                <?php   
                        $_SESSION['form'] = [];
                        if(isset($_POST['save-usr'])){
                            /** manage user */
                            try{
                                $_SESSION['form']['UserFullName'] = $_POST['UserFullName'];
                                $_SESSION['form']['UserEmail'] = $_POST['UserEmail'];
                                $_SESSION['form']['UserPhone'] = $_POST['UserPhone'];
                                $_SESSION['form']['UserPhoneAlt'] = $_POST['UserPhoneAlt'];
                                $_SESSION['form']['UserCounty'] = $_POST['UserCounty'];
                                $_SESSION['form']['UserCity'] = $_POST['UserCity'];
                                $_SESSION['form']['UserShippingAddress'] = $_POST['UserShippingAddress'];
                                
                                $UserId = $util->KeyGen(16);
                                $util->ValidateEmail($_POST['UserEmail']);
                                $util->ValidatePhone($_POST['UserPhone']);
                                $util->ValidatePhone($_POST['UserPhoneAlt']);
                                $util->ValidateExtension($util->FindExtension('UserProfilePhoto'));
                                $util->ValidateImageDimension('UserProfilePhoto', 150,150);
                                $util->ValidateUploadSize('UserProfilePhoto');
                                $UserProfilePhoto = $UserId.'.'.$util->FindExtension('UserProfilePhoto');
                                $Connection = $util->CreateConnection();
                                $_POST['UserPassword'] = $util->KeyGen(8);
                                $UserPassword = $util->HashPassword($_POST['UserPassword']);
                                $user = new User($Connection,$UserId,$_POST['UserFullName'],$_POST['UserEmail'],$_POST['UserPhone'],$_POST['UserPhoneAlt'],$_POST['UserCurrency'],$_POST['UserCountry'],$_POST['UserCounty'],$_POST['UserCity'],$_POST['UserType'],$_POST['UserShippingAddress'],$UserPassword,$UserProfilePhoto,1);
                                $user->ValidateFields();
                                $util->UploadFile('UserProfilePhoto', APP_IMG_DIR.'profiles/'.$UserProfilePhoto);
                                if($user->Create()){
                                    $user->Notify($UserId, $_POST['UserPassword']);
                                    $util->FlashMessage('User Created Successfully! Login to your email for more information');
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 0);
                            }
                        }
                ?>
				<div class="panel-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-box">

                                    <div class="form-group m-b-20">
                                        <label>Full name <span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserFullName']?>" class="form-control" id="UserFullName" name="UserFullName">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Email Address<span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserEmail']?>" class="form-control" id="UserEmail" name="UserEmail">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Phone Number<span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserPhone']?>" placeholder="07xxxxxxxx" class="form-control" id="UserPhone" name="UserPhone">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card-box">

                                    <div class="form-group m-b-20">
                                        <label>Alternative Phone Number<span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserPhoneAlt']?>" placeholder="01xxxxxxxx" class="form-control" id="UserPhoneAlt" name="UserPhoneAlt">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Preferred Currency <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="UserCurrency" id="UserCurrency">
                                            <option value="nn">Select</option>
                                            <?php 
                                            foreach( $util->Currencies() as $k => $v ):
                                                print '<option value="'.$k.'">'.$v.'</option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Country <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="UserCountry" id="UserCountry">
                                            <option value="nn">Select</option>
                                            <?php 
                                            foreach( $util->Countries() as $k => $v ):
                                                print '<option value="'.$k.'">'.$v.'</option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="card-box">
                                    
                                    <div class="form-group m-b-20">
                                        <label>County/State <span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserCounty']?>" class="form-control" name="UserCounty" id="UserCounty">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>City/Town <span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserCity']?>" class="form-control" name="UserCity" id="UserCity">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>User Type <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="UserType" id="UserType">
                                            <?php 
                                            foreach( $util->UserTypes() as $k => $v ):
                                                if($k == 4005 ){
                                                    print '<option selected value="'.$k.'">'.$v.'</option>';
                                                }
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="card-box">

                                    <div class="form-group m-b-20">
                                        <label>Shipping/Home address<span class="text-danger">*</span></label>
                                        <input type="text" value="<?=$_SESSION['form']['UserShippingAddress']?>" class="form-control" name="UserShippingAddress" id="UserShippingAddress">
                                        <?=$util->placeAutocomplete('UserShippingAddress')?>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>User Profile Photo (150X150) <small><a href="<?=AVATAR?>">Download one here</a></small><span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="UserProfilePhoto" id="UserProfilePhoto">
                                    </div>

                                    <div class="form-group m-b-20">
                                        <button type="submit" name="save-usr" class="btn w-sm btn-default waves-effect waves-light">Save</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>

				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>
						Already have account?<a href="index.php" class="text-primary m-l-5"><b>Sign In</b></a>
					</p>
				</div>
			</div>

		</div>

		<script>
			var resizefunc = [];
		</script>

		<!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>


        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

	</body>
</html>