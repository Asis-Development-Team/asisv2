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

                        <div class="col-md-8"  style="margin-left: 0; padding-left: 0">
	                        <div class="page-title">
	                            <h4><?php print $page_title ?> <?php if($this->session->sess_user_level_id < '3'){ print (@$_GET['cabang']) ? $outlet['result']['cabang_nama'] : ''; } ?> (<span id="total-data-text"><?php print $total ?></span>)</h4>
	                        </div>
                        </div>
                        <!-- END PAGE TITLE -->

                        <div class="col-md-4 text-right hidex">

                            <div class="btn-group">
                                <a class="btn blue btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> 
                                    <?php 
                                    print   (@$_GET['show']) ? @$_GET['show'] : 'Tampilkan';
                                    ?>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=20&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&pembayaran=<?php print @$_GET['pembayaran'] ?>"> 20 </a>
                                    </li>
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=50&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&pembayaran=<?php print @$_GET['pembayaran'] ?>"> 50 </a>
                                    </li>
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=100&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&pembayaran=<?php print @$_GET['pembayaran'] ?>"> 100 </a>
                                    </li>
                                </ul>
                            </div>

                            <?php /*
                            <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>
                            <a href="javascript:;" data-href="/admin/<?php print $page_url; ?>/export" id="export-product-to-excel" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="glyphicon glyphicon-export "></i></a>
                            */ ?>

                        </div>

               

                    </div>
                    <!-- END PAGE HEAD-->

                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">

                        <div class="col-md-12">

                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box purplex">

                                <div class="portlet-body">

                                            <?php /*
		                                    <div class="btn-group">
		                                        <a class="btn blue btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> 
		                                        	<?php 
		                                        	print	(@$_GET['show']) ? @$_GET['show'] : 'Tampilkan';
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
                                            */ ?>

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

                                              <div class="form-group <?php if($this->session->sess_user_level_id > 2){ print 'hidden'; } ?>">
                                                <select class="form-control" name="pembayaran">

                                                    <option value="">- Pembayaran -</option>

                                                    <option value="Tunai" <?php if(@$_GET['pembayaran'] == 'Tunai'){ print 'selected'; } ?>>Tunai</option>
                                                    <option value="Tempo" <?php if(@$_GET['pembayaran'] == 'Tempo'){ print 'selected'; } ?>>Tempo</option>
                                                    
                                                </select>
                                              </div>

                                                <div class="form-group">
                                                    
                                                    <div class="input-group input-large date-picker input-daterange" data-date="<?php print date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                                                        <input class="form-control" name="from" type="text" value="<?php print @$_GET['from'] ?>">
                                                        <span class="input-group-addon"> to </span>
                                                        <input class="form-control" name="to" type="text" value="<?php print @$_GET['to'] ?>"> 
                                                    </div>
                                                    <!-- /input-group -->
                                                    
                                                </div>                                              

                                              <div class="form-group">

                                                <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>               
                                                <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form" class="btn green tooltips " data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>
                                                <a href="javascript:;" data-href="/admin/<?php print $page_url; ?>/export" id="export-product-to-excel" class="btn green tooltips hidden" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="glyphicon glyphicon-export "></i></a>

                                              </div>
                                            </div>                                             
			                                
			   

			                            </form>
                                        <br clear="all">
			                        </div>                           

                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th width="2%" class="hide-if-mobile hidden"> </th>
                                                	

                                                	<th scope="col" width="10%"></th>

                                                    <th scope="col"  width="20%" class="text-center"> <strong>No Invoice</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Tanggal</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Kustomer</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Pembayaran</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Total</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Outlet</strong> </th>
                                                                                                        
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<?php 
                                            	foreach($result as $result):


                                                    $encode     =   array(
                                                                        'invoice_id' => $result['invoice_id'],
                                                                        'invoice_nomer_order' => $result['invoice_no_order']
                                                                    );
                                            	?>
                                                <tr class="parent-<?php print $result['invoice_id'] ?> counter">
                                                    
                                                    <?php /*
                                                    <td class="center hide-if-mobile hidden">
                                                        <input type="checkbox" class="checkboxes checkbox-delete hide-from-user" value="<?php print $result['invoice_id'] ?>" id="checkbox-delete-<?php print $result['invoice_id'] ?>" name="multichecked[]" />
                                                    </td>
                                                    */ ?>
                                                    
                                                    <td class="">
                                                    	
	                                                    <div class="text-center">
	                                                    <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form/?id=<?php print $result['invoice_id'] ?>&order=<?php print $result['invoice_no_order'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips hide-from-user"><i class="fa fa-edit"></i></a>

                                                        <a href="javascript:;" class="detail-po tooltips" id="detail-po-<?php print $result['invoice_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="data_penawaran_outlet" data-container="body" data-placement="top" data-original-title="Detail" data-target="#detail-po-<?php print $result['invoice_no_order'] ?>" data-toggle="modal">
                                                            <i class="icon-book-open"></i>
                                                        </a>                                                         

                                                        <a href="/cetak/penjualan?no=<?php print $result['invoice_no_order'] ?>" class="print-po tooltips various fancybox.iframe" id="print-po-<?php print $result['invoice_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Cetak">
                                                            <i class="icon-printer"></i>
                                                        </a>

                                                        <a href="javascript:;" class="delete-single-barux btn-delete-penjualan tooltips hide-from-user" data-id="<?php print $result['invoice_id'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="invoice_id" data-container="body" data-placement="top" data-original-title="Hapus" data-no-order="<?php print $result['invoice_no_order'] ?>">
                                                            <i class="fa  fa-trash-o"></i></a>                                                        

                                                        </div>       

                                                    </td>


                                                    <td class="text-center"><span class=""><?php print stripslashes($result['invoice_no_order']) ?></span></td>       
                                                    <td class="text-center"><?php print $this->tools->tanggal_indonesia($result['invoice_tanggal_faktur']) ?></td>
                                                    <td class="text-left"><?php print $result['pelanggan_nama'] ?></td>
                                                    <td class="text-center"><?php print $result['invoice_status_pembayaran'] ?></td> 
                                                    <td class="text-right"><?php print $this->tools->format_angka($result['invoice_total_setelah_pajak'],2) ?></td> 
                                                    <td class="text-center"><?php print $result['cabang_nama'] ?></td> 
                                                    

                                                </tr>

                                                
                                                <div id="detail-po-<?php print $result['invoice_no_order'] ?>" class="modal container" tabindex="-1">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Detail Penjualan: <?php print $result['invoice_no_order'] ?></h4>
                                                    
                                                        <br clear="all">
                                                        <div class="row">
                                                            <div class="col-md-8">

                                                                <div class="col-md-2">Sales</div>
                                                                <div class="col-md-4">: <?php print $result['nama_sales'] ?></div>

                                                                <br />
                                                                <div class="col-md-2">Keterangan</div>
                                                                <div class="col-md-4">: <?php print $result['invoice_keterangan'] ?></div>

                                                            </div>

                                                            <div class="col-md-4 pull-right"  style="border:0px solid #ff0000">
                                                                <div class="col-md-4 text-right" style="border:0px solid #ff0000">Pembayaran</div>
                                                                <div class="col-md-5" style="border:0px solid #ff0000">: <?php print $result['invoice_status_pembayaran'] ?></div>
                                                                
                                                                
                                                                <br />
                                                                <div class="col-md-4 text-right">
                                                                    <?php 
                                                                    $keterangan = ($result['invoice_status_pembayaran'] == 'Tunai') ? 'Akun Lunas' : 'Jatuh Tempo';
                                                                    print $keterangan;
                                                                    ?>                                                                
                                                                </div>

                                                                <div class="col-md-5">: 
                                                                    <?php print $result['lunas_tempo'] ?>                                                                   
                                                                </div>
                                                                

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="col-md-3 bold">Kode Produk</div>
                                                        <div class="col-md-7 bold">Nama Produk</div>
                                                        <div class="col-md-2 bold text-center">Jumlah</div>
                                                        <div class="col-md-12 bold"><hr /></div>

                                                        
                                                        <?php 
                                                        $request    =   $this->penjualan_lib->data_penjualan_inovice_single('no_order',$result['invoice_no_order']);

                                                        foreach($request['detail'] as $detail):
                                                        ?>
                                                        <div class="col-md-3"><?php print $detail['invoice_detail_kode_produk'] ?></div>
                                                        <div class="col-md-7"><?php print $detail['invoice_detail_nama_produk']  ?></div>
                                                        <div class="col-md-2 text-center"><?php print $detail['invoice_detail_jumlah_produk'] ?> x @<?php print $this->tools->format_angka($detail['invoice_detail_harga'],2) ?></div>

                                                        <div class="col-md-12"><hr /></div>
                                                        <?php endforeach; ?>

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
                                        	
                                            <div class="col-md-6 hide-from-user hidden">
                                                <button type="button" class="btn btn-danger btn-delete-checkbox-baru hide-if-mobile" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="invoice_id"><i class="icon-trash"></i> Delete Selected</button>
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
