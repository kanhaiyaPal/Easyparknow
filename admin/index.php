<?php 
require_once('../library/initialize.php'); 

if((!isset($_SESSION['adminlogged']))|| ($_SESSION['adminlogged'] == ''))
{
	header("Location:login.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Easy Park Now Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery UI -->
    <link href="https://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" media="screen">
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
  <body>
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-10">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.php">Admin Panel</a></h1>
	              </div>
	           </div>
	           <div class="col-md-2" style="padding:15px">
				  <form action="login.php" method="post"><input type="hidden" name="csrf_protect_token" value="<?php echo $_SESSION['crsf_protect_token']; ?>" /><input type="submit" name="admin_logout" value="Logout"/></form>
	           </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-2">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                	<?php if(($_SESSION['adminlogged']['user_type']) == '2'):  //subadmin menu ?>

                	<?php endif; ?>
                	<?php if(($_SESSION['adminlogged']['user_type']) == '3'):  //master admin menu ?>

                		<li class="current"><a href="#"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
	                  <li class="submenu <?=get_menu_status_admin(array('contractor_new','contractor_list','contractor_edit','manage_town','manage_location'))?>" >
							        <a href="#"><i class="glyphicon glyphicon-calendar"></i> Parkings</a>
        							<ul>
                          <li><a href="index.php?page=manage_town">Towns</a></li>
                          <li><a href="index.php?page=manage_location">Locations</a></li>
                          <li><a href="index.php?page=contractor_list">List Parkings</a></li>
                          <li><a href="index.php?page=contractor_new">Add New Parking</a></li>
        							</ul>
        						</li>
                    <li class="submenu <?=get_menu_status_admin(array('usage_parking_history','usage_generate_reports'))?>">
                      <a href="#"><i class="glyphicon glyphicon-stats"></i> Usage Statistics</a>
                      <ul>
                          <li><a href="index.php?page=usage_parking_history">User Parking History</a></li>
                          <li><a href="index.php?page=usage_generate_reports">Generate Reports</a></li>
                      </ul>                      
                    </li>
	                <?php endif; ?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-10">
		  	<div class="content-box-large">
  				<div class="panel-body">
  					<div class="row">
  						<?php 
                $include_folder = '';

                if($_SESSION['adminlogged']['user_type'] == '3'){
                  $include_folder = 'master';
                }
                if($_SESSION['adminlogged']['user_type'] == '2'){
                  $include_folder = 'subadmin';
                }

               if(isset($_REQUEST['page'])&&($_REQUEST['page']!=''))
                {
                  $pg=$include_folder.'/'.$_REQUEST['page'].".php";
                }else{
                    $pg=$include_folder.'/'."dashboard.php";      
                 }
                include($pg); 
               ?>
  					</div>
  				</div>
  			</div>
		  </div>
		</div>
    </div>
	<footer>
    <div class="container">
        <div class="copy text-center">
           Copyright 2017 <a href='#'>Gap Infotech</a>
        </div>
    </div>
  </footer>

  <link href="vendors/datatables/dataTables.bootstrap.css" rel="stylesheet" media="screen">
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery.js"></script>
  <!-- jQuery UI -->
  <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?=ROOTPATH?>/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=ROOTPATH?>/vendors/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?=ROOTPATH?>/vendors/datatables/dataTables.bootstrap.js"></script>

  <?php if(isset($require_contractor_script) && ($require_contractor_script)): ?>
  <script src="<?=ROOTPATH?>/js/contractor.js"></script>
  <?php endif; ?>

  <?php if(isset($require_locationtarrif_script) && ($require_locationtarrif_script)): ?>
  <script src="<?=ROOTPATH?>/js/location_tarrif.js"></script>
  <?php endif; ?>
  
  <script src="<?=ROOTPATH?>/js/custom.js"></script>
  <script src="<?=ROOTPATH?>/js/tables.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

  <?php if(isset($require_contractor_edit_script) && ($require_contractor_edit_script)): ?>
  <script  type="text/javascript">
  $(document).ready(function(){ 
    var dataString = "town_id=<?=$town_id?>&function=getlocationnames_by_townid"; 
    $.ajax({ 
      type: "POST", 
      url: "master/ajax_handler.php", 
      data: dataString, 
      success: function(result){
        $("select[name='contra_location']").html(result); 
        $("select[name='contra_location']").val('<?=$parking_data['location_id']?>');
      }
    });  
  });
  </script>
  <?php endif; ?>
  </body>
</html>