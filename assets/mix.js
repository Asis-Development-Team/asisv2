
$(document).ready(function(){


    $('.toggle-detail').click(function(){

        var ids =   $(this).attr('id');

        $('#detail-' + ids).toggle();

    });


    toastr.options = {
      "closeButton": true,    "debug": false,     "positionClass": "toast-top-center",    "onclick": null,    "showDuration": "20000",
      "hideDuration": "3000",     "timeOut": "3000",      "extendedTimeOut": "2000",      "showEasing": "swing",      "hideEasing": "linear",
      "showMethod": "fadeIn",     "hideMethod": "fadeOut"
    };  

    $('.checkbox-status').click(function(){

        var uri = $(this).val();

        window.location =   uri;

    });

    $('.jump-sortby').change(function(){

        var uri = $(this).val();

        window.location =   uri;

    });

    $('.jump-show').change(function(){

        var uri = $(this).val();

        window.location =   uri;

    });

    $('#shopping-cart-trigger').click(function(){

        $('.cart-items').html('');

        $('#cart-content').html('<li><i class="fa fa-spinner fa-spin"></i> loading cart content</li>');

        var formInput   =   'data=NAN';
        
        $.post('/cart/cart_content',formInput, function(data){
            
            $('#cart-content').html(data);   
                    
        });


    });

    $('.hapus-cart').click(function(){

        var ids = $(this).attr('id');

        $('#cart-spin-'+ids).show();

        
        var formInput   =   'ids=' + ids;

        $.post('/cart/cart_delete',formInput, function(data){

            var json    =   $.parseJSON(data);

            if(json['status'] == '200')
            {   
                window.location =   '/cart';                           
            }
            else
            {
                toastr.error(json['message'],'Notification');
            }
            
                    
        });
        

    });

    $('.update-cart').click(function(){

        var ids = $(this).attr('id');
        var qty = $('#cart-qty-' + ids).val();
        var data = $(this).attr('data-id');

        $('#cart-spin-'+ids).show();

        var formInput   =   'ids=' + ids + '&qty=' + qty + '&data='+ data;

        $.post('/cart/cart_update',formInput, function(data){

            var json    =   $.parseJSON(data);

            if(json['status'] == '200')
            {   
                window.location =   '/cart';                           
            }
            else
            {
                toastr.error(json['message'],'Notification');
            }
            
                    
        });

    });

    $('.add-to-cart-detail').click(function(){
        
        var ids   = $(this).attr('id');
        var data  = $(this).attr('data-id');

        $('.add-to-cart-detail-spin').show();

        var formInput   =   'data=' + data + '&qty=' + $('#detail-qty').val() + '&color=' + $('#product-color').val() + '&size=' + $('#product-size').val();

        $.post('/cart/add_to_cart',formInput, function(data){
            
            var json    =   $.parseJSON(data);

            if(json['status'] == '200')
            {   
                toastr.success(json['message'], 'Notification');    
                $('.add-to-cart-detail-spin').hide();
                $('.cart-total-badge').html(json['total_cart']);                            
            }
            else
            {
                toastr.error(json['message'],'Notification');
            }
            
                    
        });

    });

	$('.simpan-cart').click(function(){

		var ids   = $(this).attr('id');
        var data  = $(this).attr('data-id');

        //hide all spinner
        $('.fa-spinner').hide();

		$('#add-to-cart-spin-' + ids).show();
        //$('#add-to-cart2-spin-' + ids).show();

        var formInput   =   'data=' + data;
        
        $.post('/cart/add_to_cart',formInput, function(data){
            
            var json    =   $.parseJSON(data);

            if(json['status'] == '200')
            {   
                toastr.success(json['message'], 'Notification');    
                $('.fa-spinner').hide();
                $('.cart-total-badge').html(json['total_cart']);                            
            }
            else
            {
                toastr.error(json['message'],'Notification');
            }
            
                    
        });


	});

    $('.starrr').on('starrr:change', function(e, value){
      $('.rating').val(value);
    });


    $('.touchspin').on('change', function () {
    	//alert(this.value);
    });

    //$('.slider').slider();



    $('.form-save').submit(function(){
        
        $(this).ajaxSubmit({
            //target: '',
            beforeSubmit: form_save_validation,
            success: form_save_success          
        });
        
        return false; 
    });
    
    
    function form_save_success(callback, statusText, xhr, $form)  
    {   
                
        var json = $.parseJSON(callback);
        
        if(json['status'] == '200')
        {
            if(json['url'] == false){

                if(json['action'] == 'self')
                {
                   
                   $('.cart-total-badge').html('');
                   $("html,body").animate({ scrollTop: 0 }, "slow");
                   toastr.success(json['message'],'Notification');

                   $('.fa-spinner').hide();

                   $('#checkout-proses').slideUp().hide();
                   $('#checkout-complete').show(); 

                }else if(json['action'] == 'stay')
                {

                   $("html,body").animate({ scrollTop: 0 }, "slow");
                   toastr.success(json['message'],'Notification');

                   $('.fa-spinner').hide();

                   $('#process').slideUp().hide();
                   $('#complete').show(); 

                }else if(json['action'] == 'cicing')
                {

                    toastr.success(json['message'],'Notification');

                    $('.fa-spinner').hide();

                    $('#form-account')[0].reset();

                }
                else{
                    $('.fa-spinner').hide();
                    $("html,body").animate({ scrollTop: 0 }, "slow");
                    toastr.success(json['message'],'Notification'); //                  
                }

            }else{
                window.location =   json['url'];
            }
            
        }
        else
        {
            $('.fa-spinner').hide();
            $("html,body").animate({ scrollTop: 0 }, "slow");
            toastr.error(json['message'],'ERROR!'); //              
        }
        
        
    }
    function form_save_validation(formData, jqForm, options) 
    { 

        var hasError    = false;
        var form        = jqForm[0];

        //$('.login-loading').show();
        //$('#btn-login').hide();

        $('.fa-spinner').show();
                
        $('.form-save .requiredField').each(function() {
            
            if($.trim($(this).val()) == '') {
                var labelText = $(this).prev('label').text();
                hasError = true;
                
                $(this).css("background-color","#f2dede");

                
            } else if($(this).hasClass('email')) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(!emailReg.test($.trim(jQuery(this).val()))) {
                    var labelText = $(this).prev('label').text();
                    hasError = true;
                    
                    $(this).css("background-color","#f2dede");
                }
            }
            else{ 

                $(this).css("background-color","#ffffff");

            }
            
        });     

        if(hasError)
        {
            
            $('.fa-spinner').hide();
            
            //$("html,body").animate({ scrollTop: 0 }, "slow");
            
            toastr.error('Please fill all required fields','ERROR!');
            //$('.alert').show();
            
            return false;
        }   
        
        //$('.save-loading').show();  
        return true;
        
    
                
    }   


    $('.jne-propinsi').change(function(){
        
        //if(page_identifier == 'checkout'){
        //    $('.note-ongkir').html('0');
        //}
        
        var propinsi_id     = $(this).val();
        var propinsi_name   = $('.jne-propinsi option:selected').text();
        
        $('.jne-kabupaten').children().remove();
        $('.jne-kabupaten').append('<option value="">loading data...</option>');
        
        $.ajax({
            
            type: "POST",  
            url: "/checkout/jne_propinsi",  
            data: "propinsiid=" + propinsi_id, 
            
            success: function(html){

                $('#jne-propinsi-name').val(propinsi_name);

                $('.jne-kabupaten').children().remove();
                $('.jne-kabupaten').append(html);
                                    
            }//eof success
            
        });//eof ajax               
        
    });
    
    $('.jne-kabupaten').change(function(){
        
        //var formInput   =   'mode=hitung-ongkir&priceid=' + ($(this).val()) + '&page=' + page_identifier + '&propinsi=' + $('.jne-propinsi').val();
        var formInput = 'priceid=' + ($(this).val()) + '&propinsi=' + $('.jne-propinsi').val();
        
        var kabupaten_name   = $('.jne-kabupaten option:selected').text();

        $.post('/checkout/jne_kabupaten',formInput, function(data){
            
            var array   =   $.parseJSON(data);

            $('#jne-kabupaten-name').val(kabupaten_name);
            
            $('.note-ongkir').html(array['ongkir']);
            $('.note-total').html(array['grand']);

            $('#checkout-ongkir').val(array['ongkir_unformat']);
            $('#checkout-grand-total').val(array['grand_unformat']);
            
        });
        
    });  

    $('#address-book').change(function(){

        var addr    =   $(this).val();


        if(addr < 1)
        {

            $('#sname,#sphone,#saddress,#scity,#address-id').val('');

            if(addr == -2)
            {
                $('#address-fields').show();
                $('#sname,#sphone,#saddress,#scity').addClass('requiredField');
                $('#address-book').removeClass('requiredField');

                $('#address-id').val('new');
            }
            else 
            {
                $('#address-fields').hide(); 
                $('#sname,#sphone,#saddress,#scity').removeClass('requiredField');
                $('#address-book').addClass('requiredField');
                
            }

        }
        else if(addr >= 1){

            $('#address-fields').show();
            $('#sname,#sphone,#saddress,#scity').addClass('requiredField');
            $('#address-book').removeClass('requiredField');

            var data   = $('#address-book option:selected').attr('data-rel');
            //var data    =  $('#address-book').attr('id');

            var json    =   $.parseJSON(data);

            $('#sname').val(json['address_name']);
            $('#sphone').val(json['address_phone']);  
            $('#scity').val(json['address_city']);  
            $('#saddress').val(json['address_address']);             

            $('#address-id').val(json['address_id']);

        }
        else
        {
            //alert('ada');
            $('#address-fields').hide();
        }

    });  



});