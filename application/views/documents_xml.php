<?php $this->output->set_header("Content-type: application/xml; charset=UTF-8"); ?>
<prokope>
<?php
	$authors = $this->Author_model->get();
	foreach ($authors as $author) {
		$docs = $this->Document_model->getbyauthor($author->id);

		// Only print authors that have works.
		if (count($docs) > 0) {

			print("<author name=\"$author->name\" icon=\"$author->icon\">\n");
			print("\t<bio>$author->bio</bio>\n");

			/* This is a rather complex bit of code created in order to avoid multiple queries to the
			 * database. Each document has a parentid that creates a hierarchy. The document_model
			 * performs several self-joins to return something like this:
			 *
			 * +--------------+-----------+--------------+----------+
			 * | parent_title | parent_id | child_title  | child_id |
			 * +--------------+-----------+--------------+----------+
			 * | Epistulae    |        17 | NULL         |     NULL |
			 * | Book X       |         8 | Epigram 10.4 |       13 |
			 * | Book X       |         8 | Epigram 10.5 |       19 |
			 * | Book XI      |        11 | Epigram 11.2 |       14 |
			 * +--------------+-----------+--------------+----------+
			 * 
			 * Stand-alone docs have no children. Otherwise, the parent-child relationship is as shown.
			 * The loops and logic below keep track of the state of the XML being output in order to
			 * correctly close parent <work> elements, etc.
			 */

			$current_parent_id = -1; 	// id of the parent currently being processed
			$in_parent = false;			// are we currently inside a parent's work element?

			foreach ($docs as $doc) {

				// We have a stand-alone document. Close the parent if we're in one, and then
				// print the current document.
				if ( $doc->child_title == "" ) {
					if ( $in_parent ) {
						print("</work>\n"); 
						$in_parent = false;
					}
					$url = site_url() . "/rest/document/$doc->parent_id";
					print("<work name=\"$doc->parent_title\" url=\"$url\" />\n");
				}

				// We have a nested document.
				else {

					// Are we seeing this document for the first time?
					if ( $doc->parent_id != $current_parent_id ) {
						// If we're already in a parent document, close it.
						if ( $in_parent ) {
							print("</work>\n");
							$in_parent = false;
						}
						// Keep track of the current parent document id.
						$current_parent_id = $doc->parent_id;
					}

					// Start the parent element if we haven't already.
					if ( ! $in_parent ) {
						print("<work name=\"$doc->parent_title\">\n");
						$in_parent = true;
					}

					// We are in the parent, so print the work.
					$url = site_url() . "/rest/document/$doc->child_id";
					print("\t<work name=\"$doc->child_title\" url=\"$url\" />\n");
				}
			}

			// We're done processing documents. If we finishined inside a parent, then close it.
			if ( $in_parent ) {
				print("</work>\n"); // end the last work
			}
			print("</author>\n");
		}
	}
?>
</prokope>
