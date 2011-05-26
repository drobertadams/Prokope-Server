<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends CI_Model {

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
		$this->title = $title;
		$this->content = $content;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;

		$this->db->insert('documents', $this); 
	}
}
?>
