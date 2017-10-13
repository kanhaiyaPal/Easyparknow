<?php 
require_once('library/initialize.php');
require_once('library/encryptclass.php');
 
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 }
 
 if(!isset($_SESSION['parkingdata']['firstform']) || empty($_SESSION['parkingdata']['firstform'])) 
 {
    header("location:parkinglocation.php");
 }
  if(!isset($_SESSION['parkingdata']['secondform']) || empty($_SESSION['parkingdata']['secondform'])){
 
  	header("location:duration.php");
 
 }
//print_r($_SESSION);
 
 $minute_hour = ($_SESSION['parkingdata']['secondform']['minute_hour'] == 1) ? 'Minutes' : 'Hours';
 $msg = '';
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
 $db->where ("username", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
		
		$db->where("user_id",$user['id']);
		$cc_detail = $db->get("tbl_user_cc");
		
		
		$db->where("id",$_SESSION['parkingdata']['firstform']['vehicle_no']);
		$tbl_user_vehicle = $db->getone("tbl_user_vehicle");
 
 		$master_pricing_array = array();
		$db->where ("name", $_SESSION['parkingdata']['firstform']['location_no']);
		$location_detail = $db->getOne("tbl_parking_slots");
		
		$db->where ("location_id", $location_detail['location_id']);
		$parking_data = $db->getOne("tbl_parking_data");
		$parking_admin_user_id = $parking_data['user_id'];
		
		
		$db->orderBy("timing","asc");
		$db->where ("location_id",$location_detail['location_id']);
		$pricing_data = $db->get("tbl_location_pricing");
		
		$last_array_size = 0;
		foreach($pricing_data as $key=>$pricing)
		{
			if($pricing['timing']>0){
				for($i=0;$i<($pricing['timing']-$last_array_size);$i++)
				{
					//calculate total pricing
					$total_price = 0;
					if($pricing['admin_currency'] == 'cent'){ $total_price+= (floatval($pricing['admin_share'])/100); }else{ $total_price+= (floatval($pricing['admin_share'])); }
					if($pricing['town_currency'] == 'cent'){ $total_price+= (floatval($pricing['town_share'])/100); }else{ $total_price+= (floatval($pricing['town_share'])); }
					$master_pricing_array[] = round($total_price,2);
				}
				$last_array_size= (int)$pricing['timing'];
			}
		}
		
		$user_parking_duration = $_SESSION['parkingdata']['secondform']['totalminutes'];
		$unit_price = '';
		
		if(array_key_exists(($user_parking_duration-1),$master_pricing_array)){
			$unit_price = $master_pricing_array[$user_parking_duration-1];
		}else{
			$count = count($master_pricing_array) - 1;
			$unit_price = $master_pricing_array[$count];
		}
		
		$parking_charges  = $unit_price * $user_parking_duration;
		
		
		
		
		
//submit  parking		
if(isset($_POST['pay_park']))
{
		      if(empty($_POST['card_id'])) {
			  $msg = 'Please select credit/debit card';
			  
			  }else { 
//New parking start 			  
						  if($_SESSION['parkingdata']['secondform']['extend'] == 0){
							$site_user_id =  $user['id'];
							$parking_id =    $parking_admin_user_id;
							$vehicle_plate_no =  $tbl_user_vehicle['plate_no'];
							$vehicleArr=array(1=>'Car',2=>'Motorcycle',3=>'Electric Motorcyle',4=>'Heavy Goods Vehicle');
							$vehicle_type =  $vehicleArr[$tbl_user_vehicle['vehicle_type']];
							$parking_slot_no  =  $_SESSION['parkingdata']['firstform']['location_no'];
							$start_time =  date('H:i',strtotime($_SESSION['parkingdata']['secondform']['start_time']));
							$start_date =  date('Y-m-d',strtotime($_SESSION['parkingdata']['secondform']['start_time']));
							$end_time =    date('H:i',strtotime($_SESSION['parkingdata']['secondform']['expire_time']));
							$end_date =    date('Y-m-d',strtotime($_SESSION['parkingdata']['secondform']['expire_time']));
							$duration_in_minutes = $_SESSION['parkingdata']['secondform']['totalminutes'];
							$payment_amount =  round($parking_charges,2);
							
							$db->where("id",$_POST['card_id']);
							$cc_detail_card = $db->getone("tbl_user_cc");
							
							$card_number = $cc_detail_card['card_no'];
							$card_issue_date =  $cc_detail_card['issue_date'];
							$card_expiry_date =  $cc_detail_card['expiry_date'];
							
							$data_arr= array(
								'user_id'=> $site_user_id,
								'parking_id'=> $parking_id,
								'vehicle_plate_no'=> $vehicle_plate_no,
								'parking_slot_no'=> $parking_slot_no,
								'start_time'=> $start_time,
								'start_date'=> $start_date,
								'end_time'=> $end_time,
								'end_date'=> $end_date,
								'duration_in_minutes'=>$duration_in_minutes,
								'payment_amount'=> $payment_amount,
								'card_number'=> $card_number,
								'card_issue_date'=> $card_issue_date,
								'card_expiry_date'=> $card_expiry_date	,
								'vehicle_type'=> $vehicle_type,
								'mail_send'=> 0,
								'parking_status'=> 0,
								'extend_id'=> 0
									
							);
							
								$db->where("user_id",$user['id']);
								$db->where("parking_status",0);
								$db->where("parking_slot_no",$_SESSION['parkingdata']['firstform']['location_no']);
								$tbl_transactions = $db->getone("tbl_transactions");	
						 
								 if(count($tbl_transactions) > 0 ) {
									$data_update = Array ('mail_send' => '1','parking_status'=>'1');
									$db->where("user_id",$user['id']);
									$db->where("parking_status",0);
									$db->where("parking_slot_no",$_SESSION['parkingdata']['firstform']['location_no']);
									$db->update('tbl_transactions', $data_update);
								 
								 }
							   $id = $db->insert('tbl_transactions', $data_arr);
					 // exit($db->getLastError());
					  
								if($id){
								$data_update = Array ('slot_available' => '1');
								$db->where ('name',$parking_slot_no);
								$db->update ('tbl_parking_slots', $data_update);
								
								$_SESSION['parkingdata']['thirdform'] = array(
											'start_date_time' => $_SESSION['parkingdata']['secondform']['start_time'],
											'expire_date_time' => $_SESSION['parkingdata']['secondform']['expire_time'],
											'parking_slot_no' => 	$parking_slot_no
										);
										
									header("Location:parking.php");
									exit();
								}
					
					}
	//New parking End
	
	
	//Extend Parking code start 				
					else if($_SESSION['parkingdata']['secondform']['extend'] == 1) {
					
					$db->where("user_id",$user['id']);
					$db->where("parking_status",0);
					$db->where("parking_slot_no",$_SESSION['parkingdata']['secondform']['location_no']);
					$tbl_transactions = $db->getone("tbl_transactions");
					
					
					$duration_in_minutes = $_SESSION['parkingdata']['secondform']['totalminutes'];
					$total_minutes = ($_SESSION['parkingdata']['secondform']['totalminutes'] + $tbl_transactions['duration_in_minutes']);
					$totalsec = $total_minutes * 60;
					
					$endDate = date('Y-m-d H:i', strtotime($tbl_transactions['end_time']) + ($duration_in_minutes * 60));
					$total_extend_time = date("H:i",strtotime($endDate));
					$total_extend_date = date("Y-m-d",strtotime($endDate));
					
					
					$payment_amount =  round($parking_charges,2);
					$data_arr= array(
					'user_id'=> $tbl_transactions['user_id'],
					'parking_id'=> $tbl_transactions['parking_id'],
					'vehicle_plate_no'=> $tbl_transactions['vehicle_plate_no'],
					'parking_slot_no'=> $tbl_transactions['parking_slot_no'],
					'start_time'=> $tbl_transactions['start_time'],
					'start_date'=> $tbl_transactions['start_date'],
					'end_time'=> $total_extend_time,
					'duration_in_minutes'=>$duration_in_minutes,
					'end_date'=> $total_extend_date,
					'payment_amount'=> $payment_amount,
					
					'card_number'=> $tbl_transactions['card_number'],
					'card_issue_date'=> $tbl_transactions['card_issue_date'],
					'card_expiry_date'=> $tbl_transactions['card_expiry_date']	,
					'vehicle_type'=> $tbl_transactions['vehicle_type'],
					'mail_send'=> 0,
					'parking_status'=> 0,
					'extend_id'=> $tbl_transactions['id']
					);
					
							$db->where("user_id",$user['id']);
							$db->where("parking_status",0);
							$db->where("parking_slot_no",$_SESSION['parkingdata']['secondform']['location_no']);
							$tbl_transactions = $db->getone("tbl_transactions");	
							
							if(count($tbl_transactions) > 0 ) {
							$data_update = Array ('mail_send' => '1','parking_status'=>'1');
							$db->where("user_id",$user['id']);
							$db->where("parking_status",0);
							$db->where("parking_slot_no",$_SESSION['parkingdata']['secondform']['location_no']);
							$db->update('tbl_transactions', $data_update);
							
							}
							$id = $db->insert('tbl_transactions', $data_arr);
							// exit($db->getLastError());
							
							if($id){	
							$_SESSION['parkingdata']['thirdform'] = array(
							'start_date_time' => $_SESSION['parkingdata']['secondform']['start_time'],
							'expire_date_time' => $_SESSION['parkingdata']['secondform']['expire_time'],
							'parking_slot_no' => 	$parking_slot_no
							);
							
							header("Location:parking.php");
							exit();
							}
					
					
			}
				
//Extend Parking code end				
	}
		
}	
		
		
 
?>

<?php //$result_seo=mysql_fetch_array(mysql_query("select * from  tbl_seo where id='1'"));?>
<?php //$webmasterRow=mysql_fetch_array(mysql_query("select * from tbl_mobileemail where pkId = '2'"));?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />


<title>Parking</title>
<meta name="description" content="Photo Sketch Paintings" />
<meta name="keywords" content="Photo Sketch Paintings" />

<link href="css/style.css" rel="stylesheet" type="text/css" />


<script type="text/javascript" src="megamenu/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="megamenu/css/webslidemenu.css" />
<script type="text/javascript" src="megamenu/js/webslidemenu.js"></script>
<link rel="stylesheet" type="text/css" href="megamenu/font-awesome/css/font-awesome.min.css" />


<link rel="stylesheet" href="plugins/slider/responsiveslides.css">
<script src="plugins/slider/responsiveslides.min.js"></script>
<script>
var $11 = jQuery.noConflict();
	    $11(function () {	
	      $11("#slider1").responsiveSlides({
	        maxwidth: 1600,
	        speed: 700
	      });
	});
</script>


<link rel="stylesheet" href="t-slider/css/jquery.bxslider.css" type="text/css" />
<script type="text/javascript" src="t-slider/js/jquery.min.js"></script>
<script type="text/javascript" src="t-slider/js/jquery.bxslider.js"></script>
<script type="text/javascript">
var $2222 = jQuery.noConflict();
$2222(document).ready(function(){
$2222('#slider2').bxSlider({
  slideWidth: 250,
    minSlides: 1,
    maxSlides: 4,
    moveSlides: 1,
	auto: true,
	controls: false,
    slideMargin: 10	
});
});
</script>

<script type="text/javascript">
var $333 = jQuery.noConflict();
$333(document).ready(function(){
$333('#slider3').bxSlider({  
    minSlides: 1,
    maxSlides: 1,
    moveSlides: 1,
	auto: true,
	controls: false,
    slideMargin: 50	
});
});
</script>


</head>

<body>
<?php include"header.php"?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
             <?php if(isset($msg) && !empty($msg)){ ?>
            <div class="alert alert-danger">
               <strong>  <?php echo $msg; ?></strong>
            </div>
              <?php } ?>
            <h1 class="text-center login-title">Please Confirm Your Purchase</h1>
            <div class="account-wall">
                <div align="center">Parking Duration : <?php echo $_SESSION['parkingdata']['secondform']['timeduration']?>&nbsp;<?php echo $minute_hour ?>  </div>
                 <div align="center"><?php echo  'Start at : '.$_SESSION['parkingdata']['secondform']['start_time'].'  Expire at : '.$_SESSION['parkingdata']['secondform']['expire_time'] ?> </div>
                  <div align="center"><?php echo  'Location  : '.$_SESSION['parkingdata']['firstform']['location_no']; ?> </div>
                   <div align="center"><?php echo  'Vehicle No. : '.$tbl_user_vehicle['plate_no']; ?> </div>
                     <div align="center"><?php echo  'Parking  Charges. : '.$parking_charges .' Doller'; ?> </div>
                <form class="form-signin" action="" method="post">
                
                   <select class="selectpicker" name="card_id" required>
                        <optgroup label="Select your Card">
                         <option value="0">Select your Card</option>
                        <?php if(count($cc_detail) > 0) {
						    foreach($cc_detail as $card_ids){
						?>
                        <option value="<?php echo $card_ids['id']?>"><?php
						
						 $key = "23c34e56fSdrtWrg"; // Encryption Key
                         $crypt = new Encryption($key);
			             $number =  $crypt->decrypt($card_ids['card_no']);
						 // $number =  $card_ids['card_no'];
                         $masked =  str_pad(substr($number, -4), strlen($number), '*', STR_PAD_LEFT);
			             echo $masked; ?></option>
                       <?php } }  ?>
                        </optgroup>
                        </select>

               
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="pay_park">
                  Pay and Park </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>