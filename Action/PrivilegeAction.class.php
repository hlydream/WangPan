<?php
	class PrivilegeAction extends Action{
		public function Login(){
			$this->view->display(strtolower(ACTION).".html");
		}
		public function signup(){
			$this->view->display(strtolower(ACTION).".html");
		}
		public function Signin(){
			$username = isset($_POST['username']) ? $_POST['username'] : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			$admin_login = new AdminModel();
			if($admin_login->UserLogin($username,$password)){
				header("Location:../index/index");
			}else{
				echo "密码错误";
			}
		}
	}