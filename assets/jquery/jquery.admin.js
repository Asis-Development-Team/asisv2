$(document).ready(function(){
	
	var php				=	$.parseJSON(page_data);	
	var page_identifier	=	php['page_identifier'];
	var no_po 			=	php['no_po'];
	var form_identifier =	php['form_identifier'];
	var no_invoice 		=	php['no_invoice'];

	var total_po_detail =	php['total_po_detail'];

	var po_identifier 	=	false;

	var tigger_start 	=	false;

	var trigger_po_supplier 	=	true;
	var trigger_cabang_id 		=	true;

	var refresh_po_supplier 	=	false;

	var trigger_pembelian_po_outlet = false;
	var trigger_pembelian_cabang_id = false;


	$('.tombol_update_sn').click(function(){

		toastr.clear();

		var id = $(this).attr('id');

		var barcode = $('#barcode_' + id).val();
		var sn 		= $('#sn_' + id).val();

		if(sn == '')
		{
			toastr.error('Isi data serial number', 'ERROR');
		}
		else
		{

			$('#fa-update-sn-' + id).removeClass('fa-refresh').addClass('fa-spin fa-spinner');

			formInput 	=	"id=" + id + "&barcode=" + barcode + "&sn=" + sn;

			//request data stok produk dan service
			$.post("/data/data_serial_number_update",formInput, function(data)
			{					
				
				var json = $.parseJSON(data);

				toastr.success(json['message'], 'Notification');

				$('#fa-update-sn-' + id).removeClass('fa-spin fa-spinner').addClass('fa-refresh');				

			});		




		}

	});	

	$(".delete_temp_po_outlet").click(function(){
		
		var id 		=	$(this).attr("data-id");
		var order 	=	$(this).attr("data-no-order");
		var cabang 	=	$(this).attr("data-cabang");

		formInput 	=	"id=" + id + "&order=" + order + "&cabang=" + cabang;

		//request data stok produk dan service
		$.post("/pembelian/po-outlet-delete-temp",formInput, function(data)
		{					
			
			var json = $.parseJSON(data);
			$("#total_po_outlet").html(json["total"]);
		});		

		$("#caption_" + id).remove();				
	});	    
	


	$('#save_po_outlet_temp').click(function(){

		var produk_kode =	$('.penawaran_product_kode option:selected').val();
		var produk_nama =	$('.penawaran_product_kode option:selected').text();
		var qty 		=	$('#penawaran_jumlah').val();
		var no_order	=	$('#penawaran_nomer').val();

		var cabang 		=	$('#penawaran_cabang_id option:selected').val();

		$('.fa-spinner').hide();

		if(produk_kode == '' || qty == '')
		{
			toastr.error('Pilih Produk dan Qty','ERROR!');
		}
		else
		{
			$('.fa-spinner').show();

		    var d = new Date();
		    var n = d.getTime();

		    var html = '<div class="caption" id="caption_' + n + '">' + 
		    '<div class="col-md-8 text-left">' + produk_nama + '</div></div>' + 
		    '<div class="col-md-3 text-center">' + qty + '</div>' + 
		    '<div class="col-md-1">' + 
		    '<a href="javascript:;" id="delete_temp_po_outlet_' + n + '" class="delete_temp_po_outlet" data-id="' + n + '"><i class="fa fa-trash-o"></i></a>' + 
		    '</div>'+ 
		    '<br clear="all"><hr>'+ 
		    '</div>';

		    //$('#table-order-killy').append(html);

			formInput 	=	'kode=' + produk_kode + '&nama=' + produk_nama + '&qty=' + qty + '&cabang=' + cabang + '&order=' + no_order;

			//request data stok produk dan service
			$.post('/pembelian/po-outlet-save-temp',formInput, function(data)
			{					
				
				var json = $.parseJSON(data);

				$('#table-order-killy').html(json['html']);

				$('#total_po_outlet').html(json['total']);

			});	
		

			$('.penawaran_product_kode').val('');
			$('.penawaran_product_kode').trigger('change');
			$('#penawaran_jumlah').val('');

			$('.fa-spinner').hide();

		}
		


	});

	$('.delete_temp_po_outletx').click(function(){
		
		var id 	=	$(this).attr('data-id');

		$('#caption_' + id).remove();

		console.log('test: ' + id);

	});

	$('#icon-logout-header').click(function(){
		window.location = '/logout';
	});


	$('.btn-delete-penjualan').btsConfirmButton(
		{
			msg: "",
			className: 'fa fa-question-circle',//'btn btn-default',
			timeout: 2500,
		}, 
		
		function(e) {
		
		var id			=	$(this).attr('data-id');
		var page 		=	$(this).attr('data-page');
		var controller	=	$(this).attr('data-controller');
		var field 		=	$(this).attr('data-field');
		var no_order 	=	$(this).attr('data-no-order')


		toastr.clear();
		
		$('.parent-'+id).remove();

		var formInput	=	'id=' + id + "&page=" + page + '&field=' + field+ '&order=' + no_order;

		
		$.post('/'+ controller +'/penjualan-delete-single',formInput, function(data){
		
			var json	=	$.parseJSON(data);

			if(json['status'] == '200')
			{	
				toastr.success(json['message'], 'Notification');	
				
				$('#total-data-text').html(json['total']);
				
				if( $(".counter").length == 0 && json['total'] > 0)
				{
					location.reload();
				}									
			}
			else
			{
				toastr.error(json['message'],'Notification');
			}
					
		});
		
		
					
	});//eof delete single	


	if(page_identifier == 'penjualan_form' && form_identifier == 'edit')
	{
     	
		var cabang = $('#invoice_cabang_id option:selected').val();

		formInput 	=	'cabang=' + cabang;

		//request data stok produk dan service
		$.post('/penjualan/request_data_stock_and_service_list',formInput, function(data)
		{					
			$('.invoice_detail_kode_produk').children().remove();
			$('.invoice_detail_kode_produk').append(data);
			$('.invoice_detail_kode_produk').trigger('change');
		});	
		
        var set_produk = function(){

        	console.clear();
        
			var formInput 	=	'no_invoice=' + no_invoice + '&cabang=' + cabang +'&mode=ajax';
			
			//console.log(formInput);
			
			$.post('/penjualan/request_data_penjualan_single',formInput, function(data){
				
				//console.log('debug: ' + data);

		       	var json = $.parseJSON(data);

				$.each(json,function(i,object){
				    
				    //console.log(i +'('+object.length+')');

				    var urut 			=	'';
				    var total_result 	=	i +'('+object.length+')';
				    total_result 		=	total_result.replace('detail(','');
				    total_result 		=	parseInt(total_result.replace(')',''));

				    //console.log(total_result);

				    $.each(object, function (index, obj) {
				        
				        //console.log(index +' - '+ obj.penawaran_detail_product_kode +' - '+ obj.penawaran_detail_jumlah);

				        urut 		= index;
				        var kode 	= obj.invoice_detail_kode_produk;
				        var nama 	= obj.invoice_detail_nama_produk;
				        var jumlah 	= obj.invoice_detail_jumlah_produk;

				        var kategori = obj.invoice_detail_produk_kategori_id;
				        var hpp 	 = obj.invoice_detail_produk_hpp;
				        var stok 	 = obj.invoice_detail_produk_stok;	

				        var stok_riil =	obj.stok_riil;

				        var harga 	= obj.invoice_detail_harga
				        var harga 	= harga.replace('.00','');

				        var sub_total = obj.invoice_detail_total;
				        var sub_total =	sub_total.replace('.00','');

				        $('#invoice_detail_kode_produk_' + urut).val(kode).trigger('change');
				        $('#invoice_detail_jumlah_produk_' + urut).val(jumlah);
				        $('#invoice_detail_harga_' + urut).val(harga);
				        $('#invoice_detail_total_' + urut).val(sub_total);

				        $('#invoice_detail_nama_produk_' + urut).val(nama);
				        $('#invoice_produk_kategori_id_' + urut).val(kategori);
				        $('#invoice_hpp_' + urut).val(hpp);

				        $('#invoice_stok_' + urut).val(stok_riil);

				        $('#old_jumlah_pembelian_' + urut).val(jumlah);
				        $('#stock_id_' + urut).val(obj.stock_id);
				        $('#stok_id_' + urut).val(obj.stok_id);

				        $('#invoice_detail_serial_number_' + urut).val(obj.invoice_detail_serial_number);

				    });

				    var sisa 	=	5 - total_result;

				    if(sisa > 0)
				    {

					    for(i=1;i<=sisa;i++)
					    {					        
					        var j = urut + i;

					        $('#invoice_detail_kode_produk_' + j).val('').trigger('change');

					        $('#invoice_detail_jumlah_produk_' + j).val('');
					        $('#invoice_detail_harga_' + j).val('');
					        $('#invoice_detail_total_' + j).val('');

					        //console.log('j: ' + j + '\r\n');	

					        $('#div-produk-outlet-' + j).hide();
					    }

					}

				});

				//$('#po-outlet-identifier').val('1');
				hitung_total_form_penjualan();

			});	
			
			

        }//eof set var

        setTimeout(set_produk, 1000);
 
	}


	if(page_identifier == 'penjualan_form' && form_identifier == 'edit')
	{
		
		//var cabang = $('#invoice_cabang_id option:selected').val();

		/*
		formInput 	=	'cabang=' + cabang;
		//request data karyawan
		$.post('/penjualan/request-data-karyawan',formInput, function(data)
		{					
			$('#invoice_sales_id').children().remove();
			$('#invoice_sales_id').append(data);
			$('#invoice_sales_id').trigger('change');
		});	
		*/

		//console.log('cabang: ' + cabang);

	}

	toastr.options = {
	  "closeButton": true,	  "debug": false,	  "positionClass": "toast-top-center",	  "onclick": null,	  "showDuration": "20000",
	  "hideDuration": "3000",	  "timeOut": "3000",	  "extendedTimeOut": "2000",	  "showEasing": "swing",	  "hideEasing": "linear",
	  "showMethod": "fadeIn",	  "hideMethod": "fadeOut",
	};	

	$('#unlock-password').keypress(function(e){
		if(e.keyCode == 13)
		{
			$('#unlock-button').click();
		}
	});

	$('#unlock-button').click(function(){

		var user = $('#unlock-password').attr('data-lock-user');
		var pass = $('#unlock-password').val();

        var formInput   =   'username=' + user + "&password=" + pass;

        $.post('/ajax/login',formInput, function(data){
            
            var json    =   $.parseJSON(data);
            
            if(json['status'] == '204')
            {
               toastr.error('Invalid Login','ERROR');
            }
            else
            {
                $('#unlock-password').val('');
                $('#lock-idle').modal('hide');
            }
        });

	});

	/*penjualan*/
	$('#invoice_no_so').change(function(){

		var value 	=	$('#invoice_no_so option:selected').val();
		var cabang 	=	$('#invoice_cabang_id option:selected').val();

		if(value != '0')
		{
			
			formInput =	"cabang=" + cabang + "&invoice_no_so=" + value;

			$.post('/penjualan/request-penjualan-tunda',formInput, function(data)
			{					
				
				var json = $.parseJSON(data);


			});				

		}

	});

	$('#invoice_uang_muka').keyup(function(){
		hitung_total_form_penjualan();
	});

	$('.tutup-tambah-pelanggan-form').click(function(){
		$('#tambah-pelanggan-form').find('input:text').val('');
		$('#simpan-tambah-pelanggan').html('Simpan');
	});

	$('#tambah-pelanggan-form').on('shown.bs.modal', function () {
  		//console.log('close');
  		//$('#tambah-pelanggan-form').find('input:text').val('');
  		$('#pelanggan_nama').focus();
	})

	$('#simpan-tambah-pelanggan').click(function(){
		
		var pelanggan_nama 		=	$('#pelanggan_nama').val();
		var pelanggan_telepon 	=	$('#pelanggan_telepon').val();
		var pelanggan_email 	=	$('#pelanggan_email').val();
		var pelanggan_alamat 	=	$('#pelanggan_alamat').val();
		var pelanggan_kecamatan_nama 	=	$('#pelanggan_kecamatan_nama').val();
		var pelanggan_nama_kota 		=	$('#pelanggan_nama_kota').val();

		var pelanggan_type 			=	$('#pelanggan_type option:selected').val();
		var pelanggan_cabang_id 	=	$('#pelanggan_cabang_id option:selected').val();

		if(pelanggan_nama == '' || pelanggan_telepon == '' || pelanggan_type == '' || pelanggan_cabang_id == '')
		{
			toastr.error('isi semua kolom dengan tanda (*)','Notification');
		}
		else
		{

			$('#simpan-tambah-pelanggan').html('<span><i class="fa fa-spin fa-spinner"></i></span>');

			var formInput	=	'pelanggan_nama=' + pelanggan_nama + '&pelanggan_telepon=' + pelanggan_telepon + '&pelanggan_email=' + pelanggan_email + '&pelanggan_alamat=' + pelanggan_alamat + '&pelanggan_kecamatan_nama=' + pelanggan_kecamatan_nama + '&pelanggan_nama_kota=' + pelanggan_nama_kota + '&pelanggan_type=' + pelanggan_type + '&pelanggan_cabang_id=' + pelanggan_cabang_id;
			
			$.post('/penjualan/penjualan_save_customer',formInput, function(data){

				var json =	$.parseJSON(data);

				if(json['status'] == '200')
				{
					
					toastr.success(json['message'],'Notification');

					//console.log(data);
					$('#tambah-pelanggan-form').find('input:text').val('');
					$('#simpan-tambah-pelanggan').html('Simpan');		
					
					//refresh data pelanggan dropdown		
					formInput =	"cabang=" + json['cabang_id'];

					$.post('/penjualan/request-data-customer',formInput, function(data)
					{					
						$('#invoice_customer_code').children().remove();
						$('#invoice_customer_code').append(data);
						$('#invoice_customer_code').trigger('change');
					});							

					//$('#tambah-pelanggan-form').modal('hide');

				}else
				{
					toastr.error(json['message'],'Notification');
					$('#simpan-tambah-pelanggan').html('Simpan');	
				}

			});			

		}
	})

	$('.invoice_detail_jumlah_produk').keyup(function(){

		var urutan 	=	$(this).attr('data-urutan');

		var jumlah 	=	$('#invoice_detail_jumlah_produk_' + urutan).val();
		var stok 	=	$('#invoice_stok_' + urutan).val();

		if(parseInt(jumlah) > parseInt(stok))
		{
			
			toastr.error('Jumlah melebihi stok yang tersedia','ERROR!');
			$('#invoice_detail_jumlah_produk_' + urutan).val(stok);

			return false;

		}

		hitung_sub_total_form_penjualan(urutan);

	});

	$('.invoice_detail_harga').keyup(function(){

		var urutan 	=	$(this).attr('data-urutan');

		hitung_sub_total_form_penjualan(urutan);

	});



	$('#btn-clone-penjualan').click(function(){

		var id 		= $('.master-data-komponen').last().attr('data-div-master-id');
		var baru  	=	id + 1;

		$('#div-produk-outlet-' + id).clone().appendTo('#clone');

		//hitung class ditempat clone
		var jumlah  = $('#clone .master-data-komponen').length;
		var id_baru = parseInt(id) + parseInt(jumlah);

		$('#clone .master-data-komponen').last().attr({
			"id":"div-produk-outlet-" + id_baru,
			"data-div-master-id": + id_baru
		});


		//data select produk terbaru
		$('#clone .invoice_detail_kode_produk').last().attr({
			"id" : "invoice_detail_kode_produk_" + id_baru,
			"data-id" : + id_baru
		});
		
		var selet_terbaru = $('#clone .invoice_detail_kode_produk').last().attr('id');

		//console.log('terbaru: ' + selet_terbaru );

		formInput 	=	'cabang=' + 6;

		//request data stok produk dan service
		$.post('/penjualan/request_data_stock_and_service_list',formInput, function(data)
		{					
			$('#invoice_detail_kode_produk').children().remove();
			$('#invoice_detail_kode_produk').append(data);
			$('#invoice_detail_kode_produk').trigger('change');
		});	

		$('#invoice_detail_kode_produk').val(json['customer_code']);
		$('#invoice_detail_kode_produk').trigger('change');

	});

	$('#coba-clone').click(function(){
		
		$('#clone .master-data-komponen').last().attr({
			"id":"div-produk-outlet-5",
			"data-div-master-id":"5"
		});

		var debug = $('#clone .master-data-komponen').last().attr('id');

		console.log('debug: ' + debug);

	});

	$('.invoice_detail_kode_produk').change(function(){
	
		var id 		=	$(this).attr('data-id');
		//var jumlah 	=	parseInt($('#po-outlet-identifier').val());
		var nama 	=	$('#invoice_detail_kode_produk_' + id + ' option:selected').text();
		var harga 	=	parseInt($('#invoice_detail_kode_produk_' + id + ' option:selected').attr('data-harga'));
		var stok 	=	$('#invoice_detail_kode_produk_' + id + ' option:selected').attr('data-stok');
		var kategori =	$('#invoice_detail_kode_produk_' + id + ' option:selected').attr('data-produk-kategori-id');

		$('#invoice_detail_nama_produk_' + id).val(nama);
		$('#invoice_stok_' + id).val(stok);

		$("#invoice_produk_kategori_id_" + id).val(kategori);
		$("#invoice_hpp_" + id).val(harga); //hpp

		if(stok > 0){
			$('#invoice_detail_jumlah_produk_' + id).val('1');
		}else
		{
			$('#invoice_detail_jumlah_produk_' + id + ',#invoice_detail_harga_' + id + ',#invoice_detail_total_' + id + ',#invoice_stok_' + id + ',#invoice_detail_nama_produk_' + id + ',#invoice_produk_kategori_id_' + id + ',#invoice_hpp_' + id).val('');
		}

		if(harga > 0)
		{
			//$('#invoice_detail_harga_' + id).val(harga);
		}else
		{
			//$('#invoice_detail_harga_' + id).val('');
		}

		//console.log('harga: ' + harga);

		//if(isNaN(jumlah))
		//{
		//	var jumlah =	0;
		//}		

		//var identifier 	=	parseInt(jumlah) + 1;

		/*
		$('#jumlah_' + id).val('1');
		$('#harga_' + id).val(harga);
		$('#sub_total_' + id).val(sub_total);

		$('#po-outlet-identifier').val(identifier);

		

		var qty =	parseInt($('#jumlah_' + id).val());

		var sub_total 	=	qty * harga;

		$('#sub_total_' + id).val(sub_total);

		//hitung_total_po_supplier();
		*/

	});


	$('#invoice_status_pembayaran').change(function(){

		var show =	$('#invoice_status_pembayaran option:selected').attr('data-show');
		var hide =	$('#invoice_status_pembayaran option:selected').attr('data-hide');
		var text =	$('#invoice_status_pembayaran option:selected').attr('data-text');

		var value 	=	$('#invoice_status_pembayaran option:selected').val();

		$(show).show();
		$(hide).hide();

		$('#penjualan-text-cara-bayar').text(text);	

		console.log('debug: ' + value);

		if(value == 'Tunai')
		{
			$('#text-tambahan-pembayaran-tempo, #text-tambahan-pembayaran-tempo-nominal').hide();
			$('#invoice_kode_akun_lunas').addClass('requiredField');
		}else
		{
			$('#text-tambahan-pembayaran-tempo, #text-tambahan-pembayaran-tempo-nominal').show();
			$('#invoice_uang_muka').val('0');
			$('#invoice_piutang_text').html('0');

			$('#invoice_kode_akun_lunas').removeClass('requiredField');
		}


	});

	$('#invoice_cabang_id').change(function(){

		var cabang = $('#invoice_cabang_id option:selected').val();

		$('#pelanggan_cabang_id').val(cabang);

		formInput 	=	'cabang=' + cabang;

		//request data stok produk dan service
		$.post('/penjualan/request_data_stock_and_service_list',formInput, function(data)
		{					
			$('.invoice_detail_kode_produk').children().remove();
			$('.invoice_detail_kode_produk').append(data);
			$('.invoice_detail_kode_produk').trigger('change');
		});	
		

		//request data karyawan
		$.post('/penjualan/request-data-karyawan',formInput, function(data)
		{					
			$('#invoice_sales_id').children().remove();
			$('#invoice_sales_id').append(data);
			$('#invoice_sales_id').trigger('change');
		});	

		//request data customer
		$.post('/penjualan/request-data-penjualan-belum-posting',formInput, function(data)
		{					
			$('#invoice_no_so').children().remove();
			$('#invoice_no_so').append(data);
			$('#invoice_no_so').trigger('change');
		});	

		//request data customer
		$.post('/penjualan/request-data-customer',formInput, function(data)
		{					
			$('#invoice_customer_code').children().remove();
			$('#invoice_customer_code').append(data);
			$('#invoice_customer_code').trigger('change');
		});			

		//request rekening
		$.post('/penjualan/request-akun-rekening',formInput, function(data)
		{					
			$('#invoice_kode_akun_lunas').children().remove();
			$('#invoice_kode_akun_lunas').append(data);
			$('#invoice_kode_akun_lunas').trigger('change');
		});	

	});

	$('#pembayaran_dari_ke').change(function(){
		$('#pembayaran_cabang_id').val('');
		$('#pembayaran_invoice').val('').trigger('change').children().remove();
		$('#pembayaran_jumlah_invoice,#pembayaran_total,#pembayaran_kode').val('');
	});

	$('#pembayaran_cabang_id').change(function(){

		var supplier  =	$('#pembayaran_dari_ke option:selected').val();
		var cabang_id =	$('#pembayaran_cabang_id').val(); //

		$('#pembayaran_jumlah_invoice,#pembayaran_total').val('');

		if(supplier == '')
		{
			toastr.error('Pilih data penerima','ERROR!');
			$('#pembayaran_cabang_id').val('');
			return false;
		}

		formInput 	=	'cabang=' + cabang_id + '&supplier=' + supplier;

		$.post('/pembelian/request-data-invoice',formInput, function(data)
		{			
			$('#pembayaran_invoice').children().remove();
			$('#pembayaran_invoice').append(data);
		});	

		$.post('/pembelian/request-nomer-pembayaran-hutang',formInput, function(data)
		{			
			$('#pembayaran_kode').val(data);
		});	


	});

	$('#pembayaran_invoice').change(function(){
		
		var saldo 			= $('#pembayaran_invoice option:selected').attr('data-saldo');
		var jumlah 			= $('#pembayaran_invoice option:selected').attr('data-jumlah');
		var terbayar		= $('#pembayaran_invoice option:selected').attr('data-terbayar');

		var nomer_invoice 	= $('#pembayaran_invoice option:selected').val();

		$("#pembayaran_jumlah_invoice").autoNumeric('set', saldo);
		$('#pembayaran_detail_no_invoice').val(nomer_invoice);

		$('#hutang_jumlah').val(jumlah);
		$('#hutang_terbayar').val(terbayar);
		$('#hutang_saldo').val(saldo);

	});

	$('#pembayaran_total').keyup(function(){

		var saldo = parseInt($('#pembayaran_jumlah_invoice').autoNumeric('get'));
		var bayar =	parseInt($(this).autoNumeric('get'));

		if(saldo < bayar)
		{
			toastr.clear();
			toastr.error('Jumlah pembayaran lebih besar dari invoice','ERROR!');
			$(this).val('');
		}

	});

	var beli_po_supplier 	=	function(po,um,cara,id,cabang,keterangan)
	{
		$('#beli-po-supplier-icon-' + po).removeClass('fa-cart-plus').addClass('fa-spin fa-spinner');		

		var formInput 	=	'po=' + po + '&um=' + um +'&cara=' + cara + '&id=' + id + '&cabang-id=' + cabang + '&keterangan=' + keterangan;
		
		$.post('/pembelian/po-supplier-save-beli',formInput, function(data)
		{				
	       var json = $.parseJSON(data);

	       if(json['status'] == '200')
	       {
	       		
	       		$('#beli-po-supplier-' + po).html('<a href="javascript:;"  class="btn blue tooltips" data-id="" data-container="body" data-placement="top" data-original-title="Terima Produk" data-target="#detail-po-terima-'+ po +'" data-toggle="modal"><i class="fa fa-download"></i></a>');
	       		
	       		$('#po-status-text-' + po).html('<span style="color:#1BBC9B">Sudah di Proses</span>');
	       		toastr.success(json['message'],'Notification');

	       }
		
		});			
	}

	if(page_identifier == 'po_outlet_form' && form_identifier == 'edit')
	{

		var set_produk_outlet 	=	function(){

			var formInput 	=	'nomer-po=' + no_po;

			$.post('/pembelian/po-outlet-detail-penawaran',formInput, function(data){

				var json = $.parseJSON(data);

				$.each(json,function(i,object){
				    
				    //console.log(i +'('+object.length+')');

				    var total_result 	=	i +'('+object.length+')';
				    total_result 		=	total_result.replace('result(','');
				    total_result 		=	parseInt(total_result.replace(')',''));

				    $.each(object, function (index, obj) {
				        
				        //console.log(index +' - '+ obj.penawaran_detail_product_kode +' - '+ obj.penawaran_detail_jumlah);

				        var urut 	= index;
				        var kode 	= obj.penawaran_detail_product_kode;
				        var jumlah 	= obj.penawaran_detail_jumlah;
				        var nama 	= obj.penawaran_detail_product_nama;


				        //console.log(urut +' - '+ kode);

				        $('#penawaran_product_kode_' + urut).val(kode).trigger('change');
				        $('#jumlah_' + urut).val(jumlah);
				        $('#penawaran_detail_product_nama_' + urut).val(nama);				        

				    });

				    var sisa 	=	5 - total_result;

				    if(sisa > 0){

					    for(i=total_result;i<=sisa;i++)
					    {

					        $('#jumlah_' + i).val('');
					        $('#penawaran_detail_product_nama_' + i).val('');
					        
					    }

					}

				});				

			});//eof post

		}//eof var function

		setTimeout(set_produk_outlet, 1000);

	}	

	if(page_identifier == 'po_supplier_form' && form_identifier == 'edit')
	{
     	
     	/*
        var set_produk = function(){

        	console.clear();
        
			var formInput 	=	'nomer-po=' + no_po;

			$.post('/pembelian/po-supplier-detail-penawaran',formInput, function(data){
				
		       var json = $.parseJSON(data);

				$.each(json,function(i,object){
				    
				    console.log(i +'('+object.length+')');

				    var urut 			=	'';
				    var total_result 	=	i +'('+object.length+')';
				    total_result 		=	total_result.replace('result(','');
				    total_result 		=	parseInt(total_result.replace(')',''));

				    //console.log(total_result);

				    $.each(object, function (index, obj) {
				        
				        //console.log(index +' - '+ obj.penawaran_detail_product_kode +' - '+ obj.penawaran_detail_jumlah);

				        urut 		= index;
				        var kode 	= obj.po_detail_product_kode;
				        var jumlah 	= obj.po_detail_jumlah_permintaan;
				        var harga 	= obj.po_detail_harga
				        var harga 	= harga.replace('.00','');

				        var sub_total = obj.po_detail_total;
				        var sub_total =	sub_total.replace('.00','');

				        $('#penawaran_product_kode_' + urut).val(kode).trigger('change');
				        $('#jumlah_' + urut).val(jumlah);
				        $('#harga_' + urut).val(harga);
				        $('#sub_total_' + urut).val(sub_total);

				    });

				    var sisa 	=	5 - total_result;

				    if(sisa > 0)
				    {

					    for(i=1;i<=sisa;i++)
					    {
					        
					        var j = urut + i;

					        $('#penawaran_product_kode_' + j).val('').trigger('change');

					        $('#jumlah_' + j).val('');
					        $('#harga_' + j).val('');
					        $('#sub_total_' + j).val('');

					        //console.log('j: ' + j + '\r\n');	

					        $('#div-produk-outlet-' + j).hide();
					    }

					}

				});

				$('#po-outlet-identifier').val('1');
				hitung_total_po_supplier();

			});	

        }//eof set var

        setTimeout(set_produk, 1000);
 		*/
	}

	$('.jumlah-po-supplier, .harga-satuan-po-supplier').keyup(function(){

		var urutan 	=	$(this).attr('data-urutan');

		hitung_sub_total_po_supplier(urutan);

	});


	$('#refresh-po-supplier').click(function(){
		
		refresh_po_supplier 	=	true;

		//location.reload();
		$('#po_outlet').val('').trigger('change');

		for(i=0;i<=4;i++)
		{
			$('#div-produk-outlet-' + i).show();

	        //$('#penawaran_product_kode_' + j).val('').trigger('change');

	        $('#jumlah_' + i).val('');
	        $('#harga_' + i).val('');
	        $('#sub_total_' + i).val('');			
		}

	});

	$('#po_uang_muka').keyup(function(){
		hitung_total_po_supplier();
	});

	$('#po_cara_bayar').change(function(){

		var show =	$('#po_cara_bayar option:selected').attr('data-show');
		var hide =	$('#po_cara_bayar option:selected').attr('data-hide');
		var text =	$('#po_cara_bayar option:selected').attr('data-text');

		$(show).show();
		$(hide).hide();

		$('#po-supplier-text-cara-bayar').text(text);		

	});

	$('#po_cabang_id').change(function(){

		if(trigger_po_supplier == false){

			$('#po_supplier').val('').trigger('change');

			trigger_cabang_id 	=	true;
		}

		//request generate po outlet base on cabang id
		var cabang_id = $('#po_cabang_id option:selected').val();

		var formInput =	'cabang-id=' + cabang_id;
		
		po_supplier_generate_number(formInput);
		generate_product_list(formInput);

		/*
        $.post('/pembelian/generate-product-list',formInput, function(data){    

            $('.penawaran_product_kode').children().remove();
            $('.penawaran_product_kode').append(data);
            $('.penawaran_product_kode').trigger('change');                    
        
        });  		
        */

        $('#po-outlet-identifier').val('0');
        $('#table-order-killy').html('');

        $('.div-add-produk').show();

	});

	var po_supplier_generate_number = function(formInput)
	{
		$.post('/pembelian/po-supplier-generate-number',formInput, function(data){			
	    	$('#po_nomer_po').val(data);					
		});	
	}

	var generate_product_list = function(formInput)
	{
        
        $.post('/pembelian/generate-product-list',formInput, function(data){    

            $('.penawaran_product_kode').children().remove();
            $('.penawaran_product_kode').append(data);
            $('.penawaran_product_kode').trigger('change');                    
        
        });  

	}

	//dropdown dari menu pembelian purchasing po-supplier
	$('#po_supplier').change(function(){

		if(trigger_cabang_id == false){
			trigger_po_supplier	=	true;
		}

		var cabang_id 	= $('#po_supplier option:selected').attr('data-cabang');
		var tanggal 	= $('#po_supplier option:selected').attr('data-date');
		var nomer_po	= $('#po_supplier option:selected').attr('data-po-nomer');
		var supplier 	= $('#po_supplier option:selected').attr('data-supplier');

		var tempo 		= $('#po_supplier option:selected').attr('data-jatuh-tempo');
		var cara_bayar 	= $('#po_supplier option:selected').attr('data-cara-bayar');
		var akun_bayar 	= $('#po_supplier option:selected').attr('data-akun-bayar');
		var keterangan 	= $('#po_supplier option:selected').attr('data-keterangan');

		var total 	=	0;

		if(trigger_po_supplier == true)
		{
			$('#po_cabang_id').val(cabang_id).trigger('change');
		}

		$('#po_tgl_pesan').val(tanggal);
		$('#po_nomer_penawaran').val(nomer_po);

		$('#po_supplier_id').val(supplier).trigger('change');
		$('#po_cara_bayar').val(cara_bayar).trigger('change');

		$('#po_hari_jatuh_tempo').val(tempo);
		$('#po_akun_bayar').val(akun_bayar).trigger('change');
		$('#po_keterangan').val(keterangan);


		//query data po detail
		//clear dulu
		for (i = 0; i <= 4; i++) 
		{
	        $('#penawaran_product_kode_' + i).val('').trigger('change');
	        $('#jumlah_' + i).val('');
		}		

		var formInput 	=	'nomer-po=' + nomer_po;

		$.post('/pembelian/pembelian-purchasing-detail-po',formInput, function(data){
			
	       var json = $.parseJSON(data);

			$.each(json,function(i,object){
			    
			    //console.log(i +'('+object.length+')');

			    var total_result 	=	i +'('+object.length+')';
			    total_result 		=	total_result.replace('result(','');
			    total_result 		=	parseInt(total_result.replace(')',''));

			    //console.log(total_result);

			    $.each(object, function (index, obj) {
			        
			        //console.log(index +' - '+ obj.penawaran_detail_product_kode +' - '+ obj.penawaran_detail_jumlah);

			        var urut 	= index;
			        var kode 	= obj.po_detail_product_kode;
			        var jumlah 	= obj.po_detail_jumlah_permintaan;
			        var harga 	= obj.po_detail_harga;
			        var harga 	= harga.replace('.00','');

			        var sub_total = obj.po_detail_total;
			        var sub_total =	sub_total.replace('.00','');

			        //console.log(urut +' - '+ kode);

			        $('#penawaran_product_kode_' + urut).val(kode).trigger('change');
			        $('#jumlah_' + urut).val(jumlah);
			        $('#harga_' + urut).val(harga);
			        $('#sub_total_' + urut).val(sub_total);

			    });

			    var sisa 	=	5 - total_result;

			    if(sisa > 0){

				    for(i=total_result;i<=sisa;i++)
				    {

				        $('#jumlah_' + i).val('');
				        $('#harga_' + i).val('');
				        $('#sub_total_' + i).val('');

				    }

				}

			});

			
			$('#po-outlet-identifier').val('1');
			hitung_total_po_supplier();
		});	

		//$('#penawaran_product_kode_0').val('101002').trigger('change');
		$('#po_nomer_po').val(nomer_po);

		trigger_po_supplier	=	false;

	});


	//dropdown dari menu pembelian po-outlet
	$('#po_outlet').change(function(){

		console.clear();

		reset_tanggal('po_tgl_input');

		var cabang_id 	= $('#po_outlet option:selected').attr('data-cabang');
		var tanggal 	= $('#po_outlet option:selected').attr('data-date');
		var nomer_po	= $('#po_outlet option:selected').attr('data-po-nomer');

		var total 	=	0;

		$('#po_cabang_id').val(cabang_id).trigger('change');

		$('#po_tgl_pesan').val(tanggal);
		$('#po_nomer_penawaran').val(nomer_po);

		//if(trigger_pembelian_po_outlet == true)
		//{
			var formInput 	=	'nomer-po=' + nomer_po;

			$.post('/pembelian/po-outlet-detail-penawaran',formInput, function(data){
				
				$('#table-order-killy').html(data);		

			});
		//}

		$('#po-outlet-identifier').val('1');
		$('.div-add-produk').hide();

		/*
		//query data po detail
		//clear dulu
		for (i = 0; i <= 4; i++) 
		{
	        $('#penawaran_product_kode_' + i).val('').trigger('change');
	        $('#jumlah_' + i).val('');

	        $('#div-produk-outlet-' + i).show();
		}		

		var formInput 	=	'nomer-po=' + nomer_po;

		$.post('/pembelian/po-outlet-detail-penawaran',formInput, function(data){
		
	       var json = $.parseJSON(data);
	        
			$.each(json,function(i,object){
			    
			    //console.log(i +'('+object.length+')');
			    var urut 	=	'';

			    var total_result 	=	i +'('+object.length+')';
			    total_result 		=	total_result.replace('result(','');
			    total_result 		=	parseInt(total_result.replace(')',''));

			    var sisa 			=	5 - total_result;

			    //console.log('total: ' + total_result + '\r\n');

			    $.each(object, function (index, obj) {
			        
			        //console.log(index +' - '+ obj.penawaran_detail_product_kode +' - '+ obj.penawaran_detail_jumlah);

			        urut 	= index;
			        var kode 	= obj.penawaran_detail_product_kode;
			        var jumlah 	= obj.penawaran_detail_jumlah
			        var harga 	= obj.harga
			        var harga 	= harga.replace('.00','');

			        var sub_total = obj.sub_total;
			        var sub_total =	sub_total.replace('.00','');

			        //console.log(urut +' - '+ kode);

			        $('#penawaran_product_kode_' + urut).val(kode).trigger('change');
			        $('#jumlah_' + urut).val(jumlah);
			        $('#harga_' + urut).val(harga);
			        $('#sub_total_' + urut).val(sub_total);

				    //console.log('urut: ' + urut + '\r\n');

				    //console.log('sisa: ' + sisa );

			    });

				//console.log('urut: ' + urut + '\r\n');	
				//console.log('sisa: ' + sisa + '\r\n');			    

				//if(refresh_po_supplier == false){

				    if(sisa > 0)
				    {

					    for(i=1;i<=sisa;i++)
					    {
					        
					        var j = urut + i;

					        $('#penawaran_product_kode_' + j).val('').trigger('change');

					        $('#jumlah_' + j).val('');
					        $('#harga_' + j).val('');
					        $('#sub_total_' + j).val('');

					        //console.log('j: ' + j + '\r\n');	

					        $('#div-produk-outlet-' + j).hide();
					    }

					}
				//}

				

			});

			if(total == 0 && refresh_po_supplier == true)
			{
				for(i=0;i<=4;i++)
				{
					$('#div-produk-outlet-' + i).show();
				}

				refresh_po_supplier = false;
			}



			$('#po-outlet-identifier').val('1');
			hitung_total_po_supplier();
		});	
		*/

		//$('#penawaran_product_kode_0').val('101002').trigger('change');

	});//po_outlet



	$('.tombol-rakit').click(function(){

		var kode 		= $(this).attr('data-kode');
		var cabang 		= $(this).attr('data-cabang');
		var identify 	= $(this).attr('data-identify');	
		
		$('#fa-cog-' + identify).addClass('fa-spin');

		$('#print-rakitan-' + identify).click();

	});

	$('#rakitan_detail_kode_produk').change(function(){

		var nama 	= $('#rakitan_detail_kode_produk option:selected').text();

		$('#rakitan_detail_kode_produk_hidden').val('1');
		$('#rakitan_detail_kode_produk_name').val(nama);
	
	});

	$('.refresh-list-produk-po-outlet').click(function(){

		var id 		=	$(this).attr('id');
		var jumlah 	=	parseInt($('#po-outlet-identifier').val());

		$('#refresh-' + id).addClass('fa-spin');

		$('#penawaran_product_kode_' + id).val('').trigger('change');
		$('#refresh-' + id).removeClass('fa-spin');
		$('#po-outlet-identifier').val(jumlah-1);
		$('#jumlah_' + id).val('');
		$('#penawaran_detail_product_nama_' + id).val('');
		$('#harga_' + id).val('');
		$('#sub_total_' + id).val('');

		hitung_total_po_supplier();

	});

	$('.penawaran_product_kode').change(function(){
		
		if(page_identifier == 'po_outlet_form' || page_identifier == 'po_supplier_form')
		{
			
			$('#penawaran_jumlah,#penawaran_harga').keypress(function(event) {
			    if (event.keyCode == 13) {
			        event.preventDefault();
			    }
			});

			var produk_kode =	$('.penawaran_product_kode option:selected').val();
			var produk_nama =	$('.penawaran_product_kode option:selected').text();

			//console.log('kode: ' + produk_kode + ' - nama: ' + produk_nama);
			if(produk_kode)
			{
				$('#penawaran_jumlah').val('1').focus();
			}else
			{
				$('#penawaran_jumlah').val('');
			}

		}
		else{

			var id 		=	$(this).attr('data-id');
			var jumlah 	=	parseInt($('#po-outlet-identifier').val());
			var nama 	=	$('#penawaran_product_kode_' + id + ' option:selected').text();
			var harga 	=	parseInt($('#penawaran_product_kode_' + id + ' option:selected').attr('data-harga'));

			if(isNaN(jumlah))
			{
				var jumlah =	0;
			}		

			var identifier 	=	parseInt(jumlah) + 1;

			$('#jumlah_' + id).val('1');
			$('#harga_' + id).val(harga);
			$('#sub_total_' + id).val(sub_total);

			$('#po-outlet-identifier').val(identifier);

			$('#penawaran_detail_product_nama_' + id).val(nama);

			var qty =	parseInt($('#jumlah_' + id).val());

			var sub_total 	=	qty * harga;

			$('#sub_total_' + id).val(sub_total);

			hitung_total_po_supplier();

		}

	});

	$('.hapus-list-produk-po-outlet').click(function(){
		$(this).closest('div.row').remove();
	});

	$('#penawaran_cabang_id').change(function(){

		var cabang_id 	 =	$('#penawaran_cabang_id option:selected').val();
		var cabang_kode	 =	$('#penawaran_cabang_id option:selected').attr('data-kode');

		var formInput 	=	'cabang-id=' + cabang_id + '&cabang-kode=' + cabang_kode;
		
		$.post('/pembelian/po-outlet-generate-number',formInput, function(data){
			
	       $('#penawaran_nomer').val(data);
	       $('#penawaran_keterangan').val('PO Outlet, ' + data);
					
		});		

		//generate produk berdasarkan cabang id
        //var formInput   =   '';

        $.post('/pembelian/generate-product-list',formInput, function(data){    

			$('.penawaran_product_kode').children().remove();
			$('.penawaran_product_kode').append(data);
			$('.penawaran_product_kode').trigger('change');                    
        
        });                     

	});



	$("#btn-clone").click(function(){
	    //$(".master-data-komponen").clone().appendTo("#clone");
	    var hitung 	=	$('.penawaran_product_kode').length + 1;

	    var html	=	'<div class="row">\n\
	    <div class="col-md-10">\n\
	    <div class="form-group">\n\
	    <select class="pilih2 form-control penawaran_product_kode" name="penawaran_product_kode[]" id="penawaran_product_kode_'+ hitung +'"  style="width:100%">\n\
	    <option value=""></option>\n\
	    </select>\n\
	    </div>\n\
	    </div>\n\
	    <div class="col-md-1">\n\
	    <div class="form-group text-centerx">\n\
	    <input type="text" class="form-control text-center number-only" name="jumlah[]" value="1">\n\
	    </div>\n\
	    </div>\n\
	    <div class="col-md-1">\n\
	    <div>\n\
	    <a href="javascript:;" class="btn btn-icon-only default hapus-list-produk-po-outlet">\n\
	    <i class="fa fa-trash"></i>\n\
	    </a>\n\
	    </div>\n\
	    </div>\n\
	    </div>';

	    $('#clone').append(html);

	    $('#penawaran_product_kode_' + hitung).select2();

	    var formInput	=	'';

		$.post('/pembelian/generate-product-list',formInput, function(data){
			
	        $('#penawaran_product_kode_' + hitung).append(data);
					
		});			    

	});

	$('#tambah-baris').click(function(){

		var html 	=	$('#master-copy').html();

		$('#append-data').append(html);

	});

	function format_rupiah(n, currency) 
	{
	    //return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	    return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "1.");
	}	


	$('.td-hpp-user').click(function(){

		var id = $(this).attr('id');

		$('#td-hpp-user-text-' + id).hide();
		$('#td-hpp-user-input-' + id).show();

		$('#product-hpp-user-input-' + id).focus();

	});

	$('.product-hpp-user-input').focusout(function(){

		var id 	 = $(this).attr('data-id');
		var baru =	$(this).val();
		var lama =	$(this).attr('data-old');
		var kode =	$(this).attr('data-kode');

		$('#td-hpp-user-input-' + id).hide();

		if(baru == lama)
		{
			$('#td-hpp-user-text-' + id).show();
		}else
		{
			
			$('#td-hpp-user-text-' + id).show().html('<i class="fa fa-spinner fa-spin"></i>');

			var formInput 	=	"stok_id=" + id + "&product_kode=" + kode + "&product_hpp=" + baru + "&identifier=hpp-user";

			$.post('/data/data-harga-jual-update',formInput, function(data){
				
		        var json 	=	$.parseJSON(data);

		        $('#td-hpp-user-text-' + id).show().html(json['message']);
						
			});					

			
		}

	});

	$('.td-hpp-dealer').click(function(){

		var id = $(this).attr('id');

		$('#td-hpp-dealer-text-' + id).hide();
		$('#td-hpp-dealer-input-' + id).show();

		$('#product-hpp-dealer-input-' + id).focus();

	});

	$('.product-hpp-dealer-input').focusout(function(){

		var id 	 = $(this).attr('data-id');
		var baru =	$(this).val();
		var lama =	$(this).attr('data-old');
		var kode =	$(this).attr('data-kode');

		$('#td-hpp-dealer-input-' + id).hide();

		if(baru == lama)
		{
			$('#td-hpp-dealer-text-' + id).show();
		}else
		{
			
			$('#td-hpp-dealer-text-' + id).show().html('<i class="fa fa-spinner fa-spin"></i>');

			var formInput 	=	"stok_id=" + id + "&product_kode=" + kode + "&product_hpp=" + baru + "&identifier=hpp-dealer";

			$.post('/data/data-harga-jual-update',formInput, function(data){
				
		        var json 	=	$.parseJSON(data);

		        $('#td-hpp-dealer-text-' + id).show().html(json['message']);
						
			});					

			
		}

	});


	$('.td-hpp').click(function(){

		var id = $(this).attr('id');

		$('#td-hpp-text-' + id).hide();
		$('#td-hpp-input-' + id).show();

		$('#product-hpp-input-' + id).focus();

	});

	$('.product-hpp-input').focusout(function(){

		var id = $(this).attr('data-id');
		var baru =	$(this).val();
		var lama =	$(this).attr('data-old');
		var kode =	$(this).attr('data-kode');

		$('#td-hpp-input-' + id).hide();

		if(baru == lama)
		{
			$('#td-hpp-text-' + id).show();
		}else
		{
			//var uang 	=	baru;
			//var fixed 	=	format_rupiah(uang,'');
			//var rp 		=	fixed.slice(0,-3);
			
			$('#td-hpp-text-' + id).show().html('<i class="fa fa-spinner fa-spin"></i>');

			var formInput 	=	"stok_id=" + id + "&product_kode=" + kode + "&product_hpp=" + baru + "&identifier=hpp";

			$.post('/data/data-harga-jual-update',formInput, function(data){
				
		        var json 	=	$.parseJSON(data);

		        $('#td-hpp-text-' + id).show().html(json['message']);
						
			});					

			
		}

	});

	function generate_product_code(kategori,merk)
	{
		if(kategori && merk)
		{
			
			var nomer 		=	kategori + merk;
			var formInput 	=	"nomer=" + nomer;

			$.post('/setting/setting-produk-generate-number',formInput, function(data){
				
		        var json 	=	$.parseJSON(data);

		        $('#product_kode').val(json['kode_produk']);
						
			});			

		}else
		{
			$('#product_kode').val('');
		}
	}

	$('#product_merk').change(function(){
		
		var kategori_kode	=	$('#product_category_id option:selected').val();
		var merk_kode		=	$("#product_merk option:selected" ).val();

		generate_product_code(kategori_kode,merk_kode);

	});

	$('#product_category_id').change(function(){

		var data_rel 	=	$("#product_category_id option:selected" ).attr('data-rel');
		var json 		=	$.parseJSON(data_rel);

		var kode 	=	json['kode'];
		var dibeli 	=	json['dibeli'];
		var dijual 	=	json['dijual'];
		var disimpan=	json['disimpan'];

		var data_id 	=	$( "#product_category_id option:selected" ).attr('data-id');
		var json 		=	$.parseJSON(data_id);

		var akun_biaya 		=	json['rek_hpp'];
		var akun_penjualan 	=	json['rek_penjualan'];
		var akun_persediaan	=	json['rek_persediaan'];

		var kategori_kode	=	$('#product_category_id option:selected').val();
		var merk_kode		=	$("#product_merk option:selected" ).val();

		generate_product_code(kategori_kode,merk_kode);

		if(dibeli != null)
		{
			if(dibeli == 'Aktif')
			{	
				var dibeli = 'ya';
			}
			else
			{
				var dibeli = 'tidak';
			}

			$('#product_status_beli').val(dibeli);

		}else
		{
			$('#product_status_beli').val('');
		}

		if(dijual != null)
		{
			if(dijual == 'Aktif')
			{	
				var dijual = 'ya';
			}
			else
			{
				var dijual = 'tidak';
			}

			$('#product_status_jual').val(dijual);
			
		}else
		{
			$('#product_status_jual').val('');
		}

		if(disimpan != null)
		{
			if(disimpan == 'Aktif')
			{	
				var disimpan = 'ya';
			}
			else
			{
				var disimpan = 'tidak';
			}

			$('#product_status_simpan').val(disimpan);
			
		}else
		{
			$('#product_status_simpan').val('');
		}
		
		if(akun_biaya != null)
		{
			//$('#akun_biaya option[value='+ akun_biaya + ']').prop('selected', true);
			$('#product_akun_biaya').val(akun_biaya);
		}else
		{
			//$('#akun_biaya').val('');
			$('#product_akun_biaya').val('');
		}

		if(akun_penjualan != null)
		{
			$('#product_akun_jual').val(akun_penjualan);
		}else
		{
			$('#product_akun_jual').val('');
		}

		if(akun_persediaan != null)
		{
			$('#product_akun_simpan').val(akun_persediaan);
		}else
		{
			$('#product_akun_simpan').val('');
		}

		/*
		$('#akun_biaya option[value='+ akun_biaya + ']').prop('selected', true);	
		$('#akun_penjualan option[value='+ akun_penjualan + ']').prop('selected', true);
		$('#akun_persediaan option[value='+ akun_persediaan + ']').prop('selected', true);
		*/

	});



	$('#setting_lrm_sub_klasifikasi').change(function(){

		var parent 	=	$( "#setting_lrm_sub_klasifikasi option:selected" ).val();

		var formInput 	=	"parent=" + parent;

		$.post('/ajax/get-sub-klasifikasi',formInput, function(data){
			
	        $('#setting_lrm_klasifikasi').children().remove();
	        $('#setting_lrm_klasifikasi').append(data);
					
		});

	});

	$('#user_employee_code').change(function(){

		var cabang_id 	=	$( "#user_employee_code option:selected" ).attr('data-rel');
		var nama 		=	$( "#user_employee_code option:selected" ).attr('data-id');

		$('#user_cabang_id').val(cabang_id);	
		$('#user_fullname').val(nama);	

	});

	$('#rekening_no_akun').change(function(){

		var kode_akun 	=	$( "#rekening_no_akun option:selected" ).attr('data-rel');

		$('#rekening_kode').val(kode_akun);

	});

	$('#pelanggan_type').change(function(){

		var mode 	=	$( "#pelanggan_type option:selected" ).attr('data-rel');
		var text 	=	$( "#pelanggan_type option:selected" ).text();

	
		
		if(mode == 'close')
		{
			$('.pelanggan_instansi_nama').html('');
			$('.pelanggan_instansi_alamat').html('');
			$('#pelanggan_instansi_nama').removeClass('requiredField');
			$('#detail-tipe-kustomer').hide();

			$('#pelanggan_instansi_nama, #pelanggan_instansi_alamat').val('');
		}
		else
		{
			$('.pelanggan_instansi_nama').html(text);
			$('.pelanggan_instansi_alamat').html(text);
			
			$('#pelanggan_instansi_nama').addClass('requiredField');
			$('#detail-tipe-kustomer').show();

			$('#pelanggan_instansi_nama, #pelanggan_instansi_alamat').val('');
		}

	});

	$('.btn-generate-new').click(function(){

		var btn_id 			=	$(this).attr('id');
		var form_action 	=	$(this).attr('data-rel');

		var class_action 	=	btn_id;

		var dbname 	=	$('#dbname option:selected').text();

        var formInput   =   "action=" + form_action + "&dbname=" + dbname + "&class=" + class_action;

        $('#' + btn_id).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('/ajax/dump-content', formInput, function(data){

        	console.log(data);
            
            var json    =   $.parseJSON(data);
                        
            if(json['status'] == '200')
            {

                toastr.success(json['message'],'Notification');	//

                $('#' + btn_id).html('DONE');

            }
            else
            {
                //window.location = json['url'];
                toastr.error(json['message'],'ERROR!');
                $('#' + btn_id).html(json['button-text']);
            }
            


        });

	});

	$('.btn-generate').click(function(){

		var btn_id 	=	$(this).attr('id');
		var action 	=	btn_id.replace('btn-','');

		var class_action 	=	$(this).attr('data-rel');

		var dbname 	=	$('#dbname option:selected').text();

        var formInput   =   "action=" + action + "&dbname=" + dbname;

        $('#' + btn_id).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('/ajax/' + action, formInput, function(data){
            
            var json    =   $.parseJSON(data);
                        
            if(json['status'] == '200')
            {

                toastr.success(json['message'],'Notification');	//

                $('#' + btn_id).html('DONE');

            }
            else
            {
                //window.location = json['url'];
                toastr.error(json['message'],'ERROR!');
                $('#' + btn_id).html(json['button-text']);
            }
            


        });

	});


	//save or update form handling
	$('.form-add-edit').submit(function(){

		var id = $(this).attr('id');

		//alert(id);
		//return false;

		toastr.clear();
		
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

				$('.fa-spinner').hide();
				$("html,body").animate({ scrollTop: 0 }, "slow");
				toastr.success(json['message'],'Notification');	//					

			}else{
				window.location	=	json['url'];
			}
			
		}
		else
		{
			$('.fa-spinner').hide();
			$("html,body").animate({ scrollTop: 0 }, "slow");
			toastr.error(json['message'],'ERROR!').css("width","400px");	//			 	
		}
		
		
	}

	function form_save_validation(formData, jqForm, options) 
	{ 
		
		//alert('validation');
		//return false;

		var hasError	= false;
		var form		= jqForm[0];
		
		$('.fa-spinner').show(); 
				
        $('.form-add-edit .requiredField').each(function() {
            
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
			
			$("html,body").animate({ scrollTop: 0 }, "slow");
			
			toastr.error('Isi semua kolom dengan tanda *','ERROR!').css("width","400px");
			//$('.alert').show();
			
			return false;
		}	
		
		$('.save-loading').show();	
		return true;
				
	}	
	//eof product save



	var message	=	getUrlVars()["msg"];
	
	if(message == 1)
	{
		//$('.alert').show().addClass('alert-success');
		//var notes	=	'Data telah tersimpan.';
		//$('.alert-notes').html('<strong>'+notes+'</strong>');
		toastr.success('Data telah disimpan','Notification');
		
	}
	else if(message == 2)
	{
		//$('.alert').show().addClass('alert-success');
		//var notes	=	'Data telah diperbarui.';
		//$('.alert-notes').html('<strong>'+notes+'</strong>');
		
		toastr.success('Data telah diperbarui', 'Notification');
	}	
	

	$('.btn-refresh').click(function(){
		var myString		= self.location.toString();
		var mySplitResult	= myString.split("?");
		
		var url	=	mySplitResult[0];
		
		//setTimeout(function() {
			window.location	=	mySplitResult[0];						 
		//}, 1000);	
	});	
	

	$(".custom-upload").filestyle({
		buttonName: "btn-primary green",
		buttonBefore: true,
		'buttonText': '&nbsp;&nbsp;Choose Images',
	});
		
	
	
	//upload file handling
	//$('input[type=file]').change(function(){
	$('#image-upload,#image-upload-1,#image-upload-2,#image-upload-3,#image-upload-4,#image-upload-5').change(function(){
		
		var filename	=	$(this).val();//$(this).attr("value");
		var ext 		=	filename.split('.').pop();
		var ext			=	'.' + ext;
		
		var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;   
		if(valid_extensions.test(ext))
		{ 
			
			//cek file size
			if(window.ActiveXObject){
				var fso = new ActiveXObject("Scripting.FileSystemObject");
				var filepath = document.getElementById('image-upload').value;
				var thefile = fso.getFile(filepath);
				var sizeinbytes = thefile.size;
			}else{
				var sizeinbytes = document.getElementById('image-upload').files[0].size;
			}
		
			var fSExt = new Array('Bytes', 'KB', 'MB', 'GB');
			fSize = sizeinbytes; i=0;while(fSize>900){fSize/=1024;i++;}
			
			//2148576
			//alert(fSize+' '+fSExt[i]);
			//alert((Math.round(fSize*100)/100)+' '+fSExt[i]);
			
			if(sizeinbytes > 2148576)
			{
			   //alert('Ukuran file tidak boleh lebih dari 1 Mb');
			   
			   toastr.error('<br />File size is to large','ERROR!');
			   
			   $(".custom-upload").filestyle('clear');
			   $('#image-counter').val('0'); 
			   $(this).val('');			
			   return false;	
			}
	
	
		   else
		   {
				$('#image-counter').val('1');  
		   }
		}
		else
		{
		   
		   toastr.error('<br />Invalid File Extension.<br />Please Upload only .jpg/.png','ERROR!');
		   
		   //alert('Invalid file extension, please upload .jpg/.png');
		   $('#image-counter').val('0'); 
		   $(this).val('');
		   //$(this).attr({"value":""});
		   
		   $(".custom-upload").filestyle('clear');
		}		
				
	})  //upload file handling		
	
	
	//$.get("/token", function( data ) {	
	//	$('.secure-form').append('<input type="hidden" name="token" class="token" value="'+ data +'" />');
	//});		


	$('.delete-single-baru').btsConfirmButton(
		{
			msg: "",
			className: 'fa fa-question-circle',//'btn btn-default',
			timeout: 2500,
		}, 
		
		function(e) {
		
		var id			=	$(this).attr('data-id');
		var page 		=	$(this).attr('data-page');
		var controller	=	$(this).attr('data-controller');
		var field 		=	$(this).attr('data-field');


		toastr.clear();
		
		$('.parent-'+id).remove();

		var formInput	=	'id=' + id + "&page=" + page + '&field=' + field;

		$.post('/'+ controller +'/delete-single',formInput, function(data){
		
			var json	=	$.parseJSON(data);

			if(json['status'] == '200')
			{	
				toastr.success(json['message'], 'Notification');	
				
				$('#total-data-text').html(json['total']);
				
				if( $(".counter").length == 0 && json['total'] > 0)
				{
					location.reload();
				}									
			}
			else
			{
				toastr.error(json['message'],'Notification');
			}
					
		});
		
					
	});//eof delete single	


	$('.delete-single').btsConfirmButton(
		{
			msg: "",
			className: 'fa fa-question-circle',//'btn btn-default',
			timeout: 2500,
		}, 
		
		function(e) {
		
		var id			=	$(this).attr('data-id');
		var identifier	=	$(this).attr('data-type');


		toastr.clear();
		
		$('.parent-'+id).remove();

		var formInput	=	'id=' + id + "&identifier=" + identifier;
		
		$.post('/ajax/delete-single',formInput, function(data){
			
			var json	=	$.parseJSON(data);

			if(json['status'] == '200')
			{	
				toastr.success(json['message'], 'Notification');	
				
				$('#total-data-text').html(json['total']);
				
				if( $(".counter").length == 0 && json['total'] > 0)
				{
					location.reload();
				}									
			}
			else
			{
				toastr.error(json['message'],'Notification');
			}
					
		});
		
					
	});//eof delete single	
	
	$('.delete-single-image').btsConfirmButton(
		{
			msg: "",
			className: 'fa fa-question-circle',//'btn btn-default',
			timeout: 2500,
		}, 
		
		function(e) {
		
		var id			=	$(this).attr('data-id');
		var identifier	=	$(this).attr('data-type');
		
		$('#image-holder-'+id).remove();
	
		var formInput	=	'id=' + id + '&identifier=' + identifier;
		
		$.post('/admin/delete_single_image',formInput, function(data){

			var json	=	$.parseJSON(data);

			if(json['status'] == '200')
			{	
				toastr.success(json['message'], 'Notification');	
			}
			else
			{
				toastr.error(json['message'],'Notification');
			}
					
		});
		
					
	});//eof delete single	
	
		
	$('.btn-delete-checkbox-baru').btsConfirmButton(
		{
			msg: "",
			className: 'icon-question',//'btn btn-default',
			timeout: 3500,
		}, 
		
		function(e) {
		
		toastr.clear();
		
		var final = '';
		
		$('.checkbox-delete:checked').each(function(){        
		
			var values = $(this).val();
			var parent	= $('.parent-'+ values);
			
			parent.slideUp(500).delay(800).remove();

			final += values + ',';
		
		});
		

		
		if(final)
		{

			$('.loading-delete').show();

			//var identifier 	=	$(this).attr('data-type');
			//var page 		=	$(this).attr('data-rel');

			var page 		=	$(this).attr('data-page');
			var controller	=	$(this).attr('data-controller');
			var field 		=	$(this).attr('data-field');


			var formInput	=	'id=' + final + '&page='+ page +'&field='+ field;
			
			$.post('/'+ controller +'/delete-multi',formInput, function(data){


				var json	=	$.parseJSON(data);
				
				if(json['status'] == '200')
				{
					
					$('#total-data-text').html(json['total']);
					
					toastr.success(json['message'],'Notification');
					
					if( $(".counter").length == 0 && json['total'] > 0)
					{
						location.reload();
					}
				
				}
				else
				{
					toastr.error(json['message'],'Notification');
				}
				
				$('.loading-delete').hide();
				
			});

		}
		else
		{
			toastr.error('Please select an item(s)', 'ERROR!');
		}
		
		return false;
					
	});

	$('.btn-delete-checkbox').btsConfirmButton(
		{
			msg: "",
			className: 'icon-question',//'btn btn-default',
			timeout: 3500,
		}, 
		
		function(e) {
		
		toastr.clear();
		
		var final = '';
		
		$('.checkbox-delete:checked').each(function(){        
		
			var values = $(this).val();
			var parent		= $('.parent-'+ values);
			
			parent.slideUp(500).delay(800).remove();

			final += values + ',';
		
		});
		

		
		if(final)
		{

			$('.loading-delete').show();
			var formInput	=	'id=' + final + '&identifier=' + $(this).attr('data-type');
			
			$.post('/ajax/delete_multi',formInput, function(data){

				var json	=	$.parseJSON(data);
				
				if(json['status'] == '200')
				{
					
					$('#total-data-text').html(json['total']);
					
					toastr.success(json['message'],'Notification');
					
					if( $(".counter").length == 0 && json['total'] > 0)
					{
						location.reload();
					}
				
				}
				else
				{
					toastr.error(json['message'],'Notification');
				}
				
				$('.loading-delete').hide();
				
			});

		}
		else
		{
			toastr.error('Please select an item(s)', 'ERROR!');
		}
		
		return false;
					
	});//eof delete image	
	
    $('#custom-url').alphanumeric({allow:"-",nocaps:true});
	$('.number-only').numeric();
	$('.number-only-price').numeric({allow:'.'});	

	var rupiah 	=	function(angka)
	{
	    var rupiah = '';
	    var angkarev = angka.toString().split('').reverse().join('');
	    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	    return rupiah.split('',rupiah.length-1).reverse().join('');
	}

	//$('.detail-po-modal').

	//reload page on modal close
	$('.detail-po-modal').on('hidden.bs.modal', function () {
		//location.reload();
	});

	$('.detail-po-terima-modal').on('shown.bs.modal', function(){
    	$('.penerimaan_no_surat_jalan').focus();
	})

	$('[data-toggle="tooltip"]').on('click', function () {
	   $(this).tooltip('hide');
	})
	
	$(function() {
		$('body').confirmation({
			selector: '[data-toggle="confirmation"]'
		});
		$('.confirmation-callback').confirmation({
			onConfirm: function() { 
				
				var identifier = $(this).attr('data-identifier');

				if(identifier == 'po-supplier')
				{			
					var po 	 =	$(this).attr('data-po');
					var um 	 =	$(this).attr('data-um');
					var cara =	$(this).attr('data-cara-bayar'); 
					var id 	 =	$(this).attr('data-id');

					var cabang 		=	$(this).attr('data-cabang-id');
					var keterangan 	=	$(this).attr('data-keterangan');

					beli_po_supplier(po,um,cara,id,cabang,keterangan); 
				}
				

			}
			//onCancel: function() { alert('cancel') }
		});
	});

	$('.simpan-penerimaan').click(function(){

		var po 		=	$(this).attr('data-nomer-po');
		var content =	$(this).attr('data-content');

		var spj 	=	$('#penerimaan_no_surat_jalan_' + po).val();
		var tanggal =	$('#penerimaan_tanggal_' + po).val();

		if(spj == '' || tanggal == '')
		{
			toastr.error('Isi Nomer Surat Jalan','Notification');
			$('#penerimaan_no_surat_jalan_' + po).focus();
		}
		else
		{

			$('#simpan-penerimaan-' + po).html('<span><i class="fa fa-spin fa-spinner"></i></span>');

			var formInput	=	'penerimaan_no_po=' + po + '&penerimaan_no_surat_jalan=' + spj + '&penerimaan_tanggal=' + tanggal + '&content=' + content;
			
			$.post('/pembelian/po_supplier_penerimaan_save',formInput, function(data){

				var json =	$.parseJSON(data);

				if(json['status'] == 200)
				{

					$('#penerimaan_no_surat_jalan_' + po).attr('disabled','true');

					$('#po-status-text-' + po).html('<span style="color:#3598dc">Diterima</span');
					//$('#tombol-terima-' + po).html('<a href="/cetak/po-penerimaan?no='+ po +'" class="print-po-penerimaan tooltips various fancybox.iframe btn purple-studio" id="print-po-penerimaan-'+ po +'" data-controller="" data-page="" data-container="body" data-placement="top" data-original-title="Cetak Bukti Penerimaan"><i class="glyphicon glyphicon-print"></i></a>');

					$('#tombol-terima-' + po).html('<a href="javascript:;"  class="btn purple-studio tooltips" data-id="'+ po +'" data-container="body" data-placement="top" data-original-title="Lihat & Cetak Bukti Penerimaan" data-target="#detail-po-terima-'+ po +'" data-toggle="modal"><i class="glyphicon glyphicon-print"></i></a>');

					$('#simpan-penerimaan-' + po).hide();

					$('#penerimaan_tanggal_input_' + po).removeClass('date-picker');

					toastr.success(json['message'],'Notification');

					$('#print-po-penerimaan-modal-' + po).show();

					$('#text-no-penerimaan-' + po).html(json['no_penerimaan']);

					//console.log(data);
				}

			});			
			
			
		}

	});

	var reset_tanggal =	function(element){
		
		var d = new Date();

		var month = d.getMonth()+1;
		var day = d.getDate();

		var tanggal = d.getFullYear() + '-' +
		    ((''+month).length<2 ? '0' : '') + month + '-' +
		    ((''+day).length<2 ? '0' : '') + day;


		$('#' + element).val(tanggal);

		//console.log('reset tanggal: ' + tanggal);
	}

	var hitung_sub_total_form_penjualan = function(urutan)
	{
		var qty 	= $('#invoice_detail_jumlah_produk_' + urutan).val();
		var harga 	= $('#invoice_detail_harga_' + urutan).autoNumeric('get'); //$('#harga_' + urutan).val();
		var total 	= parseFloat(qty) * parseFloat(harga);

		$('#invoice_detail_total_' + urutan).autoNumeric('set', total);

		hitung_total_form_penjualan();
	}

	var hitung_total_form_penjualan = function()
	{
		//hitung total subtotal
	    var total 		=	0;
	    var uang_muka 	=	$('#invoice_uang_muka').autoNumeric('get');
	    var terhutang 	=	'0';	

	    $('.invoice_detail_total').each(function(){
	    	total 	+=	parseFloat($(this).autoNumeric('get')); //+$(this).val();
	    });

	    var number 			=	numeral(total);
	    var total_formated 	=	number.format('0,0');

	    $('#invoice_total_setelah_pajak_text').html(total_formated);
	    $('#invoice_total_setelah_pajak, #invoice_piutang').val(total);
	    
	    terhutang 			=	total - uang_muka;
	    
	    terhutang_formated	=	numeral(terhutang);
	    terhutang_formated	=	terhutang_formated.format('0,0');

	    $('#invoice_piutang_text').html(terhutang_formated);
	    $('#invoice_piutang').val(terhutang);
	    $('#invoice_total_setelah_pajak').val(total);	    
	}



});

