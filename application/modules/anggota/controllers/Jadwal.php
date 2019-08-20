<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends Anggota_Controller
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
		$this->title 	= "Jadwal ";
		$this->content 	= "jadwal/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}

	public function add()
	{
		$this->title 	= "Jadwal Anggota";
		$this->content 	= "jadwal/add";
		$this->assets 	= array('assets_form');
		
		$paket_anggota = $this->m_paket->by_anggota($this->user->a_kode)->result();
		$data['paket'][''] = 'Pilih Paket';
		foreach ($paket_anggota as $item) {
			if (strtotime($item->p_expired) >= strtotime(date('Y-m-d'))){
				$data['paket'][$item->p_id] = $item->a_nama.' ('.$item->p_id.')';	
			}
		}

		$data['instruktur-jadwal'][''] = 'Pilih Jadwal';	
		$data['tanggal'][''] = 'Pilih Tanggal';
		$data['pertemuan'][''] = 'Pilih Pertemuan';	
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	public function detekpertemuan($paket,$tgl){
		$param['paket'] = $paket;
		$param['tanggal_saatini'] = $tgl;
		$paket_anggota = $this->m_paket->by_id($param['paket'])->row();
		$param['tipe_member'] = $paket_anggota->a_member;
		if ($param['tipe_member']=="yes"){
			$param['tipe_member'] = "member";
		}else{
			$param['tipe_member'] = "non";
		}

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
		$paket_anggota = $this->m_paket->by_id($param['paket'])->row();
		$param['tipe_member'] = $paket_anggota->a_member;
		if ($param['tipe_member']=="yes"){
			$param['tipe_member'] = "member";
		}else{
			$param['tipe_member'] = "non";
		}

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
		$paket_anggota = $this->m_paket->by_id($param['paket'])->row();
		$param['tipe_member'] = $paket_anggota->a_member;
		if ($param['tipe_member']=="yes"){
			$param['tipe_member'] = "member";
		}else{
			$param['tipe_member'] = "non";
		}

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
	public function edit($id)
	{
		$this->title 	= "Jadwal Anggota";
		$this->content 	= "jadwal/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['anggota-jadwal'] = $this->m_anggota_jadwal->by_id($id)->row();
		
		$paket_anggota = $this->m_paket->all()->result();
		$data['paket'][''] = 'Pilih Paket';
		foreach ($paket_anggota as $item) {
			if (strtotime($item->p_expired) >= strtotime(date('Y-m-d'))){
				$data['paket'][$item->p_id] = $item->a_nama.' ('.$item->p_id.')';
			}
		}

		$jadwal_instruktur = $this->m_instruktur_jadwal->by_tgl($data['anggota-jadwal']->ji_tgl)->result();
		$data['instruktur-jadwal'][''] = 'Pilih Jadwal';		
		foreach ($jadwal_instruktur as $item) {
			$pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$item->ji_id)->count_all_results();
			$data['instruktur-jadwal'][$item->ji_id] = $item->ji_jam_mulai.' s.d '.$item->ji_jam_selesai.' - '.$item->i_nama.' - '.$item->r_ruangan.' ('.$pendaftar.' dari '.$item->ji_kuota.')';
		}
		$data['tanggal'][''] = 'Pilih Tanggal';
		$pertemuan = $this->detekpertemuan($data['anggota-jadwal']->id_paket,$data['anggota-jadwal']->ji_tgl);
		if ($pertemuan['ke']>0){
			$data['anggota-jadwal']->pertemuan_ke = $pertemuan['ke'];
			for($i=0;$i<count($pertemuan['tanggal']);$i++){
				$data['tanggal'][$pertemuan['tanggal'][$i]] = $pertemuan['tanggal'][$i];
			}
		}
		$param = array(
			'data' => $data,
		);

		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['id_jadwal_instruktur'] 		= $this->input->post('jadwal',TRUE);
		
		if (empty($id)){

			$data['id_paket'] 					= $this->input->post('paket',TRUE);
			$ketersediaan = $this->m_anggota_jadwal->ketersediaan($data,$id);
			if ($ketersediaan['status'] == false){
				fs_create_alert(['type'=>'warning','message'=>$ketersediaan['message']]);
				redirect('anggota/jadwal/add');
			}else{
				$save = $this->m_anggota_jadwal->insert($data);
				if ($save){
					fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
					redirect('anggota/jadwal/');
				}else{
					fs_create_alert(['type'=>'danger','message'=>$save['message']]);
					redirect('anggota/jadwal/add/');

				}
			}
			
		}else{

			$id = base64_decode($id);
			$ketersediaan = $this->m_anggota_jadwal->ketersediaan($data,$id);

			$anggota_jadwal = $this->m_anggota_jadwal->by_id($id)->row();
			if ($ketersediaan['status'] == false && ($data['id_jadwal_instruktur'] != $anggota_jadwal->id_jadwal_instruktur)){
				fs_create_alert(['type'=>'warning','message'=>$ketersediaan['message']]);
				redirect('anggota/jadwal/edit/'.base64_encode($id));
			}else{
				$save = $this->m_anggota_jadwal->update($id,$data);
				if ($save){
					fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
					redirect('anggota/jadwal/');
				}else{
					fs_create_alert(['type'=>'danger','message'=>$save['message']]);
					redirect('anggota/jadwal/edit/'.base64_encode($id));

				}
			}
			
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['aj_id'] = $id;
		$delete = $this->m_anggota_jadwal->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('anggota/jadwal/');
	}
	public function ajax_list()
	{

		$list = $this->m_anggota_jadwal->get_datatables();
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
						<li>' . anchor("anggota/jadwal/edit/".base64_encode($item->aj_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("anggota/jadwal/hapus/".base64_encode($item->aj_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
            $awal = strtotime(date('Y-m-d H:i:s', strtotime( '+1 day' )));
            $akhir = strtotime($item->ji_tgl.' '.$item->ji_jam_mulai);
            if ($awal > $akhir){
            	$btngroup = $btngroup_disable;
            }
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->i_nama;
			$row[] = $item->k_kategori;
			$row[] = $item->ji_tgl;
			$row[] = $item->ji_jam_mulai;
			$row[] = $item->ji_jam_selesai;
			$row[] = $item->r_ruangan;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_anggota_jadwal->count_all(),
			"recordsFiltered" => $this->m_anggota_jadwal->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
