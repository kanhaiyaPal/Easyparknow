<?php 
 require_once('library/initialize.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 }

 $msg = '';
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
 $db->where ("username", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
 if(isset($_SESSION['parkingdata']['firstform']) && !empty($_SESSION['parkingdata']['firstform'])){
 if(isset($_POST['continue'])){	
  if($_POST['minute_hour'] == 1) {
  $totalminutes = $_POST['timeduration'];
$now = time();
$minutes = $now + ($totalminutes * 60);
 $startDate = date('m/d/Y  H:i', $now);
 $endDate = date('m/d/Y  H:i', $minutes);

  }
  if($_POST['minute_hour'] == 2){
$timeduration = $_POST['timeduration'];
$totalminutes = $timeduration * 60;
$now = time();
$minutes = $now + ($timeduration * 60 * 60);
 $startDate = date('m/d/Y  H:i', $now);
 $endDate = date('m/d/Y  H:i', $minutes);
  }
 
 unset($_SESSION['parkingdata']['secondform']);
 
 if(empty($_POST['minute_hour']) || empty($_POST['timeduration']) ){
 $msg = "Please fill all fields";
 }
 else {
 
 	$_SESSION['parkingdata']['secondform'] = array(
						'minute_hour' => $_POST['minute_hour'],
						'timeduration' =>$_POST['timeduration'],
						'start_time' => $startDate,
						'expire_time' => $endDate,
						'totalminutes' => $totalminutes,
						'extend' => $_POST['extend'],
						'location_no' => $_POST['location_no']
						
					);
					
				header("Location:parknow.php");
				exit();
 }
			
			}
		
	}
	else{
	
	header("location:parkinglocation.php");
	}	
		
?>

<?php //$result_seo=mysql_fetch_array(mysql_query("select * from  tbl_seo where id='1'"));?>
<?php //$webmasterRow=mysql_fetch_array(mysql_query("select * from tbl_mobileemail where pkId = '2'"));?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />


<titleParking</title>
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
            <h1 class="text-center login-title">Select a Parking Duration</h1>
            <div class="account-wall">
               <div align="center"><?php echo   (isset($_POST['location_no'])) ? 'Parking : '.$_POST['location_no'] : 'Parking : '.$_SESSION['parkingdata']['firstform']['location_no']; ?> </div>
                <form class="form-signin" action="" method="post">
                
                   <input name="timeduration" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  maxlength="4" type="text" class="form-control" placeholder="Enter duration time" required>
                        <select class="selectpicker" name="minute_hour" required>
                        <optgroup label="Your Vehicle">
                         <option value="0">Select Time Type</option>
                         <option value="1">Minutes</option>
                         <option value="2">Hours</option>
                        </optgroup>
                        </select>
                      <input type="hidden" value="<?php echo (isset($_POST['extend'])) ? '1' : '0' ?>" name="extend">
                       <input type="hidden" value="<?php echo (isset($_POST['location_no'])) ? $_POST['location_no'] : '' ?>" name="location_no">
                     
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="continue">
                  Continue </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>