<?php
@session_start();
if(isset($_REQUEST['token']) && isset($_SESSION['csrf_token_contractor']) && (urldecode($_REQUEST['token'])==$_SESSION['csrf_token_contractor'])){
	$all_data_to_export = $_SESSION['data_export_call'];
	unset($_SESSION['data_export_call']);

	$out = '';
	$out .= 'Sr.No,';
	$out .= 'Parking Slot No,';
	$out .= 'Vehicle Plate No,';
	$out .= 'Start Date,';
	$out .= 'Start Time,';
	$out .= 'End Date,';
	$out .= 'End Time,';
	$out .= 'Admin Share,';
	$out .= 'Town Share,';
	$out .="\n";

	$count_pr = 1;
	// Add all values in the table to $out.
	foreach($all_data_to_export as $parking): 

	$out .='"'.$count_pr.'",';
	$out .='"'.$parking['parking_slot_no'].'",';
	$out .='"'.$parking['vehicle_plate_no'].'",';
	$out .='"'.$parking['start_date'].'",';
	$out .='"'.$parking['start_time'].'",';
	$out .='"'.$parking['end_date'].'",';
	$out .='"'.$parking['end_time'].'",';
	$out .='"'.$parking['admin_share'].'",';
	$out .='"'.$parking['town_share'].'",';
	$out .="\n";
	$count_pr++;

	endforeach; 

	// Open file export.csv.
	$f = fopen ('export_order_data.csv','w');
	// Put all values from $out to export.csv.
	fputs($f, $out);
	fclose($f);
	$curdate = date("d-m-Y");
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename="export_order_data'.$curdate.'.csv"');
	readfile('export_order_data.csv');
}else{
	exit('Token Expired');
}
?>