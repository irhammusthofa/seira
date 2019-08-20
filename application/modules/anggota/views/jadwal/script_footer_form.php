<script type="text/javascript">
	function loadjadwal(){
		var id_paket = $("#paket").val();
		var tgl = $("#tanggal").val();

	    $('#div-loading').show();
		$.ajax({
			url: "<?= site_url('anggota/jadwal/loadjadwal') ?>",
			type: "post",
			dataType: "json",
			data:{ 
				tgl: tgl,
				paket: id_paket,
			},
			success: function(response) {
	            $('#div-loading').hide();
	            $("#jadwal").html("");
	            $("#jadwal").append('<option value="">Pilih Jadwal</option>');
	            for (var i = 0; i < response.data.length; i++) {
	            	$("#jadwal").append('<option value="' + response.data[i].ji_id +'">' +  response.data[i].ji_jam_mulai + " s.d " + response.data[i].ji_jam_selesai + " - " + response.data[i].i_nama + " - " + response.data[i].r_ruangan + ' ( ' + response.data[i].kuota + ' )</option>');
	            }
	            if (response.status == false){
	            	alert("Jadwal tidak tersedia");
	            	console.log(response);
	            }
	        },
	        error: function(jqXHR, textStatus, errorThrown) {
	            $('#div-loading').hide();
	            alert("Gagal load data")

	        }
		});
	}
	function loadpertemuan(){
		var id_paket = $("#paket").val();

	    $('#div-loading').show();
		$.ajax({
			url: "<?= site_url('anggota/jadwal/loadpertemuan') ?>",
			type: "post",
			dataType: "json",
			data:{ 
				paket: id_paket, 
			},
			success: function(response) {
	            $('#div-loading').hide();
	            $("#pertemuan").html("");
	            
	            if (response.status == false){
	            	alert("Jadwal tidak tersedia atau sudah habis");
	            }else{
	            	$("#tanggal").html("");
	            	$("#tanggal").append('<option value="">Pilih Tanggal</option>');
	            	$("#pertemuan").append('<option value="' + response.ke +'">' +  response.ke + '</option>');
	            	if (response.tanggal.length == 0){
	            		alert('Jadwal tidak tersedia');
	            	}else{
	            		for (var i = 0; i < response.tanggal.length; i++) {
	            			$("#tanggal").append('<option value="'+response.tanggal[i]+'">'+response.tanggal[i]+'</option>');
	            		}
	            	}
	            }
	        },
	        error: function(jqXHR, textStatus, errorThrown) {
	            $('#div-loading').hide();
	            alert("Gagal load data")

	        }
		});
	}
</script>