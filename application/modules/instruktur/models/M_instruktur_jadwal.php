<?php

class M_instruktur_jadwal extends CI_Model
{
    var $_table = 'instruktur_jadwal';

    var $table = 'instruktur_jadwal ji';
    var $column_order = array('ji.ji_id', 'ji.ji_tgl','ji.ji_jam','i.i_kode','i.i_nama','r.r_ruangan'); //set column field database for datatable orderable
    var $column_search = array('ji.ji_id', 'ji.ji_tgl','ji.ji_jam','i.i_kode','i.i_nama','r.r_ruangan'); //set column field database for datatable searchable
    var $order = array('ji.ji_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->where('i.i_kode',$this->user->i_kode);


    
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($param='')
    {
        $this->_get_datatables_query($param);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($param='')
    {
        $this->_get_datatables_query($param);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function ketersediaan($data,$id_update=''){
        $query = "SELECT count(*) c FROM instruktur_jadwal WHERE ji_tgl='".$data['ji_tgl']."' AND ((ji_jam_mulai <= '".$data['ji_jam_mulai']."' AND ji_jam_selesai > '".$data['ji_jam_mulai']."') OR (ji_jam_mulai < '".$data['ji_jam_selesai']."' AND ji_jam_selesai >= '".$data['ji_jam_selesai']."'))  AND id_ruangan='".$data['id_ruangan']."'";
        if (!empty($id_update)){
            $query = $query . " AND NOT ji_id='".$id_update."'"; 
        }
        $q = $this->db->query($query)->row();
        return ($q->c < 1);
    }
    public function count_all($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->where('i.i_kode',$this->user->i_kode);
        return $this->db->count_all_results();
    }
    
    public function hari_ini(){
        $this->db->order_by('ji_jam_mulai','asc');
        $this->db->from($this->table);
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->where('i.i_kode',$this->user->i_kode);
        return $this->db->where('ji.ji_tgl',date('Y-m-d'))->get();
    }
    public function by_id($id){
        $this->db->from($this->table);
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        return $this->db->where('ji.ji_id',$id)->get();
    }
    public function by_tgl($tgl){
        $this->db->from($this->table);
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        return $this->db->where('ji.ji_tgl',$tgl)->get();
    }

    public function insert($data){
        return $this->db->insert('instruktur_jadwal',$data);
    }
    
    public function update($id,$data){
        return $this->db->where('ji_id',$id)->update('instruktur_jadwal',$data);
    }
    
    public function delete($data){
        return $this->db->delete('instruktur_jadwal',$data);
    }

    
}
