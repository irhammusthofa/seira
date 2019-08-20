<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwalinstruktur extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_instruktur_jadwal');
		$this->load->model('m_instruktur_latihan');
		$this->load->model('m_instruktur');
		$this->load->model('m_ruangan');
		//$this->load->model('m_kategori');

	}
	public function index()
	{
		$this->title 	= "Jadwal Instruktur";
		$this->content 	= "instrukturjadwal/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}

	public function add()
	{
		$this->title 	= "Jadwal Instruktur";
		$this->content 	= "instrukturjadwal/add";
		$this->assets 	= array('assets_form');
		
		$instruktur_latihan = $this->m_instruktur_latihan->all()->result();
		$data['instruktur-latihan'][''] = 'Pilih Instruktur';
		foreach ($instruktur_latihan as $item) {
			$data['instruktur-latihan'][$item->il_id] = $item->i_kode.' | '.$item->i_nama.' ('.$item->k_kategori.')';
		}

		$ruangan = $this->m_ruangan->all()->result();
		$data['ruangan'][''] = 'Pilih Ruangan';
		foreach ($ruangan as $item) {
			$data['ruangan'][$item->r_id] = $item->r_ruangan;
		}

		$data['tipe'][''] = 'Pilih Tipe Member';
		$data['tipe']['member'] = 'Member';
		$data['tipe']['non'] = 'Non Member';
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Jadwal Instruktur";
		$this->content 	= "instrukturjadwal/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['instruktur-jadwal'] = $this->m_instruktur_jadwal->by_id($id)->row();

		$instruktur_latihan = $this->m_instruktur_latihan->all()->result();
		$data['instruktur-latihan'][''] = 'Pilih Instruktur';
		foreach ($instruktur_latihan as $item) {
			$data['instruktur-latihan'][$item->il_id] = $item->i_kode.' | '.$item->i_nama.' ('.$item->k_kategori.')';
		}

		$ruangan = $this->m_ruangan->all()->result();
		$data['ruangan'][''] = 'Pilih Ruangan';
		foreach ($ruangan as $item) {
			$data['ruangan'][$item->r_id] = $item->r_ruangan;
		}
		
		$data['tipe'][''] = 'Pilih Tipe Member';
		$data['tipe']['member'] = 'Member';
		$data['tipe']['non'] = 'Non Member';
		$param = array(
			'data' => $data,
		);

		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['ji_tgl'] 					= $this->input->post('tgl',TRUE);
		$data['ji_jam_mulai'] 				= $this->input->post('mulai',TRUE);
		$data['ji_jam_selesai'] 			= $this->input->post('selesai',TRUE);
		$data['id_instruktur_latihan'] 		= $this->input->post('instruktur',TRUE);
		$data['id_ruangan'] 				= $this->input->post('ruangan',TRUE);
		$data['ji_kuota'] 					= $this->input->post('kuota',TRUE);
		$data['ji_tipemember'] 				= $this->input->post('tipe',TRUE);

		$mulai = strtotime($data['ji_jam_mulai']);
		$selesai = strtotime($data['ji_jam_selesai']);

		
		if (empty($id)){
			if ($selesai<$mulai){
				fs_create_alert(['type'=>'warning','message'=>'Jam Mulai harus lebih kecil dari Jam Selesai']);
				redirect('admin/jadwal/instruktur/add');
			}else if ($this->m_instruktur_jadwal->ketersediaan($data) == false){
				fs_create_alert(['type'=>'warning','message'=>'Jadwal tidak tersedia / sudah ada']);
				redirect('admin/jadwal/instruktur/add');
			}else{
				$save = $this->m_instruktur_jadwal->insert($data);
				if ($save){
					fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
					redirect('admin/jadwal/instruktur/');
				}else{
					fs_create_alert(['type'=>'danger','message'=>$save['message']]);
					redirect('admin/jadwal/instruktur/add/');

				}
			}
			
		}else{

			$id = base64_decode($id);
			if ($selesai<$mulai){
				fs_create_alert(['type'=>'warning','message'=>'Jam Mulai harus lebih kecil dari Jam Selesai']);
				redirect('admin/jadwal/instruktur/edit/'.base64_encode($id));
			}else if ($this->m_instruktur_jadwal->ketersediaan($data,$id) == false){
				fs_create_alert(['type'=>'warning','message'=>'Jadwal tidak tersedia / sudah ada']);
				redirect('admin/jadwal/instruktur/edit/'.base64_encode($id));
			}else{
				$save = $this->m_instruktur_jadwal->update($id,$data);
				if ($save){
					fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
					redirect('admin/jadwal/instruktur/');
				}else{
					fs_create_alert(['type'=>'danger','message'=>$save['message']]);
					redirect('admin/jadwal/instruktur/edit/'.base64_encode($id));

				}
			}
			
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['ji_id'] = $id;
		$delete = $this->m_instruktur_jadwal->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/jadwal/instruktur/');
	}
	public function ajax_list()
	{

		$list = $this->m_instruktur_jadwal->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$btngroup_disable = '<div class="input-group">
			<button type="button" class="btn btn-xs btn-default pull-right dropdown-toggle" data-toggle="dropdown" disabled>
				<span> Action
				</span>
				<i class="fa fa-caret-down"></i>
			</button>
		</div>';
			$btngroup = '<div class="input-group">
					<button type="button" class="btn btn-xs btn-default pull-right dropdown-toggle" data-toggle="dropdown">
						<span> Action
						</span>
						<i class="fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu">
						<li>' . anchor("admin/jadwal/instruktur/edit/".base64_encode($item->ji_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/jadwal/instruktur/hapus/".base64_encode($item->ji_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
            $pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$item->ji_id)->count_all_results();
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->i_kode;
			$row[] = $item->i_nama;
			$row[] = $item->k_kategori;
			$row[] = $item->ji_tgl;
			$row[] = $item->ji_jam_mulai;
			$row[] = $item->ji_jam_selesai;
			$row[] = $item->r_ruangan;
			$row[] = $pendaftar.' dari '.$item->ji_kuota.' orang';
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
