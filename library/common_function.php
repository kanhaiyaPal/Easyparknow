<?php
//functions 
if(!defined('VALIDATE')){ exit('DIRECT ACCESS NOT ALLOWED'); }
function generate_token($length = 21)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	return $randomString;
}
function hash_password($password){
   return password_hash($password, PASSWORD_BCRYPT);
}

function verify_pass($password,$hash){
	return password_verify($password,$hash);
}
function send_mail($message,$to,$subject)
{
	
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <info@capitalhunt.in>' . "\r\n";
	//$headers .= 'Cc: myboss@example.com' . "\r\n";

	@mail($to,$subject,$message,$headers);
}
function upload_image()
{
if(file_exists($_FILES['doc']['tmp_name']) && is_uploaded_file($_FILES['doc']['tmp_name'])):
	$target_dir = IMGPATH;
	//exit($target_dir);
	$random_str = generate_token(7);
	$target_file = $target_dir . DIRECTORY_SEPARATOR .$random_str. basename($_FILES["doc"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["doc"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			exit("File is not an image.");
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		exit('File already exists');
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["doc"]["size"] > 5000000) {
		exit( "Sorry, your file is too large.");
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" && $imageFileType != "zip" ) {
		exit("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		$uploadOk = 0;
	}

	if ($uploadOk == 0) {
		exit("Sorry, your file was not uploaded.");
	} else {
		if(move_uploaded_file($_FILES["doc"]["tmp_name"], $target_file)) {
			return $random_str.basename($_FILES["doc"]["name"]);
		} else {
			return false;
		}
	}
else: return true;
endif;
}
function upload_multiple_image()
{
	$total = count($_FILES['doc']['name']);
	$return_array = array();
	$return_string = '';
	if($total>0){
		for($i=0; $i<$total; $i++) {
				if(file_exists($_FILES['doc']['tmp_name'][$i]) && is_uploaded_file($_FILES['doc']['tmp_name'][$i])):
				$target_dir = IMGPATH;
				//exit($target_dir);
				$random_str = generate_token(7);
				$target_file = $target_dir . DIRECTORY_SEPARATOR .$random_str. basename($_FILES["doc"]["name"][$i]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_FILES["doc"]["tmp_name"])) {
					$check = getimagesize($_FILES["doc"]["tmp_name"][$i]);
					if($check !== false) {
						$uploadOk = 1;
					} else {
						exit("File is not an image.");
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($target_file)) {
					exit('File already exists');
					$uploadOk = 0;
				}
				// Check file size
				if ($_FILES["doc"]["size"][$i] > 5000000) {
					exit( "Sorry, your file is too large.");
					$uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "xls" && $imageFileType != "xlsx" && $imageFileType != "pdf" ) {
					exit("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
					$uploadOk = 0;
				}

				if ($uploadOk == 0) {
					exit("Sorry, your file was not uploaded.");
				} else {
					if(move_uploaded_file($_FILES["doc"]["tmp_name"][$i], $target_file)) {
						$return_array[] = $random_str.basename($_FILES["doc"]["name"][$i]);
					}
				}
			endif;
		}
	}
	if(count($return_array)>0){
		$return_string = implode('|',$return_array);
	
		return $return_string;
	}else{
		return false;
	}
	
}
function get_menu_status_admin($listings = array()){
	$status = '';
	$page_val = '';
	if(isset($_REQUEST['page'])){
		$page_val = $_REQUEST['page'];
	}
	
	foreach ($listings as $key => $value) {
		if(strpos($page_val,$value)!== FALSE){
			$status = 'open';
		}
	}
	return $status;
}

function generate_alerts_admin_pages($msg = '')
{
	if($msg ==''){ $msg = $_REQUEST['msg']; }
	switch ($msg) {
		case 'add':
			echo '<div class="alert alert-success">Data Added Successfully</div>';
			break;
		case 'edit':
			echo '<div class="alert alert-info">Data Updated Successfully</div>';
			break;
		case 'delete':
			echo '<div class="alert alert-danger">Data Deleted Successfully</div>';
			break;
		default:
			echo '<div class="alert alert-danger">'.htmlspecialchars($msg).'</div>';
			break;
	}
}

function is_ajax_call(){
	$return = FALSE;
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$return = TRUE;
	}
	return $return;
}

function generate_subadmin_permissions()
{
  //add newly created pages to this array
	$p_arry = array(
		'dashboard',
		'usage_parking_history',
		'usage_generate_reports'
	);
	return implode('|', $p_arry);
}

function generate_admin_permissions()
{
	//add newly created pages to this array
	$p_arry = array(
		'dashboard',
		'contractor_list',
		'contractor_new',
		'contractor_edit',
		'manage_location',
		'manage_town',
		'usage_parking_history',
		'users_list',
		'users_transactions',
		'users_vehicles',
		'usage_generate_reports'
	);
	return implode('|', $p_arry);
}

function check_permission_to_access()
{
	$page ='';
	if(isset($_REQUEST['page']) && ($_REQUEST['page']!=''))
	{
		$page = $_REQUEST['page'];
	}
	if((!isset($_SESSION['adminlogged']))|| ($_SESSION['adminlogged'] == ''))
	{
		exit('Permission Denied!');
	}else{
		$permissions = explode('|', $_SESSION['adminlogged']['permission']);
		$permitted = FALSE;
		foreach ($permissions as $key => $value) {
			if($value == $page){
				$permitted = TRUE;
				break;
			}else{
				$permitted = FALSE;
			}
		}

		if(!$permitted){
			exit('Permission Denied!');
		}
	}
}

function check_active_transaction($sql_con,$field,$value)
{

	$sql_con->where ($field,$value);
	$sql_con->where ('parking_status','0');
	$transactions = $sql_con->get('tbl_transactions');
	if($sql_con->count > 0){
		return FALSE;
	}
	return true;
}

/**Location and Town Functions**/
function get_all_town($sql_con)
{
	$sql_con->where ("parent",'0');
	$towns = $sql_con->get('tbl_town_location');
	return $towns;
}
function get_location_of_town($sql_con,$town_id = 0){
	$sql_con->where ("parent",$town_id);
	$towns = $sql_con->get('tbl_town_location');
	return $towns;
}

function get_slotname($sql_con,$slotname = 0){
	$sql_con->where ("name",$slotname);
	$slotname = $sql_con->get('tbl_parking_slots');
	return $slotname;
}
function get_town_by_locationid($sql_con,$parent_id = 0)
{
	$sql_con->where ("id",$parent_id);
	$town = $sql_con->getOne('tbl_town_location');
	return $town['id'];
}
function get_all_parking_data($sql_con)
{
	$sql_con->where ("user_type",'2');
	$parkings = $sql_con->get('users');
	return $parkings;
}
/**Location and Town Functions Ends **/




/*Report Generation Function*/
function generate_data_format_display($sql_raw_data = array(),$sql_con = '')
{

	$sql_parking_data = $sql_raw_data;
	$current_parkings = array();

	//calculate the admin and town share
	foreach ($sql_parking_data as $mkey => $value) {
		$sql_con->where ("name",$value['parking_slot_no']);
		$location_info = $sql_con->getOne("tbl_parking_slots");

		$sql_con->orderBy("timing","asc");
		$sql_con->where ("location_id",$location_info['location_id']);
		$pricing_data = $sql_con->get("tbl_location_pricing");
		
		$last_array_size = 0;
		$admin_master_pricing_array = array();
		$town_master_pricing_array = array();
		foreach($pricing_data as $key=>$pricing)
		{
			if($pricing['timing']>0){
				for($i=0;$i<($pricing['timing']-$last_array_size);$i++)
				{
					//calculate total pricing
					$total_admin_price = 0;
					$total_town_price = 0;
					if($pricing['admin_currency'] == 'cent'){ $total_admin_price+= (floatval($pricing['admin_share'])/100); }else{ $total_admin_price+= (floatval($pricing['admin_share'])); }
					$admin_master_pricing_array[] = round($total_admin_price,2);

					if($pricing['town_currency'] == 'cent'){ $total_town_price+= (floatval($pricing['town_share'])/100); }else{ $total_town_price+= (floatval($pricing['town_share'])); }
					$town_master_pricing_array[] = round($total_town_price,2);
				}
				$last_array_size= (int)$pricing['timing'];
			}
		}
		
		$user_parking_duration = $value['duration_in_minutes'];
		$unit_price = '';
		
		if(array_key_exists(($user_parking_duration-1),$admin_master_pricing_array)){
			$unit_price = $admin_master_pricing_array[$user_parking_duration-1];
		}else{
			$count = count($admin_master_pricing_array) - 1;
			$unit_price = $admin_master_pricing_array[$count];
		}
		
		$sql_parking_data[$mkey]['admin_share']  = $unit_price * $user_parking_duration;

		$unit_tw_price = '';
		
		if(array_key_exists(($user_parking_duration-1),$town_master_pricing_array)){
			$unit_tw_price = $town_master_pricing_array[$user_parking_duration-1];
		}else{
			$count = count($town_master_pricing_array) - 1;
			$unit_tw_price = $town_master_pricing_array[$count];
		}

		$sql_parking_data[$mkey]['town_share']  = $unit_tw_price * $user_parking_duration;		
	}


	//get parent level transactions only
	foreach ($sql_parking_data as $key => $value) {
		if($value['extend_id'] == '0'){
			$current_parkings[] = array(
				'id' => $value['id'],
				'parking_slot_no' => $value['parking_slot_no'],
				'vehicle_plate_no' => $value['vehicle_plate_no'],
				'start_date' => $value['start_date'],
				'start_time' => $value['start_time'],
				'end_date' => $value['end_date'],
				'end_time' => $value['end_time'],
				'duration_in_minutes' => $value['duration_in_minutes'],
				'admin_share' => $value['admin_share'],
				'town_share' => $value['town_share'],
				'payment_amount' => $value['payment_amount']
			);
		}
	}

	

	//Merge with their extended transaction
	foreach ($sql_parking_data as $key => $value) {
		if($value['extend_id'] != '0'){
			foreach ($current_parkings as $parkindex => $park) {
				if($park['id'] == $value['extend_id']){
					$current_parkings[$parkindex]['end_date'] = $value['end_date'];
					$current_parkings[$parkindex]['end_time'] = $value['end_time'];
					$current_parkings[$parkindex]['duration_in_minutes'] = (int)$park['duration_in_minutes'] + (int)$value['duration_in_minutes'];
					$current_parkings[$parkindex]['payment_amount'] = floatval($park['payment_amount']) + floatval($value['payment_amount']);
					$current_parkings[$parkindex]['admin_share'] = floatval($park['admin_share']) + floatval($value['admin_share']);
					$current_parkings[$parkindex]['town_share'] = floatval($park['town_share']) + floatval($value['town_share']);
				}
			}
		}
	}

	return $current_parkings;
}

function convert_all_extended_to_parent($sql_parking_data = array())
{
	$search_parent = FALSE;
	foreach ($sql_parking_data as $mkey => $value) {
		if($value['parking_status'] == '0'){
			foreach ($sql_parking_data as $skey => $svalue) {
				if($svalue['id'] == $value['extend_id']){
					$search_parent = TRUE;
				}
			}

			if(!$search_parent){
				$sql_parking_data[$mkey]['extend_id'] = 0;
			}
		}
	}

	return $sql_parking_data;
}

function is_current_parking_extended($park_id = 0,$sql_con = '')
{
	$extended = false;
	$sql_con->where('extend_id',$park_id);
	$sql_con->where('parking_status','0');
	$parking_data = $sql_con->get('tbl_transactions');

	if(count($parking_data)>0){
		$extended = true;
	}

	return $extended;
}
/*Report Generation Function Ends*/
?>