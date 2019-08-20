<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_paket');
		$this->load->model('m_anggota');
		$this->load->model('m_pembayaran');

		$config = [
               'mailtype'  => 'html',
               'charset'   => 'utf-8',
               'protocol'  => 'smtp',
               'smtp_host' => 'ssl://smtp.gmail.com',
               'smtp_user' => 'nusahawae63@gmail.com',    // Ganti dengan email gmail kamu
               'smtp_pass' => 'ulahpoho',      // Password gmail kamu
               'smtp_port' => 465,
               'crlf'      => "\r\n",
               'newline'   => "\r\n"
           ];
        
		$this->load->library('email',$config);

	}
	public function index()
	{
		$this->title 	= "Pembayaran";
		$this->content 	= "pembayaran/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	public function add()
	{
		$this->title 	= "Pembayaran";
		$this->content 	= "pembayaran/add";
		$this->assets 	= array('assets_form');
		$paket = $this->m_paket->all()->result();
		$data['paket'][''] = 'Pilih Paket';
		foreach ($paket as $row) {
			$data['paket'][$row->p_id] = $row->p_id . ' | '. $row->k_kategori .' | '. $row->a_nama; 
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	public function cetaknota($id)
	{
		$id = base64_decode($id);
		$data['pembayaran'] = $this->m_pembayaran->by_id($id)->row();

		$param = array(
			'data' => $data,
		);
		$this->load->view('pembayaran/nota',$param);
	}
	private function howDays($from, $to) {
	    $first_date = strtotime($from);
	    $second_date = strtotime($to);
	    $offset = $second_date-$first_date; 
	    return floor($offset/60/60/24);
	}
	public function cekharga(){
		$id_paket = $this->input->post('id_paket',TRUE);
		$paket = $this->m_paket->by_id($id_paket)->row();
		if(empty($paket)){
			$arr = array(
				'status' => FALSE,
				'message'=>'Data Paket tidak ditemukan'
			);
		}else{
			$day = $this->howDays($paket->p_tgl,$paket->p_expired);
			
			if($paket->a_member=='no'){
				$hasil = $paket->k_h_nmember;
			}else if ($day > 30){
				$hasil = $paket->k_h_member2;
			}else{
				$hasil = $paket->k_h_member1;
			}
			$arr = array(
				'status' => TRUE,
				'message'=>'Data Paket ditemukan '.$id_paket,
				'paket' => $paket,
				'hasil' => $hasil
			);
		}
		echo json_encode($arr);
		
	}
	public function edit($id)
	{
		$this->title 	= "Pembayaran";
		$this->content 	= "pembayaran/edit";
		$this->assets 	= array('assets_form');

		$paket = $this->m_paket->all()->result();
		$data['paket'][''] = 'Pilih Paket';
		foreach ($paket as $row) {
			$data['paket'][$row->p_id] = $row->p_id . ' | '. $row->k_kategori .' | '. $row->a_nama; 
		}


		$id = base64_decode($id);
		$data['pembayaran'] = $this->m_pembayaran->by_id($id)->row();

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	private function sendemail_nota($id)
	{
		$id = base64_decode($id);
		$data['pembayaran'] = $this->m_pembayaran->by_id($id)->row();

		$param = array(
			'data' => $data,
		);
		$view = $this->load->view('pembayaran/nota',$param,TRUE);
		$this->email->from('nusahawae63@gmail.com', 'Seira Studio');
		$this->email->to($data['pembayaran']->u_email);
		$this->email->set_mailtype("html");

		$this->email->subject('Nota Pembayaran');
		$this->email->message($view);

		return $this->email->send();
	}
	public function sendemail($id){
		if ($this->sendemail_nota($id)){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dikirim']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dikirim']);
		}
		redirect('admin/pembayaran');
	}
	public function simpan($id="")
	{
		if (empty($id)){
			$save = $this->m_pembayaran->insert();
			if ($save['status']){
				$this->sendemail_nota(base64_encode($save['id']));
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/pembayaran/cetaknota/'.base64_encode($save['id']));
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/pembayaran/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_pembayaran->update($id);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/pembayaran');
			}else{
				fs_create_alert(['type'=>'danger','message'=>$save['message']]);
				redirect('admin/pembayaran/edit/'.base64_encode($id));

			}
		}
	}
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['pm_id'] = $id;
		$delete = $this->m_pembayaran->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/pembayaran');
	}
	public function ajax_list()
	{

		$list = $this->m_pembayaran->get_datatables();
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
						<li>' . anchor("admin/pembayaran/edit/".base64_encode($item->pm_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/pembayaran/cetaknota/".base64_encode($item->pm_id),"<i class=\"fa fa-print\"></i>Cetak Nota") . '</li>
						<li>' . anchor("admin/pembayaran/sendemail/".base64_encode($item->pm_id),"<i class=\"fa fa-envelope\"></i>Send Email") . '</li>
						<li>' . anchor("admin/pembayaran/hapus/".base64_encode($item->pm_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
            
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->pm_id;
			$row[] = $item->p_id;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->k_kategori;
			$row[] = $item->pm_tgl;
			$row[] = "Rp".number_format($item->pm_biaya);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_pembayaran->count_all(),
			"recordsFiltered" => $this->m_pembayaran->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
