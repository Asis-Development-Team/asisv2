                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                        <h1 class="hide">Add New Event</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/setting/product-category">Product Category</a>
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
                                                                <i class="fa fa-edit"></i>Edit Product Category </div>
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
                                                            <form action="/admin/setting_product_category_save" class="horizontal-form form-add-edit" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Category Name</label>
                                                                                <input type="text" id="name" name="name" class="form-control requiredField" placeholder="" value="<?php print $result['category_name'] ?>" maxlength="100">
                                                                                <span class="help-block"> &nbsp;</span>
                                                                            </div>
                                                                        </div>

                                                                    </div>



                                                                    <div class="row">

                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Parent Category </label>
                                                                                
                                                                                <?php 
                                                                                $request =  $this->data->setting_product_category(false,false,false, true);
                                                                                ?>

                                                                                <select name="parent" class="form-control">
                                                                                    <option value="0"></option>
                                                                                    <?php 
                                                                                    foreach($request['result'] as $category): 
                                                                                        
                                                                                        $selected   =   ($result['parent_id'] == $category['category_id']) ? 'selected' : '';

                                                                                    ?>
                                                                                    <option value="<?php print $category['category_id'] ?>" <?php print $selected ?>><?php print $category['category_name'] ?></option>

                                                                                        <?php 
                                                                                        $request    =   $this->data->setting_product_category(false,false,false,false,$category['category_id']);

                                                                                        foreach($request['result'] as $category_child):

                                                                                            $selected2   =   ($result['parent_id'] == $category_child['category_id']) ? 'selected' : '';

                                                                                        ?>
                                                                                        <option value="<?php print $category_child['category_id'] ?>" <?php print $selected2 ?>>&nbsp;&nbsp;&raquo; <?php print $category_child['category_name'] ?></option>
                                                                                        
                                                                                            <?php 
                                                                                            /*
                                                                                            $request    =   $this->data->setting_product_category(false,false,false,false,$category_child['category_id']);

                                                                                            foreach($request['result'] as $category_child):
                                                                                                $selected3   =   ($result['parent_id'] == $category_child['category_id']) ? 'selected' : '';
                                                                                            ?>
                                                                                            <option value="<?php print $category_child['category_id'] ?>" <?php print $selected3 ?>>&nbsp;&nbsp;&nbsp;&nbsp;&raquo; <?php print $category_child['category_name'] ?></option>
                                                                                            <?php 
                                                                                                unset($selected3);
                                                                                            endforeach; 
                                                                                            */
                                                                                            ?>

                                                                                        <?php 
                                                                                            unset($selected2);
                                                                                        endforeach; 
                                                                                        ?>

                                                                                    <?php 
                                                                                        unset($selected);
                                                                                    endforeach; 
                                                                                    ?>
                                                                                </select>

                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">

                                                                            <div class="form-group">
                                                                                <label class="control-label">Category Order</label>
                                                                                <input type="text" id="category-order" name="category-order" class="form-control requiredFieldx" placeholder="" value="<?php print $result['category_order'] ?>" maxlength="3">
                                                                                <span class="help-block"> number only </span>
                                                                            </div>

                                                                        </div>


                                                                    </div>
                                                                    <!--/row-->


                                                                </div>
                                                                <div class="form-actions rightx">
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Update
                                                                        <i class="fa fa-spinner fa-spin" style="display:none"></i>
                                                                    </button>
                                                                    <input type="hidden" name="identifier" value="edit" />
                                                                    <input type="hidden" name="id" value="<?php print $result['category_id'] ?>" />
                                                                    
                                                                    <input type="hidden" name="image-counter" class="requiredFieldx" value="" id="image-counter" /> 

                                                                    <span class="save-loadingx" style="display:none">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span>       

                                                                    <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/<?php print $this->uri->segment('4') ?>" class="btn default pull-right">
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