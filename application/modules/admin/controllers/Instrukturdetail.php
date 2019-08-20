<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instrukturdetail extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_instruktur_latihan');
		$this->load->model('m_instruktur');
		$this->load->model('m_kategori');

	}
	public function index($id_instruktur)
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instrukturdetail/index";
		$this->assets 	= array('assets');

		$id_instruktur 	= base64_decode($id_instruktur);
		$data['instruktur'] 		= $this->m_instruktur->by_id($id_instruktur)->row();

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}

	public function add($id)
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instrukturdetail/add";
		$this->assets 	= array('assets_form');
		$id = base64_decode($id);

		$kategori = $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Kategori';
		foreach ($kategori as $item) {
			$data['kategori'][$item->k_id] = $item->k_kategori;
		}

		$data['instruktur'] = $this->m_instruktur->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id,$id_latihan)
	{
		$this->title 	= "Instruktur";
		$this->content 	= "instrukturdetail/edit";
		$this->assets 	= array('assets_form');

		$kategori = $this->m_kategori->all()->result();
		$data['kategori'][''] = 'Pilih Kategori';
		foreach ($kategori as $item) {
			$data['kategori'][$item->k_id] = $item->k_kategori;
		}

		$id = base64_decode($id);
		$id_latihan = base64_decode($id_latihan);
		$data['instruktur'] 		= $this->m_instruktur->by_id($id)->row();
		$data['instruktur-detail'] = $this->m_instruktur_latihan->by_id($id_latihan)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id_instruktur,$id="")
	{
		$data['id_instruktur'] 	= base64_decode($id_instruktur);
		$data['id_kategori'] 	= $this->input->post('kategori',TRUE);
		$data['status'] 		= $this->input->post('status',TRUE);

		if (empty($id)){
			$save = $this->m_instruktur_latihan->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/instruktur/kategori/'.$id_instruktur);
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/instruktur/kategori/add/'.$id_instruktur);

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_instruktur_latihan->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/instruktur/kategori/'.$id_instruktur);
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/instruktur/edit/kategori/'.$id_instruktur.'/'.base64_encode($id));

			}
		}
	}
	public function hapus($id_instruktur,$id)
	{

		$id = base64_decode($id);
		$data['il_id'] = $id;
		$delete = $this->m_instruktur_latihan->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/instruktur/kategori/'.$id_instruktur);
	}
	public function ajax_list()
	{
		$param['id_instruktur'] = $this->input->post('id_instruktur',TRUE);
		$list = $this->m_instruktur_latihan->get_datatables($param);
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
						<li>' . anchor("admin/instruktur/kategori/edit/".base64_encode($item->id_instruktur).'/'.base64_encode($item->il_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/instruktur/kategori/hapus/".base64_encode($item->id_instruktur).'/'.base64_encode($item->il_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->k_kategori;
			$row[] = convert_status_account($item->status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_instruktur_latihan->count_all($param),
			"recordsFiltered" => $this->m_instruktur_latihan->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
