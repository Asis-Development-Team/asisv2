                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/ajax/save-<?php print $page_url_main ?>" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Kode Rekening <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="setting_lrm_kode_rekening" id="setting_lrm_kode_rekening" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($rekening as $rekening):

                                                                            $selected   =   '';

                                                                            if($rekening['rekening_kode'] == $result['setting_lrm_kode_rekening']):
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


                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Klasifikasi Laba Rugi Baru  <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="setting_lrm_sub_klasifikasi" id="setting_lrm_sub_klasifikasi" class="form-control select2 select2-hidden-accessible requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($sub_klas as $sub):

                                                                            $selected   =   '';

                                                                            if($sub['nama_field'] == $result['setting_lrm_sub_klasifikasi']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                            print '<option value="'.$sub['nama_field'].'" '.$selected.'>'.$sub['nama_field'].'</option>';

                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>              

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Sub Klasifikasi <span class="font-red">*</span></label>
                                                                    
                                                                    <select name="setting_lrm_klasifikasi" id="setting_lrm_klasifikasi" class="form-control select2x select2-hidden-accessiblex requiredField" tabindex="" aria-hidden="true">                
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($result_sub as $result_sub):

                                                                            $selected   =   '';

                                                                            if($result_sub['nama_field'] == $result['setting_lrm_klasifikasi']):
                                                                                $selected   =   'selected';
                                                                            endif;

                                                                            print '<option value="'.$result_sub['nama_field'].'" '.$selected.'>'.$result_sub['nama_field'].'</option>';

                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>              

                                                                    <span class="help-block"> &nbsp; </span>
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
                                                        
                                                        <input type="hidden" name="setting_lrm_id" value="<?php print @$result['setting_lrm_id'] ?>">
                                         
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>