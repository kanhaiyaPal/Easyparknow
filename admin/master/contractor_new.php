<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }  ?>
<span><b>
<?php	
	check_permission_to_access();
	
	$db_handler = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
	$towns_opt = get_all_town($db_handler);

/*message handler*/

if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
generate_alerts_admin_pages($_REQUEST['msg']);

/*message handler ends*/

	if ((isset($_POST['add_new_contractor']) && ($_POST['add_new_contractor']=='Save'))||(isset($_POST['save_add_new_contractor']) && ($_POST['save_add_new_contractor']=='Save and Add New'))) {

		if(isset($_SESSION['csrf_token_contractor']) && ($_SESSION['csrf_token_contractor']==$_POST['csrf_token_contractor']))
		{
			$db_handler->where ("email", $_POST['contra_mail']);
			$user = $db_handler->getOne("users");
			if($user['id']){
				echo 'Email Id already exist in database!';
			}else{
				
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
				//exit($db_handler->getLastQuery());

				if(!empty($_POST['parkingslots'])){
					foreach($_POST['parkingslots'] as $parking_slot){
						$data = Array (
								"name" => $parking_slot,
							   	"user_id" => $id,
							   	"location_id" => $_POST['contra_location']
						);
						$db_handler->insert ('tbl_parking_slots', $data);
					}
				}

				$data = Array (
						"user_id" => $id ,
						"price" => $_POST['contra_amount'],
					   	"denomination" => $_POST['contra_currency'],
					   	"location_id" => $_POST['contra_location']
				);
				$db_handler->insert ('tbl_parking_data', $data);

				
				if(isset($_POST['save_add_new_contractor']) && ($_POST['save_add_new_contractor']=='Save and Add New')){
					unset($_POST);
					header("Location:index.php?page=contractor_new&msg=add"); 
					exit();
				}else{
					unset($_POST);
					header("Location:index.php?page=contractor_list&msg=add"); 
					exit();
				}
			}
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
    <div class="panel-title">Add New Parking Account</div>
</div>
<div class="panel-body">
	<form action="" method="post">
		<fieldset>
			<div class="form-group">
				<label>Town</label>
				<select name="contra_town" class="form-control" required>
					<option value="">Please Select</option>
					<?php foreach ($towns_opt as $value) { ?>
						<option value="<?=$value['id']?>"><?=$value['name']?></option>
					<?php }	?>
				</select>
			</div>
			<div class="form-group">
				<label>Location</label>
				<select  name="contra_location" class="form-control" required>
					<option>Select town first</option>
				</select>
			</div>
			<div class="form-group">
				<label>Name of Parking</label>
				<input class="form-control" name="contra_name" type="text" required>
			</div>
			<div class="form-group" id="size_parking">
				<label>Number of Parking Slots</label>
				<div class="row">
					<div class="col-md-10"><input class="form-control" name="contra_size" type="text" required></div>
					<div class="col-md-2"><input type="button" class="btn btn-default" name="bt_generate_slots" value="Generate Slots" /></div>
				</div>				 
			</div>
			<div class="form-group" id="slots_parking">
			</div>
			<div class="form-group">
				<label>Parking Tarrif(Amount)</label>
				<div class="row">
					<div class="col-md-8">
						<div class="input-group">
							<input class="form-control" name="contra_amount" type="text" required><span class="input-group-addon">per minute</span>
						</div>
					</div>
					<div class="col-md-4">
						<select class="form-control" name="contra_currency" required>
							<option value="dollar">Dollar</option>
							<option value="cent">Cent</option>
						</select>
					</div>
				</div>	
				
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
			<input type="hidden" value="<?=ROOTPATH?>" name="rootpath_val">
			<input type="hidden" value="<?=$csrf_token?>" name="csrf_token_contractor">
			<input type="Submit" class="btn btn-primary" name="add_new_contractor" value="Save" /> 
			<input type="Submit" class="btn btn-default" name="save_add_new_contractor" value="Save and Add New" /> 
			<input type="reset" class="btn btn-default" name="" value="Reset" /> 
		</div>
	</form>
</div>
<?php $require_contractor_script = TRUE; ?>