                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama <span class="font-red">*</span></label>
                                                                    <input type="text" id="karyawan_nama" name="karyawan_nama" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['karyawan_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">No HP <span class="font-red">*</span></label>
                                                                    <input type="text" class="form-control requiredField" name="karyawan_hp" maxlength="35" value="<?php print @$result['karyawan_hp'] ?>" >
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Email <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" name="karyawan_email" maxlength="100" value="<?php print @$result['karyawan_email'] ?>">
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Alamat <span class="font-red"></span></label>
                                                                    <input type="text" id="karyawan_alamat" name="karyawan_alamat" class="form-control" placeholder="" maxlength="200" value="<?php print @$result['karyawan_alamat'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Jabatan</label>
                                                                    <?php /*
                                                                    <input type="text" id="karyawan_jabatan" name="karyawan_jabatan" class="form-control" placeholder="" maxlength="70" value="<?php print @$result['karyawan_jabatan'] ?>">
                                                                    */ ?>
                                                                    <select name="karyawan_jabatan" id="karyawan_jabatan" class="form-control">
                                                                        
                                                                        <option value="admin" <?php if(@$result['karyawan_jabatan'] == 'admin'){ print 'selected'; } ?>>Admin</option>
                                                                        <option value="sales" <?php if(@$result['karyawan_jabatan'] == 'sales'){ print 'selected'; } ?>>Sales</option>
                                                                        <option value="store manager" <?php if(@$result['karyawan_jabatan'] == 'store manager'){ print 'selected'; } ?>>Store Manager</option>
                                                                        <option value="admin service" <?php if(@$result['karyawan_jabatan'] == 'admin service'){ print 'selected'; } ?>>admin Service</option>
                                                                        <option value="teknisi" <?php if(@$result['karyawan_jabatan'] == 'teknisi'){ print 'selected'; } ?>>Teknisi</option>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>


                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Simpan Ke Outlet <span class="font-red">*</span></label>
                                                                    
     
                                                                    <select name="karyawan_cabang_id" id="karyawan_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <?php 
                                                                        foreach($outlets as $cabang): 
                                                                            
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? @$pelanggan['customer_cabang_id'] : $this->session->sess_user_cabang_id;
 
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






                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        
                                                        <input type="hidden" name="karyawan_id" value="<?php print @$result['karyawan_id'] ?>">
                                                        <input type="hidden" name="karyawan_kode_identitas" value="<?php print @$result['karyawan_kode_identitas'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>