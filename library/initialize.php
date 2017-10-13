<?php
define('VALIDATE',true);
if($_SERVER['HTTP_HOST']=="localhost"){
	define('DBHOST','localhost');
	define('DBNAME','easyparknow_db');
	define('DBUSER','root');
	define('DBPASS',''); 
}else{
	define('DBHOST','localhost');
	define('DBNAME','capitalhunt_db');
	define('DBUSER','root');
	define('DBPASS',''); 
}

date_default_timezone_set('Asia/Calcutta');
define('IMGPATH',dirname(__FILE__) . '/../uploads');
define('ROOTPATH',dirname("http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
define('CURURL',"http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);


@session_start();
require_once( dirname(__FILE__) . '/MysqliDb.php');
require_once( dirname(__FILE__) . '/common_function.php');
require_once( dirname(__FILE__) . '/users_function.php');

?>