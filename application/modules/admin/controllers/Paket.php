<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paket extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_paket');
		$this->load->model('m_anggota');
		$this->load->model('m_kategori');

	}
	public function index()
	{
		$this->title 	= "Paket";
		$this->content 	= "paket/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	public function add()
	{
		$this->title 	= "Paket";
		$this->content 	= "paket/add";
		$this->assets 	= array('assets_form');
		$anggota = $this->m_anggota->all()->result();
		$data['anggota'][''] = 'Pilih Anggota';
		foreach ($anggota as $row) {
			$data['anggota'][$row->a_kode] = $row->a_kode . ' | '. $row->a_nama; 
		}

		$kategori = $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Kategori';
		foreach ($kategori as $row) {
			$data['kategori'][$row->k_id] = $row->k_kategori; 
		}
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Paket";
		$this->content 	= "paket/edit";
		$this->assets 	= array('assets_form');

		$anggota = $this->m_anggota->all()->result();
		$data['anggota'][''] = 'Pilih Kategori';
		foreach ($anggota as $row) {
			$data['anggota'][$row->a_kode] = $row->a_kode . ' | '. $row->a_nama; 
		}

		$kategori = $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Anggota';
		foreach ($kategori as $row) {
			$data['kategori'][$row->k_id] = $row->k_kategori; 
		}
		$id = base64_decode($id);
		$data['paket'] = $this->m_paket->by_id($id)->row();
		$exp = explode("-", $data['paket']->p_expired);
		$tgl = explode("-", $data['paket']->p_tgl);

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		if (empty($id)){
			$save = $this->m_paket->insert();
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/paket');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/paket/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_paket->update($id);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/paket');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/paket/edit/'.base64_encode($id));

			}
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['p_id'] = $id;
		$delete = $this->m_paket->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/paket');
	}
	public function ajax_list()
	{

		$list = $this->m_paket->get_datatables();
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
						<li>' . anchor("admin/paket/edit/".base64_encode($item->p_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/paket/hapus/".base64_encode($item->p_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
            
            $now = strtotime(date('Y-m-d'));
            $exp = strtotime($item->p_expired);
            if ($now>$exp){
            	$status = 2;
            }else{
            	$status = 1;
            }
            $this->db->where('p_id',$item->p_id)->update('paket',['p_status'=>$status]);
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->p_id;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->k_kategori;
			$row[] = $item->p_tgl;
			$row[] = $item->p_expired;
			// $row[] = "Rp".number_format($item->p_biaya);
			$row[] = convert_status_paket($status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_paket->count_all(),
			"recordsFiltered" => $this->m_paket->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
