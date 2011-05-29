<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for documents. */
class Document extends CI_Controller {

	/** Adds a document. */
	public function add()
	{
		// Load my helper that returns downloaded file content.
		$this->load->helper('my_download_helper');
		$content = get_file_upload_content();

		// Make sure the user provided a title.
		if ( ! $this->input->post('title', TRUE) ) {
			// User didn't provide a title.  Delete the file and display an error on the home page.
			delete_files( $upload_data['file_path'] );
			$error = array('error' => "<p>You did not provide a title for the document.</p>");
			$this->load->view('home_page', $error);
			return;
		}

		// Add the document to the database.
		$this->load->model('Document_model');
		$this->Document_model->add( $this->input->post('title'), $content );

		// We're done. Delete the file and return home.
		redirect('/');
	}

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}


	/** View a document. */
	public function view($id)
	{
		$this->load->helper('my_document_helper');
		$data = get_document_components($id);
		$this->load->view('doc_view', $data);
	}
}
