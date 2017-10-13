<?php 
 require_once('library/initialize.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 $msg = '';
 $chek_available = '';
 $login = 0;
 if(isset($_SESSION['parkingdata']['thirdform']['vehicle_plate_no'])){
 
   $chek_available = $_SESSION['parkingdata']['thirdform']['parking_slot_no'];
   $login = 1;
 } 
//echo $chek_available;
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
 $db->where ("username", $_SESSION['userlogged']['username']);
 $user = $db->getOne ("users");
 
 $db->where ("user_id", $user['id']);
 $tbl_user_vehicle = $db->get("tbl_user_vehicle");
 if(isset($_POST['validate'])){
 
  $db->where ("name",$_POST['location_no']);

  $parkingLocation = $db->getOne("tbl_parking_slots");
		
		if(empty($_POST['vehicle_no']) || empty($_POST['location_no'])){
		  $msg = "please fill all fields";
		}
	 	else if(count($parkingLocation) > 0){
		   //  $db->where("parking_status",1);
			// $db->where("user_id",$user['id']);
			// $db->where("parking_slot_no",$_POST['location_no']);
		     //$parkingactive_user = $db->get("tbl_transactions");
			 
			 	$db->where("parking_slot_no",$_POST['location_no']);
			    $db->where("parking_status",0);
				$parkingactive_user = $db->get("tbl_transactions");
				 //$db->where("user_id",$user['id']);
			 
			 // $db->where("parking_slot_no",$_POST['location_no']);
			 // $db->where("parking_status",0);
		       //$parkingactive_user_inactive = $db->get("tbl_transactions");
				
			
		       // $parkingactive_user_same = $db->get("tbl_transactions");
			 //  print_r($parkingactive_user);
				if((count($parkingactive_user) == 0 ) || ((count($parkingactive_user) == 1)  && ($parkingactive_user[0]['user_id'] == $user['id'])) ) {
				//echo "Location is available";
				//die;
				unset($_SESSION['parkingdata']['firstform']);
						$_SESSION['parkingdata']['firstform'] = array(
								'vehicle_no' => $_POST['vehicle_no'],
								'location_no' =>$_POST['location_no']
							);
							
						header("Location:duration.php");
						exit();
				
				
					  } 
					  else {
					  
					 $msg = "Location is not available try other loacation no.";
					  }
		
			}else {
		
		$msg = "Enter wrong loacation no.";
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
            <h1 class="text-center login-title">Select vehicle and enter location.</h1>
            <div class="account-wall">
                <a href="addvehicle.php" style="color:#fff" class="btn  btn-info" role="button">+ Add Vehicle</a>
                <form class="form-signin" action="" method="post">
                
                        <select class="selectpicker" name="vehicle_no" required>
                        <optgroup label="Your Vehicle">
                         <option value="0">Select your vehicle</option>
                        <?php if(count($tbl_user_vehicle) > 0) {
						    $vehicleArr=array(1=>'Car',2=>'Motorcycle',3=>'Electric Motorcyle',4=>'Heavy Goods Vehicle');
						    foreach($tbl_user_vehicle as $uservehicle){
						?>
                        <option value="<?php echo $uservehicle['id']?>"><?php echo $vehicleArr[$uservehicle['vehicle_type']].' : '.$uservehicle['plate_no'] ?></option>
                       <?php } }  ?>
                        </optgroup>
                        </select>

                <input name="location_no" type="text" class="form-control" placeholder="Location" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="validate">
                  Validate </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>
<script>
function validation_user(){

}
</script>