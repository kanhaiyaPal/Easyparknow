<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />-->
<?php  require_once('library/initialize.php'); ?> 
<link href="<?php echo ROOTPATH; ?>/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>





<div class="top-nav">
<div class="container-fluid">

<div class="row">
<div class="col-md-4">
<i class="fa fa-volume-control-phone" aria-hidden="true"></i> +91-8585858585
</div>

<div class="col-md-8">
<ul>
  <?php    if(!isset($_SESSION['userlogged']['username'])){ ?>
  <li><a href="index.php">Log in</a></li>
  <li><a href="signup.php">Sign up</a></li>
<?php } else { ?>
<li><a href="parking.php">MY Parking</a></li>
 <li><a href="account.php">MY Account</a></li>
 <li><a href="logout.php">Log out</a></li>
  
<?php } ?>
</ul>
</div>
</div>


</div>
</div>


<?php /*?><header data-spy="affix" data-offset-top="250">
<div class="container-fluid">
<div class="logo"><a href="index.php"><img src="<?php echo ROOTPATH; ?>/images/logo.png" alt="Photo Sketch Paintings" /></a></div>


<div class="h-right">
<div class="t-menu">
<div class="wsmenucontainer">
  <div class="wsmenuexpandermain slideRight"><a id="navToggle" class="animated-arrow slideLeft" href="#"><span></span></a></div>
  <div class="wsmenucontent overlapblackbg"></div>
  <div>
 <!--Menu HTML Code-->
<nav class="wsmenu slideLeft clearfix">
		<ul class="mobile-sub wsmenu-list">    
       <!-- Dynamic pages--> 

        
<!-- Dynamic pages End-->                 
        </ul>
</nav>
</div> 
</div>
</div>

</div>


<div class="h-account">
<ul>
	
    <li><a href="addtocart"><img src="<?php echo ROOTPATH; ?>/images/icon-cart.png" alt="cart" /></a></li>  
      <?php if(!isset($_SESSION['uuser_id'])) {?>
    <li><a href="register.php"><img src="<?php echo ROOTPATH; ?>/images/icon-user.png" alt="cart" /></a></li>
     <li><a href="login.php"><img src="<?php echo ROOTPATH; ?>/images/icon-login.png" alt="cart" /></a></li>
     <?php } else {?>
    <li><a href="uploadimages.php"><img src="<?php echo ROOTPATH; ?>/images/icon-myaccount.png" alt="cart" /></a></li>
     <li><a href="logout.php"><img src="<?php echo ROOTPATH; ?>/images/icon-logout.png" alt="cart" /></a></li>
    <?php } ?>
</ul>
</div>



<div class="clear"></div>

</div>
</header><?php */?>