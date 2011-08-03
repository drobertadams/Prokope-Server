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

	/**
	 * Logs user activity to the DB.
	 * Assumes data comes in via POST with the format:
	 * <entries user="USERNAME">
	 *    <like date="2011-07-25 19:42:54" doc="DOCUMENTID" comment="COMMENTID" />
	 *    <dislike date="2011-07-25 19:42:54" doc="DOCUMENTID" comment="COMMENTID" />
	 * 	  <media date="2011-08-03 08:24:00" doc="DOCUMENTID" comment="COMMENTID" />
	 *    <click date="2011-07-25 19:42:54" doc="DOCUMENTID" word="WORDID" />
	 *	  <follow date="2011-08-03 08:27:00" doc="DOCUMENTID" url="URL" />
	 * </entries>
	 * Where:
	 *		USERNAME is the email address of the user
	 *		DOCUMENTID is the (int) unique document id
	 *		COMMENTID is the (int) unique comment id
	 * 		WORDID is the id of the word within the document (usually of the form "10.2.1.14")
	 *		URL is the URL that was followed
	 * Returns "<result>1</result>" on success, 
	 * 		<result>-1</result> on user not found,
	 * 		<result>-2</result> on a runtime exception (probably malformed XML).
	 */
	public function log()
	{
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		try {
			/* Grab the XML document inside the HTML body. */
			$xml = @file_get_contents('php://input');
			libxml_use_internal_errors(true);
			$entries = new SimpleXMLElement($xml);
			if ( ! $entries ) {
				print("<result>-2</result>");
				return;
			}

			/* Find the user's id. */
			$userid = -1;
			$this->db->select('id')->from('users')->where('username', (string) $entries['user']);
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
				$row = $q->row();
				$userid = $row->id;
			}
			if ($userid == -1) {
				print("<result>-1</result>");
				return;
			}
			
			$this->load->model("Event_model");
			foreach ($entries->children() as $child) {
				if ($child->getName() == 'like' || $child->getName() == 'dislike' || 
					$child->getName() == 'media') {
					$this->Event_model->add($child->getName(), (string) $child['date'], $userid, 
						(string) $child['doc'], (string) $child['comment'], NULL);
				}
				else if ($child->getName() == 'click') {
					$this->Event_model->add($child->getName(), (string) $child['date'], $userid, 
						(string) $child['doc'], NULL, (string) $child['word'], NULL);
				}
				else if ($child->getName() == 'follow') {
					$this->Event_model->add($child->getName(), (string) $child['date'], $userid, 
						(string) $child['doc'], NULL, NULL, (string) $child['url']);
				}

			} 
			print("<result>1</result>");
		} catch (Exception $e) {
			print("<result>-2</result>");
		}
		
	}

	/**
	 * Testing function. Dumps out all the events in the database.
	 */
	public function events()
	{
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		/* Get the events. */
		$this->load->model("Event_model");
		$events = $this->Event_model->get();

		/* Generate XML. */
		print "<events>\n";
		foreach ($events as $event) {
			printf("<%s date=\"%s\" doc=\"%d\" ", $event->type, $event->created, $event->document_id);
			switch ($event->type) {
				case "like":
				case "dislike":
				case "media":
					printf("comment=\"%d\"", $event->comment_id);
					break;
				case "click":
					printf("word=\"%s\"", $event->word_id);
					break;
				case "follow":
					printf("url=\"%s\"", $event->url);
					break;
			}
			print " />\n";
		}
		print "</events>\n";

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
	 * rest/update/oldusername/OLDUSERNAME/newusername/NEWUSERNAME/\
	 *    oldpassword/OLDPASSWORD/newpassword/NEWPASSOWRD/professor/PROFESSOR
	 * Returns <result>N</result> where
	 *		N=-4 : newusername already in use
	 *		N=-3 : permissions denied
	 *		N=-2 : error in URI string
	 *		N=-1 : user not found
	 *		N=1  : success
	 */
	public function update()
	{
		/* URI field names. */
		$OLDUSERNAME = "oldusername";
		$NEWUSERNAME = "newusername";
		$OLDPASSWORD = "oldpassword";
		$NEWPASSWORD = "newpassword";
		$PROFESSOR = "professor";
		
		/* Tell the browser we're outputting XML. */
		$this->output->set_header("Content-type: application/xml; charset=UTF-8");

		/* Decompose the URI string into elements and decode them. */
		$uri_keys = array($OLDUSERNAME, $NEWUSERNAME, $OLDPASSWORD, $NEWPASSWORD, $PROFESSOR);
		$uri_vals = $this->uri->uri_to_assoc(3, $uri_keys);
		$uri_vals[$OLDUSERNAME] = urldecode($uri_vals[$OLDUSERNAME]);
		$uri_vals[$NEWUSERNAME] = urldecode($uri_vals[$NEWUSERNAME]);
		$uri_vals[$OLDPASSWORD] = urldecode($uri_vals[$OLDPASSWORD]);
		$uri_vals[$NEWPASSWORD] = urldecode($uri_vals[$NEWPASSWORD]);
		$uri_vals[$PROFESSOR] = urldecode($uri_vals[$PROFESSOR]);

		/* Make sure all three components are listed. If not, fail. */
		if ( ! $uri_vals[$OLDUSERNAME] || ! $uri_vals[$NEWUSERNAME] || 
			! $uri_vals[$OLDPASSWORD] || ! $uri_vals[$NEWPASSWORD] || ! $uri_vals[$PROFESSOR] ) {
			print("<result>-2</result>");
			return;
		}

		/* Make sure the  user is changing their own entry. */
		if ( ! $this->quickauth->login( $uri_vals[$OLDUSERNAME], $uri_vals[$OLDPASSWORD] ) ) {
			print("<result>-3</result>");
			exit;
		}

		/* If the username is changing, make sure the new one is unused. */
		if ( $uri_vals[$NEWUSERNAME] != $uri_vals[$OLDUSERNAME] ) {
			$this->db->select('id')->from('users')->where('username', $uri_vals[$NEWUSERNAME]);
			$q = $this->db->get();	
			if ($q->num_rows() != 0) {
				print("<result>-4</result>");
				return;
			} 
		}

		/* Get the professor's id. */
		$this->db->select('id')->from('users')->where('username', $uri_vals[$PROFESSOR]);
		$q = $this->db->get();
		$row = $q->row();
		$prof_id = $row->id;

		/* Update the user's info. */
		$userdata = array(
			'username'  => $uri_vals[$NEWUSERNAME],
			'password'  => $this->quickauth->encrypt( $uri_vals[$NEWPASSWORD] ),
			'firstname' => '',
			'lastname' 	=> '',	
			'professorid' => $prof_id
		);
		$this->db->where('username', $uri_vals[$OLDUSERNAME]);
		$this->db->update('users', $userdata);

		/* Log the user back in. */
		$this->quickauth->login($uri_vals[$NEWUSERNAME], $uri_vals[$NEWPASSWORD]);

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
