<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paket extends Anggota_Controller
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
			//$row[] = $btngroup;
			$row[] = $item->p_id;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->k_kategori;
			$row[] = $item->p_tgl;
			$row[] = $item->p_expired;
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
