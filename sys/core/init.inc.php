<?php
	if(!isset($_SESSION)){
		session_start();
	}
	if(!isset($_SESSION["token"])){
		$_SESSION["token"] = sha1(uniqid(mt_rand(),TRUE));
	}

	include_once '../sys/config/db-cred.inc.php';

	foreach ($C as $name => $value) {
		define($name,$value);
	}
	//后面的字符集参数是说指定使用utf8的方式从PDO拿过来数据
	$dsn = "mysql:host=". DB_HOST . ";dbname=" . DB_NAME.";charset=utf8";
	$dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

	function __autoload($class){
		$filename = "../sys/class/class." . strtolower($class) . ".inc.php";
		if(file_exists($filename)){
			include_once $filename;
		}
	}

?>


