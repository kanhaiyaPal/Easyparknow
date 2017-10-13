<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_usage_history = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
?>

<?php 
	if(isset($_REQUEST['contractor'])  && ($_REQUEST['contractor']!='') && ((int)$_REQUEST['contractor']>0)): 


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
<?php endif; ?>