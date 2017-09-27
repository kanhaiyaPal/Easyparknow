<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	$db_generate_reports = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
	$towns_opt = get_all_town($db_generate_reports);
	$parkings_dt = get_all_parking_data($db_generate_reports);
?>

<?php 
	if(isset($_REQUEST['gen_report']) && isset($_REQUEST['csrf_token']) && ($_REQUEST['gen_report']!='') && ($_SESSION['csrf_token_contractor'] == $_REQUEST['csrf_token'])): 

	$parking_slots_ar = array();
	$town_slots = array();
	$location_slots = array();
	
	if(isset($_POST['town']) && ($_POST['town'] != '')){
		$db_generate_reports->where ("id", (int)$_POST['town']);
		$db_generate_reports->orWhere ('parent', (int)$_POST['town']);
		$loc_ar = $db_generate_reports->get('tbl_town_location');
		foreach ($loc_ar as $key => $value) {
			$db_generate_reports->where ("location_id", (int)$value['id']);
			$sl_ret = $db_generate_reports->get('tbl_parking_slots');
			if(count($sl_ret) > 0){
				foreach($sl_ret as $slt){
					$town_slots[] = $slt['name'];
				}
			}
		}
	}
	if(isset($_POST['location']) && ($_POST['location'] != '')){
		
		$db_generate_reports->where ("location_id", (int)$_POST['location']);
		$sl_ret = $db_generate_reports->get('tbl_parking_slots');
		if(count($sl_ret) > 0){
			foreach($sl_ret as $slt){
				$location_slots[] = $slt['name'];
			}
		}
	}

	$parking_slots_ar = array_unique(array_merge($town_slots,$location_slots), SORT_REGULAR);

	if(!empty($parking_slots_ar)){
		$db_generate_reports->where("parking_slot_no",$parking_slots_ar, 'IN');
	}

	if(isset($_POST['parking_name']) && ($_POST['parking_name'] != '')){
		$db_generate_reports->where("parking_id",(int)$_POST['parking_name']);
	}

	if(isset($_POST['from_date']) && ($_POST['from_date'] != '')){
		$db_generate_reports->where("str_to_date(start_date, '%d-%m-%Y') >= str_to_date('".$_POST['from_date']."', '%d-%m-%Y')");
	}

	if(isset($_POST['to_date']) && ($_POST['to_date'] != '')){
		$db_generate_reports->where("str_to_date(start_date, '%d-%m-%Y') <= str_to_date('".$_POST['to_date']."', '%d-%m-%Y')");
	}

	$current_parkings = $db_generate_reports->get('tbl_transactions');

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

	unset($_SESSION['data_export_call']);
	$_SESSION['data_export_call'] = $current_parkings;

	$count_pr = 1;
?>
<div class="panel-heading">
    <div class="panel-title">Search Result</div>
    <div class="panel-options">
      <a href="<?=ROOTPATH?>/master/export_master_csv_data.php?token=<?=urlencode($csrf_token)?>" target="_blank"  style="font-size:20px" title="Export to CSV" ><i class="glyphicon glyphicon-share"></i></a>
    </div>
</div>
<div class="panel-body">
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
</div>
<?php 
	else: 

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;

?>
<div class="panel-heading">
    <div class="panel-title">Generate Reports</div>
</div>
<div class="panel-body">
	<form action="" method="post" class="form-inline">
		<fieldset>
			<div class="form-group col-md-2">
				<label>From Date</label>
				<input type="text" name="from_date" class="datepicker_ar form-control" required>
			</div>
			<div class="form-group col-md-2">
				<label>To Date</label>
				<input type="text" name="to_date" class="datepicker_dep form-control" required>
			</div>
			<div class="form-group col-md-2">
				<label>Town</label>
				<select name="town" class="form-control">
					<option value="" >Select Town</option>
					<?php foreach ($towns_opt as $value) { ?>
						<option value="<?=$value['id']?>" ><?=$value['name']?></option>
					<?php }	?>
				</select>
			</div>
			<div class="form-group col-md-2">
				<label>Location</label>
				<select name="location" class="form-control">
					<option value="" >Select town first</option>
				</select>
			</div>
			<div class="form-group col-md-2">
				<label>Parking Name</label>
				<select name="parking_name" class="form-control">
					<option value="" >Select Parking</option>
					<?php foreach($parkings_dt as $park_dt): ?>
						<option value="<?=$park_dt['id']?>"><?=$park_dt['name']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<label>&nbsp;</label>
				<input type="hidden" value="<?=ROOTPATH?>" name="rootpath_val">
				<input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
				<input type="submit" name="gen_report" value="Go" class="btn btn-block btn-primary">
			</div>
		</fieldset>
	</form>
</div>
<?php endif; 
$require_generate_reports_js = TRUE;
?>