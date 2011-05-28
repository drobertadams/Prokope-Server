<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends CI_Model {

	var $id = 0;
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

	/** Fetches a specific document. @document_id is the id of the DOCUMENT to which
	 * the comment is applied. 
	 */
	public function get_by_document($document_id)
	{
		$this->db->select('*')->from('comments')->where('document_id', $document_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
				$comment = $query->row();
				$this->id = $comment->id;
				$this->document_id = $document_id;
				$this->content = $comment->content;
				$this->created = $comment->created;
				$this->userid = $comment->userid;
		}
	}

}
?>
