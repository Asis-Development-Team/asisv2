                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/data/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama <span class="font-red">*</span></label>
                                                                    <input type="text" id="pelanggan_nama" name="pelanggan_nama" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$pelanggan['pelanggan_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">No HP <span class="font-red">*</span></label>
                                                                    <input type="text" class="form-control requiredField" name="pelanggan_telepon" maxlength="35" value="<?php print @$pelanggan['pelanggan_telepon'] ?>" >
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Email <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" name="pelanggan_email" maxlength="100" value="<?php print @$pelanggan['pelanggan_email'] ?>">
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Alamat <span class="font-red"></span></label>
                                                                    <input type="text" id="pelanggan_alamat" name="pelanggan_alamat" class="form-control" placeholder="" maxlength="200" value="<?php print @$pelanggan['pelanggan_alamat'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kecamatan</label>

                                                                    <input type="text" id="pelanggan_kecamatan_nama" name="pelanggan_kecamatan_nama" class="form-control" placeholder="" maxlength="70" value="<?php print @$pelanggan['pelanggan_kecamatan_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kota</label>

                                                                    <input type="text" id="pelanggan_nama_kota" name="pelanggan_nama_kota" class="form-control" placeholder="" maxlength="70" value="<?php print @$pelanggan['pelanggan_nama_kota'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tipe Kustomer <span class="font-red">*</span></label>

                                                                    <select class="form-control requiredField" name="pelanggan_type" id="pelanggan_type">
 
                                                                        <option value="Perseorangan" data-rel="close" <?php if($pelanggan['pelanggan_type'] == 'Perseorangan'){ print 'selected'; } ?>>Perseorangan</option>
                                                                        <option value="Instansi Pemerintah" data-rel="open" <?php if($pelanggan['pelanggan_type'] == 'Instansi Pemerintah'){ print 'selected'; } ?>>Instansi Pemerintah</option>
                                                                        <option value="Perusahaan Swasta" data-rel="open" <?php if($pelanggan['pelanggan_type'] == 'Perusahaan Swasta'){ print 'selected'; } ?>>Perusahaan Swasta</option>
                                                                        <option value="Lembaga Pendidikan" data-rel="open" <?php if($pelanggan['pelanggan_type'] == 'Lembaga Pendidikan'){ print 'selected'; } ?>>Lembaga Pendidikan</option>
                                                                        <option value="Bank" data-rel="open" <?php if($pelanggan['pelanggan_type'] == 'Bank'){ print 'selected'; } ?>>Bank</option>

                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Simpan Ke Outlet <span class="font-red">*</span></label>
                                                                    
     
                                                                    <select name="pelanggan_cabang_id" id="pelanggan_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <?php 
                                                                        foreach($outlets as $cabang): 
                                                                            
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? @$pelanggan['pelanggan_cabang_id'] : $this->session->sess_user_cabang_id;
 
                                                                            $selected   =   ($selected_var == $cabang['cabang_id']) ? 'selected' : '';
                                                                            
                                                                            $disabled   =   ($this->session->sess_user_cabang_id == true && $selected != 'selected' ) ? 'disabled' : '';

                                                                            print '<option value="'.$cabang['cabang_id'].'" '.$selected.' '.$disabled.'>'.$cabang['cabang_nama'].'</option>';

                                                                            unset($selected);
                                                                            unset($disabled);


                                                                        endforeach;
                                                                        ?>

                                                                    </select>                                                                    

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                        </div>




                                                    </div>


                                                    <div class="form-actions" id="detail-tipe-kustomer" <?php if(@$pelanggan['pelanggan_type'] == 'Perseorangan'){ ?>style="display: none;" <?php } ?> <?php if(@!$_GET['id']){ ?>style="display: none;" <?php } ?>>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama <span class="pelanggan_instansi_nama"></span> <span class="font-red">*</span></label>
                                                                    <input type="text" id="pelanggan_instansi_nama" name="pelanggan_instansi_nama" class="form-control" placeholder="" maxlength="70" value="<?php print @$pelanggan['pelanggan_instansi_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Alamat <span class="pelanggan_instansi_alamat"></span> <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" id="pelanggan_instansi_alamat" name="pelanggan_instansi_alamat" maxlength="255" value="<?php print @$pelanggan['pelanggan_instansi_alamat'] ?>" >
                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                        </div>

                                                    </div><!--//eof form action-->



                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        
                                                        <input type="hidden" name="pelanggan_id" value="<?php print @$pelanggan['pelanggan_id'] ?>">
                                                        <input type="hidden" name="pelanggan_code" value="<?php print @$pelanggan['pelanggan_code'] ?>">
                                                        <input type="hidden" name="pelanggan_nomer_membership" value="<?php print @$pelanggan['pelanggan_nomer_membership'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>