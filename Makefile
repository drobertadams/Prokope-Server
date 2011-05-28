# Uploads the project to cis.gvsu.edu.
upload: 
	rsync --archive --verbose --rsh=ssh --delete \
	--exclude="log*" --exclude=config.php --exclude=database.php --exclude=.DS_Store --exclude=.git \
	--exclude=user_guide . eos04.cis.gvsu.edu:public_html/Prokope
