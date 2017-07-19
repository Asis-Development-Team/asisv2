                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Klasifikasi Akun <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="rekening_no_akun" id="rekening_no_akun" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">

                                                                        <option value=""></option>

                                                                        <?php
                                                                        foreach($klasifikasi as $klasifikasi):
                                                                            
                                                                            $request    =   $this->generate_lib->generate_rekening_kode($klasifikasi['setting_ka_sub_no_akun']);

                                                                            $selected   =   '';

                                                                            if($klasifikasi['setting_ka_sub_no_akun'] == $result['rekening_no_akun']):
                                                                                $selected = 'selected';
                                                                            endif;

                                                                            print '<option value="'.$klasifikasi['setting_ka_sub_no_akun'].'" data-rel='.$request['result']['rekening_kode'].' '.$selected.'>'.$klasifikasi['setting_ka_nama'].'</option>';

                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kode Rekening <span class="font-red">*</span></label>
                                                                    <input type="text" class="form-control requiredField" name="rekening_kode" id="rekening_kode" maxlength="35" value="<?php print @$result['rekening_kode'] ?>" >
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    <select name="rekening_cabang_id" id="rekening_cabang_id" class="requiredField form-control">

                                                                        <option value=""></option>
                                                                        
                                                                        <?php 
                                                                        foreach($outlets as $cabang): 
                                                                            
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? @$result['rekening_cabang_id'] : $this->session->sess_user_cabang_id;
 
                                                                            $selected   =   ($selected_var == $cabang['cabang_id']) ? 'selected' : '';
                                                                            
                                                                            $disabled   =   ($this->session->sess_user_cabang_id == true && $selected != 'selected' ) ? 'disabled' : '';

                                                                            print '<option value="'.$cabang['cabang_id'].'" '.$selected.' '.$disabled.'>'.$cabang['cabang_nama'].'</option>';

                                                                            unset($selected);
                                                                            unset($disabled);


                                                                        endforeach;
                                                                        ?>

                                                                    </select>     
                                                                
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Rekening <span class="font-red">*</span></label>
                                                                    <input type="text" id="rekening_nama" name="rekening_nama" class="form-control requiredField" placeholder="" maxlength="100" value="<?php print @$result['rekening_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-4 hidden">
                                                                <div class="form-group">

                                                                    <label class="control-label">Keterangan</label>

                                                                    <input type="text" id="rekening_keterangan" name="rekening_keterangan" class="form-control" placeholder="" maxlength="100" value="<?php print @$result['rekening_keterangan'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kas Bank</label>

                                                                    <select name="rekening_kas_bank" class="form-control">
                                                                        <option value="Yes" <?php if(@$result['rekening_kas_bank'] == 'Yes'){ print 'selected'; } ?>>Yes</option>
                                                                        <option value="No" <?php if(@$result['rekening_kas_bank'] == 'No'){ print 'selected'; } ?>>No</option>
                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Aktifasi</label>

                                                                    <select name="rekening_aktifasi" class="form-control">
                                                                        
                                                                        <option value="Aktif" <?php if(@$result['rekening_aktifasi'] == 'Aktif'){ print 'selected'; } ?>>Aktif</option>
                                                                        <option value="Tidak Aktif" <?php if(@$result['rekening_aktifasi'] == 'Tidak Aktif'){ print 'selected'; } ?>>Tidak Aktif</option>

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
                                                        
                                                        <input type="hidden" name="rekening_id" id="rekening_id" value="<?php print @$result['rekening_id'] ?>">
                                                        
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>