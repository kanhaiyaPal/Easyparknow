<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();


	if((!isset($_REQUEST['token']))||(!isset($_REQUEST['id']))){
		exit('Invalid Access');
	}

	if(urldecode($_REQUEST['token']) == $_SESSION['csrf_token_contractor']){

		$db_user_transaction = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

		/*message handler*/

		if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
		generate_alerts_admin_pages($_REQUEST['msg']);

		/*message handler ends*/

		$db_user_transaction->where('parking_status','1');
		$db_user_transaction->where('user_id',(int)$_REQUEST['id']);
		$user_transactions = $db_user_transaction->get('tbl_transactions');


		$current_parkings = generate_data_format_display($user_transactions,$db_user_transaction);

		/*geneerate token for requests*/
		$csrf_token = generate_token();
		unset($_SESSION['csrf_token_contractor']);
		$_SESSION['csrf_token_contractor'] = $csrf_token;

		$count_pr = 1;

	}else{
		exit('Invalid Access');
	}	
?>
<div class="content-box-header">
	<button onclick="event.preventDefault(); location.href='index.php?page=users_list'" class="btn btn-primary"><i class="glyphicon glyphicon-circle-arrow-left"></i></button>
</div>
<div class="content-box-large box-with-header">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
		<thead>
			<tr>
				<th>Sr.No</th>
				<th>Parking Slot No</th>
				<th>Vehicle Plate No</th>
				<th>Start Date</th>
				<th>Start Time</th>
				<th>End Date</th>
				<th>End Time</th>
				<th>Admin Share($)</th>
				<th>Town Share($)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($current_parkings as $parking): ?>
			<tr>
				<td><?=$count_pr?></td>
				<td><?=$parking['parking_slot_no']?></td>
				<td><?=$parking['vehicle_plate_no']?></td>
				<td><?=$parking['start_date']?></td>
				<td><?=$parking['start_time']?></td>
				<td><?=$parking['end_date']?></td>
				<td><?=$parking['end_time']?></td>
				<td><?=$parking['admin_share']?></td>
				<td><?=$parking['town_share']?></td>
			</tr>
			<?php $count_pr++; endforeach; ?>
		</tbody>
	</table>
</div>