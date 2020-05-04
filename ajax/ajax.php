<?php 
session_start();
require_once '../lib/Util.php';
$util = new Util();
// $util->ShowErrors();
require_once '../lib/BladeSMS.php';
require_once '../lib/Category.php';
require_once '../lib/Cipher.php';
require_once '../lib/Gallery.php';
require_once '../lib/Param.php';
require_once '../lib/Payment.php';
require_once '../lib/Product.php';
require_once '../lib/Size.php';
require_once '../lib/Stock.php';
require_once '../lib/Color.php';
require_once '../lib/User.php';
require_once '../lib/Pack.php';
require_once '../lib/Room.php';
require_once '../lib/RoomCategory.php';
require_once '../lib/Car.php';
require_once '../lib/Package.php';

switch($_REQUEST['activity']){
    default:
        exit(json_encode(['ERR' => 'Mission Failed!']));
    break;
    case 'reserve-htl-rm':
        $adults = $_POST['adultQty'];
        $children = $_POST['childQty'];
        $checkin = date_create($_POST['indate']);
        $checkout = date_create($_POST['outdate']);
        $romm_id = $_POST['ProductId'];
        $now = date_create();
        $diff = date_diff($now,$checkin);
        if($checkin < $now ){
            exit(json_encode(['ERR' => 'Checkin date is invalid!']));
        }
        if($checkout <= $checkin ){
            exit(json_encode(['ERR' => 'Checkin and Checkout dates are invalid!']));
        }
        $reservation_data = [$adults, $children,$checkin,$checkout,$romm_id];
        exit(json_encode(['MSG' => $diff]));
    break;
    case 'show-category-modal':
        $Connection = $util->CreateConnection();
        $category = new Category($Connection);
        $product = new Product($Connection);
        $gallery = new Gallery($Connection);
        $meta = $category->FindById($_POST['CategoryId']);
        $categories = $category->FindAll();
        $formid = "'updateCategoryForm'";
        $select_option = '';
        foreach( $categories as $c ):
            $nomenclecture = $util->CategoryNaming($category, $c);
            if($c['CategoryId'] == $meta['CategoryParent']){
                $select_option .= '<option selected value="'.$c['CategoryId'].'">'.$nomenclecture.'</option>';
            }else{
                $select_option .= '<option value="'.$c['CategoryId'].'">'.$nomenclecture.'</option>';
            }
        endforeach;
        $body = '';
        $products = $product->FindByCategory($meta['CategoryId']);
        foreach($products as $prod): 
                $galleries = $gallery->FindByTypeProduct($prod['ProductId'], 5003);
                $body .= '<tr>';
                $body .= '<td><img src="'.APP_IMG_PATH . 'items/' .$galleries['GalleryPath'].'" class="thumb-sm" alt=""></td>';
                $body .= '<td>'.$prod['ProductName'].'</td>';
                $body .= '<td>'.$prod['ProductPrice'].'</td>';
                $body .= '</tr>';
        endforeach;

        $tab_1 = '
        <form method="post" enctype="multipart/form-data" id="updateCategoryForm"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="succ" style="display:none;"></div><div class="alert alert-danger" id="err" style="display:none;"></div></div><div class="col-lg-4"><div class="card-box"><div class="form-group m-b-20"><label>Category name <span class="text-danger">*</span></label><input type="hidden" value="'.$meta['CategoryId'].'" id="CategoryId" name="CategoryId"><input type="hidden" value="'.$meta['CategoryThumbPath'].'" id="CategoryThumbPath" name="CategoryThumbPath"><input type="hidden" value="'.$meta['CategoryBannerPath'].'" id="CategoryBannerPath" name="CategoryBannerPath"><input type="text" class="form-control" value="'.$meta['CategoryName'].'" id="CategoryName" name="CategoryName"></div><div class="form-group m-b-20"><label>Category Parent<span class="text-danger">*</span></label><select class="form-control select2" name="CategoryParent" id="CategoryParent"><option value="mega">Has No Parent</option>'.$select_option.' </select></div></div></div><div class="col-lg-4"><div class="card-box"><div class="form-group m-b-20"><label>Category Thumbnail (400 by 400 px)<span class="text-danger"><img src="'.APP_IMG_PATH.'categories/' .$meta['CategoryThumbPath'].'" class="thumb-sm" alt=""></span></label><input type="file" class="form-control" name="CategoryThumbPath" id="CategoryThumbPath"></div><div class="form-group m-b-20"><label>Category Banner (800 by 400 px) <span class="text-danger"><img style="width:50px;height:21px;" src="'.APP_IMG_PATH.'categories/' .$meta['CategoryBannerPath'].'" class="thumb-sm" alt=""></span></label><input type="file" class="form-control" name="CategoryBannerPath" id="CategoryBannerPath"></div></div></div><div class="col-lg-4"><div class="card-box"><div class="form-group m-b-20"><label>Category Description <span class="text-danger">*</span></label><input type="text" class="form-control" value="'.$meta['CategoryDescription'].'" name="CategoryDescription" id="CategoryDescription"></div><div class="form-group m-b-20"><br><button type="button" onclick="updateCategory('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Update</button></div></div></div></div></form>';
        
        $tab_2 = '<div class="card-box table-responsive"><h4 class="m-t-0 header-title"><b>Products in this Category</b></h4><table id="datatable_buttons_modal" class="table table-striped table-bordered"><thead><tr><th>#Thumb</th><th>Name</th><th>Price</th></tr></thead><tbody>'.$body.' </tbody></table><script>$(".select2").select2();$("#datatable_buttons_modal").length && $("#datatable_buttons_modal").DataTable({dom:"Bfrtip",buttons:[{extend:"copy",className:"btn-sm"},{extend:"csv",className:"btn-sm"},{extend:"excel",className:"btn-sm"},{extend:"pdf",className:"btn-sm"},{extend:"print",className:"btn-sm"}],responsive:!0}) </script></div>';

        $data = ['tab1' => $tab_1, 'tab2' => $tab_2];
        exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;
    case 'update-category':
        try{
            $CategoryId = $_POST['CategoryId'];
            $CategoryThumbPath = $CategoryBannerPath = '';
            if(!empty($_FILES['CategoryThumbPath']['name']) && !empty($_FILES['CategoryBannerPath']['name'])){
                $util->ValidateExtension($util->FindExtension('CategoryThumbPath'));
                $util->ValidateImageDimension('CategoryThumbPath', 400,400);
                $util->ValidateUploadSize('CategoryThumbPath');
                $CategoryThumbPath = $CategoryId.'-thumb.'.$util->FindExtension('CategoryThumbPath');
                $util->UploadFile('CategoryThumbPath', APP_IMG_DIR.'categories/'.$CategoryThumbPath);

                $util->ValidateExtension($util->FindExtension('CategoryBannerPath'));
                $util->ValidateImageDimension('CategoryBannerPath', 800,400);
                $util->ValidateUploadSize('CategoryBannerPath');
                $CategoryBannerPath = $CategoryId.'-banner.'.$util->FindExtension('CategoryBannerPath');
                $util->UploadFile('CategoryBannerPath', APP_IMG_DIR.'categories/'.$CategoryBannerPath);
            }else{
                $CategoryThumbPath = $_POST['CategoryThumbPath'];
                $CategoryBannerPath = $_POST['CategoryBannerPath'];
            }

            $Connection = $util->CreateConnection();
            $c = new Category($Connection, $CategoryId, $_POST['CategoryName'], $_POST['CategoryParent'], $CategoryThumbPath, $CategoryBannerPath, $_POST['CategoryDescription'], 1);
            $c->ValidateFields();
            if($c->Update()){
                exit(json_encode(['MSG' => 'Category updated Successfully!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'create-user-item':
        try{
            $ProductId = $util->KeyGen(20);
            $Connection = $util->CreateConnection();
            $_POST['ProductDescription'] = $util->HtmlEncode($_POST['ProductDescription']);
            $_POST['ProductCategories'] = implode(',',$_POST['ProductCategories']);
            $_POST['ProductColors'] = implode(',',$_POST['ProductColors']);
            $product = new Product($Connection, $ProductId, $_POST['ProductName'], $_POST['ProductShortDescription'], $_POST['ProductDescription'], $_POST['ProductPrice'], $_POST['ProductDiscountType'], $_POST['ProductDiscountValue'], $_POST['ProductTags'], $_POST['ProductMetaDescription'], $_POST['ProductCategories'], $_POST['ProductCommisionType'], $_POST['ProductCommisionValue'], $_POST['ProductOwner'], $_POST['ProductShipper'], $_POST['Product3D'], $_POST['ProductColors'], 1);
            $product->ValidateFields();
            $product->Create();
            exit(json_encode(['MSG' => 'Item created!']));
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'update-product-item':
        try{
            $ProductId = $_POST['ProductId'];
            $Connection = $util->CreateConnection();
            $_POST['ProductDescription'] = $util->HtmlEncode($_POST['PDescription']);
            // exit(json_encode(['MSG' => $_POST['PDescription']]));
            $_POST['ProductCategories'] = implode(',',$_POST['ProductCategories']);
            $_POST['ProductColors'] = implode(',',$_POST['ProductColors']);
            $product = new Product($Connection, $ProductId, $_POST['ProductName'], $_POST['ProductShortDescription'], $_POST['ProductDescription'], $_POST['ProductPrice'], $_POST['ProductDiscountType'], $_POST['ProductDiscountValue'], $_POST['ProductTags'], $_POST['ProductMetaDescription'], $_POST['ProductCategories'], $_POST['ProductCommisionType'], $_POST['ProductCommisionValue'], $_POST['ProductOwner'], $_POST['ProductShipper'], $_POST['Product3D'], $_POST['ProductColors'], 1);
            $product->ValidateFields();
            $product->Update();
            exit(json_encode(['MSG' => 'Item Updated!']));
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'show-product-modal':
        $Connection = $util->CreateConnection();
        $category = new Category($Connection);
        $product = new Product($Connection);
        $gallery = new Gallery($Connection);
        $param = new Param($Connection);
        $size = new Size($Connection);
        $stock = new Stock($Connection);
        $color = new Color($Connection);
        $user = new User($Connection);
        //
        $meta = $product->FindById($_POST['ProductId']);
        $galleries = $gallery->FindByProduct($_POST['ProductId']);
        $sizes = $size->FindByProduct($_POST['ProductId']);
        $params = $param->FindByProduct($_POST['ProductId']);
        $stocks = $stock->FindByProduct($_POST['ProductId']);
        //
        $formid = "'update_product_form'";
        $shipper_select = $comm_select = $color_select = $discount_select = $categ_select = $vendor_select = '';
        //
        foreach( $util->Shippers() as $k => $v ):
            if($meta['ProductShipper'] == $k ){
                $shipper_select .= '<option selected value="'.$k.'">'.$v.'</option>';
            }else{
                $shipper_select .= '<option value="'.$k.'">'.$v.'</option>';
            }
        endforeach;

        foreach( $util->CommissionTypes() as $k => $v ):
            if($meta['ProductCommisionType'] == $k ){
                $comm_select .= '<option selected value="'.$k.'">'.$v.'</option>';
            }else{
                $comm_select .= '<option value="'.$k.'">'.$v.'</option>';
            }
        endforeach;

        foreach( $color->FindAll() as $co ):
            if(in_array($co['ColorId'], explode(',', $meta['ProductColors']))){
                $color_select .= '<option selected value="'.$co['ColorId'].'">'.$co['ColorName'].'</option>';
            }else{
                $color_select .= '<option value="'.$co['ColorId'].'">'.$co['ColorName'].'</option>';
            }
        endforeach;

        foreach( $util->DiscountTypes() as $k => $v ):
            if($meta['ProductDiscountType'] == $k ){
                $discount_select .= '<option selected value="'.$k.'">'.$v.'</option>';
            }else{
                $discount_select .= '<option value="'.$k.'">'.$v.'</option>';
            }
        endforeach;

        foreach( $category->FindByNoChildren() as $c ):
            $nomenclecture = $util->CategoryNaming($category, $c);
            if(in_array($c['CategoryId'], explode(',', $meta['ProductCategories']))){
                $categ_select .= '<option selected value="'.$c['CategoryId'].'">'.$nomenclecture.'</option>';
            }else{
                $categ_select .= '<option value="'.$c['CategoryId'].'">'.$nomenclecture.'</option>';
            }
        endforeach;

        foreach( $user->FindByType(4004) as $kv ):
            if($util->isAdmin()){
                if($meta['ProductOwner'] == $kv['UserId']){
                    $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                }else{
                    $vendor_select .= '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>'; 
                }
            }else{
                if($meta['ProductOwner'] == $kv['UserId'] && $_SESSION['usr']['UserId'] == $kv['UserId']){
                    $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                }
            }
        endforeach;
                                        
        $body_gallery = $body_size = $body_params = '';
        // $tbl_gallery = $tbl_size = $tbl_params = [];

        $tab_1 = '<!-- m-body -->
        <form method="post" id="update_product_form" enctype="multipart/form-data"><div class="modal-body"><div class="row"><div class="col-lg-12"><div class="card-box"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="succc" style="display:none;"></div><div class="alert alert-danger" id="errr" style="display:none;"></div></div><!-- Begin left box --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-4"><div class="form-group m-b-20"><input type="hidden" id="ProductId" name="ProductId" value="'.$meta['ProductId'].'"><input type="text" value="'.$meta['ProductName'].'" class="form-control" id="ProductName" name="ProductName" placeholder="Product name"></div></div><div class="col-lg-4"><div class="form-group m-b-20"><input type="number" min="1" value="'.$meta['ProductPrice'].'" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Price"></div></div><div class="col-lg-4"><div class="form-group m-b-20"><select class="form-control select2" name="ProductShipper" id="ProductShipper"><option value="nn">Shipper</option>'.$shipper_select.'</select></div></div></div><!-- end row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="ProductCommisionType" id="ProductCommisionType"><option value="nn">Commision Type</option>'.$comm_select.'</select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['ProductCommisionValue'].'" class="form-control" id="ProductCommisionValue" name="ProductCommisionValue" placeholder="Commission Value"></div></div></div><!-- end row --></div><!-- End left,begin middle --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="ProductDiscountType" id="ProductDiscountType"><option value="nn">Discount Type</option>'.$discount_select.'</select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['ProductDiscountValue'].'" class="form-control" id="ProductDiscountValue" name="ProductDiscountValue" placeholder="Discount Value"></div></div><div class="col-lg-12"><div class="form-group m-b-20"><input type="number" value="'.$meta['Product3D'].'" class="form-control" id="Product3D" name="Product3D" placeholder="3D Spinzam"></div></div></div></div><!-- End middle begin right --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-12"><div class="form-group m-b-20"><input type="text" value="'.$meta['ProductMetaDescription'].'" class="form-control" id="ProductMetaDescription" name="ProductMetaDescription" placeholder="Meta Description"></div><div class="form-group m-b-20"><select class="form-control select2" name="ProductOwner" id="ProductOwner"><option value="nn">Vendor/Merchant</option>'.$vendor_select.'</select></div></div></div></div><!-- end right --></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><label>Product Colors,Category,Tags<span class="text-danger">*</span></label><select class="form-control select2" name="ProductColors[]" id="ProductColors" multiple="multiple" data-placeholder="Product Available Colors">'.$color_select.'</select></div><div class="form-group m-b-20"><input type="text" value="'.$meta['ProductTags'].'" class="form-control" id="ProductTags" name="ProductTags" placeholder="Add Meta Tags" data-role="tagsinput"></div><div class="form-group m-b-20"><select class="form-control select2" name="ProductCategories[]" id="ProductCategories" multiple="multiple" data-placeholder="Select Categories for the item">'.$categ_select.'</select></div><div class="form-group m-b-20"><input type="text" value="'.$meta['ProductShortDescription'].'" class="form-control" id="ProductShortDescription" name="ProductShortDescription" placeholder="Short Description"></div><div class="form-group m-b-20"><br><button type="button" name="save-product" onclick="updateProduct('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Click To Save Changes</button></div></div><div class="col-lg-6"><div class="form-group m-b-20"><label>Product Description<span class="text-danger">*</span></label><textarea class="form-control" id="PDescription" name="PDescription">'.$util->HtmlDecode($meta['ProductDescription']).'</textarea></div></div></div></div></div></div><style>.select2-search__field{width:100%!important}</style><script src="assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script></div></form>
        <!-- end body -->';
        
        $galleryType_select = '';
        foreach( $util->GalleryTypes() as $k => $v ):
            $galleryType_select .= '<option value="'.$k.'">'.$v.'</option>';
        endforeach;
        $gformid = "'create_gallery'";
        foreach($galleries as $gal): 
                $specimen = '<img src="'.APP_IMG_PATH . 'items/'.$gal['GalleryPath'].'" class="thumb-sm" alt="">';
                if($gal['GalleryType'] == '5006')
                    $specimen = $gal['GalleryPath'];
                $gid = "'".$gal['GalleryId']."'";
                $body_gallery .= '<tr id="'.$gal['GalleryId'].'">';
                $body_gallery .= '<td>'.$util->GalleryName($gal['GalleryType']).'</td>';
                $body_gallery .= '<td>'.$specimen.'</td>';
                $body_gallery .= '<td><a onclick="DeleteGallery('.$gid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
                $body_gallery .= '</tr>';
        endforeach;
        $tab_2 = '
        <form method="post" id="create_gallery" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="sc" style="display:none;"></div>
                <div class="alert alert-danger" id="er" style="display:none;"></div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <input type="hidden" value="'.$_POST['ProductId'].'" id="GalleryProduct" name="GalleryProduct">
                        <label>Gallery Type<span class="text-danger">*</span></label>
                        <select class="form-control select2" name="GalleryType" id="GalleryType">
                            <option value="nn">Select</option>
                            '.$galleryType_select.'
                        </select>
                    </div>
                    <div class="form-group m-b-20">
                        <label>Gallery Image<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="GalleryPath" name="GalleryPath">
                    </div>
                    <div class="form-group m-b-20">
                        <label>If you selected Video above, fill in below<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="enter Youtube code or video full url" id="GalleryPath_v" name="GalleryPath_v">
                    </div>
                    <div class="form-group m-b-20">
                        <button type="button" onclick="CreateGallery('.$gformid.')" class="btn w-sm btn-default waves-effect waves-light">Create
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-box">
                <table id="hgjghkgjhhgg" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Specimen</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    '.$body_gallery.'
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </form>
        <script>$(".select2").select2();$("#hgjghkgjhhgg").length && $("#hgjghkgjhhgg").DataTable();</script>
        ';

        $SizeName_select = '';
        foreach( $util->SizeNames() as $v ):
            $SizeName_select .= '<option value="'.$v.'">'.$v.'</option>';
        endforeach;
        $sformid = "'create_size'";

        foreach($sizes as $saz): 
                $sid = "'".$saz['SizeId']."'";
                $body_size .= '<tr id="'.$saz['SizeId'].'">';
                $body_size .= '<td>'.$saz['SizeName'].'</td>';
                $body_size .= '<td>'.$saz['SizeValue'].'</td>';
                $body_size .= '<td><a onclick="DeleteSize('.$sid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
                $body_size .= '</tr>';
        endforeach;
        $tab_3 = '
        <form method="post" id="create_size" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="scc" style="display:none;"></div>
                <div class="alert alert-danger" id="eer" style="display:none;"></div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <input type="hidden" value="'.$_POST['ProductId'].'" id="SizeProduct" name="SizeProduct">
                        <label>Size Name/Type<span class="text-danger">*</span></label>
                        <select class="form-control select2" name="SizeName" id="SizeName">
                            <option value="nn">Select</option>
                            '.$SizeName_select.'
                        </select>
                    </div>
                    <div class="form-group m-b-20">
                        <label>Size Value<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="SizeValue" name="SizeValue">
                    </div>
                    <div class="form-group m-b-20">
                        <button type="button" onclick="CreateSize('.$sformid.')" class="btn w-sm btn-default waves-effect waves-light">Create
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-box">
                <table id="datatable_ab" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    '.$body_size.'
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </form>
        <script>$(".select2").select2();$("#datatable_ab").length && $("#datatable_ab").DataTable();</script>';

        
        $pformid = "'create_param'";

        foreach($params as $pa): 
                $pid = "'".$pa['ParamId']."'";
                $body_params .= '<tr id="'.$pa['ParamId'].'">';
                $body_params .= '<td>'.$pa['Width'].'</td>';
                $body_params .= '<td>'.$pa['Weight'].'</td>';
                $body_params .= '<td>'.$pa['Length'].'</td>';
                $body_params .= '<td>'.$pa['Height'].'</td>';
                $body_params .= '<td><a onclick="DeleteParam('.$pid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
                $body_params .= '</tr>';
        endforeach;
        $tab_4 = '
        <form method="post" id="create_param" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="sccc" style="display:none;"></div>
                <div class="alert alert-danger" id="eeer" style="display:none;"></div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <input type="hidden" value="'.$_POST['ProductId'].'" id="ParamProduct" name="ParamProduct">
                        <label>Width(cm)<span class="text-danger">*</span></label>
                        <input type="number" min="1" step="0.1" class="form-control" id="Width" name="Width">
                    </div>
                    <div class="form-group m-b-20">
                        <label>Weight(kg)<span class="text-danger">*</span></label>
                        <input type="number" min="1" step="0.1" class="form-control" id="Weight" name="Weight">
                    </div>
                    <div class="form-group m-b-20">
                        <label>Length(cm)<span class="text-danger">*</span></label>
                        <input type="number" min="1" step="0.1" class="form-control" id="Length" name="Length">
                    </div>
                    <div class="form-group m-b-20">
                        <label>Height(cm)<span class="text-danger">*</span></label>
                        <input type="number" min="1" step="0.1" class="form-control" id="Height" name="Height">
                    </div>
                    <div class="form-group m-b-20">
                        <button type="button" onclick="CreateParam('.$pformid.')" class="btn w-sm btn-default waves-effect waves-light">Create
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-box">
                <table id="datatable_abc" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Width(cm)</th>
                        <th>Weight(kg)</th>
                        <th>Length(cm)</th>
                        <th>Height(cm)</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    '.$body_params.'
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </form>
        <script>$(".select2").select2();$("#datatable_abc").length && $("#datatable_abc").DataTable();</script>';
        
        $ActionFunc = 'CreateStock';
        $act = 'Create Stock';
        $stockidfield = '';
        $stformid = "'manage_item_stock'";
        if(!empty($stocks)){
            $stockidfield = '<input type="hidden" value="'.$stocks['StockId'].'" id="StockId" name="StockId">';
            $ActionFunc = 'UpdateStock';
            $act = 'Update Stock';
        }
        $tab_5 = '
        <form method="post" id="manage_item_stock" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="scccc" style="display:none;"></div>
                <div class="alert alert-danger" id="eeeer" style="display:none;"></div>
            </div>
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        '.$stockidfield.'
                        <input type="hidden" value="'.$_POST['ProductId'].'" id="StockProduct" name="StockProduct">
                        <label>Stock Level<span class="text-danger">*</span></label>
                        <input type="number" value="'.$stocks['Stock'].'" min="1" class="form-control" id="Stock" name="Stock">
                    </div>
                    <div class="form-group m-b-20">
                        <label>Stock Warning Level<span class="text-danger">*</span></label>
                        <input type="number" value="'.$stocks['StockWarnLevel'].'" min="1" class="form-control" id="StockWarnLevel" name="StockWarnLevel">
                    </div>
                    <div class="form-group m-b-20">
                        <label>Out Of Stock Level<span class="text-danger">*</span></label>
                        <input type="number" value="'.$stocks['StockOutOfStockLevel'].'" min="1" class="form-control" id="StockOutOfStockLevel" name="StockOutOfStockLevel">
                    </div>
                    <div class="form-group m-b-20">
                        <button type="button" onclick="'.$ActionFunc.'('.$stformid.')" class="btn w-sm btn-default waves-effect waves-light">'.$act.'
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>';
        $data = [
            'item' => $meta['ProductName'], 
            'tab1' => $tab_1, 'tab2' => $tab_2, 'tab3' => $tab_3, 'tab4' => $tab_4, 'tab5' => $tab_5
        ];
        exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;

    case 'create-gallery-item':
        try{
            $GalleryId = $util->KeyGen(18);
            $Connection = $util->CreateConnection();
            $GalleryPath = '';
            if($_POST['GalleryType'] != '5006'){
                $util->ValidateExtension($util->FindExtension('GalleryPath'));
                $gtype = '';
                switch($_POST['GalleryType']){
                    case '5003':
                        $util->ValidateImageDimension('GalleryPath', 350,350);
                        $gtype = 'thumb';
                    break;
                    case '5004':
                        $util->ValidateImageDimension('GalleryPath', 1920,630);
                        $gtype = 'slider';
                    break;
                    case '5005':
                        $util->ValidateImageDimension('GalleryPath', 600,600);
                        $gtype = 'banner';
                    break;
                }
                $util->ValidateUploadSize('GalleryPath');
                $GalleryPath = $GalleryId.'-'.$gtype.'.'.$util->FindExtension('GalleryPath');
                $util->UploadFile('GalleryPath', APP_IMG_DIR.'items/'.$GalleryPath);
            }else{
                $GalleryPath = $_POST['GalleryPath_v'];
            }
            $g = new Gallery($Connection, $GalleryId, $_POST['GalleryType'], $GalleryPath, $_POST['GalleryProduct'], 1);
            $g->ValidateFields();
            if($g->Create()){
                exit(json_encode(['MSG' => 'Item Created!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'delete-gallery-item':
    try{
        $Connection = $util->CreateConnection();
        $gallery = new Gallery($Connection);
        if( $gallery->Delete($_POST['GalleryId']) ){
            exit(json_encode(['MSG' => 'Gallery deleted!']));
        }
    }catch(Exception $e ){
        exit(json_encode(['ERR' => $e->getMessage()]));
    }
    break;
    case 'create-size-item':
        try{
            $SizeId = $util->KeyGen(9);
            $Connection = $util->CreateConnection();
            $s = new Size($Connection, $SizeId, $_POST['SizeName'], $_POST['SizeValue'], $_POST['SizeProduct'], 1);
            $s->ValidateFields();
            if($s->Create()){
                exit(json_encode(['MSG' => 'Size item Created!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'delete-size-item':
    try{
        $Connection = $util->CreateConnection();
        $s = new Size($Connection);
        if( $s->Delete($_POST['SizeId']) ){
            exit(json_encode(['MSG' => 'Size deleted!']));
        }
    }catch(Exception $e ){
        exit(json_encode(['ERR' => $e->getMessage()]));
    }
    break;
    case 'create-param-item':
        try{
            $ParamId = $util->KeyGen(7);
            $Connection = $util->CreateConnection();
            $p = new Param($Connection, $ParamId, $_POST['ParamProduct'], $_POST['Width'], $_POST['Weight'], $_POST['Length'], $_POST['Height'], 1);
            $p->ValidateFields();
            if($p->Create()){
                exit(json_encode(['MSG' => 'Param item Created!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'delete-param-item':
    try{
        $Connection = $util->CreateConnection();
        $p = new Param($Connection);
        if( $p->Delete($_POST['ParamId']) ){
            exit(json_encode(['MSG' => 'Param deleted!']));
        }
    }catch(Exception $e ){
        exit(json_encode(['ERR' => $e->getMessage()]));
    }
    break;
    case 'create-stock-item':
        try{
            $StockId = $util->KeyGen(21);
            $Connection = $util->CreateConnection();
            $s = new Stock($Connection, $StockId, $_POST['StockProduct'], $_POST['Stock'], $_POST['StockWarnLevel'], $_POST['StockOutOfStockLevel'], 1);
            $s->ValidateFields();
            if($s->Create()){
                exit(json_encode(['MSG' => 'Stock item Created!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'update-stock-item':
        try{
            $StockId = $_POST['StockId'];
            $Connection = $util->CreateConnection();
            $s = new Stock($Connection, $StockId, $_POST['StockProduct'], $_POST['Stock'], $_POST['StockWarnLevel'], $_POST['StockOutOfStockLevel'], 1);
            $s->ValidateFields();
            if($s->Update()){
                exit(json_encode(['MSG' => 'Stock item Updated!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'show-roomcategory-modal':
        $Connection = $util->CreateConnection();
        $rc = new RoomCategory($Connection);
        $meta = $rc->FindById($_POST['RoomCategoryId']);
        $formid = "'updateRoomCategoryForm'";
       
        $tab_1 = '
        <form method="post" enctype="multipart/form-data" id="updateRoomCategoryForm">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="succ" style="display:none;"></div>
                <div class="alert alert-danger" id="err" style="display:none;"></div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <label>Room Category name <span class="text-danger">*</span></label>
                        <input type="hidden" value="'.$meta['RoomCategoryId'].'" id="RoomCategoryId" name="RoomCategoryId">
                        <input type="text" class="form-control" value="'.$meta['RoomCategoryName'].'" id="RoomCategoryName" name="RoomCategoryName">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <label>Room Category Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="'.$meta['RoomCategoryDescription'].'" id="RoomCategoryDescription" name="RoomCategoryDescription">
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <br>
                        <button type="button" onclick="updateRoomCategory('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>';
        
        $tab_2 = '<div class="card-box table-responsive"></div>';

        $data = ['tab1' => $tab_1, 'tab2' => $tab_2];
        exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;
    case 'update-roomcategory':
        try{
            $RoomCategoryId = $_POST['RoomCategoryId'];
            $Connection = $util->CreateConnection();
            $rc = new RoomCategory($Connection, $RoomCategoryId, $_POST['RoomCategoryName'], $_POST['RoomCategoryDescription'], 1);
            $rc->ValidateFields();
            if($rc->Update()){
                exit(json_encode(['MSG' => 'Room category updated Successfully!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'show-pack-modal':
        $Connection = $util->CreateConnection();
        $pack = new Pack($Connection);
        $meta = $pack->FindById($_POST['PackId']);
        $formid = "'updatePackForm'";
       
        $tab_1 = '
        <form method="post" enctype="multipart/form-data" id="updatePackForm">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" id="succ" style="display:none;"></div>
                <div class="alert alert-danger" id="err" style="display:none;"></div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <label>Pack name <span class="text-danger">*</span></label>
                        <input type="hidden" value="'.$meta['PackId'].'" id="PackId" name="PackId">
                        <input type="text" class="form-control" value="'.$meta['PackName'].'" id="PackName" name="PackName">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <div class="form-group m-b-20">
                        <br>
                        <button type="button" onclick="updatePack('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>';
        
        $tab_2 = '<div class="card-box table-responsive"></div>';

        $data = ['tab1' => $tab_1, 'tab2' => $tab_2];
        exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;
    case 'update-pack':
        try{
            $PackId = $_POST['PackId'];
            $Connection = $util->CreateConnection();
            $c = new Pack($Connection, $PackId, $_POST['PackName'], 1);
            $c->ValidateFields();
            if($c->Update()){
                exit(json_encode(['MSG' => 'Pack updated Successfully!']));
            }
        }catch(Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'create-user-room':
        try{
            $Connection = $util->CreateConnection();
            $RoomId = $util->KeyGen(15);
            $_POST['RoomDescription'] = $util->HtmlEncode($_POST['RoomDescription']);
            $_POST['RoomPacks'] = implode(',',$_POST['RoomPacks']);
            $room = new Room($Connection,$RoomId,$_POST['RoomName'],$_POST['RoomShortDescription'],$_POST['RoomDescription'],$_POST['RoomPrice'],$_POST['RoomDiscountType'],$_POST['RoomDiscountValue'],$_POST['RoomPacks'],$_POST['RoomMetaDescription'],$_POST['RoomCategory'],$_POST['RoomCommisionType'],$_POST['RoomCommisionValue'],$_POST['RoomOwner'],$_POST['Room3D'],1);
            $room->ValidateFields();
            if($room->Create()){
                exit(json_encode(['MSG' => 'Room created Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Room creation failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
    case 'show-Room-modal':
    $Connection = $util->CreateConnection();
    $category = new RoomCategory($Connection);
    $room = new Room($Connection);
    $gallery = new Gallery($Connection);
    $param = new Param($Connection);
    $size = new Size($Connection);
    $stock = new Stock($Connection);
    $user = new User($Connection);
    $pack = new Pack($Connection);
    //
    $meta = $room->FindById($_POST['RoomId']);
    $galleries = $gallery->FindByProduct($_POST['RoomId']);
    $sizes = $size->FindByProduct($_POST['RoomId']);
    $params = $param->FindByProduct($_POST['RoomId']);
    $stocks = $stock->FindByProduct($_POST['RoomId']);
    //
    $formid = "'update_Room_form'";
    $comm_select = $pack_select = $discount_select = $categ_select = $vendor_select = '';

    foreach( $util->CommissionTypes() as $k => $v ):
        if($meta['RoomCommisionType'] == $k ){
            $comm_select .= '<option selected value="'.$k.'">'.$v.'</option>';
        }else{
            $comm_select .= '<option value="'.$k.'">'.$v.'</option>';
        }
    endforeach;

    foreach( $pack->FindAll() as $p ):
        if(in_array($p['PackId'], explode(',', $meta['RoomPacks']))){
            $pack_select .= '<option selected value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
        }else{
            $pack_select .= '<option value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
        }
    endforeach;

    foreach( $util->DiscountTypes() as $k => $v ):
        if($meta['RoomDiscountType'] == $k ){
            $discount_select .= '<option selected value="'.$k.'">'.$v.'</option>';
        }else{
            $discount_select .= '<option value="'.$k.'">'.$v.'</option>';
        }
    endforeach;

    foreach( $category->FindAll() as $c ):
        if( $c['RoomCategoryId'] == $meta['RoomCategory'] ){
            $categ_select .= '<option selected value="'.$c['RoomCategoryId'].'">'.$c['RoomCategoryName'].'</option>';
        }else{
            $categ_select .= '<option value="'.$c['RoomCategoryId'].'">'.$c['RoomCategoryName'].'</option>';
        }
    endforeach;

    foreach( $user->FindByType(4004) as $kv ):
        if($util->isAdmin()){
            if($meta['RoomOwner'] == $kv['UserId']){
                $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
            }else{
                $vendor_select .= '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>'; 
            }
        }else{
            if($meta['RoomOwner'] == $kv['UserId'] && $_SESSION['usr']['UserId'] == $kv['UserId']){
                $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
            }
        }
    endforeach;
                                    
    $body_gallery = $body_size = $body_params = '';
    // $tbl_gallery = $tbl_size = $tbl_params = [];

    $tab_1 = '<!-- m-body -->
    <form method="post" id="update_Room_form" enctype="multipart/form-data"><div class="modal-body"><div class="row"><div class="col-lg-12"><div class="card-box"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="succc" style="display:none;"></div><div class="alert alert-danger" id="errr" style="display:none;"></div></div><!-- Begin left box --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="hidden" id="RoomId" name="RoomId" value="'.$meta['RoomId'].'"><input type="text" value="'.$meta['RoomName'].'" class="form-control" id="RoomName" name="RoomName" placeholder="Room name"></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="number" min="1" value="'.$meta['RoomPrice'].'" class="form-control" id="RoomPrice" name="RoomPrice" placeholder="Price"></div></div></div><!-- end row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="RoomCommisionType" id="RoomCommisionType"><option value="nn">Commision Type</option>'.$comm_select.'</select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['RoomCommisionValue'].'" class="form-control" id="RoomCommisionValue" name="RoomCommisionValue" placeholder="Commission Value"></div></div></div><!-- end row --></div><!-- End left,begin middle --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="RoomDiscountType" id="RoomDiscountType"><option value="nn">Discount Type</option>'.$discount_select.'</select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['RoomDiscountValue'].'" class="form-control" id="RoomDiscountValue" name="RoomDiscountValue" placeholder="Discount Value"></div></div><div class="col-lg-12"><div class="form-group m-b-20"><input type="number" value="'.$meta['Room3D'].'" class="form-control" id="Room3D" name="Room3D" placeholder="3D Spinzam"></div></div></div></div><!-- End middle begin right --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-12"><div class="form-group m-b-20"><input type="text" value="'.$meta['RoomMetaDescription'].'" class="form-control" id="RoomMetaDescription" name="RoomMetaDescription" placeholder="Meta Description"></div><div class="form-group m-b-20"><select class="form-control select2" name="RoomOwner" id="RoomOwner"><option value="nn">Vendor/Merchant</option>'.$vendor_select.'</select></div></div></div></div><!-- end right --></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><label>Room Classification<span class="text-danger">*</span></label><select class="form-control select2" name="RoomCategory" id="RoomCategory" data-placeholder="Room Classes">'.$categ_select.'</select></div><div class="form-group m-b-20"><select class="form-control select2" name="RoomPacks[]" id="RoomPacks" multiple="multiple" data-placeholder="Select Room Packs">'.$pack_select.'</select></div><div class="form-group m-b-20"><input type="text" value="'.$meta['RoomShortDescription'].'" class="form-control" id="RoomShortDescription" name="RoomShortDescription" placeholder="Short Description"></div><div class="form-group m-b-20"><br><button type="button" name="save-Room" onclick="updateRoom('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Click To Save Changes</button></div></div><div class="col-lg-6"><div class="form-group m-b-20"><label>Room Description<span class="text-danger">*</span></label><textarea class="form-control" id="RDescription" name="RDescription">'.$util->HtmlDecode($meta['RoomDescription']).'</textarea></div></div></div></div></div></div><style>.select2-search__field{width:100%!important}</style></div></form>
    <!-- end body -->';
    
    $galleryType_select = '';
    foreach( $util->GalleryTypes() as $k => $v ):
        $galleryType_select .= '<option value="'.$k.'">'.$v.'</option>';
    endforeach;
    $gformid = "'create_gallery'";
    foreach($galleries as $gal): 
            $specimen = '<img src="'.APP_IMG_PATH . 'items/'.$gal['GalleryPath'].'" class="thumb-sm" alt="">';
            if($gal['GalleryType'] == '5006')
                $specimen = $gal['GalleryPath'];
            $gid = "'".$gal['GalleryId']."'";
            $body_gallery .= '<tr id="'.$gal['GalleryId'].'">';
            $body_gallery .= '<td>'.$util->GalleryName($gal['GalleryType']).'</td>';
            $body_gallery .= '<td>'.$specimen.'</td>';
            $body_gallery .= '<td><a onclick="DeleteGallery('.$gid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
            $body_gallery .= '</tr>';
    endforeach;
    $tab_2 = '
    <form method="post" id="create_gallery" enctype="multipart/form-data"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="sc" style="display:none;"></div><div class="alert alert-danger" id="er" style="display:none;"></div></div><div class="col-lg-6"><div class="card-box"><div class="form-group m-b-20"><input type="hidden" value="'.$_POST['RoomId'].'" id="GalleryProduct" name="GalleryProduct"><label>Gallery Type<span class="text-danger">*</span></label><select class="form-control select2" name="GalleryType" id="GalleryType"><option value="nn">Select</option>'.$galleryType_select.' </select></div><div class="form-group m-b-20"><label>Gallery Image<span class="text-danger">*</span></label><input type="file" class="form-control" id="GalleryPath" name="GalleryPath"></div><div class="form-group m-b-20"><label>If you selected Video above,fill in below<span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="enter Youtube code or video full url" id="GalleryPath_v" name="GalleryPath_v"></div><div class="form-group m-b-20"><button type="button" onclick="CreateGallery('.$gformid.')" class="btn w-sm btn-default waves-effect waves-light">Create </button></div></div></div><div class="col-lg-6"><div class="card-box"><table id="hgjghkgjhhgg" class="table table-striped table-bordered"><thead><tr><th>Type</th><th>Specimen</th><th>Remove</th></tr></thead><tbody>'.$body_gallery.' </tbody></table></div></div></div></form><script>$(".select2").select2();$("#hgjghkgjhhgg").length && $("#hgjghkgjhhgg").DataTable();</script>
    ';

    $SizeName_select = '';
    foreach( $util->SizeNames() as $v ):
        $SizeName_select .= '<option value="'.$v.'">'.$v.'</option>';
    endforeach;
    $sformid = "'create_size'";

    foreach($sizes as $saz): 
            $sid = "'".$saz['SizeId']."'";
            $body_size .= '<tr id="'.$saz['SizeId'].'">';
            $body_size .= '<td>'.$saz['SizeName'].'</td>';
            $body_size .= '<td>'.$saz['SizeValue'].'</td>';
            $body_size .= '<td><a onclick="DeleteSize('.$sid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
            $body_size .= '</tr>';
    endforeach;
    $tab_3 = '<form method="post" id="create_size" enctype="multipart/form-data"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="scc" style="display:none;"></div><div class="alert alert-danger" id="eer" style="display:none;"></div></div><div class="col-lg-6"><div class="card-box"><div class="form-group m-b-20"><input type="hidden" value="'.$_POST['RoomId'].'" id="SizeProduct" name="SizeProduct"><label>Size Name/Type<span class="text-danger">*</span></label><select class="form-control select2" name="SizeName" id="SizeName"><option value="nn">Select</option>'.$SizeName_select.' </select></div><div class="form-group m-b-20"><label>Size Value<span class="text-danger">*</span></label><input type="text" class="form-control" id="SizeValue" name="SizeValue"></div><div class="form-group m-b-20"><button type="button" onclick="CreateSize('.$sformid.')" class="btn w-sm btn-default waves-effect waves-light">Create </button></div></div></div><div class="col-lg-6"><div class="card-box"><table id="datatable_ab" class="table table-striped table-bordered"><thead><tr><th>Name</th><th>Value</th><th>Remove</th></tr></thead><tbody>'.$body_size.' </tbody></table></div></div></div></form><script>$(".select2").select2();$("#datatable_ab").length && $("#datatable_ab").DataTable();</script>';

    $ActionFunc = 'CreateStock';
    $act = 'Create Stock';
    $stockidfield = '';
    $stformid = "'manage_item_stock'";
    if(!empty($stocks)){
        $stockidfield = '<input type="hidden" value="'.$stocks['StockId'].'" id="StockId" name="StockId">';
        $ActionFunc = 'UpdateStock';
        $act = 'Update Stock';
    }
    $tab_4 = '
    <form method="post" id="manage_item_stock" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success" id="scccc" style="display:none;"></div>
            <div class="alert alert-danger" id="eeeer" style="display:none;"></div>
        </div>
        <div class="col-lg-12">
            <div class="card-box">
                <div class="form-group m-b-20">
                    '.$stockidfield.'
                    <input type="hidden" value="'.$_POST['RoomId'].'" id="StockProduct" name="StockProduct">
                    <label>Rooms Available<span class="text-danger">*</span></label>
                    <input type="number" value="'.$stocks['Stock'].'" min="1" class="form-control" id="Stock" name="Stock">
                </div>
                <div class="form-group m-b-20">
                    <label>Availability Warning level<span class="text-danger">*</span></label>
                    <input type="number" value="'.$stocks['StockWarnLevel'].'" min="1" class="form-control" id="StockWarnLevel" name="StockWarnLevel">
                </div>
                <div class="form-group m-b-20">
                    <label>Unavailable level<span class="text-danger">*</span></label>
                    <input type="number" value="'.$stocks['StockOutOfStockLevel'].'" min="1" class="form-control" id="StockOutOfStockLevel" name="StockOutOfStockLevel">
                </div>
                <div class="form-group m-b-20">
                    <button type="button" onclick="'.$ActionFunc.'('.$stformid.')" class="btn w-sm btn-default waves-effect waves-light">'.$act.'
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>';
    $data = [
        'item' => $meta['RoomName'], 
        'tab1' => $tab_1, 'tab2' => $tab_2, 'tab3' => $tab_3, 'tab4' => $tab_4
    ];
    exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;
    case 'update-Room-item':
        try{
            $Connection = $util->CreateConnection();
            $RoomId = $_POST['RoomId'];
            $_POST['RoomDescription'] = $util->HtmlEncode($_POST['RDescription']);
            $_POST['RoomPacks'] = implode(',',$_POST['RoomPacks']);
            $room = new Room($Connection,$RoomId,$_POST['RoomName'],$_POST['RoomShortDescription'],$_POST['RoomDescription'],$_POST['RoomPrice'],$_POST['RoomDiscountType'],$_POST['RoomDiscountValue'],$_POST['RoomPacks'],$_POST['RoomMetaDescription'],$_POST['RoomCategory'],$_POST['RoomCommisionType'],$_POST['RoomCommisionValue'],$_POST['RoomOwner'],$_POST['Room3D'],1);
            $room->ValidateFields();
            if($room->Update()){
                exit(json_encode(['MSG' => 'Room updated Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Room update failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'create-user-car':
        try{
            $Connection = $util->CreateConnection();
            $CarId = $util->KeyGen(25);
            $_POST['CarDescription'] = $util->HtmlEncode($_POST['CarDescription']);
            $_POST['CarPack'] = implode(',',$_POST['CarPack']);
            $car = new Car($Connection,$CarId,$_POST['CarName'],$_POST['CarPlateNumber'],$_POST['CarPrice'],$_POST['CarBrand'],$_POST['CarDiscountType'],$_POST['CarDiscountValue'],$_POST['CarCommisionType'],$_POST['CarCommisionValue'],$_POST['CarPack'],$_POST['CarMetaDescription'],$_POST['CarDescription'],$_POST['CarOwner'],$_POST['Car3D'],1);
            $car->ValidateFields();
            if($car->Create()){
                exit(json_encode(['MSG' => 'Car created Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Car creation failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
    case 'show-car-modal':
        $Connection = $util->CreateConnection();
        $gallery = new Gallery($Connection);
        $user = new User($Connection);
        $pack = new Pack($Connection);
        $car = new Car($Connection);
        //
        $meta = $car->FindById($_POST['CarId']);
        $galleries = $gallery->FindByProduct($_POST['CarId']);
        //
        $formid = "'create_car_form'";
        $comm_select = $pack_select = $discount_select = $categ_select = $vendor_select = '';

        foreach( $util->CommissionTypes() as $k => $v ):
            if($meta['CarCommisionType'] == $k ){
                $comm_select .= '<option selected value="'.$k.'">'.$v.'</option>';
            }else{
                $comm_select .= '<option value="'.$k.'">'.$v.'</option>';
            }
        endforeach;

        foreach( $pack->FindAll() as $p ):
            if(in_array($p['PackId'], explode(',', $meta['CarPack']))){
                $pack_select .= '<option selected value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
            }else{
                $pack_select .= '<option value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
            }
        endforeach;

        foreach( $util->DiscountTypes() as $k => $v ):
            if($meta['CarDiscountType'] == $k ){
                $discount_select .= '<option selected value="'.$k.'">'.$v.'</option>';
            }else{
                $discount_select .= '<option value="'.$k.'">'.$v.'</option>';
            }
        endforeach;
        
        foreach( $user->FindByType(4004) as $kv ):
            if($util->isAdmin()){
                if($meta['CarOwner'] == $kv['UserId']){
                    $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                }else{
                    $vendor_select .= '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>'; 
                }
            }else{
                if($meta['CarOwner'] == $kv['UserId'] && $_SESSION['usr']['UserId'] == $kv['UserId']){
                    $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                }
            }
        endforeach;
                                        
        $body_gallery = $body_size = $body_params = '';
        // $tbl_gallery = $tbl_size = $tbl_params = [];

        $tab_1 = '<form method="post" id="create_car_form" enctype="multipart/form-data"><div class="modal-body"><div class="row"><div class="col-lg-12"><div class="card-box"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="succc" style="display:none;"></div><div class="alert alert-danger" id="errr" style="display:none;"></div></div><!-- Begin left box --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="hidden" id="CarId" name="CarId" value="'.$meta['CarId'].'"><input type="text" value="'.$meta['CarName'].'" class="form-control" id="CarName" name="CarName" placeholder="Car name"></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['CarPlateNumber'].'" class="form-control" id="CarPlateNumber" name="CarPlateNumber" placeholder="Car License Plate Number"></div></div></div><!-- end row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['CarPrice'].'" class="form-control" id="CarPrice" name="CarPrice" placeholder="Car Hire/Rent Price"></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['CarBrand'].'" class="form-control" id="CarBrand" name="CarBrand" placeholder="CarBrand"></div></div></div><!-- end row --></div><!-- End left,begin middle --><div class="col-lg-8"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="CarCommisionType" id="CarCommisionType"><option value="nn">Commision Type</option>'.$comm_select.' </select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['CarCommisionValue'].'" class="form-control" id="CarCommisionValue" name="CarCommisionValue" placeholder="Commission Value"></div></div></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="number" value="'.$meta['Car3D'].'" class="form-control" id="Car3D" name="Car3D" placeholder="3D Spinzam"></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['CarMetaDescription'].'" class="form-control" id="CarMetaDescription" name="CarMetaDescription" placeholder="Meta Description"></div></div></div></div><!-- End middle begin right --></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><label>Discounts,Vendor,Packs<span class="text-danger">*</span></label><select class="form-control select2" name="CarDiscountType" id="CarDiscountType"><option value="nn">Discount Type</option>'.$discount_select.' </select></div><div class="form-group m-b-20"><input type="text" value="'.$meta['CarDiscountValue'].'" class="form-control" id="CarDiscountValue" name="CarDiscountValue" placeholder="Discount Value"></div><div class="form-group m-b-20"><select class="form-control select2" name="CarOwner" id="CarOwner"><option value="nn">Vendor/Merchant</option>'.$vendor_select.' </select></div><div class="form-group m-b-20"><select class="form-control select2" name="CarPack[]" id="CarPack" multiple="multiple" data-placeholder="Select Car Packs">'.$pack_select.' </select></div><div class="form-group m-b-20"><button type="button" name="save-Car" onclick="updateCar('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Save Changes</button></div></div><div class="col-lg-6"><div class="form-group m-b-20"><label>Car Description<span class="text-danger">*</span></label><textarea class="form-control" id="CDescription" name="CDescription">'.$util->HtmlDecode($meta['CarDescription']).'</textarea></div></div></div></div></div></div><style>.select2-search__field{width:100%!important}</style></div></form><script>$(".select2").select2();$("#datatable_ab").length && $("#datatable_ab").DataTable();</script>';
        
        $galleryType_select = '';
        foreach( $util->GalleryTypes() as $k => $v ):
            $galleryType_select .= '<option value="'.$k.'">'.$v.'</option>';
        endforeach;
        $gformid = "'create_gallery'";
        foreach($galleries as $gal): 
                $specimen = '<img src="'.APP_IMG_PATH . 'items/'.$gal['GalleryPath'].'" class="thumb-sm" alt="">';
                if($gal['GalleryType'] == '5006')
                    $specimen = $gal['GalleryPath'];
                $gid = "'".$gal['GalleryId']."'";
                $body_gallery .= '<tr id="'.$gal['GalleryId'].'">';
                $body_gallery .= '<td>'.$util->GalleryName($gal['GalleryType']).'</td>';
                $body_gallery .= '<td>'.$specimen.'</td>';
                $body_gallery .= '<td><a onclick="DeleteGallery('.$gid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
                $body_gallery .= '</tr>';
        endforeach;
        $tab_2 = '
        <form method="post" id="create_gallery" enctype="multipart/form-data"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="sc" style="display:none;"></div><div class="alert alert-danger" id="er" style="display:none;"></div></div><div class="col-lg-6"><div class="card-box"><div class="form-group m-b-20"><input type="hidden" value="'.$_POST['CarId'].'" id="GalleryProduct" name="GalleryProduct"><label>Gallery Type<span class="text-danger">*</span></label><select class="form-control select2" name="GalleryType" id="GalleryType"><option value="nn">Select</option>'.$galleryType_select.' </select></div><div class="form-group m-b-20"><label>Gallery Image<span class="text-danger">*</span></label><input type="file" class="form-control" id="GalleryPath" name="GalleryPath"></div><div class="form-group m-b-20"><label>If you selected Video above,fill in below<span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="enter Youtube code or video full url" id="GalleryPath_v" name="GalleryPath_v"></div><div class="form-group m-b-20"><button type="button" onclick="CreateGallery('.$gformid.')" class="btn w-sm btn-default waves-effect waves-light">Create </button></div></div></div><div class="col-lg-6"><div class="card-box"><table id="hgjghkgjhhgg" class="table table-striped table-bordered"><thead><tr><th>Type</th><th>Specimen</th><th>Remove</th></tr></thead><tbody>'.$body_gallery.' </tbody></table></div></div></div></form><script>$(".select2").select2();$("#hgjghkgjhhgg").length && $("#hgjghkgjhhgg").DataTable();</script>
        ';
        $tab_3 = '';
        $data = [
            'item' => $meta['CarName'], 
            'tab1' => $tab_1, 'tab2' => $tab_2, 'tab3' => $tab_3
        ];
        exit(json_encode(['MSG' => 'Success!', 'data' => $data]));
    break;
    case 'update-car-item':
        try{
            $Connection = $util->CreateConnection();
            $CarId = $_POST['CarId'];
            $_POST['CarDescription'] = $util->HtmlEncode($_POST['CDescription']);
            $_POST['CarPack'] = implode(',',$_POST['CarPack']);
            $car = new Car($Connection,$CarId,$_POST['CarName'],$_POST['CarPlateNumber'],$_POST['CarPrice'],$_POST['CarBrand'],$_POST['CarDiscountType'],$_POST['CarDiscountValue'],$_POST['CarCommisionType'],$_POST['CarCommisionValue'],$_POST['CarPack'],$_POST['CarMetaDescription'],$_POST['CarDescription'],$_POST['CarOwner'],$_POST['Car3D'],1);
            $car->ValidateFields();
            if($car->Update()){
                exit(json_encode(['MSG' => 'Car updated Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Car update failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
    case 'newsletter-sbscrb':
    $email = $_POST['email'];
    // echo $email;
    try{
        $util->ValidateEmail($email);
        $util->SubscribeNews([$email]);
        exit(json_encode(['MSG' => 'Subscribed!']));
    }catch(Exception $e ){
        exit(json_encode(['ERR' => $e->getMessage()]));
    }
    break;
    case 'wish-list':
     try{
        $Connection = $util->CreateConnection();
        $product = new Product($Connection);
        if(!isset($_SESSION['usr']['UserId'])){
            exit(json_encode(['ERR' => 'You must login to complete this action']));
        }
        if($_POST['t'] == '1' ){
            if($product->Wish([$_POST['item'], $_SESSION['usr']['UserId']])){
                //if adding to wishlist means they viewed
                $product->View([$_POST['item'], $_SESSION['usr']['UserId']]);
                exit(json_encode(['MSG' => 'Added to wishlist successfully!']));
            }
        }elseif($_POST['t'] == '2'){
            if($product->View([$_POST['item'], $_SESSION['usr']['UserId']])){
                exit(json_encode(['MSG' => 'Liked item successfully!']));
            }
        }else{
            exit(json_encode(['ERR' => 'Err:- Unknown action!']));
        }
     }catch(Exception $e ){
         exit(json_encode(['ERR' => $e->getMessage()]));
     }
    break;
    case 'review-item':
     try{
         if($_POST['c'] === 'undefined'){
             $_POST['c'] = 'this is worthy a referral';
         }
        $Connection = $util->CreateConnection();
        $product = new Product($Connection);
        if(!isset($_SESSION['usr']['UserId'])){
            exit(json_encode(['ERR' => 'You must login to complete this action']));
        }
        $input_data = [$_POST['r'],$_POST['c'],$_POST['item'], $_SESSION['usr']['UserId']];
        if($product->Rate($input_data)){
            //if rating means they viewed
            $product->View([$_POST['item'], $_SESSION['usr']['UserId']]);
            exit(json_encode(['MSG' => 'Rated successfully!']));
        }
     }catch(Exception $e ){
         exit(json_encode(['ERR' => $e->getMessage()]));
     }
    break;
    case 'quickview-modal':
    try{
        $Connection = $util->CreateConnection();
        $product = new Product($Connection);
        $gallery = new Gallery($Connection);
        $product_id_get = $_POST['ProductId'];
        $product_meta = $product->FindById($product_id_get);
        $t_content = $t_navigation = '';
        $product_thumbs = $gallery->FindForCarosell($product_id_get);
        $loop = 1;
        foreach( $product_thumbs as $bn => $th ):
            $tclass = ' fade show active';
            $nclass = ' active';
            if( $loop > 1 ){
                $tclass = ' fade';
                $nclass = '';
            }
            if($loop === 3){
                $nclass = ' button_three';
            }
            $t_content .= '
            <div class="tab-pane'.$tclass.'" id="tab'.$loop.'" role="tabpanel" >
                <div class="modal_tab_img">
                    <a href="#"><img src="'.APP_IMG_PATH.'items/'.$bn.'" alt=""></a>    
                </div>
            </div>';
            $t_navigation .= '
            <li >
                <a class="nav-link'.$nclass.'" data-toggle="tab" href="#tab'.$loop.'" role="tab" aria-controls="tab'.$loop.'" aria-selected="false"><img src="'.APP_IMG_PATH.'items/'.$th.'" alt=""></a>
            </li>';
            $loop++;
        endforeach;

        $price_tag = '<span class="new_price">'.$_SESSION['cry'] .' ' .$util->Forex($util->ApplyDiscount($product_meta)).'</span>';
        if($product_meta['ProductDiscountValue'] > 0){
            $price_tag .= '<span class="old_price">'.$_SESSION['cry'] .' ' .$util->Forex($product_meta['ProductPrice']).'</span>';
        }
        $returned_content = '
        <div class="col-lg-5 col-md-5 col-sm-12">
        <div class="modal_tab">  
            <div class="tab-content product-details-large">
                '.$t_content.'
            </div>
            <div class="modal_tab_button">    
                <ul class="nav product_navactive owl-carousel" role="tablist">
                    '.$t_navigation.'
                </ul>
            </div>    
        </div>  
    </div> 
    <div class="col-lg-7 col-md-7 col-sm-12">
        <div class="modal_right">
            <div class="modal_title mb-10">
                <h2>'.$product_meta['ProductName'].'</h2> 
            </div>
            <div class="modal_price mb-10">
            '.$price_tag.'   
            </div>
            <div class="see_all">
                <a href="product.php?item='.$product_meta['ProductId'].'">See all features</a>
            </div>  
            <div class="modal_add_to_cart mb-15">
                <form action="#">
                    <input min="0" max="100" step="2" value="1" type="number">
                    <button type="submit">add to cart</button>
                </form>
            </div>   
            <div class="modal_description mb-15">
                <p>'.$util->Slice($product_meta['ProductShortDescription'], 140).'...</p>    
            </div> 
            <div class="product_ratings">
                <h3 style="color:#faa618;font-size: 20px;">Customer review</h3>
                <ul>
                '.$util->ShowRating($product->FindProductRate($product_meta['ProductId'])).'
                </ul>    
            </div>      
        </div>    
    </div> 
        ';
        exit(json_encode(['MSG' => 'Rated successfully!', 'data' => $returned_content]));
    }catch(Exception $e){
        exit(json_encode(['ERR' => $e->getMessage()]));
    }
    break;
    case 'add-to-cart':
        try{
            // exit(json_encode(['ERR' => 'System got to rout ']));
            $Connection = $util->CreateConnection();
            $product = new Product($Connection);
            $color_object = new Color($Connection);
            $size_object = new Size($Connection);
            $item = $_POST['ProductId'];
            $qty = $_POST['ProductQty'];
            $item_meta = $product->FindById($item);
            $item_sizes_av = $size_object->FindByProduct($item);
            $color = explode(',', $item_meta['ProductColors'])[0];
            $size = $size_object->FindOneByProduct($item)['SizeId'];
            $cart_item = [$item, $qty, $color, $size];
            if(!isset($_SESSION['curr_usr_cart'])){
                $_SESSION['curr_usr_cart'] = [$cart_item];
            }else{
                if($util->isInCart($item)){
                    $util->UpdateCartItemQty($item, $qty);
                }else{
                    array_push($_SESSION['curr_usr_cart'], $cart_item);
                }
            }
            exit(json_encode(['MSG' => 'Added to cart successfully!']));
        }catch( Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'remove-from-cart':
        try{
            $Connection = $util->CreateConnection();
            $product = new Product($Connection);
            $color_object = new Color($Connection);
            $size_object = new Size($Connection);
            $item = $_POST['ProductId'];
            if($util->isInCart($item)){
                if($util->RemoveFromCart($item)){
                    exit(json_encode(['MSG' => 'Removed from cart successfully!']));
                }
            }else{
                exit(json_encode(['ERR' => 'Item not found in cart!']));
            }
        }catch( Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'update-qty-in-cart':
        try{
            $Connection = $util->CreateConnection();
            $product = new Product($Connection);
            $color_object = new Color($Connection);
            $size_object = new Size($Connection);
            $item = $_POST['ProductId'];
            $qty = $_POST['ProductQty'];
            if($util->isInCart($item)){
                if($util->UpdateCartItemQty($item, $qty, 1)){
                    exit(json_encode(['MSG' => 'Item Qty updated successfully!']));
                }
            }else{
                exit(json_encode(['ERR' => 'Item not found in cart!']));
            }
        }catch( Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'update-size-in-cart':
        try{
            $Connection = $util->CreateConnection();
            $product = new Product($Connection);
            $color_object = new Color($Connection);
            $size_object = new Size($Connection);
            $item = $_POST['ProductId'];
            $size = $_POST['ProductSize'];
            if($util->isInCart($item)){
                if($util->UpdateCartItemSize($item, $size)){
                    exit(json_encode(['MSG' => 'Item size updated successfully!']));
                }
            }else{
                exit(json_encode(['ERR' => 'Item not found in cart!']));
            }
        }catch( Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'update-color-in-cart':
        try{
            $Connection = $util->CreateConnection();
            $product = new Product($Connection);
            $color_object = new Color($Connection);
            $size_object = new Size($Connection);
            $item = $_POST['ProductId'];
            $color = $_POST['ProductColor'];
            if($util->isInCart($item)){
                if($util->UpdateCartItemColor($item, $color)){
                    exit(json_encode(['MSG' => 'Item color updated successfully!']));
                }
            }else{
                exit(json_encode(['ERR' => 'Item not found in cart!']));
            }
        }catch( Exception $e ){
            exit(json_encode(['ERR' => $e->getMessage()]));
        }
    break;
    case 'create-user-Package':
        try{
            $Connection = $util->CreateConnection();
            $PackageId = $util->KeyGen(24);

            $_POST['PackageDescription'] = $util->HtmlEncode($_POST['PackageDescription']);
            $_POST['PackagePack'] = implode(',',$_POST['PackagePack']);
            // $_POST['PackageLocation'] = 'loca';
            $package = new Package($Connection,$PackageId,$_POST['PackageName'],$_POST['PackagePrice'],$_POST['PackageType'],$_POST['PackageDiscountType'],$_POST['PackageDiscountValue'],$_POST['PackageCommisionType'],$_POST['PackageCommisionValue'],$_POST['PackageLocation'],$_POST['PackageValidTill'],$_POST['PackageMetaDescription'],$_POST['PackageDescription'],$_POST['PackageOwner'],$_POST['PackagePack'],1);
            $package->ValidateFields();
            if($package->Create()){
                exit(json_encode(['MSG' => 'Package created Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Package creation failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
    case 'show-package-modal':
        try{
            $Connection = $util->CreateConnection();
            $gallery = new Gallery($Connection);
            $user = new User($Connection);
            $pack = new Pack($Connection);
            $package = new Package($Connection);
            //
            $meta = $package->FindById($_POST['PackageId']);
            $galleries = $gallery->FindByProduct($_POST['PackageId']);
            //
            $formid = "'update_Package_form'";
            $comm_select = $package_type_sel = $pack_select = $discount_select = $categ_select = $vendor_select = '';

            foreach( $util->CommissionTypes() as $k => $v ):
                if($meta['PackageCommisionType'] == $k ){
                    $comm_select .= '<option selected value="'.$k.'">'.$v.'</option>';
                }else{
                    $comm_select .= '<option value="'.$k.'">'.$v.'</option>';
                }
            endforeach;

            foreach( $util->PackageTypes() as $k => $v ):
                if($meta['PackageType'] == $k ){
                    $package_type_sel .= '<option selected value="'.$k.'">'.$v.'</option>';
                }else{
                    $package_type_sel .= '<option value="'.$k.'">'.$v.'</option>';
                }
            endforeach;

            foreach( $pack->FindAll() as $p ):
                if(in_array($p['PackId'], explode(',', $meta['PackagePack']))){
                    $pack_select .= '<option selected value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
                }else{
                    $pack_select .= '<option value="'.$p['PackId'].'">'.$p['PackName'].'</option>';
                }
            endforeach;

            foreach( $util->DiscountTypes() as $k => $v ):
                if($meta['PackageDiscountType'] == $k ){
                    $discount_select .= '<option selected value="'.$k.'">'.$v.'</option>';
                }else{
                    $discount_select .= '<option value="'.$k.'">'.$v.'</option>';
                }
            endforeach;
            
            foreach( $user->FindByType(4004) as $kv ):
                if($util->isAdmin()){
                    if($meta['PackageOwner'] == $kv['UserId']){
                        $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                    }else{
                        $vendor_select .= '<option value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>'; 
                    }
                }else{
                    if($meta['PackageOwner'] == $kv['UserId'] && $_SESSION['usr']['UserId'] == $kv['UserId']){
                        $vendor_select .= '<option selected value="'.$kv['UserId'].'">'.$kv['UserFullName'].'</option>';
                    }
                }
            endforeach;
                                            
            $body_gallery = $body_size = $body_params = '';
            // $tbl_gallery = $tbl_size = $tbl_params = [];

            $tab_1 = '<form method="post" id="update_Package_form" enctype="multipart/form-data"><div class="modal-body"><div class="row"><div class="col-lg-12"><div class="Packaged-box"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="succc" style="display:none;"></div><div class="alert alert-danger" id="errr" style="display:none;"></div></div><!-- Begin left box --><div class="col-lg-4"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="hidden" value="'.$_POST['PackageId'].'" id="PackageId" name="PackageId"><input type="text" value="'.$meta['PackageName'].'" class="form-control" id="PackageName" name="PackageName" placeholder="Package name"></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['PackagePrice'].'" class="form-control" id="PackagePrice" name="PackagePrice" placeholder="Package Price"></div></div></div><!-- end row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="PackageType" id="PackageType"><option value="nn">Package Type</option>'.$package_type_sel.' </select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="date" value="'.date('Y-m-d', strtotime($meta['PackageValidTill'])).'" class="form-control" id="PackageValidTill" name="PackageValidTill" placeholder="Package Valid Till date"></div></div></div><!-- end row --></div><!-- End left,begin middle --><div class="col-lg-8"><!-- row --><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="PackageCommisionType" id="PackageCommisionType"><option value="nn">Commision Type</option>'.$comm_select.' </select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['PackageCommisionValue'].'" class="form-control" id="PackageCommisionValue" name="PackageCommisionValue" placeholder="Commission Value"></div></div></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><select class="form-control select2" name="PackageDiscountType" id="PackageDiscountType"><option value="nn">Discount Type</option>'.$discount_select.' </select></div></div><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['PackageDiscountValue'].'" class="form-control" id="PackageDiscountValue" name="PackageDiscountValue" placeholder="Discount Value"></div></div></div></div><!-- End middle begin right --></div><div class="row"><div class="col-lg-6"><div class="form-group m-b-20"><input type="text" value="'.$meta['PackageMetaDescription'].'" class="form-control" id="PackageMetaDescription" name="PackageMetaDescription" placeholder="Meta Description"></div><div class="form-group m-b-20"><input type="text" value="'.$meta['PackageLocation'].'" class="form-control" id="PackageLocation" name="PackageLocation" placeholder="Holiday location"></div><div class="form-group m-b-20"><select class="form-control select2" name="PackageOwner" id="PackageOwner"><option value="nn">Vendor/Merchant</option>'.$vendor_select.' </select></div><div class="form-group m-b-20"><select class="form-control select2" name="PackagePack[]" id="PackagePack" multiple="multiple" data-placeholder="Select Package Includes">'.$pack_select.' </select></div><div class="form-group m-b-20"><button type="button" name="save-Package" onclick="updatePackage('.$formid.')" class="btn w-sm btn-default waves-effect waves-light">Create item</button></div></div><div class="col-lg-6"><div class="form-group m-b-20"><label>Package Description<span class="text-danger">*</span></label><textarea class="form-control" id="PDescription" name="PackageDescription">'.$util->HtmlDecode($meta['PackageDescription']).'</textarea></div></div></div></div></div></div><style>.select2-search__field{width:100%!important}</style></div></form><script>$(".select2").select2();$("#datatable_ab").length && $("#datatable_ab").DataTable();</script>';
            
            $galleryType_select = '';
            foreach( $util->GalleryTypes() as $k => $v ):
                $galleryType_select .= '<option value="'.$k.'">'.$v.'</option>';
            endforeach;
            $gformid = "'create_gallery'";
            foreach($galleries as $gal): 
                    $specimen = '<img src="'.APP_IMG_PATH . 'items/'.$gal['GalleryPath'].'" class="thumb-sm" alt="">';
                    if($gal['GalleryType'] == '5006')
                        $specimen = $gal['GalleryPath'];
                    $gid = "'".$gal['GalleryId']."'";
                    $body_gallery .= '<tr id="'.$gal['GalleryId'].'">';
                    $body_gallery .= '<td>'.$util->GalleryName($gal['GalleryType']).'</td>';
                    $body_gallery .= '<td>'.$specimen.'</td>';
                    $body_gallery .= '<td><a onclick="DeleteGallery('.$gid.')" class="btn btn-outline"> <span class="md md-delete"> </span> Delete</a></td>';
                    $body_gallery .= '</tr>';
            endforeach;
            $tab_2 = '
            <form method="post" id="create_gallery" enctype="multipart/form-data"><div class="row"><div class="col-lg-12"><div class="alert alert-success" id="sc" style="display:none;"></div><div class="alert alert-danger" id="er" style="display:none;"></div></div><div class="col-lg-6"><div class="card-box"><div class="form-group m-b-20"><input type="hidden" value="'.$_POST['PackageId'].'" id="GalleryProduct" name="GalleryProduct"><label>Gallery Type<span class="text-danger">*</span></label><select class="form-control select2" name="GalleryType" id="GalleryType"><option value="nn">Select</option>'.$galleryType_select.' </select></div><div class="form-group m-b-20"><label>Gallery Image<span class="text-danger">*</span></label><input type="file" class="form-control" id="GalleryPath" name="GalleryPath"></div><div class="form-group m-b-20"><label>If you selected Video above,fill in below<span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="enter Youtube code or video full url" id="GalleryPath_v" name="GalleryPath_v"></div><div class="form-group m-b-20"><button type="button" onclick="CreateGallery('.$gformid.')" class="btn w-sm btn-default waves-effect waves-light">Create </button></div></div></div><div class="col-lg-6"><div class="card-box"><table id="hgjghkgjhhgg" class="table table-striped table-bordered"><thead><tr><th>Type</th><th>Specimen</th><th>Remove</th></tr></thead><tbody>'.$body_gallery.' </tbody></table></div></div></div></form><script>$(".select2").select2();$("#hgjghkgjhhgg").length && $("#hgjghkgjhhgg").DataTable();</script>
            ';
            $tab_3 = '';
            $data = [
                'item' => $meta['PackageName'], 
                'tab1' => $tab_1, 'tab2' => $tab_2, 'tab3' => $tab_3
            ];
            exit(json_encode(['MSG' => 'Success!', 'data' => $data]));  
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
    case 'update-package-item':
        try{
            $Connection = $util->CreateConnection();
            $PackageId = $_POST['PackageId'];
            $_POST['PackageDescription'] = $util->HtmlEncode($_POST['PackageDescription']);
            $_POST['PackagePack'] = implode(',',$_POST['PackagePack']);
            $package = new Package($Connection,$PackageId,$_POST['PackageName'],$_POST['PackagePrice'],$_POST['PackageType'],$_POST['PackageDiscountType'],$_POST['PackageDiscountValue'],$_POST['PackageCommisionType'],$_POST['PackageCommisionValue'],$_POST['PackageLocation'],$_POST['PackageValidTill'],$_POST['PackageMetaDescription'],$_POST['PackageDescription'],$_POST['PackageOwner'],$_POST['PackagePack'],1);
            $package->ValidateFields();
            if($package->Update()){
                exit(json_encode(['MSG' => 'Package updated Successfully!']));
            }else{
                exit(json_encode(['ERR' => 'Package updated failed!']));
            }
        }catch(Exception $e){
            exit(json_encode(['ERR' => $e->getMessage() ]));
        }
    break;
}
?>