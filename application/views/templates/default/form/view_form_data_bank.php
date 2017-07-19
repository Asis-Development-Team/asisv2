
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Bank <span class="font-red">&nbsp;</span></label>
                                                                    <input type="text" id="bank_nama" name="bank_nama" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['bank_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nomer Rekening <span class="font-red">&nbsp;</span></label>
                                                                    <input type="text" id="bank_no_rekening" name="bank_no_rekening" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['bank_no_rekening'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">

                                                                    <label class="control-label">Akun <span class="font-red">*</span></label>

                                                                    <select name="bank_kode_rekening" id="bank_kode_rekening" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                                            <option value="">Select Data</option>
                                                                            
                                                                            <?php 
                                                                            foreach($rekening as $rekening):
                                                                                
                                                                                $selected   =   '';

                                                                                if($rekening['rekening_kode'] == @$result['bank_kode_rekening']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$rekening['rekening_kode'].'" '.$selected.'>'.$rekening['rekening_nama'].'</option>';

                                                                                unset($selected);

                                                                            endforeach;
                                                                            ?>

                                                                    </select>


                                                                </div>
                                                            </div>
                                                        </div>





                                                    </div>




                                                    <div class="form-actions rightx">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="bank_id" value="<?php print @$result['bank_id'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>
