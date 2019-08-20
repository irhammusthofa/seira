<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_anggota');

	}
	public function index()
	{
		$this->title 	= "Laporan";
		$this->content 	= "laporan/index";
		$this->assets 	= array();

		$param = array(

		);
		$this->template($param);
	}
	public function anggota(){
		$param['tgl1'] = $this->input->get('tgl1');
		$param['tgl2'] = $this->input->get('tgl2');
		$data = $this->m_anggota->lap_periode($param)->result();

		$param = array(
			'data' => $data,
		);
		$this->load->view('admin/laporan/anggota',$param);
	}
	
	
}
