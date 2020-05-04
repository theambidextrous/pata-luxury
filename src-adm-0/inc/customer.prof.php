<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">My Profile</h4>
        <p class="text-muted page-title-alt">Welcome to your PataShop account! manage user information</p>
    </div>
</div>

<div class="row">
    
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="m-t-0 header-title"><b>Here, You Can</b></h4>
        <p class="text-muted font-13 m-b-30">
            Change your user information. Shipping address, city, country among others
        </p>
        
        <!-- short cuts -->
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="widget-panel widget-style-2 bg-white">
                    <?php   
                            $_SESSION['form'] = $user->FindById($_SESSION['usr']['UserId']);
                            /** update */
                            if(isset($_POST['save-usr'])){
                                /** manage user */
                                try{
                                    $UserId = $_SESSION['usr']['UserId'];
                                    $util->ValidateEmail($_POST['UserEmail']);
                                    $util->ValidatePhone($_POST['UserPhone']);
                                    $util->ValidatePhone($_POST['UserPhoneAlt']);
                                    $UserProfilePhoto = $_SESSION['usr']['UserId'];
                                    if(isset($_FILES['UserProfilePhoto']["size"])){
                                        $util->ValidateExtension($util->FindExtension('UserProfilePhoto'));
                                        $util->ValidateImageDimension('UserProfilePhoto', 150,150);
                                        $util->ValidateUploadSize('UserProfilePhoto');
                                        $UserProfilePhoto = $UserId.'.'.$util->FindExtension('UserProfilePhoto');
                                        $util->UploadFile('UserProfilePhoto', APP_IMG_DIR.'profiles/'.$UserProfilePhoto);
                                    }
                                    $UserPassword = $_SESSION['form']['UserPassword'];
                                    $user = new User($Connection,$UserId,$_POST['UserFullName'],$_POST['UserEmail'],$_POST['UserPhone'],$_POST['UserPhoneAlt'],$_POST['UserCurrency'],$_POST['UserCountry'],$_POST['UserCounty'],$_POST['UserCity'],$_POST['UserType'],$_POST['UserShippingAddress'],$UserPassword,$UserProfilePhoto,1);
                                    $user->ValidateFields();
                                    if($user->Update()){
                                        $util->FlashMessage('Updated Successfully!');
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
                                                    if($_SESSION['form']['UserCurrency'] == $k){
                                                        print '<option selected value="'.$k.'">'.$v.'</option>';
                                                    }else{
                                                        print '<option value="'.$k.'">'.$v.'</option>';
                                                    }
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
                                                    if($_SESSION['form']['UserCountry'] == $k){
                                                        print '<option selected value="'.$k.'">'.$v.'</option>';
                                                    }else{
                                                        print '<option value="'.$k.'">'.$v.'</option>';
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
                                            <label>User Profile Photo <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="UserProfilePhoto" id="UserProfilePhoto">
                                        </div>

                                        <div class="form-group m-b-20">
                                            <button type="submit" name="save-usr" class="btn w-sm btn-default waves-effect waves-light">Update Information</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- end shortcuts -->
    </div>
</div>

</div>