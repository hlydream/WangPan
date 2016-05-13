<?php
	class AdminModel extends DB{
		protected $table="admin";
		public function UserLogin($username,$password){
			$username = addslashes($username);
			$password = md5($password);
			$sql ="select * from {$this->GetTableName()} where m_username='{$username}' and m_pwd='{$password}' limit 1";
			if($user=$this->DB_GetRow($sql)){
				$time = time();
				$ip = $_SERVER["REMOTE_ADDR"];
				$sql = "update m_admin set m_last_login_time = {$time},m_last_login_ip='{$ip}' where m_username ='{$username}'";
				$this->DB_Update($sql);
				$_SESSION['user']=$user;
				return true;
			}else{
				return false;
			}
		}
	}