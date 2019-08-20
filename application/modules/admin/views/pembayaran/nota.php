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

<center><h3>NOTA PEMBAYARAN</h3></center>
<p>No. Nota : <?= $data['pembayaran']->pm_id ?></p>
<p>Tanggal Bayar : <?= date('d-m-Y',strtotime($data['pembayaran']->pm_tgl)) ?></p>
<p>Jam Bayar : <?= date('H:i:s',strtotime($data['pembayaran']->pm_tgl)) ?></p>

<table width="100%" style="margin-top: 50px;border: 1px solid black;border-collapse: collapse;" >
	<thead>
		<th style="border: 1px solid black;padding: 5px">No</th>
		<th style="border: 1px solid black;padding: 5px">Id Paket</th>
		<th style="border: 1px solid black;padding: 5px">Id Anggota</th>
		<th style="border: 1px solid black;padding: 5px">Nama Anggota</th>
		<th style="border: 1px solid black;padding: 5px">Kategori</th>
		<th style="border: 1px solid black;padding: 5px">Fasilitas</th>
		<th style="border: 1px solid black;padding: 5px">Harga</th>
	</thead>
	<tbody>
		<tr>
			<td style="border: 1px solid black;padding: 5px">1</td>
			<td style="border: 1px solid black;padding: 5px"><?= $data['pembayaran']->id_paket ?></td>
			<td style="border: 1px solid black;padding: 5px"><?= $data['pembayaran']->id_anggota ?></td>
			<td style="border: 1px solid black;padding: 5px"><?= $data['pembayaran']->a_nama ?></td>
			<td style="border: 1px solid black;padding: 5px"><?= $data['pembayaran']->k_kategori ?></td>
			<td style="border: 1px solid black;padding: 5px"><?= ($data['pembayaran']->a_member=='yes') ? 'Member' : 'Non Member' ?></td>
			<td style="border: 1px solid black;padding: 5px;text-align: right"><?= "Rp".number_format($data['pembayaran']->pm_biaya) ?></td>
		</tr>

		<tr>
			<td style="border: 1px solid black;padding: 5px;text-align: right" colspan="6">TOTAL</td>
			<td style="border: 1px solid black;padding: 5px;text-align: right"><?= "Rp".number_format($data['pembayaran']->pm_biaya) ?></td>
		</tr>
	</tbody>
</table>

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