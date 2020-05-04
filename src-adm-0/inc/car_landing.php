<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Fleet Users</h4>
        <p class="text-muted page-title-alt">Welcome to PataShop administration panel. Here you can manage fleets. Create and update cars/vehicles. Package cars with other utilities like Driver, fuel etc.</p>
    </div>
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
            <h4 class="m-t-0 header-title"><b>Fleet Owners</b></h4>
            <p class="text-muted font-13 m-b-30">
                Manage PataShop Fleets and Fleet packages.
            </p>

            <table id="datatable-buttons" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Cars</th>
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
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$merch['UserId']?>" class="btn btn-outline"><span class="md md-pageview"> </span> View Cars</a></td>
                        <td><a href="?action=mrchnt-details&usr-curr=<?=$merch['UserId']?>" class="btn btn-outline"> <span class="md md-edit"> </span> Manage</a></td>
                    </tr>
                <?php 
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>