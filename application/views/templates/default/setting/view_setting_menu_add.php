                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/dashboard">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active blue"><?php print $page_title ?></span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">


                        <div class="col-md-12">


                                    <div class="tab-pane" id="tab_1">

                                        <div class="portlet light bordered">

                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="font-blue-hoki"></i>
                                                    <span class="caption-subject font-blue-hoki bold uppercase"><?php print $page_title ?></span>
                                                    
                                                </div>
                                                <div class="tools">


                                                <a href="/admin/<?php print $page_url_main ?>" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-arrow-left "></i> Kembali</button>
                                                </a>

                                                <a href="/admin/<?php print $page_url_main ?>-add" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-edit "></i> Tambah</button>
                                                </a>  


                                                </div>
                                            </div>



                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/ajax/save-<?php print $page_url_main ?>" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama Menu <span class="font-red">*</span></label>
                                                                    <input type="text" id="nama" name="nama" class="form-control requiredField" placeholder="" maxlength="70" value="">
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

                                                                            ?>
                                                                            <option value="<?php print $parent['menu_id'] ?>"><?php print $parent['menu_name'] ?></option>
                                                                            <?php 
                                                                                
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
                                                                    <input type="text" id="link" name="link" class="form-control requiredField" placeholder="" maxlength="100" value="">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Menu Icon</label>

                                                                    <input type="text" id="icon" name="icon" class="form-control requiredFieldx" placeholder="" maxlength="70" value="">
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
                                                                        foreach($ulevel as $ulevel):

                                                                        ?>
                                                                        <option value="<?php print $ulevel['user_level_id'] ?>"><?php print $ulevel['user_level_name'] ?></option>
                                                                        <?php 
                                                                        endforeach;
                                                                        ?>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Posisi Menu <span class="font-red">*</span></label>
                                                                    
                                                                    <select class="form-control requiredField" name="posisi">
                                                                        <option value="left">Kiri</option>
                                                                        <option value="right">Kanan</option>
                                                                        <option value="top">Atas</option>
                                                                        <option value="bottom">Bawah</option>
                                                                    </select>

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Status Menu <span class="font-red">*</span></label>
                                                                    
                                                                    <select class="form-control requiredField" name="status">
                                                                        <option value="show">Aktif</option>
                                                                        <option value="hide">Tidak Aktif</option>
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

                                                        <a href="/admin/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="add">
                                                        

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>
                                        </div>


                                    </div>



                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->