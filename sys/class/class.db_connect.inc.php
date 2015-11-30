<?php

	class DB_Connect {

		protected $dbo;

		protected function __construct($dbo=NULL){

			if(is_object($dbo)){
				$this->dbo = $dbo;
			}
			else{
				//后面的字符集参数是说指定使用utf8的格式从PDO拿过来数据
				$dsn = "mysql:host=". DB_HOST . ";dbname=" . DB_NAME.";charset=utf8";
				try{
					$this->dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
				}
				catch( Exception $e){
					die($e->getMessage());
				}
			}
		}
	} 
?>