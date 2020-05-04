<?php 
// $util->ShowErrors();
?>
<!-- Top Bar Start -->
<div class="topbar">

<!-- LOGO -->
<div class="topbar-left">
    <div class="text-center">
        <!-- Image Logo here -->
        <a href="#" class="logo">
            <i class="icon-c-logo"> <img src="<?=LOGO?>" height="42"/> </i>
            <span><img style="width: 150px;height: auto;" src="<?=LOGO?>" height="20"/></span>
        </a>
    </div>
</div>

<!-- Button mobile view to collapse sidebar menu -->
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="">
            <div class="pull-left">
                <button class="button-menu-mobile open-left waves-effect waves-light">
                    <i class="md md-menu"></i>
                </button>
                <span class="clearfix"></span>
            </div>

            <ul class="nav navbar-nav hidden-xs">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                       role="button" aria-haspopup="true" aria-expanded="false">Create <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">User</a></li>
                        <li><a href="#">Product</a></li>
                        <li><a href="#">Transaction</a></li>
                        <li><a href="#">Message</a></li>
                    </ul>
                </li>
            </ul>

            <form role="search" class="navbar-left app-search pull-left hidden-xs">
                 <input type="text" placeholder="Search..." class="form-control">
                 <a href=""><i class="fa fa-search"></i></a>
            </form>

            <?php 
                $Notes = $user->FindUserNotes($_SESSION['usr']['UserId']);
                $Notes = !empty($Notes)?$Notes:[];
            ?>
            <ul class="nav navbar-nav navbar-right pull-right">
                <li class="dropdown top-menu-item-xs">
                    <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                        <i class="icon-bell"></i> <span class="badge badge-xs badge-danger"><?=count($Notes)?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg">
                        <li class="notifi-title"><span class="label label-default pull-right">New <?=count($Notes)?></span>Notification</li>
                        <li class="list-group slimscroll-noti notification-list">
                           <!-- list item-->
                           <?php
                            foreach( $Notes as $note ):
                            ?>
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                 <div class="pull-left p-r-10">
                                    <em class="fa <?=$util->NoteIcons($note['NoteType'])?> noti-primary"></em>
                                 </div>
                                 <div class="media-body">
                                    <h5 class="media-heading"><?=$note['Note']?></h5>
                                    <p class="m-0">
                                        <small><?=$note['Note']?></small>
                                    </p>
                                 </div>
                              </div>
                           </a>
                            <?php endforeach; ?>
                           <!-- end list item -->
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="list-group-item text-right">
                                <!-- <small class="font-600">See all notifications</small> -->
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="hidden-xs">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="icon-size-fullscreen"></i></a>
                </li>
                <li class="dropdown top-menu-item-xs">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="<?=APP_IMG_PATH?>/profiles/<?=$_SESSION['usr']['UserProfilePhoto']?>" alt="user-img" class="img-circle"> </a>
                    <ul class="dropdown-menu">
                        <li class="divider"></li>
                        <li><a href="#"><i class="ti-user m-r-10 text-danger"></i> Logged in as <?=$_SESSION['usr']['UserFullName']?></a></li>
                        <li><a href="logout.exe.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
</div>
<!-- Top Bar End -->