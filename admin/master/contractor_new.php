<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }  ?>
<span><b>
<?php	
	if (isset($_POST['add_new_contractor']) && ($_POST['add_new_contractor']== 'Save')) {
		if(isset($_SESSION['csrf_token_contractor']) && ($_SESSION['csrf_token_contractor']==$_POST['csrf_token_contractor']))
		{
			$db_handler = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

			$db_handler->where ("email", $_POST['contra_mail']);
			$user = $db_handler->getOne ("users");
			if($user['id']){
				echo 'Email Id already exist in database!';
			}

			$password_hash = hash_password($_POST['contra_pass']);

			$data = Array (
							"name" => $_POST['contra_name'],
						   	"email" => $_POST['contra_mail'],
						   	"username" => $_POST['contra_mail'],
						   	"password" => $password_hash,
						   	"added_date" => date('d-m-Y'),
						   	"user_type" => '2'
					);
			$id = $db_handler->insert ('users', $data);
		}else{
			echo 'Unable to verify security token.Please try again';
			unset($_POST);
		}
	}else{
		$csrf_token = generate_token();
		unset($_SESSION['csrf_token_contractor']);
		$_SESSION['csrf_token_contractor'] = $csrf_token;
	}
?></b></span>
<div class="panel-heading">
    <div class="panel-title">Add New Contractor</div>
</div>
<div class="panel-body">
	<form action="" method="post">
		<fieldset>
			<div class="form-group">
				<label>Town</label>
				<select name="contra_town">
					
				</select>
			</div>
			<div class="form-group">
				<label>Location</label>
				<select name="contra_location">
					
				</select>
			</div>
			<div class="form-group">
				<label>Name of Parking</label>
				<input class="form-control" name="contra_name" type="text" required>
			</div>
			<div class="form-group" id="size_parking">
				<label>Number of Parking Slots</label>
				<input class="form-control" name="contra_size" type="text" required>
			</div>
			<div class="form-group" id="slots_parking">
				<label>Label each parking slot</label>
			</div>
			<div class="form-group">
				<label>E-mail( This will be username to login)</label>
				<input class="form-control" name="contra_mail" type="email" required>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input class="form-control" name="contra_pass" type="text" value="" required>
			</div>
		</fieldset>
		<div>
			<input type="hidden" value="<?=$csrf_token?>" name="csrf_token_contractor">
			<input type="Submit" class="btn btn-primary" name="add_new_contractor" value="Save" /> 
			<input type="reset" class="btn btn-default" name="" value="Reset" /> 
		</div>
	</form>
</div>