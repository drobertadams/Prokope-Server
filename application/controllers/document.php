<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for documents. */
class Document extends CI_Controller {

	/** Adds a document. */
	public function add()
	{
		// Load my helper that returns downloaded file content.
		$this->load->helper('my_download_helper');
		$content = get_file_upload_content();

		// Make sure the user provided a title and an author.
		if ( ! $this->input->post('title', TRUE) ) {
			// User didn't provide a title.  Display an error on the home page.
			$error = array('error' => "<p>You did not provide a title for the document.</p>");
			$this->load->view('home_page', $error);
			return;
		}

		// Add the document to the database.
		$this->load->model('Document_model');
		$this->Document_model->add( $this->input->post('title'), $content, $this->input->post('author') );

		// We're done. Return home.
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
