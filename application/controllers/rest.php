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

	/** Logs in a user. The URI format should be:
	 *  rest/login/username/USERNAME/password/PASSWORD
	 *  USERNAME is the user's email
	 *  Returns <result>X</result> where
	 *		N=professor : on success
	 *		N=-1 		: fail
	 *		N=-2 		: malformed URL
	 */
	public function login()
	{
		/* URI field names. */
		$USERNAME = "username";
		$PASSWORD = "password";
		
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		/* Decompose the URI string into elements and decode them. */
		$uri_keys = array($USERNAME, $PASSWORD);
		$uri_vals = $this->uri->uri_to_assoc(3, $uri_keys);
		$uri_vals[$USERNAME] = urldecode($uri_vals[$USERNAME]);
		$uri_vals[$PASSWORD] = urldecode($uri_vals[$PASSWORD]);

		/* Make sure both components are listed. If not, fail. */
		if ( ! $uri_vals[$USERNAME] || ! $uri_vals[$PASSWORD] ) {
			print("<result>-2</result>");
			return;
		}

		/* Try to log in. */
		if ( ! $this->quickauth->login( $uri_vals[$USERNAME], $uri_vals[$PASSWORD] ) ) {
			print("<result>-1</result>");
			return;
		}

		/* Login is successful. Get the professor's name. */
		$userid = $this->session->userdata('userid');
		$q = $this->db->query("select prof.username from users as prof join users as user on user.professorid=prof.id where user.id=$userid");

		$professorname = ""; // default empty
		if ($q->num_rows() > 0) {
			$row = $q->row();
			$professorname = $row->username;
		}
		print("<result>$professorname</result>");
	}
	
	/** Returns a list of professors. */
	public function professor()
	{
		/* Get the group id of the "professor" group. */
		$this->db->select('*')->from('groups')->where('title', 'professor');
		$q = $this->db->get();
		$data = $q->row();
		$id = $data->id;
		
		/* Get a list of users that are professors. */
		$this->db->select('*')->from('users')->
			join('group_memberships', 'users.id=group_memberships.userid')->where('groupid', $id);
		$q = $this->db->get();
		$profs = $q->result();

		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");
		print("<professors>\n");

		foreach ($profs as $prof) {
			print("<professor username=\"$prof->username\" fullname=\"$prof->lastname, $prof->firstname\" id=\"$prof->userid\" />\n");
		}
		print("</professors>\n");

	}

	/** Register a user. The URI format should be:
	 *  rest/register/username/USERNAME/password/PASSWORD/professor/PROFESSOR
	 *  USERNAME is the user's email, PROFESSOR is the username (email) of the professor.
	 *  Returns <result>N</result> where
	 *		N=-2 : error in URI string
	 *		N=-1 : user already registered
	 *		N=1  : success
	 */
	public function register()
	{
		/* URI field names. */
		$USERNAME = "username";
		$PASSWORD = "password";
		$PROFESSOR = "professor";
		
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		/* Decompose the URI string into elements and decode them. */
		$uri_keys = array($USERNAME, $PASSWORD, $PROFESSOR);
		$uri_vals = $this->uri->uri_to_assoc(3, $uri_keys);
		$uri_vals[$USERNAME] = urldecode($uri_vals[$USERNAME]);
		$uri_vals[$PASSWORD] = urldecode($uri_vals[$PASSWORD]);
		$uri_vals[$PROFESSOR] = urldecode($uri_vals[$PROFESSOR]);

		/* Make sure all three components are listed. If not, fail. */
		if ( ! $uri_vals[$USERNAME] || ! $uri_vals[$PASSWORD] || ! $uri_vals[$PROFESSOR] ) {
			print("<result>-2</result>");
			return;
		}

		/* If already registered, fail. */
		$this->db->select('id')->from('users')->where('username', $uri_vals[$USERNAME]);
		$q = $this->db->get();	
		if ($q->num_rows() > 0) {
			print("<result>-1</result>");
			return;
		} 

		/* Register the basic user info. */
		$userdata = array(
			'type'      => array(2), // Users registered RESTfully go into group "2" (user).
			'username'  => $uri_vals[$USERNAME],
			'password'  => $uri_vals[$PASSWORD],
			'firstname' => '',
			'lastname' 	=> ''	
		);
		$this->quickauth->register($userdata);

		/* Get the professor's id. */
		$this->db->select('id')->from('users')->where('username', $uri_vals[$PROFESSOR]);
		$q = $this->db->get();
		$row = $q->row();
		$prof_id = $row->id;

		/* Update the "professorid" field for the newly added user. */
		$data = array( 'professorid' => $prof_id);
		$this->db->where('username', $uri_vals[$USERNAME]);
		$this->db->update('users', $data);

		/* Success! */
		print("<result>1</result>\n");
	}

	/** 
	 * Update a user's registration information. The URI format should be:
	 * rest/update/oldusername/OLDUSERNAME/newusername/NEWUSERNAME/password/PASSWORD/professor/PROFESSOR
	 * Returns <result>N</result> where
	 *		N=-2 : error in URI string
	 *		N=-1 : user not found
	 *		N=1  : success
	 */
	public function update()
	{
		/* URI field names. */
		$OLDUSERNAME = "oldusername";
		$NEWUSERNAME = "newusername";
		$PASSWORD = "password";
		$PROFESSOR = "professor";
		
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		/* Decompose the URI string into elements and decode them. */
		$uri_keys = array($OLDUSERNAME, $NEWUSERNAME, $PASSWORD, $PROFESSOR);
		$uri_vals = $this->uri->uri_to_assoc(3, $uri_keys);
		$uri_vals[$OLDUSERNAME] = urldecode($uri_vals[$OLDUSERNAME]);
		$uri_vals[$NEWUSERNAME] = urldecode($uri_vals[$NEWUSERNAME]);
		$uri_vals[$PASSWORD] = urldecode($uri_vals[$PASSWORD]);
		$uri_vals[$PROFESSOR] = urldecode($uri_vals[$PROFESSOR]);

		/* Make sure all three components are listed. If not, fail. */
		if ( ! $uri_vals[$OLDUSERNAME] || ! $uri_vals[$NEWUSERNAME] || 
			! $uri_vals[$PASSWORD] || ! $uri_vals[$PROFESSOR] ) {
			print("<result>-2</result>");
			return;
		}

		/* If not already registered, fail. */
		$this->db->select('id')->from('users')->where('username', $uri_vals[$OLDUSERNAME]);
		$q = $this->db->get();	
		if ($q->num_rows() == 0) {
			print("<result>-1</result>");
			return;
		} 

		/* Get the professor's id. */
		$this->db->select('id')->from('users')->where('username', $uri_vals[$PROFESSOR]);
		$q = $this->db->get();
		$row = $q->row();
		$prof_id = $row->id;

		/* Update the user's info. */
		$userdata = array(
			'username'  => $uri_vals[$NEWUSERNAME],
			'password'  => $this->quickauth->encrypt( $uri_vals[$PASSWORD] ),
			'firstname' => '',
			'lastname' 	=> '',	
			'professorid' => $prof_id
		);
		$this->db->where('username', $uri_vals[$OLDUSERNAME]);
		$this->db->update('users', $userdata);

		/* Success! */
		print("<result>1</result>\n");
	}


	/** A test method that simply echos back the input that is given. */
	public function test($data)
	{
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");
		print("<result>" . $data . "</result>");
	}

}

?>
