<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for comments. */
class Comment extends CI_Controller {

	/** Adds a comment. */
	public function add()
	{
		// Load my helper that returns downloaded file content.
		$this->load->helper('my_download_helper');
		$content = get_file_upload_content();

		// Add the comment to the database.
		$this->load->model('Comment_model');
		$this->Comment_model->add($content, $this->input->post('document_id'));

		// We're done. Delete the file and return to the document view.
		redirect( site_url( 'Document/view/' . $this->input->post('document_id') ) );
	}

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}

	/** View a comment. */
/*	public function view($id)
	{
		// Fetch the comment.
		$this->load->model('Comment_model');
		$this->Comment_model->get($id);
		
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
	*/
}
?>
