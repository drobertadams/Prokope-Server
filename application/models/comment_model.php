<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends CI_Model {

	var $id = '';
	var $document_id = 0;
    var $content = '';
    var $created = '';
	var $userid   = '';

	/** Adds a new comment to the database. 
	  * $content is the comment's content.
	  * $document_id is the related document.
	  */
	public function add($content, $document_id)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->content = $content;
		$this->document_id = $document_id;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;

		$this->db->insert('comments', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	}

	/** Fetches a specific document. @id is the id of the document. */
/*	public function get($id)
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
*/
}
?>
