<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_user_list = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

	/*message handler*/
	if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
	generate_alerts_admin_pages($_REQUEST['msg']);
	/*message handler ends*/

	if(isset($_SESSION['csrf_token_contractor']) && (isset($_REQUEST['id'])) && ($_REQUEST['id']!= '') && ($_SESSION['csrf_token_contractor']==urldecode($_REQUEST['token']))){
		//delete slots
		$db_user_list->where('user_id',(int)$_REQUEST['id']);
		$db_user_list->delete('tbl_user_settings');

		//delete parking data
		$db_user_list->where('user_id',(int)$_REQUEST['id']);
		$db_user_list->delete('tbl_user_cc');

		//delete parking data
		$db_user_list->where('user_id',(int)$_REQUEST['id']);
		$db_user_list->delete('tbl_transactions');

		//delete user
		$db_user_list->where('id',(int)$_REQUEST['id']);
		$db_user_list->delete('users');

		header("Location:index.php?page=users_list&msg=delete");
	}

	$db_user_list->where('user_type','1');
	$users = $db_user_list->get('users');

	/*geneerate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

	$count_sr = 1;
?>
<div class="content-box-header">
	<div class="panel-title">Registered Users</div>
</div>
<div class="content-box-large box-with-header">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
		<thead>
			<tr>
				<th>Sr.No</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>Registration Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($users as $user): ?>
			<tr>
				<td><?=$count_sr?></td>
				<td><?=$user['mobile']?></td>
				<td><?=$user['email']?></td>
				<td><?=$user['added_date']?></td>
				<td>
				<a title="View Parkings" href="index.php?page=users_transactions&id=<?=$user['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-list"></span></a> &nbsp;|&nbsp; 
				<a title="View Vehicles" href="index.php?page=users_vehicles&id=<?=$user['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-road"></span></a> &nbsp;|&nbsp;
				<a title="Delete User" href="index.php?page=users_list&id=<?=$user['id']?>&token=<?=urlencode($csrf_token)?>" onclick="confirm('Are you sure you want to delete this user?')"><span class="glyphicon glyphicon-trash"></span></a>
				</td>
			</tr>
			<?php $count_sr++; endforeach; ?>
		</tbody>
	</table>
</div>