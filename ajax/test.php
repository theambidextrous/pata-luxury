<?php 
$f = '
    <!-- m-body -->
    <form method="post" id="update_product_form" enctype="multipart/form-data">
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
                                    <div class="col-lg-4">
                                        <div class="form-group m-b-20">
                                            <input type="hidden" id="ProductId" name="ProductId" value="'.$meta['ProductId'].'">
                                            <input type="text" value="'.$meta['ProductName'].'" class="form-control" id="ProductName" name="ProductName" placeholder="Product name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group m-b-20">
                                            <input type="number" min="1" value="'.$meta['ProductPrice'].'" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group m-b-20">
                                            <select class="form-control select2" name="ProductShipper" id="ProductShipper">
                                                <option value="nn">Shipper</option>
                                                '.$shipper_select.'
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <select class="form-control select2" name="ProductCommisionType" id="ProductCommisionType">
                                                <option value="nn">Commision Type</option>
                                                '.$comm_select.'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <input type="text" value="'.$meta['ProductCommisionValue'].'" class="form-control" id="ProductCommisionValue" name="ProductCommisionValue" placeholder="Commission Value">
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- End left, begin middle -->
                            <div class="col-lg-4">  
                                <!-- row -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <select class="form-control select2" name="ProductDiscountType" id="ProductDiscountType">
                                                <option value="nn">Discount Type</option>
                                                '.$discount_select.'
                                            </select>
                                        </div>        
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-20">
                                            <input type="text" value="'.$meta['ProductDiscountValue'].'" class="form-control" id="ProductDiscountValue" name="ProductDiscountValue" placeholder="Discount Value">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group m-b-20">
                                            <input type="number" value="'.$meta['Product3D'].'" class="form-control" id="Product3D" name="Product3D" placeholder="3D Spinzam">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End middle begin right -->
                            <div class="col-lg-4">
                                <!-- row -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group m-b-20">
                                            <input type="text" value="'.$meta['ProductMetaDescription'].'" class="form-control" id="ProductMetaDescription" name="ProductMetaDescription" placeholder="Meta Description">
                                        </div>
                                        <div class="form-group m-b-20">
                                            <select class="form-control select2" name="ProductOwner" id="ProductOwner">
                                                <option value="nn">Vendor/Merchant</option>
                                               '.$vendor_select.'
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end right -->
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-b-20">
                                    <label>Product Colors, Category, Tags<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="ProductColors[]" id="ProductColors" multiple="multiple" data-placeholder="Product Available Colors">
                                        '.$color_select.'
                                    </select>
                                </div>
                                <div class="form-group m-b-20">
                                    <input type="text" value="'.$meta['ProductTags'].'" class="form-control" id="ProductTags" name="ProductTags" placeholder="Add Meta Tags" data-role="tagsinput">
                                </div>
                                <div class="form-group m-b-20">
                                    <select class="form-control select2" name="ProductCategories[]" id="ProductCategories" multiple="multiple" data-placeholder="Select Categories for the item">
                                        '.$categ_select.'
                                    </select>
                                </div>
                                <div class="form-group m-b-20">
                                    <input type="text" value="'.$meta['ProductShortDescription'].'" class="form-control" id="ProductShortDescription" name="ProductShortDescription" placeholder="Short Description">
                                </div>
                                <div class="form-group m-b-20">
                                    <button type="button" name="save-product" onclick="postProduct('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Create item</button>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-b-20">
                                    <label>Product Description<span class="text-danger">*</span></label>
                                    <textarea class="summernote form-control" id="ProductDescription" name="ProductDescription">
                                    '.$util->HtmlDecode($meta['ProductDescription']).'
                                    </textarea>
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
    </form>
    <!-- end body -->
';
?>