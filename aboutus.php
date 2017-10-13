<?php 
include("include/connect.php");
include("include/function.php");
?>
<?php $result_seo=mysql_fetch_array(mysql_query("select * from  tbl_seo where id='2'"));?>
<?php $webmasterRow=mysql_fetch_array(mysql_query("select * from tbl_mobileemail where pkId = '2'"));?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />


<title><?php echo stripslashes($result_seo['title']); ?></title>
<meta name="description" content="<?php echo stripslashes($result_seo['description']); ?>" />
<meta name="keywords" content="<?php echo stripslashes($result_seo['metatag']); ?>" />
<?php echo stripslashes($webmasterRow['analitic_code']); ?>
<?php echo stripslashes($webmasterRow['webmaster_code']); ?>



<link href="css/style.css" rel="stylesheet" type="text/css" />

<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />


<script type="text/javascript" src="megamenu/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="megamenu/css/webslidemenu.css" />
<script type="text/javascript" src="megamenu/js/webslidemenu.js"></script>
<link rel="stylesheet" href="megamenu/font-awesome/css/font-awesome.min.css" />


<link href="plugins/v_ticker/v_ticker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="plugins/v_ticker/jquery.vticker-min.js"></script>
<script type="text/javascript">
var $jq888 = jQuery.noConflict();
$jq888(function(){
	$jq888('#news-container').vTicker({ 
		speed: 500,
		pause: 8000,
		animation: 'fade',
		mousePause: false,
		showItems: 1
	});
});
</script>
</head>

<body>
<?php include"header.php"?>
<div class="container">
<?php $teamRow = mysql_fetch_array(mysql_query("select * from tbl_homecontent where id='2'"));?>
<ul class="breadcrumb">
  <li><a href="<?php echo $rootpath; ?>/index">Home</a></li>
  <li><a href="<?php echo $rootpath; ?>/aboutus" class="active">Overview</a></li>
</ul>


<div class="col-md-9 text-justify">

<h1><span><?php echo stripslashes($teamRow['heading']); ?></span></h1>
<p><?php echo stripslashes($teamRow['home_content']);?></p>
<p><?php echo stripslashes($teamRow['home_content1']);?></p>
<p><?php echo stripslashes($teamRow['home_content2']);?></p>
  <p>&nbsp;</p>
</div>
</div>

<?php include"footer.php"?>

</body>
</html>