<?php

class M_aktivasi extends CI_Model
{
    var $_table = 'anggota';

    var $table = 'anggota a';
    var $column_order = array('a.a_kode', 'a.a_nama','a.a_hp','a.a_alamat','u.u_email','u.u_status'); //set column field database for datatable orderable
    var $column_search = array('a.a_kode', 'a.a_nama','a.a_hp','a.a_alamat','u.u_email','u.u_status'); //set column field database for datatable searchable
    var $order = array('a.a_kode' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('user u','u.u_id=a.id_user','inner');
        $this->db->where('u.u_status',0);
    
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
        $this->db->join('user u','u.u_id=a.id_user','inner');
        $this->db->where('u.u_status',0);
        return $this->db->count_all_results();
    }
    public function aktivasi($id){
        $this->db->trans_begin();
            $data_anggota = $this->by_id($id)->row();
            if (empty($data_anggota)){
                $this->db->trans_rollback();
                return FALSE;
            }else{

                $password           = rand(1000,9999);
                $user['u_name'] = $data_anggota->u_name;
                $user['u_email'] = $data_anggota->u_email;
                $user['u_password'] = sha1($password);
                $user['u_status'] = 1;
                $save_user = $this->db->where('u_id',$data_anggota->id_user)->update('user',$user);
                $user['u_password'] = $password;
                $this->sendemailaktivasi($user);
            }
           
            

        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function tolak($id){
        $this->db->trans_begin();
            $data_anggota = $this->by_id($id)->row();
            if (empty($data_anggota)){
                $this->db->trans_rollback();
                return FALSE;
            }else{

                $password           = rand(1000,9999);
                $user['u_name'] = $data_anggota->u_name;
                $user['u_email'] = $data_anggota->u_email;
                $user['u_status'] = 3;
                $save_user = $this->db->where('u_id',$data_anggota->id_user)->update('user',$user);
                $user['alasan'] = $this->input->post('alasan',TRUE);
                $this->sendemailtolak($user);
            }
           
            

        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function sendemailtolak($data){
        $this->load->library('email');

        $this->email->from('hadiseptian63@gmail.com', 'Tolak Aktivasi Akun Seira');
        $this->email->to($data['u_email']);
        $this->email->set_mailtype("html");

        $this->email->subject('Tolak Aktivasi');
       $this->email->message('Permintaan aktivasi akun ditolak oleh admin dengan alasan "<b>'.$data["alasan"].'</b>".');

        return $this->email->send();
    }
    public function sendemailaktivasi($data){
        $this->load->library('email');

        $this->email->from('hadiseptian63@gmail.com', 'Aktivasi Akun Seira');
        $this->email->to($data['u_email']);
        $this->email->set_mailtype("html");

        $this->email->subject('Aktivasi');
       $this->email->message('Akun sudah aktif, silahkan login dan berikut adalah detail akun anda : <br><b>Username : '.$data['u_name'].'<br>Password : '.$data['u_password']);

        return $this->email->send();
    }
    
    public function by_id($id){
        return $this->db->from('anggota a')
            ->join('user u','u.u_id=a.id_user','inner')
            ->where('a.a_kode',$id)->get();
    }

    
}
