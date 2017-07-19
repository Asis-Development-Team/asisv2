                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        
                        <h1 class="hide">Add New Product</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/data/product">Product</a>
                            </li>
                            <li class="active">Add Product</li>
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
                                                                <i class="fa fa-database"></i>Add Product </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="/admin/data_product_save" class="horizontal-form form-add-edit secure-formx" id="product" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    <h3 class="form-section hidden">Add Product</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Product Name</label>
                                                                                <input type="text" id="name" class="form-control requiredField" name="name" placeholder="Product Name">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group has-errorx">
                                                                                <label class="control-label">Product Code / SKU</label>
                                                                                <input type="text" id="code" name="code" class="form-control requiredField" placeholder="Product Code">
                                                                                <span class="help-block"> </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">

                                                                        <div class="col-md-6">


                                                                            <div class="col-md-4" style="padding-left:0; margin-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Price <span class="required">*</span></label>
                                                                                    <input type="text" id="price" name="price" class="form-control requiredField number-only-price" placeholder="12.99" maxlength="25">
                                                                                    <span class="help-block">number only (dot allowed)</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Size <span class="required">*</span></label>
                                                                                    <input type="text" name="size" id="mask_number2x" class="form-control number-onlyx requiredField" placeholder="S,M,L,XL,XXL or 39,40,41" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4" style="margin-left:0; padding-left:0">                                                
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Color <span class="required">*</span></label>
                                                                                    <input type="text" name="color" id="mask_numberx" class="form-control number-onlyx requiredField" placeholder="Red,Green,Blue" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>



                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">

                                                                            <div class="col-md-6" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Tags <span class="required">&nbsp;</span></label>
                                                                                    <input type="text" name="tags" id="tags" class="form-control number-onlyx requiredFieldx" placeholder="Tags" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Set Label As <span class="required">&nbsp;</span></label>
                                                                                    
                                                                                    <select name="label" id="label" class="form-control">
                                                                                        <option value=""></option>
                                                                                        <option value="New">New</option>
                                                                                        <option value="Hot">Hot</option>
                                                                                        <option value="Sale">Sale</option>
                                                                                    </select>

                                                                                    <span class="help-block">&nbsp;</span>
                                                                                </div>
                                                                            </div>

                                                                            

                                                                        </div>
                                                                        <!--/span-->


                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">
                                                                        <div class="col-md-6">

                                                                            <div class="form-group">
                                                                                <label class="control-label">Category <span class="required">*</span></label>
                                                                                <br />
                                                                                <?php /*
                                                                                <select class="select2_category form-control requiredField" name="category" id="category" data-placeholder="Choose a Category" tabindex="1">
                                                                                    <option value="">- Select Category -</option>
                                                                                    <option value="Category 1">Category 1</option>
                                                                                    <option value="Category 2">Category 2</option>
                                                                                    <option value="Category 3">Category 5</option>
                                                                                    <option value="Category 4">Category 4</option>
                                                                                </select>
                                                                                */ ?>
                                                                                <select id="product-category" name="categories[]" class="requiredField" multiple="multiple">
                                                                                    
                                                                                    <?php 
                                                                                    $request    =   $this->data->setting_product_category(false,false,false,true,false);

                                                                                    foreach($request['result'] as $category): 
                                                                                    ?>
                                                                                    <option value="<?php print $category['category_id'] ?>"><?php print $category['category_name'] ?></option>

                                                                                        <?php 
                                                                                        $request    =   $this->data->setting_product_category(false,false,false,false,$category['category_id']);
                                                                                        foreach($request['result'] as $category): 
                                                                                        ?>
                                                                                        <option value="<?php print $category['category_id'] ?>">&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<?php print $category['category_name'] ?></option>
                                                                                        
                                                                                            <?php 
                                                                                            $request    =   $this->data->setting_product_category(false,false,false,false,$category['category_id']);
                                                                                            foreach($request['result'] as $category): 
                                                                                            ?>
                                                                                            <option value="<?php print $category['category_id'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<?php print $category['category_name'] ?></option>
                                                                                            <?php endforeach; ?>

                                                                                        <?php endforeach; ?>

                                                                                    <?php endforeach; ?>
                                                                                    
                                                                                </select>                                                               
                                                                                
                                                                            </div>



                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">


                                                                            <div class="form-group">
                                                                        
                                                                                <div class="col-md-6" style="margin-left:0; padding-left:0">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Weight (gram) <span class="required">*</span></label>
                                                                                        <input type="text" name="weight" id="mask_number2" class="form-control number-only requiredField" placeholder="weight in gram">
                                                                                        <span class="help-block">number only</span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6" style="margin-left:0; padding-left:0">                                                
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Stock <span class="required">*</span></label>
                                                                                        <input type="text" name="stock" id="stock" class="form-control number-only requiredField" placeholder="Stock">
                                                                                        <span class="help-block">number only</span>
                                                                                    </div>
                                                                                </div>


                                                                                
                                                                            </div>

                                                                        </div>
                                                                        <!--/span-->
                                                                    </div>
                                                                    <!--/row-->
                                                                    <div class="row">

                                                                        <div class="col-md-12 ">
                                                                            <div class="form-group">
                                                                                <label>Product Short Description <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-short-description" name="product-short-description" rows="6"></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="product-short-description-hidden" name="product-short-description-hidden" rows="6"></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                    <div class="row">

                                                                        <div class="col-md-12 ">
                                                                            <div class="form-group">
                                                                                <label>Product Description <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-description" name="product-description" rows="6"></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="product-description-hidden" name="product-description-hidden" rows="6"></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="row">

                                                                        <div class="col-md-12 ">
                                                                            <div class="form-group">
                                                                                <label>Product More Information <span class="required" style="color:#ff0000;">&nbsp;</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-info" name="product-info" rows="6"></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredFieldx" id="product-info-hidden" name="product-info-hidden" rows="6"></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                    <div class="row">

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Add Images <span class="required" style="color:#ff0000;">*</span></label>
                                                                                
                                                                                <input type="file" class="form-controlx custom-upload" name="images[]" id="image-upload" multiple="multiple" accept="image/*" />
                                                                                <span class="help-block">.jpg / .png only. max file size 2 Mb. Please upload large image size, ex: 1024 x 768 pixels or higher.</span>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <!--/row-->

                                                       


                                                                </div>


                                                                <div class="form-actions rightx">


                                                                    <input type="hidden" name="identifier" value="add" />
                                                                    <input type="hidden" name="image-counter" class="requiredField" value="" id="image-counter" />                                                                                                                                        
                                                                    
                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Save
                                                                        <i class="fa fa-spinner fa-spin" style="display:none"></i>
                                                                    </button>

                                                                    <span class="save-loadingx" style="display:none">
                                                                        <i class="fa fa-spinnerx fa-spin"></i>
                                                                    </span>                                            


                                                                    <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>" type="button" class="btn default pull-right">Cancel</a>
                                                                    
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