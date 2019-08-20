<?php
/**
* M_kriteria
*/
class M_kriteria extends CI_Model
{
	public function by_kategori($id){
		return $this->db->where('id_kategori',$id)->get('kriteria');
	}
}