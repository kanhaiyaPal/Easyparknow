<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_usage_history = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
?>

<?php 
	if(isset($_REQUEST['contractor']) && isset($_REQUEST['token']) && ($_REQUEST['contractor']!='') && ((int)$_REQUEST['contractor']>0) && ($_SESSION['csrf_token_contractor'] == urldecode($_REQUEST['token']))): 

	$db_usage_history->where('parking_id',(int)$_REQUEST['contractor']);
	$current_parkings = $db_usage_history->get('tbl_transactions');

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

	$count_pr = 1;
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Parking Slot No.</th>
			<th>Vehicle Plate No</th>
			<th>Start Date</th>
			<th>Start Time</th>
			<th>End Date</th>
			<th>End Time</th>
			<th>Amount Paid</th>
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
			<td><?=$parking['payment_amount']?></td>
		</tr>
		<?php $count_pr++; endforeach; ?>
	</tbody>
</table>
<?php 
	else: 

	$db_usage_history->where('user_type','2');
	$all_parkings = $db_usage_history->get('users');
	
	$contractor_list = array();
	foreach ($all_parkings as $key => $value) {
		$db_usage_history->where('user_id',$value['id']);
		$parking_data = $db_usage_history->getOne('tbl_parking_data');

		$db_usage_history->where('id',$parking_data['location_id']);
		$location = $db_usage_history->getOne('tbl_town_location');

		$contractor_list[] = array('id'=> $value['id'],'username' => $value['username'],'contractor_name'=>$value['name'],'contractor_location' => $location['name']);
	}

	$count_sr = 1;

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_user_table">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Username</th>
			<th>Parking Name</th>
			<th>Location Alloted</th>
			<th>View Parking</th>
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
			<a href="index.php?page=usage_parking_history&contractor=<?=$contractor['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-list-alt"></span></a>
			</td>
		</tr>
		<?php $count_sr++; endforeach; ?>
	</tbody>
</table>	
<?php endif; ?>