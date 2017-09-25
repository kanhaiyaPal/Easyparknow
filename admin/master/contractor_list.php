<?php if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); } ?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="contractor_table">
	<thead>
		<tr>
			<th>Username</th>
			<th>Contractor Name</th>
			<th>Town Alloted</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($contractor_list as $contractor): ?>
		<tr>
			<td><?=$contractor['username']?></td>
			<td><?=$contractor['contractor_name']?></td>
			<td><?=$contractor['contractor_town']?></td>
			<td>
			<a href="index.php?page=contractor_edit?id=<?=$contractor['id']?>"><span class="glyphicon glyphicon-pencil"></span></a> | 
			<a href="index.php?page=contractor_list?id=<?=$contractor['id']?>"><span class="glyphicon glyphicon-trash"></span></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>