function printElem(options){
     $('#print-invoice').printElement(options);
 }

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function hitung_sub_total_po_supplier(urutan)
{
	var qty 	= $('#jumlah_' + urutan).val();
	var harga 	= $('#harga_' + urutan).autoNumeric('get'); //$('#harga_' + urutan).val();
	var total 	= parseFloat(qty) * parseFloat(harga);

	//$('#sub_total_' + urutan).val(total);
	$('#sub_total_' + urutan).autoNumeric('set', total);

	hitung_total_po_supplier();
}

function hitung_total_po_supplier()
{
	//hitung total subtotal
    var total 		=	0;
    var uang_muka 	=	$('#po_uang_muka').val();
    var terhutang 	=	'0';	

    $('.sub-total-po-supplier').each(function(){
    	total 	+=	parseFloat($(this).autoNumeric('get')); //+$(this).val();
    });

    var number 			=	numeral(total);
    var total_formated 	=	number.format('0,0');

    $('#po_total_text').html(total_formated);
    $('#po_total').val(total);

    terhutang 			=	total - uang_muka;
    
    terhutang_formated	=	numeral(terhutang);
    terhutang_formated	=	terhutang_formated.format('0,0');

    $('#po_hutang_text').html(terhutang_formated);
    $('#po_hutang').val(terhutang);
    $('#po_total_setelah_pajak').val(total);
}
