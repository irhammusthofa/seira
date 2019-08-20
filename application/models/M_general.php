<?php
/**
* M_general
*/
class M_general extends CI_Model
{
	public function generate_id_anggota(){
        // nomor/CIP-PTG/Tahun contoh (12/CIP-PTG/2019)
        $tabel = 'anggota';
        $kolom = 'a_kode';
        $lebar = 3;
        $awalan = "A/SEIRA/".date('Y')."/";
        if(empty($awalan)){
            $query="select $kolom from $tabel order by $kolom desc limit 1";
        }else{
            $query="select $kolom from $tabel where $kolom like '%$awalan%' order by $kolom desc limit 1";
        }
        $hasil          = $this->db->query($query)->row();
        $jumlahrecord   = count($hasil);
        if($jumlahrecord == 0)
            $nomor=1;
        else
        {
            $row=$hasil;
            $nomor=intval(substr($row->$kolom,strlen($awalan)))+1;
        }
        if($lebar>0){
            $origin = str_pad($nomor,$lebar,"0",STR_PAD_LEFT)."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        }else{
            $origin = $nomor."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.$nomor;
        }
        return array('temp'=>$angka,'origin'=>$origin);
    }
    public function generate_id_instruktur(){
        // nomor/CIP-PTG/Tahun contoh (12/CIP-PTG/2019)
        $tabel = 'instruktur';
        $kolom = 'i_kode';
        $lebar = 3;
        $awalan = "I/SEIRA/".date('Y')."/";
        if(empty($awalan)){
            $query="select $kolom from $tabel order by $kolom desc limit 1";
        }else{
            $query="select $kolom from $tabel where $kolom like '%$awalan%' order by $kolom desc limit 1";
        }
        $hasil          = $this->db->query($query)->row();
        $jumlahrecord   = count($hasil);
        if($jumlahrecord == 0)
            $nomor=1;
        else
        {
            $row=$hasil;
            $nomor=intval(substr($row->$kolom,strlen($awalan)))+1;
        }
        if($lebar>0){
            $origin = str_pad($nomor,$lebar,"0",STR_PAD_LEFT)."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        }else{
            $origin = $nomor."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.$nomor;
        }
        return array('temp'=>$angka,'origin'=>$origin);
    }
    public function generate_id_paket(){
        // nomor/CIP-PTG/Tahun contoh (12/CIP-PTG/2019)
        $tabel = 'paket';
        $kolom = 'p_id';
        $lebar = 3;
        $awalan = "PKT/SEIRA/".date('Y')."/";
        if(empty($awalan)){
            $query="select $kolom from $tabel order by $kolom desc limit 1";
        }else{
            $query="select $kolom from $tabel where $kolom like '%$awalan%' order by $kolom desc limit 1";
        }
        $hasil          = $this->db->query($query)->row();
        $jumlahrecord   = count($hasil);
        if($jumlahrecord == 0)
            $nomor=1;
        else
        {
            $row=$hasil;
            $nomor=intval(substr($row->$kolom,strlen($awalan)))+1;
        }
        if($lebar>0){
            $origin = str_pad($nomor,$lebar,"0",STR_PAD_LEFT)."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        }else{
            $origin = $nomor."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.$nomor;
        }
        return array('temp'=>$angka,'origin'=>$origin);
    }
    public function generate_id_pembayaran(){
        // nomor/CIP-PTG/Tahun contoh (12/CIP-PTG/2019)
        $tabel = 'pembayaran';
        $kolom = 'pm_id';
        $lebar = 3;
        $awalan = "PM/SEIRA/".date('Y')."/";
        if(empty($awalan)){
            $query="select $kolom from $tabel order by $kolom desc limit 1";
        }else{
            $query="select $kolom from $tabel where $kolom like '%$awalan%' order by $kolom desc limit 1";
        }
        $hasil          = $this->db->query($query)->row();
        $jumlahrecord   = count($hasil);
        if($jumlahrecord == 0)
            $nomor=1;
        else
        {
            $row=$hasil;
            $nomor=intval(substr($row->$kolom,strlen($awalan)))+1;
        }
        if($lebar>0){
            $origin = str_pad($nomor,$lebar,"0",STR_PAD_LEFT)."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        }else{
            $origin = $nomor."/".substr($awalan,0,strlen($awalan)-1);
            $angka = $awalan.$nomor;
        }
        return array('temp'=>$angka,'origin'=>$origin);
    }
}