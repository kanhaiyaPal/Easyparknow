<?php 
 require_once('library/initialize.php');
 if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 $expiretime = '';
// $expire_parking_slot_no = 0;
 //print_r($_SESSION['parkingdata']['thirdform']);
 //echo $_SESSION['parkingdata']['thirdform']['expire_date_time'];
 
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
 $db->where ("username", $_SESSION['userlogged']['username']);
 $user = $db->getOne ("users");
 
 $db->where("user_id",$user['id']);
 $db->where("parking_status",0);
 $tbl_transactions = $db->get("tbl_transactions");	
 $expire_parking_slot_no = array(); 
 foreach($tbl_transactions as $expire_time){
 $expire_date_time = $expire_time['end_date'].' '.$expire_time['end_time'];
 $expiretime_new =  date('M d, Y H:i',strtotime($expire_date_time));
 $expire_parking_slot_no[] = array($expire_time['parking_slot_no']=>$expiretime_new);
  
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
<form id="extend" action="duration.php" method="post">
<input type="hidden" name="extend" >
<input type="hidden" id="location_no" name="location_no"  value="";>
</form>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="alert alert-danger">
             <a href="parkinglocation.php"> <button class="btn btn-lg btn-primary btn-block" type="submit" name="log_in">Start New Parking</strong></a>
            
            </div>
            <?php 
			if(count($expire_parking_slot_no) > 0) {
			foreach($expire_parking_slot_no as $eachparking ) {
			foreach($eachparking as $key=>$value){
			
			?>
              <div class="alert alert-danger">
              <p>Location No. : <?php echo $key; ?></p>
              Parking Expire time : <p id="demo<?php echo $key; ?>"></p>
             <button onClick="extend('<?php echo $key; ?>')" class="btn btn-lg btn-primary btn-block" type="submit" name="extend_time">Extend Time </button>
                
             </div>
          <?php } } } ?>
            </div>
</div>
</div>
<?php 
if(count($expire_parking_slot_no) > 0) {
foreach($expire_parking_slot_no as $eachdiv ) {
$i=0;
foreach($eachdiv as $expiretime=>$divid){
?>
<script>

 var countDownDate<?php echo $expiretime?> = new Date('<?php echo $divid; ?>').getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance<?php echo $expiretime?> = countDownDate<?php echo $expiretime?> - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance<?php echo $expiretime?> / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance<?php echo $expiretime?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance<?php echo $expiretime?> % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance<?php echo $expiretime?> % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById('demo<?php echo $expiretime; ?>').innerHTML = hours + "h "
    + minutes + "m " + seconds + "s ";
    
    // If the count down is over, write some text 
    if (distance<?php echo $expiretime?> < 0) {
        clearInterval(x);
        document.getElementById('demo<?php echo $expiretime; ?>').innerHTML = "EXPIRED";
    }
}, 1000);


</script>
<?php $i++;} } } ?>
<?php include"footer.php"?>

<script>
function extend(id){
$('#location_no').val(id);
$('#extend').submit();

}</script>



</body>
</html>