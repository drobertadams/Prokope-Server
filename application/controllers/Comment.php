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
}
?>
