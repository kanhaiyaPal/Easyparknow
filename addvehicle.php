<?php 
 require_once('library/initialize.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 
 $msg = '';
if(isset($_POST['add_vehicle'])){
$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
        $db->where ("mobile", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
		
		$params = Array($user['id'], $_POST['licence_plate']);
        $plateno = $db->rawQuery("SELECT id FROM tbl_user_vehicle WHERE user_id = ? AND plate_no = ?", $params);
		
		//$emptychek = check_empty($name,$fieldname);
      
		if(count($plateno) > 0 ){
		
		$msg = "Vehicle already added";
		} else if(empty($_POST['licence_plate'])){
		
		$msg = "Licence plate no. is empty";
		} else {
				 $data = Array ("user_id" =>$user['id'],
               "vehicle_type" =>$_POST['vehicle_type'],
               "plate_no" => $_POST['licence_plate']
			 
);

	
$id = $db->insert('tbl_user_vehicle', $data);
		if($id ){
	$msg = "Vehicle added";
	header("location:vehicledetail.php");
	}
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
               <strong> <?php echo $msg; ?></strong>
            </div>
              <?php } ?>
            <h1 class="text-center login-title">Add Vehicle</h1>
            <div class="account-wall">
               
                <form class="form-signin" action="" method="post">
                        <select class="selectpicker" name="vehicle_type">
                        <optgroup label="Vehicle Type">
                        <option value="1">Car</option>
                        <option value="2">Motorcycle</option>
                        <option value="3">Electric Motorcyle</option>
                        <option value="4">Heavy Goods Vehicle</option>
                        </optgroup>
                        </select>

                <input name="licence_plate" type="text" class="form-control" placeholder="License Plate " required>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="add_vehicle">
                  Add Vehicle </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>