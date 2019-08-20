<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends Instruktur_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_anggota_jadwal');
		$this->load->model('m_paket');
		$this->load->model('m_instruktur_jadwal');
		//$this->load->model('m_instruktur_latihan');
		//$this->load->model('m_instruktur');
		//$this->load->model('m_ruangan');
		//$this->load->model('m_kategori');

	}
	public function index()
	{
		$this->title 	= "Jadwal";
		$this->content 	= "jadwal/index";
		$this->assets 	= array('assets');

		$data['jadwal-hari-ini'] = $this->m_instruktur_jadwal->hari_ini()->result();

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}

	
	public function detekpertemuan($paket,$tgl){
		$param['paket'] = $paket;
		$param['tanggal_saatini'] = $tgl;

		$pertemuan = $this->m_anggota_jadwal->detekpertemuan($param);
		if ($pertemuan['ke']>0){
			$tanggal 	= [];
			for($i=0;$i<7;$i++){
				$param['tgl'] = date('Y-m-d',strtotime($pertemuan['tgl_awal'] . ' +'.$i.' day'));
				$jadwal = $this->m_anggota_jadwal->loadjadwal($param)->result();
				if (count($jadwal)>0){
					$tanggal[] = $param['tgl'];
				}
			}
			$response = array(
				'status' 	=> true,
				'ke' 		=> $pertemuan['ke'],
				'tanggal'	=> $tanggal,
			);
		}else{
			$response = array(
				'status' => false,
				'ke' => $pertemuan['ke'],
			);
		}
		return $response;
	}
	public function loadpertemuan(){
		$param['paket'] = $this->input->post('paket',TRUE);

		$pertemuan = $this->m_anggota_jadwal->loadpertemuan($param);
		if ($pertemuan['ke']>0){
			$tanggal 	= [];
			for($i=0;$i<7;$i++){
				$param['tgl'] = date('Y-m-d',strtotime($pertemuan['tgl_awal'] . ' +'.$i.' day'));
				$jadwal = $this->m_anggota_jadwal->loadjadwal($param)->result();
				if (count($jadwal)>0){
					$tanggal[] = $param['tgl'];
				}
			}
			$response = array(
				'status' 	=> true,
				'ke' 		=> $pertemuan['ke'],
				'tanggal'	=> $tanggal,
			);
		}else{
			$response = array(
				'status' => false,
				'ke' => $pertemuan['ke'],
			);
		}
		echo json_encode($response);
		
	}
	public function loadjadwal(){
		$param['paket'] = $this->input->post('paket',TRUE);
		$param['tgl'] = $this->input->post('tgl',TRUE);

		$jadwal = $this->m_anggota_jadwal->loadjadwal($param)->result();
		foreach ($jadwal as $item) {
			$pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$item->ji_id)->count_all_results();

			$item->ji_jam_mulai = date('H:i',strtotime($item->ji_jam_mulai));
			$item->ji_jam_selesai = date('H:i',strtotime($item->ji_jam_selesai));
			$item->kuota = $pendaftar.' dari '.$item->ji_kuota;
		}
		echo json_encode(
			array(
				'status' => count($jadwal),
				'message' => $param,
				'data' => $jadwal,
			)
		);
	}
	
	public function ajax_list()
	{

		$list = $this->m_instruktur_jadwal->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$item->ji_id)->count_all_results();
			$no++;
			$row = array();
			$row[] = $item->ji_tgl;
			$row[] = $item->ji_jam_mulai;
			$row[] = $item->ji_jam_selesai;
			$row[] = $item->k_kategori;
			$row[] = $item->r_ruangan;
			$row[] = $pendaftar.' dari '.$item->ji_kuota;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_instruktur_jadwal->count_all(),
			"recordsFiltered" => $this->m_instruktur_jadwal->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
