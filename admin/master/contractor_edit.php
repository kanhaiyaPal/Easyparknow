<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }  ?>
<span><b>
<?php	
	check_permission_to_access();
	
	$db_contractor_edit = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
	$towns_opt = get_all_town($db_contractor_edit);

	/*message handler*/

	if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
	generate_alerts_admin_pages($_REQUEST['msg']);

	/*message handler ends*/
	$slots_data = '';
	$parking_data = '';
	$user_data = '';
	$town_id = 0;

	if(isset($_POST['edit_contractor']) && ($_POST['edit_contractor'] == 'Save')){
		if(isset($_SESSION['csrf_token_contractor']) && ($_SESSION['csrf_token_contractor']==$_POST['csrf_token_contractor'])) {
			
			$user_data = Array (
					"name" => $_POST['contra_name'],
				   	"email" => $_POST['contra_mail'],
				   	"username" => $_POST['contra_mail']
			);

			if(isset($_POST['contra_pass']) && ($_POST['contra_pass']!='')){
				$user_data["password"] = hash_password($_POST['contra_pass']);
 			}

 			$db_contractor_edit->where('id', (int)$_POST['user_id']);
 			$db_contractor_edit->update('users', $user_data);

 			if(!empty($_POST['existparkingslotsname'])){
 				$existing_slots = array();
	 			foreach ($_POST['existparkingslotsname']  as $key => $value) {
	 				$park_data = array('name' => $value);
	 				$db_contractor_edit->where('id', (int)$_POST['existparkingslotsid'][$key]);
					$db_contractor_edit->update('tbl_parking_slots', $park_data);
	 			}

	 			//check for existing values and remove those also
	 			$db_contractor_edit->where('user_id', (int)$_POST['user_id']);
	 			$db_contractor_edit->where('id', $_POST['existparkingslotsid'], 'NOT IN');
	 			$removable_slots = $db_contractor_edit->get('tbl_parking_slots');
	 			foreach ($removable_slots as $key => $value) {
	 				$db_contractor_edit->where('id', $value['id']);
					$db_contractor_edit->delete('tbl_parking_slots');
	 			}
 			}else{
 				$db_contractor_edit->where('user_id',(int)$_POST['user_id']);
				$db_contractor_edit->delete('tbl_parking_slots');
 			}

 			if(!empty($_POST['parkingslots'])){
	 			foreach($_POST['parkingslots'] as $parking_slot){
					$data = Array (
							"name" => $parking_slot,
						   	"user_id" => (int)$_POST['user_id'],
						   	"location_id" => $_POST['contra_location']
					);
					$db_contractor_edit->insert ('tbl_parking_slots', $data);
				}
			}

 			$park_data = Array (
				   	"location_id" => $_POST['contra_location']
			);

			$db_contractor_edit->where('user_id', (int)$_POST['user_id']);
			$db_contractor_edit->update('tbl_parking_data', $park_data);

			header("Location:index.php?page=contractor_list&msg=edit"); 
			exit();
		}else{
			echo 'Unable to verify security token.Please try again';
			unset($_POST);
			exit();
		}
	}

	if ((int)$_REQUEST['id'] > 0) {
		if(isset($_SESSION['csrf_token_contractor']) && ($_SESSION['csrf_token_contractor']==urldecode($_REQUEST['token']))) {
			$db_contractor_edit->where('user_id',(int)$_REQUEST['id']);
			$slots_data = $db_contractor_edit->get('tbl_parking_slots');

			$db_contractor_edit->where('user_id',(int)$_REQUEST['id']);
			$parking_data = $db_contractor_edit->getOne('tbl_parking_data');

			$db_contractor_edit->where('id',$parking_data['location_id']);
			$town_data = $db_contractor_edit->getOne('tbl_town_location');

			$location_parent = $town_data['parent'];

			$town_id = get_town_by_locationid($db_contractor_edit,$location_parent);

			$db_contractor_edit->where('id',(int)$_REQUEST['id']);
			$user_data = $db_contractor_edit->getOne('users');

			$csrf_token = generate_token();
			unset($_SESSION['csrf_token_contractor']);
			$_SESSION['csrf_token_contractor'] = $csrf_token;

		}else{
			echo 'Unable to verify security token.Please try again';
			unset($_POST);
			exit();
		}
	}else{
		header("Location:index.php?page=contractor_list");
		exit();
	}
	
?></b></span>
<div class="panel-heading">
    <div class="panel-title">Edit Parking Account</div>
</div>
<div class="panel-body">
	<form action="" method="post">
		<fieldset>
			<div class="form-group">
				<label>Town</label>
				<select name="contra_town" class="form-control" required>
					<option value="">Please Select</option>
					<?php foreach ($towns_opt as $value) { ?>
						<option value="<?=$value['id']?>" <?php if ($value['id']==$town_id) { echo "selected"; }?>><?=$value['name']?></option>
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
				<input class="form-control" name="contra_name" value="<?=$user_data['name']?>" type="text" required>
			</div>
			<?php if(count($slots_data)>0): ?>
			<div class="row form-group">
				<div class="col-md-12"><label>Existing slots</label></div>
				<?php foreach($slots_data as $slot): ?>
					<div class="col-md-3">
						<input type="text" name="existparkingslotsname[]" class="form-control" value="<?=$slot['name']?>" required />
						<input type="hidden" name="existparkingslotsid[]" value="<?=$slot['id']?>" />
						<?php if(check_active_transaction($db_contractor_edit,'parking_slot_no',$slot['name'])): ?>
							<a href="javascript:void(0)" onclick="$(this).parent('div').remove()">Remove</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<div class="form-group" id="size_parking" >
				<label>Number of Parking Slots</label>
				<div class="row">
					<div class="col-md-10"><input class="form-control" name="contra_size" type="text" ></div>
					<div class="col-md-2"><input type="button" class="btn btn-default" name="bt_generate_slots" value="Generate Slots" /></div>
				</div>				 
			</div>
			<div class="form-group" id="slots_parking" >
			</div>
			<div class="form-group">
				<label>E-mail( This will be username to login)</label>
				<input class="form-control" name="contra_mail" value="<?=$user_data['email']?>" type="email" required>
			</div>
			<div class="form-group">
				<label>Password(Enter new password to change)</label>
				<input class="form-control" name="contra_pass"  type="text" value="" >
			</div>
		</fieldset>
		<div>
			<input type="hidden" value="<?=$user_data['id']?>" name="user_id">
			<input type="hidden" value="<?=ROOTPATH?>" name="rootpath_val">
			<input type="hidden" value="<?=$csrf_token?>" name="csrf_token_contractor">
			<input type="Submit" class="btn btn-primary" name="edit_contractor" value="Save" /> 
			<input type="reset" class="btn btn-default" name="" value="Reset" /> 
		</div>
	</form>
</div>
<?php 
$require_contractor_script = TRUE; 
$require_contractor_edit_script = TRUE;
?>