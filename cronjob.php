<?php 
 require_once('library/initialize.php');
$db = new MysqliDb(DBHOST,DBUSER,DBPASS,DBNAME);

 $date = date("Y-m-d H:i:s");
 $time = strtotime($date);
 $time = $time + (10 * 60);
 $mail_date_time = date("Y-m-d H:i", $time);
 $mail_date = date('Y-m-d',strtotime($mail_date_time));
 $mail_time = date('H:i',strtotime($mail_date_time));
		$db->where("end_date",$mail_date);
		$db->where("end_time",$mail_time);
		$db->where("mail_send",0);
		$tbl_transactions = $db->get("tbl_transactions");
	
 	
if(count($tbl_transactions) > 0){
		foreach($tbl_transactions as $mail_send){
		$db->where ("id", $mail_send['user_id']);
		$user = $db->getOne ("users");
		//echo $user['email'].'<br>';
		
		$data_update = Array ('mail_send' => '1');
        $db->where ('id',$mail_send['id']);
        $db->update ('tbl_transactions', $data_update);
        
		
		}
}


$deactive_date_time = date("Y-m-d H:i");		
$deactive_date = date('Y-m-d',strtotime($deactive_date_time));
$deactive_time = date('H:i',strtotime($deactive_date_time));	
 		
		$db->where("end_date",$deactive_date);
		$db->where("end_time",$deactive_time);
		$db->where("parking_status",0);
		$close_parking = $db->get("tbl_transactions");
		
		
if(count($close_parking) > 0){

		foreach($close_parking as $deactive_parking){
		
				$data_update = Array ('parking_status' => '1');
				$db->where ('id',$deactive_parking['id']);
				$db->update ('tbl_transactions', $data_update);
		}


}		
?>

