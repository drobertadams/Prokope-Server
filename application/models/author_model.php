<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Author_model extends CI_Model {

	var $id = '';
 	var $name   = '';
    var $icon = '';
    var $created = '';
	var $userid   = '';
	var $bio   = '';

	/** Adds a new author to the database. 
	  * $name is the author's name.
	  * $icon is the url of the user's picture.
	  * $bio is biographical data about the author.
	  */
	public function add($name, $icon, $bio)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->name = $name;
		$this->icon = $icon;
		$this->bio = $bio;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;

		$this->db->insert('authors', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	} 

	/** Fetches all authors. */
	public function get()
	{
		$this->db->select('*')->from('authors')->order_by('name', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

}
?>
