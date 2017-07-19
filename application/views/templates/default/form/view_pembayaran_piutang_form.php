                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>

                                                                    <select <?php if(@$_GET['id']){ ?>disabled<?php } ?> name="piutang_cabang_id" id="piutang_cabang_id" class="requiredField form-control">
                                                                        
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


                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Akun Kas <span class="font-red">*</span></label>
                                                                    
                                                                    <select  name="piutang_rekening" id="piutang_rekening" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
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

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Konsumen <span class="font-red">*</span></label>
                                                                    
                                                                    <select <?php if(@$_GET['id']){ ?>disabled<?php } ?> name="piutang_konsumen" id="piutang_konsumen" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value="<?php print @$result['pelanggan_nama'] ?>"><?php print @$result['pelanggan_nama'] ?></option>

                                                                    </select>                                                                    

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal <span class="font-red">*</span></label>

                                                                    <div class="input-group input-medium date date-pickerx" data-date-format="yyyy-mm-dd" data-date-start-date="" data-orientation="bottom">                                                                                        
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>                 
                                                                        <?php 
                                                                        $tanggal    =   ($form_identifier == 'add') ? $today : @$result['tgl'];
                                                                        ?>                                                        
                                                                        <input rel="datepicker" class="form-control requiredField tanggal-bawah" readonly="" name="piutang_tanggal" id="piutang_tanggal" value="<?php print $tanggal ?>" style="" type="text">
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                
                                                                <div class="form-group">

                                                                    <label class="control-label">Invoice <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="piutang_invoice" id="piutang_invoice" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <?php if($form_identifier == 'edit'): ?>
                                                                        <option value="<?php print @$result['pembayaran_no_invoice'] ?>"><?php print @$result['pembayaran_no_invoice'] ?></option>
                                                                        <?php endif; ?>

                                                                    </select>                                                                    

                                                                </div>                                                                

                                                            </div>

                                                            <?php //if($form_identifier == 'add'): ?>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Sisa Invoice <span class="font-red">&nbsp;</span></label>        
                                                                    <input type="text" class="form-control nomer-auto" name="piutang_sisa_invoice" id="piutang_sisa_invoice" value="<?php print @$result['piutang_saldo'] ?>" readonly>

                                                                </div>
                                                            </div>
                                                            <?php //endif; ?>
                                                            

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Jumlah Pembayaran <span class="font-red">*</span></label>        
                                                                    <input type="text" class="form-control requiredField nomer-auto" name="piutang_jumlah_pembayaran" id="piutang_jumlah_pembayaran" value="<?php print @$result['pembayaran_total'] ?>">

                                                                </div>
                                                            </div>



                                                        </div>

                                                        <div class="row">
                                                            
                                                                <div class="col-md-12">
                                                                <div class="form-group">
                                                                        
                                                                    <label class="control-label">Keterangan <span class="font-red">&nbsp;</span></label>        
                                                                    <input type="text" class="form-control" name="piutang_keterangan" id="piutang_keterangan" value="<?php print @$result['pembayaran_keterangan'] ?>">

                                                                </div>
                                                            </div>                                                            
                                                        </div>




                                                    </div>


                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>


                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="pembayaran_tipe" id="pembayaran_tipe" value="piutang">       

                                                        <input type="hidden" name="piutang_id" id="piutang_id" value="<?php print @$result['piutang_id'] ?>">   
                                                        <input type="hidden" name="piutang_terbayar" id="piutang_terbayar" value="<?php print @$result['piutang_terbayar'] ?>">

                                                        <input type="hidden" name="pembayaran_kode" id="pembayaran_kode"  value="<?php print @$result['pembayaran_kode'] ?>">
                                                        
                                                        <input type="hidden" name="piutang_saldo" id="piutang_saldo" value="<?php print @$result['piutang_saldo'] ?>">
                                                        <input type="hidden" name="piutang_jumlah_pembayaran_lama" id="piutang_jumlah_pembayaran_lama" value="<?php print @$result['pembayaran_total'] ?>">

                                                        <input type="hidden" name="pembayaran_kode" id="pembayaran_kode" value="<?php print @$result['pembayaran_kode'] ?>">

                                                        <input type="hidden" name="piutang_jumlah" id="piutang_jumlah" value="<?php print @$result['piutang_jumlah'] ?>">

                                                        <input type="hidden" name="pembayaran_id" id="pembayaran_id" value="<?php print @$result['pembayaran_id'] ?>">                  
                                         
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>