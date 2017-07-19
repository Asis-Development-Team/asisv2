                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1 class="hidden">Products</h1>

                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li class="active">Product Category</li>
                        </ol>

                        <div class="pull-right">
 
                                
                            <form name="search-product" id="form-search-product" method="get">
                                            
                                <div style="float:left; margin-right:4px;"><input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" /></div>
                                <div style="float:left; margin-right:4px;">

                                <select name="category" class="form-control hidden">
                                    <option value="">- all category -</option>
                                </select>
                                </div>
                                <div style="float:left; margin-right:0px;">
                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
                                    <?php /*
                                    <button id="export-product-to-excel" class="btn green tooltips" data-container="body" data-placement="bottom" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></button>
                                    */ ?>
                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
                                    <a href="/admin/data/product/export" id="export-product-to-excel" class="btn green tooltips hidden" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
                                    
                                </div>
   

                            </form>
                        </div>

                    </div>
                    <!-- END BREADCRUMBS -->                    


                    <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
                    <div class="page-content-container">
                        <div class="page-content-row">
                            
                           <?php /* page side bar goes here */ ?>

                            <div class="page-content-col">


                                <div class="row">

                                    <div class="col-md-12">
                                        <!-- BEGIN SAMPLE TABLE PORTLET-->
                                        <div class="portlet">
                                            <div class="portlet-title">
                                                

                                                <div class="caption">
                                                    <i class="fa fa-gear"></i>Product Category (<span class="total-entry"><?php print $total; ?></span>) 
                                                </div>

                                                <div class="toolsx pull-right">
                                                    <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/add" class="btn green"><i class="fa fa-edit"></i> Add Data</a>
                                                </div>

                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable">
                                                    <table class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%"> </th>
                                                                <th class="text-center" width="80%">
                                                                    <i class=""></i> Category Name </th>
                                            

                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Category Order </th>    

                                                                <th> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php 

                                                            //get parent category only
                                                            $request    =   $this->data->setting_product_category(@$_GET['q'],10,false,true,false);
                                                            $result     =   $request['result'];

                                                            foreach($result as $result):
                                                            ?>
                                                            <tr class="parent-<?=$result['category_id']?> counter">
                                                                <td class="center">
                                                                    <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['category_id'] ?>" id="checkbox-delete-<?php print $result['category_id'] ?>" name="multichecked[]" />
                                                                </td>

                                                         
                                                                <td>
                                                                    <?php print $result['category_name'] ?>
                                                                    
                                                                </td>
                                                                

                                                                <td class="text-center">
                                                                    <?php print $result['category_order']; ?>
                                                                </td>
    

                                                                <td>

                                                                    <div class="text-center">
                                                                    <a href="/admin/setting/product-category/edit/?id=<?php print $result['category_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
                                                                    <a href="javascript:;" class="delete-single tooltips" data-type="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result['category_id'] ?>" id="delete-product-<?php print $result['category_id'] ?>"><i class="fa  fa-trash-o"></i></a>
                                                                    </div>                                                                    

                                                                </td>


                                                            </tr>

                                                                <?php 
                                                                //get first child category
                                                                $request_child  =   $this->data->setting_product_category(@$_GET['q'],false,false,false,$result['category_id']); 
                                                                $result_child   =   $request_child['result'];

                                                                foreach($result_child as $result_child):

                                                                ?>
                                                                    <tr class="parent-<?=$result_child['category_id']?> counter">
                                                                        <td class="center">
                                                                            <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result_child['category_id'] ?>" id="checkbox-delete-<?php print $result_child['category_id'] ?>" name="multichecked[]" />
                                                                        </td>

                                                                 
                                                                        <td>
                                                                            
                                                                            &nbsp;&nbsp;&nbsp;&raquo; <?php print $result_child['category_name'] ?>
                                                                            
                                                                        </td>
                                                                        

                                                                        <td class="text-center">
                                                                            <?php print $result_child['category_order']; ?>
                                                                        </td>
            

                                                                        <td>

                                                                            <div class="text-center">
                                                                            <a href="/admin/setting/product-category/edit/?id=<?php print $result_child['category_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
                                                                            <a href="javascript:;" class="delete-single tooltips" data-type="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result_child['category_id'] ?>" id="delete-product-<?php print $result_child['category_id'] ?>"><i class="fa  fa-trash-o"></i></a>
                                                                            </div>                                                                    

                                                                        </td>


                                                                    </tr>


                                                                            <?php 
                                                                            //get second child category
                                                                            $request_child  =   $this->data->setting_product_category(@$_GET['q'],false,false,false,$result_child['category_id']); 
                                                                            $result_child   =   $request_child['result'];

                                                                            foreach($result_child as $result_child):

                                                                            ?>
                                                                                <tr class="parent-<?=$result_child['category_id']?> counter">
                                                                                    <td class="center">
                                                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result_child['category_id'] ?>" id="checkbox-delete-<?php print $result_child['category_id'] ?>" name="multichecked[]" />
                                                                                    </td>

                                                                             
                                                                                    <td>
                                                                                        
                                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo; <?php print $result_child['category_name'] ?>
                                                                                        
                                                                                    </td>
                                                                                    

                                                                                    <td class="text-center">
                                                                                        <?php print $result_child['category_order']; ?>
                                                                                    </td>
                        

                                                                                    <td>

                                                                                        <div class="text-center">
                                                                                        <a href="/admin/setting/product-category/edit/?id=<?php print $result_child['category_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
                                                                                        <a href="javascript:;" class="delete-single tooltips" data-type="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result_child['category_id'] ?>" id="delete-product-<?php print $result_child['category_id'] ?>"><i class="fa  fa-trash-o"></i></a>
                                                                                        </div>                                                                    

                                                                                    </td>


                                                                                </tr>
                                                                            <?php 
                                                                            endforeach; 
                                                                            //eof first child
                                                                            ?>


                                                                <?php 
                                                                endforeach; 
                                                                //eof first child
                                                                ?>


                                                            <?php endforeach; ?>
          
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>





                                        </div>
                                        <!-- END SAMPLE TABLE PORTLET-->
                                    </div>
                                </div>
                                <!-- END PAGE BASE CONTENT -->

                                <div class="row">

                                    <?php if($total): ?>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-danger btn-delete-checkbox" data-type="<?php print $page_identifier ?>"><i class="icon-trash"></i> Delete Selected</button>
                                     </div>
                                     <?php endif; ?>        

                                    <div class="col-md-6 text-right">

                                        <ul class="pagination pagination-sm">
                                        <?php 
                                        print $paging;
                                        ?>                       
                                        </ul>

                                        <?php /*                            
                                        <ul class="pagination pagination-sm">
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"> 1 </a>
                                            </li>
                                            <li class="active">
                                                <a href="javascript:;"> 2 </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"> 3 </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"> 4 </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"> 5 </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"> 6 </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        */ ?>
                                    </div>                                                                 

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END SIDEBAR CONTENT LAYOUT -->