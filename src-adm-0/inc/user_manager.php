<style>
.modal-sm{
max-width: 50%!important;
background: goldenrod!important;
}
.modal-sm > .modal-content{
background: black!important;
}
.m-b-20 {
    margin-bottom: 7px !important;
}
@media (min-width: 768px){
    .modal-dialog {
    width: 80%!important;
    margin: 30px auto;
}
}
.modal .modal-dialog .modal-content {
    padding: 7px!important;
    border-color: rgba(255, 255, 255, 0);
    border-radius: 8px;
}
.card-box{
    padding: 6px!important;
}
</style>
<?php 
    error_reporting(1);
    require_once '../lib/Product.php';
    require_once '../lib/Category.php';
    require_once '../lib/Color.php';
    $category = new Category($util->CreateConnection());
    $user = new User($util->CreateConnection());
    $color = new Color($util->CreateConnection());
    $meta = $user->FindById($_GET['usr-curr']);
    if(!$util->isAdmin()){
        $meta = $user->FindById($_SESSION['usr']['UserId']);
    }
?>
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title"><a href="ecommerce-users.php">Merchants</a> > <a href="#">merchant</a> > <?=$meta['UserFullName']?></h4>
        <p class="text-muted page-title-alt">Welcome to PataShop administration panel</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <!-- tab start -->
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#updateuser" data-toggle="tab">Update user</a></li>
                            <li><a href="#usrproducts" data-toggle="tab">Products</a></li>
                            <li><a href="#usrorders" data-toggle="tab">Orders</a></li>
                            <li><a href="#usrpreference" data-toggle="tab">Preferences</a></li>
                            <li><a href="#usrsettings" data-toggle="tab">Settings</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="updateuser">
                            <!-- Update user start -->
                            <div class="card-box table-responsive">
                                <h4 class="m-t-0 header-title"><b>PataShop Sellers/Merchants</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Manage PataShop Sellers.
                                </p>
                                <?php 
                                    $UserId = $meta['UserId'];
                                    $Connection = $util->CreateConnection();
                                    if(isset($_POST['dis-usr'])){
                                        $usr = new User($Connection);
                                        $usr->Disable($UserId);
                                    }
                                    if(isset($_POST['del-usr'])){
                                        $usr = new User($Connection);
                                        $usr->Delete($UserId);
                                    }
                                    /** update */
                                    if(isset($_POST['save-usr'])){
                                        /** manage user */
                                        try{
                                            $util->ValidateEmail($_POST['UserEmail']);
                                            $util->ValidatePhone($_POST['UserPhone']);
                                            $util->ValidatePhone($_POST['UserPhoneAlt']);
                                            $util->ValidateExtension($util->FindExtension('UserProfilePhoto'));
                                            $util->ValidateImageDimension('UserProfilePhoto', 150,150);
                                            $util->ValidateUploadSize('UserProfilePhoto');
                                            $UserProfilePhoto = $UserId.'.'.$util->FindExtension('UserProfilePhoto');
                                            $_POST['UserPassword'] = $util->KeyGen(8);
                                            $UserPassword = $util->HashPassword($_POST['UserPassword']);
                                            $user = new User($Connection,$UserId,$_POST['UserFullName'],$_POST['UserEmail'],$_POST['UserPhone'],$_POST['UserPhoneAlt'],$_POST['UserCurrency'],$_POST['UserCountry'],$_POST['UserCounty'],$_POST['UserCity'],$_POST['UserType'],$_POST['UserShippingAddress'],$UserPassword,$UserProfilePhoto,1);
                                            $user->ValidateFields();
                                            $util->UploadFile('UserProfilePhoto', APP_IMG_DIR.'profiles/'.$UserProfilePhoto);
                                            if($user->Update()){
                                                $user->Notify($UserId, $_POST['UserPassword']);
                                                $util->FlashMessage('User Updated Successfully!');
                                            }
                                        }catch(Exception $e ){
                                            $util->FlashMessage($e->getMessage(), 0);
                                        }
                                    }
                                ?>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="card-box">

                                                <div class="form-group m-b-20">
                                                    <label>Full name <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserFullName']?>" class="form-control" id="UserFullName" name="UserFullName">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>Email Address<span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserEmail']?>" class="form-control" id="UserEmail" name="UserEmail">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>Phone Number<span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserPhone']?>" class="form-control" id="UserPhone" name="UserPhone">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card-box">

                                                <div class="form-group m-b-20">
                                                    <label>Alternative Phone Number<span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserPhoneAlt']?>" class="form-control" id="UserPhoneAlt" name="UserPhoneAlt">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>Preferred Currency <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" name="UserCurrency" id="UserCurrency">
                                                        <option value="nn">Select</option>
                                                        <?php 
                                                        foreach( $util->Currencies() as $k => $v ):
                                                            if($meta['UserCurrency'] == $k ){
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
                                                            if($meta['UserCountry'] == $k ){
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
                                        <div class="col-lg-2">
                                            <div class="card-box">
                                                
                                                <div class="form-group m-b-20">
                                                    <label>County/State <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserCounty']?>" class="form-control" name="UserCounty" id="UserCounty">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>City/Town <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserCity']?>" class="form-control" name="UserCity" id="UserCity">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>User Type <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" name="UserType" id="UserType">
                                                        <option value="nn">Select</option>
                                                        <?php 
                                                        foreach( $util->UserTypes() as $k => $v ):
                                                            if($meta['UserType'] == $k ){
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
                                        <div class="col-lg-4">
                                            <div class="card-box">

                                                <div class="form-group m-b-20">
                                                    <label>Shipping/Warehouse/Store/Home address (<small>depends on usertype</small>) <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?=$meta['UserShippingAddress']?>" class="form-control" name="UserShippingAddress" id="UserShippingAddress">
                                                    <?=$util->placeAutocomplete('UserShippingAddress')?>
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <label>User Profile Photo <span class="text-danger">*</span></label>
                                                    <input type="file" value="<?=$meta['UserProfilePhoto']?>" class="form-control" name="UserProfilePhoto" id="UserProfilePhoto">
                                                </div>

                                                <div class="form-group m-b-20">
                                                    <?php if($util->isAdmin()){?>
                                                    <button type="submit" name="save-usr" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                                                    <button type="submit" name="dis-usr" class="btn w-sm btn-warning waves-effect waves-light">Disable</button>
                                                    <button type="submit" name="del-usr" class="btn w-sm btn-danger waves-effect waves-light">Delete</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- end  -->
                        </div>
                        <div class="tab-pane fade" id="usrproducts">
                            <?php 
                            $product = new Product($util->CreateConnection());
                            $obj_0 = new Color($util->CreateConnection());
                            $obj_1 = new Category($util->CreateConnection());
                            $products = $product->FindByVendor($meta['UserId']);
                            // $util->Show($products);
                            ?>
                            <div class="card-box table-responsive" id="reload_div">
                                <h4 class="m-t-0 header-title"><b><?=$meta['UserFullName']?> Products</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Manage <?=$meta['UserFullName']?> products. Create, Update, Delete.
                                <?php if(!$util->isCustomer()){?>
                                        <a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#product_create" style="font-weight:900;font-size:18px;" class="pull-right btn btn-default btn-sm waves-effect waves-light"><span class="md md-add-box"></span> Create New </a>
                                <?php } ?>
                                </p>
                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Available Colors</th>
                                        <th>Categories</th>
                                        <th>Tags</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach($products as $prod): ?>
                                        <tr>
                                            <td><?=$no?></td>
                                            <td><?=$prod['ProductName']?></td>
                                            <td><?=$prod['ProductPrice']?></td>
                                            <td><?=$util->CodeToNames($obj_0, $prod['ProductColors'], 'ColorName')?></td>
                                            <td><?=$util->CodeToNames($obj_1, $prod['ProductCategories'], 'CategoryName')?></td>
                                            <td><?=$prod['ProductTags']?></td>
                                            <td><a onclick="ProductDetailsModal('<?=$prod['ProductId']?>')" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                                        </tr>
                                    <?php 
                                    $no++;
                                    endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="usrorders">Success 3</div>
                        <div class="tab-pane fade" id="usrpreference">Success 4</div>
                        <div class="tab-pane fade" id="usrsettings">Success 5</div>
                    </div>
                </div>
            </div>
            <!-- end tabs -->
        </div>
    </div>

</div>
