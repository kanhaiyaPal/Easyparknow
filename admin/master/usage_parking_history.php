<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_usage_history = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
?>

<?php 
	if(isset($_REQUEST['contractor']) && isset($_REQUEST['token']) && ($_REQUEST['contractor']!='') && ((int)$_REQUEST['contractor']>0) && ($_SESSION['csrf_token_contractor'] == urldecode($_REQUEST['token']))): 


	if(isset($_REQUEST['active']) && ($_REQUEST['active'] == '1')){
		$db_usage_history->where('parking_status','0');		
	}else{
		$db_usage_history->where('parking_status','1');		
	}

	$db_usage_history->where('parking_id',(int)$_REQUEST['contractor']);
	$sql_parking_data = $db_usage_history->get('tbl_transactions');
	
	$sql_parking_data = convert_all_extended_to_parent($sql_parking_data);

	$current_parkings = generate_data_format_display($sql_parking_data,$db_usage_history);

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

	$count_pr = 1;
?>
<div class="row">
	<div class="col-md-1"><button onclick="event.preventDefault(); location.href='index.php?page=usage_parking_history'" class="btn btn-primary"><i class="glyphicon glyphicon-circle-arrow-left"></i></button></div>
	<div class="col-md-11">&nbsp;</div>
	<div class="col-md-12">&nbsp;</div>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Parking Slot No.</th>
			<th>Vehicle Plate No</th>
			<th>Start Date</th>
			<th>Start Time</th>
			<?php if(isset($_REQUEST['active']) && ($_REQUEST['active'] == '1')){ ?>
				<th>Remaining Time(in mins.)</th>
			<?php }else{ ?>
				<th>End Date</th>
				<th>End Time</th>
			<?php } ?>
			<th>Town Share( $ )</th>
			<th>Admin Share( $ )</th>
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
			<?php 
				if(isset($_REQUEST['active']) && ($_REQUEST['active'] == '1')){ 
				$to_time = strtotime($parking['end_date'].''.$parking['end_time']);
				$from_time = strtotime(date('Y-m-d H:i'));
				//echo round(abs($to_time - $from_time) / 60,2). " minute";
			?>
				<td><?=round(abs($to_time - $from_time) / 60,2). " minute"?></td>	
			<?php }else{ ?>
				<td><?=$parking['end_date']?> <?php if(is_current_parking_extended($parking['id'],$db_usage_history)){ ?><a title="This parking has been exteded" href="javascript:void(0)"><i class="glyphicon glyphicon-circle-arrow-right"></i></a><?php } ?></td>
				<td><?=$parking['end_time']?> <?php if(is_current_parking_extended($parking['id'],$db_usage_history)){ ?><a title="This parking has been exteded" href="javascript:void(0)"><i class="glyphicon glyphicon-circle-arrow-right"></i></a><?php } ?></td>
			<?php } ?>			
			<td><?=$parking['town_share']?></td>
			<td><?=$parking['admin_share']?></td>
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
				<table>
					<tr>
						<td>
							<a title="Past Parking Data" href="index.php?page=usage_parking_history&contractor=<?=$contractor['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-th-list"></span></a>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<a title="Active Parking" href="index.php?page=usage_parking_history&active=1&contractor=<?=$contractor['id']?>&token=<?=urlencode($csrf_token)?>"><span class="glyphicon glyphicon-flash"></span></a>
						</td>
					</tr>
				</table>			
			</td>
		</tr>
		<?php $count_sr++; endforeach; ?>
	</tbody>
</table>	
<?php endif; ?>