                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                        <h1 class="hide">Add New Member</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/data/member">Member</a>
                            </li>
                            <li class="active">Edit Data</li>
                        </ol>


                    </div>
                    <!-- END BREADCRUMBS -->


                    <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
                    <div class="page-content-container">
                        <div class="page-content-row">
                            

                            <?php /* page sidebar container */ ?>

                            <div class="page-content-col">

                                <!-- BEGIN PAGE BASE CONTENT -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tabbable-line boxless tabbable-reversed">

                                            <div class="tab-content">
                                                

                                                <div class="tab-pane active" id="tab_1">

                                                    <div class="portlet box blue">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="fa fa-edit"></i>Edit Member </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>
                                                                <a class="hidden" href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/add" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-edit "></i> Add Data</button>
                                                                </a>                                                                    

                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="/admin/data_member_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Email (Login ID)<span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="email" name="email" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['email']) ?>">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Password (Leave it blank if not change) <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                <input type="password" id="password" name="password" class="form-control requiredFieldx" placeholder="" maxlength="70" >
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Surname<span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="surname" name="surname" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['surname']) ?>">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Address <span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="address" name="address" class="form-control requiredField" placeholder="" value="<?php print $result['user_address'] ?>">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">City<span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="city" name="city" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['user_city']) ?>">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Phone <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                <input type="text" id="phone" name="phone" class="form-control requiredFieldx" placeholder="" value="<?php print $result['user_hp'] ?>">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Company <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                <input type="text" id="company" name="company" class="form-control requiredFieldx" placeholder="" value="<?php print $result['user_company'] ?>">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>



                                                                </div>
                                                                <div class="form-actions rightx">
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Update 
                                                                        <i class="fa fa-spinner fa-spin" style="display:none;"></i>
                                                                    </button>
                                                                    <input type="hidden" name="identifier" value="edit" />

                                                                    <input type="hidden" name="id" value="<?php print $result['user_id'] ?>">
                                                                    <input type="hidden" name="ids" value="<?php print $result['user_ids'] ?>">
                                                                    
                                                                    <input type="hidden" name="image-counter" class="requiredFieldx" value="1" id="image-counter" /> 

                                                                    <?php /*
                                                                    <span class="save-loading" style="display:none">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span> 
                                                                    */ ?>      

                                                                    <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>" class="btn default pull-right">
                                                                    Cancel
                                                                    </a>

                                                                </div>
                                                            </form>
                                                            <!-- END FORM-->
                                                        </div>
                                                    </div>
                                                

                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PAGE BASE CONTENT -->
                            </div>
                        </div>
                    </div>
                    <!-- END SIDEBAR CONTENT LAYOUT -->                    