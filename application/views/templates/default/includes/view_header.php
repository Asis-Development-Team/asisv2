<?php 
$is_login 	=	json_decode($this->authentication->is_login($this->session->user_id,$this->session->session_id),true);

if($is_login['status'] == true):
	if($this->session->is_admin == 'no' ):
		header('location:/admin');
	endif;
else:
	header('location:/admin');
endif;
?>

<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Admin Panel</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN LAYOUT FIRST STYLES -->
        <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />
        <!-- END LAYOUT FIRST STYLES -->
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

        <link href="/assets/admin/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="/assets/admin/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />

        <?php /* plugin profile page */ ?>
        <link href="/assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/pages/css/profile.min.css" rel="stylesheet" type="text/css" />

        <link href="/assets/admin/global/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />



        <link rel="stylesheet" type="text/css" href="/assets/admin/global/plugins/bootstrap-summernote/summernote.css">
        <!-- END PAGE LEVEL PLUGINS -->


        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/admin/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/admin/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/admin/layouts/layout5/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/layouts/layout5/css/custom.min.css" rel="stylesheet" type="text/css" />




        <link rel="stylesheet" type="text/css" href="/assets/admin/global/plugins/bootstrap-toastr/toastr.min.css"/>

        <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-multiselect.css" type="text/css"/>




        <link href="/assets/admin/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />


        <link href="/assets/admin/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />


        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo">
        <!-- BEGIN CONTAINER -->
        <div class="wrapper">
            <!-- BEGIN HEADER -->
            <header class="page-header">
                <nav class="navbar mega-menu" role="navigation">
                    <div class="container-fluid">
                        <div class="clearfix navbar-fixed-top">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="toggle-icon">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </span>
                            </button>
                            <!-- End Toggle Button -->
                            <!-- BEGIN LOGO -->
                            <a id="index" class="page-logo hiddenx" href="/admin/dashboard">
                                <img src="/assets/images/logo-dark.png" alt="Logo"> 
                            </a>
                            <!-- END LOGO -->
                            
                            <!-- BEGIN SEARCH -->
                            <form class="search hidden" action="" method="GET">
                                <input type="name" class="form-control" name="query" placeholder="Search...">
                                <a href="javascript:;" class="btn submit md-skip">
                                    <i class="fa fa-search"></i>
                                </a>
                            </form>
                            <!-- END SEARCH -->
                            <!-- BEGIN TOPBAR ACTIONS -->
                            <div class="topbar-actions">
                                
                                <!-- BEGIN GROUP INFORMATION -->
                                
                                <div class="btn-group-red btn-group hidden">
                                    <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <i class="fa fa-sticky-note-o"></i>
                                    </button>
                                    <ul class="dropdown-menu-v2" role="menu">
                                        <li class="active">
                                            <a href="#">New Post</a>
                                        </li>
                                        <li>
                                            <a href="#">New Comment</a>
                                        </li>
                                        <li>
                                            <a href="#">Share</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#">Comments
                                                <span class="badge badge-success">4</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">Feedbacks
                                                <span class="badge badge-danger">2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- END GROUP INFORMATION -->
                                <!-- BEGIN USER PROFILE -->
                                <div class="btn-group-img btn-group">
                                    <button type="button" class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <span>Hi, <?php print $this->session->surname; ?></span>
                                        <img src="/assets/admin/layouts/layout5/img/avatar1.jpg" alt="" class="hidden"> 
                                    </button>
                                    <ul class="dropdown-menu-v2" role="menu">
                                        <li>
                                            <a href="/admin/profile">
                                                <i class="icon-user"></i> My Profile
                                                
                                            </a>
                                        </li>
                    
                                        <li class="divider"> </li>

                                        <li>
                                            <a href="/admin/logout">
                                                <i class="icon-key"></i> Log Out 
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- END USER PROFILE -->
                                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                                <button type="button" class="quick-sidebar-toggler md-skip hidden" data-toggle="collapse">
                                    <span class="sr-only">Toggle Quick Sidebar</span>
                                    <i class="icon-logout"></i>
                                </button>
                                <!-- END QUICK SIDEBAR TOGGLER -->
                            </div>
                            <!-- END TOPBAR ACTIONS -->
                        </div>



                        <!-- BEGIN HEADER MENU -->
                        <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                            <ul class="nav navbar-nav">
                                <li class="dropdown dropdown-fw dropdown-fw-disabled  <?php if($page_identifier=='dashboard'){ ?>active open selected<?php } ?>">
                                    <a href="/admin/dashboard" class="text-uppercase">
                                        <i class="icon-home"></i> Dashboard </a>
                                </li>

                                <?php 
                                $data_css_class     =   '';

                                if (preg_match("/\bdata\b/i", $page_identifier)):
                                    $data_css_class     =   'active open selected';
                                endif;
                                ?>
                                <li class="dropdown dropdown-fw dropdown-fw-disabled <?php print $data_css_class ?>">
                                    <a href="javascript:;" class="text-uppercase">
                                        <i class="fa fa-database"></i> Data </a>

                                    <ul class="dropdown-menu dropdown-menu-fw">

                                        <li class="dropdown more-dropdown-subx">
                                            
                                            <a href="/admin/data/product">
                                                <i class="fa fa-calendar-check-o"></i> Products 
                                            </a>

                                        </li>

                                        <li class="dropdown more-dropdown-subx">
                                            
                                            <a href="/admin/data/order">
                                                <i class="fa fa-shopping-cart"></i> Order 
                                            </a>

                                        </li>

                                        <li class="dropdown more-dropdown-subx">
                                            
                                            <a href="/admin/data/member">
                                                <i class="fa fa-users"></i> Member 
                                            </a>

                                        </li>
                                    </ul>
                                </li>




                                <?php
                                if (preg_match("/\bsetting\b/i", $page_identifier)):
                                    $data_css_class_setting     =   'active open selected';
                                endif;
                                ?>                                
                                <li class="dropdown dropdown-fw dropdown-fw-disabled <?php print @$data_css_class_setting; ?>">
                                    <a href="javascript:;" class="text-uppercase">
                                        <i class="fa fa-gear"></i> Setting 
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-fw">
                                        
                                        <li class="dropdown more-dropdown-subx">
                                            <a href="/admin/setting/product-category"><i class="fa fa-bars"></i>Product Category</a>
                                        </li>
                                        <li class="dropdown more-dropdown-subx">
                                            <a href="/admin/setting/pages"><i class="fa fa-file-archive-o"></i>Static Page</a>
                                        </li>                                        
                                    </ul>


                                </li>


                            </ul>
                        </div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!--/container-->
                </nav>
            </header>
            <!-- END HEADER -->




            <div class="container-fluid">

                <div class="page-content">

