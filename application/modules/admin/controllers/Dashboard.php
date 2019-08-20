<?php
/**
* Dashboard
*/
class Dashboard extends Admin_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth();

	}
	public function index(){
		redirect('admin/aktivasi');
	}
}