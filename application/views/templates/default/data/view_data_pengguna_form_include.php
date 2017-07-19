                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Username <span class="font-red">*</span></label>
                                                                    <input type="text" id="user_username" name="user_username" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['user_username'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Password <?php if($form_identifier == 'add'){ ?><span class="font-red">*</span><?php } ?></label>
                                                                    <input type="password" class="form-control <?php if($form_identifier == 'add'){ ?>requiredField<?php } ?>" name="user_password" id="user_password" maxlength="50" value="" >
                                                                    <?php if($form_identifier == 'edit'){ ?><span class="help-block"> kosongkan jika tidak dirubah </span><?php } ?>
                                                                </div>
                                                            </div>


                                                        </div>


                                                        <div class="row">
                                                            
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">User Level <span class="font-red">*</span></label>

                                                                    <select class="form-control requiredField" name="user_level_id" id="user_level_id">
                                                                        
                                                                        <option value=""></option>
                                                                        <?php 
                                                                        foreach($user_level as $level):

                                                                            $selected   =   '';

                                                                            if($level['user_level_id'] == $result['user_level_id']):
                                                                                $selected   =   'selected';
                                                                            endif;
                                                                            
                                                                            print '<option value="'.$level['user_level_id'].'" '.$selected.'>'.$level['user_level_name'].'</option>';

                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Karyawan <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="user_employee_code" id="user_employee_code" class="form-control select2 select2-hidden-accessible" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($karyawan as $karyawan):

                                                                            $selected   =   '';

                                                                            if($karyawan['karyawan_kode_identitas'] == $result['user_employee_code'] && $karyawan['karyawan_cabang_id'] == $result['user_cabang_id']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                            print '<option value="'.$karyawan['karyawan_kode_identitas'].'" data-id="'.$karyawan['karyawan_nama'].'" data-rel="'.$karyawan['karyawan_cabang_id'].'" '.$selected.'>'.$karyawan['karyawan_nama'].' - ['.$karyawan['nama_cabang'].']</option>';

                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>            

                                                                    <input type="hidden" name="user_cabang_id" id="user_cabang_id" value="<?php print @$result['user_cabang_id'] ?>">                                                        
                                                                    <input type="hidden" name="user_fullname" id="user_fullname" value="<?php print @$result['user_fullname'] ?>">
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
                                                        
                                                        <input type="hidden" name="user_id" value="<?php print @$result['user_id'] ?>">
                                         
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>