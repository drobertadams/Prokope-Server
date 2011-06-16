<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends CI_Model {

	var $id       = '';
 	var $title    = '';
    var $content  = '';
    var $created  = '';
	var $userid   = '';
	var $authorid = '';
	var $parentid = '';

	/** Adds a new document to the database. 
	  * $title is the document's title
	  * $content is the documents' content.
	  * $authorid is the id of the author.
	  * $parentid is the id of the parent document or -1.
	  */
	public function add($title, $content, $authorid, $parentid)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->title = $title;
		$this->content = $content;
		$this->authorid = $authorid;
		$this->parentid = $parentid; 
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
				$this->authorid = $doc->authorid;
				$this->parentid = $doc->parentid;
				$this->created = $doc->created;
				$this->userid = $doc->userid;
		}
	}

	/** Fetches all the documents written by the given authorid.
	  * Returns an array of Document_model objects. */
	public function getbyauthor($authorid)
	{
		// Get the information. This returns a table of
		// parent_title, parent_id, child_title, child_id tuples. child_title and child_id will be
		// NULL if the work consists of a single document.
		$query = $this->db->query("
		select t2.title as parent_title, t2.id as parent_id, t3.title as child_title, t3.id as child_id 
		from documents as t1 
		left join documents as t2 on t2.parentid = t1.id 
		left join documents as t3 on t3.parentid = t2.id 
		where t1.title='Document' and t2.authorid=$authorid order by parent_title;
		");
		return $query->result();
	}

	/** Fetches all the documents associated with the currently logged in user. 
	  * Returns an array of Document_model objects. */
	public function usersdocs()
	{
		// Get the user's id.
		$userid = $this->quickauth->user()->id;

		// Get the information.
		$this->db->select('*')->from('documents')->where('userid', $userid)->order_by('title', 'asc');
		$query = $this->db->get();

		return $query->result();
	}
}
?>
