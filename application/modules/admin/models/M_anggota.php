<?php

class M_anggota extends CI_Model
{
    var $_table = 'anggota';

    var $table = 'anggota a';
    var $column_order = array('a.a_kode', 'a.a_nama','a.a_hp','a.a_alamat','u.u_email','u.u_status'); //set column field database for datatable orderable
    var $column_search = array('a.a_kode', 'a.a_nama','a.a_hp','a.a_alamat','u.u_email','u.u_status'); //set column field database for datatable searchable
    var $order = array('a.a_kode' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('user u','u.u_id=a.id_user','inner');
        $this->db->where_not_in('u.u_status',[0]);
    
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
        $this->db->where_not_in('u.u_status',[0]);
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
    
    public function all(){
        return $this->db->from('anggota a')
            ->join('user u','u.u_id=a.id_user','inner')
            ->get();
    }
    public function by_id($id){
        return $this->db->from('anggota a')
            ->join('user u','u.u_id=a.id_user','inner')
            ->where('a.a_kode',$id)->get();
    }


    public function insert($data){
        $this->db->trans_begin();
        $data['u_email']            = $this->input->post('email',TRUE);

        if (count($this->m_user->by_email($data['u_email'])->result())>0){
            $arr = array(
                'status' => FALSE,
                'message'=> 'Email yang anda masukan sudah terdaftar,'
            );
        }else{
            $temp_id            = $this->m_general->generate_id_anggota();
            $password           = $this->input->post('password',TRUE);
            $data['u_name']     = $temp_id['temp'];
            $data['u_password'] = sha1($password);
            $data['u_status']   = 1;
            $data['u_role'] = 'user';
            if($this->m_user->insert($data)){
                $last_id = $this->db->insert_id();
                $data['password'] = $password;
                //$this->m_user->sendemailsignup($data);
                $team['a_kode']                 = $data['u_name'];
                $team['a_nama']                 = $this->input->post('nama',TRUE);
                $team['a_hp']                   = $this->input->post('hp',TRUE);
                $team['a_alamat']               = $this->input->post('alamat',TRUE);
                $team['a_member']               = $this->input->post('jenis',TRUE);
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
        if ($arr['status']===FALSE){
            $this->db->trans_rollback();
            return $arr;
        }else{
            if ($this->db->trans_status()===FALSE){
                $this->db->trans_rollback();
                return $arr;
            }else{
                $this->db->trans_commit();
                return $arr;
            }
        }
        
    }
    public function update($id){
        $this->db->trans_begin();
        $data_anggota = $this->m_anggota->by_id($id)->row();
        $data['u_email']            = $this->input->post('email',TRUE);
        $password           = $this->input->post('password',TRUE);
        if (!empty($password)){
            $data['u_password'] = sha1($password);
        }
        $data['u_name']     = $this->input->post('kode',TRUE);
        $data['u_status']   = $this->input->post('status',TRUE);;
        $this->m_user->update($data_anggota->id_user,$data);

        $team['a_kode']                 = $data['u_name'];
        $team['a_nama']                 = $this->input->post('nama',TRUE);
        $team['a_hp']                   = $this->input->post('hp',TRUE);
        $team['a_alamat']               = $this->input->post('alamat',TRUE);
        $team['a_member']               = $this->input->post('jenis',TRUE);

        $this->db->where('a_kode',$id)->update('anggota',$team);
    
        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function delete($data){
        $this->db->trans_begin();
            $this->db->delete($this->_table,$data);
            $this->db->delete('user',['u_name'=>$data['a_kode']]);
        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }            
    }

    public function lap_periode($param){
        return $this->db->from('anggota a')
            ->join('paket p','p.id_anggota=a.a_kode','inner')
            ->join('pembayaran pm','pm.id_paket=p.p_id','inner')
            ->join('kategori k','k.k_id=p.id_kategori','inner')
            ->where('p.p_tgl BETWEEN "'.$param['tgl1'].'" AND "'.$param['tgl2'].'"',NULL,false)
            ->order_by('k.k_id','ASC')
            ->order_by('a.a_kode','ASC')
            ->get();
    }

    
}
