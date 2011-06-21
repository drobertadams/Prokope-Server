<?php $this->load->view('header'); ?>
		
<script src="<?php echo base_url(); ?>js/index.js" type="text/javascript"></script>

<div id="error">
<?php echo $this->session->flashdata('error'); ?>
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
<?php 

// Some helper functions.

// Function to find a document's title by id from a list of documents.
// Returns "" or "in title"
function get_doc_title($docs, $id)
{
	for ($i = 0; $i < count($docs); $i++)
		if ( $docs[$i]->id == $id )
			return "in " . $docs[$i]->title;
	return "";
}

// Function to find an author's name by id from a list of authors.
// Returns "" or "by title"
function get_author_name($authors, $id)
{
	for ($i = 0; $i < count($authors); $i++)
		if ( $authors[$i]->id == $id )
			return "by " . $authors[$i]->name;
	return "";
}

// If we have a list of documents, display them as links.
if ( isset($docs) ) { 
	echo "<ul>";
	foreach ($docs as $doc) { 
		echo "<li><a href=\"" . site_url("Document/view/$doc->id") . "\">$doc->title</a> " . 
			get_doc_title($docs, $doc->parentid) . " " .get_author_name($authors, $doc->authorid) . 
			"</li>";
	} 
	echo "</ul>";
} 
?>

<?php 
	// If the user is logged in, display a document upload form.
	if ($this->quickauth->logged_in()) { ?>
	<p><a href="#" id="upload_form_label">Upload New Document</a></p>
	<form id="upload_form" action="<?php echo site_url("Document/add");?>" enctype="multipart/form-data" method="post">
	<table>
	<tr>
		<td>Title:</td> <td> <input type="text" name="title" /> </td>
	</tr>
	<tr>
		<td>Author:</td> <td><select name="author">
			<?php foreach ($authors as $author) {
				echo "<option value=\"$author->id\">$author->name</option>";
			}
			?>
		</select></td>
	</tr>
	<tr>
		<td>Parent document:</td> <td> <select name="parent">
			<option value="1">None</option>
			<?php foreach ($docs as $doc) {
				if ( $doc->content == null ) {
					// Only list works that currently have no content.
					echo "<option value=\"$doc->id\">$doc->title</option>";
				}
			}
			?>
		</select></td>
	</tr>
	<tr>
		<td>File (XML only):</td><td> <input type="file" name="userfile"/></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Upload"/></td>
	</tr>
	</table>
	</form>
<?php } ?>

<h2>Authors</h2>

<?php 
// Display the list of authors
if ( isset($authors) ) { 
	echo "<ul>";
	foreach ($authors as $author) { 
		echo "<li>$author->name</li>";
	} 
	echo "</ul>";
} 
?>

<?php if ($this->quickauth->logged_in()) { ?>
	<p><a href="#" id="author_upload_form_label">Create New Author</a></p>
	<form id="author_upload_form" action="<?php echo site_url("Author/add");?>" method="post">
		<table>
		<tr>
			<td>Name</td> <td><input type="text" name="name" /></td>
		</tr>
		<tr>
			<td>Icon URL</td> <td> <input type="text" name="icon" /> </td>
		</tr>
		<tr>
			<td>Bio</td> <td><textarea name="bio" rows="10"></textarea> </td>
		</tr>
		<tr>
			<td colspan="2" ><input type="submit" value="Upload"/></td>
		</tr>
		</table>
	</form>
<?php } ?>


<?php $this->load->view('footer'); ?>
