                </div>
            </div>
            <!-- END QUICK SIDEBAR -->


        </div>
        <!-- END CONTAINER -->


<!--//modal lock idle-->

<div id="lock-idle" class="modal containerx lock-idle" tabindex="-1" data-keyboard="false" data-backdrop="static" role="basic">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title text-center"><i class="fa fa-lock"></i> <bold>Lock Screen</bold></h4>
    </div>

    
    <div class="modal-body">

            <div class="form-group">

                <div class="col-md-12 text-center" style="padding-bottom: 5px;">
                    <h4><i class="fa fa-user"></i> <?php print $this->session->sess_surname; ?></h4>
                </div>                

            </div>

            <div class="form-group">

                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fa fa-lock fa-fw"></i>
                            <input id="unlock-password" class="form-control" name="unlock-password" placeholder="password" type="password" data-lock-user="<?php print $this->session->sess_user_login ?>"> </div>
                        <span class="input-group-btn">
                            <button id="unlock-button" class="btn btn-success" type="button">
                                <i class="fa fa-unlock fa-fw"></i> Unlock
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <br clear="all">

    </div>

    <div class="modal-body text-center">



        <a href="/" class="btn default"><i class="icon-logout "></i> Logout</a>

    </div>
</div>

<!--//eof modal idle time-->

        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2016 &copy; ASIS

            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="/assets/admin/global/plugins/respond.min.js"></script>
