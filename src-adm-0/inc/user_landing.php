<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Merchants</h4>
        <p class="text-muted page-title-alt">Welcome to PataShop administration panel</p>
    </div>
</div>

<div class="row">
    <?php 
        // $util->Mail(['test','iotuya05@gmail.com','Juma', $util->FetchMailTemplates()]);
        // $user = new User($util->CreateConnection());
        // $res = $user->FindById('PGwlVhjrduB6tKxb');
        // $util->Show($res['UserEmail']);
        if(isset($_POST['save-usr'])){
            /** manage user */
            try{
                $UserId = $util->KeyGen(16);
                $util->ValidateEmail($_POST['UserEmail']);
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
                    $util->FlashMessage('User Created Successfully!');
                }
            }catch(Exception $e ){
                $util->FlashMessage($e->getMessage(), 0);
            }
        }
    ?>
    <?php if($util->isAdmin()){?>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-3">
                <div class="card-box">

                    <div class="form-group m-b-20">
                        <label>Full name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="UserFullName" name="UserFullName">
                    </div>

                    <div class="form-group m-b-20">
                        <label>Email Address<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="UserEmail" name="UserEmail">
                    </div>

                    <div class="form-group m-b-20">
                        <label>Phone Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="UserPhone" name="UserPhone">
                    </div>

                </div>
            </div>

            <div class="col-lg-3">
                <div class="card-box">

                    <div class="form-group m-b-20">
                        <label>Alternative Phone Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="UserPhoneAlt" name="UserPhoneAlt">
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


            <div class="col-lg-3">
                <div class="card-box">
                    
                    <div class="form-group m-b-20">
                        <label>County/State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="UserCounty" id="UserCounty">
                    </div>

                    <div class="form-group m-b-20">
                        <label>City/Town <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="UserCity" id="UserCity">
                    </div>

                    <div class="form-group m-b-20">
                        <label>User Type <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="UserType" id="UserType">
                            <option value="nn">Select</option>
                            <?php 
                            foreach( $util->UserTypes() as $k => $v ):
                                print '<option value="'.$k.'">'.$v.'</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>

                </div>
            </div>


            <div class="col-lg-3">
                <div class="card-box">

                    <div class="form-group m-b-20">
                        <label>Shipping/Warehouse/Store/Home address (<small>depends on usertype</small>) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="UserShippingAddress" id="UserShippingAddress">
                        <?=$util->placeAutocomplete('UserShippingAddress')?>
                    </div>

                    <div class="form-group m-b-20">
                        <label>User Profile Photo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="UserProfilePhoto" id="UserProfilePhoto">
                    </div>

                    <div class="form-group m-b-20">
                        <button type="submit" name="save-usr" class="btn w-sm btn-default waves-effect waves-light">Save</button>
                    </div>
                </div>
            </div>


        </div>


        <!-- <div class="row">
            <div class="col-sm-12">
                <div class="text-center p-20">
                    <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button>
                    <button type="button" class="btn w-sm btn-default waves-effect waves-light">Save</button>
                    <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete</button>
                </div>
            </div>
        </div> -->
    </form>
    <?php } ?>
</div>

<!-- MERCHANTS -->
<div class="row">
    <?php 
    $merchant = new User($util->CreateConnection());
    $merchants = $merchant->FindByType(4004);
    if(!$util->isAdmin()){
        $merchants = $merchant->FindMerchantById($_SESSION['usr']['UserId'], 4004);
    }
    ?>
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title"><b>PataShop Sellers/Merchants</b></h4>
            <p class="text-muted font-13 m-b-30">
                Manage PataShop Sellers.
            </p>

            <table id="datatable-buttons" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
                </thead>


                <tbody>
                <?php 
                foreach($merchants as $merch): ?>
                    <tr>
                        <td><?=$merch['UserFullName']?></td>
                        <td><?=$merch['UserEmail']?></td>
                        <td><?=$merch['UserPhone']?></td>
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$merch['UserId']?>" class="btn btn-outline"><span class="md md-pageview"> </span> View products</a></td>
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$merch['UserId']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                    </tr>
                <?php 
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- ADMINS  -->
<div class="row">
    <?php 
    $admin = new User($util->CreateConnection());
    $admins = $admin->FindByType(4003);
    if(!$util->isAdmin()){
        $admins = $admin->FindMerchantById($_SESSION['usr']['UserId'], 4003);
    }
    ?>
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title"><b>PataShop Admin Users</b></h4>
            <p class="text-muted font-13 m-b-30">
                Manage PataShop Admins.
            </p>

            <table id="datatable-buttons_a" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                </thead>


                <tbody>
                <?php 
                foreach($admins as $adm): ?>
                    <tr>
                        <td><?=$adm['UserId']?></td>
                        <td><?=$adm['UserFullName']?></td>
                        <td><?=$adm['UserEmail']?></td>
                        <td><?=$adm['UserPhone']?></td>
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$adm['UserId']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                    </tr>
                <?php 
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ADMINS  -->
<div class="row">
    <?php 
    $cust = new User($util->CreateConnection());
    $customers = $cust->FindByType(4005);
    if(!$util->isAdmin()){
        $customers = $cust->FindMerchantById($_SESSION['usr']['UserId'], 4005);
    }
    ?>
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title"><b>PataShop Clients/Customers</b></h4>
            <p class="text-muted font-13 m-b-30">
                Manage PataShop Customers/Clients.
            </p>

            <table id="datatable-buttons_b" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                </thead>


                <tbody>
                <?php 
                foreach($customers as $customer): ?>
                    <tr>
                        <td><?=$customer['UserId']?></td>
                        <td><?=$customer['UserFullName']?></td>
                        <td><?=$customer['UserEmail']?></td>
                        <td><?=$customer['UserPhone']?></td>
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$customer['UserId']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                    </tr>
                <?php 
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>