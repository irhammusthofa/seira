<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nilai extends Admin_Controller
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
		$this->content 	= "nilai/index";
		$this->assets 	= array('assets');


		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Edit";
		$this->content 	= "nilai/edit";
		$this->assets 	= array('assets_form');

		$data['id_paket'] = base64_decode($id);

		$paket = $this->m_paket->by_id($data['id_paket'])->row();
		$data['id_kategori'] = $paket->id_kategori;

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function ajax_list()
	{

		$list = $this->m_nilai->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$absen = $this->db->where('id_paket',$item->p_id)->get('anggota_jadwal')->result();
			$nilai = $this->db->select('sum(nilai) as nilai')->from('anggota_jadwal aj')
				->join('nilai n','n.id_jadwal_anggota=aj.aj_id','inner')
				->where('aj.id_paket',$item->p_id)->get()->row()->nilai;

			$query = "SELECT count(*) as cnt FROM anggota_jadwal aj LEFT JOIN nilai n ON n.id_jadwal_anggota=aj.aj_id WHERE aj.id_paket='".$item->p_id."' AND n.n_id IS NULL";
			$status = $this->db->query($query)->row()->cnt;
			$skor_absen = 0;
			foreach ($absen as $a) {
				$skor_absen += $a->aj_absen;
			}
			$btngroup = anchor('admin/nilai/edit/'.base64_encode($item->p_id),'<i class="fa fa-eye"></i> Lihat',array('class'=>'btn btn-xs btn-success'));
			//$pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$item->ji_id)->count_all_results();
			$nilai_akhir = round(($nilai/8),2);
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->p_id;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->k_kategori;
			$row[] = round(($skor_absen / 8) * 100,2).'%';
			$row[] = $nilai_akhir;
			$row[] = ($status>0) ? label_skin(['type'=>'danger','text'=>'Belum lengkap']) : label_skin(['type'=>'success','text'=>'Sudah lengkap']);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_nilai->count_all(),
			"recordsFiltered" => $this->m_nilai->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
