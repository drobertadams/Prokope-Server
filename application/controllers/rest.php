<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for REST. */
class rest extends CI_Controller 
{
	/** Dummy list of lots of data. */
	public function big()
	{
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");
		$url = site_url("rest/document/3");
		$this->output->set_output(<<<EOT
		<prokope>
			<author name="Cicero" icon="url">
				<bio>All about Cicero.</bio>
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Martial" icon="url">
				<bio>All about Martial.</bio>
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
				<bio>All about Agricola.</bio>
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Caeser" icon="url">
				<bio>All about Caeser.</bio>
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
			<author name="Pliny" icon="url">
				<bio>All about Pliny.</bio>
				<work name="Work 1" url="url" />
				<work name="Work 2" url="url" />
				<work name="Work 3" url="url" />
			</author>
		</prokope>
EOT
		); 

	}

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

	/** Dummy method to register a user. The format should be:
	 *  rest/register/username/USERNAME/password/PASSWORD/professor/PROFESSOR
	 */
	public function register()
	{
		$uri_keys = array('username', 'password', 'professor');
		$uri_vals = $this->uri->uri_to_assoc(3, $uri_keys);
		if ( $uri_vals['username'] == 'true' ) {
			print("<result>1</result>\n");
		}
		else {
			print("<result>-1</result>\n");
		}


/*		echo $uri_vals['username'];	
		echo $uri_vals['password'];	
		echo $uri_vals['professor'];	 */
	}

	/** A test method that simply echos back the input that is given. */
	public function test($data)
	{
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");
		print("<result>" . $data . "</result>");
	}

}
