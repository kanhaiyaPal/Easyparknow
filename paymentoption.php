<?php 
 require_once('library/initialize.php');
  require_once('library/encryptclass.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 $msg = '';
		$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
		$db->where ("username", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
		
		$db->where ("user_id", $user['id']);
		$tbl_user_cc = $db->get("tbl_user_cc");
	 
if(isset($_POST['delid'])){
$db->where('id', $_POST['delid']);
if($db->delete('tbl_user_cc')) 
{
    $msg = 'Card removed';
	header("location:paymentoption.php");
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
            <h1 class="text-center login-title">Payment Options</h1>
            <div class="account-wall">
             <form id ="form1" class="form-signin" action="" method="post">
                <a href="addcard.php" style="color:#fff" class="btn  btn-info" role="button">+ Add Payment option</a>
                <?php 
				if(count($tbl_user_cc) > 0 ) {
				foreach($tbl_user_cc as $carddetail) {?>
                <div class="alert alert-info fade in alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" id="remove" onClick="removecard()" title="close">×</a>
               <p> <strong>Card No : </strong> 
			   <?php
			   $key = "23c34e56fSdrtWrg"; // Encryption Key
               $crypt = new Encryption($key);
			   $number =  $crypt->decrypt($carddetail['card_no']);
               $masked =  str_pad(substr($number, -4), strlen($number), '*', STR_PAD_LEFT);
			   echo $masked;
			     ?></p>
                 <p> <strong>EXP Date : </strong> <?php echo  $carddetail['expiry_date']?></p>
                </div>
                <?php }} ?>
               
                <input type="hidden" name="delid"  id="delid" value="<?php echo (isset($carddetail['id'])) ? $carddetail['id'] : '' ?>">
                      
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>
<script>
function removecard(){
var con =  confirm('Are you sure want to remove card');
if(con)
$("#form1").submit();
}

</script>
</body>
</html>