<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">My Preferences</h4>
        <p class="text-muted page-title-alt">Welcome to your PataShop account! manage preferences</p>
    </div>
</div>

<div class="row">
    
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="m-t-0 header-title"><b>Here, You Can</b></h4>
        <p class="text-muted font-13 m-b-30">
            Change your preferences. Whether to get updates, notifications, alternative Shipping address.
        </p>
        
        <!-- short cuts -->
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="widget-panel widget-style-2 bg-white">
                    <?php 
                        $pf = new Preference($util->CreateConnection());
                        $p = $pf->FindByUser($_SESSION['usr']['UserId']);
                        $_SESSION['form'] = $p;
                        if(isset($_POST['save-pref'])){
                            /** manage user */
                            try{
                                $pref = new Preference($util->CreateConnection(), $_SESSION['usr']['UserId'], $_POST['PrefAltAddress'], $_POST['ReceiverAdsMails'],$_POST['ReceiveNews'],$_POST['ReceiveSuggestedItems']);
                                if(!empty($p)){
                                    if($pref->Update()){
                                        $util->FlashMessage('Preference Updated Successfully!');
                                    }
                                }else{
                                    if($pref->Create()){
                                        $util->FlashMessage('Preference Updated Successfully!');
                                    }
                                }
                            }catch(Exception $e ){
                                $util->FlashMessage($e->getMessage(), 0);
                            }
                        }
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-b-20">
                                    <label>Alternative Shipping Address(e.g a friend's to deliver gifts)<span class="text-danger">*</span></label>
                                    <input type="text" value="<?=$_SESSION['form']['PrefAltAddress']?>" class="form-control" id="PrefAltAddress" name="PrefAltAddress">
                                    <?=$util->placeAutocomplete('PrefAltAddress')?>
                                </div>
                                <div class="form-group m-b-20">
                                    <label>Receiver Marketing Mail<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="ReceiverAdsMails" id="ReceiverAdsMails">
                                        <?php
                                            if($_SESSION['form']['ReceiverAdsMails'] == 1){
                                                print '<option selected value="1">Yes</option>';
                                                print '<option value="0">No</option>';
                                            }else if($_SESSION['form']['ReceiverAdsMails'] == 0){
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>'; 
                                            }else{
                                                print '<option value="3">Select one</option>';
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group m-b-20">
                                    <label>Receiver News<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="ReceiveNews" id="ReceiveNews">
                                        <?php
                                            if($_SESSION['form']['ReceiveNews'] == 1){
                                                print '<option selected value="1">Yes</option>';
                                                print '<option value="0">No</option>';
                                            }else if($_SESSION['form']['ReceiveNews'] == 0){
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>'; 
                                            }else{
                                                print '<option value="3">Select one</option>';
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group m-b-20">
                                    <label>Receive Suggested Items<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="ReceiveSuggestedItems" id="ReceiveSuggestedItems">
                                        <?php
                                            if($_SESSION['form']['ReceiveSuggestedItems'] == 1){
                                                print '<option selected value="1">Yes</option>';
                                                print '<option value="0">No</option>';
                                            }else if($_SESSION['form']['ReceiveSuggestedItems'] == 0){
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>'; 
                                            }else{
                                                print '<option value="3">Select one</option>';
                                                print '<option value="1">Yes</option>';
                                                print '<option selected value="0">No</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group m-b-20">
                                    <button type="submit" name="save-pref" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end shortcuts -->
    </div>
</div>

</div>