<script src="/assets/admin/global/plugins/excanvas.min.js"></script> 
<![endif]-->

        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->



        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/admin/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/admin/layouts/layout2/scripts/layout.js" type="text/javascript"></script>
        <script src="/assets/admin/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
        <script src="/assets/admin/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->


        <script src="/assets/admin/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>

        <script src="/assets/admin/global/plugins/bootstrap-toastr/toastr.min.js"></script>
        <script src="/assets/admin/pages/scripts/ui-toastr.js"></script>

        <script src="/assets/jquery/jquery.form.js" type="text/javascript"></script>

        <script src="/assets/jquery/jquery.alphanumeric.js" type="text/javascript"></script>
        <script src="/assets/jquery/jquery.printElement.min.js" type="text/javascript"></script>
        

        <script type="text/javascript" src="/assets/bootstrap/js/bootstrap-filestyle.js"> </script>
        <script src="/assets/bootstrap/js/bootstrap-confirm-button.src.js"></script>

        <script src="/assets/admin/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>     
        
        <script type="text/javascript" src="/assets/admin/pages/scripts/components-date-time-pickers.js"></script>


        <script src="/assets/admin/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>               

        <?php /*
        <script src="/assets/admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        */ ?>

        <?php 
        /*
        <script src="/assets/jquery/dashboardx.js" type="text/javascript"></script>        
        */ 

        ?>



        <script type="text/javascript" src="/assets/bootstrap/js/bootstrap-multiselect.js"></script>

        <script src="/assets/admin/pages/scripts/components-select2.min.js" type="text/javascript"></script>

        <script src="/assets/admin/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>


        <script src="/assets/admin/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="/assets/admin/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

        <script src="/assets/admin/pages/scripts/charts-amcharts.js" type="text/javascript"></script>

        
        <script type="text/javascript" src="/assets/fancyapps/jquery.fancybox.pack.js?v=2.1.5"></script>
        <script type="text/javascript" src="/assets/js/numeral.min.js"></script>

        <script src="/assets/jquery/jquery.alphanumeric.js" type="text/javascript"></script>

        <script src="/assets/js/autoNumeric.min.js" type="text/javascript"></script>

        <script>

            $(document).ready(function(){

                $('input[rel=datepicker]').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    //startDate: new Date(),
                    setDate: new Date(),
                    todayHighlight: true,
                    orientation: "bottom auto"
                });                
                
                $('.nomer-auto,.penawaran_supplier_detail_total,#total_po_outlet').autoNumeric('init');

                $(".pilih2").select2();
                
                <?php if(!$this->agent->is_mobile()): ?>
                $('.menu-toggler').click();
                <?php endif; ?>

                $('#product-category').multiselect({
                    //includeSelectAllOption: true,
                    enableFiltering: true,
                    maxHeight: 200,
                    buttonWidth: '400px',       
                });     


                <?php if( ($page_identifier == 'po_outlet_form' || $page_identifier == 'data_produk_rakitan_form' || $page_identifier == 'po_supplier_form' || $page_identifier == 'pembelian_purchasing_form') && $form_identifier == 'add'): ?>
                
                var formInput   =   '';

                //$.post('/pembelian/generate-product-list',formInput, function(data){                    
                //    $('.penawaran_product_kode').append(data);                            
                //});                     

                <?php endif; ?>

                <?php 
                if(($page_identifier == 'po_supplier_form' && $form_identifier == 'edit') || ($page_identifier == 'po_outlet_form' && $form_identifier == 'edit')): 

                    if($page_identifier == 'po_supplier_form'):
                    
                        //$request    =   $this->pembelian_lib->po_supplier_single('nomer_po',@$_GET['po'],$this->session->sess_user_cabang_id);
                        //$no_po      =   $request['result']['po_nomer_po'];

                        //$request_detail     =   $this->pembelian_lib->po_supplier_detail_single('nomer_po', $no_po);

                        //$total_po_detail    =   $request_detail['total'];
                        ?>

                        formInput   =   'cabang-id=' + <?php print @$result['po_cabang_id'] ?>;

                        <?php
                    
                    elseif($page_identifier == 'po_outlet_form'):

                        //$request    =   $this->pembelian_lib->po_outlet_single('nomer',@$_GET['po'],$this->session->sess_user_cabang_id); 
                        //$no_po      =   $request['result']['penawaran_nomer'];


                        ?>
                    
                        formInput   =   'cabang-id=' + <?php print @$result['penawaran_cabang_id'] ?>;

                        <?php 

                    endif;
                ?>

                $.post('/pembelian/generate-product-list',formInput, function(data){    

                    $('.penawaran_product_kode').children().remove();
                    $('.penawaran_product_kode').append(data);
                    $('.penawaran_product_kode').trigger('change');                    
                
                });  

                //var formInput   =   '';

                //$.post('/pembelian/generate-product-list',formInput, function(data){                    
                //    $('.penawaran_product_kode').append(data);                            
                //});         

                //$('#penawaran_product_kode_0').val('1203085').trigger('change');                

                <?php endif; ?>


                $(".various").fancybox({
                    //maxWidth    : 800,
                    //maxHeight   : 600,
                    fitToView   : true,
                    width       : '100%',
                    height      : '70%',
                    autoSize    : true,
                    closeClick  : false,
                    openEffect  : 'none',
                    closeEffect : 'none',
                    scrolling   : 'auto',
                });


            });

            function handle_mousedown(e){
                window.my_dragging = {};
                my_dragging.pageX0 = e.pageX;
                my_dragging.pageY0 = e.pageY;
                my_dragging.elem = this;
                my_dragging.offset0 = $(this).offset();
                function handle_dragging(e){
                    var left = my_dragging.offset0.left + (e.pageX - my_dragging.pageX0);
                    var top = my_dragging.offset0.top + (e.pageY - my_dragging.pageY0);
                    $(my_dragging.elem)
                    .offset({top: top, left: left});
                }
                function handle_mouseup(e){
                    $('body')
                    .off('mousemove', handle_dragging)
                    .off('mouseup', handle_mouseup);
                }
                $('body')
                .on('mouseup', handle_mouseup)
                .on('mousemove', handle_dragging);
            }            

            $('#dragable').mousedown(handle_mousedown);

            $('#jumlah').keypress(function(e){

                if (e.keyCode == 13) 
                {
                    $('#kode').focus();
                }

            });

            <?php 
            $page_data  =   json_encode(
                                array(
                                    'page_identifier' => @$page_identifier,
                                    'form_identifier' => @$form_identifier,   
                                    'no_po' => @$no_po,
                                    'total_po_detail' => @$total_po_detail,
                                    'no_invoice' => @$_GET['order'],
                                )
                            );    
            ?>

            var page_data = '<?php print $page_data; ?>';

        </script>  

        <script src="/assets/jquery/jquery.admin.js" type="text/javascript"></script>

        <?php if($this->uri->segment('1') == 'pembelian'): ?>
        <script src="/assets/jquery/jquery.admin.pembelian.js" type="text/javascript"></script>
        <?php endif; ?>

        <?php if($this->uri->segment('1') == 'penjualan'): ?>
        <script src="/assets/jquery/jquery.admin.penjualan.js" type="text/javascript"></script>
        <?php endif; ?>     

        <?php if($this->uri->segment('1') == 'keuangan'): ?>
        <script src="/assets/jquery/jquery.admin.keuangan.js" type="text/javascript"></script>
        <?php endif; ?>        

        <script type="text/javascript" src="/assets/jquery/jquery.idle.min.js"></script>
        <script src="/assets/jquery/jquery.admin-idle.js" type="text/javascript"></script>
      
    </body>

</html>