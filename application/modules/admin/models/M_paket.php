<?php

class M_paket extends CI_Model
{
    var $_table = 'paket';

    var $table = 'paket p';
    var $column_order = array('p.p_id', 'p.p_tgl','p.p_expired','a.a_kode','a.a_nama','p.p_status','k.k_kategori'); //set column field database for datatable orderable
    var $column_search = array('p.p_id', 'p.p_tgl','p.p_expired','a.a_kode','a.a_nama','p.p_status','k.k_kategori'); //set column field database for datatable searchable
    var $order = array('p.p_status' => 'asc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
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
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->join('kategori k','k.k_id=p.id_kategori','inner');
        return $this->db->count_all_results();
    }
    
    public function all(){
        return $this->db->from('paket p')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->join('kategori k','k.k_id=p.id_kategori','inner')
            ->get();
    }
    public function by_id($id){
        return $this->db->from('paket p')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->join('kategori k','k.k_id=p.id_kategori','inner')
            ->where('p.p_id',$id)->get();
    }


    public function insert(){
        $id_paket = $this->m_general->generate_id_paket(); 
        $current_date = date('Y-m-d');
        
        $data['p_id'] = $id_paket['temp'];
        $data['p_tgl'] = $this->input->post('tgl',TRUE);
        $data['id_kategori'] = $this->input->post('kategori',TRUE);
        //$tgl = explode("/", $data['p_tgl']);
        //$data['p_tgl'] = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];

        $data['p_expired'] = $this->input->post('expired',TRUE);
        //$exp = explode("/", $data['p_expired']);
        //$data['p_expired'] = $exp[2].'-'.$exp[1].'-'.$exp[0];
        
        $data['id_anggota'] = $this->input->post('anggota',TRUE);
        //$data['p_biaya'] = $this->input->post('biaya',TRUE);
        $data['p_status'] = 1;

        $cari_paket = $this->db->query("SELECT * FROM paket p WHERE ((p.p_tgl >= ".$data['p_tgl']." AND p.p_expired <= ".$data['p_tgl'].") OR  (p.p_tgl >= ".$data['p_expired']." AND p.p_expired <= ".$data['p_expired'].") AND p.p_expired >= ".$current_date.") AND p.id_anggota='".$data['id_anggota']."' AND p.id_kategori='".$data['id_kategori']."'");

        // if (count($cari_paket)>0){
        //     return array('status'=> FALSE,'message'=>'Jadwal dengan kategori sama sudah ada dan masih dalam keadaan aktif.');
        // }else{
            
        // }
        return $this->db->insert('paket',$data);    
        
    }
    
    public function update($id){
        $data['p_tgl'] = $this->input->post('tgl',TRUE);
        $data['id_kategori'] = $this->input->post('kategori',TRUE);
        //$tgl = explode("/", $data['p_tgl']);
        //$data['p_tgl'] = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];

        $data['p_expired'] = $this->input->post('expired',TRUE);
        //$exp = explode("/", $data['p_expired']);
        //$data['p_expired'] = $exp[2].'-'.$exp[1].'-'.$exp[0];
        //$data['p_biaya'] = $this->input->post('biaya',TRUE);
        
        $data['id_anggota'] = $this->input->post('anggota',TRUE);

        return $this->db->where('p_id',$id)->update('paket',$data);
    }
    
    public function delete($data){
        return $this->db->delete('paket',$data);
    }

    
}
