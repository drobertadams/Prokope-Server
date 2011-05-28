<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My custom download helper. All files are downloaded the same, so
 * this helper contains the code common to all.
 */

// ------------------------------------------------------------------------

/**
 * Downloads a file and returns the file contents. If there is a problem,
 * it redirects to the home page where $error is displayed.
 */
if ( ! function_exists('get_file_content'))
{
	function get_file_upload_content()
	{
		// Upload the file.
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xml';
		$config['overwrite'] = TRUE;
		$config['max_size']	= '100';

		$CI =& get_instance();
		$CI->load->library('upload', $config);

		if ( ! $CI->upload->do_upload() )
		{
			// There was a problem. Grab the error message and display it on the home page.
			$error = array('error' => $CI->upload->display_errors());
			$CI->load->view('home_page', $error);
			return;
		}

		// Upload success. Grab the file contents.
		$upload_data = $CI->upload->data();
		$CI->load->helper('file');
		$content = read_file( $upload_data['full_path'] );

		// Delete the file.
		delete_files( $upload_data['file_path'] );

		return $content;
	}
}
