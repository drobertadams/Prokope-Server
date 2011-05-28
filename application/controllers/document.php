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
		// Fetch the document.
		$this->load->model('Document_model');
		$this->Document_model->get($id);
		
		// ### Massage the document to make it suitable for display. ###
        // Force a line break after each line.
        $this->Document_model->content = str_replace("</l>", "</l><br/>", $this->Document_model->content);
        // Make sure that "indent"ed lines are indented. &#160; is the XML version of &nbsp.
        $this->Document_model->content = str_replace('rend="indent">', 
			'rend="indent">&#160;&#160;&#160;&#160;&#160;&#160;', $this->Document_model->content);
		// Convert words (w) to hyperlinks (a href).
		// ims turns on the case-insensitive, multiline, dot matches newline flags.
		$this->Document_model->content = preg_replace('/<w\s+id="([0-9.]+)">([^>]+)<\/w>/ims', 
			'<a href="$1">$2</a>', $this->Document_model->content);

		// Fetch the associated commentary.
		$this->load->model('Comment_model');
		$this->Comment_model->get_by_document($this->Document_model->id);
		// If there are no comments, display "None".
		if ( $this->Comment_model->id == 0 ) {
			$this->Comment_model->content = "None";
		}
        // Convert "comment" to "li" (list items).
		$this->Comment_model->content = str_replace('<comment', '<li', $this->Comment_model->content);
		$this->Comment_model->content = str_replace("</comment", "</li", $this->Comment_model->content);

		// Display everything.
		$data = array('doc' => $this->Document_model, 'comment' => $this->Comment_model);
		$this->load->view('doc_view', $data);
	}
}
