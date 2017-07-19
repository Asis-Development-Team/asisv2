                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Perusahaan<span class="font-red">*</span></label>
                                                                    <input type="text" id="supplier_nama" name="supplier_nama" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['supplier_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">No Telepon <span class="font-red">*</span></label>
                                                                    <input type="text" class="form-control requiredField" name="supplier_telepon" maxlength="35" value="<?php print @$result['supplier_telepon'] ?>" >
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Email <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" name="supplier_email" maxlength="100" value="<?php print @$result['supplier_email'] ?>">
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Alamat <span class="font-red"></span></label>
                                                                    <input type="text" id="supplier_alamat" name="supplier_alamat" class="form-control" placeholder="" maxlength="200" value="<?php print @$result['supplier_alamat'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kecamatan</label>

                                                                    <input type="text" id="supplier_kecamatan_nama" name="supplier_kecamatan_nama" class="form-control" placeholder="" maxlength="70" value="<?php print @$result['supplier_kecamatan_nama'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kota</label>

                                                                    <input type="text" id="supplier_nama_kota" name="supplier_nama_kota" class="form-control" placeholder="" maxlength="70" value="<?php print @$result['supplier_nama_kota'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            
                                                            <div class="col-md-12">
                                                                <h4 class="block">Contact Peson</h4>
                                                                <hr />
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Nama CP <span class="font-red">*</span></label>

                                                                    <input type="text" id="supplier_cp" name="supplier_cp" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['supplier_cp'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">No Telepon CP <span class="font-red">*</span></label>

                                                                    <input type="text" id="supplier_cp_telepon" name="supplier_cp_telepon" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['supplier_cp_telepon'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>


                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Email CP <span class="font-red"></span></label>

                                                                    <input type="text" id="supplier_cp_email" name="supplier_cp_email" class="form-control" placeholder="" maxlength="70" value="<?php print @$result['supplier_cp_email'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                        </diiv>




                                                    </div>


                                                    <div class="form-actions">
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        
                                                        <input type="hidden" name="supplier_id" value="<?php print @$result['supplier_id'] ?>">
                                                        <input type="hidden" name="supplier_code" value="<?php print @$result['supplier_code'] ?>">
                                                        
                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>