<?php
	class IndexAction extends Action{
		public function Index(){
			$this->view->assign('username',$_SESSION['user']['m_username']);
			$this->view->assign('zzurl',$GLOBALS['config']['server']['host']);
			$this->view->display("index.html");
		}
	}