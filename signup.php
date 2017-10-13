<?php 
 require_once('library/defuse-crypto.phar');
 require_once('library/initialize.php');
 $msg = '';

 if(isset($_POST['register'])){
 $mobile = $_POST['mobile'];
 $email =  $_POST['email'];
 $password = $_POST['password'];
 

$form_errors =  validate_form($password,$email,$mobile);

$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
$db->where("mobile",$_POST['mobile']);
 $countmob = $db->getOne ("users");
 
 if(count($countmob) > 0){
   $form_errors[] = 'Mobile no. already exists'; 
 }
 
 $db->where ("email",$_POST['email']);
 $countemail = $db->getOne ("users");
  if(count($countemail) > 0){
   $form_errors[] = 'Email no. already exists'; 
 }
 
 
$err = '';
if(count($form_errors) > 0)
 {
  
    foreach($form_errors as $err){
	   
	   $msg .=  $err.'<br>';
	
	}
 }else 
 {

  
 
$protected_key_encoded = CreateUserAccount(hash_password($_POST['password']));

 $data = Array ("mobile" => $_POST['mobile'],
               "username" =>$_POST['mobile'],
			   "email" =>$_POST['email'],
               "password" => hash_password($_POST['password']),
			   "protected_key_encoded"=> $protected_key_encoded,
			   "user_type" => 1,
			   "added_date" => date('d-m-Y H:i')
);



$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

$id = $db->insert('users', $data);
if($id) {
$data_setting = Array("sms_notification" =>$_POST['sms_notification'],"mail_notification" =>0,"receipt_notification" =>0,"user_id"=>$id);
 if($db->insert('tbl_user_settings', $data_setting))
 {
$_SESSION['userlogged'] = array(
						'email' => $_POST['email'],
						'username' => $_POST['mobile'],
						'user_type' => 1,
						'permission' => 1,
						'protected_key_encoded'=> $protected_key_encoded
					);
					//print_r($_SESSION);
					//die;
				header("Location:parking.php");
				exit();
				}

}else {

$msg = "Username or Password is wrong";

}
}
/*if($id){ echo 'user was created. Id=' . $id; }else{
 //var_dump($id);
 }*/
 }
//include("include/connect.php");
//include("include/function.php");
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
            <div class="alert alert-success">
               <strong> <?php echo $msg; ?></strong>
            </div>
              <?php } ?>
            <h1 class="text-center login-title">Sign up to continue</h1>
            <div class="account-wall">
               
                <form class="form-signin" action="" method="post" name="register" >
                
                <input name="mobile" maxlength="10" type="tel" class="form-control" placeholder="Mobile" required autofocus>
                 <input name="email" type="email" class="form-control" placeholder="Email" required autofocus>
                <input name="password" type="password" class="form-control" placeholder="Password" required>
                  <label class="radio-inline">
                 SMS Notification
                </label>
                <label class="radio-inline">
                <input type="radio" name="sms_notification" checked value="1">Yes
                </label>
                <label class="radio-inline">
                <input type="radio" name="sms_notification" value="0">No
                </label>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">
                   Register </button>
            
                </form>
            </div>
           <!-- <a href="#" class="text-center new-account">Create an account </a>-->
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>