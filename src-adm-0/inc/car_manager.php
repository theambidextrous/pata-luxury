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
    $user = new User($util->CreateConnection());
    $pack = new Pack($util->CreateConnection());
    $meta = $user->FindById($_GET['usr-curr']);
    if(!$util->isAdmin()){
        $meta = $user->FindById($_SESSION['usr']['UserId']);
    }
?>
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title"><a href="carbooking-users.php">Car Booking</a> > <a href="#">Fleet Owners</a> > <?=$meta['UserFullName']?></h4>
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
                            <li class="active"><a href="#hotelCars" data-toggle="tab">Fleets</a></li>
                            <li><a href="#reservations" data-toggle="tab">Reservations</a></li>
                            <li><a href="#inquiries" data-toggle="tab">Inquiries</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="hotelCars">
                        <!-- start -->
                            <?php 
                            $Car = new Car($util->CreateConnection());
                            $obj_0 = new Pack($util->CreateConnection());
                            $Cars = $Car->FindByVendor($meta['UserId']);
                            // $util->Show($Cars);
                            ?>
                            <div class="card-box table-responsive" id="reload_div">
                                <h4 class="m-t-0 header-title"><b><?=$meta['UserFullName']?></b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Manage <?=$meta['UserFullName']?> Manage Cars. Create, Update, Delete.
                                <?php if(!$util->isCustomer()){?>
                                        <a href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#Car_create" style="font-weight:900;font-size:18px;" class="pull-right btn btn-default btn-sm waves-effect waves-light"><span class="md md-add-box"></span> Create New </a>
                                <?php } ?>
                                </p>
                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Car Name</th>
                                        <th>Hire Price</th>
                                        <th>L.P. No.</th>
                                        <th>Brand</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach($Cars as $rm): ?>
                                        <tr>
                                            <td><?=$no?></td>
                                            <td><?=$rm['CarName']?></td>
                                            <td><?=$rm['CarPrice']?></td>
                                            <td><?=$rm['CarPlateNumber']?></td>
                                            <td><?=$rm['CarBrand']?></td>
                                            <td><a onclick="CarDetailsModal('<?=$rm['CarId']?>')" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                                        </tr>
                                    <?php 
                                    $no++;
                                    endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <!-- end -->
                        </div>
                        <div class="tab-pane fade" id="reservations">
                            
                        </div>
                        <div class="tab-pane fade" id="inquiries">Success 3</div>
                    </div>
                </div>
            </div>
            <!-- end tabs -->
        </div>
    </div>

</div>
