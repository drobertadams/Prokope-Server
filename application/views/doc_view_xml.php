<?php $this->output->set_header("Content-type: application/xml; charset=UTF-8"); ?>
<document>
	<text>
			<title><?php echo $document->title; ?></title>
			<author><?php echo $document->userid; ?></author>
			<content><?php echo $document->content; ?></content>
	</text>
	<?php if (isset($comment)) { echo $comment->content; } ?>
	<?php if (isset($vocabulary)) { echo $vocabulary->content; } ?>
	<?php if (isset($sidebar)) { echo $sidebar->content; } ?>
</document>
