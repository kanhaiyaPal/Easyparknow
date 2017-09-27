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
	$msg = $_REQUEST['msg'];
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
		'usage_parking_history',
		'usage_generate_reports'
	);
	return implode('|', $p_arry);
}

function generate_admin_permissions()
{
	//add newly created pages to this array
	$p_arry = array(
		'contractor_list',
		'contractor_new',
		'contractor_edit',
		'manage_location',
		'manage_town',
		'usage_parking_history',
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
?>