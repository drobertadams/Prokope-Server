<?php $this->load->view('header'); ?>
		
<script src="<?php echo base_url(); ?>js/index.js" type="text/javascript"></script>

<div id="error">
<?php if (isset($error)) { echo $error; } ?>
</div>

<div class="contentbox">
	<h2>Welcome</h2>
	<p>The Prokope Project is an experimental project aimed at
	investigating how scholars and students interact with classical
	texts, providing their own translations, annotations, and
	commentary.</p>
</div>

<br/>

<h2>My Documents</h2>
<ul id="doc_list">
</ul>

<?php if ($this->quickauth->logged_in()) { ?>
	<p><a href="#" id="upload_form_label">Upload New Document</a></p>
	<form id="upload_form" action="<?php echo site_url("Document/add");?>" enctype="multipart/form-data" method="post">
		Title: <input type="text" name="title" /> <br/>
		File: <input type="file" name="userfile"/> <br/>
		<input type="submit" value="Upload">
	</form>
<? } ?>

<?php $this->load->view('footer'); ?>
