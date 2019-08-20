<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_kriteria');
		$this->load->model('m_kategori');

	}
	public function index()
	{
		$this->title 	= "Kriteria";
		$this->content 	= "kriteria/index";
		$this->assets 	= array('assets');

		$kategori 		= $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Semua Kategori';
		foreach ($kategori as $item) {
			$data['kategori'][$item->k_id] = $item->k_kategori;
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Kriteria";
		$this->content 	= "kriteria/add";
		$this->assets 	= array('assets_form');

		$kategori 		= $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Kategori';
		foreach ($kategori as $item) {
			$data['kategori'][$item->k_id] = $item->k_kategori;
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Kriteria";
		$this->content 	= "kriteria/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['kriteria'] = $this->m_kriteria->by_id($id)->row();

		$kategori 		= $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Kategori';
		foreach ($kategori as $item) {
			$data['kategori'][$item->k_id] = $item->k_kategori;
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['kr_kriteria'] = $this->input->post('kriteria',TRUE);
		$data['id_kategori'] = $this->input->post('kategori',TRUE);
		if (empty($id)){
			$save = $this->m_kriteria->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/kriteria');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/kriteria/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_kriteria->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/kriteria');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/kriteria/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['kr_id'] = $id;
		$delete = $this->m_kriteria->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/kriteria');
	}
	public function ajax_list()
	{
		$param['id_kategori'] = $this->input->post('id_kategori',TRUE);
		
		$list = $this->m_kriteria->get_datatables($param);
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
						<li>' . anchor("admin/kriteria/edit/".base64_encode($item->kr_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/kriteria/hapus/".base64_encode($item->kr_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->kr_kriteria;
			$row[] = $item->k_kategori;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kriteria->count_all($param),
			"recordsFiltered" => $this->m_kriteria->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
