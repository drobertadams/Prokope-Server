<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends CI_Model {

	var $id = 0;
	var $document_id = 0;
    var $content = '';
    var $created = '';
	var $userid  = '';
	var $ref     = '';
	var $type    = '';
	var $title   = '';
	var $src     = '';

	/** Adds a new comment to the database. 
	  * $content is the comment's content.
	  * $document_id is the related document.
	  * $ref is the word id to which this comment references
	  * $type is the type of comment
	  * $title is an optional title for the comment
	  * $src is an optional src uri for image comments
	  */
	public function add($content, $document_id, $ref, $type, $title="", $src="")
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->content = $content;
		$this->document_id = $document_id;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;
		$this->ref = $ref;
		$this->type = $type;
		$this->title = $title;
		$this->src = $src;

		$this->db->insert('comments', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	}

	/** Fetches a specific document. @document_id is the id of the DOCUMENT to which
	 * the comment is applied. Returns an array of Comment_model objects.
	 */
	public function get_by_document($document_id)
	{
		$this->db->select('*')->from('comments')->where('document_id', $document_id);
		$query = $this->db->get();
		$result = array();
		foreach ($query->result() as $row) {
			$comment = new Comment_model;
			$comment->id = $row->id;
			$comment->document_id = $document_id;
			$comment->content = $row->content;
			$comment->created = $row->created;
			$comment->userid  = $row->userid;
			$comment->ref = $row->ref;
			$comment->type = $row->type;
			$comment->title = $row->title;
			$comment->src = $row->src;
			array_push($result, $comment);
		}
		return $result;
	}

}
?>
