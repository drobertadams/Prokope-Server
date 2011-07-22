<?php $this->output->set_header("Content-type: application/xml; charset=UTF-8"); ?>
<document>
	<text>
			<title><?php echo $document->title; ?></title>
			<author><?php echo $document->userid; ?></author>
			<content><?php echo $document->content; ?></content>
	</text>
	<commentary>
	<?php foreach ($comments as $comment) { 
		print "<li id=\"$comment->id\" ref=\"$comment->ref\" type=\"$comment->type\">";
		if ( strlen($comment->title) > 0 ) {
			print "<span class=\"title\">$comment->title</span>: ";
		}
		print "$comment->content</li>\n";
	} ?>
	</commentary>
	<?php if (isset($vocabulary)) { echo $vocabulary->content; } ?>
	<?php if (isset($sidebar)) { echo $sidebar->content; } ?>
</document>
