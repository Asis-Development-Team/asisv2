                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active">Tables</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->


                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <!-- BEGIN PAGE TITLE -->

                        <div class="col-md-4"  style="margin-left: 0; padding-left: 0">
	                        <div class="page-title">
	                            <h1>Responsive Datatables</h1>
	                        </div>
                        </div>
                        <!-- END PAGE TITLE -->

                        <div class="col-md-8 pull-right">


                        	
	                        <div class="pull-right">
	 
	                                
	                            <form name="search-product" id="form-search-product" method="get">
	                                
	               
	
	                                
	                                <div style="float:left; margin:5px 4px 0 0;">
	                                	<input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" />
	                                </div>
	                                <div style="float:left; margin:5px 4px 0 0;">

	                                    <select name="category" class="form-control">
	                                        <option value="">- all category -</option>
									    </select>

	                                </div>
	                                
	                                <div style="float:right; margin:5px 0 5px 5px;">

	                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
	                                    
	   
	                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
	                                    <a href="/admin/data/product/export" id="export-product-to-excel" class="btn green tooltips hide" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
	                                    
	                                </div>
	                                
	   

	                            </form>
	                        </div>      
	                      

                        </div>                  

                    </div>
                    <!-- END PAGE HEAD-->

                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box purplex">

                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th width="2%" class="hide-if-mobile"> </th>
                                                	

                                                	<th scope="col" style="width:60px !important"></th>

                                                    <th scope="col"> Column header 1 </th>
                                                    
                                                    <?php for($i=2;$i<=10;$i++): ?>
                                                    <th scope="col"> Column header 2 </th>
                                                	<?php endfor; ?>

                                                	
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	
                                                <tr class="parent-<?php //print $result['product_id']?> counter">
                                                    
                                                    <td class="center hide-if-mobile">
                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php //print $result['product_id'] ?>" id="checkbox-delete-<?php //print $result['product_id'] ?>" name="multichecked[]" />
                                                    </td>

                                                    <td>
                                                    	
	                                                    <div class="text-center">
	                                                    <a href="/admin/data/product/edit/?id=" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
	                                                    <a href="javascript:;" class="delete-single tooltips" data-type="<?php //print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php //print $result['product_id'] ?>" id="delete-product-<?php //rint $result['product_id'] ?>"><i class="fa  fa-trash-o"></i></a>
	                                                    </div>       

                                                    </td>


                                                    <td> Table data </td>

                                                    <?php for($i=2;$i<=10;$i++): ?>
                                                    <td> Table data </td>
                                                	<?php endfor; ?>
                                                    

                                                </tr>
	                                            



                                            </tbody>
                                        </table>

                                    </div>


                                        <div class="row">
                                        	
		                                    <div class="col-md-6 hide-if-mobile">
		                                        <button type="button" class="btn btn-danger btn-delete-checkbox" data-type="<?php print $page_identifier ?>"><i class="icon-trash"></i> Delete Selected</button>
		                                     </div>
		                                    

                                        	<div class="col-md-6 text-right">

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
	                                        </div>

                                        </div><!--eof row-->

                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->


                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
