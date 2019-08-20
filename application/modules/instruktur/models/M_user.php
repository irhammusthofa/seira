<?php
/**
* M_user
*/
class M_user extends CI_Model
{
	public function login($param){
		return $this->db->where('u_name',$param['u_name'])
			->where('u_password',sha1($param['u_password']))
			->get('user');
	}
	public function by_email($id){
		return $this->db->where('u_email',$id)->get('user');
	}
	public function by_id($id){
		return $this->db->from('user u')
			->join('instruktur i','i.id_user=u.u_id','inner')
			->where('u.u_id',$id)->get();
	}
	public function insert($data){
		return $this->db->insert('user',$data);
	}
	public function auth_token($token){
		$this->db->select('*')
			->from('user')
			->where("u_password",$token);

		$query = $this->db->get()->row();
		if (count($query)>0){
			return array(
					'status' 	=> TRUE,
					'login'		=> TRUE,
	                'message' 	=> 'Token Valid', 
				);
		}else{
			return array(
					'status' 	=> FALSE,
					'login'		=> FALSE,
	                'message' 	=> 'Token salah/sudah tidak dapat digunakan lagi.', 
				);
		}
	}
	public function update_akun($data){
		return $this->db->where('u_id',$this->user->u_id)->update('user',$data);
	}
}