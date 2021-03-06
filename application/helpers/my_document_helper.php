<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My custom document helper.
 */

if ( ! function_exists('__inject_images')) {
	/* $comment is a Comment_model object */
	function __inject_images($comment)
	{
		$CI =& get_instance();

		$img = "";

		/* Convert the img element into an clickable <a href>. */
		$img = "<a type=\"media\" id=\"$comment->id\" href=\"$comment->src\"><img src=\"$comment->src\" /></a>";

		/* Now insert $img into the document. */
		$pos = strpos($CI->Document_model->content, $comment->ref); // find the ref in the document
		if ($pos > 0) {
			/* The ref should appear in an <a href="REF"...> element. $pos will refer
			 * to where that REF appears. Scan forward to the next </a> element.
			 */
			$close_a = stripos($CI->Document_model->content, "</a>", $pos);
			$close_a += 4; // move over the "</a>" characters.
			/* Now insert the image. */
			$CI->Document_model->content = substr_replace($CI->Document_model->content, $img, $close_a, 0);
		}
	}
}

if ( ! function_exists('__inject_videos')) {
	/* $comment is a Comment_model object */
	function __inject_videos($comment)
	{
		$CI =& get_instance();

		$tag = "";

		/* Create a clickable links. */
		$url = base_url() . "images/video.jpg";
		$tag = "<a type=\"media\" id=\"$comment->id\" href=\"$comment->src\"><img src=\"$url\" /></a>";

		/* Now insert $tag into the document. */
		$pos = strpos($CI->Document_model->content, $comment->ref); // find the ref in the document
		if ($pos > 0) {
			/* The ref should appear in an <a href="REF"...> element. $pos will refer
			 * to where that REF appears. Scan forward to the next </a> element.
			 */
			$close_a = stripos($CI->Document_model->content, "</a>", $pos);
			$close_a += 4; // move over the "</a>" characters.
			/* Now insert the image. */
			$CI->Document_model->content = substr_replace($CI->Document_model->content, $tag, $close_a, 0);
		}
	}
}

if ( ! function_exists('__inject_audios')) {
	/* $comment is a Comment_model object */
	function __inject_audios($comment)
	{
		$CI =& get_instance();

		$tag = "";

		/* Create a clickable links. */
		$url = base_url() . "images/audio.jpg";
		$tag = "<a type=\"media\" id=\"$comment->id\" href=\"$comment->src\"><img src=\"$url\" /></a>";

		/* Now insert $tag into the document. */
		$pos = strpos($CI->Document_model->content, $comment->ref); // find the ref in the document
		if ($pos > 0) {
			/* The ref should appear in an <a href="REF"...> element. $pos will refer
			 * to where that REF appears. Scan forward to the next </a> element.
			 */
			$close_a = stripos($CI->Document_model->content, "</a>", $pos);
			$close_a += 4; // move over the "</a>" characters.
			/* Now insert the image. */
			$CI->Document_model->content = substr_replace($CI->Document_model->content, $tag, $close_a, 0);
		}
	}
}

if ( ! function_exists('__inject_maps')) {
	/* $comment is a Comment_model object */
	function __inject_maps($comment)
	{
		$CI =& get_instance();

		$tag = "";

		/* Create a clickable links. */
		$url = base_url() . "images/map.jpg";
		$tag = "<a type=\"media\" id=\"$comment->id\" href=\"$comment->src\"><img src=\"$url\" /></a>";

		/* Now insert $tag into the document. */
		$pos = strpos($CI->Document_model->content, $comment->ref); // find the ref in the document
		if ($pos > 0) {
			/* The ref should appear in an <a href="REF"...> element. $pos will refer
			 * to where that REF appears. Scan forward to the next </a> element.
			 */
			$close_a = stripos($CI->Document_model->content, "</a>", $pos);
			$close_a += 4; // move over the "</a>" characters.
			/* Now insert the image. */
			$CI->Document_model->content = substr_replace($CI->Document_model->content, $tag, $close_a, 0);
		}
	}
}




// ------------------------------------------------------------------------
/** Utility function to fetch a document and all associated components. 
 * Returns an array of {document, comment, vocabulary, sidebar} containing
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

		// Massage the document to make it suitable for display. ###
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
		$comments = $CI->Comment_model->get_by_document($CI->Document_model->id);
		// If there are no comments, display "None".
		if ( count($comments) == 0 ) {
			$comments = array();
			$comment = new Comment_model;
			$comment->content = "None";
			array_push($comments, $comment);
		}
		// Find and remove all comments with type="image" and inject them into the document.
		foreach (array_keys($comments) as $i) {
			if ($comments[$i]->type == "image") {
				__inject_images($comments[$i]);
				unset($comments[$i]); // remove from the array
			}
		} 
		// Find and remove all comments with type="video" and inject them into the document.
		foreach (array_keys($comments) as $i) {
			if ($comments[$i]->type == "video") {
				__inject_videos($comments[$i]);
				unset($comments[$i]); // remove from the array
			}
		} 
		// Find and remove all comments with type="audio" and inject them into the document.
		foreach (array_keys($comments) as $i) {
			if ($comments[$i]->type == "audio") {
				__inject_audios($comments[$i]);
				unset($comments[$i]); // remove from the array
			}
		} 
		// Find and remove all comments with type="map" and inject them into the document.
		foreach (array_keys($comments) as $i) {
			if ($comments[$i]->type == "map") {
				__inject_maps($comments[$i]);
				unset($comments[$i]); // remove from the array
			}
		} 





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
					 'comments' => $comments, 
					 'vocabulary' => $CI->Vocabulary_model,
					 'sidebar' => $CI->Sidebar_model);
	}

}
