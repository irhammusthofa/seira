<script type="text/javascript">
	$(document).ready(function () {
	const picker = document.getElementById('date1');
	picker.addEventListener('input', function(e){
	  var day = new Date(this.value).getUTCDay();
	  if([0].includes(day)){
	    e.preventDefault();
	    this.value = '';
	    alert('Weekends not allowed');
	  }
	});

});
</script>