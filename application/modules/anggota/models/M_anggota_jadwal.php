<?php

class M_anggota_jadwal extends CI_Model
{
    var $_table = 'anggota_jadwal';

    var $table = 'anggota_jadwal aj';
    var $column_order = array('aj.aj_id', 'ji.ji_tgl','ji.ji_jam','i.i_kode','i.i_nama','r.r_ruangan','a.a_kode','a.a_nama'); //set column field database for datatable orderable
    var $column_search = array('aj.aj_id', 'ji.ji_tgl','ji.ji_jam','i.i_kode','i.i_nama','r.r_ruangan','a.a_kode','a.a_nama'); //set column field database for datatable searchable
    var $order = array('aj.aj_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','aj.id_paket=p.p_id','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->where('a.a_kode',$this->user->a_kode);


    
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
        $pendaftar = $this->db->from('anggota_jadwal')->where('id_jadwal_instruktur',$data['id_jadwal_instruktur'])->count_all_results();
        $kuota = $this->db->from('instruktur_jadwal')->where('ji_id',$data['id_jadwal_instruktur'])->get()->row();
        if ($pendaftar < $kuota->ji_kuota){
            if (!empty($id_update)){
                $this->db->where_not_in('aj.aj_id',[$id_update]);
            }
            $ada_jadwal = $this->db->from('anggota_jadwal aj')
                ->join('paket p','p.p_id=aj.id_paket','inner')
                ->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner')
                ->where('ji.ji_tgl',$kuota->ji_tgl)
                ->where('p.p_id',$data['id_paket'])->count_all_results();
            if ($ada_jadwal>0){
                return array('status'=>false,'message'=>'Anggota ini sudah memiliki jadwal');
            }else{
                return array('status'=>true,'message'=>'Tersedia');
            }
            
        }else{
            return array('status'=>false,'message'=>'Tidak tersedia');
        }
    }
    public function detekpertemuan($param){
        $paket = $this->m_paket->by_id($param['paket'])->row();
        $param['tgl'] = $paket->p_tgl;
        $cektglpaket = $this->by_tgl($param)->row();
        if (empty($cektglpaket)){
            for($i=1;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8-7).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8).' day'));
                $cektglpaket = $this->by_tglperiode($param)->row();
                $cur = strtotime(date('Y-m-d',strtotime($param['tanggal_saatini'])));
                if ($cur >= strtotime($param['tgl_awal']) && $cur <= strtotime($param['tgl_akhir'])){
                    return array(
                        'ke' => $i,
                        'tgl_awal' => ($i==1) ? $param['tgl'] : $param['tgl_awal'],
                        'tgl_akhir' => $param['tgl_akhir'],
                    );
                    break;
                }
            }
            return array(
                'ke'=>0,
                'tgl_awal' => '',
                'tgl_akhir' => '',
            );
        }else{

            $cur = strtotime(date('Y-m-d'));
            for($i=2;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7-6).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7).' day'));
                $cektglpaket = $this->by_tglperiode($param)->row();
                $cur = strtotime(date('Y-m-d',strtotime($param['tanggal_saatini'])));
                if ($cur >= strtotime($param['tgl_awal']) && $cur <= strtotime($param['tgl_akhir'])){
                    return array(
                        'ke' => $i,
                        'tgl_awal' => ($i==1) ? $param['tgl'] : $param['tgl_awal'],
                        'tgl_akhir' => $param['tgl_akhir'],
                    );
                    break;
                }
            }
            return array(
                'ke'=>0,
                'tgl_awal' => '',
                'tgl_akhir' => '',
            );
        }
    }
    public function loadpertemuan($param){
        $paket = $this->m_paket->by_id($param['paket'])->row();
        $param['tgl'] = $paket->p_tgl;
        $cektglpaket = $this->by_tgl($param)->row();
        if (empty($cektglpaket)){
            for($i=1;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8-7).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*8).' day'));
                $cektglpaket = $this->by_tglperiode($param)->row();
                $cur = strtotime(date('Y-m-d'));
                if (empty($cektglpaket) && $cur <= strtotime($param['tgl_akhir'])){
                    return array(
                        'ke' => $i,
                        'tgl_awal' => ($i==1) ? $param['tgl'] : $param['tgl_awal'],
                        'tgl_akhir' => $param['tgl_akhir'],
                    );
                    break;
                }
            }
            return array(
                'ke'=>0,
                'tgl_awal' => '',
                'tgl_akhir' => '',
            );
        }else{

            $cur = strtotime(date('Y-m-d'));
            for($i=2;$i<9;$i++){
                $param['tgl_awal'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7-6).' day'));
                $param['tgl_akhir'] = date('Y-m-d', strtotime($param['tgl'] . ' +'.($i*7).' day'));
                $cektglpaket = $this->by_tglperiode($param)->row();
                if (empty($cektglpaket) && $cur <= strtotime($param['tgl_akhir'])){
                    return array(
                        'ke' => $i,
                        'tgl_awal' => $param['tgl_awal'],
                        'tgl_akhir' => $param['tgl_akhir'],
                    );
                    break;
                }
            }
            return array(
                'ke'=>0,
                'tgl_awal' => '',
                'tgl_akhir' => '',
            );
        }
    }
    public function count_all($param='')
    {
        $this->db->from($this->table);
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','aj.id_paket=p.p_id','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->where('a.a_kode',$this->user->a_kode);
        return $this->db->count_all_results();
    }
    
    public function loadjadwal($param){
        $this->db->from('instruktur_jadwal ji');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','p.id_kategori=il.id_kategori','inner');
        $this->db->where('ji.ji_tgl',$param['tgl']);
        $this->db->where('p.p_id',$param['paket']);
        $this->db->where('ji.ji_tipemember',$param['tipe_member']);
        return $this->db->get();
    }
    public function by_id($id){
        $this->db->from($this->table);
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','aj.id_paket=p.p_id','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        return $this->db->where('aj.aj_id',$id)->get();
    }
    public function by_tgl($param){
        $this->db->from($this->table);
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','aj.id_paket=p.p_id','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->where('ji.ji_tgl',$param['tgl']);
        $this->db->where('aj.id_paket',$param['paket']);
        return $this->db->get();
    }

    public function by_tglperiode($param){
        $this->db->from($this->table);
        $this->db->join('instruktur_jadwal ji','ji.ji_id=aj.id_jadwal_instruktur','inner');
        $this->db->join('instruktur_latihan il','il.il_id=ji.id_instruktur_latihan','inner');
        $this->db->join('instruktur i','i.i_kode=il.id_instruktur','inner');
        $this->db->join('ruangan r','r.r_id=ji.id_ruangan','inner');
        $this->db->join('kategori k','k.k_id=il.id_kategori','inner');
        $this->db->join('paket p','aj.id_paket=p.p_id','inner');
        $this->db->join('anggota a','a.a_kode=p.id_anggota','inner');
        $this->db->where('ji.ji_tgl BETWEEN "'. date('Y-m-d', strtotime($param['tgl_awal'])). '" and "'. date('Y-m-d', strtotime($param['tgl_akhir'])).'"');
        $this->db->where('aj.id_paket',$param['paket']);
        return $this->db->get();
    }
    public function insert($data){
        return $this->db->insert('anggota_jadwal',$data);
    }
    
    public function update($id,$data){
        return $this->db->where('aj_id',$id)->update('anggota_jadwal',$data);
    }
    
    public function delete($data){
        return $this->db->delete('anggota_jadwal',$data);
    }

    
}
