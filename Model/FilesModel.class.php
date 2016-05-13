<?php
	class FilesModel extends DB{
		protected $table="files";
		public function SaveInMysql($path,$old,$new,$size,$type,$ip,$time){
			$key = md5($new);
			$sql = "insert into {$this->GetTableName()}(m_old_name,m_new_name,m_path,m_size,m_key,m_type,m_ip,m_time) values('{$old}','{$new}','{$path}','{$size}','{$key}','{$type}','{$ip}','{$time}')";
			return $this->DB_Insert($sql);
		}
		public function GetPicInfo($key){
			$key =addslashes($key);
			$sql = "select * from {$this->GetTableName()} where m_key='{$key}'";
			return $this->DB_GetRow($sql);
		}
		public function GetVideoInfo($key){
			$key =addslashes($key);
			$sql = "select * from {$this->GetTableName()} where m_key='{$key}'";
			return $this->DB_GetRow($sql);
		}
		public function GetMp3Info($key){
			$key =addslashes($key);
			$sql = "select * from {$this->GetTableName()} where m_key='{$key}'";
			return $this->DB_GetRow($sql);
		}
		public function GetDocumentInfo($key){
			$key =addslashes($key);
			$sql = "select * from {$this->GetTableName()} where m_key='{$key}'";
			return $this->DB_GetRow($sql);
		}
		public function GetFilesList(){
			$sql = "select m_id,m_new_name,m_old_name,m_key,m_type,m_size,m_time,m_path from {$this->GetTableName()}";
			return $this->DB_GetAll($sql);
		}
	}