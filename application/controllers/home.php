<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for the home page. */
class Home extends CI_Controller {

	/** Displays the home page. */
	public function index()
	{
		// If the user is logged in, fetch their documents.
		$data = NULL;
		if ($this->quickauth->logged_in()) {
			$this->load->model('Document_model');
			$docs = $this->Document_model->usersdocs();
			$data = array('docs' => $docs);
		}
		$this->load->view('home_page', $data);
	}
}
