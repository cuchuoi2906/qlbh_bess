<?php

require_once dirname(__FILE__) . '/bootstrap.php';
//Chống bot truy cập
$denyconnect = new denyconnect();
disable_debug_bar();

$loginpath = "login.php";
if (!isset($_SESSION["logged"])) {
    redirect($loginpath);
} else {
    if ($_SESSION["logged"] != 1) {
        redirect($loginpath);
    }
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Area</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="resource/adminlte/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="resource/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="resource/adminlte/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="resource/adminlte/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="resource/css/waitMe.min.css">
	<meta name="robots" content="noindex, nofollow"/>
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .nav-tabs > li .close {
            margin: -2px 0 0 10px;
            font-size: 18px;
        }

        .marginBottom {
            margin-bottom: 1px !important;
        }

        .operationDiv {
            padding: 5px 10px 5px 5px;
        }

        .operationDivWrapper {
            margin-top: -1px;
        }

        .leftMenu {
            height: 70%;
            background-color: #E6E6E6;
            border-right: 2px solid #BFBFBF;
        }

        #tab_1000 a button {
            display: none;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini" style="">
                <img src="<?= setting_image('logo') ?>" style="height: 37px"/>
            </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg" style="">
                <img src="<?= setting_image('logo') ?>" style="height: 45px"/>
            </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <!--<li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 4 messages</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="resource/adminlte/dist/img/user2-160x160.jpg"
                                                     class="img-circle"
                                                     alt="User Image">
                                            </div>
                                            <h4>
                                                Support Team
                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <p>Why not buy a new awesome theme?</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                        </ul>
                    </li>-->
                    <!-- Notifications: style can be found in dropdown.less -->
                    <!--<li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>-->
                    <!-- Tasks: style can be found in dropdown.less -->
					<?php 
					$db_menu = new db_query("SELECT count(ord_id) as orderCountNew
						FROM orders WHERE ord_status_code = 'NEW' LIMIT 1");
					$modules = $db_menu->fetch();
					?>
                    <li class="dropdown tasks-menu">
                        <a id="order_new" onclick="return false;" href="modules/orders/listing.php?status=NEW">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger"><?php echo $modules[0]['orderCountNew']; ?></span>
                        </a>
                        <!--<ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            
                        </ul>-->
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="resource/adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?= getValue("userlogin", "str", "SESSION", "") ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="resource/adminlte/dist/img/user2-160x160.jpg" class="img-circle"
                                     alt="User Image">

                                <p>
                                    <?= getValue("userlogin", "str", "SESSION", "") ?> - Web Developer
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a id="profile_button" onclick="return false;" href="resource/profile/myprofile.php"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="resource/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="resource/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?= getValue("userlogin", "str", "SESSION", "") ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Đang Online</a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" id="sidebar-menu">
                <li class="header">THANH ĐIỀU HƯỚNG</li>
                <li class="treeview active">
                    <a href="/admin">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <?php
                $isAdmin = isset($_SESSION["isAdmin"]) ? intval($_SESSION["isAdmin"]) : 0;
                $user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;
                $sql = '';
                if ($isAdmin != 1) {
                    $sql = ' INNER JOIN admin_user_right ON(adu_admin_module_id  = mod_id AND adu_admin_id = ' . $user_id . ')';
                }
                $db_menu = new db_query("SELECT *
								 FROM modules
								 " . $sql . "
								 ORDER BY mod_order ASC");

                $i = 0;
                $id_tab = 1;
                $modules = $db_menu->fetch();
                $modules_group = [];
                foreach ($modules as $module) {
                    if ($module['mod_group'] != '') {
                        $modules_group[$module['mod_group']] = $modules_group[$module['mod_group']] ?? [];
                        $modules_group[$module['mod_group']][] = $module;
                    } else {
                        $modules_group[] = $module;
                    }
                }

                foreach ($modules_group as $key => $group) {
                    if (is_array(reset($group))) {
                        ?>
                        <li>
                            <a href="#">
                                <i class="fa fa-folder"></i> <span><?= $key ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                foreach ($group AS $row) {
                                    if (file_exists("modules/" . $row["mod_path"] . "/inc_security.php") === true) {
                                        $i++;
                                        $arraySub = explode("|", $row["mod_listname"]);
                                        $arrayUrl = explode("|", $row["mod_listfile"]);
                                        if (count($arraySub) == 1) {
                                            $module_single = true;
                                        } else {
                                            $module_single = false;
                                        }
                                        ?>
                                        <?php if ($module_single): ?>
                                            <li class="left-menu" id-tab="<?= $id_tab ?>"
                                                title-tab="<?= $key ?> > <?= $row['mod_name'] ?>">
                                                <a onclick="return false;"
                                                   href="modules/<?= $row["mod_path"] ?>/<?= reset($arrayUrl) ?>">
                                                    <i class="fa fa-caret-right"></i>
                                                    <?= $row['mod_name'] ?>
                                                </a>
                                            </li>
                                            <?php $id_tab++; else: ?>
                                            <li class="treeview">
                                                <a href="#">
                                                    <i class="fa fa-folder"></i> <span><?= $row["mod_name"] ?></span>
                                                    <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                                </a>
                                                <ul class="treeview-menu">
                                                    <?php

                                                    //$id_tab = $row["mod_id"];
                                                    $title_tab = $row["mod_name"];
                                                    $arraySub = explode("|", $row["mod_listname"]);
                                                    $arrayUrl = explode("|", $row["mod_listfile"]);
                                                    ?>
                                                    <?

                                                    foreach ($arraySub as $key => $value) {

                                                        $url = isset($arrayUrl[$key]) ? $arrayUrl[$key] : '#';
                                                        //$id_tab = $i + 1;
                                                        ?>
                                                        <li class="left-menu" id-tab="<?= $id_tab ?>"
                                                            title-tab="<?= $key ?> > <?= $title_tab ?> > <?= $value ?>">
                                                            <a onclick="return false;"
                                                               href="modules/<?= $row["mod_path"] ?>/<?= $url ?>">
                                                                <i class="fa fa-caret-right"></i>
                                                                <?= $value ?>
                                                            </a>
                                                        </li>

                                                        <?
                                                        $id_tab++;
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                        <?
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    } else {
                        $row = $group;

                        if (file_exists("modules/" . $row["mod_path"] . "/inc_security.php") === true) {
                            $i++;

                            $arraySub = explode("|", $row["mod_listname"]);
                            $arrayUrl = explode("|", $row["mod_listfile"]);
                            if (count($arraySub) == 1) {
                                $module_single = true;
                            } else {
                                $module_single = false;
                            }

                            ?>
                            <?php if ($module_single): ?>
                                <li class="left-menu" id-tab="<?= $id_tab ?>"
                                    title-tab="<?= $row['mod_name'] ?>">
                                    <a onclick="return false;"
                                       href="modules/<?= $row["mod_path"] ?>/<?= reset($arrayUrl) ?>">
                                        <i class="fa fa-folder"></i>
                                        <?= $row['mod_name'] ?>
                                    </a>
                                </li>
                                <?php $id_tab++; else: ?>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-folder"></i> <span><?= $row["mod_name"] ?></span>
                                        <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <?
                                        $title_tab = $row["mod_name"];
                                        $arraySub = explode("|", $row["mod_listname"]);
                                        $arrayUrl = explode("|", $row["mod_listfile"]);
                                        ?>
                                        <?

                                        foreach ($arraySub as $key => $value) {
                                            $url = isset($arrayUrl[$key]) ? $arrayUrl[$key] : '#';
                                            ?>
                                            <li class="left-menu" id-tab="<?= $id_tab ?>"
                                                title-tab="<?= $title_tab ?> > <?= $value ?>">
                                                <a onclick="return false;"
                                                   href="modules/<?= $row["mod_path"] ?>/<?= $url ?>">
                                                    <i class="fa fa-caret-right"></i>
                                                    <?= $value ?>
                                                </a>
                                            </li>

                                            <?
                                            $id_tab++;
                                        }
                                        ?>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?
                        }
                    }
                }
                ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div id="admin_body" class="box-body"
                 style="min-height: 600px; padding: 0;background: url(https://www.oakdenehouse.org.au/wp-content/uploads/2012/04/Background_Image1.png); background-size: cover;">
                <div class="nav-tabs-custom" style="margin-bottom: 0">
                    <ul id="nav_tabs" class="nav nav-tabs">
                        <li id="tab_1000" class="active">
                            <a href="#content_tab_1000" data-toggle="tab">
                                <button class="close closeTab" type="button" onclick="close_tab(this)">×</button>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                    <div id="tabs_content" class="tab-content">

                        <div class="tab-pane active" id="content_tab_1000">
                            <div class="row" style="overflow-y: auto">
                                <?php
                                foreach ($modules as $module):
                                    if (
                                        file_exists("modules/" . $module["mod_path"] . "/inc_security.php") === true
                                        && file_exists("modules/" . $module["mod_path"] . "/dashboard_report.php") === true
                                    ):
                                        ?>
                                        <div class="col-md-6">
                                            <!-- MAP & BOX PANE -->
                                            <div class="box box-info">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title"><?=$module['mod_name']?></h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool"
                                                                data-widget="collapse"><i class="fa fa-minus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-box-tool"
                                                                data-widget="remove"><i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="pad">
                                                               <iframe id="idframe_report_<?=$module['mod_id']?>" src="modules/<?=$module['mod_path']?>/dashboard_report.php" frameborder='0' width='100%' height="240" onLoad="//calcHeight('idframe_report_<?=$module['mod_id']?>');"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                        </div>
                                    <?php endif; endforeach; ?>
                            </div>
                        </div>


                    </div>
                    <!-- /.tab-content -->
                </div>

            </div>
            <!-- /.box-body -->
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.8
        </div>
        <strong>Copyright &copy; 2018-<?=date('Y')?> <a href="http://acecrm.info">ACE Crm</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="resource/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="resource/adminlte/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="resource/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="resource/adminlte/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="resource/adminlte/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="resource/adminlte/dist/js/demo.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="resource/css/jquery-ui.css"/>

<script src="resource/js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>

<!-- PACE -->
<script src="resource/adminlte/plugins/pace/pace.min.js"></script>
<script src="resource/js/waitMe.min.js"></script>

<script type="text/javascript">

    $(function () {
        $('#sidebar-menu').slimScroll({
            height: 'calc(100vh - 185px)',
            color: '#fff',
            opacity: '1'
        });
    });
    $(function () {
        $('.tab-pane>div').slimScroll({
            height: 'calc(100vh - 185px)',
            color: 'gray',
            opacity: '1'
        });
        $(window).resize();
    });

    function calcHeight(id) {
        if (!$("#" + id).attr("data-loaded")) {
            //var divHeight = $("#tabs").height() - 50;
            var divHeight = $(window).height() - 140;
            $("#" + id).height(divHeight);
            $("#" + id).attr("data-loaded", "true");
        }
    }

    function close_tab(ele) {
        var tabContentId = $(ele).parent().attr("href");
        var preview = $(ele).parent().parent().prev();
        $(ele).parent().parent().remove(); //remove li of tab
        $(tabContentId).remove(); //remove respective tab content
        if (!$('#nav_tabs li.active').length) {
            $(preview).find('a').tab('show');
        }
    }

    $(function () {
        function run_waitMe(el, num, effect) {
            text = 'Please wait...';
            fontSize = '';
            switch (num) {
                case 1:
                    maxSize = '';
                    textPos = 'vertical';
                    break;
                case 2:
                    text = '';
                    maxSize = 30;
                    textPos = 'vertical';
                    break;
                case 3:
                    maxSize = 30;
                    textPos = 'horizontal';
                    fontSize = '18px';
                    break;
            }
            el.waitMe({
                effect: effect,
                text: text,
                bg: 'rgba(255,255,255,0.7)',
                color: '#000',
                maxSize: maxSize,
                waitTime: 1000,
                source: 'img.svg',
                textPos: textPos,
                fontSize: fontSize,
                onClose: function (el) {
                }
            });
        }

        $('.left-menu').click(function () {

            $('.left-menu, .treeview').removeClass('active');
            $(this).addClass('active').parents('.treeview').addClass('active');

            run_waitMe($('#tabs_content'), 1, 'bounce');

            var tab_id = $(this).attr('id-tab');
			console.log($('#tab_' + tab_id).length);
            if ($('#tab_' + tab_id).length) {

                $('#tab_' + tab_id).find('a').click();
                var source = $(this).find('a').attr('href');
                $('#content_tab_' + tab_id + ' iframe').attr('src', source);
            } else {
                //nav
                var new_tab = $('#tab_1000').clone();
                new_tab.attr('id', 'tab_' + tab_id);
                new_tab.find('a').attr('href', '#content_tab_' + tab_id);
                new_tab.find('a span').html($(this).attr('title-tab'));
                $('#nav_tabs').append(new_tab);
                $('#nav_tabs').find('li').removeClass('active');

                //Content
                var new_content = $('#content_tab_1000').clone();
                new_content.attr('id', 'content_tab_' + tab_id);

                var source = $(this).find('a').attr('href');
                new_content.html("<iframe id='idframe_" + tab_id + "' src='" + source + "' frameborder='0' width='100%' onLoad=\"calcHeight('idframe_" + tab_id + "');\"></iframe>");
                $('#tabs_content').append(new_content);
                new_tab.find('a').click();
            }
        });
		$('#order_new').click(function () {
			$('.left-menu, .treeview').removeClass('active');
			run_waitMe($('#tabs_content'), 1, 'bounce');
			var tab_id = 3;
			if ($('#tab_' + tab_id).length) {
				
                //$('#tab_' + tab_id).find('a').click();
				$('#nav_tabs').find('li').removeClass('active');
				$('#content_tab_'+tab_id).addClass('active');
                var source = $(this).attr('href');
                $('#content_tab_' + tab_id + ' iframe').attr('src', source);
            } else {
                //nav
                var new_tab = $('#tab_1000').clone();
                new_tab.attr('id', 'tab_' + tab_id);
                new_tab.find('a').attr('href', '#content_tab_' + tab_id);
                new_tab.find('a span').html($(this).attr('title-tab'));
                $('#nav_tabs').append(new_tab);
                $('#nav_tabs').find('li').removeClass('active');

                //Content
                var new_content = $('#content_tab_1000').clone();
                new_content.attr('id', 'content_tab_' + tab_id);

                var source = $(this).attr('href');
                new_content.html("<iframe id='idframe_" + tab_id + "' src='" + source + "' frameborder='0' width='100%' onLoad=\"calcHeight('idframe_" + tab_id + "');\"></iframe>");
                $('#tabs_content').append(new_content);
                new_tab.find('a').click();
            }

		});
    });

    $('#profile_button').click(function () {
        $('.left-menu, .treeview').removeClass('active');
        $(this).addClass('active').parents('.treeview').addClass('active');

        var stt = 1001111;
        var source = $(this).attr('href');

        $('#module_name').find('a').html('Thông tin cá nhân');
        $('#module_action').html('Cập nhật');

        var title = 'Cập nhật thông tin cá nhân';
        $('#content_header').find('h1').html(title);

        $('#content_header').show();

        $('#admin_body').html("<iframe id='idframe_" + stt + "' src='" + source + "' frameborder='0' width='100%' onLoad=\"calcHeight('idframe_" + stt + "');\"></iframe>");

        Pace.restart();

        return;

    });
</script>

<script>
    function remove_accent(str) {
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
        str = str.replace(/-+-/g, "-");
        str = str.replace(/^\-+|\-+$/g, "");
        return str;
    }

    (function ($) {
        // custom css expression for a case-insensitive contains()
        jQuery.expr[':'].Contains = function (a, i, m) {
            return (remove_accent(a.textContent) || remove_accent(a.innerText) || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };
    }(jQuery));
    filterList(
        $(".input-group"),
        $("ul.sidebar-menu"),
        {
            find: 'a',
            container: 'li'
        }
    );

    function filterList(header, list, options) {
        var options_default = {
            find: 'td',
            container: 'tr',
            placeHolder: 'Search...',
            name_input: 'keyword_filter',
            id_input: 'f_input',
            hidden: ''
        };
        height = $(list).css('height');

        options = $.extend({}, options_default, options);
        input = $(header).find('input');

        $(input).change(function () {
            var filter = remove_accent($(this).val());
            console.log(filter);
            if (filter) {

                $matches = $(list).find(options.find + ':Contains(' + filter + ')').parent();
                $(list).find(options.container).not($matches).hide();
                $matches.show();
                $(options.hidden).hide();
                $(list).css('height', 'auto');

            } else {
                $(list).find(options.container).show();
                $(list).css('height', height);
            }
            return false;
        })
            .keyup(function () {
                // fire the above change event after every letter
                $(this).change();
            });
    }


</script>

</body>
</html>
