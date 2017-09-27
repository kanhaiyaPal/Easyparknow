<?php 
require_once('../library/initialize.php'); 

if(isset($_POST['csrf_protect_token']) && ($_POST['csrf_protect_token'] == $_SESSION['crsf_protect_token'])){
	
	//login admin
	if(isset($_POST['admin_login']) && ($_POST['admin_login']!= ''))
	{

		$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
		
		$db->where ("username", $_POST['uname']);
		$user = $db->getOne ("users");
		if($user['id']){
			if(verify_pass($_POST['upass'],$user['password'])){
				unset($_SESSION['adminlogged']);

				$admin_permissions = '';
				if($user['user_type'] == '2'){ $admin_permissions = generate_subadmin_permissions(); }
				if($user['user_type'] == '3'){ $admin_permissions = generate_admin_permissions(); }
				
				$_SESSION['adminlogged'] = array(
						'email' => $user['email'],
						'username' => $user['username'],
						'user_type' => $user['user_type'],
						'permission' => $admin_permissions
					);
				header("Location:index.php");
				exit();
			}
		}
	}
	
	//logout admin
	if(isset($_POST['admin_logout']) && ($_POST['admin_logout']!=''))
	{
		unset($_SESSION['adminlogged']);
	}
}else{ 
	if(isset($_SESSION['adminlogged']) && ($_SESSION['adminlogged']!=''))
	{
		header("Location:index.php");
		exit();
	}else{
		unset($_SESSION['crsf_protect_token']);
		$_SESSION['crsf_protect_token'] = generate_token();
	}
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Easy Park Now Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-bg">
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-12">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.php">Admin Panel</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>
	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
			                <h6>Sign In</h6>
							<form action="" method="post" >
			                <input class="form-control" name="uname" type="text" placeholder="Username" required>
			                <input class="form-control" name="upass" type="password" placeholder="Password" required>
							<input type="hidden" name="csrf_protect_token" value="<?php echo $_SESSION['crsf_protect_token']; ?>" />
			                <div class="action">
			                    <input type="submit" class="btn btn-primary signup" name="admin_login" value="Login" />
			                </div>  
							</form>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>