<?php

class M_instruktur_latihan extends CI_Model
{
    var $_table = 'instruktur_latihan';

    var $table = 'instruktur_latihan il';
    var $column_order = array('il.il_id', 'il.status','i.i_kode','i.i_nama','k.k_kategori'); //set column field database for datatable orderable
    var $column_search = array('il.il_id', 'il.status','i.i_kode','i.i_nama','k.k_kategori'); //set column field database for datatable searchable
    var $order = array('il.il_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->where('i.i_kode',$param['id_instruktur']);

    
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

    public function count_all($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->where('i.i_kode',$param['id_instruktur']);
        return $this->db->count_all_results();
    }
     public function all()
    {
        $this->db->from($this->table);
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        return $this->db->get();
    }
    
    public function by_id($id){
        return $this->db->from('instruktur_latihan il')
            ->join('instruktur i','i.i_kode=il.id_instruktur','inner')
            ->join('kategori k','k.k_id=il.id_kategori','inner')
            ->where('il.il_id',$id)->get();
    }


    public function insert($data){
        return $this->db->insert('instruktur_latihan',$data);
    }
    
    public function update($id,$data){
        return $this->db->where('il_id',$id)->update('instruktur_latihan',$data);
    }
    
    public function delete($data){
        return $this->db->delete('instruktur_latihan',$data);
    }

    
}
