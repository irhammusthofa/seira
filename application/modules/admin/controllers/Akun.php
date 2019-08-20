<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_akun');

	}
	public function index()
	{
		$this->title 	= "Akun";
		$this->content 	= "akun/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Akun";
		$this->content 	= "akun/add";
		$this->assets 	= array('assets_form');

		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Akun";
		$this->content 	= "akun/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['akun'] = $this->m_akun->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['u_name'] = $this->input->post('username',TRUE);
		$data['u_email'] = $this->input->post('email',TRUE);
		$data['u_status'] = $this->input->post('status',TRUE);
		$data['u_role'] = 'admin';
		if (empty($id)){

			$data['u_password'] = sha1($this->input->post('password',TRUE));
			$save = $this->m_akun->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/akun');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/akun/add');

			}
		}else{
			$id = base64_decode($id);
			$pass = $this->input->post('password',TRUE);
			if (!empty($pass)){
				$data['u_password'] = sha1($pass);
			}
			$save = $this->m_akun->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/akun');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/akun/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['u_id'] = $id;
		$delete = $this->m_akun->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/akun');
	}
	public function ajax_list()
	{

		$list = $this->m_akun->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->u_id),
				'item' => $item,
			);
			$hapus = '<li>' . anchor("admin/akun/hapus/".base64_encode($item->u_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>';
			if ($item->u_role!='admin'){
				$hapus = '';
			}
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
						<li>' . anchor("admin/akun/edit/".base64_encode($item->u_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						'.$hapus.'
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->u_name;
			$row[] = $item->u_email;
			$row[] = $item->u_role;
			$row[] = convert_status_account($item->u_status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_akun->count_all(),
			"recordsFiltered" => $this->m_akun->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
