                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                        <h1 class="hide">Add New Event</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/data/events">Events</a>
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
                                                                <i class="fa fa-edit"></i>Edit Events </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/add" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-edit "></i> Add Data</button>
                                                                </a>                                                                    

                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="/admin/data_events_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Title (Indonesia)<span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="title-id" name="title-id" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['event_title_id']) ?>">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Title (English) <span class="required" style="color:#ff0000;">*</span></label>
                                                                                <input type="text" id="title-en" name="title-en" class="form-control requiredField" placeholder="" maxlength="200" value="<?php print stripslashes($result['event_title_en']) ?>">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>


                                                                    <div class="row">

                                                                        <div class="col-md-6">

                                                                            <div class="form-group">

                                                                                
                                                                                <?php 
                                                                                $time   =   $this->tools->time_zone();

                                                                                ?>
                                                                                <label>Event Date</label>
                                                                                
                                                                                <div class="input-group input-medium date date-picker" data-date="<?php print $result['event_date']; ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                                                                                    
                                                                                    <input size="16" readonly="" class="form-control requiredField" type="text" name="date" value="<?php print $result['event_date']; ?>">

                                                                                    <span class="input-group-btn">  
                                                                                        <button class="btn default date-reset" type="button">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                        <button class="btn default date-set" type="button">
                                                                                            <i class="fa fa-calendar"></i>
                                                                                        </button>
                                                                                    </span>

                                                                                </div>

                                                                                

                                                                            </div>


                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">

                                                                                    <label>Event Time</label>
                                                                                    <div class="input-group">
                                                                                        
                                                                                        <input type="text" class="form-control timepicker timepicker-24" name="time" readonly="" value="<?php print $result['event_time'] ?>"> 

                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn default" type="button">
                                                                                                <i class="fa fa-clock-o"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>

                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <!--/row-->


                                                                    <br clear="all" />
                                                                    <div class="row">

                                                                        <div class="col-md-6 ">
                                                                            <div class="form-group">
                                                                                <label>Event Detail (Indonesia) <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="detail-id" name="detail-id" rows="6"><?php print stripslashes($result['event_detail_id']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="detail-id-hidden" name="detail-id-hidden" rows="6"><?php print stripslashes($result['event_detail_id']) ?></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6 ">
                                                                            <div class="form-group">
                                                                                <label>Event Detail (English) <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="detail-en" name="detail-en" rows="6"><?php print stripslashes($result['event_detail_en']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="detail-en-hidden" name="detail-en-hidden" rows="6"><?php print stripslashes($result['event_detail_en']) ?></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>       

                                                                    <h3>Speakers</h3>

                                                                    <?php 
                                                                    $i =   '1';
                                                                    //for($i=1;$i<=5;$i++): 
                                                                    foreach($speaker as $speaker):
                                                                    ?>
                                                                    <div class="row">

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Speaker Name <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                
                                                                                <input type="text" id="speaker-<?php print $i ?>" name="speaker-<?php print $i ?>" class="form-control requiredFieldx" placeholder="" value="<?php print $speaker['speaker_name'] ?>" maxlength="70">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Jabatan <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                <input type="text" id="jabatan-<?php print $i ?>" name="jabatan-<?php print $i ?>" class="form-control requiredFieldx" placeholder="" value="<?php print $speaker['speaker_jabatan'] ?>" maxlength="70">
                                                                                <span class="help-block"> &nbsp; </span>

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Add Image <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                
                                                                                <input type="file" class="form-controlx custom-upload" name="images-<?php print $i ?>[]" id="image-upload" accept="image/*" />
                                                                                <span class="help-block">.jpg / .png only. max file size 2 Mb.</span>
                                                                                
                                                                                <?php if($speaker['speaker_picture']): ?>
                                                                                <p>
                                                                                    <img src="/assets/ivf/images/<?php print $speaker['speaker_picture'] ?>" />
                                                                                </p>
                                                                               <?php endif; ?>

                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" name="speaker-id-<?php print $i ?>" value="<?php print $speaker['speaker_id'] ?>">
                                                                        <input type="hidden" name="speaker-image-<?php print $i ?>" value="<?php print $speaker['speaker_picture'] ?>">


                                                                    </div>
                                                                    
                                                                    <?php 
                                                                        $i++;
                                                                    endforeach; 

                                                                    //tambahan speaker
                                                                    for($y=$i;$y<=5;$y++):
                                                                    ?>
                                                                    
                                                                    <div class="row">

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Speaker Name <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                
                                                                                <input type="text" id="speaker-<?php print $y ?>" name="speaker-<?php print $y ?>" class="form-control requiredFieldx" placeholder="" value="" maxlength="70">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Jabatan <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                <input type="text" id="jabatan-<?php print $y ?>" name="jabatan-<?php print $y ?>" class="form-control requiredFieldx" placeholder="" value="" maxlength="70">
                                                                                <span class="help-block"> &nbsp; </span>

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Add Image <span class="required" style="color:#ff0000;">&nbsp;</span></label>
                                                                                
                                                                                <input type="file" class="form-controlx custom-upload" name="images-<?php print $y ?>[]" id="image-upload" accept="image/*" />
                                                                                <span class="help-block">.jpg / .png only. max file size 2 Mb.</span>
                                                                                

                                                                            </div>
                                                                        </div>



                                                                    </div>

                                                                    <?php
                                                                    endfor;
                                                                    ?>
                                                                    <!--/row-->






                                                                </div>
                                                                <div class="form-actions rightx">
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Update 
                                                                        <i class="fa fa-spinner fa-spin" style="display:none;"></i>
                                                                    </button>
                                                                    <input type="hidden" name="identifier" value="edit" />

                                                                    <input type="hidden" name="id" value="<?php print $result['event_id'] ?>">
                                                                    
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