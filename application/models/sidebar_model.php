<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar_model extends CI_Model {

	var $id = 0;
	var $document_id = 0;
    var $content = '';
    var $created = '';
	var $userid   = '';

	/** Adds new sidebar to the database. 
	  * $content is the sidebar's content.
	  * $document_id is the related document.
	  */
	public function add($content, $document_id)
	{
		unset($this->id); // hides this variable from the db library, otherwise it will be used for insert
		$this->content = $content;
		$this->document_id = $document_id;
		$this->created = date( 'Y-m-d H:i:s');
		$this->userid = $this->quickauth->user()->id;

		$this->db->insert('sidebars', $this); 
		$this->id = $this->db->insert_id();	// grab the id last inserted
	}

	/** Fetches a specific sidebar. @document_id is the id of the DOCUMENT to which
	 * the sidebar is related. 
	 */
	public function get_by_document($document_id)
	{
		$this->db->select('*')->from('sidebars')->where('document_id', $document_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
				$sidebar = $query->row();
				$this->id = $sidebar->id;
				$this->document_id = $document_id;
				$this->content = $sidebar->content;
				$this->created = $sidebar->created;
				$this->userid = $sidebar->userid;
		}
	}

}
?>
