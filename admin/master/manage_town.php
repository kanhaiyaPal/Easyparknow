<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }  ?>
<?php
/*message handler*/

if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
generate_alerts_admin_pages($_REQUEST['msg']);

/*message handler ends*/


$db_town = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='add')){  
	$data = Array ("name" => $_REQUEST['entity_name'],"parent" => '0');
	$id = $db_town->insert ('tbl_town_location', $data);
	if($id){ unset($_POST); header("Location:index.php?page=manage_town&msg=add"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='change')){  
	$data = Array ("name" => $_REQUEST['entity_name'],"parent" => '0');
	$db_town->where ("id", $_GET['id']);
	$id = $db_town->update('tbl_town_location', $data);
	if($id){ unset($_POST); header("Location:index.php?page=manage_town&msg=edit"); exit(); }
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='edit')){  

$db_town->where ("id", $_GET['id']);
$town_data = $db_town->getOne("tbl_town_location");
?>
	<form name="add_town_location" method="post" action="index.php?page=manage_town&action=change&id=<?=$town_data['id']?>">
		<fieldset>
			<legend>Edit Town</legend>
			<div class="form-group">
				<label>Name</label>
				<input class="form-control" type="text" required name="entity_name" value="<?=$town_data['name']?>">
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

		//delete location of town first
		$db_town->where ("parent", $_GET['id']);
		$db_town->delete("tbl_town_location");

		$db_town->where ("id", $_GET['id']);
		if($db_town->delete("tbl_town_location")){ 
			unset($_REQUEST['action']); 
			header("Location:index.php?page=manage_town&msg=delete"); 
			exit(); 
		}
	}else{

		$db_town->where ("parent", 0);
		$towns = $db_town->get("tbl_town_location");
		?>
<form name="add_town_location" method="post" action="index.php?page=manage_town&action=add">
	<fieldset>
		<legend>Add New Town</legend>
		<div class="form-group">
			<label>Name</label>
			<input class="form-control" type="text" required name="entity_name">
		</div>
	</fieldset>
	<div>
		<input type="Submit" class="btn btn-primary" name="add_new_town" value="Save" /> 
		<input type="reset" class="btn btn-default" name="" value="Reset" /> 
	</div>
</form>
<hr/>
		<?php
		if(count($towns)<=0){
			echo "No Existing Data to display";
		}else{ 

		$active_towns = array();
		$db_town->where("parking_status",'0');
		$active_parkings = $db_town->get("tbl_transactions",null,"DISTINCT parking_id as parkid");

		foreach ($active_parkings as $parking) {
			$db_town->where("user_id",$parking['parkid']);
			$location_id = $db_town->getOne("tbl_parking_data");

			$db_town->where("id",$location_id['location_id']);
			$location_id_sc = $db_town->getOne("tbl_town_location");

			$db_town->where("id",$location_id_sc['parent']);
			$final_town = $db_town->getOne("tbl_town_location");

			$active_towns[] = $final_town['id'];
		}

		$srcount = 1;
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="town_table">
	<thead>
		<tr>
			<th>Sr. No</th>
			<th>Town Name</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($towns as $town): ?>
		<tr>
			<td><?=$srcount?></td>
			<td><?=$town['name']?></td>
			<td><a href="index.php?page=manage_town&id=<?=$town['id']?>&action=edit"><span class="glyphicon glyphicon-pencil"></span></a></td>
			<td><a 
			<?php if(in_array($town['id'],$active_towns)){ ?> 
			href="#" onclick="alert('Town cannot be deleted right now'); return false;"
			<?php }else{ ?> 
			href="index.php?page=manage_town&id=<?=$town['id']?>&action=delete" onclick="return confirm('Are you sure you want to delete this data?')"
			<?php } ?>
			><span class="glyphicon glyphicon-trash"></span></a></td>
		</tr>
		<?php $srcount++; endforeach; ?>
	</tbody>
</table>

<?php 
	}
  }
} 
?>