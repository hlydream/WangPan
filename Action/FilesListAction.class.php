<?php
	class FileslistAction extends Action{
		public function Lists(){
			$files = new FilesModel();
			$lists = $files->GetFilesList();
			$i=0;
			while($file = each($lists)){
				if(is_file(UPLOAD_DIR.'/'.$file['value']['m_new_name'])){
					$lists[$i]['del']=1;
				}
				else{
					$lists[$i]['del']=0;
				}
				$lists[$i]['m_time']=date("Y-m-d H:i:s",$file['value']['m_time']);
				if($size = $this->ByteFormat($file['value']['m_size'], "KB", 0) > 1024){
					$size = $this->ByteFormat($file['value']['m_size'], "MB", 0);
				}else{$size = $this->ByteFormat($file['value']['m_size'], "KB", 0);}
				$lists[$i]['m_size']= $size;
				$i++;
			}
			$server = $GLOBALS['config']['server']['host'];
			$this->view->assign('username',$_SESSION['user']['m_username']);
			$this->view->assign('ip',$_SESSION['user']['m_last_login_ip']);
			$this->view->assign('time',date('Y-m-d H:i:s',$_SESSION['user']['m_last_login_time']));
			$this->view->assign('zzurl',$server);
			$this->view->assign('lists',$lists);
			$this->view->assign('count',$i);
			$this->view->display('file-list.html');
		}
	}