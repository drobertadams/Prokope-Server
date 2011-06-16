<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for authors. */
class Author extends CI_Controller {

	/** Adds an author. */
	public function add()
	{
		// Make sure the user provided a name.
		if ( ! $this->input->post('name', TRUE) ) {
			// User didn't provide a name. Display an error on the home page.
			$this->load->library('session');
			$this->session->set_flashdata('error', "You did not provide a name for the author.");
			redirect("/");
			return;
		}

		// Add the author to the database.
		$this->load->model('Author_model');
		$this->Author_model->add( $this->input->post('name'), $this->input->post('icon') );

		// We're done. Return home.
		redirect('/');
	}

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}
}
