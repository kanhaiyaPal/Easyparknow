<?php 
// require_once('library/defuse-crypto.phar');
 require_once('library/initialize.php');
 require_once('library/encryptclass.php');
 
  if(!isset($_SESSION['userlogged']) && $_SESSION['userlogged']['user_type'] != 1 ){
  header("location:login.php");;
 } 
 
 $db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);
        $db->where ("mobile", $_SESSION['userlogged']['username']);
		$user = $db->getOne ("users");
 //print_r($_SESSION);
 

 //use Defuse\Crypto\KeyProtectedByPassword;
 
//$protected_key_encoded = // ... load it from the user's account record
//$protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($user['password']);
//$user_key = $protected_key->unlockKey($_SESSION['userlogged']['protected_key_encoded']);
//$user_key_encoded = $user_key->saveToAsciiSafeString();


//use Defuse\Crypto\Crypto;
//use Defuse\Crypto\Key;

// ...

//$user_key_encoded = // ... get it out of the session ...
//$user_key = Key::loadFromAsciiSafeString($user_key_encoded);

// ...

//$credit_card_number = // ... get credit card number from the user
     //$encrypted_card_number = Crypto::encrypt($credit_card_number, $user_key);
// ... save $encrypted_card_number in the database
 
 $msg = '';
if(isset($_POST['add_card'])){

$key = "23c34e56fSdrtWrg"; // Encryption Key
$crypt = new Encryption($key);
 
$number = $_POST['card_no']; // your credit card number
 
$encrypted_card_number = $crypt->encrypt($number); // Encrypt your credit card number
 
//$decrypted_string = $crypt->decrypt($encrypted_string);
		
		$params = Array($user['id'], $_POST['card_no']);
        $card_no = $db->rawQuery("SELECT id FROM tbl_user_cc WHERE user_id = ? AND 	card_no = ?", $params);

					if (checkCreditCard($_POST['card_no'], $_POST['cardname'], $ccerror, $ccerrortext)) {
							if(count($card_no) > 0 ){
							$msg = "Card already added";
									}
									 else if(!preg_match('/^[0-9]{4}$/',$_POST['exp_date']) || !preg_match('/^[0-9]{4}$/',$_POST['start_date'])) {
									
									   $msg = "Enter correct  format start and expiry date ";
									
									
									
									} else {
							$data = Array ("user_id" =>$user['id'],
							"card_no" => $encrypted_card_number ,
							"issue_date" => $_POST['start_date'],
							"expiry_date" => $_POST['exp_date']
							);
							
							
							$id = $db->insert('tbl_user_cc', $data);
							if($id ){
							$msg = "Card added succesfully";
							}
							header("location:paymentoption.php");
							}
} else {
  
  $msg = 'Please check card no';
  
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
               <p class="log"></p>
            <h1 class="text-center login-title">Add Card</h1>
            <div class="account-wall">
               
                <form class="form-signin" action="" method="post" onSubmit="return cardvalidation()" >
                <input id="card_no" name="card_no"  maxlength="16" type="text" class="form-control" placeholder="Card Number " onBlur="cardvalidation()" required>
                <input name="cardname" type="hidden" id="cardname">
                 
                <input name="start_date" pattern="^[0-9/]+$" type="text" maxlength="4" class="form-control" placeholder="Start date  May 2010 as 0510" required>
                <input name="exp_date" pattern="^[0-9/]+$" type="text" maxlength="4" class="form-control" placeholder="Exp Date in  Dec  2021 as 1221" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="add_card">
                  Continue  </button>
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
		
		if(result.valid == false || result.length_valid == false )  {
		
           /* $('.log').html('Card type: ' + (result.card_type == null ? '-' : result.card_type.name)
                     + '<br>Valid: ' + result.valid
                     + '<br>Length valid: ' + result.length_valid
                     + '<br>Luhn valid: ' + result.luhn_valid);
					 $('#cardname').val(result.card_type.name);*/
					 
					  $('.log').html('Card type: ' + (result.card_type == null ? '-' : result.card_type.name)
                     + '<br>Length valid: ' + result.length_valid);
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