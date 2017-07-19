                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/admin/dashboard">Home</a>
                        </li>
                        <li>
                            <span class="active"><?php print $page_title ?></span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->


                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <!-- BEGIN PAGE TITLE -->

                        <div class="col-md-12"  style="margin-left: 0; padding-left: 0">
	                        <div class="page-title">
	                            <h4><?php print $page_title ?></h4>
	                        </div>
                        </div>
                        <!-- END PAGE TITLE -->

                

                    </div>
                    <!-- END PAGE HEAD-->

                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box purplex">

                                <div class="portlet-body">





			                        <div class="pull-right">
			 

			                            <form name="search-<?php print $page_url ?>" id="form-search-<?php print $page_url ?>" method="get">
			                                
			                                              
			                                <div style="float:left; margin:5px 4px 0 0;">
			                                	<input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" />
			                                </div>

			                                
			                                <div style="float:right; margin:5px 0 5px 5px;">

			                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
			                                    
			   
			                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
			                                    
			                                    <a href="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url ?>-form" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>

			                                    <a href="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url; ?>/export" id="export-product-to-excel" class="btn green tooltips hide" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
			                                    


		                                                                                   
			                                </div>
			                                
			   

			                            </form>
			                        </div>           


                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th width="2%" class="hide-if-mobile hidden"> </th>
                                                	

                                                	<th scope="col" style="width:60px !important" width="15%"></th>

                                                    <th scope="col"  width="40%"> <strong>Menu Name</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Menu Link</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Status</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Position</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Icon</strong> </th>

                                                    
                                                    
                                                                                                        
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<?php 
                                            	foreach($result as $result):
                                            	?>
                                                <tr class="parent-<?php print $result['menu_id'] ?> counter">
                                                    
                                                    <td class="center hide-if-mobile hidden">
                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php //print $result['menu_id'] ?>" id="checkbox-delete-<?php //print $result['menu_id'] ?>" name="multichecked[]" />
                                                    </td>

                                                    <td class="">
                                                    	
	                                                    <div class="text-center">
	                                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form/?id=<?php print $result['menu_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
	                                                    
	                                                    <?php if($this->db->count_all($this->db->dbprefix."setting_menu WHERE parent_id = '".$result['menu_id']."'") < 1): ?>
	                                                    <a href="javascript:;" class="delete-single-baru tooltips" data-container="body" data-placement="top" data-original-title="Delete"><i class="fa  fa-trash-o"></i></a>
	                                                    <?php endif; ?>


	                                                    <a href="javascript:;" class="delete-single-new hidden" data-toggle="confirmation" data-singleton="true" data-popout="true">
	                                                    	<i class="fa  fa-trash-o"></i>
	                                                    </a>

	                                                    </div>       

                                                    </td>


                                                    <td><span class="bold"><?php print $result['menu_name'] ?></span></td>       
                                                    <td class="text-left"><?php print $result['menu_url'] ?></td>
                                                    <td class="text-center"><?php print $result['status_menu'] ?></td> 
                                                    <td class="text-center"><?php print $result['menu_position'] ?></td> 
                                                    <td class="text-center"><i class="<?php print $result['menu_icon'] ?>"></i></td>
                                                       

                                                </tr>


                                                    <?php 
                                                    if(!@$_GET['q']):
                                                    //get child
                                                    $request 	=	$this->setting_lib->setting_main_menu(@$_GET['q'],false,$result['menu_id'],false,false);

                                                    foreach($request['result'] as $child):
                                                    ?>

	                                                <tr class="parent-<?php print $child['menu_id'] ?> counter">
	                                                    
	                                                    <td class="center hide-if-mobile hidden">
	                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php //print $result['menu_id'] ?>" id="checkbox-delete-<?php //print $result['menu_id'] ?>" name="multichecked[]" />
	                                                    </td>

	                                                    <td class="">
	                                                    	
		                                                    <div class="text-center">
		                                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form/?id=<?php print $child['menu_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
		                                                    <a href="javascript:;" class="delete-single-baru tooltips" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result['menu_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="menu_id"><i class="fa  fa-trash-o"></i></a>
		                                                    </div>       

	                                                    </td>


	                                                    <td  style="padding-left: 30px;"><i class="fa fa-angle-rightx"></i>- <?php print $child['menu_name'] ?> </td>       

	                                                    <td class="text-left"><?php print $child['menu_url'] ?></td>
	                                                    <td class="text-center"><?php print $child['status_menu'] ?></td> 
	                                                    <td class="text-center"><?php print $child['menu_position'] ?></td> 
	                                                    <td class="text-center"><i class="<?php print $child['menu_icon'] ?>"></i></td>
	                                                         

	                                                </tr>
                                                	<?php 
                                                		endforeach; 
                                                	endif;
                                                	?>



	                                            <?php endforeach; ?>



                                            </tbody>
                                        </table>

                                    </div>


                                        <div class="row">
                                        	
		                                    <div class="col-md-6 hide-if-mobile hidden">
		                                        <button type="button" class="btn btn-danger btn-delete-checkbox" data-type="<?php print $page_identifier ?>"><i class="icon-trash"></i> Delete Selected</button>
		                                     </div>
		                                    

                                        	<div class="col-md-6 text-right">

	                                            <ul class="pagination pagination-sm">

	                                            	<?php

	                                            	print $paging
	                                            	?>
	                                            </ul>        
	                                        </div>

                                        </div><!--eof row-->

                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->


                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
