                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        
                        <h1 class="hide">Edit Product</h1>
                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/admin/data/product">Product</a>
                            </li>
                            <li class="active">Edit Product</li>
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
                                                                <i class="fa fa-database"></i>Edit Product </div>
                                                            <div class="tools">
                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-arrow-left "></i> Back</button>
                                                                </a>

                                                                <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/add" style="margin:0 0 20px 0">
                                                                <button class="btn default"><i class="fa fa-edit "></i> Add Data</button>
                                                                </a>                                                                   
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body form">
                                                            <!-- BEGIN FORM-->
                                                            <form action="/admin/data_product_save" class="horizontal-form form-add-edit secure-formx" id="product" method="post" enctype="multipart/form-data">
                                                                <div class="form-body">
                                                                    <h3 class="form-section hidden">Edit Product</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Product Name</label>
                                                                                <input type="text" id="name" class="form-control requiredField" name="name" placeholder="Product Name" value="<?php print stripslashes($result['product_name']) ?>" maxlength="150">
                                                                                <span class="help-block"> &nbsp; </span>
                                                                            </div>
                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">
                                                                            <div class="form-group has-errorx">
                                                                                <label class="control-label">Product Code / SKU</label>
                                                                                <input type="text" id="code" name="code" class="form-control requiredField" placeholder="Product Code" value="<?php print stripslashes($result['product_sku']) ?>" maxlegth="50">
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
                                                                                    <input type="text" id="price" name="price" class="form-control requiredField number-only-price" placeholder="12.99" maxlength="25" value="<?php print stripslashes($result['product_price']) ?>">
                                                                                    <span class="help-block">number only (dot allowed)</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Size <span class="required">*</span></label>
                                                                                    <input type="text" name="size" id="mask_number2x" class="form-control number-onlyx requiredField" placeholder="S,M,L,XL,XXL or 39,40,41" value="<?php print stripslashes($result['product_size']) ?>" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4" style="margin-left:0; padding-left:0">                                                
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Color <span class="required">*</span></label>
                                                                                    <input type="text" name="color" id="mask_numberx" class="form-control number-onlyx requiredField" placeholder="Red,Green,Blue" value="<?php print stripslashes($result['product_color']) ?>" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">

                                                                            <div class="col-md-6" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Tags <span class="required">&nbsp;</span></label>
                                                                                    <input type="text" name="tags" id="tags" class="form-control number-onlyx requiredFieldx" placeholder="Tags" value="<?php print stripslashes($result['product_tags']) ?>" maxlength="100">
                                                                                    <span class="help-block">separated by commas</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6" style="margin-left:0; padding-left:0">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Set Label As <span class="required">&nbsp;</span></label>
                                                                                    
                                                                                    <select name="label" id="label" class="form-control">
                                                                                        <option value=""></option>
                                                                                        <option value="New" <?php if($result['product_label'] == 'New'){ print 'selected'; } ?>>New</option>
                                                                                        <option value="Hot" <?php if($result['product_label'] == 'Hot'){ print 'selected'; } ?>>Hot</option>
                                                                                        <option value="Sale" <?php if($result['product_label'] == 'Sale'){ print 'selected'; } ?>>Sale</option>
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

                                                                                <select id="product-category" name="categories[]" class="requiredField" multiple="multiple">
                                                                                    <?php 
                                                                                    $array  =   explode(',', $result['category_id']);

                                                                                    $request    =   $this->data->setting_product_category(false,false,false,true,false);

                                                                                    foreach($request['result'] as $category): 

                                                                                        $selected   =   '';
                                                                                        
                                                                                        for($i=0;$i<=count($array)-1;$i++):
                                                                                        
                                                                                            if($array[$i] == $category['category_id']):
                                                                                                $selected   =   'selected';
                                                                                            endif;
                                                                                            
                                                                                        endfor;                                                                                   
                                                                                    ?>
                                                                                    <option value="<?php print $category['category_id'] ?>" <?php print $selected ?>><?php print $category['category_name'] ?></option>

                                                                                        <?php 
                                                                                        $array2  =   explode(',', $result['category_id']);

                                                                                        $request2    =   $this->data->setting_product_category(false,false,false,false,$category['category_id']);

                                                                                        foreach($request2['result'] as $category2):

                                                                                            $selected2   =   '';
                                                                                            
                                                                                            for($j=0;$j<=count($array2)-1;$j++):
                                                                                            
                                                                                                if($array2[$j] == $category2['category_id']):
                                                                                                    $selected2   =   'selected';
                                                                                                endif;
                                                                                                
                                                                                            endfor;   

                                                                                        ?>
                                                                                        <option value="<?php print $category2['category_id'] ?>" <?php print $selected2 ?>>&nbsp;&nbsp;&nbsp;&raquo;&nbsp;<?php print $category2['category_name'] ?></option>
                                                                                        

                                                                                            <?php 
                                                                                            $array3  =   explode(',', $result['category_id']);

                                                                                            $request3    =   $this->data->setting_product_category(false,false,false,false,$category2['category_id']);

                                                                                            foreach($request3['result'] as $category3):

                                                                                                $selected3   =   '';
                                                                                                
                                                                                                for($k=0;$k<=count($array3)-1;$k++):
                                                                                                
                                                                                                    if($array3[$k] == $category3['category_id']):
                                                                                                        $selected3   =   'selected';
                                                                                                    endif;
                                                                                                    
                                                                                                endfor;   

                                                                                            ?>
                                                                                            <option value="<?php print $category3['category_id'] ?>" <?php print $selected3 ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;<?php print $category3['category_name'] ?></option>
                                                                                            <?php 
                                                                                                unset($selected3);
                                                                                            endforeach;
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

                                                                  
                                                                                
                                                                            </div>



                                                                        </div>
                                                                        <!--/span-->
                                                                        <div class="col-md-6">

                                                                            <div class="form-group">
                                                                        
                                                                                <div class="col-md-4" style="margin-left:0; padding-left:0">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Weight (gram) <span class="required">*</span></label>
                                                                                        <input type="text" name="weight" id="mask_number2" class="form-control number-only requiredField" placeholder="" value="<?php print stripslashes($result['product_weight']) ?>" maxlength="5">
                                                                                        <span class="help-block">number only</span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-4" style="margin-left:0; padding-left:0">                                                
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Stock <span class="required">*</span></label>
                                                                                        <input type="text" name="stock" id="stock" class="form-control number-only requiredField" placeholder="" value="<?php print stripslashes($result['product_stock']) ?>" maxlength="5">
                                                                                        <span class="help-block">number only</span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-4" style="margin-left:0; padding-left:0">                                                
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Sales <span class="required">&nbsp;</span></label>
                                                                                        <input type="text" name="sales" id="sales" class="form-control number-only requiredFieldx" placeholder="" value="<?php print stripslashes($result['product_sales']) ?>" maxlength="7">
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

                                                                                <textarea class="form-control requiredFieldx" id="product-short-description" name="product-short-description" rows="6"><?php print stripslashes($result['product_description_short']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="product-short-description-hidden" name="product-short-description-hidden" rows="6"><?php print stripslashes($result['product_description_short']) ?></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                    <div class="row">

                                                                        <div class="col-md-12 ">
                                                                            <div class="form-group">
                                                                                <label>Product Description <span class="required" style="color:#ff0000;">*</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-description" name="product-description" rows="6"><?php print stripslashes($result['product_description_detail']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredField" id="product-description-hidden" name="product-description-hidden" rows="6"><?php print stripslashes($result['product_description_detail']) ?></textarea>
                                                                                                                                                                                     
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="row">

                                                                        <div class="col-md-12 ">
                                                                            <div class="form-group">
                                                                                <label>Product More Information <span class="required" style="color:#ff0000;">&nbsp;</span></label>

                                                                                <textarea class="form-control requiredFieldx" id="product-info" name="product-info" rows="6"><?php print stripslashes($result['product_additional_info']) ?></textarea>
                                                                                <textarea style="display:none;" class="form-control requiredFieldx" id="product-info-hidden" name="product-info-hidden" rows="6"><?php print stripslashes($result['product_additional_info']) ?></textarea>
                                                                                                                                                                                     
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

                                                                        <br clear="all" />
                                                                        <?php
                                                                        
                                                                        $images =   $this->data->data_product_images($result['product_id']);

                                                                        foreach($images['result'] as $images):
                                                                        ?>
                                                                        <div class="col-md-2 text-centerx" style="border:0px solid #ff0000" id="image-holder-<?php print $images['image_id'] ?>">
                                                                            
                                                                            <img src="/product/medium/<?php print $images['image_name']; ?>" style="border:1px solid #c2c2c2; padding:2px;" />
                                                                            
                                                                            <?php if($images['image_default'] != '1'): ?>
                                                                            <br />
                                                                            <a href="javascript:;" class="tooltips delete-single-image" data-container="body" data-placement="bottom" data-html="true" data-original-title="Delete Image" data-id="<?php print $images['image_id'] ?>" data-type="<?php print $page_identifier ?>"><i class="fa fa-trash-o"></i></a>
                                                                            <?php endif; ?>
                                                                            
                                                                        </div>
                                                                        <?php 
                                                                        endforeach;
                                                                        ?>
                                                                                                                                        

                                                                    </div>
                                                                    <!--/row-->


                                                          

                                                                </div>


                                                                <div class="form-actions rightx">


                                                                    <input type="hidden" name="identifier" value="edit" />
                                                                    <input type="hidden" name="image-counter" class="requiredFieldx" value="" id="image-counter" />                                                                                                                                        
                                                                    <input type="hidden" name="id" value="<?php print $result['product_id'] ?>" />

                                                                    <button type="submit" class="btn blue">
                                                                        <i class="fa fa-check"></i> Update
                                                                        <i class="fa fa-spinner fa-spin" style="display:none"></i>   
                                                                    </button>
                                      


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