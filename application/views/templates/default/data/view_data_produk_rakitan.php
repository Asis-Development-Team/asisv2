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
	                            <h4><?php print $page_title ?> (<span id="total-data-textx"><?php print $total ?></span>)</h4>
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
                                                    print   (@$_GET['show']) ? @$_GET['show'] : 'Tampilkan';
                                                    ?>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=20"> 20 </a>
                                                    </li>
                                                    <li>
                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=50"> 50 </a>
                                                    </li>
                                                    <li>
                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=100"> 100 </a>
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
                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form" class="btn green tooltips " data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>
                                                <a href="javascript:;" data-href="/admin/<?php print $page_url; ?>/export" id="export-product-to-excel" class="btn green tooltips " data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="glyphicon glyphicon-export "></i></a>

                                              </div>
                                            </div>                                             
                                            			                                
			                                
			   

			                            </form>
			                        </div>           


                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th width="2%" class="hide-if-mobile hidden"> </th>
                                                	

                                                	<th scope="col" width="10%"></th>
                                                	
                                                    <th scope="col"  width="10%" class="text-center"> <strong>Kode</strong> </th>
                                                    <th scope="col" class="text-center" width="55%"> <strong>Nama</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Outlet</strong> </th>
                                                    <th scope="col" class="text-center bold" width="10%">Rakit</th>
                                                                     	                                   
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<?php 
                                            	foreach($result as $result):
                                            	?>
                                                <tr class="parent-<?php print $result['produk_kode'] ?> counter">
                                                    
                                                    <td class="center hide-if-mobile hidden">
                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['produk_kode'] ?>" id="checkbox-delete-<?php //print $result['product_id'] ?>" name="multichecked[]" />
                                                    </td>

                                                    <td class="">
                                                    	
	                                                    <div class="text-center">
	                                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form/?id=<?php print $result['produk_kode'] ?>&cabang=<?php print $result['cabang_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
	                                                    
                                                        <a href="javascript:;" class="tooltips" data-container="body" data-placement="top" data-original-title="Detail" data-target="#detail-rakitan-<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>" data-toggle="modal">
                                                            <i class="icon-book-open"></i>
                                                        </a>
                                                         
                                                        
                                                        <a href="/cetak/rakitan?no=<?php print $result['produk_kode'] ?>&cabang=<?php print $result['cabang_id'] ?>" class="print-rakitan tooltips various fancybox.iframe" id="print-rakitan-<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Cetak">
                                                            <i class="icon-printer"></i>
                                                        </a>
                                                        

	                                                    <a href="javascript:;" class="delete-single-baru hidden" data-id="<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="setting_produk_rakitan_detail" data-field="rakitan_detail_kode_produk" data-container="body" data-placement="top" data-original-title="Delete" class="tooltips">
	                                                    	<i class="fa  fa-trash-o"></i>
	                                                    </a>

	                                                    </div>       

                                                    </td>

                                                    

                                                    <td class="text-center"><span class="bold text-center"><?php print $result['produk_kode'] ?></span></td>       
                                                    <td class="text-left"><?php print $result['product_nama']. ' - ' . $result['merk_nama'] .' - '.$result['category_nama'] ?></td>
                                                    <td class="text-center"><?php print $result['nama_cabang'] ?></td> 
                                                    <td class="text-center">
                                                    	<button class="btn blue tombol-rakit" id="tombol-rakit-<?php print $result['produk_kode'] ?>" data-kode="<?php print $result['produk_kode'] ?>" data-cabang="<?php print $result['cabang_id'] ?>" data-identify="<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>"><i class="fa fa-cog" id="fa-cog-<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>"></i> rakit</button>
                                                    </td>

                                                </tr>            

                                                <div id="detail-rakitan-<?php print $result['produk_kode'] ?>-<?php print $result['cabang_id'] ?>" class="modal container" tabindex="-1">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Detail Rakitan: <?php print $result['produk_kode'] ?>: <?php print $result['product_nama']. ' [' . $result['merk_nama'] .' - '.$result['category_nama'] ?>]</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="col-md-1 bold">No</div>
                                                        <div class="col-md-9 bold">Nama Barang</div>
                                                        <div class="col-md-2 bold text-center">Jumlah</div>
                                                        <div class="col-md-12 bold"><hr /></div>

                                                        
                                                        <?php 
                                                        $request    =   $this->data_lib->data_produk_rakitan_detail_single('kode_produk',$result['produk_kode'],$result['cabang_id']);
                                                        $no     =   1;

                                                        foreach($request['result'] as $detail):
                                                        ?>
                                                        <div class="col-md-1"><?php print $no; ?></div>
                                                        <div class="col-md-9"><?php print $detail['nama_produk']  ?></div>
                                                        <div class="col-md-2 text-center"><?php print $detail['rakitan_detail_jumlah']  ?></div>
                                                        <div class="col-md-12"><hr /></div>
                                                        <?php 
                                                            $no++;
                                                        endforeach; 
                                                        ?>

                                                        <br clear="all">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Tutup</button>
                                                        
                                                    </div>
                                                </div>               

	                                            <?php endforeach; ?>



                                            </tbody>
                                        </table>

                                    </div>


                                        <div class="row">
                                        	
		                                    <div class="col-md-6">
		                                        <button type="button" class="hidden btn btn-danger btn-delete-checkbox-baru hide-if-mobile" data-controller="<?php print $this->uri->segment('1') ?>" data-page="setting_produk_rakitan" data-field="rakitan_product_kode"><i class="icon-trash"></i> Delete Selected</button>
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
