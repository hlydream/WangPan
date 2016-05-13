<?php
	class UploadAction extends Action{
		protected $error ='';
		#加载上传文件模板
		public function UploadFile(){
			$this->view->display("upload-file.html");
		}
		#上传类
		public function Upload(){
			/*
			 *1.获取当前时间作为文件名和上传时间的记录
			 *2.获取当前上传者的IP地址记录
			 *3.获取上传文件的信息 $file_name上传文件名 $file_type文件类型 $file_tmpname文件缓存名 $file_size文件大小
			 *4.调用ErrorReport 判断文件上传是否错误，如果错误报错
			 *5.为文件重命名
			 *6.移动文件至选定的文件夹
			 *7.移动成功后，将文件信息存入数据库 文件的新名字 旧名字 文件相对路径 文件类型 文件类型 上传者IP 上传者时间 
			 *8.如果失败则提示失败信息
			 *9.如果成功，则输出文件的新名字，和文件的唯一码key，和查看文件的连接地址
			*/
			if(isset($_FILES['file'])){
				$time = time();
				$ip =$_SERVER['REMOTE_ADDR'];
				$file_name = $_FILES['file']['name'];
				$file_type = substr($file_name,strrpos($file_name,'.')+1);
				$file_tmpname = $_FILES['file']['tmp_name'];
				$file_error = $_FILES['file']['error'];
				$file_size = $_FILES['file']['size'];
				$this->ErrorReport($file_error);
				if($this->error!=''){
					echo "上传失败原因：".$this->error;
					exit;
				}
				$file_newname = UploadAction::GetNewName($file_name);
				if(move_uploaded_file($file_tmpname,UPLOAD_DIR . '/' . $file_newname)){
					#成功
					$file_path='/Upload/' . $file_newname;
					if($this->SaveInMySql($file_path,$file_name,$file_newname,$file_size,$file_type,$ip,$time)){
						echo "上传成功，文件地址是：".$file_path.'<br>';
						echo "key:                ".md5($file_newname)."<br>";
						$this->GetDocumentUrl($file_type,$file_newname,$GLOBALS['config']['server']['host'],$file_name);
					}else{
						echo "数据库存储失败";
					}
				}else{
					#失败
					return false;
				}
			}else{		
				echo "上传错误";
			}			
		}
		#上传文件错误处理
		protected function ErrorReport($errorNo) {
			 switch($errorNo) {
				case 0:	$this->error='';	
					break;
				case 1:
					$this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
					break;
				case 2:
					$this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
					break;
				case 3:
					$this->error = '文件只有部分被上传';
					break;
				case 4:
					$this->error = '没有文件被上传';
					break;
				case 6:
					$this->error = '找不到临时文件夹';
					break;
				case 7:
					$this->error = '文件写入失败';
					break;
				default:
					$this->error = '未知上传错误！';
			}
			return ;
		}
		#上传文件重命名
		private static function GetNewName($filename){
			#获取文件的后缀名
			$extension = substr($filename,strrpos($filename,'.'));

			#生成新名字
			$newname = date('YmdHis');

			#拼凑随机字符串
			$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			for($i = 0;$i < 6;$i++){
				$newname .= $str[mt_rand(0,strlen($str) - 1)];
			}

			#拼凑文件名
			return $newname . $extension;
		}
		#将文件信息存入数据库
		private static function SaveInMysql($path,$old,$new,$size,$type,$ip,$time){		
			$files = new FilesModel();
			return $files->SaveInMysql($path,$old,$new,$size,$type,$ip,$time);
		}
		#提供链接地址
		public function GetDocumentUrl($filetype,$filename,$hosturl,$file_old_name){
			/*
			 *1.基于同文件，输出上传文件查看连接
			*/
			$filename = md5($filename);
			switch($filetype){
				case 'mp3': echo '文件链接：'."<a href='$hosturl"."files/mp3view/keys/"."$filename' target='_blank'>".$file_old_name."</a>";  
					break;
				case 'mp4':	echo '文件链接：'."<a href='$hosturl"."files/videoview/keys/"."$filename' target='_blank'>".$file_old_name."</a>";	
					break;
				case 'flv':	echo '文件链接：'."<a href='$hosturl"."files/videoview/keys/"."$filename' target='_blank'>".$file_old_name."</a>";	
					break;
				case 'avi':	echo '文件链接：'."<a href='$hosturl"."files/videoview/keys/"."$filename' target='_blank'>".$file_old_name."</a>";	
					break;
				case 'jpg':	echo '文件链接：'."<a href='$hosturl"."files/picview/keys/"."$filename' target='_blank'>".$file_old_name."</a>";	
					break;
			    default : echo '文件链接：'."<a href='$hosturl"."files/documentview/keys/"."$filename' target='_blank'>".$file_old_name."</a>";
					break;
			}
		}
		#提供链接地址跳转
		public function GoToView($filetype,$filename,$hosturl,$file_old_name){
			$filename = md5($filename);
			switch($filetype){
				case 'mp3': header("Location:$hosturl"."files/mp3view/keys/$filename");  
					break;
				case 'mp4':	header("Location:$hosturl"."files/videoview/keys/$filename"); 	
					break;
				case 'jpg':	header("Location:$hosturl"."files/picview/keys/$filename"); 	
					break;
			    default :	header("Location:$hosturl"."files/documentview/keys/$filename"); 
					break;
			}
		}
	}