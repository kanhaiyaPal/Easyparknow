<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php

	$db_dashboard = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

	if(isset($_POST['reset_password']) && ($_POST['reset_password']=='Change') && (isset($_POST['csrf_token'])) && ($_SESSION['crsf_protect_token_dash'] == $_POST['csrf_token']))
	{
		$db_dashboard->where ("id", $_SESSION['adminlogged']['id']);
		$user = $db_dashboard->getOne ("users");
		if($user['id']){
			if(verify_pass($_POST['old_pass'],$user['password'])){

				$db_dashboard->where ("id", $_SESSION['adminlogged']['id']);
				$data = array('password' => hash_password($_POST['rep_new_pass']));
				$id = $db_dashboard->update('users', $data);
				unset($_SESSION['adminlogged']);

				header("Location:index.php");
				exit();
			}else{
				generate_alerts_admin_pages("Old Password Incorrect");
				unset($_SESSION['crsf_protect_token_dash']);
				$_SESSION['crsf_protect_token_dash'] = generate_token();
			}
		}else{
			generate_alerts_admin_pages("User Not Found");
			unset($_SESSION['crsf_protect_token_dash']);
			$_SESSION['crsf_protect_token_dash'] = generate_token();
		}
	}else{
		unset($_SESSION['crsf_protect_token_dash']);
		$_SESSION['crsf_protect_token_dash'] = generate_token();
	}
	
?>
				<div class="col-md-6">
		  			<div class="content-box-large">
		  				<div class="panel-heading">
							<div class="panel-title">New vs Returning Visitors</div>
							
							<div class="panel-options">
								<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
								<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
							</div>
						</div>
		  				<div class="panel-body">
		  					Ut tristique adipiscing mauris, sit amet suscipit metus porta quis. Donec dictum tincidunt erat, eu blandit ligula. Nam sit amet dolor sapien. Quisque velit erat, congue sed suscipit vel, feugiat sit amet enim. Suspendisse interdum enim at mi tempor commodo. Sed tincidunt sed tortor eu scelerisque. Donec luctus malesuada vulputate. Nunc vel auctor metus, vel adipiscing odio. Aliquam aliquet rhoncus libero, at varius nisi pulvinar nec. Aliquam erat volutpat. Donec ut neque mi. Praesent enim nisl, bibendum vitae ante et, placerat pharetra magna. Donec facilisis nisl turpis, eget facilisis turpis semper non. Maecenas luctus ligula tincidunt iasdsd vitae ante et, 
				  			<br /><br />
				  			Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque sed consectetur erat. Maecenas in elementum libero. Sed consequat pellentesque ultricies. Ut laoreet vehicula nisl sed placerat. Duis posuere lectus n, eros et hendrerit pellentesque, ante magna condimentum sapien, eget ultrices eros libero non orci. Etiam varius diam lectus.
							<br /><br />
		  				</div>
		  			</div>
		  		</div>

		  		<div class="col-md-6">
		  			<div class="row">
		  				<div class="col-md-12">
		  					<div class="content-box-header">
			  					<div class="panel-title">Change Password</div>
				  			</div>
				  			<div class="content-box-large box-with-header">
				  				<div class="alert alert-warning">Changing the password will log you out from current session</div>
				  				<form method="post" action="" class="form-horizontal" >
				  					<div class="form-group">
				  						<label>Current Password</label>
				  						<input class="form-control" type="Password" name="old_pass" required="required">
				  					</div>
				  					<div class="form-group">
				  						<label>New Password</label>
				  						<input class="form-control" type="Password" id="password" name="new_pass" >
				  					</div>
				  					<div class="form-group">
				  						<label>Repeat New Password</label>
				  						<input class="form-control" type="Password" id="confirm_password" name="rep_new_pass">
				  					</div>
				  					<input type="hidden" name="csrf_token" value="<?=$_SESSION['crsf_protect_token_dash']?>">
				  					<input type="submit" name="reset_password" class="btn btn-primary" value="Change">
				  					<input type="reset" name="reset_password_form" class="btn btn-default" value="Reset">
				  				</form>
					  			<br /><br />
							</div>
		  				</div>
		  			</div>
		  		</div>
<script type="text/javascript">

var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");

function validatePassword(){
if(password.value != confirm_password.value) {
confirm_password.setCustomValidity("Passwords Don't Match");
} else {
confirm_password.setCustomValidity('');
}
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>