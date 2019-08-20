<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_anggota');

	}
	public function index()
	{
		$this->title 	= "Anggota";
		$this->content 	= "anggota/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	public function add()
	{
		$this->title 	= "Anggota";
		$this->content 	= "anggota/add";
		$this->assets 	= array('assets_form');

		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Anggota";
		$this->content 	= "anggota/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['anggota'] = $this->m_anggota->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		if (empty($id)){
			$save = $this->m_anggota->insert();
			if ($save['status']){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/anggota');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/anggota/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_anggota->update($id);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/anggota');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/anggota/edit/'.base64_encode($id));

			}
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['a_kode'] = $id;
		$delete = $this->m_anggota->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/anggota');
	}
	public function ajax_list()
	{

		$list = $this->m_anggota->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->a_kode),
				'item' => $item,
			);
			$btnlihat = '<a href="#" onclick="lihat(\''.htmlspecialchars(json_encode($arrParam),ENT_QUOTES).'\')"><i class="fa fa-eye"></i>Lihat</a>';
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
						<li>' . anchor("admin/anggota/edit/".base64_encode($item->a_kode),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/anggota/hapus/".base64_encode($item->a_kode),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->u_email;
			$row[] = $item->a_alamat;
			$row[] = $item->a_hp;
			$row[] = convert_status_activate($item->u_status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_anggota->count_all(),
			"recordsFiltered" => $this->m_anggota->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
