<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for sidebar. */
class Sidebar extends CI_Controller {

	/** Adds a sidebar file. */
	public function add()
	{
		// Load my helper that returns downloaded file content.
		$this->load->helper('my_download_helper');
		$content = get_file_upload_content();

		// Add the sidebar to the database.
		$this->load->model('Sidebar_model');
		$this->Sidebar_model->add($content, $this->input->post('document_id'));

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
