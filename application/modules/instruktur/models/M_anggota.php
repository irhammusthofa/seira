<?php
/**
* M_anggota
*/
class M_anggota extends CI_Model
{
	public function __construct(){
		parent::__construct();
        $this->load->model('m_user');
		$this->load->model('m_general');
	}
	public function signup(){
		$this->db->trans_begin();
        $data['u_email']            = $this->input->post('email',TRUE);

        if (count($this->m_user->by_email($data['u_email'])->result())>0){
			$arr = array(
				'status' => FALSE,
				'message'=> 'Email yang anda masukan sudah terdaftar,'
			);
		}else{
            $temp_id            = $this->m_general->generate_id_anggota();
            $password 			= rand(1000,9999);
            $data['u_name']     = $temp_id['temp'];
			$data['u_password'] = sha1($password);
			$data['u_status']	= 0;
			$data['u_role']	= 'user';
			if($this->m_user->insert($data)){
                $last_id = $this->db->insert_id();
				$data['password'] = $password;
                $this->m_user->sendemailsignup($data);
                $team['a_kode']             	= $data['u_name'];
                $team['a_nama']           		= $this->input->post('nama',TRUE);
                $team['a_hp']            		= $this->input->post('hp',TRUE);
                $team['a_alamat']      			= $this->input->post('alamat',TRUE);
                $team['a_member']           	= $this->input->post('jenis',TRUE);
                $team['id_user']                = $last_id;

                $this->db->insert('anggota',$team);
				$arr = array(
					'status' => TRUE,
					'message'=> 'Pendaftaran berhasil,'
				);
			}else{
				$arr = array(
					'status' => FALSE,
					'message'=> 'Pendaftaran gagal, silahkan coba kembali,'
				);
			}
		}
        
        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return $arr;
        }else{
            $this->db->trans_commit();
            return $arr;
        }
	}
	
}