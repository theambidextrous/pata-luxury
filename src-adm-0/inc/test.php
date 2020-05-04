<?php 
$t = '
<form method="post" id="create_Car_form" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-success" id="succ" style="display:none;"></div>
                            <div class="alert alert-danger" id="err" style="display:none;"></div>
                        </div>
                        <!-- Begin left box -->
                        <div class="col-lg-4">
                            <!-- row -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarName'].'" class="form-control" id="CarName" name="CarName" placeholder="Car name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarPlateNumber'].'" class="form-control" id="CarPlateNumber" name="CarPlateNumber" placeholder="Car License Plate Number">
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarPrice'].'" class="form-control" id="CarPrice" name="CarPrice" placeholder="Car Hire/Rent Price">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarBrand'].'" class="form-control" id="CarBrand" name="CarBrand" placeholder="CarBrand">
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- End left, begin middle -->
                        <div class="col-lg-8">  
                            <!-- row -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <select class="form-control select2" name="CarCommisionType" id="CarCommisionType">
                                            <option value="nn">Commision Type</option>
                                            '.$comm_select.'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarCommisionValue'].'" class="form-control" id="CarCommisionValue" name="CarCommisionValue" placeholder="Commission Value">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="number" value="'.$meta['Car3D'].'" class="form-control" id="Car3D" name="Car3D" placeholder="3D Spinzam">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-b-20">
                                        <input type="text" value="'.$meta['CarMetaDescription'].'" class="form-control" id="CarMetaDescription" name="CarMetaDescription" placeholder="Meta Description">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End middle begin right -->
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            
                            <div class="form-group m-b-20">
                                <label>Discounts, Vendor, Packs<span class="text-danger">*</span></label>
                                <select class="form-control select2" name="CarDiscountType" id="CarDiscountType">
                                    <option value="nn">Discount Type</option>
                                    '.$discount_select.'
                                </select>
                            </div>        
                            <div class="form-group m-b-20">
                                <input type="text" value="'.$meta['CarDiscountValue'].'" class="form-control" id="CarDiscountValue" name="CarDiscountValue" placeholder="Discount Value">
                            </div>
                                
                            <div class="form-group m-b-20">
                                <select class="form-control select2" name="CarOwner" id="CarOwner">
                                    <option value="nn">Vendor/Merchant</option>
                                    '.$vendor_select.'
                                </select>
                            </div>
                            <div class="form-group m-b-20">
                                <select class="form-control select2" name="CarPack[]" id="CarPack" multiple="multiple" data-placeholder="Select Car Packs">
                                   '.$pack_select.'
                                </select>
                            </div>
                            <div class="form-group m-b-20">
                                <button type="button" name="save-Car" onclick="updateCar('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Save Changes</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-b-20">
                                <label>Car Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="CarDescription" name="CarDescription">'.$util->HtmlDecode($meta['CarDescription']).'</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .select2-search__field{
                width:100%!important;
            }
        </style>
    </div>
</form>';
?>