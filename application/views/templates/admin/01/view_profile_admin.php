

                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1>User Profile | Account</h1>
                        
                        <?php /*
                        <ol class="breadcrumb">
                            <li>
                                <a href="#">Home</a>
                            </li>
                            <li>
                                <a href="#">Pages</a>
                            </li>
                            <li class="active">User</li>
                        </ol>
                        */ ?>
                        <!-- Sidebar Toggle Button -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".page-sidebar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="toggle-icon">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </span>
                        </button>
                        <!-- Sidebar Toggle Button -->
                    </div>
                    <!-- END BREADCRUMBS -->
                    <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
                    <div class="page-content-container">
                        <div class="page-content-row">



                            <div class="page-content-col">
                                <!-- BEGIN PAGE BASE CONTENT -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN PROFILE SIDEBAR -->
                                        <div class="profile-sidebar">
                                            <!-- PORTLET MAIN -->
                                            <div class="portlet light profile-sidebar-portlet bordered">
                                                <!-- SIDEBAR USERPIC -->
                                                <div class="profile-userpic">
                                                    
                                                    <?php
                                                    $avatar =   ($result['user_picture']) ? $result['user_picture'] : '5.png';
                                                    ?>

                                                    <img src="/assets/ivf/images/<?php print $avatar ?>" class="img-responsive" alt=""> 

                                                </div>

                                                <!-- END SIDEBAR USERPIC -->
                                                <!-- SIDEBAR USER TITLE -->
                                                <div class="profile-usertitle">
                                                    <div class="profile-usertitle-name"> <?php print stripslashes(ucfirst($result['surname'])); ?> </div>

                                                    <div class="profile-usertitle-job hidden"> Developer </div>
                                                </div>
                                                <!-- END SIDEBAR USER TITLE -->
                                                
                                                <?php /*
                                                <div class="profile-usermenu">
                                                    <ul class="nav">
                                                        <li>
                                                            <a href="page_user_profile_1.html">
                                                                <i class="icon-home"></i> Overview </a>
                                                        </li>
                                                        <li class="active">
                                                            <a href="page_user_profile_1_account.html">
                                                                <i class="icon-settings"></i> Account Settings </a>
                                                        </li>
                                                        <li>
                                                            <a href="page_user_profile_1_help.html">
                                                                <i class="icon-info"></i> Help </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- END MENU -->
                                                */ ?>

                                            </div>
                                            <!-- END PORTLET MAIN -->


                                            <?php /*
                                            <!-- PORTLET MAIN -->
                                            <div class="portlet light bordered">
                                                <!-- STAT -->
                                                <div class="row list-separated profile-stat">
                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <div class="uppercase profile-stat-title"> 37 </div>
                                                        <div class="uppercase profile-stat-text"> Projects </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <div class="uppercase profile-stat-title"> 51 </div>
                                                        <div class="uppercase profile-stat-text"> Tasks </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <div class="uppercase profile-stat-title"> 61 </div>
                                                        <div class="uppercase profile-stat-text"> Uploads </div>
                                                    </div>
                                                </div>
                                                <!-- END STAT -->
                                                <div>
                                                    <h4 class="profile-desc-title">About Marcus Doe</h4>
                                                    <span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
                                                    <div class="margin-top-20 profile-desc-link">
                                                        <i class="fa fa-globe"></i>
                                                        <a href="http://www.keenthemes.com">www.keenthemes.com</a>
                                                    </div>
                                                    <div class="margin-top-20 profile-desc-link">
                                                        <i class="fa fa-twitter"></i>
                                                        <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                                                    </div>
                                                    <div class="margin-top-20 profile-desc-link">
                                                        <i class="fa fa-facebook"></i>
                                                        <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END PORTLET MAIN -->
                                            */ ?>

                                        </div>
                                        <!-- END BEGIN PROFILE SIDEBAR -->
                                        <!-- BEGIN PROFILE CONTENT -->
                                        <div class="profile-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="portlet light bordered">
                                                        <div class="portlet-title tabbable-line">
                                                            <div class="caption caption-md">
                                                                <i class="icon-globe theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                                            </div>
                                                            <ul class="nav nav-tabs">
                                                                <li class="active">
                                                                    <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                                </li>
                                                                <li class="hidden">
                                                                    <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                                                </li>
                                                                <li class="hidden">
                                                                    <a href="#tab_1_4" data-toggle="tab">Privacy Settings</a>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <div class="portlet-body">

                                                            <div class="tab-content">
                                                                <!-- PERSONAL INFO TAB -->
                                                                <div class="tab-pane active" id="tab_1_1">

                                                                    
                                                                    <form action="/admin/profile_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="control-label">Email Address (Login ID) <span class="required">*</span></label>
                                                                            <input type="text" placeholder="" value="<?php print stripslashes($result['username']) ?>" class="form-control requiredField" id="email" name="email" /> 
                                                                        </div>


                                                                        <div class="form-group">
                                                                            <label class="control-label">Surname</label>
                                                                            <input type="text" placeholder="" value="<?php print stripslashes($result['surname']) ?>" class="form-control requiredField" name="surname" id="surname" /> 
                                                                        </div>
                                                                        
            
                                                                        <div class="form-group">
                                                                            <label class="control-label">Phone Number</label>
                                                                            <input type="text" placeholder="" class="form-control requiredField" name="phone" value="<?php print $result['user_hp'] ?>" maxlength="20" /> 
                                                                        </div>
                                                                        
                                                                        <div class="margiv-top-10">
                                                                            
                                                                            <input type="hidden" name="identifier" value="profile">

                                                                            <button type="submit" class="btn blue">
                                                                                <i class="fa fa-check"></i> Update 
                                                                                <i class="fa fa-spinner fa-spin" style="display:none;"></i>
                                                                            </button>

                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- END PERSONAL INFO TAB -->
                                                                <!-- CHANGE AVATAR TAB -->
                                                                <div class="tab-pane hidden" id="tab_1_2">
                                                                    <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                                        laborum eiusmod. </p>
                                                                    <form action="#" role="form">
                                                                        <div class="form-group">
                                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                                <div>
                                                                                    <span class="btn default btn-file">
                                                                                        <span class="fileinput-new"> Select image </span>
                                                                                        <span class="fileinput-exists"> Change </span>
                                                                                        <input type="file" name="..."> </span>
                                                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="clearfix margin-top-10">
                                                                                <span class="label label-danger">NOTE! </span>
                                                                                <span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="margin-top-10">
                                                                            <a href="javascript:;" class="btn green"> Submit </a>
                                                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- END CHANGE AVATAR TAB -->
                                                                <!-- CHANGE PASSWORD TAB -->
                                                                <div class="tab-pane" id="tab_1_3">

                                                                    <form action="/admin/profile_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">

                                                                        <div class="form-group">
                                                                            <label class="control-label">New Password</label>
                                                                            <input type="password" class="form-control requiredFieldx" name="password" /> 
                                                                        </div>


                                                                        <div class="margin-top-10">
                                                                            
                                                                            <input type="hidden" name="identifier" value="password">

                                                                            <button type="submit" class="btn blue">
                                                                                <i class="fa fa-check"></i> Update 
                                                                                <i class="fa fa-spinner fa-spin" style="display:none;"></i>
                                                                            </button>

                                                                        </div>
                                                                    </form>

                                                                </div>
                                                                <!-- END CHANGE PASSWORD TAB -->
                                                                <!-- PRIVACY SETTINGS TAB -->
                                                                <div class="tab-pane hidden" id="tab_1_4">
                                                                    <form action="#">
                                                                        <table class="table table-light table-hover">
                                                                            <tr>
                                                                                <td> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus.. </td>
                                                                                <td>
                                                                                    <div class="mt-radio-inline">
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios1" value="option1" /> Yes
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios1" value="option2" checked/> No
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>
                                                                                <td>
                                                                                    <div class="mt-radio-inline">
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios11" value="option1" /> Yes
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios11" value="option2" checked/> No
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>
                                                                                <td>
                                                                                    <div class="mt-radio-inline">
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios21" value="option1" /> Yes
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios21" value="option2" checked/> No
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>
                                                                                <td>
                                                                                    <div class="mt-radio-inline">
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios31" value="option1" /> Yes
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <label class="mt-radio">
                                                                                            <input type="radio" name="optionsRadios31" value="option2" checked/> No
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <!--end profile-settings-->
                                                                        <div class="margin-top-10">
                                                                            <a href="javascript:;" class="btn red"> Save Changes </a>
                                                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- END PRIVACY SETTINGS TAB -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PROFILE CONTENT -->
                                    </div>
                                </div>
                                <!-- END PAGE BASE CONTENT -->
                            </div>
                        </div>
                    </div>
                    <!-- END SIDEBAR CONTENT LAYOUT -->
