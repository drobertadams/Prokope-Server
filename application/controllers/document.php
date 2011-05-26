<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for documents. */
class Document extends CI_Controller {

	/** Adds a document. */
	public function add()
	{
		// Upload the file.
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xml';
		$config['overwrite'] = TRUE;
		$config['max_size']	= '100';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload() )
		{
			// There was a problem. Grab the error message and display it on the home page.
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('home_page', $error);
			return;
		}

		// Upload success. Grab the file contents.
		$upload_data = $this->upload->data();
		$this->load->helper('file');
		$content = read_file( $upload_data['full_path'] );

		// Store the data in the database.
		$this->load->model('Document_model');

		if ( ! $this->input->post('title', TRUE) ) {
			// User didn't provide a title.  Delete the file and display an error on the home page.
			delete_files( $upload_data['file_path'] );
			$error = array('error' => "<p>You did not provide a title for the document.</p>");
			$this->load->view('home_page', $error);
			return;
		}
		$this->Document_model->add( $this->input->post('title'), $content );

		// We're done. Delete the file and return home.
		delete_files( $upload_data['file_path'] );
		$this->load->view('home_page');
	}

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}
}
