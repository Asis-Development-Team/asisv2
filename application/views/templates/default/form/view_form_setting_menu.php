
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Menu <span class="font-red">&nbsp;</span></label>
                                                                    <input type="text" id="nama" name="nama" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['menu_name'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Menu Utama <span class="font-red">*</span></label>

                                                                    <select name="parent" id="single" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                                            <option value="">Select Data</option>

                                                                            <?php
                                                                            foreach($root_menu as $parent):

                                                                                $selected   =   (@$result['parent_id'] == $parent['menu_id']) ? "selected" : "";

                                                                            ?>
                                                                            <option value="<?php print $parent['menu_id'] ?>" <?php print $selected ?>><?php print @$parent['menu_name'] ?></option>
                                                                            <?php 
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
                                                                    <label class="control-label">Menu Link <span class="font-red">*</span></label>
                                                                    <input type="text" id="link" name="link" class="form-control requiredField" placeholder="" maxlength="100" value="<?php print $result['menu_url'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Menu Icon</label>

                                                                    <input type="text" id="icon" name="icon" class="form-control requiredFieldx" placeholder="" maxlength="70" value="<?php print @$result['menu_icon'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Menu Akses Level <span class="font-red">*</span></label>

                                                                    <input type="hidden" name="menu-level[]" value="1">

                                                                    <select id="product-category" name="menu-level[]" class="requiredField" multiple="multiple" style="display: none;">
                                                                        <?php 
                                                                        $array  =   explode(',', $result['menu_access_level']);

                                                                        foreach($ulevel as $ulevel):

                                                                            $selected = '';

                                                                            for($i=0;$i<=count($array)-1;$i++):
                                                                            
                                                                                if($array[$i] == $ulevel['user_level_id']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                            endfor;

                                                                        ?>
                                                                        <option value="<?php print $ulevel['user_level_id'] ?>" <?php print $selected ?>><?php print @$ulevel['user_level_name'] ?></option>
                                                                        <?php 
                                                                            unset($selected);
                                                                        endforeach;
                                                                        ?>
                                                                    </select>

                                                                </div>
                                                            </div>


                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="control-label">Urutan Menu <span class="font-red">&nbsp;</span></label>
                                                                    
                                                                    <input type="text" class="form-control text-center" maxlength="3" name="menu_ordering" id="menu_ordering" value="<?php print @$result['menu_ordering'] ?>">

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="control-label">Posisi Menu <span class="font-red">*</span></label>
                                                                    
                                                                    <select class="form-control requiredField" name="posisi">
                                                                        <option value="left" <?php if($result['menu_position'] == 'left'){ print 'selected'; } ?>>Kiri</option>
                                                                        <option value="right" <?php if($result['menu_position'] == 'right'){ print 'selected'; } ?>>Kanan</option>
                                                                        <option value="top" <?php if($result['menu_position'] == 'top'){ print 'selected'; } ?>>Atas</option>
                                                                        <option value="bottom" <?php if($result['menu_position'] == 'bottom'){ print 'selected'; } ?>>Bawah</option>
                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="control-label">Status Menu <span class="font-red">*</span></label>
                                                                    
                                                                    <select class="form-control requiredField" name="status">
                                                                        <option value="show" <?php if($result['menu_status'] == 'show'){ print 'selected'; } ?>>Aktif</option>
                                                                        <option value="hide" <?php if($result['menu_status'] == 'hide'){ print 'selected'; } ?>>Tidak Aktif</option>
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

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="id" value="<?php print $result['menu_id'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>
