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

                        <div class="col-md-4 text-right hidex hidden">

                            <div class="btn-group">
                                <a class="btn blue btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> 
                                    <?php 
                                    print   (@$_GET['show']) ? @$_GET['show'] : 'Tampilkan';
                                    ?>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=20&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&supplier=<?php print @$_GET['supplier'] ?>"> 20 </a>
                                    </li>
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=50&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&supplier=<?php print @$_GET['supplier'] ?>"> 50 </a>
                                    </li>
                                    <li>
                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $this->uri->segment('2') ?>/?q=<?php print @$_GET['q'] ?>&cabang=<?php print @$_GET['cabang'] ?>&show=100&from=<?php print @$_GET['from'] ?>&to=<?php print @$_GET['to'] ?>&supplier=<?php print @$_GET['supplier'] ?>"> 100 </a>
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



                                                <?php /*
                                                <div class="form-group hidden">
                                                    
                                                    <div class="input-group input-large date-picker input-daterange" data-date="<?php print date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                                                        <input class="form-control" name="from" type="text" value="<?php print @$_GET['from'] ?>">
                                                        <span class="input-group-addon"> to </span>
                                                        <input class="form-control" name="to" type="text" value="<?php print @$_GET['to'] ?>"> 
                                                    </div>
                                                    <!-- /input-group -->
                                                    
                                                </div>        
                                                */ ?>                                      

                                              <div class="form-group">

                                                <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>               
                                                <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
                                                
                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url ?>-form" class="btn green tooltips hidden" data-container="body" data-placement="top" data-original-title="Tambah Data"><i class="fa fa-plus"></i></a>
                                                
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
                                                	

                                                	<th scope="col" width="8%"></th>

                                                    <th scope="col" width="14]]%" class="text-center"> <strong>Outlet</strong> </th>
                                                    <th scope="col"   class="text-center"> <strong>Pelanggan</strong> </th>
                                                    <th scope="col"  class="text-center"> <strong>Total Hutang</strong> </th>
                                                    <th scope="col"  class="text-center"> <strong>Total Bayar</strong> </th>
                                                    <th scope="col" class="text-center"> <strong>Saldo Hutang</strong> </th>

                                                    
                                                                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<?php 
                                            	foreach($result as $result):
                                            	?>
                                                <tr class="parent-<?php print $result['piutang_id'] ?> counter">
                                                    
                                                    <td class="center hide-if-mobile hidden">
                                                        <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['piutang_id'] ?>" id="checkbox-delete-<?php print $result['piutang_id'] ?>" name="multichecked[]" />
                                                    </td>

                                                    <td class="">                                                    	
	                                                    <div class="text-center">
                                                            <a href="javascript:;"  class="tooltips" data-id="<?php print $result['piutang_id'] ?>" data-container="body" data-placement="top" data-original-title="Lihat Detail" data-target="#detail-po-<?php print $result['piutang_id'] ?>" data-toggle="modal">                                                        
                                                                <i class="icon-book-open"></i>
                                                            </a>
                                                        </div>                                                         
                                                    </td>

                                                    <td class="text-center"><?php print $result['cabang_nama'] ?></td> 
                                                    <td class="text-left"><span class=""><?php print stripslashes($result['pelanggan_nama']) ?></span></td>       
                                                    <td class="text-right"><?php print $this->tools->format_angka($result['piutang_jumlah'],2) ?></td>
                                                    <td class="text-right"><?php print $this->tools->format_angka($result['piutang_terbayar'],2) ?></td> 
                                                    <td class="text-right"><?php print $this->tools->format_angka($result['piutang_saldo'],2) ?></td>                                                    
                                                

                                                </tr>



                                                <div id="detail-po-<?php print $result['piutang_id'] ?>" class="modal container" tabindex="-1" data-backdrop="static">
                                                    
                                                    <div class="modal-header">
                                                    
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Perincian Piutang: <?php print $result['pelanggan_nama'] ?></h4>

                                                        <br clear="all">
                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="col-md-2 bold">Tanggal</div>
                                                        <div class="col-md-2 bold">No Invoice</div>
                                                        <div class="col-md-2 bold text-center">0-30</div>
                                                        <div class="col-md-2 bold text-center">30-60</div>
                                                        <div class="col-md-2 bold text-center">60-90</div>
                                                        <div class="col-md-2 bold text-center">90 - ~</div>

                                                        <div class="col-md-12 bold"><hr /></div>

                                                        
                                                        <?php 
                                                        $request    =   $this->penjualan_lib->data_piutang_kustomer_list($result['piutang_kustomer_kode']);
                                                        
                                                        foreach($request['result'] as $detail):
                                                        ?>
                                                        <div class="col-md-2"><?php print $this->tools->tanggal_indonesia($detail['piutang_tanggal_faktur']) ?></div>
                                                        <div class="col-md-2"><?php print $detail['piutang_no_invoice']  ?></div>
                                                        <div class="col-md-2 text-right"><?php print $this->tools->format_angka($detail['periode_pertama'],2)  ?></div>
                                                        <div class="col-md-2 text-right"><?php print $this->tools->format_angka($detail['periode_kedua'],2)  ?></div>
                                                        <div class="col-md-2 text-right"><?php print $this->tools->format_angka($detail['periode_ketiga'],2)  ?></div>
                                                        <div class="col-md-2 text-right"><?php print $this->tools->format_angka($detail['periode_keempat'],2)  ?></div>

                                                        <div class="col-md-12"><hr /></div>
                                                        <?php 

                                                            @$total_pertama  +=  $detail['periode_pertama'];
                                                            @$total_kedua    +=  $detail['periode_kedua'];
                                                            @$total_ketiga   +=  $detail['periode_ketiga'];
                                                            @$total_keempat  +=  $detail['periode_keempat'];

                                                        endforeach; 
                                                        ?>

                                                        <div class="col-md-2 bold">Total</div>
                                                        <div class="col-md-2 bold">&nbsp;</div>
                                                        <div class="col-md-2 text-right bold"><?php print $this->tools->format_angka($total_pertama,2)  ?></div>
                                                        <div class="col-md-2 text-right bold"><?php print $this->tools->format_angka($total_kedua,2)  ?></div>
                                                        <div class="col-md-2 text-right bold"><?php print $this->tools->format_angka($total_ketiga,2)  ?></div>
                                                        <div class="col-md-2 text-right bold"><?php print $this->tools->format_angka($total_keempat,2)  ?></div>



                                                        <br clear="all">

                                                    </div>
                                                    <div class="modal-footer">
                                                        
                                                        <a href="/cetak/po-penerimaan?no=<?php //print $result['penerimaan_no_penerimaan'] ?>" data-dismiss="modal" class="print-po-penerimaan tooltips various fancybox.iframe btn grey-mint hidden" id="print-po-penerimaan-modal-<?php //print $result['penerimaan_no_penerimaan'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-container="body" data-placement="top">
                                                            <i class="glyphicon glyphicon-print"></i> Cetak Bukti Penerimaan
                                                        </a>


                                                        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Tutup</button>
                                                        
                                                    </div>
                                                </div>                                                 

	                                            <?php endforeach; ?>

                                            </tbody>
                                        </table>

                                    </div>


                                        <div class="row">
                                        	
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-danger btn-delete-checkbox-baru hide-if-mobile hidden" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-field="penerimaan_id"><i class="icon-trash"></i> Delete Selected</button>
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
