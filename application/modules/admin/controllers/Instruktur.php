<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instruktur extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_instruktur');

	}
	public function index()
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instruktur/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}

	public function add()
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instruktur/add";
		$this->assets 	= array('assets_form');

		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instruktur/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['instruktur'] = $this->m_instruktur->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		if (empty($id)){
			$save = $this->m_instruktur->insert();
			if ($save['status']){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/instruktur');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/instruktur/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_instruktur->update($id);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/instruktur');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/instruktur/edit/'.base64_encode($id));

			}
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['i_kode'] = $id;
		$delete = $this->m_instruktur->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/instruktur');
	}
	public function ajax_list()
	{

		$list = $this->m_instruktur->get_datatables();
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
						<li>' . anchor("admin/instruktur/edit/".base64_encode($item->i_kode),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/instruktur/hapus/".base64_encode($item->i_kode),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
						<li>' . anchor("admin/instruktur/kategori/".base64_encode($item->i_kode),"<i class=\"fa fa-file-text\"></i>Kategori Latihan") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->i_kode;
			$row[] = $item->i_nama;
			$row[] = $item->u_email;
			$row[] = $item->i_alamat;
			$row[] = $item->i_hp;
			$row[] = convert_status_activate($item->u_status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_instruktur->count_all(),
			"recordsFiltered" => $this->m_instruktur->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
