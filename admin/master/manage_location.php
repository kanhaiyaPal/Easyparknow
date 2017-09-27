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

	$pricing_data = array();
	if (!empty($_POST['timingsSlots'])) {
		foreach ($_POST['timingsSlots'] as $key => $value) {
			
		}
	}

	if($id){ unset($_POST); header("Location:index.php?page=manage_location&msg=add"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='change')){  
	$data = Array ("name" => $_REQUEST['entity_name'],"parent" => $_REQUEST['parent_id']);
	$db_location->where ("id", $_GET['id']);
	$id = $db_location->update('tbl_town_location', $data);
	if($id){ unset($_POST); header("Location:index.php?page=manage_location&msg=edit"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='edit')){  

$db_location->where ("id", $_GET['id']);
$location_data = $db_location->getOne("tbl_town_location");
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
		</fieldset>
		<div>
			<input type="Submit" class="btn btn-primary" name="edit_town" value="Save" /> 
			<input type="reset" class="btn btn-default" name="" value="Reset" /> 
		</div>
	</form>
	<hr/>
<?php
}else{
	if (isset($_REQUEST['action']) && ($_REQUEST['action']=='delete')) {
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