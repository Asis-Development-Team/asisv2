$(document).ready(function(){


	$('#btn-clone-form-kas-keluar-delete').click(function(){

		$('#clone-div .master-form').last().remove();
		total_kas_keluar();

	});

	$('#btn-clone-form-kas-keluar').click(function(){

		var cabang 	=	$('#kas_cabang_id option:selected').val();

		if(!cabang)
		{	
			toastr.error('Pilih data outlet!','ERROR!');
			return false;
		}else
		{			
	        $.get("/keuangan/request_kas_keluar_clone_form?cabang=" + cabang, function(data, status){	            
	            $('#clone-div').append(data);
	        });		
		}

	});

	$('.kas_nilai').keyup(function()
	{
		total_kas_keluar();
	});

	function total_kas_keluar()
	{
        var sum 		 = 0;
        var administrasi = $('#kas_adm_bank').autoNumeric('get');

        $("input[class *= 'kas_nilai']").each(function() {
            sum += +$(this).autoNumeric('get');
        });

        //sum 	=	sum + numeral(administrasi);

	    terhutang_formated	=	numeral(sum);
	    terhutang_formated	=	terhutang_formated.format('0,0');

        //$("#kas_total_keluar").autoNumeric('set', sum);

        $("#kas_total_keluar").text(terhutang_formated);		

	}

	$('#kas_cabang_id').change(function(){

		var cabang = $('#kas_cabang_id option:selected').val();

		formInput 	=	'cabang=' + cabang;

		//request rekening
		$.post('/keuangan/request-akun-rekening',formInput, function(data)
		{					
			$('#kas_akun_kas,.kas_nama_akun').children().remove();
			$('#kas_akun_kas,.kas_nama_akun').append(data);
			$('#kas_akun_kas,.kas_nama_akun').trigger('change');

		});	

		$.post('/keuangan/request-data-karyawan',formInput, function(data)
		{		
			$('#kas_penerima').children().remove();
			$('#kas_penerima').append(data);
			$('#kas_penerima').trigger('change');
		});

	});

});	