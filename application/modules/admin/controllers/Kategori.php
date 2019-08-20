<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_kategori');

	}
	public function index()
	{
		$this->title 	= "Kategori";
		$this->content 	= "kategori/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Kategori";
		$this->content 	= "kategori/add";
		$this->assets 	= array('assets_form');

		$param = array(
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Kategori";
		$this->content 	= "kategori/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['kategori'] = $this->m_kategori->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['k_kategori'] = $this->input->post('kategori',TRUE);
		$data['k_h_member1'] = $this->input->post('member1',TRUE);
		$data['k_h_member2'] = $this->input->post('member2',TRUE);
		$data['k_h_nmember'] = $this->input->post('nmember',TRUE);
		if (empty($id)){
			$save = $this->m_kategori->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/kategori');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/kategori/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_kategori->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/kategori');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/kategori/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['k_id'] = $id;
		$delete = $this->m_kategori->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/kategori');
	}
	public function ajax_list()
	{

		$list = $this->m_kategori->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->k_id),
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
						<li>' . anchor("admin/kategori/edit/".base64_encode($item->k_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/kategori/hapus/".base64_encode($item->k_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->k_kategori;
			$row[] = number_format($item->k_h_member1);
			$row[] = number_format($item->k_h_member2);
			$row[] = number_format($item->k_h_nmember);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kategori->count_all(),
			"recordsFiltered" => $this->m_kategori->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
