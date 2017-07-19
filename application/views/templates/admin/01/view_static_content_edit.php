                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                        <h1 class="hide">Edit Pages</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/setting/pages">Pages</a>
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
                                                                <i class="fa fa-edit"></i>Edit Page </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>
                                                                <a class="hide" href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/add" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-edit "></i> Add Data</button>
                                                                </a>                                                                    

                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="/admin/setting_pages_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Title <span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="title" name="title" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['static_title']) ?>">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <br clear="all" />
                                                                    <div class="row">

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Page Content <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="detail-id" name="detail-id" rows="6"><?php print stripslashes($result['static_content']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="detail-id-hidden" name="detail-id-hidden" rows="6"><?php print stripslashes($result['static_content']) ?></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>       









                                                                </div>
                                                                <div class="form-actions rightx">
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Update 
                                                                        <i class="fa fa-spinner fa-spin" style="display:none;"></i>
                                                                    </button>
                                                                    <input type="hidden" name="identifier" value="edit" />

                                                                    <input type="hidden" name="id" value="<?php print $result['static_id'] ?>">
                                                                    
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