<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My custom document helper.
 */

// ------------------------------------------------------------------------
/** Utility function to fetch a document and all associated components. 
 * Returns a array of {document, comment, vocabulary, sidebar} containing
 * models.
 * @id is the id of the document.
 */
if ( ! function_exists('get_document_components'))
{
	function get_document_components($id)
	{
		$CI =& get_instance();

		// Fetch the document.
		$CI->load->model('Document_model');
		$CI->Document_model->get($id);

		// ### Massage the document to make it suitable for display. ###
        // Force a line break after each line.
        $CI->Document_model->content = str_replace("</l>", "</l><br/>", $CI->Document_model->content);
        // Make sure that "indent"ed lines are indented. &#160; is the XML version of &nbsp.
        $CI->Document_model->content = str_replace('rend="indent">', 
			'rend="indent">&#160;&#160;&#160;&#160;&#160;&#160;', $CI->Document_model->content);
		// Convert words (w) to hyperlinks (a href).
		// ims turns on the case-insensitive, multiline, dot matches newline flags.
		$CI->Document_model->content = preg_replace('/<w\s+id="([0-9.]+)">([^>]+)<\/w>/ims', 
			'<a href="$1">$2</a>', $CI->Document_model->content);

		// Fetch the associated commentary.
		$CI->load->model('Comment_model');
		$CI->Comment_model->get_by_document($CI->Document_model->id);
		// If there are no comments, display "None".
		if ( $CI->Comment_model->id == 0 ) {
			$CI->Comment_model->content = "None";
		}
        // Convert "comment" to "li" (list items).
		$CI->Comment_model->content = str_replace('<comment ', '<li ', $CI->Comment_model->content);
		$CI->Comment_model->content = str_replace("</comment>", "</li>", $CI->Comment_model->content);

		// Fetch the associated vocabulary.
		$CI->load->model('Vocabulary_model');
		$CI->Vocabulary_model->get_by_document($CI->Document_model->id);
		// If there is no vocabulary, display "None".
		if ( $CI->Vocabulary_model->id == 0 ) {
			$CI->Vocabulary_model->content = "None";
		}
        // Convert "vocab" to "li" (list items).
		$CI->Vocabulary_model->content = str_replace('<vocab ', '<li ', $CI->Vocabulary_model->content);
		$CI->Vocabulary_model->content = str_replace("</vocab>", "</li>", $CI->Vocabulary_model->content);


		// Fetch the associated sidebar.
		$CI->load->model('Sidebar_model');
		$CI->Sidebar_model->get_by_document($CI->Document_model->id);
		// If there is no sidebar, display "None".
		if ( $CI->Sidebar_model->id == 0 ) {
			$CI->Sidebar_model->content = "None";
		}

		// Return everything.
		return array('document' => $CI->Document_model, 
					 'comment' => $CI->Comment_model, 
					 'vocabulary' => $CI->Vocabulary_model,
					 'sidebar' => $CI->Sidebar_model);
	}

}
