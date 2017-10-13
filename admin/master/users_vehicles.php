<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();


	if((!isset($_REQUEST['token']))||(!isset($_REQUEST['id']))){
		exit('Invalid Access');
	}

	if(urldecode($_REQUEST['token']) == $_SESSION['csrf_token_contractor']){

		$db_user_vehicles = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

		/*message handler*/

		if(isset($_REQUEST['msg']) && ($_REQUEST['msg']!=''))
		generate_alerts_admin_pages($_REQUEST['msg']);

		/*message handler ends*/

		$db_user_vehicles->where('user_id',(int)$_REQUEST['id']);
		$user_vehicles = $db_user_vehicles->get('tbl_user_vehicle');

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
				<th>Vehicle Plate No.</th>
				<th>Vehicle Type </th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($user_vehicles as $parking): ?>
			<tr>
				<td><?=$count_pr?></td>
				<td><?=$parking['plate_no']?></td>
				<td><?php 
					switch($parking['vehicle_type']){
						case '1' : echo "Car"; break;
						case '2' : echo "Motorcycle"; break;
						case '3' : echo "Electric Motorcycle"; break;
						case '4' : echo "Heavy Goods Vehicle"; break;
					}
				?></td>
			</tr>
			<?php $count_pr++; endforeach; ?>
		</tbody>
	</table>
</div>