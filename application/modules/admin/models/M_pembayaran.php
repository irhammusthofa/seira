<?php

class M_pembayaran extends CI_Model
{
    var $_table = 'pembayaran';

    var $table = 'pembayaran pm';
    var $column_order = array('p.p_id', 'pm.pm_tgl', 'pm.pm_biaya','a.a_kode','a.a_nama','k.k_kategori'); //set column field database for datatable orderable
    var $column_search = array('p.p_id', 'pm.pm_tgl', 'pm.pm_biaya','a.a_kode','a.a_nama','k.k_kategori'); //set column field database for datatable searchable
    var $order = array('pm.pm_id' => 'asc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('paket p','p.p_id=pm.id_paket','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->join('kategori k','k.k_id=p.id_kategori','inner');
    
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
        $this->db->join('paket p','p.p_id=pm.id_paket','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->join('kategori k','k.k_id=p.id_kategori','inner');
        return $this->db->count_all_results();
    }
    
    public function all(){
        return $this->db->from('pembayaran pm')
            ->join('paket p','p.p_id=pm.id_paket','inner')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->get();
    }
    public function by_id($id){
        return $this->db->from('pembayaran pm')
            ->join('paket p','p.p_id=pm.id_paket','inner')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->join('kategori k','k.k_id=p.id_kategori','inner')
            ->join('user u','u.u_id=a.id_user','inner')
            ->where('pm.pm_id',$id)->get();
    }


    public function insert(){
        $id_paket = $this->m_general->generate_id_pembayaran(); 
        
        $data['pm_id'] = $id_paket['temp'];
        $data['pm_tgl'] = $this->input->post('tgl',TRUE);
        $data['id_paket'] = $this->input->post('paket',TRUE);
        $data['pm_biaya'] = $this->input->post('biaya',TRUE);
        $save = $this->db->insert('pembayaran',$data);    
        if ($save){
            return array(
                'status' => TRUE,
                'message'=> 'Data berhasil disimpan',
                'id' => $data['pm_id'],
            );
        }else{
            return array(
                'status' => FALSE,
                'message'=> 'Data gagal disimpan',
            );
        }
        
    }
    
    public function update($id){
        $data['pm_tgl'] = $this->input->post('tgl',TRUE);
        $data['id_paket'] = $this->input->post('paket',TRUE);
        $data['pm_biaya'] = $this->input->post('biaya',TRUE);
        return $this->db->where('pm_id',$id)->update('pembayaran',$data);
    }
    
    public function delete($data){
        return $this->db->delete('pembayaran',$data);
    }

    
}
