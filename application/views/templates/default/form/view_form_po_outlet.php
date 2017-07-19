                                            <div class="portlet-body form">  
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>-save" class="inline-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                
                                                        <div class="row">

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>
                                                                    
     
                                                                    <select name="penawaran_cabang_id" id="penawaran_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($outlet as $cabang): 
                                                                            
                                                                            /*
                                                                            //$sess_user_cabang_id =  true;
                                                                            $selected_var   =   ($form_identifier == 'edit') ? @$pelanggan['pelanggan_cabang_id'] : $this->session->sess_user_cabang_id;
 
                                                                            $selected   =   ($selected_var == $cabang['cabang_id']) ? 'selected' : '';
                                                                            
                                                                            $disabled   =   ($this->session->sess_user_cabang_id == true && $selected != 'selected' ) ? 'disabled' : '';

                                                                            print '<option value="'.$cabang['cabang_id'].'" '.$selected.' '.$disabled.'>'.$cabang['cabang_nama'].'</option>';

                                                                            unset($selected);
                                                                            unset($disabled);
                                                                            */
                                                                            if($this->session->sess_user_level_id > 2):
                                                                                $hidden =   ($cabang['cabang_id'] != $this->session->sess_user_cabang_id) ? ' class="hidden" ' : '';
                                                                            endif;
                                                                            
                                                                            $selected   =   '';

                                                                            if($result['penawaran_cabang_id'] == $cabang['cabang_id']):
                                                                                $selected = 'selected';
                                                                            endif;
                                                                            
                                                                            print '<option value="'.$cabang['cabang_id'].'" data-kode="'.$cabang['cabang_code'].'" '.@$hidden.' '.$selected.'>'.$cabang['cabang_nama'].'</option>';
                                                                            
                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>                                                                    

                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">No PO <span class="font-red">*</span></label>
                                                                    <input type="text" id="penawaran_nomer" readonly="" name="penawaran_nomer" class="form-control requiredField" placeholder="" maxlength="70" value="<?php print @$result['penawaran_nomer'] ?>">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">

                                                                    <label class="control-label">Tanggal <span class="font-red">*</span></label>

                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $tanggal    =   $result['tanggal_po'];
                                                                    else:
                                                                        $tanggal    =   date('Y-m-d');
                                                                    endif;
                                                                    ?>

                                                                    <div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd" data-date-start-date="<?php if($this->session->sess_user_level_id > 2){ print '+0d'; } ?>">
                                                                        <input class="form-control requiredField" readonly="" name="penawaran_tanggal_pesan" id="penawaran_tanggal_pesan" type="text" value="<?php print $tanggal ?>">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 pull-right">
                                                                <div class="form-group">

                                                                    <label class="control-label">Po Bulanan <span class="font-red"></span></label>
                                                                    <select class="form-control" name="penawaran_bulanan" id="penawaran_bulanan">
                                                                        <option value="0" <?php if(@$result['penawaran_bulanan'] == '0'){ print 'selected'; } ?>>Tidak</option>
                                                                        <option value="1" <?php if(@$result['penawaran_bulanan'] == '1'){ print 'selected'; } ?>>Ya</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Keterangan <span class="font-red"></span></label>
                                                                    <?php 
                                                                    if(@$_GET['id']):
                                                                        $keterangan     =   stripslashes(@$result['penawaran_keterangan']);
                                                                    else:
                                                                        $keterangan     =   'PO Outlet';
                                                                    endif;
                                                                    ?>
                                                                    <input type="text" class="form-control" name="penawaran_keterangan" id="penawaran_keterangan" value="<?php print @$keterangan ?>" maxlength="100">
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->

                                                        </div>

                                                        
                                                        
                                                        <?php 
                                                        /*
                                                        <h4>Produk <span class="font-red">*</span></h4>
                                                        for($i=0;$i<=4;$i++):                                                    
                                                        ?>
                                                        <div class="row master-data-komponen">

                                                            <div class="col-md-10">
                                                                
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode[]" id="penawaran_product_kode_<?php print $i ?>" data-id="<?php print $i ?>"  style="width:100%">
                                                                        <option value=""></option>
                                                                        
                                                                    </select>         
                                                                    <input type="hidden" name="penawaran_detail_product_nama[]" id="penawaran_detail_product_nama_<?php print $i ?>" value="<?php print @$append2 ?>">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-center number-only" placeholder="Jml" name="penawaran_jumlah[]" id="jumlah_<?php print $i ?>" value="<?php print @$append; ?>" maxlength="2">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div>                                                                    
                                                                    <a href="javascript:;" class="btn btn-icon-only default refresh-list-produk-po-outlet" id="<?php print $i ?>">
                                                                        <i class="fa fa-refresh" id="refresh-<?php print $i ?>"></i>
                                                                    </a>
                                                                </div>
                                                            </div>


                                                            <!--/span-->
                                                        </div>
                                                        <?php 
                                                        endfor; 
                                                        */
                                                        ?>
                                                        

                                                        
                                                        <div class="row">
                                                            

                                                                        <!-- BEGIN PORTLET-->
                                                                        <div class="portlet light borderedx" id="order-form">
                                                                            
                                                                            <div class="portlet-title">
                                                                                <div class="caption">
                                                                                    <i class="icon-bubble font-hide hide"></i>
                                                                                    <span class="caption-subject font-hide bold uppercase text-center">Produk</span>
                                                                                </div>
                                                                                <div class="actions">
                                                                                    <div class="portlet-input input-inline">
                                                                                        <div class="input-icon right bold">
                                                                                            &nbsp;
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                            <div class="portlet-title">

                                                                                <div class="row" style="padding: 15px;">
                                                                                    
                                                                                    <div class="row">

                                                                                        <div class="col-md-8" style="border:0px solid  #ff0000">
                                                                                            <label class="control-label">Produk <span class="font-red"></span></label>
                                                                                            <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode" id="po_outlet_penawaran_product_kode" data-id=""  style="width:100%">
                                                                                                <option value=""></option>
                                                                                                
                                                                                            </select>                                                                                              
                                                                                            <span style="color: #454545; font-size: 12px;" class="hidden"></span>

                                                                                        </div>

                                                                                        <div class="col-md-2">

                                                                                            <label class="control-label">Qty <span class="font-red"></span></label>
                                                                                            <input type="text" class="form-control requiredFieldx text-center" name="penawaran_jumlah" id="penawaran_jumlah" placeholder="Qty" value="" maxlength="3" tabindex="6">
                                                
                                                                                        </div>

                                                                                        <div class="col-md-2" style="margin-top:23px">

                                                                                            <input type="hidden" name="order_number" id="order_number" value="">
                                                                                            

                                                                                            <button class="btn blue" type="button" id="save_po_outlet_temp">
                                                                                                <i class="fa fa-long-arrow-down"></i>
                                                                                                <i class="fa fa-spinner fa-spin" style="display:none"></i>
                                                                                            </button>

                                                                                        </div>

                                                                                    </div>
                                                                                   

                                                                                </div>
                                                                                

                                                                            </div>
                                                                            
                                                                            <div class="portlet-title">
                                                                                <div class="caption">
                                                                                    <i class="icon-bubble font-hide hide"></i>
                                                                                    <span class="caption-subject font-hide bold uppercase">Tabel Order</span>
                                                                                </div>
                                                                                <br clear="all" />
                                                                                <div class="captionx">
                                                                                    <div class="col-md-8 text-left bold">Nama Produk</div>
                                                                                    <div class="col-md-3 text-center bold">Jumlah</div>                                                                                    
                                                                                    <div class="col-md-1">&nbsp;</div>
                                                                                </div>

                                                                            </div>


                                                                            <div class="portlet-body" id="chats">
                                                                                

                                                                                <div id="table-order-killy" class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible1="1">
                                                                                        
                                                                                    <?php 
                                                                                    $total  =   0;
                                                                                    if($form_identifier == 'edit'):


                                                                                        
                                                                                        foreach($detail as $detail):

                                                                                            //$total  =   '';
                                                                                            
                                                                                            print '
                                                                                                <div class="caption" id="caption_'.$detail['penawaran_detail_id'].'">
                                                                                                
                                                                                                    <div class="col-md-8 text-left">'.$detail['penawaran_detail_product_nama'].'</div>
                                                                                                    <div class="col-md-3 text-center">'.$detail['penawaran_detail_jumlah'].'</div>      
                                                                                                    <div class="col-md-1">
                                                                                                        <a href="javascript:;" id="delete_temp_po_outlet_'.$detail['penawaran_detail_id'].'" class="delete_temp_po_outlet" data-id="'.$detail['penawaran_detail_id'].'" data-cabang="'.$detail['penawaran_detail_cabang_id'].'" data-no-order="'.$detail['penawaran_detail_nomer'].'"><i class="fa fa-trash-o"></i></a>
                                                                                                    </div>                                                                           
                                                                                                    <br clear="all">
                                                                                                    <hr>
                                                                                                </div>
                                                                                            ';

                                                                                            $total +=   $detail['penawaran_detail_jumlah'];

                                                                                        endforeach;

                                                                                    endif; 
                                                                                    ?>




                                                                                </div>

                                                                                <div class="chat-form">
                                                                                    
                                                                                    <div class="col-md-8 text-right">
                                                                                        Total
                                                                                    </div>
                                                                                    <div class="col-md-3 text-center bold" id="total_po_outlet"><?php print @$total; ?></div>
                                                                                    <div class="col-md-1 text-right bold" id="total-work-order">&nbsp;</div>

                                                                                    <div class="form-group hidden">

                                                                                        <div style="border: 0px solid #ff0000; width: 20%; float: left">
                                                                                            <input class="form-control  text-center" value="1"  id="jumlah" type="text" placeholder="Jumlah" tabindex="1" maxlength="2" />
                                                                                        </div>

                                                                                        <div style="border: 0px solid #ff0000; width: 80%; float: left">
                                                                                            <div class="input-group">
                                                                                            <input class="form-control" id="kode" type="text" placeholder="Barcode / Produk Kode" tabindex="2" />

                                                                                            <span class="input-group-addon">
                                                                                                <a href="javascript:;"><i class="fa fa-level-up font-blue"></i></a>
                                                                                            </span>                  
                                                                                            </div>

                                                                                        </div>

                                                                                    </div>


                                                                                </div>
                                                                                <br clear="all"/>



                                                                            </div>

                                                                            

                                                                        </div>
                                                                        <!-- END PORTLET-->

                                                        </div><!--//eor for portlet-->
                                                        



                                                        <div id="clone"></div>


                                                        <button type="button" id="btn-clone" class="btn green hidden"><i class="fa fa-plus"></i> Tambah Baris</button>

                                                    </div>

                                                    <div class="form-actions rightx">

                                                        
                                                        <input type="hidden" name="identifier" id="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="penawaran_id" id="penawaran_id" value="<?php print @$result['penawaran_id'] ?>">

                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>



                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>