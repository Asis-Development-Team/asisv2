                </div>
            </div>
            <!-- END QUICK SIDEBAR -->


        </div>
        <!-- END CONTAINER -->



        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2016 &copy; Admin Panel

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
        <script src="/assets/admin/layouts/layout4/scripts/layout.js" type="text/javascript"></script>
        <script src="/assets/admin/layouts/layout4/scripts/demo.min.js" type="text/javascript"></script>
        <script src="/assets/admin/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->


        <script src="/assets/admin/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>




        <script src="/assets/admin/global/plugins/bootstrap-toastr/toastr.min.js"></script>
        <script src="/assets/admin/pages/scripts/ui-toastr.js"></script>

        <script src="/assets/jquery/jquery.form.js" type="text/javascript"></script>

        <script src="/assets/jquery/jquery.alphanumeric.js" type="text/javascript"></script>
        <script src="/assets/jquery/jquery.printElement.min.js" type="text/javascript"></script>
        <script src="/assets/jquery/jquery.admin.js" type="text/javascript"></script>

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

        <?php /*
        <script src="/assets/jquery/dashboardx.js" type="text/javascript"></script>        
        */ ?>

        <script type="text/javascript" src="/assets/bootstrap/js/bootstrap-multiselect.js"></script>

        <script src="/assets/admin/pages/scripts/components-select2.min.js" type="text/javascript"></script>

        <script src="/assets/admin/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>



        <script>
            
            $(document).ready(function(){
                
                <?php if(!$this->agent->is_mobile()): ?>
                $('.menu-toggler').click();
                <?php endif; ?>

                $('#product-category').multiselect({
                    //includeSelectAllOption: true,
                    enableFiltering: true,
                    maxHeight: 200,
                    buttonWidth: '400px',       
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



        </script>  
        
    </body>

</html>