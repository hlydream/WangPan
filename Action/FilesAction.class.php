<?php
	class FilesAction extends Action{
		#加载图片模板
		public function PicView(){
			/*
			 *1.判断是否传入参数文件keys，如果参数为空则对出
			 *2.如果存在keys就进行数据库查询，查询文件信息，如果查询不到则提示文件被删除退出
			 *3.查到文件之后，进行文件信息用smarty分配，然后输出
			*/
			if(KEYS==''){
				echo "参数为空";exit;
			}
			$key = KEYS;
			$pic = new FilesModel();
			if(!$picinfo = $pic->GetPicInfo($key)){
				echo "文件不存在或者已被删除";exit;
			}
			$server = $GLOBALS['config']['server']['host'];
			$this->view->assign("tit",$picinfo['m_old_name']); 
			$this->view->assign("filename",$picinfo['m_new_name']); //文件名
			$this->view->assign("fileurl",$server.$picinfo['m_path']); //外链URL
			$this->view->assign("head",$picinfo['m_old_name']); 
			$this->view->assign('zzurl',$server);
			$this->view->assign("filetype",explode('.',$picinfo['m_new_name'])[1]); //文件扩展名 
			$this->view->assign("key", $key);
			$this->view->display("pic_view.html"); 
		}
		#加载视频播放器模板
		public function VideoView(){
			/*
			 *1.判断是否传入参数文件keys，如果参数为空则对出
			 *2.如果存在keys就进行数据库查询，查询文件信息，如果查询不到则提示文件被删除退出
			 *3.查到文件之后，进行文件信息用smarty分配，然后输出
			*/
			if(KEYS==''){
				echo "参数为空";exit;
			}
			$key = KEYS;
			$video = new FilesModel();
			if(!$videoinfo = $video->GetVideoInfo($key)){
				echo "文件不存在或者已被删除";exit;
			}
			$server = $GLOBALS['config']['server']['host'];
			$this->view->assign("tit",$videoinfo['m_old_name']); 
			$this->view->assign("filename",$videoinfo['m_new_name']); //文件名
			$this->view->assign("fileurl",$server.$videoinfo['m_path']); //外链URL
			$this->view->assign("head",$videoinfo['m_old_name']); 
			$this->view->assign('zzurl',$server);
			$this->view->assign("filetype",explode('.',$videoinfo['m_new_name'])[1]); //文件扩展名 
			$this->view->assign("key", $key);
			$this->view->display("video_view.html");  // 输出页面
		}
		#加载MP3模板
		public function Mp3View(){
			/*
			 *1.判断是否传入参数文件keys，如果参数为空则对出
			 *2.如果存在keys就进行数据库查询，查询文件信息，如果查询不到则提示文件被删除退出
			 *3.查到文件之后，进行文件信息用smarty分配，然后输出
			*/
			if(KEYS==''){
				echo "参数为空";exit;
			}
			$key = KEYS;
			$mp3 = new FilesModel();
			if(!$mp3info = $mp3->GetMp3Info($key)){
				echo "文件不存在或者已被删除";exit;
			}
			$server = $GLOBALS['config']['server']['host'];
			$this->view->assign("tit",$mp3info['m_old_name']); 
			$this->view->assign("filename",$mp3info['m_new_name']); //文件名
			$this->view->assign("fileurl",$server.$mp3info['m_path']); //外链URL
			$this->view->assign("head",$mp3info['m_old_name']); 
			$this->view->assign('zzurl',$server);
			$this->view->assign("filetype",explode('.',$mp3info['m_new_name'])[1]); //文件扩展名 
			$this->view->assign("key", $key);
			$this->view->display("mp3_view.html");  // 输出页面
		}
		#文件类模板加载
		public function DocumentView(){
			/*
			 *1.判断是否传入参数文件keys，如果参数为空则对出
			 *2.如果存在keys就进行数据库查询，查询文件信息，如果查询不到则提示文件被删除退出
			 *3.查到文件之后，进行文件信息用smarty分配，然后输出
			*/
			if(KEYS==''){
				echo "参数为空";exit;
			}
			$key = KEYS;
			$doc = new FilesModel();
			$docinfo = $doc->GetDocumentInfo($key);
			$server = $GLOBALS['config']['server']['host'];
			$this->view->assign("tit",$docinfo['m_old_name']); 
			$this->view->assign("filename",$docinfo['m_new_name']); //文件名
			$this->view->assign("fileurl",$server.$docinfo['m_path']); //外链URL
			$this->view->assign("head",$docinfo['m_old_name']); 
			$this->view->assign('zzurl',$server);
			$this->view->assign("filetype",explode('.',$docinfo['m_new_name'])[1]); //文件扩展名 
			$this->view->assign("key", $key);
			$this->view->display("else_view.html"); 
		}
		#图片信息查询
		public function PicInfo(){
			$key = KEYS;
			$pic = new FilesModel();
			$picinfo = $pic->GetPicInfo($key);
			$picinfo['m_time']=date("Y-m-d H:i:s",$picinfo['m_time']);
			echo $picinfo['m_ip'].'|'.$picinfo['m_size'].'|'.$picinfo['m_type'].'|'.$picinfo['m_time'];
		}
		#视频信息查询
		public function VideoInfo(){
			$key = KEYS;
			$video = new FilesModel();
			$videoinfo = $video->GetVideoInfo($key);
			$videoinfo['m_time']=date("Y-m-d H:i:s",$videoinfo['m_time']);
			echo $videoinfo['m_ip'].'|'.$videoinfo['m_size'].'|'.$videoinfo['m_type'].'|'.$videoinfo['m_time'];
		}
		#mp3信息查询
		public function Mp3Info(){
			$key = KEYS;
			$mp3 = new FilesModel();
			$mp3info = $mp3->GetMp3Info($key);
			$mp3info['m_time']=date("Y-m-d H:i:s",$mp3info['m_time']);
			echo $mp3info['m_ip'].'|'.$mp3info['m_size'].'|'.$mp3info['m_type'].'|'.$mp3info['m_time'];
		}
		#文件类信息查询
		public function DocumentInfo(){
			$key = KEYS;
			$doc = new FilesModel();
			$docinfo = $doc->GetDocumentInfo($key);
			$docinfo['m_time']=date("Y-m-d H:i:s",$docinfo['m_time']);
			echo $docinfo['m_ip'].'|'.$docinfo['m_size'].'|'.$docinfo['m_type'].'|'.$docinfo['m_time'];
		}
		
	}