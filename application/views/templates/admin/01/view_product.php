                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1 class="hidden">Products</h1>

                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li class="active">Products</li>
                        </ol>

                        <div class="pull-right">
 
                                
                            <form name="search-product" id="form-search-product" method="get">
                                            
                                <div style="float:left; margin-right:4px;"><input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" /></div>
                                <div style="float:left; margin-right:4px;">

                                    <select name="category" class="form-control">
                                        <option value="">- all category -</option>
                                        
                                        <?php 
                                        $request    =   $this->data->setting_product_category(false,false,false,true,false);
                                        
                                        foreach($request['result'] as $category):

                                            $selected   =   (@$_GET['category'] == $category['category_id']) ? 'selected' : '';

                                            print '<option value="'.$category['category_id'].'" '.$selected.'>'.$category['category_name'].'</option>';


                                                //get child category
                                                $request2    =   $this->data->setting_product_category(false,false,false,false,$category['category_id']);

                                                foreach($request2['result'] as $category2):

                                                     $selected2   =   (@$_GET['category'] == $category2['category_id']) ? 'selected' : '';

                                                     print '<option value="'.$category2['category_id'].'" '.$selected2.'>&nbsp;&nbsp;&nbsp;&raquo;&nbsp;'.$category2['category_name'].'</option>';


                                                        //get grand child
                                                        $request3    =   $this->data->setting_product_category(false,false,false,false,$category2['category_id']);

                                                        foreach($request3['result'] as $category3):


                                                             $selected3   =   (@$_GET['category'] == $category3['category_id']) ? 'selected' : '';

                                                             print '<option value="'.$category3['category_id'].'" '.$selected3.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;'.$category3['category_name'].'</option>';

                                                             unset($selected3);
                                                        endforeach;


                                                     unset($selected2);

                                                endforeach;

                                            unset($selected);

                                        endforeach;
                                        ?>

                                    </select>

                                </div>
                                
                                <div style="float:left; margin-right:0px;">
                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
                                    <?php /*
                                    <button id="export-product-to-excel" class="btn green tooltips" data-container="body" data-placement="bottom" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></button>
                                    */ ?>
                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
                                    <a href="/admin/data/product/export" id="export-product-to-excel" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
                                    
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
                                                    <i class="fa fa-database"></i>Data Products (<span class="total-entry"><?php print $total; ?></span>) 
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
                                                                <th class="text-center">
                                                                    <i class=""></i> Product Name / Code </th>
                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Category </th>
                                                                <th class="text-center">
                                                                    <i class=""></i> Price ($ USD) </th>
                                                                <th class="text-center"> Stock </th>
                                                                <th class="text-center"> Sales </th>
                                                                <th class="text-center"> Image </th>

                                                                <th> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php 
                                                            foreach($result as $result):
                                                            ?>
                                                            <tr class="parent-<?=$result['product_id']?> counter">
                                                                <td class="center">
                                                                    <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['product_id'] ?>" id="checkbox-delete-<?php print $result['product_id'] ?>" name="multichecked[]" />
                                                                </td>

                                                         
                                                                <td>
                                                                         <?php 
                                                                         print $result['product_name'] . ' [' . $result['product_sku'] .']'
                                                                         ?>
                                                                    
                                                                </td>
                                                                <td class="hidden-xs text-center"> 
                                                                    
                                                                    <?php 

                                                                    $category   =   $this->data->setting_product_category();

                                                                    $array      =   explode(',',$result['category_id']);
                                                                    $kategori   =   '';

                                                                    foreach($category['result'] as $category):
                                                                        
                                                                        for($i=0;$i<=count($array)-1;$i++):
                                                                            
                                                                            if($array[$i] == $category['category_id']):
                                                                                $kategori   .=  $category['category_name'].', ';
                                                                            endif;
                                                                            
                                                                        endfor; 
                                                                        
                                                                    endforeach;     
                                                                    
                                                                    $kategori   =   substr($kategori,0,-2);
                                                                    
                                                                    print $kategori;
                                                                
                                                                    

                                                                    //print $product['category_name_en'];
                                                                                                
                                                                    ?>
                                                                </td>
                                                                <td> 
                                                                     <div class="text-center">
                                                                     <?php print $this->tools->format_angka($result['product_price'],0); ?>
                                                                     </div>                                                                
                                                                </td>
                                                                
                                                                <td>
                                                                    
                                                                    <div style="text-align:center"><?php print $this->tools->format_angka($result['product_stock'],0); ?></div>

                                                                </td>
                                                                <td>
                                                                    
                                                                    <div style="text-align:center"><?php print $result['product_sales']; ?></div>

                                                                </td>

                                                                <td>
                                                                     <?php 
                                                                     $query =   $this->db->query("
                                                                                    SELECT * FROM ".$this->db->dbprefix."product_images 
                                                                                    WHERE product_id = '".addslashes($result['product_id'])."' && image_default = '1'
                                                                                ");
                                                                    
                                                                     $fetch =   $query->row_array();
                                                                     ?>
                                                                     <div style="text-align:center">
                                                                     <img src="/product/medium/<?php print $fetch['image_name']; ?>" />
                                                                     </div>                                                                    
                                                                </td>

                                                                <td>

                                                                    <div class="text-center">
                                                                    <a href="/admin/data/product/edit/?id=<?php print $result['product_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
                                                                    <a href="javascript:;" class="delete-single tooltips" data-type="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result['product_id'] ?>" id="delete-product-<?php print $result['product_id'] ?>"><i class="fa  fa-trash-o"></i></a>
                                                                    </div>                                                                    

                                                                </td>


                                                            </tr>
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