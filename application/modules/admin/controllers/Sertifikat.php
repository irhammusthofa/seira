<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Sertifikat extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_paket');
		$this->load->model('m_anggota');
		$this->load->model('m_kategori');

		$this->load->model('m_kriteria');
	}
	public function index()
	{
		$this->title 	= "Sertifikat";
		$this->content 	= "sertifikat/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	public function generate($id){
		$id = base64_decode($id);
		$paket = $this->m_paket->by_id($id)->row();
		$anggota = $this->m_anggota->by_id($paket->id_anggota)->row();
		$sertifikat = $this->db->where('id_paket',$id)->get('sertifikat')->row();

		if (!empty($sertifikat)){
			fs_create_alert(['type'=>'warning','message'=>'Sertifikat sudah digenerate']);
			redirect('admin/sertifikat');
		}
		$filename  = str_replace("/", "", $id);
		$data = $anggota;
		$html = $this->load->view('sertifikat/sertifikat',['data'=>$data],TRUE);
		// instantiate and use the dompdf class
		$options = new Options();
		$options->set('isRemoteEnabled', TRUE);
		$dompdf = new Dompdf($options);
		$contxt = stream_context_create([ 
		    'ssl' => [ 
		        'verify_peer' => FALSE, 
		        'verify_peer_name' => FALSE,
		        'allow_self_signed'=> TRUE
		    ] 
		]);
		$dompdf->setHttpContext($contxt);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');

		// Render the HTML as PDF
		$dompdf->render();
		$sn = strtotime(date('Y-m-d H:i:s'));

		// Output the generated PDF to Browser
		//$dompdf->stream();
		$output = $dompdf->output();
		file_put_contents('assets/sertifikat/'.$sn.'.pdf', $output);
		
		if (empty($sertifikat)){
			$this->db->insert('sertifikat',['s_sn'=>$sn,'id_paket'=>$id]);
		}else{
			$this->db->update('sertifikat',['s_sn'=>$sn,'id_paket'=>$id]);
		}
		fs_create_alert(['type'=>'success','message'=>'Sertifikat berhasil digenerate']);
		redirect('admin/sertifikat');
		//generateCard(date("Y-m-d"),$anggota->a_nama,$filename );
		//echo '<img src="'.base_url("assets/img/".$filename.".jpg").'"/>';
	}
	public function download($id){
		$this->load->helper('download');
		$id = base64_decode($id);

		$sertifikat = $this->db->where('id_paket',$id)->get('sertifikat')->row();
		if (empty($sertifikat)){
			fs_create_alert(['type'=>'danger','message'=>'Sertifikat tidak tersedia']);
			redirect('admin/sertifikat');
		}else{
			force_download('assets/sertifikat/'.$sertifikat->s_sn.'.pdf',NULL);
		}
	}
	public function ajax_list()
	{

		$list = $this->m_paket->get_datatables();
		$data = array();

		$no = $_POST['start'];
		$cnt = 0;
		foreach ($list as $item) {
			$total_kriteria = count($this->m_kriteria->by_kategori($item->id_kategori)->result());

			$rata2 = $this->db->select_sum('n.nilai')->from('anggota_jadwal aj')
				->join('nilai n','n.id_jadwal_anggota=aj.aj_id','inner')
				->where('aj.id_paket',$item->p_id)
				->get()->row();
			$rata2 = $rata2->nilai;
			if ($rata2>0){
				$rata2 = ($rata2 / $total_kriteria) / 8;	
			}else{
				$rata2 = 0;
			}
			if ($rata2 < 85){
				$cnt++;
				continue;
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
						<li>' . anchor("admin/sertifikat/download/".base64_encode($item->p_id),"<i class=\"fa fa-download\"></i>Download") . '</li>
						<li>' . anchor("admin/sertifikat/generate/".base64_encode($item->p_id),"<i class=\"fa fa-gear\"></i>Generate") . '</li>
					</ul>
                </div>';
            
            $now = strtotime(date('Y-m-d'));
            $exp = strtotime($item->p_expired);

            $data_nilai = count($this->db->from('anggota_jadwal aj')
            	->join('paket p','p.p_id=aj.id_paket','inner')
            	->join('nilai n','n.id_jadwal_anggota=aj.aj_id','inner')
            	->group_by('aj.aj_id')->get()->result());
            // if ($now>$exp){
            // 	$status = 2;
            // }else{
            // 	$status = 1;
            // }
            if ($data_nilai<8){
            	$status = 1;
            }else{
            	$status = 2;
            }
            $this->db->where('p_id',$item->p_id)->update('paket',['p_status'=>$status]);
            
            $sertifikat = $this->db->where('id_paket',$item->p_id)->get('sertifikat')->row();

            if ($status==2){
            	if (empty($sertifikat)){
            		$status = label_skin(['type'=>'primary','text'=>'Belum digenerate']);	
            	}else{
            		$status = label_skin(['type'=>'success','text'=>'Sudah digenerate']);
            	}
            }else{
            	$status = label_skin(['type'=>'danger','text'=>'Belum lengkap']);
            }
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->p_id;
			$row[] = $item->a_kode;
			$row[] = $item->a_nama;
			$row[] = $item->k_kategori;
			$row[] = round($rata2,2);
			$row[] = $status;
			$row[] = @$sertifikat->s_sn;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_paket->count_all()-$cnt,
			"recordsFiltered" => $this->m_paket->count_filtered()-$cnt,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
