<script type="text/javascript">
	$(document).ready(function(){
    	$('.select2').select2();
	});
	function loadpaket(){
		var id_paket = $("#paket").val();
		$.ajax({
        	url : "<?= base_url('admin/pembayaran/cekharga') ?>", 
        	type: "post", //form method
        	data: {
           		id_paket:id_paket,
        	},
        	dataType:"json", //misal kita ingin format datanya brupa json
        	beforeSend:function(){
             //$("#loading").html("Please wait....");
            },
        	success:function(result){
        		if(result.status){
        			$("#biaya").val(result.hasil);
        		}else{
        			alert(result.message);
        		}
           },
           error: function(xhr, Status, err) {
             alert(err);
             //$("Terjadi error : "+Status);
           }
           
         });
	}
</script>