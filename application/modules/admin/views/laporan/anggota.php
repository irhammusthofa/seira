<!DOCTYPE html>
<html>
<head>
	<title>Laporan Anggota</title>
</head>
<body>
	<table  width="100%">
		<tr>
			<td width="230px"><img src="<?= base_url('assets/img/logo1.png') ?>"></td>
			<td><h2>SEIRA STUDIO</h2><p>Jl. Buah Batu No. 27, Burangrang, Lengkong</p><p style="margin-top: -10px">Kota Bandung, Jawa Barat 40262</p><p style="margin-top: -10px">No. Telp : (022) 7305129</p></td>
		</tr>
	</table>
<hr>

<h3>Laporan Anggota Per Periode</h3>
<p>Periode Tanggal : <?= @$_GET['tgl1'] ?> s.d <?= @$_GET['tgl2'] ?></p>
<p>Tanggal Cetak : <?= date('Y-m-d') ?></p>

<?php 
	$kategori = '';
	$no = 0;
	$total = 0;
	$baris = 0;
	foreach ($data as $item){
		if ($item->k_id != $kategori){
			$no = 0;
			$kategori = $item->k_id;
			if (!empty($kategori)){
				echo '<tr>';
				echo '<th style="border: 1px solid black;padding:5px" colspan="3">TOTAL</th>';
				echo '<th style="border: 1px solid black;padding:5px;text-align:right">'.number_format($total).'</th>';
				echo '</tr>';
				echo '</table><br>';
			}

			$total = 0;
			
			echo '<h4>Kategori : '.$item->k_kategori.'</h4>';
			echo '<table style="border: 1px solid black;border-collapse: collapse;padding:10px" width="100%">';
			echo '<tr>';
			echo '<th style="border: 1px solid black;padding:10px">No</th>';
			echo '<th style="border: 1px solid black;padding:10px">Kode Anggota</th>';
			echo '<th style="border: 1px solid black;padding:10px">Nama Anggota</th>';
			echo '<th style="border: 1px solid black;padding:10px">Biaya</th>';
			echo '</tr>';

		}
		$no++;
		echo '<tr>';
		echo '<td style="border: 1px solid black;padding:5px">'.$no.'</td>';
		echo '<td style="border: 1px solid black;padding:5px">'.$item->a_kode.'</td>';
		echo '<td style="border: 1px solid black;padding:5px">'.$item->a_nama.'</td>';
		echo '<td style="border: 1px solid black;padding:5px;text-align:right">'.number_format($item->pm_biaya).'</td>';
		echo '</tr>';
		$total += $item->pm_biaya;
		$baris++;
		if ($baris == count($data)){
			echo '<tr>';
			echo '<th style="border: 1px solid black;padding:5px" colspan="3">TOTAL</th>';
			echo '<th style="border: 1px solid black;padding:5px;text-align:right">'.number_format($total).'</th>';
			echo '</tr>';
		}
	}

		

?>

<table width="100%" style="margin-top: 50px">
	<tr>
		<td width="50%"></td>
		<td width="50%"><center>Wangi Julianda<br><br><br><br>Penanggung Jawab</center></td>
	</tr>
</table>
<script type="text/javascript">
	function wprint(){
        window.print();
    }
    setTimeout(function() {
        wprint();   
    }, 1000);
    
</script>
</body>
</html>