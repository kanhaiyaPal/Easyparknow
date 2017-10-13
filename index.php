<?php 
 require_once('library/defuse-crypto.phar');
 require_once('library/initialize.php');
 $msg = '';
 if(isset($_POST['log_in'])){
		$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
		$db->where ("username", $_POST['uname']);
		$user = $db->getOne ("users");
	
		if($user['id']){
			if(verify_pass($_POST['upass'],$user['password'])){
				unset($_SESSION['userlogged']);

				$user_permissions = '1';
				/*if($user['user_type'] == '2'){ $admin_permissions = generate_subadmin_permissions(); }
				if($user['user_type'] == '3'){ $admin_permissions = generate_admin_permissions(); }*/
				
				$_SESSION['userlogged'] = array(
						'email' => $user['email'],
						'username' => $user['username'],
						'user_type' => $user['user_type'],
						'protected_key_encoded'=> $user['protected_key_encoded'],
						'permission' => $user_permissions
					);
					//print_r($_SESSION);
					//die;
				header("Location:parking.php");
				exit();
			}
		}
		else {
		
		$msg = "Username or Password is wrong";
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
               <strong>  Enter wrong username or password</strong>
            </div>
              <?php } ?>
            <h1 class="text-center login-title">Sign in to continue</h1>
            <div class="account-wall">
               
                <form class="form-signin" action="" method="post">
                <input name="uname" type="text" class="form-control" placeholder="Mobile no" required autofocus>
                <input name="upass" type="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="log_in">
                    Log in</button>
               <!-- <label class="checkbox pull-left">
                    <input type="checkbox" value="remember-me">
                    Remember me
                </label>
                <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>-->
                </form>
            </div>
            <a href="signup.php" class="text-center new-account">Create an account </a>
        </div>
    </div>
</div>

<?php include"footer.php"?>

</body>
</html>