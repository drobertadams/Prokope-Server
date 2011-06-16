<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for the home page. */
class Home extends CI_Controller {

	/** Displays the home page. */
	public function index()
	{
		$this->load->library('session');

		// If the user is logged in, fetch their documents.
		$data = NULL;
		if ($this->quickauth->logged_in()) {
			$this->load->model('Document_model');
			$this->load->model('Author_model');
			$docs = $this->Document_model->usersdocs();
			$authors = $this->Author_model->get();
			$data = array('docs' => $docs, 'authors' => $authors);
		}
		$this->load->view('home_page', $data);
	}
}
