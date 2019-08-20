<?php

class M_instruktur extends CI_Model
{
    var $_table = 'instruktur';

    var $table = 'instruktur a';
    var $column_order = array('a.i_kode', 'a.i_nama','a.i_hp','a.i_alamat','u.u_email','u.u_status'); //set column field database for datatable orderable
    var $column_search = array('a.i_kode', 'a.i_nama','a.i_hp','a.i_alamat','u.u_email','u.u_status'); //set column field database for datatable searchable
    var $order = array('a.i_kode' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_general');
        $config = [
               'mailtype'  => 'html',
               'charset'   => 'utf-8',
               'protocol'  => 'smtp',
               'smtp_host' => 'ssl://smtp.gmail.com',
               'smtp_user' => 'nusahawae63@gmail.com',    // Ganti dengan email gmail kamu
               'smtp_pass' => 'ulahpoho',      // Password gmail kamu
               'smtp_port' => 465,
               'crlf'      => "\r\n",
               'newline'   => "\r\n"
           ];
        $this->load->library('email',$config);

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
    
    
    public function all(){
        return $this->db->from('instruktur')->get();
    }
    public function by_id($id){
        return $this->db->from('instruktur a')
            ->join('user u','u.u_id=a.id_user','inner')
            ->where('a.i_kode',$id)->get();
    }

    private function generatePassword()
    {
        return rand(10000,99999);
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
            $temp_id            = $this->m_general->generate_id_instruktur();
            $password           = $this->generatePassword();
            $data['u_name']     = $temp_id['temp'];
            $data['u_password'] = sha1($password);
            $data['u_status']   = 1;
            $data['u_role'] = 'instruktur';
            if($this->m_user->insert($data)){
                $last_id = $this->db->insert_id();
                $data['password'] = $password;
                //$this->m_user->sendemailsignup($data);
                $team['i_kode']                 = $data['u_name'];
                $team['i_nama']                 = $this->input->post('nama',TRUE);
                $team['i_hp']                   = $this->input->post('hp',TRUE);
                $team['i_alamat']               = $this->input->post('alamat',TRUE);
                $team['id_user']                = $last_id;

                $this->db->insert('instruktur',$team);
                $pemail['email'] = $data['u_email'];
                $pemail['id']   = $data['u_name'];
                $pemail['password']   = $password;
                $this->sendemailinstruktur($pemail);
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
    private function sendemailinstruktur($data){
        $this->email
            ->from('nusahawae63@gmail.com', 'Seira Studio')
            ->to($data['email'])
            ->subject('Pendaftaran Instruktur')
            ->message('Selamat anda telah terdaftar sebagai instruktur di Seira Studio dengan ID : <b>'.$data['id'].'</b> dan Password : <b>'.$data['password'].'</b>')
            ->set_mailtype('html');

        // send email
        return $this->email->send();
    }
    public function update($id){
        $this->db->trans_begin();
        $data_instruktur                = $this->by_id($id)->row();
        $data['u_email']                = $this->input->post('email',TRUE);
        $password                       = $this->input->post('password',TRUE);
        if (!empty($password)){
            $data['u_password'] = sha1($password);
        }
        $data['u_name']     = $this->input->post('kode',TRUE);
        $data['u_status']   = $this->input->post('status',TRUE);;
        $this->m_user->update($data_instruktur->id_user,$data);

        $team['i_kode']                   = $data['u_name'];
        $team['i_nama']                 = $this->input->post('nama',TRUE);
        $team['i_hp']                   = $this->input->post('hp',TRUE);
        $team['i_alamat']               = $this->input->post('alamat',TRUE);

        $this->db->where('i_kode',$id)->update('instruktur',$team);
    
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
            $this->db->delete('user',['u_name'=>$data['i_kode']]);
        if ($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }            
    }

    
}
