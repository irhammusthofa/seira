<?php

class M_nilai extends CI_Model
{
    var $_table = 'anggota_jadwal';

    var $table = 'anggota_jadwal aj';
    var $column_order = array('aj.aj_id','a.a_kode','a.a_nama','k.k_kategori'); //set column field database for datatable orderable
    var $column_search = array('aj.aj_id','a.a_kode','a.a_nama','k.k_kategori'); //set column field database for datatable searchable
    var $order = array('aj.aj_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
        $this->load->model('m_paket');
        $this->load->model('m_anggota_jadwal');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('paket p','p.p_id=aj.id_paket','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->join('kategori k','k.k_id=p.id_kategori','inner');
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');

        $this->db->where('a.a_member','yes');
        $this->db->group_by('p.p_id');
    
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
        $this->db->join('paket p','p.p_id=aj.id_paket','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->join('kategori k','k.k_id=p.id_kategori','inner');
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->where('a.a_member','yes');
        $this->db->group_by('p.p_id');
        return count($this->db->get()->result());
    }
    
    public function all(){
        return $this->db->from('paket p')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->get();
    }
    public function by_anggota($id){
        return $this->db->from('paket p')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->where('a.a_kode',$id)->get();
    }
    public function by_id($id){
        return $this->db->from('paket p')
            ->join('anggota a','a.a_kode=p.id_anggota','inner')
            ->where('p.p_id',$id)->get();
    }
    public function by_jadwal_anggota($id,$kriteria){
        return $this->db->from('nilai')
            ->where('id_kriteria',$kriteria)
            ->where('id_jadwal_anggota',$id)->get();
    }
    public function by_pertemuan_kriteria($param){
        $pertemuan = $this->loadpertemuan($param);
        if ($pertemuan['status']){
            $nilai = $this->by_jadwal_anggota($pertemuan['id'],$param['id_kriteria'])->row();
            return array(
                'status' => true,
                'aj_id' => $pertemuan['id'],
                'aj_absen' => @$pertemuan['aj_absen'],
                'value' => (empty($nilai)) ? '' : $nilai->nilai,
            );
        }else{
            return array(
                'status' => false,
                'aj_id' => '',
                'aj_absen' => '',
                'value' => '',
            );
        }
    }
    public function loadpertemuan($param){
        $paket = $this->m_paket->by_id($param['paket'])->row();
        $param['tgl'] = $paket->p_tgl;
        $cektglpaket = $this->m_anggota_jadwal->by_tgl($param)->row();
        if (empty($cektglpaket)){
            for($i=1;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8-7).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8).' day'));
                $cektglpaket = $this->m_anggota_jadwal->by_tglperiode($param)->row();
                $cur = strtotime(date('Y-m-d'));
                if ($param['pertemuan']==$i && empty($cektglpaket)){
                    return array(
                        'status' => false,
                        'id' => '',
                    );
                }else if($param['pertemuan']==$i){
                    return array(
                        'status' => true,
                        'id' => $cektglpaket->aj_id,
                        'aj_absen' => $cektglpaket->aj_absen,
                    );
                }else if ($param['pertemuan']==$i){
                    return array(
                        'status' => false,
                        'id' => '',
                        'aj_absen' => '',
                    );
                }
            }
            return array(
                'status' => false,
                'id' => '',
                'aj_absen' => '',
            );
        }else{

            $cur = strtotime(date('Y-m-d'));
            for($i=2;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7-6).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7).' day'));
                $cektglpaket = $this->m_anggota_jadwal->by_tglperiode($param)->row();
                if ($param['pertemuan']==$i && empty($cektglpaket)){
                    return array(
                        'status' => false,
                        'id' => '',
                    );
                }else if($param['pertemuan']==$i){
                    return array(
                        'status' => true,
                        'id' => $cektglpaket->aj_id,
                    );
                }else if ($param['pertemuan']==$i){
                    return array(
                        'status' => false,
                        'id' => '',
                    );
                }
            }
            return array(
                'status' => false,
                'id' => '',
            );
        }
    }
    public function insert(){
        $id_paket = $this->m_general->generate_id_paket(); 
        $data['p_id'] = $id_paket['temp'];
        $data['p_tgl'] = $this->input->post('tgl',TRUE);
        $data['id_kategori'] = $this->input->post('kategori',TRUE);
        //$tgl = explode("/", $data['p_tgl']);
        //$data['p_tgl'] = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];

        $data['p_expired'] = $this->input->post('expired',TRUE);
        //$exp = explode("/", $data['p_expired']);
        //$data['p_expired'] = $exp[2].'-'.$exp[1].'-'.$exp[0];
        
        $data['id_anggota'] = $this->input->post('anggota',TRUE);
        $data['p_status'] = 1;

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
        
        $data['id_anggota'] = $this->input->post('anggota',TRUE);

        return $this->db->where('p_id',$id)->update('paket',$data);
    }
    

    public function delete($data){
        return $this->db->delete('paket',$data);
    }

    
}
