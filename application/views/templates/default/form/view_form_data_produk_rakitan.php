
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                                
                                                <form action="/<?php print $this->uri->segment('1'); ?>/<?php print $page_url_main ?>-save" class="horizontal-form form-add-edit" id="" method="post" enctype="multipart/form-data">

                                                    <div class="form-body">
                                                                                

                                                        <div class="row">

                                                            <!--/span-->
                                                            <div class="col-md-8">
                                                                <div class="form-group">

                                                                    <label class="control-label">Produk yang akan dirakit <span class="font-red">*</span></label>

                                                                    <select name="rakitan_detail_kode_produk" id="rakitan_detail_kode_produk" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                                            <option value="">Select Data</option>
                                                                            <?php 
                                                                            foreach($rakitan as $rakitan):

                                                                                $selected   =   '';

                                                                                if(@$_GET['id'] == $rakitan['product_kode']):
                                                                                    $selected   =   'selected';
                                                                                endif;

                                                                                print '<option value="'.$rakitan['product_kode'].'" '.$selected.'>'.$rakitan['product_nama']. ' [ '. $rakitan['category_nama'] . ' - '. $rakitan['merk_nama'] . ' ]</option>';

                                                                                unset($selected);

                                                                            endforeach;
                                                                            ?>
                                                                    </select>

                                                                    <input type="hidden" name="rakitan_detail_kode_produk_name" value="" id="rakitan_detail_kode_produk_name">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label class="control-label">Outlet <span class="font-red">*</span></label>

                                                                    <select name="rakitan_detail_cabang_id" id="rakitan_detail_cabang_id" class="requiredField form-control">
                                                                        
                                                                        <option value=""></option>

                                                                        <?php 
                                                                        foreach($outlet as $cabang): 
                                                                            
                                                                            if($this->session->sess_user_level_id > 2):
                                                                                $hidden =   ($cabang['cabang_id'] != $this->session->sess_user_cabang_id) ? ' class="hidden" ' : '';
                                                                            endif;
                                                                            
                                                                            $selected   =   '';

                                                                            if(@$_GET['cabang'] == $cabang['cabang_id']):
                                                                                $selected = 'selected';
                                                                            endif;
                                                                            
                                                                            print '<option value="'.$cabang['cabang_id'].'" data-kode="'.$cabang['cabang_code'].'" '.@$hidden.' '.$selected.'>'.$cabang['cabang_nama'].'</option>';
                                                                            
                                                                            unset($selected);

                                                                        endforeach;
                                                                        ?>

                                                                    </select>       


                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>

                                                        <div class="caption">
                                                            
                                                            <span class="caption-subject font-blue-hoki bold uppercase">Komponen Rakitan</span> <span class="font-red">*</span>
                                                            <hr />
                                                        </div>

                                                        <?php 
                                                        /*
                                                        for($i=1;$i<=5;$i++):

                                                            if($i=='1'):
                                                                //$required   =   'requiredField';
                                                            endif;
                                                        ?>
                                                        <div class="row">

                                                            <!--master-->
                                                            <div id="master-copy">
                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    <select name="parent" id="" class="form-control select2 select2-hidden-accessible <?php print @$required ?>" tabindex="-1" aria-hidden="true">
                                                                            <option value="">Select Data</option>
                                                                            <?php 
                                                                            foreach($produk as $list):

                                                                                if($list['product_kode'] > 0):
                                                                                    print '<option value="'.$list['product_kode'].'">'.$list['product_nama']. ' [ '. $list['category_nama'] . ' - '. $list['merk_nama'] . ' ]</option>';
                                                                                endif;

                                                                            endforeach;
                                                                            ?>
                                                                    </select>
                                                                    
                                                                    <span class="help-block"> &nbsp; </span>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-2">
                                                                <div class="form-group">

                                                                    <input type="text" id="" name="jumlah[]" class="form-control requiredFieldx text-center number-only" maxlength="2" value="1">
                                                                    <span class="help-block"> &nbsp; </span>
                                                                    
                                                                </div>
                                                            </div>

                                                            <!--/span-->
                                                            </div><!--//master-->

                                                        </div>

                                                        <?php 
                                                        endfor; 
                                                        */
                                                        ?>


                                                        <?php 

                                                        if($form_identifier == 'edit'):


                                                            $kode   =   '';
                                                            $jumlah =   '';
                                                            //$nama   =   '';

                                                            foreach($result as $detail):
                                                                $kode   .=  $detail['rakitan_detail_rakitan_kode'].',';
                                                                $jumlah .=  $detail['rakitan_detail_jumlah'].',';
                                                                //$nama   .=  $detail['penawaran_detail_product_nama'].',';
                                                            endforeach;

                                                            $kode    =   substr($kode,0,-1);
                                                            $jumlah  =   substr($jumlah,0,-1);
                                                            //$nama    =   substr($nama,0,-1);

                                                            $jumlah  =   explode(',', $jumlah);
                                                            //$kode    =   explode(',',$kode);
                                                            

                                                        endif;
                                                        
                                                                     
                                                        for($i=0;$i<=4;$i++): 

                                                            if($form_identifier == 'edit'):

                                                                $append =   '';

                                                                for($k=0;$k<count($jumlah);$k++):
                                                                    if($k == $i):
                                                                        $append  =  $jumlah[$k];
                                                                    endif;
                                                                endfor;

                                                                /*
                                                                $append2 =   '';

                                                                for($l=0;$l<count($kode);$l++):
                                                                    if($l == $i):
                                                                        $append2  =  $kode[$l];
                                                                    endif;
                                                                endfor;     
                                                                */

                                                            endif;                                                       

                                                        ?>
                                                        <div class="row master-data-komponen">

                                                            <div class="col-md-10">
                                                                
                                                                <div class="form-group">

                                                                    <select class="pilih2 form-control penawaran_product_kode" name="rakitan_detail_rakitan_kode[]" id="penawaran_product_kode_<?php print $i ?>" data-id="<?php print $i ?>"  style="width:100%">
                                                                        <option value=""></option>
                                                                        <?php 

                                                                        if($form_identifier == 'edit'):

                                                                        $array  =   explode(',',$kode);
                                                                        
                                                                        foreach($produk as $jasa1):
                                                                            
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

                                                                </div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <div class="form-group text-centerx">
                                                                    <input type="text" class="form-control text-center number-only" placeholder="Jml" name="rakitan_detail_jumlah[]" id="jumlah_<?php print $i ?>" value="<?php print @$append; ?>" maxlength="2">
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
                                                        

                                                        <div class="row" id="append-data">
                                                            
                                                        </div>

                                                        <div class="row hidden">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn blue" id="tambah-baris"><i class="fa fa-plus"></i> Tambah Baris</button>
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
                                                        <?php /* <input type="hidden" name="id" value="<?php print $result['menu_id'] ?>"> */ ?>

                                                        <input type="hidden" name="po-outlet-identifier" class="<?php if($form_identifier == 'add'){ ?>requiredField<?php } ?>" id="po-outlet-identifier" value="">
                                                        <input type="hidden" name="rakitan_detail_kode_produk_hidden" id="rakitan_detail_kode_produk_hidden" value="" class="<?php if($form_identifier == 'add'){ ?>requiredField<?php } ?>">
                                                    </div>

                                                </form>
                                                <!-- END FORM-->
                                            </div>