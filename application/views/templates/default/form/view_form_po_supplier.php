                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="inline-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">

                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label class="control-label">Buat PO Suplier Dari PO Outlet <span class="font-red"></span></label>
                                                                    

                                                                    <?php if($form_identifier == 'add'): ?>
                                                                    <select name="po_outlet" id="po_outlet" class="form-control select2">                                                                        
                                                                        
                                                                        <option value="0"></option>

                                                                        <?php 
                                                                        foreach($po as $po): 
                                                                            
                                                                            $selected   =   '';

                                                                            print '<option value="'.$po['penawaran_id'].'" data-date="'.date('Y-m-d', strtotime($po['penawaran_tanggal_pesan'])).'" data-cabang="'.$po['penawaran_cabang_id'].'" data-po-nomer="'.$po['penawaran_nomer'].'" '.@$selected.'>'.$po['penawaran_nomer']. ' - ' . $po['cabang_nama'] . '</option>';
                                                                            
                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select> 

                                                                    <?php else: ?>

                                                                        &nbsp;<span class="bold"><?php print @$result['po_no_penawaran'] ?></span>

                                                                    <?php endif; ?>



                                                                    <input type="hidden" name="po_nomer_penawaran" id="po_nomer_penawaran" value="<?php print @$result['po_no_penawaran'] ?>">

                                                                    <input type="hidden" name="po_no_invoice" id="po_no_invoice" value="<?php print @$result['po_no_invoice'] ?>">


                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1 <?php if($form_identifier == 'edit'){ print 'hidden'; } ?>">
                                                                <div class="form-group">
                                                                    <label class="control-label" style="color:#fff;">reset<span class="font-red"></span></label>

                                                                    <a href="javascript:;" class="btn btn-icon-only red tooltips btn-refreshx" id="refresh-po-supplier" data-container="body" data-placement="top" data-original-title="Kosongkan PO Supplier">
                                                                        <i class="fa fa-refresh" id="refresh"></i>
                                                                    </a>                                                                    
                                                                </div>
                                                            </div>

                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal PO Outlet <span class="font-red"></span></label>

                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $tanggal    =   $result['tanggal_po'];
                                                                    else:
                                                                        $tanggal    =   date('Y-m-d');
                                                                    endif;
                                                                    ?>

                                                                    <?php if($form_identifier == 'add'):  ?>
                                                                    <div class="input-group input-medium date date-pickerx">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>                                                                        
                                                                        <input class="form-control" readonly="" name="po_tgl_pesan" id="po_tgl_pesan" type="text" value="<?php print @$result['tanggal_po'] ?>">

                                                                    </div>
                                                                    <?php else: ?>
                                                                        &nbsp;<span class="bold"><?php print $result['tanggal_po'] ?></span>
                                                                        <input type="hidden" name="po_tgl_pesan" id="po_tgl_pesan" value="<?php print @$result['tanggal_po'] ?>">
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-12" style="margin-top: 0; padding-top: 0"><hr /></div>


                                                        </div>

                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="po_cabang_id" class="form-control requiredField select2" id="po_cabang_id" <?php if($form_identifier == 'edit'){ print 'disabled'; } ?>>
                                                                        <option value=""></option>
                                                                        <?php 
                                                                        foreach($outlet as $outlet):

                                                                            $selected = '';

                                                                            if($outlet['cabang_id'] == @$result['po_cabang_id']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                        ?>
                                                                        <option value="<?php print $outlet['cabang_id'] ?>" <?php print $selected ?>><?php print $outlet['cabang_nama'] ?></option>
                                                                        <?php 
                                                                            unset($selected);
                                                                        endforeach;
                                                                        ?>

                                                                    </select>
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>

                                                                    <?php if($form_identifier == 'edit'): ?>
                                                                    <input type="hidden" name="po_cabang_id" id="po_cabang_id" value="<?php print @$result['po_cabang_id'] ?>">
                                                                    <?php endif; ?>


                                                                </div>                                                                
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">No PO Supplier <span class="font-red">*</span></label>
                                                                    <input readonly type="text" class="form-control" name="po_nomer_po" id="po_nomer_po" value="<?php print @$result['po_nomer_po'] ?>" maxlength="20">
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>                                                                
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal PO <span class="font-red">*</span></label>

                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $tanggal    =   $result['tanggal_po_input'];
                                                                    else:
                                                                        $tanggal    =   date('Y-m-d');
                                                                    endif;
                                                                    ?>

                                                                    <div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd" data-date-start-date="<?php if($this->session->sess_user_level_id > 2){ print '+0d'; } ?>">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>                                                                        
                                                                        <input class="form-control requiredField" readonly="" name="po_tgl_input" id="po_tgl_input" type="text" value="<?php print $tanggal ?>">

                                                                    </div>

                                                                </div>
                                                            </div>



                                                        </div>


                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Cara Pembayaran<span class="font-red">*</span></label>
                                                                    
                                                                    <select name="po_cara_bayar" class="form-control requiredField" id="po_cara_bayar">
                                                                        <option <?php if(@$result['po_cara_bayar'] == 'tempo'){ print 'selected'; } ?> value="tempo" data-show=".po_hari_jatuh_tempo" data-hide=".po_akun_bayar" data-text="Hari Jatuh Tempo">Tempo</option>
                                                                        <option <?php if(@$result['po_cara_bayar'] == 'lunas'){ print 'selected'; } ?> value="lunas" data-show=".po_akun_bayar" data-hide=".po_hari_jatuh_tempo" data-text="Akun Pembayaran">Lunas</option>
                                                                    </select>
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>                                                                
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?php 
                                                                    $txt   = '';

                                                                    if($form_identifier == 'add'):
                                                                        $txt    = 'Hari Jatuh Tempo';
                                                                    else:

                                                                        if(@$result['po_cara_bayar'] == 'lunas'):
                                                                            $txt    = 'Akun Pembayaran';
                                                                        else:                                                                            
                                                                            $txt    = 'Hari Jatuh Tempo';
                                                                        endif;

                                                                    endif;
                                                                    ?>
                                                                    <label class="control-label"><span id="po-supplier-text-cara-bayar"><?php print $txt ?></span> <span class="font-red">*</span></label>
                                                                    
                                                                    <?php 
                                                                    $hide_akun_bayar   = '';
                                                                    $hide_jatuh_tempo  = '';

                                                                    if($form_identifier == 'add'):
                                                                        $hide_akun_bayar    = 'display: none;';
                                                                    else:

                                                                        if(@$result['po_cara_bayar'] == 'lunas'):

                                                                            $hide_akun_bayar    = '';
                                                                            $hide_jatuh_tempo   = 'display: none;';

                                                                        else:
                                                                            
                                                                            $hide_akun_bayar    = 'display: none;';
                                                                            $hide_jatuh_tempo   = '';

                                                                        endif;

                                                                    endif;
                                                                    ?>

                                                                    <div class="po_hari_jatuh_tempo" style="<?php print $hide_jatuh_tempo; ?>">
                                                                        <?php 
                                                                        $po_hari_jatuh_tempo    =   (@$_GET['id']) ? @$result['po_hari_jatuh_tempo'] : '14';
                                                                        ?>
                                                                        <input type="text" class="form-control number-only" name="po_hari_jatuh_tempo" id="po_hari_jatuh_tempo" value="<?php print $po_hari_jatuh_tempo ?>" maxlength="2">
                                                                    </div>

                                                                    <div class="po_akun_bayar" style="<?php print $hide_akun_bayar ?>">
                                                                        
                                                                        <select name="po_akun_bayar" id="po_akun_bayar" class="form-control select2">
                                                                            <option value=""></option>
                                                                            <?php 
                                                                            foreach($rekening as $rekening): 

                                                                                $selected   =   '';

                                                                                if($rekening['rekening_kode'] == @$result['po_akun_bayar']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$rekening['rekening_kode'].'" '.$selected.'>'.$rekening['rekening_nama'].'</option>';

                                                                                unset($selected); 

                                                                            endforeach;
                                                                            ?>

                                                                        </select>

                                                                    </div>
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>                                                                
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Supplier <span class="font-red">*</span></label>

                                                                    <select name="po_supplier_id" id="po_supplier_id" class="form-control select2 requiredField">
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($supplier as $supplier):
                                                                        
                                                                            $selected  = '';

                                                                            if($supplier['supplier_code'] == @$result['po_supplier_id']):
                                                                                $selected   = 'selected';
                                                                            endif;

                                                                        ?>
                                                                        <option value="<?php print $supplier['supplier_code'] ?>" <?php print $selected ?>><?php print $supplier['supplier_nama'] ?></option>
                                                                        <?php 
                                                                            
                                                                            unset($select);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Keterangan <span class="font-red"></span></label>

                                                                    <input type="text" name="po_keterangan" id="po_keterangan" class="form-control" maxlength="255" value="<?php print @$result['po_keterangan'] ?>">

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                        </div>



                                                        <?php /*
                                                        <h4>Produk <span class="font-red">*</span></h4>

                                                        <?php 
                                                        for($i=0;$i<=$total_detail-1;$i++):             
                                                        ?>

                                                        <div class="row master-data-komponen div-produk-outlet" id="div-produk-outlet-<?php print $i ?>">

                                                            <div class="col-md-6">
                                                                
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode[]" id="penawaran_product_kode_<?php print $i ?>" data-id="<?php print $i ?>"  style="width:100%">
                                                                        <option value=""></option>

                                                                    </select>         
                                                                    <input type="hidden" name="penawaran_detail_product_nama[]" id="penawaran_detail_product_nama_<?php print $i ?>" value="">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-center number-only jumlah-po-supplier" placeholder="Jml" name="penawaran_jumlah[]" id="jumlah_<?php print $i ?>" value="" maxlength="2" data-urutan="<?php print $i ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-right number-onlyx nomer-auto harga-satuan-po-supplier" placeholder="harga satuan" name="penawaran_harga[]" id="harga_<?php print $i ?>" value="" maxlength="8" data-urutan="<?php print $i ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-right number-onlyx nomer-auto sub-total-po-supplier" placeholder="sub total" name="penawaran_subtotal[]" id="sub_total_<?php print $i ?>" value="" maxlength="8" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div>                                                                    
                                                                    <a href="javascript:;" class="btn btn-icon-only default refresh-list-produk-po-outlet tooltips" id="<?php print $i ?>" data-container="body" data-placement="top" data-original-title="Reset">
                                                                        <i class="fa fa-refresh" id="refresh-<?php print $i ?>"></i>
                                                                    </a>
                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                        </div>
                                                        <?php endfor; ?>

                                                        <div id="clone"></div>

                                                        <button type="button" id="btn-clone" class="btn green hidden"><i class="fa fa-plus"></i> Tambah Baris</button>
                                                        */ ?>


                                                        <div class="row">
                                                            

                                                                        <!-- BEGIN PORTLET-->
                                                                        <div class="portlet light borderedx" id="order-form">
                                                                            
                                                                            <div class="portlet-title div-add-produk">
                                                                                <div class="caption">
                                                                                    <i class="icon-bubble font-hide hide"></i>
                                                                                    <span class="caption-subject font-hide bold uppercase text-center">Produk</span>
                                                                                </div>
                                                                                <div class="actions">
                                                                                    <div class="portlet-input input-inline">
                                                                                        <div class="input-icon right bold">
                                                                                            &nbsp;
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                            <div class="portlet-title div-add-produk">

                                                                                <div class="row" style="padding: 15px;">
                                                                                    
                                                                                    <div class="row">

                                                                                        <div class="col-md-6" style="border:0px solid  #ff0000">
                                                                                            <label class="control-label">Produk <span class="font-red"></span></label>
                                                                                            <select class="pilih2 form-control penawaran_product_kode" name="po_product_kode" id="po_supplier_product_kode" data-id=""  style="width:100%">
                                                                                                <option value=""></option>
                                                                                                
                                                                                            </select>                                                                                              
                                                                                            <span style="color: #454545; font-size: 12px;" class="hidden"></span>

                                                                                        </div>

                                                                                        <div class="col-md-2">

                                                                                            <label class="control-label">Qty <span class="font-red"></span></label>
                                                                                            <input type="text" class="form-control text-center number-only" name="penawaran_jumlah" id="penawaran_jumlah" placeholder="Qty" value="" maxlength="3" tabindex="6">
                                                
                                                                                        </div>

                                                                                        <div class="col-md-2">

                                                                                            <label class="control-label">Harga <span class="font-red"></span></label>
                                                                                            <input type="text" class="form-control text-center nomer-auto" name="penawaran_harga" id="penawaran_harga" placeholder="Harga" value="" tabindex="6">
                                                
                                                                                        </div>

                                                                                        <div class="col-md-2" style="margin-top:23px">

                                                                                            <input type="hidden" name="order_number" id="order_number" value="">                                                                                            

                                                                                            <button class="btn blue" type="button" id="save_po_supplier_temp">
                                                                                                <i class="fa fa-long-arrow-down"></i>
                                                                                                <i class="fa fa-spinner fa-spin" style="display:none" id="save_po_supplier_temp_spinner"></i>
                                                                                            </button>

                                                                                        </div>

                                                                                    </div>
                                                                                   

                                                                                </div>
                                                                                

                                                                            </div>
                                                                            
                                                                            <div class="portlet-title">
                                                                                <div class="caption">
                                                                                    <i class="icon-bubble font-hide hide"></i>
                                                                                    <span class="caption-subject font-hide bold uppercase">Tabel Order</span>
                                                                                </div>
                                                                                <br clear="all" />
                                                                                <div class="captionx">
                                                                                    <div class="col-md-5 text-left bold">Produk</div>
                                                                                    <div class="col-md-1 text-center bold">Qty</div>
                                                                                    <div class="col-md-2 text-center bold">Harga</div>
                                                                                    <div class="col-md-3 text-center bold">Total</div>                                                                                    
                                                                                    <div class="col-md-1">&nbsp;</div>
                                                                                </div>

                                                                            </div>


                                                                            <div class="portlet-body" id="chats">
                                                                                

                                                                                <div id="table-order-killy" class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                                                                                    
                                                                                    <?php /*
                                                                                    <div class="captionx">
                                                                                        <div class="col-md-5 text-left bold">Produk</div>
                                                                                        <div class="col-md-1 text-center bold">
                                                                                            
                                                                                            <input type="text" class="form-control number-only text-center penawaran_supplier_detail_jumlah" value="2" maxlength="2" id="penawaran_supplier_detail_jumlah_" data-id="">

                                                                                        </div>

                                                                                        <div class="col-md-2 text-right bold">

                                                                                            <input type="text" class="form-control text-right penawaran_supplier_detail_harga" value="1.250.000" id="penawaran_supplier_detail_harga_" data-id="">

                                                                                        </div>
                                                                                        <div class="col-md-3 text-right bold"><span id="penawaran_supplier_detail_total_">5.000.000</span></div>                                                                                    
                                                                                        <div class="col-md-1">
                                                                                        <a href="javascript:;" class="delete_po_supplier_detail_temp" id="" data-id=""><i class="fa fa-trash-o"></i></a>
                                                                                        </div>
                                                                                    </div>
                                                                                    */ ?>
                                                                                    

                                                                                    <?php 
                                                                                    $total  =   0;
                                                                                    if($form_identifier == 'edit'):
                                                                                        
                                                                                        foreach($detail as $result):

                                                                                            print '
                                                                                                <div class="captionx" id="caption_'.$result['po_detail_id'].'">
                                                                                                    <div class="col-md-5 text-left bold">'.$result['po_detail_product_nama'].'</div>
                                                                                                    <div class="col-md-1 text-center bold">
                                                                                                        
                                                                                                        <input type="text" class="form-control number-only text-center penawaran_supplier_detail_jumlah hidden" value="'.$result['po_detail_jumlah_permintaan'].'" maxlength="2" id="penawaran_supplier_detail_jumlah_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'">

                                                                                                        <span>'.$result['po_detail_jumlah_permintaan'].'</span>

                                                                                                    </div>

                                                                                                    <div class="col-md-2 text-right bold">

                                                                                                        <input type="text" class="hidden form-control text-right penawaran_supplier_detail_harga nomer-auto" value="'.$result['po_detail_harga'].'" id="penawaran_supplier_detail_harga_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'" data-po="'.$result['po_detail_nomer_po'].'">

                                                                                                        <span class="nomer-auto">'.$result['po_detail_harga'].'</span>
                                                                                                    </div>
                                                                                                    <div class="col-md-3 text-right bold"><span class="penawaran_supplier_detail_total" id="penawaran_supplier_detail_total_'.$result['po_detail_id'].'">'.$result['po_detail_total'].'</span></div>                                                                                    
                                                                                                    <div class="col-md-1" style="display:nonex">
                                                                                                        <i style="display:none" class="fa fa-spinner fa-spin" id="penawaran_supplier_detail_spin_'.$result['po_detail_id'].'"></i>
                                                                                                        <a href="javascript:;" class="delete_po_supplier_detail_temp" id="delete_po_supplier_detail_temp_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'" data-po="'.$result['po_detail_nomer_po'].'"><i class="fa fa-trash-o"></i></a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <br clear="all">
                                                                                                <br clear="all">

                                                                                            ';

                                                                                            $total  +=  $result['po_detail_total'];

                                                                                        endforeach;


                                                                                        print '
                                                                                            <div class="chat-form">
                                                                                                                                                                                
                                                                                                <div class="col-md-8 text-right">
                                                                                                    Total
                                                                                                </div>
                                                                                                <div class="col-md-3 text-center bold" id="total_po_outlet">'.@$total.'</div>
                                                                                                <div class="col-md-1 text-right bold" id="total-work-order">&nbsp;</div>

                                                                                                <div class="form-group hidden">

                                                                                                    <div style="border: 0px solid #ff0000; width: 20%; float: left">
                                                                                                        <input class="form-control  text-center" value="1"  id="jumlah" type="text" placeholder="Jumlah" tabindex="1" maxlength="2" />
                                                                                                    </div>

                                                                                                    <div style="border: 0px solid #ff0000; width: 80%; float: left">
                                                                                                        <div class="input-group">
                                                                                                        <input class="form-control" id="kode" type="text" placeholder="Barcode / Produk Kode" tabindex="2" />

                                                                                                        <span class="input-group-addon">
                                                                                                            <a href="javascript:;"><i class="fa fa-level-up font-blue"></i></a>
                                                                                                        </span>                  
                                                                                                        </div>

                                                                                                    </div>

                                                                                                </div>


                                                                                            </div>
                                                                                        ';          

                                                                                    endif; 
                                                                                    ?>




                                                                                </div>

                                                                                <?php /*
                                                                                <div class="chat-form" id="div-totalx" style="display: none;">
                                                                                    
                                                                                    <div class="col-md-8 text-right">
                                                                                        Total
                                                                                    </div>
                                                                                    <div class="col-md-3 text-center bold" id="total_po_outlet"><?php print @$total; ?></div>
                                                                                    <div class="col-md-1 text-right bold" id="total-work-order">&nbsp;</div>

                                                                                    <div class="form-group hidden">

                                                                                        <div style="border: 0px solid #ff0000; width: 20%; float: left">
                                                                                            <input class="form-control  text-center" value="1"  id="jumlah" type="text" placeholder="Jumlah" tabindex="1" maxlength="2" />
                                                                                        </div>

                                                                                        <div style="border: 0px solid #ff0000; width: 80%; float: left">
                                                                                            <div class="input-group">
                                                                                            <input class="form-control" id="kode" type="text" placeholder="Barcode / Produk Kode" tabindex="2" />

                                                                                            <span class="input-group-addon">
                                                                                                <a href="javascript:;"><i class="fa fa-level-up font-blue"></i></a>
                                                                                            </span>                  
                                                                                            </div>

                                                                                        </div>

                                                                                    </div>


                                                                                </div>
                                                                                */ ?>
                                                                                <br clear="all"/>



                                                                            </div>

                                                                            

                                                                        </div>
                                                                        <!-- END PORTLET-->

                                                        </div><!--//eor for portlet-->


                                                    </div>

                                                    <div class="row form-actionsx hidden">
                                                        <hr />
                                                        <div class="form-group text-centerx">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div>Total</div>
                                                                <div style="padding-top: 5px" class="hidden">Uang Muka</div>
                                                                <div style="padding-top: 15px">Saldo Terhutang</div>
                                                            </div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div id="po_total_text" style="padding-right: 13px">0</div>
                                                                <div style="padding-top: 5px;" class="hiddenx"><input type="text" name="po_uang_muka" id="po_uang_muka" class="form-control number-only text-right" maxlength="8" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['po_uang_muka']; } ?>"></div>
                                                                <div id="po_hutang_text" style="padding-top: 15px; padding-right: 13px" class="text-right bold">
                                                                    0
                                                                </div>
                                                                
                                                                <input type="hidden" name="po_total" id="po_total" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['po_total_setelah_pajak']; } ?>">
                                                                <input type="hidden" name="po_hutang" id="po_hutang" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['po_hutang']; } ?>">
                                                                <input type="hidden" name="po_total_setelah_pajak" id="po_total_setelah_pajak" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['po_total_setelah_pajak']; } ?>">
                                                            
                                                            </div>
                                                            <div class="col-md-1"></div>
                                                        </div>
                                                        <br />
                                                        
                                                    </div>


                                                    <div class="form-actions rightx">

                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="po-outlet-identifier" class="<?php if($form_identifier == 'add'){ ?>requiredFieldx<?php } ?>" id="po-outlet-identifier" value="">
                                                        <input type="hidden" name="po_id" value="<?php print @$result['po_id'] ?>">

                                                        <input type="hidden" name="po_status" value="<?php if($form_identifier == 'add'){ print '1'; }else{ print @$result['po_status']; } ?>">



                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>