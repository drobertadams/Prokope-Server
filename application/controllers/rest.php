<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for REST. */
class rest extends CI_Controller {

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}

	/** Get a document and all associated content. */
	public function document($id)
	{
		$this->load->helper('my_document_helper');
		$data = get_document_components($id);
		$this->load->view('doc_view_xml', $data);
	}
}
