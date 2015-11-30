<?php
	class Admin extends DB_Connect{

		private $_saltLength = 7;

		public function __construct($dbo=NULL,$saltLength=NULL){

			parent::__construct($dbo);

			if(is_int($saltLength)){
				$this->_saltLength = $saltLength;
			}

		}


		public function processLoginForm(){
			if($_POST['action']!='user_login'){
				return "不合法的请求";
			}

			$uname = htmlentities($_POST['uname'],ENT_QUOTES);
			$pword = htmlentities($_POST['pword'],ENT_QUOTES);


			$sql = "SELECT user_id, user_name, user_email, user_pass
			FROM users
			WHERE user_name = :uname LIMIT 1";
			try{
				$stmt = $this->dbo->prepare($sql);
				$stmt->bindParam(':uname',$uname,PDO::PARAM_STR);
				$stmt->execute();
				$user = array_shift($stmt->fetchAll());
				$stmt->closeCursor();
			}
			catch( Exception $e){
				die( $e->getMessage());
			}

			if(!isset($user)){
				return "ID不对哦";
			}

			$hash = $this->_getSaltedHash($pword, $user['user_pass']);

			if($user['user_pass']==$hash)
			{
				$_SESSION['user'] = array(
					'id'=>$user['user_id'],
					'name'=>$user['user_name'],
					'email'=>$user['user_email']);
				return TRUE;
			}
			else{
				return "ID或密码不对哦";
			}
		}

		private function _getSaltedHash($string, $salt=NULL){
			if($salt==NULL){
				$salt=substr(md5(time()),0,$this->_saltLength);
			}

			else{
				$salt=substr($salt, 0,$this->_saltLength);
			}
			return $salt.sha1($salt.$string);
		}

		public function testSaltedHash($string,$salt=NULL){
			return $this->_getSaltedHash($string,$salt);
		}

		public function processLogout(){
			if($_POST['action']!='user_logout'){
				return "不合法的请求";
			}

			session_destroy();
			return TRUE;
		}
	}
?>