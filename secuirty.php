<?php 
 require_once('library/initialize.php');
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
        $msg = '';
		$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
		$db->where ("username", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
		if(isset($_POST['change_pass'])) {
		
		
	if(verify_pass($_POST['old_password'],$user['password']) && ($_POST['new_password'] == $_POST['confirm_password'])) {
	     	$db->where('id', $user['id'])->update('users', ['password' => hash_password($_POST['new_password'])]);
			$msg = "Updated succefully";
			header('account.php');
		}else {
			
		$msg = "Old Password not match";
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
            <h1 class="text-center login-title">Change your account password. Current Password/Pin</h1>
            <div class="account-wall">
             <form id ="form1" class="form-signin" action="" method="post" onSubmit="return changepassword()">
               <input type="text" name="old_password"  id="old_password" placeholder="Current Password" value="">
               <input type="text" name="new_password"  id="new_password"  placeholder="New Password" value="">
               <input type="text" name="confirm_password"  id="confirm_password"  placeholder="Confirm New Password" value="">
               <button class="btn btn-lg btn-primary btn-block" type="submit" name="change_pass"> Save Changes   
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php"?>
<script>
function changepassword(){
$('#old_password').val();
var new_pass = $('#new_password').val();
var confirm_pass = $('#confirm_password').val();
if(new_pass == confirm_pass ){
return true;
} else {
alert('password not confirm');
return false;
}

}

</script>
</body>
</html>