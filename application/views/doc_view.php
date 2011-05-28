<?php $this->load->view('header'); ?>
		
<script src="<?php echo base_url(); ?>js/show.js" type="text/javascript" ></script>

<div id="error">
<?php if (isset($error)) { echo $error; } ?>
</div>

<span id="nickname" style="display: none"><?php echo $this->quickauth->user()->id; ?></span>

<div class="contentbox" id="document">
	<span id="document_id" style="display: none"><?php echo $doc->id; ?></span>
	<h2 id="title"><?php echo $doc->title; ?></h2>
	<div id="text"><?php echo $doc->content; ?></div>
</div>

<div class="contentbox" id="commentary">
	<h2>Commentary</h2>
	{{commentary}}
	<?php if ($this->quickauth->logged_in()) { ?>
			<p><a href="#" id="upload_comment_form_label">Upload Comments</a></p>
			<form id="upload_comment_form" action="<?php echo site_url("Comment/add"); ?>" enctype="multipart/form-data" method="post">
				<input type="hidden" name="document_id" value="<?php echo $doc->id; ?>" />
				File: <input type="file" name="userfile"></input> <br/>
				<input type="submit" value="Upload"></input>
			</form>
	<?php } ?>
</div>

<div class="contentbox" id="vocabulary">
	<h2>Vocabulary</h2>
	{{vocabulary}}
	{% if nickname %}
	<p><a href="#" id="upload_vocab_form_label">Upload Vocabulary</a></p>
	<form id="upload_vocab_form" action="/vocab" enctype="multipart/form-data" method="post">
		<input type="hidden" name="doc_key" value="" />
	    File: <input type="file" name="vocabulary"/> <br/>
	    <input type="submit" value="Upload">
	</form>
	{% endif %}
</div>

<div class="contentbox"  id="sidebar">
	<h2>Sidebar</h2>
	{{sidebar}}
	{% if nickname %}
	<p><a href="#" id="upload_sidebar_form_label">Upload Sidebar</a></p>
	<form id="upload_sidebar_form" action="/sidebar" enctype="multipart/form-data" method="post">
		<input type="hidden" name="doc_key" value="" />
	    File: <input type="file" name="sidebar"/> <br/>
	    <input type="submit" value="Upload">
	</form>
	{% endif %}	
</div>

<?php $this->load->view('footer'); ?>
