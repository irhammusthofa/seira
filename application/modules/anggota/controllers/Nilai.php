<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nilai extends Anggota_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_nilai');
		$this->load->model('m_kriteria');
		$this->load->model('m_paket');
		$this->load->model('m_anggota_jadwal');

	}
	public function index()
	{
		$this->title 	= "Nilai";
		$this->content 	= "nilai/edit";
		$this->assets 	= array('assets_form');

		$paket_anggota = $this->m_paket->by_anggota($this->user->a_kode)->result();
		$paket_anggota = $this->m_paket->by_anggota($this->user->a_kode)->result();

		foreach ($paket_anggota as $item) {
			if (strtotime($item->p_expired) > strtotime(date('Y-m-d'))){
				$data['paket'] = $item;
				break;
			}
		}
		
		$paket = $this->m_paket->by_id($data['paket']->p_id)->row();
		$data['id_kategori'] = $paket->id_kategori;
		$data['id_paket'] = $paket->p_id;
        $data['sertifikat'] = $this->db->where('id_paket',$paket->p_id)->get('sertifikat')->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	public function grafik(){
		$paket_anggota = $this->m_paket->by_anggota($this->user->a_kode)->result();
		$paket_anggota = $this->m_paket->by_anggota($this->user->a_kode)->result();

		foreach ($paket_anggota as $item) {
			if (strtotime($item->p_expired) > strtotime(date('Y-m-d'))){
				$data['paket'] = $item;
				break;
			}
		}
		
		$paket = $this->m_paket->by_id($data['paket']->p_id)->row();
		$data['id_kategori'] = $paket->id_kategori;
		$data['id_paket'] = $paket->p_id;
		$kriteria = $this->m_kriteria->by_kategori($data['id_kategori'])->result();
        $skor[1] = 0;
        $skor[2] = 0;
        $skor[3] = 0;
        $skor[4] = 0;
        $skor[5] = 0;
        $skor[6] = 0;
        $skor[7] = 0;
        $skor[8] = 0;

        foreach ($kriteria as $item) {
            $srow[1] = 0;
            $srow[2] = 0;
            $srow[3] = 0;
            $srow[4] = 0;
            $srow[5] = 0;
            $srow[6] = 0;
            $srow[7] = 0;
            $srow[8] = 0;
            for($i=0;$i<=9;$i++){
                if ($i==0){
                }else if($i==9){
                }else{

                    $param['paket'] = $data['id_paket'];
                    //$param['id_instruktur'] = $this->user->i_kode;
                    $param['pertemuan'] = $i;
                    $param['id_kriteria'] = $item->kr_id;
                    $nilai = $this->m_nilai->by_pertemuan_kriteria($param);
                    
                    if ($nilai['status'] == false){
                    }else{
                        $skor[$i] += $nilai['value'];
                        $srow[$i] += $nilai['value'];
                    }
                    
                }
            }
        }
        $pertemuan = [];
        $nilai = [];
        for ($i=1; $i < 9 ; $i++) { 
        	$pertemuan[] = "ke-".$i;
        	$nilai[] = $skor[$i];
        }
        $arr = array(
            'status' => TRUE,
            'message' => 'OK',
            'pertemuan' => $pertemuan,
            'nilai' => $nilai,
        );
        echo json_encode($arr);
    }
}
