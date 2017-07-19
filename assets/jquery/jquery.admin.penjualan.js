$(document).ready(function(){
	
	var php				=	$.parseJSON(page_data);	
	var page_identifier	=	php['page_identifier'];
	var form_identifier =	php['form_identifier'];

	$('#piutang_jumlah_pembayaran').keyup(function(){

		var saldo 	=	parseInt($('#piutang_sisa_invoice').autoNumeric('get')); 
		var bayar	=	parseInt($(this).autoNumeric('get'));

		//nominal sebelum opsi update
		var bayar_lama 	=	parseInt($('#piutang_jumlah_pembayaran_lama').val());
		var piutang 	=	parseInt($('#piutang_saldo').val()); 

		var limit 		= bayar_lama + piutang;

		if(form_identifier == 'add' && saldo < bayar)
		{
			toastr.clear();
			toastr.error('Jumlah pembayaran lebih besar dari invoice','ERROR!');
			$(this).val('');
		}
		else if(form_identifier == 'edit' && bayar > limit)
		{
			toastr.clear();
			toastr.error('Jumlah pembayaran tidak boleh lebih dari: ' +  limit ,'ERROR!');
			$(this).val(bayar_lama);
		}

	});

	$('#piutang_invoice').change(function(){

		var saldo 		= $('#piutang_invoice option:selected').attr('data-saldo');
		var piutang_id 	= $('#piutang_invoice option:selected').attr('data-piutang-id');
		var terbayar 	= $('#piutang_invoice option:selected').attr('data-piutang-terbayar');

		$('#piutang_sisa_invoice').autoNumeric('set', saldo);
		$('#piutang_id').val(piutang_id);
		$('#piutang_terbayar').val(terbayar);

	});

	$('#piutang_konsumen').change(function(){

		var konsumen = $('#piutang_konsumen option:selected').val();

		var formInput = 'konsumen=' + konsumen;

		//ambil data konsumen
		$.post('/penjualan/pembayaran_piutang_request_invoice',formInput, function(data)
		{					       			
			$('#piutang_invoice').children().remove();
			$('#piutang_invoice').append(data);
			$('#piutang_invoice').trigger('change');      	       
		});			

	});

	$('#piutang_cabang_id').change(function(){
		
		var cabang  =	$('#piutang_cabang_id option:selected').val();

		var formInput = 'cabang=' + cabang;

		//ambil data konsumen
		$.post('/penjualan/pembayaran_piutang_request_konsumen',formInput, function(data)
		{				
	       
	       	//var json = $.parseJSON(data);
			
			$('#piutang_konsumen').children().remove();
			$('#piutang_konsumen').append(data);
			$('#piutang_konsumen').trigger('change');      	       

		});			

	});

	$('#invoice_detail_jumlah_produk,#invoice_detail_harga').keypress(function(event) {
	    if (event.keyCode == 13) {
	        event.preventDefault();
	        $('#save_invoice_temp').click();
	    }
	});	

	$('#save_invoice_temp').click(function(){

		if($('#invoice_detail_kode_produk').val() == '' || $('#invoice_detail_jumlah_produk').val() == '' || $('#invoice_detail_harga').val() == '' )
		{
			toastr.error('Isi produk, jumlah dan harga','ERROR!');

			return false;			
		}
		else
		{

			$('#save_invoice_temp_spinner').show();

			var cabang 		= $('#invoice_cabang_id option:selected').val();
			var produk_kode = $("#invoice_detail_kode_produk option:selected").val();
			var produk_nama = $('#invoice_detail_nama_produk').val();
			var jumlah 		= $('#invoice_detail_jumlah_produk').val();
			var harga 		= $('#invoice_detail_harga').val();

			formInput 	=	'';

		}

	});

});