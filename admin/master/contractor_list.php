<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_contractor_list = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

	/*message handler*/

	if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
	generate_alerts_admin_pages($_REQUEST['msg']);

	/*message handler ends*/

	if (isset($_REQUEST['id']) && ((int)$_REQUEST['id'] > 0)) {
		echo $_SESSION['csrf_token_contractor'];
		if(isset($_SESSION['csrf_token_contractor']) && ($_SESSION['csrf_token_contractor']==urldecode($_REQUEST['token'])))
		{
			//delete active transactions
			$active_trans = check_active_transaction($db_contractor_list,'parking_id',(int)$_REQUEST['id']);
			if($active_trans){
				//delete slots
				$db_contractor_list->where('user_id',(int)$_REQUEST['id']);
				$db_contractor_list->delete('tbl_parking_slots');

				//delete parking data
				$db_contractor_list->where('user_id',(int)$_REQUEST['id']);
				$db_contractor_list->delete('tbl_parking_data');

				//delete user
				$db_contractor_list->where('id',(int)$_REQUEST['id']);
				$db_contractor_list->delete('users');

				header("Location:index.php?page=contractor_list&msg=delete");
				exit();
			}else{
				echo 'This data is in use with some active parking, please remove the parking first';
				unset($_POST);
			}
		}else{
			echo 'Unable to verify security token.Please try again';
			unset($_POST);
		}
	}else{
		$db_contractor_list->where('user_type','2');
		$all_parkings = $db_contractor_list->get('users');
		
		$contractor_list = array();
		foreach ($all_parkings as $key => $value) {
			$db_contractor_list->where('user_id',$value['id']);
			$parking_data = $db_contractor_list->getOne('tbl_parking_data');

			$db_contractor_list->where('id',$parking_data['location_id']);
			$location = $db_contractor_list->getOne('tbl_town_location');

			$contractor_list[] = array('id'=> $value['id'],'username' => $value['username'],'contractor_name'=>$value['name'],'contractor_location' => $location['name']);
		}

		$count_sr = 1;

		/*geneerate token for requests*/
		$csrf_token = generate_token();
		unset($_SESSION['csrf_token_contractor']);
		$_SESSION['csrf_token_contractor'] = $csrf_token;
	}

?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Username</th>
			<th>Parking Name</th>
			<th>Location Alloted</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($contractor_list as $contractor): ?>
		<tr>
			<td><?=$count_sr?></td>
			<td><?=$contractor['username']?></td>
			<td><?=$contractor['contractor_name']?></td>
			<td><?=$contractor['contractor_location']?></td>
			<td>
			<a href="index.php?page=contractor_edit&id=<?=$contractor['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-pencil"></span></a> | 
			<a href="index.php?page=contractor_list&id=<?=$contractor['id']?>&token=<?=urlencode($csrf_token)?>" onclick="confirm('Are you sure you want to delete this data?')"><span class="glyphicon glyphicon-trash"></span></a>
			</td>
		</tr>
		<?php $count_sr++; endforeach; ?>
	</tbody>
</table>