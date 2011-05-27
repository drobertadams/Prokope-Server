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

		$this->load->model('Document_model');

		// Make sure the user provided a title.
		if ( ! $this->input->post('title', TRUE) ) {
			// User didn't provide a title.  Delete the file and display an error on the home page.
			delete_files( $upload_data['file_path'] );
			$error = array('error' => "<p>You did not provide a title for the document.</p>");
			$this->load->view('home_page', $error);
			return;
		}

		// Add the document to the database.
		$this->Document_model->add( $this->input->post('title'), $content );

		// We're done. Delete the file and return home.
		delete_files( $upload_data['file_path'] );
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

		// Display it.
		$data = array('doc' => $this->Document_model);
		$this->load->view('doc_view', $data);
	}
}
