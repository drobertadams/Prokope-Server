<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for comments. */
class Comment extends CI_Controller {

	/** Adds a comment. */
	public function add()
	{
		// Load my helper that returns downloaded file content.
		$this->load->helper('my_download_helper');
		$content = get_file_upload_content(); 

		// Convert each comment element into a row in the db.
		$this->load->model('Comment_model');

		$xml = new SimpleXMLElement($content);
		foreach ($xml->comment as $comment) {
			$ref = $comment['ref'];
			$type = $comment['type'];
			$title = $comment['title'];

			// Strip off the <comment> tags from the comment.
			$commentText = (string) $comment->asXML();
			$commentText = preg_replace("/<\/?comment[^>]*>/", "", $commentText);

			// Add the comment to the DB.
			$this->Comment_model->add($commentText, $this->input->post('document_id'), 
				(string) $comment['ref'], (string) $comment['type'], (string) $comment['title'],
				(string) $comment['src']);
		}

		// We're done. Delete the file and return to the document view.
		redirect( site_url( 'Document/view/' . $this->input->post('document_id') ) ); 
	}

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}
}
?>
