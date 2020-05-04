<?php 
require_once '../mail/autoload.php';
require_once '../lib/BladeSMS.php';
require_once '../lib/Util.php';
$util = new Util();
require_once '../lib/User.php';
require_once '../lib/Otp.php';
$otp = new Otp($util->CreateConnection());
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

	</head>
	<body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		
		<div class="wrapper-page">
			<div class="card-box">
				<div class="panel-heading">
					<h3 class="text-center"> Sign In to <strong class="text-custom">PataShop</strong></h3>
				</div>

				<div class="panel-body">
					<?php 
						// $user_otp = $otp->Create(5, 'idd.otuya@outlook.com');
						// $otp->LogAccess('idd.otuya@outlook.com', 1);
						if(isset($_POST['xlogin'])){
							try{
								if(!empty($_POST['UserName']) && !empty($_POST['PassWord'])){
									$user = new User($util->CreateConnection());
									$UserMeta = $user->FindByEmail($_POST['UserName']);
									$util->ValidatePassword($_POST['PassWord'], $UserMeta['UserPassword']);
									$user_otp = $otp->Create(5, $_POST['UserName']);
									$user->SendOtp($_POST['UserName'], $user_otp);
									$util->ShowOtpForm($_POST['UserName']);
								}else{
									print '<div class="alert alert-danger">Empty fields not allowed!</div>';
								}
							}catch(Exception $e ){
								print '<div class="alert alert-danger">'.$e->getMessage().'</div>';
							}
						}else if(isset($_POST['xotp'])){
							try{
								if(!empty($_POST['UserOtp'])){
									$in_otp = $_POST['UserOtp'];
									$in_user = $_POST['UserEmail'];
									if($otp->Validate($in_user, $in_otp)){
										if($otp->FirstLogin($in_user)){
											//change password
											$util->ShowPwdForm($in_user);

										}else{
											//create sessions
											session_start();
											$user = new User($util->CreateConnection());
											$UserMeta = $user->FindByEmail($in_user);
											$_SESSION['usr'] = $UserMeta;
											$otp->LogAccess($in_user, 1);
											$util->RedirectTo('ecommerce-home.php');
										}
									}else{
										print '<div class="alert alert-danger">Invalid OTP!</div>';
										$util->ShowOtpForm($in_user);
									}
								}
						}catch(Exception $e ){
							print '<div class="alert alert-danger">'.$e->getMessage().'</div>';
						}
					}else if(isset($_POST['xpassword'])){
							try{
								if(!empty($_POST['NewPassword'])){
									$user = new User($util->CreateConnection());
									$NewPassword = $_POST['NewPassword'];
									$CNewPassword = $_POST['CNewPassword'];
									$UserEmail = $_POST['UserEmail'];
									if($NewPassword === $CNewPassword){
										$util->ValidatePasswordStrength($NewPassword);
										$user->ChangePassword([$UserEmail, $util->HashPassword($NewPassword)]);
										//create sessions
										session_start();
										$user = new User($util->CreateConnection());
										$UserMeta = $user->FindByEmail($UserEmail);
										$_SESSION['usr'] = $UserMeta;
										$otp->LogAccess($UserEmail, 1);
										$util->RedirectTo('ecommerce-home.php');
									}else{
										$util->ShowPwdForm($UserEmail);
										print '<div class="alert alert-danger">Password mismatch!</div>';
									}
								}else{
									$util->ShowPwdForm($UserEmail);
								}
						}catch(Exception $e ){
							print '<div class="alert alert-danger">'.$e->getMessage().'</div>';
						}
					}
					?>
					<form class="form-horizontal m-t-20" action="" method="post">

						<div class="form-group ">
							<div class="col-xs-12">
								<input class="form-control" name="UserName" id="UserName" type="text" required="" placeholder="Username">
							</div>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<input class="form-control" type="password" name="PassWord" id="PassWord" required="" placeholder="Password">
							</div>
						</div>

						<div class="form-group ">
							<div class="col-xs-12">
								<div class="checkbox checkbox-primary">
									<input id="checkbox-signup" type="checkbox">
									<label for="checkbox-signup"> Remember me </label>
								</div>

							</div>
						</div>

						<div class="form-group text-center m-t-40">
							<div class="col-xs-12">
								<button name="xlogin" class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">
									Log In
								</button>
							</div>
						</div>

						<div class="form-group m-t-20 m-b-0">
							<div class="col-sm-12">
								<a href="page-recoverpw.php" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
							</div>
						</div>
						<div class="form-group m-t-20 m-b-0">
							<div class="col-sm-12 text-center">
								<!-- <h4><b>Sign in with</b></h4> -->
							</div>
						</div>
						
						<!-- <div class="form-group m-b-0 text-center">
							<div class="col-sm-12">
								<button type="button" class="btn btn-facebook waves-effect waves-light m-t-20">
		                           <i class="fa fa-facebook m-r-5"></i> Facebook
		                        </button>

		                        <button type="button" class="btn btn-twitter waves-effect waves-light m-t-20">
		                           <i class="fa fa-twitter m-r-5"></i> Twitter
		                        </button>

		                        <button type="button" class="btn btn-googleplus waves-effect waves-light m-t-20">
		                           <i class="fa fa-google-plus m-r-5"></i> Google+
		                        </button>
							</div>
						</div> -->
					</form>

				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<p>
						Don't have an account? <a href="page-register.php" class="text-primary m-l-5"><b>Sign Up</b></a>
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