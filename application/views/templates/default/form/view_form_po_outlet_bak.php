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

                                                        <h4>Produk <span class="font-red">*</span></h4>

                                                        <?php 

                                                        if($form_identifier == 'edit'):

                                                            $request =  $this->pembelian_lib->po_outlet_single_detail('nomer', $result['penawaran_nomer']);
                                                            $detail  =  $request['result'];
                                                            $total   =  $request['total'];

                                                            $kode   =   '';
                                                            $jumlah =   '';
                                                            $nama   =   '';

                                                            foreach($detail as $detail):
                                                                $kode   .=  $detail['penawaran_detail_product_kode'].',';
                                                                $jumlah .=  $detail['penawaran_detail_jumlah'].',';
                                                                $nama   .=  $detail['penawaran_detail_product_nama'].',';
                                                            endforeach;

                                                            $kode    =   substr($kode,0,-1);
                                                            $jumlah  =   substr($jumlah,0,-1);
                                                            $nama    =   substr($nama,0,-1);

                                                            $jumlah  =   explode(',', $jumlah);
                                                            $nama    =   explode(',',$nama);

                                                        endif;
                                                        
                                                        
                                                        for($i=0;$i<=4;$i++): 

                                                            if($form_identifier == 'edit'):

                                                                $append =   '';

                                                                for($k=0;$k<count($jumlah);$k++):
                                                                    if($k == $i):
                                                                        $append  =  $jumlah[$k];
                                                                    endif;
                                                                endfor;

                                                                $append2 =   '';

                                                                for($l=0;$l<count($nama);$l++):
                                                                    if($l == $i):
                                                                        $append2  =  $nama[$l];
                                                                    endif;
                                                                endfor;     

                                                            endif;                                                       

                                                        ?>
                                                        <div class="row master-data-komponen">

                                                            <div class="col-md-10">
                                                                
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode[]" id="penawaran_product_kode_<?php print $i ?>" data-id="<?php print $i ?>"  style="width:100%">
                                                                        <option value=""></option>
                                                                        <?php 

                                                                        if($form_identifier == 'edit'):

                                                                        $array  =   explode(',',$kode);
                                                                        
                                                                        foreach($jasa as $jasa1):
                                                                            
                                                                            if($jasa1['product_kode'] > 0):

                                                                                $selected   =   '';

                                                                                for($j=0;$j<count($array);$j++):

                                                                                    if($j == $i && $array[$i] == $jasa1['product_kode']):
                                                                                        $selected = 'selected';
                                                                                    endif;

                                                                                endfor;

                                                                                print '<option value="'.$jasa1['product_kode'].'" '.$selected.'>'.$jasa1['product_nama']. ' [ '. $jasa1['category_nama'] . ' - '. $jasa1['merk_nama'] . ' ]</option>';
                                                                                
                                                                                unset($selected);

                                                                            endif;
                                                                        
                                                                        ?>
                                                                       
                                                                        <?php 
                                                                        endforeach;
                                                                        endif;
                                                                        ?>
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
                                                        <?php endfor; ?>

                                                        <?php /*
                                                        <div class="row">

                                                            <div class="col-md-10">
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode[]" id="penawaran_product_kode_1"  style="width:100%">
                                                                        <option value=""></option>
                                                                        <?php 
                                                                        foreach($jasa as $jasa2):
                                                                            
                                                                            if($jasa2['product_kode'] > 0):
                                                                                print '<option value="'.$jasa2['product_kode'].'">'.$jasa2['product_nama']. ' [ '. $jasa2['category_nama'] . ' - '. $jasa2['merk_nama'] . ' ]</option>';
                                                                            endif;

                                                                        endforeach;
                                                                        ?>
                                                                    </select>                
                                                                </div>
                                                            </div>


                                                            <div class="col-md-1">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-center number-only" placeholder="Jml" name="jumlah[]" value="" maxlength="2">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div>                                                                    
                                                                    <a href="javascript:;" class="btn btn-icon-only default hapus-list-produk-po-outlet">
                                                                        <i class="fa fa-refresh"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <!--/span-->
                                                        </div>
                                                        */ ?>


                                                        <div id="clone"></div>


                                                        <button type="button" id="btn-clone" class="btn green hidden"><i class="fa fa-plus"></i> Tambah Baris</button>

                                                    </div>

                                                    <div class="form-actions rightx">

                                                        
                                                        
                                                        <button type="submit" class="btn blue">
                                                            <i class="fa fa-check"></i> Simpan 
                                                            <i class="fa fa-spinner fa-spin" style="display:none"></i> 
                                                        </button>

                                                        <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" class="btn default pull-right tombol-cancel hidden" data-rel="">Batal</a>
                                                        
                                                        <input type="hidden" name="identifier" value="<?php print $form_identifier ?>">
                                                        <input type="hidden" name="po-outlet-identifier" class="<?php if($form_identifier == 'add'){ ?>requiredField<?php } ?>" id="po-outlet-identifier" value="">
                                                        <input type="hidden" name="penawaran_id" value="<?php print @$result['penawaran_id'] ?>">

                                                    </div>

                                                </form>
                                                <!-- END FORM-->


                                            </div>