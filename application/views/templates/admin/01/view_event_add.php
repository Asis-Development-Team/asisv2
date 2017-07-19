                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                        <h1 class="hide">Add New Event</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/data/event">Event</a>
                            </li>
                            <li class="active">Add Event</li>
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
                                                                <i class="fa fa-calendar"></i>Add Event </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>

                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="#" class="horizontal-form">
                                                                <div class="form-body">
                                                                    <h3 class="form-section">Person Info</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">First Name</label>
                                                                                <input type="text" id="firstName" class="form-control" placeholder="Chee Kin">
                                                                                <span class="help-block"> This is inline help </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group has-error">
                                                                                <label class="control-label">Last Name</label>
                                                                                <input type="text" id="lastName" class="form-control" placeholder="Lim">
                                                                                <span class="help-block"> This field has error. </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Gender</label>
                                                                                <select class="form-control">
                                                                                    <option value="">Male</option>
                                                                                    <option value="">Female</option>
                                                                                </select>
                                                                                <span class="help-block"> Select your gender </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Date of Birth</label>
                                                                                <input type="text" class="form-control" placeholder="dd/mm/yyyy"> </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Category</label>
                                                                                <select class="form-control" data-placeholder="Choose a Category" tabindex="1">
                                                                                    <option value="Category 1">Category 1</option>
                                                                                    <option value="Category 2">Category 2</option>
                                                                                    <option value="Category 3">Category 5</option>
                                                                                    <option value="Category 4">Category 4</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Membership</label>
                                                                                <div class="radio-list">
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked> Option 1 </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Option 2 </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <h3 class="form-section">Address</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-12 ">
                    
                                                                            <div class="form-group">
                                                                                <label>Product Description <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-description-en" name="product-description-en" rows="6"></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="product-description-en-hidden" name="product-description-en-hidden" rows="6"></textarea>
                                                                                                                                                                                     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>City</label>
                                                                                <input type="text" class="form-control"> </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>State</label>
                                                                                <input type="text" class="form-control"> </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Post Code</label>
                                                                                <input type="text" class="form-control"> </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Country</label>
                                                                                <select class="form-control"> </select>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                </div>
                                                                <div class="form-actions rightx">
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Save
                                                                    </button>

                                                                    <button type="button" class="btn default">Cancel</button>
                                                                    
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