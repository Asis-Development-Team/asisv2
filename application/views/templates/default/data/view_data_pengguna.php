                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/dashboard">Home</a>
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
	                            <h4><?php print $page_title ?> (<span id="total-data-text"><?php print $total ?></span>)</h4>
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

		                                    <div class="btn-group">
		                                        <a class="btn blue btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> 
		                                        	<?php 
		                                        	print	(@$_GET['show']) ? @$_GET['show'] : 'Tampilkan';
		                                        	?>
		                                            <i class="fa fa-angle-down"></i>
		                                        </a>
		                                        <ul class="dropdown-menu">
		                                            <li>
		                                                <a href="/data/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&show=20"> 20 </a>
		                                            </li>
		                                            <li>
		                                                <a href="/data/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&show=50"> 50 </a>
		                                            </li>
		                                            <li>
		                                                <a href="/data/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&show=100"> 100 </a>
		                                            </li>
		                                        </ul>
		                                    </div>

			                        <div class="pull-right">
			 

			                            <form name="search-<?php print $page_url ?>" id="form-search-<?php print $page_url ?>" method="get">
			                                
			                                <div class="form-inline">

			                                	<div class="form-group">
			                                		<input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" />

			                                	</div>

                                              <div class="form-group <?php if($this->session->sess_user_level_id > 2){ print 'hidden'; } ?>">
                                                <select class="form-control" name="cabang">

                                                    <option value="">- Semua Outlet -</option>

                                                    <?php 
                                                    foreach($outlets as $cabang): 
                                                        
                                                        $selected   =   (@$_GET['cabang'] == $cabang['cabang_id']) ? 'selected' : '';

                                                        print '<option value="'.$cabang['cabang_id'].'" '.$selected.'>'.$cabang['cabang_nama'].'</option>';

                                                        unset($selected);

                                                    endforeach;
                                                    ?>
                                                    
                                                </select>
                                              </div>                                                

			                                	<div class="form-group">

				                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
				                                    
				   
				                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
				                                    
				                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>

				                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url; ?>/export" id="export-product-to-excel" class="btn green tooltips hide" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
				                                    

			                                	</div>

			                                </div>
			                                             
			                            </form>
			                        </div>                           
			                        




                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th width="2%" class="hide-if-mobile"> </th>
                                                	

                                                	<th scope="col" style="width:60px !important" width="15%"></th>

                                                    <th scope="col" class="text-center" width="40%"> <strong>Nama</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>User ID</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>User Level</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Outlet</strong> </th>
                                                    
                                                                                                        
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<?php 
                                            	foreach($result as $result):
                                            	?>
                                                <tr class="parent-<?php print $result['user_id'] ?> counter">
                                                    
                                                    <td class="center hide-if-mobile">
                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['user_id'] ?>" id="checkbox-delete-<?php //print $result['product_id'] ?>" name="multichecked[]" />
                                                    </td>

                                                    <td class="">
                                                    	
	                                                    <div class="text-center">
	                                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form/?id=<?php print $result['user_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>

                                                        <a href="javascript:;" class="delete-single-baru" data-id="<?php print $result['user_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="user_id" data-container="body" data-placement="top" data-original-title="Delete" class="tooltips">
                                                            <i class="fa  fa-trash-o"></i>
                                                        </a>

                                                        </div>       

                                                    </td>


                                                    <td class="text-left"><span class=""><?php print $result['user_fullname'] ?></span></td>       
                                                    <td class="text-left"><?php print $result['user_username'] ?></td>
                                                    <td class="text-center"><?php print $result['user_level_name'] ?></td> 
                                                    <td class="text-center"><?php print $result['nama_cabang'] ?></td> 
     

                                                </tr>

	                                            <?php endforeach; ?>

                                            </tbody>
                                        </table>

                                    </div>


                                        <div class="row">
                                        	
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-danger btn-delete-checkbox-baru hide-if-mobile" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="user_id"><i class="icon-trash"></i> Delete Selected</button>
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
