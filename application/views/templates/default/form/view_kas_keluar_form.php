
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="kas_cabang_id" id="kas_cabang_id" class="form-control select2x requiredField">
                                                                        
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
                                                                        <input rel="datepicker" class="form-control requiredField tanggal-bawah" readonly="" name="kas_tanggal" id="kas_tanggal" value="<?php print $tanggal ?>" type="text">
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

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label"><span id="penjualan-text-cara-bayar">Akun Kas</span> <span class="font-red">*</span></label>

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

                                                                        <select name="kas_akun_kas" id="kas_akun_kas" class="form-control select2 select2-hidden-accessible requiredField" aria-hidden="true">
                                                                            
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

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Penerima <span class="font-red">*</span></label>

                                                                    <select name="kas_penerima" id="kas_penerima" class="form-control select2 select2-hidden-accessible requiredField" tabindex="-1" aria-hidden="true">
                                                                        
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



                                                            

                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group">

                                                                    <label class="control-label">Keterangan <span class="font-red">&nbsp;</span></label>
                                                                    <input type="text" name="invoice_keterangan" id="invoice_keterangan" max="255" class="form-control" value="<?php print @$result['invoice_keterangan'] ?>">

                                                                    <input type="hidden" name="invoice_type" id="invoice_type" value="Penjualan">
                                                                </div>
                                                            </div>

                                                        </div>




                                                        <div class="row">

                                                           <div class="col-md-12"><hr /></div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label"><span id="penjualan-text-cara-bayar">Nama Akun</span> <span class="font-red">&nbsp;</span></label>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Nilai <span class="font-red">&nbsp;</span></label>
                                                                </div>
                                                            </div>


                                                            <div id="master-form-1">

                                                                <div class="col-md-6">
                                                                    <div class="form-group">

                                                                        <div class="invoice_kode_akun_lunas" style="">

                                                                            <select name="kas_nama_akun[]" id="kas_nama_akun_1" class="form-control select2 select2-hidden-accessible requiredField kas_nama_akun" aria-hidden="true">
                                                                                <option value=""></option>
                                                                            </select>

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="text" name="kas_nilai[]" id="kas_nilai_1" class="form-control nomer-auto requiredField kas_nilai">
                                                                    </div>
                                                                </div>  


                                                                <div id="clone-div"></div>         

                                                            </div><!--//eof master form-->                                               

                                                        </div><!--//eof row-->


                                                    </div>





                                                        <div id="clone"></div>


                                            <div class="portlet-title hiddenx">
                                                <div class="actions text-left">
                                                    <a href="javascript:;" class="btn btn-circle btn-default btn-sm" id="btn-clone-form-kas-keluar">
                                                        <i class="fa fa-plus"></i> Tambah
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-circle btn-default btn-sm" id="btn-clone-form-kas-keluar-delete">
                                                        <i class="fa fa-close"></i> Hapus
                                                    </a>
                                                </div>
                                                
                                            </div>


                                                    </div>


                                                    <div class="row form-actionsx">
                                                        <hr />
                                                        <div class="form-group text-centerx">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div class="hidden">Total</div>
                                                                
                                                                <?php 
                                                                $css_hide   =   ($form_identifier == 'edit' && $result['invoice_status_pembayaran'] == 'Tempo') ? '' : 'display: none';
                                                                ?>

                                                                <div id="text-tambahan-pembayaran-tempo" style="<?php //print $css_hide ?>">
                                                                    <div style="padding-top: 5px" class="">Adm. Bank</div>                                                                
                                                                    <div style="padding-top: 15px" class="">Total</div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-2 text-right bold">
                                                                <div id="kas_totalx" class="hidden" style="padding-right: 13px">0</div>                                                                

                                                                <div id="text-tambahan-pembayaran-tempo-nominal" style="<?php //print $css_hide ?>">
                                                                    <input type="text" class="form-control text-right nomer-auto" name="kas_adm_bank" id="kas_adm_bank" value="<?php if($form_identifier == 'add'){ print '0'; }else{ print @$result['invoice_uang_muka']; } ?>">
                                                                    
                                                                    <div id="kas_total_keluar" style="padding-top: 15px; padding-right: 13px" class="text-right bold">
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
                                            