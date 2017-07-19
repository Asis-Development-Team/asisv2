

                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    
     
                                                                    <select name="penawaran_cabang_id" id="penawaran_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($outlet as $cabang): 
                                                                            
                                                                            /*
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? @$pelanggan['pelanggan_cabang_id'] : $this->session->sess_user_cabang_id;
 
                                                                            $selected   =   ($selected_var == $cabang['cabang_id']) ? 'selected' : '';
                                                                            
                                                                            $disabled   =   ($this->session->sess_user_cabang_id == true && $selected != 'selected' ) ? 'disabled' : '';

                                                                            print '<option value="'.$cabang['cabang_id'].'" '.$selected.' '.$disabled.'>'.$cabang['cabang_nama'].'</option>';

                                                                            unset($selected);
                                                                            unset($disabled);
                                                                            */
                                                                            if($this->session->sess_user_level_id > 2):
                                                                                $hidden =   ($cabang['cabang_id'] != $this->session->sess_user_cabang_id) ? ' class="hidden" ' : '';
                                                                            endif;
                                                                            
                                                                            $selected   =   '';

                                                                            if($result['penawaran_cabang_id'] == $cabang['cabang_id']):
                                                                                $selected = 'selected';
                                                                            endif;
                                                                            
                                                                            print '<option value="'.$cabang['cabang_id'].'" data-kode="'.$cabang['cabang_code'].'" '.@$hidden.' '.$selected.'>'.$cabang['cabang_nama'].'</option>';
                                                                            
                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>                                                                    

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Kelompok Produk <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="product_category_id" id="product_category_id" class="form-control select2 select2-hidden-accessible requiredField" tabindex="-1" aria-hidden="true">
                                                                            
                                                                            <option value="">Select Data</option>

                                                                            <?php 
                                                                            foreach($produk_kategori as $kategori):

                                                                            	$data_rel =	array(
                                                                            					'kode' => $kategori['category_kode'],
                                                                            					'dibeli' => $kategori['category_sifat_dibeli'],
                                                                            					'dijual' => $kategori['category_sifat_dijual'],
                                                                            					'disimpan' => $kategori['category_sifat_disimpan'] 
                                                                            				);

                                                                            	$data_rel = json_encode($data_rel);

                                                                            	$data_id =	array(
                                                                            					'rek_hpp' => $kategori['category_rekening_hpp'],
                                                                            					'rek_penjualan' => $kategori['category_rekening_penjualan'],
                                                                            					'rek_persediaan' => $kategori['category_rekening_persediaan'],
                                                                            				);

                                                                            	$data_id =	json_encode($data_id);

                                                                                $selected   =   '';
                                                                                
                                                                                if($kategori['category_kode'] == @$result['product_category_id']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                            	print '<option '.$selected.' value="'.$kategori['category_kode'].'" data-rel='.$data_rel.' data-id='.$data_id.'>'.$kategori['category_nama'].'</option>';

                                                                                unset($selected);

                                                                            endforeach;
                                                                            ?>

                                                                    </select>

                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Merk <span class="font-red">*</span></label>

                                                                    <select name="product_merk" id="product_merk" class="form-control select2 select2-hidden-accessible requiredField" tabindex="-1" aria-hidden="true">
                                                                    	<option value="">Select Data</option>

                                                                    	<?php 
                                                                    	foreach($merk as $merk):
                                                                    		
                                                                            $selected   =   '';
                                                                            
                                                                            if($merk['merk_kode'] == @$result['product_merk_id']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                    		print '<option '.$selected.' value="'.$merk['merk_kode'].'" data-id="'.$merk['merk_id'].'">'.$merk['merk_nama'].'</option>';

                                                                            unset($selected);

                                                                    	endforeach;
                                                                    	?>

                                                                    </select>


                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Nama Produk <span class="font-red">*</span></label>

                                                                    <input type="text" name="product_nama" id="product_nama" class="form-control requiredField" value="<?php print @$result['product_nama'] ?>">

                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">HPP <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <input type="text" name="product_hpp" id="product_hpp" class="form-control number-only" value="<?php print $this->tools->format_angka2(@$result['product_hpp'],0) ?>" maxlength="11">

                                                                    <span class="help-block"> tulis angka saja. misal: 150000 </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            

                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Barcode Produk <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <input type="text" name="product_barcode" id="product_barcode" class="form-control" value="<?php print @$result['product_barcode'] ?>" maxlength="50">

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Kode Produk <span class="font-red">*</span></label>
                                                                    
                                                                    <input type="text" name="product_kode" id="product_kode" class="form-control requiredField" value="<?php print @$result['product_kode'] ?>" maxlength="20" readonly>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->                                                            

                                                        </div>

                                                        <div class="row">
                                                           <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <label class="control-label">Produk Service <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <select name="product_jasa" id="product_jasa" class="form-control select2 select2-hidden-accessible requiredFieldx" tabindex="-1" aria-hidden="true">
                                                                            
                                                                            <option value="">Select Data</option>

                                                                            <?php 
                                                                            foreach($jasa as $jasa):

                                                                                $selected   =   '';

                                                                                if($jasa['product_kode'] == @$result['product_jasa'] && @$result['product_jasa'] != '0'):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$jasa['product_kode'].'" '.$selected.'>'.$jasa['category_nama'] .' - '.$jasa['merk_nama'].' - '.$jasa['product_nama'].'</option>';

                                                                                unset($selected);
                                                                            endforeach;
                                                                            ?>

                                                                    </select>

                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Produk Rakitan <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <select name="product_rakitan" id="product_rakitan" class="form-control" tabindex="-1" aria-hidden="true">
                                                                            
                                                                            <option value=""></option>
                                                                            <option value="1" <?php if(@$result['product_rakitan'] == '1'){ print 'selected'; } ?>>Rakitan</option>
                                                                            

                                                                    </select>

                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>




                                                        <?php /*
                                                        <div class="row hidden">
                                                            
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Akun Biaya <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="akun_biaya" id="akun_biaya" class="form-control select2x select2-hidden-accessiblex" tabindex="" aria-hidden="true">
                                                                            
                                                                            <option value=""></option>

                                                                            <?php 
                                                                            foreach($rekening as $akun_biaya):

                                                                            	print '<option value="'.$akun_biaya['rekening_kode'].'">'.$akun_biaya['rekening_nama'].'</option>';

                                                                            endforeach;
                                                                            ?>


                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Akun Penjualan <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="akun_penjualan" id="akun_penjualan" class="form-control select2x select2-hidden-accessiblex" tabindex="" aria-hidden="true">
                                                                            
                                                                            <option value=""></option>

                                                                            <?php 
                                                                            foreach($rekening as $akun_penjualan):

                                                                            	print '<option value="'.$akun_penjualan['rekening_kode'].'">'.$akun_penjualan['rekening_nama'].'</option>';

                                                                            endforeach;
                                                                            ?>


                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->


                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Akun Persediaan <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="akun_persediaan" id="akun_persediaan" class="form-control select2x select2-hidden-accessiblex " tabindex="" aria-hidden="true">
                                                                            
                                                                            <option value=""></option>

                                                                            <?php 
                                                                            foreach($rekening as $akun_persediaan):

                                                                            	print '<option value="'.$akun_persediaan['rekening_kode'].'">'.$akun_persediaan['rekening_nama'].'</option>';

                                                                            endforeach;
                                                                            ?>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            
                                                        </div>


                                                    </div>
                                                    */ ?>




                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="id" value="<?php print @$result['product_id'] ?>">
                                                        <input type="hidden" name="product_jasa_non_jasa" value="<?php print @$type ?>">

                                                        <input type="hidden" name="product_akun_simpan" id="product_akun_simpan" value="<?php print @$result['product_akun_simpan'] ?>">
                                                        <input type="hidden" name="product_akun_jual" id="product_akun_jual" value="<?php print @$result['product_akun_jual'] ?>">
                                                        <input type="hidden" name="product_akun_biaya" id="product_akun_biaya" value="<?php print @$result['product_akun_biaya'] ?>">

                                                        <input type="hidden" name="product_status_beli" id="product_status_beli" value="<?php print @$result['product_status_beli'] ?>">
                                                        <input type="hidden" name="product_status_jual" id="product_status_jual" value="<?php print @$result['product_status_jual'] ?>">
                                                        <input type="hidden" name="product_status_simpan" id="product_status_simpan" value="<?php print @$result['product_status_simpan'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>
