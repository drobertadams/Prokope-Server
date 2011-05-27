<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends CI_Model {

	var $id = '';
 	var $title   = '';
    var $content = '';
    var $created = '';
	var $userid   = '';

	/** Adds a new document to the database. 
	  * $title is the document's title
	  * $content is the documents' content.
	  */
	public function add($title, $content)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->title = $title;
		$this->content = $content;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;

		$this->db->insert('documents', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	}

	/** Fetches a specific document. @id is the id of the document. */
	public function get($id)
	{
		$this->db->select('*')->from('documents')->where('id', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
				$doc = $query->row();
				$this->id = $doc->id;
				$this->title = $doc->title;
				$this->content = $doc->content;
				$this->created = $doc->created;
				$this->userid = $doc->userid;
		}
	}

	/** Fetches all the documents associated with the currently logged in user. 
	  * Returns an array of objects with id and title. */
	public function usersdocs()
	{
		// Get the user's id.
		$userid = $this->quickauth->user()->id;

		// Get the information.
		$this->db->select('id, title')->from('documents')->where('userid', $userid);
		$query = $this->db->get();

		return $query->result();
	}
}
?>
