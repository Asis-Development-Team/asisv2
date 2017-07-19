$(document).ready(function(){

	/*
	var php				=	$.parseJSON(page_data);	
	var page_identifier	=	php['page_identifier'];
	var no_po 			=	php['no_po'];
	var form_identifier =	php['form_identifier'];
	var no_invoice 		=	php['no_invoice'];

	var total_po_detail =	php['total_po_detail'];
	*/

	var cabang_id_before 	=	'';
	var nomer_po_before 	=	'';

	console.clear();


	/*

	var cabang_before = $("#po_cabang_id");
	cabang_before.data("prev",cabang_before.val());
	cabang_before.change(function(data){

	  var jqThis = $(this);
	  cabang_id_before = jqThis.data("prev");

	  jqThis.data("prev",jqThis.val());

	  $('#po_outlet').val('').trigger('change');

	});
	*/

	$(".delete_po_supplier_detail_temp").click(function(){

		var id = $(this).attr("data-id");
		var po = $(this).attr("data-po");

		$("#caption_" + id).slideUp();

		formInput = "id=" + id  + "&po=" + po + '&temporary=0';

		$.post("/pembelian/po_supplier_temp_delete",formInput, function(data){

			var json = $.parseJSON(data);

			$("#total_po_outlet").autoNumeric("set", json["total"]);

		});
		
	});

	$('#penawaran_harga').keypress(function(e){
		if(e.keyCode == 13)
		{
			$('#save_po_supplier_temp').click();
		}
	});	

	$('#save_po_supplier_temp').click(function(){

		if($('#po_supplier_product_kode').val() == '' || $('#penawaran_jumlah').val() == '' || $('#penawaran_harga').val() == '')
		{

			toastr.error('Isi produk, jumlah dan harga','ERROR!');

			return false;

		}else{

			$('#save_po_supplier_temp_spinner').show();

			var cabang 	= $('#po_cabang_id option:selected').val();
			var no_po	= $('#po_nomer_po').val();
			var po_ideintifer	= $('#po-outlet-identifier').val();

			var jumlah 	=	$('#penawaran_jumlah').val();
			var harga 	=	$('#penawaran_harga').val();

			var produk_kode =	$('.penawaran_product_kode option:selected').val();
			var produk_nama =	$('.penawaran_product_kode option:selected').text();

			var formInput = 'cabang=' + cabang + '&nopo=' + no_po + '&cabang_id_before=' + cabang_id_before + '&jumlah=' + jumlah + '&harga=' + harga + '&kode=' + produk_kode + '&nama=' + produk_nama;

			$.post('/pembelian/po-supplier-temp-save',formInput, function(data){

				var json = $.parseJSON(data);

				$('#table-order-killy').html(json['html']);

				$('#div-total').show();

				$('#total_po_outlet').autoNumeric('init');
				$('#total_po_outlet').autoNumeric('set',json['total']);

				$('#save_po_supplier_temp_spinner').hide();

				$('.penawaran_product_kode').val('').trigger('change');

				var jumlah 	=	$('#penawaran_jumlah').val('');
				var harga 	=	$('#penawaran_harga').val('');

			});

		}

	});

});