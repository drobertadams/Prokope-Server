<?php $this->load->view('header'); ?>
		
<div class="contentbox">
<h2>Register</h2>
<form action="<?php echo site_url("/User/register_handler") ?>" method="post">
	<table>
		<tr> <th>Email</th> <th>Password</th> <th>First Name</th> <th>Last Name</th> </tr>
		<tr> 
			<td> <input type="text" name="username" /> </td>
			<td> <input type="text" name="password" /> </td>
			<td> <input type="text" name="firstname" /> </td>
			<td> <input type="text" name="lastname" /> </td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center"> <input type="submit" /> </td>
		</tr>
	</table>
</form>
</div>
	
<?php $this->load->view('footer'); ?>
