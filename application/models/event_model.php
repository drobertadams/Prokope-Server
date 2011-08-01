<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {

	var $id = '';
	var $type = '';
    var $created = '';
	var $userid   = '';
	var $document_id = '';
	var $comment_id = '';
	var $word_id = '';

	/** 
	 * Adds a new event.
	 * $created should be a string of the form 'Y-m-d H:i:s'
	 */
	public function add($type, $created, $userid, $document_id, $comment_id=NULL, $word_id=NULL)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->type = $type;
		$this->created = $created;
		$this->userid = $userid;
		$this->document_id = $document_id;
		$this->comment_id = $comment_id;
		$this->word_id = $word_id;

		$this->db->insert('events', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	} 

}
?>
