                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/dashboard">Home</a>
                        </li>
                        <li>
                            <span class="active blue"><?php print $page_title ?></span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">


                        <div class="col-md-12">


                                    <div class="tab-pane" id="tab_1">

                                        <div class="portlet light bordered">

                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="font-blue-hoki"></i>
                                                    <span class="caption-subject font-blue-hoki bold uppercase">Edit <?php print $page_title_global ; ?></span>
                                                    
                                                </div>

                                            </div>


                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Username <span class="font-red">*</span></label>
                                                                    <input type="text" id="user_username" name="user_username" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['user_username'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Password <span class="font-red">&nbsp;</span></label>
                                                                    <input type="password" id="user_password" name="user_password" class="form-control" placeholder="" value="">
                                                                    <span class="help-block"> 
                                                                    <?php 
                                                                    $kosongkan =  ($form_identifier == 'edit') ? 'kosongkan jika tidak dirubah' : '&nbsp;';
                                                                    print $kosongkan;
                                                                    ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                <label class="control-label">Nama <span class="font-red">*</span></label>
                                                                <input type="text" class="form-control requiredField" name="user_fullname" id="user_fullname" value="<?php print @$result['user_fullname'] ?>">
                                                                <span class="help-block"></span>
                                                                </div>

                                                            </div>



                                                        </div>





                                                    </div>




                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <input type="hidden" name="identifier" value="edit">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>



                                        </div>


                                    </div>



                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->



