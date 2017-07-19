                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Penerima (Supplier) <span class="font-red">*</span></label>
                                                                    
                                                                    <select <?php if(@$_GET['id']){ ?>disabled<?php } ?> name="pembayaran_dari_ke" id="pembayaran_dari_ke" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        asort($supplier);

                                                                        foreach($supplier as $supplier): 

                                                                            $selected = '';

                                                                            if($supplier['supplier_code'] == $result['pembayaran_dari_ke']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                        ?>

                                                                        <option <?php print $selected ?> value="<?php print $supplier['supplier_code'] ?>" data-supplier-id="<?php print $supplier['supplier_id'] ?>"><?php print $supplier['supplier_nama'] ?></option>

                                                                        <?php 
                                                                        endforeach; 
                                                                        ?>

                                                                    </select>                                                                    

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Akun Kas <span class="font-red">*</span></label>
                                                                    
                                                                    <select <?php if(@$_GET['id']){ ?>disabled<?php } ?> name="pembayaran_rekening" id="pembayaran_rekening" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                            <?php 
                                                                            foreach($rekening as $rekening): 

                                                                                $selected   =   '';

                                                                                if($rekening['rekening_kode'] == @$result['pembayaran_rekening']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$rekening['rekening_kode'].'" '.$selected.'>'.$rekening['rekening_nama'].'</option>';

                                                                                unset($selected); 

                                                                            endforeach;
                                                                            ?>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                
                                                                </div>
                                                            </div>


                                                        </div>


                                                        <div class="row">
                                                            
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>

                                                                    <select <?php if(@$_GET['id']){ ?>disabled<?php } ?> name="pembayaran_cabang_id" id="pembayaran_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 

                                                                        foreach($outlet as $cabang): 
                                                                            
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? $result['pembayaran_cabang_id'] : $this->session->sess_user_cabang_id;
 
                                                                            $selected   =   ($selected_var == $cabang['cabang_id']) ? 'selected' : '';
                                                                            
                                                                            $disabled   =   ($this->session->sess_user_cabang_id == true && $selected != 'selected' ) ? 'disabled' : '';

                                                                            print '<option value="'.$cabang['cabang_id'].'" '.$selected.' '.$disabled.' data-cabang-id="">'.$cabang['cabang_nama'].'</option>';

                                                                            unset($selected);
                                                                            unset($disabled);


                                                                        endforeach;
                                                                        ?>

                                                                    </select>    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">No Ref <span class="font-red">*</span></label>
                                                                    
                                                                    <input type="text" name="pembayaran_kode" id="pembayaran_kode" class="form-control requiredField" readonly value="<?php print @$result['pembayaran_kode'] ?>">

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal <span class="font-red">*</span></label>

                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $tanggal    =   $result['pembayaran_tanggal_faktur_formated'];
                                                                    else:
                                                                        $tanggal    =   $today;
                                                                    endif;
                                                                    ?>   

 
                                                                    <div class="input-group input-medium date date-pickerx" data-date-format="yyyy-mm-dd" data-date-start-date="" data-orientation="bottom">                                                                                        
                                                                    <span class="input-group-btn">
                                                                        <button class="btn default" type="button">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </button>
                                                                    </span>                                                                         
                                                                        <input rel="datepicker" class="form-control requiredField tanggal-bawah" readonly="" name="pembayaran_tanggal_faktur" id="pembayaran_tanggal_faktur" value="<?php print $today ?>" style="width: 200px;" type="text">
                                                                    </div>
                                                                    

                                                                </div>
                                                            </div>

                                                            <?php /*
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Tanggal <span class="font-red">*</span></label>
                                                                    
                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $tanggal    =   $result['pembayaran_tanggal_faktur_formated'];
                                                                    else:
                                                                        $tanggal    =   date('Y-m-d');
                                                                    endif;
                                                                    ?>

                                                                    <?php if($form_identifier == 'add'):  ?>
                                                                    <div class="input-group input-medium date date-picker">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>                                                                        
                                                                        <input class="form-control" readonly="" name="pembayaran_tanggal_faktur" id="pembayaran_tanggal_faktur" type="text" value="<?php print @$tanggal ?>">

                                                                    </div>
                                                                    <?php else: ?>
                                                                        &nbsp;<br /><span class="bold"><?php print $tanggal ?></span>
                                                                        <input type="hidden" name="pembayaran_tanggal_faktur" id="pembayaran_tanggal_faktur" value="<?php print @$tanggal ?>">
                                                                    <?php endif; ?>                                                                       


                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            */ ?>


                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                
                                                                <div class="form-group">

                                                                    <label class="control-label">Invoice <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="pembayaran_invoice" id="pembayaran_invoice" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <?php 
                                                                        if(@$_GET['id']):
                                                                            print '<option value="'.$result['pembayaran_detail_no_invoice'].'">'.@$result['pembayaran_detail_no_invoice'].'</option>';
                                                                        endif;
                                                                        ?>

                                                                    </select>

                                                                    <input type="hidden" name="pembayaran_detail_no_invoice" id="pembayaran_detail_no_invoice" value="">

                                                                    <input type="hidden" name="hutang_jumlah" id="hutang_jumlah" value="">
                                                                    <input type="hidden" name="hutang_terbayar" id="hutang_terbayar" value="">
                                                                    <input type="hidden" name="hutang_saldo" id="hutang_saldo" value="">

                                                                </div>                                                                

                                                            </div>

                                                            <?php if(!@$_GET['id']): ?>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Jumlah Invoice <span class="font-red">&nbsp;</span></label>        
                                                                    <input type="text" class="form-control nomer-auto requiredField" name="pembayaran_jumlah_invoice" id="pembayaran_jumlah_invoice" value="" readonly>

                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Jumlah Pembayaran <span class="font-red">&nbsp;</span></label>        
                                                                    <input type="text" class="form-control requiredField nomer-auto" name="pembayaran_total" id="pembayaran_total" value="<?php print @$result['pembayaran_total'] ?>" <?php if($form_identifier == 'edit'){ print 'readonly'; } ?>>

                                                                </div>
                                                            </div>



                                                        </div>

                                                        <div class="row">
                                                            
                                                                <div class="col-md-12">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Keterangan <span class="font-red">*</span></label>        
                                                                    <input type="text" class="form-control requiredField" name="pembayaran_keterangan" id="pembayaran_keterangan" value="<?php print @$result['pembayaran_keterangan'] ?>" <?php if($form_identifier == 'edit'){ print 'readonly'; } ?>>

                                                                </div>
                                                            </div>                                                            
                                                        </div>




                                                    </div>


                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue <?php if($form_identifier == 'edit'){ print 'hidden'; } ?>">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <?php if($form_identifier == 'edit'): ?>
                                                        <a href="/cetak/pembayaran-hutang?no=<?php print $result['pembayaran_kode'] ?>" data-dismiss="modal" class="print-po-penerimaan tooltips various fancybox.iframe btn grey-mint" id="print-po-penerimaan-modal-<?php print $result['pembayaran_kode'] ?>" data-controller="<?php print $this->uri->segment('1') ?>" data-page="<?php print $page_identifier ?>" data-container="body" data-placement="top">
                                                            <i class="glyphicon glyphicon-print"></i> Cetak Bukti Pembayaran
                                                        </a>
                                                        <?php endif; ?>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="pembayaran_tipe" id="pembayaran_tipe" value="hutang">          

                                                        <input type="hidden" name="pembayaran_id" id="pembayaran_id" value="<?php print @$result['pembayaran_id'] ?>">                  
                                         
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>