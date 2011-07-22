<?php $this->load->view('header'); ?>
		
<script src="<?php echo base_url(); ?>js/show.js" type="text/javascript" ></script>

<div id="error">
<?php if (isset($error)) { echo $error; } ?>
</div>

<span id="nickname" style="display: none"><?php echo $this->quickauth->user()->id; ?></span>

<div class="contentbox" id="document">
	<span id="document_id" style="display: none"><?php echo $document->id; ?></span>
	<h2 id="title"><?php echo $document->title; ?></h2>
	<div id="text"><?php echo $document->content; ?></div>
</div>

<div class="contentbox" id="commentary">
	<h2>Commentary</h2>
	<?php foreach ($comments as $comment) { 
		echo "<li id=\"$comment->id\" ref=\"$comment->ref\" type=\"$comment->type\">";
		if ( strlen($comment->title) > 0 ) {
			echo "<span class=\"title\">$comment->title</span>: ";
		}
		echo "$comment->content</li>\n";
	} ?>
	<?php // Display the upload form if the user is logged in and there are no comments already.
		  if ($this->quickauth->logged_in() and $comment->id == 0 ) { ?>
			<p><a href="#" id="upload_comment_form_label">Upload Comments</a></p>
			<form id="upload_comment_form" action="<?php echo site_url("Comment/add"); ?>" enctype="multipart/form-data" method="post">
				<input type="hidden" name="document_id" value="<?php echo $document->id; ?>" />
				File: <input type="file" name="userfile"></input> <br/>
				<input type="submit" value="Upload"></input>
			</form>
	<?php } ?>
</div>

<div class="contentbox" id="vocabulary">
	<h2>Vocabulary</h2>
	<?php if (isset($vocabulary)) { echo $vocabulary->content; } ?>
	<?php // Display the upload form if the user is logged in and there is no vocabulary already.
		  if ($this->quickauth->logged_in() and $vocabulary->id == 0 ) { ?>
			<p><a href="#" id="upload_vocab_form_label">Upload Vocabulary</a></p>
			<form id="upload_vocab_form" action="<?php echo site_url("Vocabulary/add"); ?>" enctype="multipart/form-data" method="post">
				<input type="hidden" name="document_id" value="<?php echo $document->id; ?>" />
				File: <input type="file" name="userfile"/> <br/>
				<input type="submit" value="Upload">
			</form>
	<?php } ?>
</div>

<div class="contentbox"  id="sidebar">
	<h2>Sidebar</h2>
	<?php if (isset($sidebar)) { echo $sidebar->content; } ?>
	<?php // Display the upload form if the user is logged in and there is no vocabulary already.
		  if ($this->quickauth->logged_in() and $sidebar->id == 0 ) { ?>
			<p><a href="#" id="upload_sidebar_form_label">Upload Sidebar</a></p>
			<form id="upload_sidebar_form" action="<?php echo site_url("Sidebar/add"); ?>" enctype="multipart/form-data" method="post">
				<input type="hidden" name="document_id" value="<?php echo $document->id; ?>" />
				File: <input type="file" name="userfile"/> <br/>
				<input type="submit" value="Upload">
			</form>
	<?php } ?>
</div>

<?php $this->load->view('footer'); ?>
