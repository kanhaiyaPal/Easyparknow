<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }  ?>
<?php

/*message handler*/

if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
generate_alerts_admin_pages($_REQUEST['msg']);

/*message handler ends*/


$db_location = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
$towns_opt = get_all_town($db_location);

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='add')){  

	$data = Array ("name" => $_REQUEST['entity_name'],"parent" => $_REQUEST['parent_id']);
	$location_id = $db_location->insert ('tbl_town_location', $data);

	if (!empty($_POST['timingsSlots'])) {
		foreach ($_POST['timingsSlots'] as $key => $value) {
			$slot_data = array(
				'location_id' => $location_id,
				'town_share' => $_POST['townShare'][$key],
				'town_currency' => $_POST['townShareCurr'][$key],
				'admin_share' => $_POST['adminShare'][$key],
				'admin_currency' => $_POST['adminShareCurr'][$key],
				'timing' => $value
			);
			$db_location->insert('tbl_location_pricing', $slot_data);
		}
	}

	if($location_id){ unset($_POST); header("Location:index.php?page=manage_location&msg=add"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='change')){  
	$data = Array ("name" => $_REQUEST['entity_name'],"parent" => $_REQUEST['parent_id']);
	$db_location->where ("id", (int)$_GET['id']);
	$id = $db_location->update('tbl_town_location', $data);

	if(!empty($_POST['existSlotsTime'])){
		$existing_slots = array();
		foreach ($_POST['existSlotsTime']  as $key => $value) {
			$park_data = array(
						'timing' => $value,
						'town_share' => $_POST['existSlotsTown'][$key],
						'town_currency' => $_POST['existSlotsTownCurr'][$key],
						'admin_share' => $_POST['existSlotsAdmin'][$key],
						'admin_currency' => $_POST['existSlotsAdminCurr'][$key]
					);
			$db_location->where('id', (int)$_POST['existsSlotsid'][$key]);
			$db_location->update('tbl_location_pricing', $park_data);
		}

		//check for existing values and remove those also
		$db_location->where('location_id',(int)$_GET['id']);
		$db_location->where('id', $_POST['existsSlotsid'], 'NOT IN');
		$removable_slots = $db_location->get('tbl_location_pricing');
		foreach ($removable_slots as $key => $value) {
			$db_location->where('id', $value['id']);
			$db_location->delete('tbl_location_pricing');
		}
	}else{
		$db_location->where('location_id',(int)$_GET['id']);
		$db_location->delete('tbl_location_pricing');
	}

	if (!empty($_POST['timingsSlots'])) {
		foreach ($_POST['timingsSlots'] as $key => $value) {
			$slot_data = array(
				'location_id' => (int)$_GET['id'],
				'town_share' => $_POST['townShare'][$key],
				'town_currency' => $_POST['townShareCurr'][$key],
				'admin_share' => $_POST['adminShare'][$key],
				'admin_currency' => $_POST['adminShareCurr'][$key],
				'timing' => $value
			);
			$db_location->insert('tbl_location_pricing', $slot_data);
		}
	}

	if($id){ unset($_POST); header("Location:index.php?page=manage_location&msg=edit"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='edit')){  

$db_location->where ("id", (int)$_GET['id']);
$location_data = $db_location->getOne("tbl_town_location");

$db_location->where('location_id',(int)$_GET['id']);
$slots_data = $db_location->get('tbl_location_pricing');

$editable = true;

$db_location->where('location_id',(int)$_GET['id']);
$location_parking_slots = $db_location->get('tbl_parking_slots');

foreach ($location_parking_slots as $key => $value) {
	if(!check_active_transaction($db_location,'parking_slot_no',$value['name'])){
		$editable = false;
		break;
	}
}
?>
	<form name="add_town_location" method="post" action="index.php?page=manage_location&action=change&id=<?=$location_data['id']?>">
		<fieldset>
			<legend>Edit Location</legend>
			<div class="form-group">
				<label>Town</label>
				<select class="form-control" name="parent_id" required>
					<?php
						foreach ($towns_opt as $value) { ?>
						<option value="<?=$value['id']?>"
						<?php
							if($value['id'] == $location_data['parent']){ echo "selected"; }
						?>
						><?=$value['name']?></option>
					<?php
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="entity_name" class="form-control" required value="<?=$location_data['name']?>">
			</div>
			<?php if(count($slots_data)>0): ?>
			<div class="row form-group">
				<div class="col-md-12"><label>Existing Timings Tarrif</label></div>
				<?php foreach($slots_data as $slot): ?>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-2">
								<input type="text" <?php if(!$editable){ echo "disabled"; } ?>  name="existSlotsTime[]" class="form-control" value="<?=$slot['timing']?>" required />
							</div>
							<div class="col-md-2">
								<input type="text" <?php if(!$editable){ echo "disabled"; } ?> name="existSlotsTown[]" class="form-control" value="<?=$slot['town_share']?>" required />
							</div>
							<div class="col-md-2">
								<select name="existSlotsTownCurr[]" class="form-control" <?php if(!$editable){ echo "disabled"; } ?>>
									<option value="dollar" <?php if($slot['town_currency']=='dollar'){ echo "selected"; } ?> >Dollar</option>
									<option value="cent" <?php if($slot['town_currency']=='cent'){ echo "selected"; } ?> >Cent</option>
								</select>
							</div>
							<div class="col-md-2">
								<input type="text" <?php if(!$editable){ echo "disabled"; } ?> name="existSlotsAdmin[]" class="form-control" value="<?=$slot['admin_share']?>" required />
							</div>
							<div class="col-md-2">
								<select name="existSlotsAdminCurr[]" class="form-control" <?php if(!$editable){ echo "disabled"; } ?>>
									<option value="dollar" <?php if($slot['admin_currency']=='dollar'){ echo "selected"; } ?> >Dollar</option>
									<option value="cent" <?php if($slot['admin_currency']=='cent'){ echo "selected"; } ?> >Cent</option>
								</select>
							</div>
							<div class="col-md-2">
								<input type="hidden" name="existsSlotsid[]" value="<?=$slot['id']?>" />
								<?php if($editable): ?>
									<a href="javascript:void(0)" onclick="$(this).closest('div.row').remove()">Remove</a>
								<?php else: ?>
									<label class="alert alert-danger">Values Not Editable</label>
								<?php endif; ?>
							</div>
							<div class="col-md-12"><hr></div>
						</div>
					</div>					
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<div class="form-group" id="size_parking">
				<label>Number of Different Parking Timings(varying tarrif)</label>
				<div class="row">
					<div class="col-md-10"><input class="form-control" name="timing_size" type="text" ></div>
					<div class="col-md-2"><input type="button" class="btn btn-default" name="bt_generate_slots" value="Generate Timings" /></div>
				</div>				 
			</div>
			<div class="form-group" id="slots_parking">
			</div>
		</fieldset>
		<div>
			<input type="hidden" value="<?=ROOTPATH?>" name="rootpath_val">
			<input type="Submit" class="btn btn-primary" name="edit_town" value="Save" /> 
			<input type="reset" class="btn btn-default" name="" value="Reset" /> 
		</div>
	</form>
	<hr/>
<?php
}else{
	if (isset($_REQUEST['action']) && ($_REQUEST['action']=='delete')) {
		$db_location->where ("location_id", $_GET['id']);
		$db_location->delete("tbl_location_pricing");
		//delete
		$db_location->where ("id", $_GET['id']);
		if($db_location->delete("tbl_town_location")){ unset($_REQUEST['action']); header("Location:index.php?page=manage_location&msg=delete"); exit(); }
	}else{

		$db_location->where ("(parent!=0)");
		$locations = $db_location->get("tbl_town_location");
		$locations_ar = array();
		foreach ($locations as $key => $value) {

			$parent_name = '';
			foreach ($towns_opt as $town) {
				if($town['id'] == $value['parent']){ $parent_name = $town['name']; }
			}
			$locations_ar[] = array(
				'parent_name' => $parent_name,
				'name' => $value['name'],
				'id' => $value['id']
			);
		}

		?>
<form name="add_town_location" method="post" action="index.php?page=manage_location&action=add">
	<fieldset>
		<legend>Add New Location</legend>
		<div class="form-group">
			<label>Town</label>
			<select class="form-control" name="parent_id" required>
				<?php
					foreach ($towns_opt as $value) { ?>
					<option value="<?=$value['id']?>"><?=$value['name']?></option>
				<?php
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<label>Name</label>
			<input class="form-control" type="text" required name="entity_name">
		</div>
		<div class="form-group" id="size_parking">
			<label>Number of Different Parking Timings(varying tarrif)</label>
			<div class="row">
				<div class="col-md-10"><input class="form-control" name="timing_size" type="text" required></div>
				<div class="col-md-2"><input type="button" class="btn btn-default" name="bt_generate_slots" value="Generate Timings" /></div>
			</div>				 
		</div>
		<div class="form-group" id="slots_parking">
		</div>
	</fieldset>
	<div>
		<input type="hidden" value="<?=ROOTPATH?>" name="rootpath_val">
		<input type="Submit" class="btn btn-primary" name="add_new_town" value="Save" /> 
		<input type="reset" class="btn btn-default" name="" value="Reset" /> 
	</div>
</form>
<hr/>
		<?php
		if(count($locations)<=0){
			echo "No Existing Data to display";
		}else{ 

		$srcount = 1;
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="location_table">
	<thead>
		<tr>
			<th>Sr. No</th>
			<th>Town Name</th>
			<th>Location Name</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($locations_ar as $location): ?>
		<tr>
			<td><?=$srcount?></td>
			<td><?=$location['parent_name']?></td>
			<td><?=$location['name']?></td>
			<td><a href="index.php?page=manage_location&id=<?=$location['id']?>&action=edit"><span class="glyphicon glyphicon-pencil"></span></a></td>
			<td><a href="index.php?page=manage_location&id=<?=$location['id']?>&action=delete"><span class="glyphicon glyphicon-trash"></span></a></td>
		</tr>
		<?php $srcount++; endforeach; ?>
	</tbody>
</table>

<?php 
	}
  }
} 
$require_locationtarrif_script = TRUE;
?>