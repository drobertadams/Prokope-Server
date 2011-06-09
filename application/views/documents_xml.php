<?php $this->output->set_header("Content-type: application/xml; charset=UTF-8"); ?>
<prokope>
<?php
	$authors = $this->Author_model->get();
	foreach ($authors as $author) {
		$docs = $this->Document_model->getbyauthor($author->id);
		if (count($docs) > 0) {
			// Only print authors that have works.
			print("<author name=\"$author->name\" icon=\"$author->icon\">");
			foreach ($docs as $doc) {
				$url = site_url() . "/rest/document/$doc->id";
				print("<work name=\"$doc->title\" url=\"$url\" />");
			}
			print("</author>");
		}
	}
?>
</prokope>
