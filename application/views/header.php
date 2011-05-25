<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
	<title>Prokope</title>
	<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet" type="text/css" />	
	<link href="<?php echo base_url(); ?>css/smoothness/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />	
	<script src="<?php echo base_url(); ?>js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/base.js" type="text/javascript"></script>
</head>

<body>
	
	<div id="header">	
			
		<div id="loginarea">
				<?php if ($this->quickauth->logged_in()) { ?>
					Welcome <?php echo $this->quickauth->user()->firstname; ?> 
					(<a href="<?php echo site_url("User/logout");?>">Sign out</a>)
				<?php } else { ?>
					<form action="<?php echo site_url("/User/login") ?>" method="post">
						<table>
							<tr> <td>Email:</td> <td><input type="input" name="username" /></td> </tr>
							<tr> <td>Password: </td> <td> <input type="password" name="password" /> </td> </tr>
							<tr> <td><input type="submit" value="Sign in"/> </td> </tr>
						</table>
					</form>
				<?php } ?>
		</div>		
		
		<h1><a href="<?php echo base_url(); ?>">Prokope</a></h1>
		<div class="description">Advancement in the Face of Adversity</div>
		
	</div>
	
	<div id="mainarea">

