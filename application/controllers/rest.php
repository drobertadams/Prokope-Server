<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for REST. */
class rest extends CI_Controller 
{

	/** Get a document and all associated content. */
	public function document($id)
	{
		$this->load->helper('my_document_helper');
		$data = get_document_components($id);
		$this->load->view('doc_view_xml', $data);
	}

	/** Get a list of everything that is available in the database. */
	public function index()
	{
		$this->load->model("Document_model");
		$this->load->model("Author_model");
		$this->load->view('documents_xml');
	}

	/** Dummy list of lots of data. */
	public function big()
	{
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");
		$url = site_url("rest/document/1");
		$this->output->set_output(<<<EOT
		<prokope>
			<author name="Cicero" icon="url">
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Martial" icon="url">
				<work name="Work 0" url="$url" />
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
				<work name="Work 4" url="url" />
				<work name="Work 5" url="url" />
				<work name="Work 6" url="url" />
				<work name="Work 7" url="url" />
				<work name="Work 8" url="url" />
				<work name="Work 9" url="url" />
				<work name="Work 10" url="url" />
				<work name="Work 11" url="url" />
				<work name="Work 12" url="url" />
				<work name="Work 13" url="url" />
				<work name="Work 14" url="url" />
				<work name="Work 15" url="url" />
				<work name="Work 16" url="url" />
				<work name="Work 17" url="url" />
				<work name="Work 18" url="url" />
				<work name="Work 19" url="url" />
				<work name="Work 20" url="url" />
				<work name="Work 21" url="url" />
				<work name="Work 22" url="url" />
				<work name="Work 23" url="url" />
				<work name="Work 24" url="url" />
				<work name="Work 25" url="url" />
				<work name="Work 26" url="url" />
				<work name="Work 27" url="url" />
				<work name="Work 28" url="url" />
				<work name="Work 29" url="url" />
			</author>
			<author name="Agricola" icon="url">
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Caeser" icon="url">
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Pliny" icon="url">
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
		</prokope>
EOT
		); 

	}
}
