<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('m_kategori');
		$data = $this->m_kategori->all()->result();
		$param = array(
			'kategori' => $data,
		);
		$this->load->view('welcome_message',$param);
	}
	public function logout($redirect)
	{
		$redirect = base64_decode($redirect);
		$this->session->sess_destroy();
		redirect($redirect);
	}
}
