<?php 
 require_once('library/initialize.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 
 $msg = '';
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
  $db->where ("mobile", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
		 $db->where ("user_id", $user['id']);
		$tbl_user_settings = $db->getOne ("tbl_user_settings");
if(isset($_POST['save_noti'])){
		$update_arr = array('sms_notification' => $_POST['sms_notification'],
		'mail_notification' => $_POST['mail_notification'],
		'receipt_notification' => $_POST['receipt_notification']);
		$db->where('user_id', $user['id'])->update('tbl_user_settings',$update_arr);
		//exit($db->getLastError());
		$db->where('id', $user['id'])->update('users',array('email'=>$_POST['email']));
		header('location:account.php');
	}	
?>

<?php //$result_seo=mysql_fetch_array(mysql_query("select * from  tbl_seo where id='1'"));?>
<?php //$webmasterRow=mysql_fetch_array(mysql_query("select * from tbl_mobileemail where pkId = '2'"));?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />


<title>Photo Sketch Painting</title>
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
               <p class="log"></p>
            <h1 class="text-center login-title"> Subscribe to receipts and reminders. </h1>
            <div class="account-wall">
               
                <form class="form-signin" action="" method="post" >
                <input id="emailid" name="email"  maxlength="16" type="text" class="form-control" placeholder="Email id " value="<?php echo $user['email']?>" required>
               <div class="form-group form-control">
               <label class="radio-inline">Email Reciepts</label>
               <label class="radio-inline"><input type="radio" value="1" <?php echo ($tbl_user_settings['mail_notification'] == 1) ? 'checked' : ''  ?> name="mail_notification">Yes</label>
               <label class="radio-inline"><input type="radio" value="0" <?php echo ($tbl_user_settings['mail_notification'] == 0) ? 'checked' : ''  ?> name="mail_notification">No </label>
               </div>
                <div class="form-group form-control">
                 <label class="radio-inline">Sms Reminders</label>
               <label class="radio-inline"><input type="radio" value="1" name="sms_notification" <?php echo ($tbl_user_settings['sms_notification'] == 1) ? 'checked' : ''  ?>>Yes</label>
               <label class="radio-inline"><input type="radio" value="0" name="sms_notification" <?php echo ($tbl_user_settings['sms_notification'] == 0) ? 'checked' : ''  ?>>No </label>
               </div>
                <div class="form-group form-control">
                 <label class="radio-inline">Sms Reciepts</label>
               <label class="radio-inline"><input type="radio" value="1" name="receipt_notification" <?php echo ($tbl_user_settings['receipt_notification'] == 1) ? 'checked' : ''  ?>>Yes</label>
               <label class="radio-inline"><input type="radio" value="0" name="receipt_notification" <?php echo ($tbl_user_settings['receipt_notification'] == 0) ? 'checked' : ''  ?>>No </label>
               </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="save_noti">
                  Saves  </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
 <script src="js/jquery.creditCardValidator.js"></script>
<script>

function cardvalidation() {
    $(function() {
        $('#card_no').validateCreditCard(function(result) {
		
		if(result.valid == false || result.length_valid == false || result.luhn_valid == false )  {
		
            $('.log').html('Card type: ' + (result.card_type == null ? '-' : result.card_type.name)
                     + '<br>Valid: ' + result.valid
                     + '<br>Length valid: ' + result.length_valid
                     + '<br>Luhn valid: ' + result.luhn_valid);
					 $('#cardname').val(result.card_type.name);
					 return false;
					 
					 } else {
					 $('#cardname').val((result.card_type == null ? '-' : result.card_type.name));
					 return true;
					 }
					
        });
    });

}
</script>
</body>
</html>