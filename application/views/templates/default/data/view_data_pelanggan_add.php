                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/admin/dashboard">Home</a>
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


                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-arrow-left "></i> Kembali</button>
                                                </a>

                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-add" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-edit "></i> Tambah</button>
                                                </a>  


                                                </div>
                                            </div>


                                            <?php 
                                            $form_data  =   array(
                                                                'form_identifier' => 'add',
                                                            );

                                            $this->load->view('templates/'.$this->template->data['template_admin'].'/data/view_data_pelanggan_form', $form_data);
                                            ?>

                                            <?php /*
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/ajax/save-<?php print $page_url_main ?>" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama <span class="font-red">*</span></label>
                                                                    <input type="text" id="customer_nama" name="customer_nama" class="form-control requiredField" placeholder="" maxlength="70" value="">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">No HP <span class="font-red">*</span></label>
                                                                    <input type="text" class="form-control requiredField" name="customer_telepon" maxlength="35" >
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Email <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" name="customer_email" maxlength="100" >
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Alamat <span class="font-red"></span></label>
                                                                    <input type="text" id="customer_alamat" name="customer_alamat" class="form-control" placeholder="" maxlength="200" value="">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kecamatan</label>

                                                                    <input type="text" id="customer_kecamatan_nama" name="customer_kecamatan_nama" class="form-control" placeholder="" maxlength="70" value="">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    

                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Kota</label>

                                                                    <input type="text" id="customer_nama_kota" name="customer_nama_kota" class="form-control" placeholder="" maxlength="70" value="">
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

                                                                    <select class="form-control requiredField" name="customer_type" id="customer_type">
 
                                                                        <option value="Perseorangan" data-rel="close">Perseorangan</option>
                                                                        <option value="Instansi Pemerintah" data-rel="open">Instansi Pemerintah</option>
                                                                        <option value="Perusahaan Swasta" data-rel="open">Perusahaan Swasta</option>
                                                                        <option value="Lembaga Pendidikan" data-rel="open">Lembaga Pendidikan</option>
                                                                        <option value="Bank" data-rel="open">Bank</option>

                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Simpan Ke Outlet <span class="font-red">*</span></label>
                                                                    
     
                                                                    <select name="cabang_id" id="cabang_id" class="requiredField form-control">
                                                                        <option value=""></option>
                                                                        <?php 
                                                                        foreach($outlets as $cabang): 
                                                                            
                                                                            //$sess_user_cabang_id =  true;

                                                                            $selected   =   ($this->session->sess_user_cabang_id == $cabang['cabang_id']) ? 'selected' : '';
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


                                                    <div class="form-actions" id="detail-tipe-kustomer" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Nama <span class="nama-instansi"></span> <span class="font-red">*</span></label>
                                                                    <input type="text" id="customer_instansi_nama" name="customer_instansi_nama" class="form-control" placeholder="" maxlength="70" value="">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label">Alamat <span class="alamat-instansi"></span> <span class="font-red"></span></label>
                                                                    <input type="text" class="form-control" id="customer_instansi_alamat" name="customer_instansi_alamat" maxlength="255" >
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

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="add">
                                                        
                                                        <input type="hidden" name="customer_code" value="">
                                                        <input type="hidden" name="customer_nomer_membership" value="">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                                

                                            </div>
                                            */ ?>


                                        </div>


                                    </div>



                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->