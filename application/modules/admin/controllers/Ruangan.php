<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ruangan extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_ruangan');

	}
	public function index()
	{
		$this->title 	= "Ruangan";
		$this->content 	= "ruangan/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Ruangan";
		$this->content 	= "ruangan/add";
		$this->assets 	= array('assets_form');

		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Ruangan";
		$this->content 	= "ruangan/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['ruangan'] = $this->m_ruangan->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['r_ruangan'] = $this->input->post('ruangan',TRUE);
		if (empty($id)){
			$save = $this->m_ruangan->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/ruangan');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/ruangan/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_ruangan->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/ruangan');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/ruangan/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['r_id'] = $id;
		$delete = $this->m_ruangan->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/ruangan');
	}
	public function ajax_list()
	{

		$list = $this->m_ruangan->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->r_id),
				'item' => $item,
			);
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
						<li>' . anchor("admin/ruangan/edit/".base64_encode($item->r_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/ruangan/hapus/".base64_encode($item->r_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->r_ruangan;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_ruangan->count_all(),
			"recordsFiltered" => $this->m_ruangan->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
