<?php
require_once('../../library/initialize.php'); 
if(is_ajax_call()){
	if((!isset($_SESSION['adminlogged']))|| ($_SESSION['adminlogged'] == ''))
	{
		header("Location:login.php");
		exit();
	}
	$ajax_handler = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

	if(isset($_POST['function']) && ($_POST['function']!='')){
		switch ($_POST['function']) {
			case 'getlocationnames_by_townid':
				if((int)$_POST['town_id'] == 0){
					exit('<option value="">Please Select Town First</option>');
				}else{
					$locations_ret = get_location_of_town($ajax_handler,(int)$_POST['town_id']);
					$locations_ret_opt = '';
					foreach ($locations_ret as $key => $value) {
						$locations_ret_opt .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
					}
					exit($locations_ret_opt);
				}				
			break;
			case 'generateParkingSlots':
				if((int)$_POST['slots'] <= 0)
				{
					exit('1');
				}else{
					$slots_html = '';
					$slots_html .= '<div class="row"><div class="col-md-12"><label>Assign Number to each slot</label></div>';
					for ($i=0;$i<(int)$_POST['slots'];$i++) {
						$slots_html .= '<div class="col-md-3"><input type="text" id="slot'.($i+1).'" name="parkingslots[]" onblur="slotexist(this.value,this.id)" placeholder="'.($i+1).'" class="form-control" required /></div>';
					}
					$slots_html .= '<div class="col-md-12"><hr/></div><div class="col-md-12"><input type="button" class="btn btn-default" name="bt_regenerate_slots" value="Re-Generate Slots" onclick="$(\'div#slots_parking\').html(\'\'); $(\'div#size_parking\').show(); return false;" /></div></div>';
					exit($slots_html);
				}
			break;
			case 'generateTimingsSlots':
				if((int)$_POST['slots'] <= 0)
				{
					exit('1');
				}else{
					$slots_html = '';
					$slots_html .= '<div class="row"><div class="col-md-12"><label>Provide timings in each cell</label></div>';
					for ($i=0;$i<(int)$_POST['slots'];$i++) {
						$slots_html .= '<div class="col-md-2"><input type="text" name="timingsSlots[]" placeholder="Slot Timing" class="form-control" required /></div><div class="col-md-3"><input type="text" name="townShare[]" placeholder="Town Share" class="form-control" required /></div><div class="col-md-2"><select name="townShareCurr[]" class="form-control" required><option value="dollar">Dollar</option><option value="cent">Cent</option></select></div><div class="col-md-3"><input type="text" name="adminShare[]" placeholder="Admin Share" class="form-control" required /></div><div class="col-md-2"><select name="adminShareCurr[]" class="form-control" required><option value="dollar">Dollar</option><option value="cent">Cent</option></select></div><div class="col-md-12"><hr/></div>';
					}
					$slots_html .= '<div class="col-md-12"><hr/></div><div class="col-md-12"><input type="button" class="btn btn-default" name="bt_regenerate_slots" value="Re-Generate Cells" onclick="$(\'div#slots_parking\').html(\'\'); $(\'div#size_parking\').show(); return false;" /></div></div>';
					exit($slots_html);
				}
			break;
			case 'slotexist':
			//echo $_POST['slots_no'].'testing';
			//die;
			
			$get_slotname_arr = get_slotname($ajax_handler,$_POST['slots_no']);
		        
					if(count($get_slotname_arr) > 0){
					    echo  'Slot Name already Exists';
					}else{
					return true;
					}
					
			
			break;
			
			
			
			
		}
	}else{
		exit('Access Denied');
	}
}else{
	exit('Access Denied A');
}
?>