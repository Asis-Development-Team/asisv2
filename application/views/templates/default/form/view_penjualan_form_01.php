
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="invoice_cabang_id" id="invoice_cabang_id" class="form-control select2x requiredField">
                                                                        
                                                                        <option value=""></option>
                                                                        <?php 
                                                                        foreach($outlet as $list):

                                                                            if($this->session->sess_user_level_id > 2):
                                                                                $hidden =   ($list['cabang_id'] != $this->session->sess_user_cabang_id) ? ' style="display:none;" ' : '';
                                                                            endif;                                                                            

                                                                            $selected = '';

                                                                            if($list['cabang_id'] == @$result['invoice_cabang_id']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                        ?>
                                                                        <option value="<?php print $list['cabang_id'] ?>" <?php print $selected ?> <?php print @$hidden ?>><?php print $list['cabang_nama'] ?></option>
                                                                        <?php 
                                                                            unset($selected);
                                                                        endforeach;
                                                                        ?>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <?php /*
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Penjualan Belum diposting <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <select name="invoice_no_so" id="invoice_no_so" class="form-control select2">
                                                                        
                                                                        <option value=""></option>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            */ ?>
                                                            <!--/span-->


                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal <span class="font-red">*</span></label>

                                                                    <?php 
                                                                    $tanggal    =   ($form_identifier == 'add') ? $today : @$result['invoice_tanggal_faktur'];
                                                                    ?>

                                                                    <div class="input-group input-medium date date-pickerx" data-date-format="yyyy-mm-dd" data-date-start-date="" data-orientation="bottom">
                                                                        <input rel="datepicker" class="form-control requiredField tanggal-bawah" readonly="" name="invoice_tanggal_faktur" id="invoice_tanggal_faktur" value="<?php print $tanggal ?>" type="text">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>


                                                                </div>
                                                            </div>


                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Pelanggan <span class="font-red">*</span></label>

                                                                    <select name="invoice_customer_code" id="invoice_customer_code" class="form-control select2 select2-hidden-accessible requiredField" tabindex="-1" aria-hidden="true">
                                                                        
                                                                        <option value="">Select Data</option>

                                                                        <?php 
                                                                        if($form_identifier == 'edit'):

                                                                            foreach($kustomer as $kustomer):

                                                                                $selected   =   '';

                                                                                if($result['invoice_customer_kode'] == $kustomer['pelanggan_code']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$kustomer['pelanggan_code'].'" '.$selected.'>'.$kustomer['pelanggan_nama'].'</option>';

                                                                                unset($selected);

                                                                            endforeach;

                                                                        endif;
                                                                        ?>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1 <?php if($form_identifier == 'edit'){ print 'hidden'; } ?>">
                                                                <div class="form-group">
                                                                    <label class="control-label" style="color:#fff;">reset<span class="font-red"></span></label>

                                                                    <a href="javascript:;" class="btn btn-icon-only blue tooltips" id="tambah-pelanggan" data-container="body" data-placement="top" data-original-title="Tambah Data Pelanggan" data-target="#tambah-pelanggan-form" data-toggle="modal">
                                                                        <i class="fa fa-plus" id="tambah-pelanggan"></i>
                                                                    </a>    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Cara Pembayaran <span class="font-red">*</span></label>

                                                                    <select name="invoice_status_pembayaran" id="invoice_status_pembayaran" class="form-control select2x">                                                           
                                                                        <option <?php if(@$result['invoice_status_pembayaran'] == 'Tunai'){ print 'selected'; } ?> value="Tunai" data-show=".invoice_kode_akun_lunas" data-hide=".invoice_hari_jatuh_tempo" data-text="Akun">Tunai</option>
                                                                        <option <?php if(@$result['invoice_status_pembayaran'] == 'Tempo'){ print 'selected'; } ?> value="Tempo" data-show=".invoice_hari_jatuh_tempo" data-hide=".invoice_kode_akun_lunas" data-text="Jatuh Tempo">Tempo</option>
                                                                    </select>


                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label"><span id="penjualan-text-cara-bayar">Akun</span> <span class="font-red">*</span></label>

                                                                    <?php 
                                                                    $hide_akun_bayar   = '';
                                                                    $hide_jatuh_tempo  = '';

                                                                    if($form_identifier == 'add'):
                                                                        $hide_jatuh_tempo    = 'display: none';
                                                                    else:

                                                                        if(@$result['invoice_status_pembayaran'] == 'Tunai'):

                                                                            $hide_akun_bayar    = '';
                                                                            $hide_jatuh_tempo   = 'display: none;';

                                                                        else:
                                                                            
                                                                            $hide_akun_bayar    = '';
                                                                            $hide_jatuh_tempo   = 'display: none;';

                                                                        endif;

                                                                    endif;
                                                                    ?>

                                                                    <div class="invoice_kode_akun_lunas" style="<?php print $hide_akun_bayar ?>">

                                                                        <select name="invoice_kode_akun_lunas" id="invoice_kode_akun_lunas" class="form-control select2 select2-hidden-accessible requiredField" aria-hidden="true">
                                                                            
                                                                            <?php 
                                                                            if($form_identifier == 'edit'):

                                                                                $request    =   $this->data_lib->data_rekening(false,false,false,false,'Yes',true,$result['invoice_cabang_id']);
                                                                                
                                                                                foreach($request['result'] as $rekening):
                                                                                    
                                                                                    if($rekening['rekening_cabang_id'] == $result['invoice_cabang_id']):

                                                                                        $selected   =   '';

                                                                                        if($result['invoice_kode_akun_lunas'] == $rekening['rekening_kode']):
                                                                                            $selected = 'selected';
                                                                                        endif;

                                                                                        print '<option value="'.$rekening['rekening_kode'].'" '.$selected.'>'.$rekening['rekening_nama'].'</option>';
                                                                                    
                                                                                        unset($selected);

                                                                                    endif;

                                                                                endforeach;

                                                                            endif;
                                                                            ?>

                                                                        </select>

                                                                    </div>

                                                                    <div class="invoice_hari_jatuh_tempo" style="<?php print $hide_jatuh_tempo ?>">
                                                                        
                                                                        <?php 
                                                                        $po_hari_jatuh_tempo    =   (@$_GET['id']) ? @$result['invoice_hari_jatuh_tempo'] : '14';
                                                                        ?>
                                                                        <input type="text" class="form-control number-only" name="invoice_hari_jatuh_tempo" id="invoice_hari_jatuh_tempo" value="<?php print $po_hari_jatuh_tempo ?>" maxlength="2">                                                                        

                                                                    </div>


                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Sales <span class="font-red">*</span></label>

                                                                    <select name="invoice_sales_id" id="invoice_sales_id" class="requiredField form-control <?php /* select2 select2-hidden-accessible */ ?>" tabindex="-1" aria-hidden="true">
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        if($form_identifier == 'edit'):
                                                                            
                                                                            $request    =   $this->data_lib->data_pengguna($result['invoice_cabang_id'],false,false);

                                                                            foreach($request['result'] as $sales):
                                                                                
                                                                                if($this->session->sess_user_level_id > 3):
                                                                                    $hide   =   ($sales['user_id'] != $this->session->sess_user_id) ? 'style="display:none;"' : '';
                                                                                endif;

                                                                                $selected   =   '';

                                                                                if($result['invoice_sales_id'] == $sales['user_employee_code']):
                                                                                    $selected = 'selected';
                                                                                endif;
                                                                                                                                                                
                                                                                print '<option value="'.$sales['user_employee_code'].'" '.@$hide.' '.$selected.'>'.$sales['user_fullname'].'</option>';
                                                                                
                                                                                unset($selected);

                                                                            endforeach;

                                                                        endif;
                                                                        ?>
                                                                    </select>


                                                                </div>
                                                            </div>

                                                            <?php /*
                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Simpan Ke <span class="font-red">*</span></label>

                                                                    <select name="invoice_type" id="invoice_type" class="form-control requiredField">
                                                                        <option value=""></option>
                                                                        <option value="Penjualan">Penjualan</option>
                                                                        <option value="Penjualan Non Stock">Penjualan Tidak Mengurangi Stok</option>
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            */ ?>

                                                            <div class="col-md-8">
                                                                <div class="form-group">

                                                                    <label class="control-label">Keterangan <span class="font-red">&nbsp;</span></label>
                                                                    <input type="text" name="invoice_keterangan" id="invoice_keterangan" max="255" class="form-control" value="<?php print @$result['invoice_keterangan'] ?>">

                                                                    <input type="hidden" name="invoice_type" id="invoice_type" value="Penjualan">
                                                                </div>
                                                            </div>

                                                        </div>


                                                    </div>


                                                       <h4>Produk <span class="font-red">*</span></h4>

                                                        <?php 
                                                        for($i=0;$i<=$total_detail-1;$i++):             
                                                        ?>

                                                        <div class="row master-data-komponen div-produk-outlet" id="div-produk-outlet-<?php print $i ?>" data-div-master-id="<?php print $i ?>">

                                                            <div class="col-md-5">
                                                                
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control invoice_detail_kode_produk" name="invoice_detail_kode_produk[]" id="invoice_detail_kode_produk_<?php print $i ?>" data-id="<?php print $i ?>"  style="width:100%">
                                                                        <option value=""></option>

                                                                    </select>         
                                                                    <input type="hidden" name="invoice_detail_nama_produk[]" id="invoice_detail_nama_produk_<?php print $i ?>" value="">
                                                                    <input type="hidden" name="invoice_produk_kategori_id[]" id="invoice_produk_kategori_id_<?php print $i ?>" value="">
                                                                    <input type="hidden" name="invoice_hpp[]" id="invoice_hpp_<?php print $i ?>" value="">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-center number-only invoice_detail_jumlah_produk" placeholder="Jml" name="invoice_detail_jumlah_produk[]" id="invoice_detail_jumlah_produk_<?php print $i ?>" value="" maxlength="2" data-urutan="<?php print $i ?>">
                                                                    <input type="hidden" class="" name="invoice_stock[]" id="invoice_stok_<?php print $i ?>" value=""><!--//stock real tersisa-->
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group text-centerx">
                                                                    <input autocomplete="off" type="text" class="form-control text-right nomer-auto invoice_detail_harga" placeholder="harga satuan" name="invoice_detail_harga[]" id="invoice_detail_harga_<?php print $i ?>" value="" maxlength="8" data-urutan="<?php print $i ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group text-centerx">
                                                                    <input autocomplete="off" type="text" class="form-control text-right number-onlyx nomer-auto invoice_detail_total" placeholder="sub total" name="invoice_detail_total[]" id="invoice_detail_total_<?php print $i ?>" value="" maxlength="11" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">

                                                                <div class="form-group">

                                                                    <input autocomplete="off" type="text" class="form-control invoice_detail_serial_number" name="invoice_detail_serial_number[]" id="invoice_detail_serial_number_<?php print $i ?>" value="" placeholder="serial number">   

                                                                </div>

                                                                <div class="hidden">                                                                    
                                                                    <a href="javascript:;" class="btn btn-icon-only default refresh-list-produk-outlet tooltips" id="<?php print $i ?>" data-container="body" data-placement="top" data-original-title="Reset">
                                                                        <i class="fa fa-refresh" id="refresh-<?php print $i ?>"></i>
                                                                    </a>
                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                            <input type="hidden" name="old_jumlah_pembelian[]" id="old_jumlah_pembelian_<?php print $i ?>" value=""><!--//utk restore jumlah stok-->
                                                            <input type="hidden" name="stock_id[]" id="stock_id_<?php print $i ?>" value=""> <!--//stock_id ditable data_stock_prodcut-->
                                                            <input type="hidden" name="stok_id[]" id="stok_id_<?php print $i ?>" value="" ><!--//stok_id dari table data_stock-->
                                                        </div>



                                                        <?php endfor; ?>


                                                        <div id="clone"></div>


                                            <div class="portlet-title hidden">
                                                <div class="actions text-right">
                                                    <a href="javascript:;" class="btn btn-circle btn-default btn-sm" id="btn-clone-form-penjualan">
                                                        <i class="fa fa-plus"></i> Tambah
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-circle btn-default btn-sm" id="btn-clone-form-penjualan-delete">
                                                        <i class="fa fa-close"></i> Hapus
                                                    </a>
                                                </div>
                                                
                                            </div>


                                                    </div>

<!--//modal form pelanggan-->
<div id="tambah-pelanggan-form" class="modal container tambah-pelanggan-form" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Tambah Data Pelanggan</h4>
    

    </div>

    
    <div class="modal-body">

            <div class="col-md-4">
                Nama <span class="font-red">*</span>:<br />
                <input type="text" name="pelanggan_nama" id="pelanggan_nama" class="form-control requiredFieldx pelanggan_nama requiredField" placeholder="" maxlength="100" >
            </div>

            <div class="col-md-4">
                No HP <span class="font-red">*</span>:<br />
                <input type="text" name="pelanggan_telepon" id="pelanggan_telepon" class="form-control requiredFieldx pelanggan_telepon requiredField" placeholder="" maxlength="20" value="">
            </div>

            <div class="col-md-4">
                Email:<br />
                <input type="text" name="pelanggan_email" id="pelanggan_email" class="form-control requiredFieldx pelanggan_email" placeholder="" maxlength="100" value="">
            </div>

            <br clear="all" /><br />

            <div class="col-md-4">
                Alamat:<br />
                <input type="text" name="pelanggan_alamat" id="pelanggan_alamat" class="form-control requiredFieldx pelanggan_alamat" placeholder="" maxlength="" value="">
            </div>

            <div class="col-md-4">
                Kecamatan:<br />
                <input type="text" name="pelanggan_kecamatan_nama" id="pelanggan_kecamatan_nama" class="form-control requiredFieldx pelanggan_kecamatan_nama" placeholder="" maxlength="" value="">
            </div>

            <div class="col-md-4">
                Kota:<br />
                <input type="text" name="pelanggan_nama_kota" id="pelanggan_nama_kota" class="form-control requiredFieldx pelanggan_nama_kota" placeholder="" maxlength="" value="">
            </div>
        

            <br clear="all" /><br />

            <div class="col-md-6">
                Tipe Kustomer <span class="font-red">*</span>:<br />


                <select class="form-control requiredFieldx" name="pelanggan_type" id="pelanggan_type" style="background-color: rgb(255, 255, 255);">

                    <option value="Perseorangan" data-rel="close">Perseorangan</option>
                    <option value="Instansi Pemerintah" data-rel="open">Instansi Pemerintah</option>
                    <option value="Perusahaan Swasta" data-rel="open">Perusahaan Swasta</option>
                    <option value="Lembaga Pendidikan" data-rel="open">Lembaga Pendidikan</option>
                    <option value="Bank" data-rel="open">Bank</option>
                    <option value="Reseller" data-rel="open">Reseller</option>

                </select>            

            </div>

            <div class="col-md-6">
                Simpan Ke Outlet <span class="font-red">*</span>:<br />

               <select name="pelanggan_cabang_id" id="pelanggan_cabang_id" class="form-control select2x requiredFieldx">
                    <option value=""></option>
                    <?php 
                    foreach($outlet as $outlet):

                        if($this->session->sess_user_level_id > 2):
                            $hidden =   ($outlet['cabang_id'] != $this->session->sess_user_cabang_id) ? ' class="hidden" ' : '';
                        endif;                                                                            

                        $selected = '';

                        if($outlet['cabang_id'] == @$result['invoice_cabang_id']):
                            $selected   =   'selected';
                        endif;

                    ?>
                    <option value="<?php print $outlet['cabang_id'] ?>" <?php print $selected ?> <?php print @$hidden ?>><?php print $outlet['cabang_nama'] ?></option>
                    <?php 
                        unset($selected);
                    endforeach;
                    ?>

                </select>


            </div>


            <br clear="all" />



    </div>

    <div class="modal-body hiddenx">

        <?php //print $result['po_cabang_id'] ?>

    </div>

    <div class="modal-footer">
       
        <button type="button" data-content='' class="btn blue simpan-tambah-pelanggan" data-nomer-po="" id="simpan-tambah-pelanggan">
            Simpan
        </button>
    
        <button type="button" data-dismiss="modal" class="btn btn-outline dark tutup-tambah-pelanggan-form">Tutup</button>
        
    </div>
</div>

<!--//eof modal form-->

                                                    <div class="row form-actionsx">
                                                        <hr />
                                                        <div class="form-group text-centerx">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div>Total</div>
                                                                
                                                                <?php 
                                                                $css_hide   =   ($form_identifier == 'edit' && $result['invoice_status_pembayaran'] == 'Tempo') ? '' : 'display: none';
                                                                ?>

                                                                <div id="text-tambahan-pembayaran-tempo" style="<?php print $css_hide ?>">
                                                                    <div style="padding-top: 5px" class="">Uang Muka</div>                                                                
                                                                    <div style="padding-top: 15px" class="">Saldo Terhutang</div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div id="invoice_total_setelah_pajak_text" style="padding-right: 13px">0</div>                                                                

                                                                <div id="text-tambahan-pembayaran-tempo-nominal" style="<?php print $css_hide ?>">
                                                                    <input type="text" class="form-control text-right nomer-auto" name="invoice_uang_muka" id="invoice_uang_muka" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['invoice_uang_muka']; } ?>">
                                                                    
                                                                    <div id="invoice_piutang_text" style="padding-top: 15px; padding-right: 13px" class="text-right bold">
                                                                        0
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="invoice_total_setelah_pajak" id="invoice_total_setelah_pajak" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['invoice_total_setelah_pajak']; } ?>">
                                                                <input type="hidden" name="invoice_piutang" id="invoice_piutang" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['invoice_piutang']; } ?>">                                                                
                                                                
                                                                <input type="hidden" name="invoice_biaya_lain" id="invoice_biaya_lain" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['invoice_biaya_lain']; } ?>">

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

                                                        <input type="hidden" name="invoice_tanggal_input" id="invoice_tanggal_input" value="">

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="invoice_id" value="<?php print @$result['invoice_id'] ?>">

                                                        <input type="hidden" name="invoice_no_order" id="invoice_no_order" value="<?php print @$result['invoice_no_order'] ?>">
                                                        <input type="hidden" name="jurnal_kode" id="jurnal_kode" value="<?php print @$result['jurnal_kode'] ?>">
                                                        <input type="hidden" name="jurnal_id" id="jurnal_id" value="<?php print @$result['jurnal_id'] ?>">
                                                        <input type="hidden" name="invoice_no_so" id="invoice_no_so" value="<?php print @$result['invoice_no_so'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>
