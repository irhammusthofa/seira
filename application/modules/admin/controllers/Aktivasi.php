<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aktivasi extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_aktivasi');

	}
	public function index()
	{
		$this->title 	= "Aktivasi Akun";
		$this->content 	= "aktivasi/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
		$this->load_view('modal_aktivasi');
		$this->load_view('modal_tolak');
	}
	
	public function proses($id)
	{
		$id = base64_decode($id);
		$save = $this->m_aktivasi->aktivasi($id);
		if ($save){
			fs_create_alert(['type' => 'success', 'message' => 'Akun berhasil diaktivasi, username dan password sudah dikirimkan melalui email.']);	
			redirect('admin/aktivasi');
		}else{
			fs_create_alert(['type' => 'danger', 'message' => 'Akun gagal diaktivasi, silahkan coba lagi.']);	
			redirect('admin/aktivasi');
		}
	}
	public function tolak($id)
	{
		$id = base64_decode($id);
		$save = $this->m_aktivasi->tolak($id);
		if ($save){
			fs_create_alert(['type' => 'success', 'message' => 'Akun berhasil ditolak, alasan penolakan dikirimkan melalui email.']);	
			redirect('admin/aktivasi');
		}else{
			fs_create_alert(['type' => 'danger', 'message' => 'Akun gagal diaktivasi, silahkan coba lagi.']);	
			redirect('admin/aktivasi');
		}
	}
	public function ajax_list()
	{

		$list = $this->m_aktivasi->get_datatables();
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id' => $item->a_kode,
				'id_encode' => base64_encode($item->a_kode),
				'nama' => $item->a_nama,
			);
			$btnaktivasi = '<a href="#" onclick="aktivasi(\''.htmlspecialchars(json_encode($arrParam),ENT_QUOTES).'\')"><i class="fa fa-unlock"></i>Aktivasi</a>';
			$btntolak = '<a href="#" onclick="tolak(\''.htmlspecialchars(json_encode($arrParam),ENT_QUOTES).'\')"><i class="fa fa-lock"></i>Tolak</a>';
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
						<li>' . $btnaktivasi . '</li>
						<li>' . $btntolak . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->u_email;
			$row[] = $item->a_alamat;
			$row[] = convert_status_activate($item->u_status,true);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_aktivasi->count_all(),
			"recordsFiltered" => $this->m_aktivasi->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
