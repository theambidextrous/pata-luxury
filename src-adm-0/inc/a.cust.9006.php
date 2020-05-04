<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Concierge Services</h4>
        <p class="text-muted page-title-alt">Welcome to your PataShop account!</p>
    </div>
</div>
<style>
    .nts{
        font-size: 20px;
        font-weight: 600;
    }
    .nts-link{
        font-size: 18px;
        text-decoration: underline;
    }
</style>
<div class="row">
    
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="m-t-0 header-title"><b>Custom services inquiry</b></h4>
        <p class="text-muted font-13 m-b-30">
            We have exclusively partnered with Thamani to bring you high-end luxury concierge services. Anything anywhere!
        </p>
        <!--form -->
        <?php 
        $thmani_list = $util->getThamaniItems();
        // $util->Show($thmani_list);
        ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group m-b-20">
                                <label>Select Service(s) <span class="text-danger">*</span></label>
                                <select class="form-control thamani_svc" id="Service" name="Service[]" multiple="multiple">
                                    <option value="nn">Select service(s)</option>
                                    <?php foreach( $thmani_list as $tl): ?>
                                        <option value=""><?=$tl['name']?></option>
                                   <?php endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-b-20">
                                <label>Location(where you want this service) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="Location" id="Location">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-b-20">
                                <label>Date & Time <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="datetime" id="datetime">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-b-20">
                                <label>Additional information <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="Info" id="Info"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-b-20">
                                <button type="submit" name="inq" class="btn w-sm btn-default waves-effect waves-light">Inquire Now</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- end form -->
    </div>
</div>

<div class="col-sm-12">
    <div class="card-box table-responsive">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
            <table id="datatable-buttons" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#No</th>
                    <th>Item(s)</th>
                    <th>Amount Charged</th>
                    <th>Amount Paid</th>
                    <th>Date Paid</th>
                    <th>Status</th>
                    <th>More</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><a onclick="loadModal()" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

</div>