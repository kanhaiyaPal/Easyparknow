<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>
<?php
	check_permission_to_access();

	/*generate token for requests*/
	$csrf_token = generate_token();
	unset($_SESSION['csrf_token_contractor']);
	$_SESSION['csrf_token_contractor'] = $csrf_token;
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
			<th>End Time</th>
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