<?php
	class DB{
		/*创建DB类所需变量
		 *$host       主机地址
		 *$mysql_name 数据库用户名
		 *$mysql_pwd  数据库密码
		 *$names      数据库名字
		 *$db_name    数据库表前缀
		 *$link       连接数据库资源
		 *$action     当前动作
		 *$count      当前产生的记录数
		 */
		private $host;
		private $sql_name;
		private $sql_pwd;
		private $names;
		private $db_name;
		private $prefix;
		private $link;
		private $action;
		private $count;
		private $type;
		private $stmt;
		private $fetch_type=2;
		#构造函数
		/*
		 *初始化数据库的各个变量，主机地址，用户名，密码，字符集，数据库名
		 *初始化连接，调用三个方法，数据库连接方法，数据库设置字符集方法，数据库选择方法
		*/
		public function __construct(){
			$this->host = $GLOBALS['config']['sql']['host'] == '' ? "localhost" : $GLOBALS['config']['sql']['host'];
			$this->sql_name = $GLOBALS['config']['sql']['sql_name'] == '' ? "root" : $GLOBALS['config']['sql']['sql_name'];
			$this->sql_pwd = $GLOBALS['config']['sql']['sql_pwd'] == '' ? "" : $GLOBALS['config']['sql']['sql_pwd'];
			$this->names = $GLOBALS['config']['sql']['names'] == '' ? "utf8" : $GLOBALS['config']['sql']['names'];
			$this->db_name = $GLOBALS['config']['sql']['db_name'] == '' ? "mysql" : $GLOBALS['config']['sql']['db_name'];
			$this->prefix = $GLOBALS['config']['sql']['prefix'] == '' ? "" : $GLOBALS['config']['sql']['prefix'];
			$this->type = $GLOBALS['config']['sql']['type'] == '' ? "mysql" : $GLOBALS['config']['sql']['type'];
			//连接数据库
			$this->ConnectMysql();
			//设置错误显示
			$this->SetMyErrorMode();
			//设置字符集
			$this->SetNames();
		}
		#连接数据库
		private function ConnectMysql(){
			try {
				$this->link = new PDO("{$this->type}:host={$this->host};dbname={$this->db_name}","{$this->sql_name}","{$this->sql_pwd}");
			}catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
			$this->action = "连接数据库";
		}
		#异常处理
		private function SetMyErrorMode(){
			$this->link->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		#设置字符集
		private function SetNames(){
			$sql = "set names {$this->names}";
			$this->action = "设置字符集{$this->names}";
			$this->MyExec($sql);
		}
		#查询数据库
		/*
		 *根据传入参数$sql语句
		 *调用mysql_query()方法
		 *调用Tips方法判断结果
		 *成功后返回结果集
		*/
		private function MyExec($sql){
			try{
				$res = $this->link->exec($sql);
				return $res;
			}catch(PDOException $e){
				echo $e->getMessage();
				return false;
			}
		}
		#设置数据获取模式
		private function SetMyFetchMode(){
			//设置获取模式
			$this->stmt->setFetchMode($this->fetch_type);
		}
		#获取完成表名字
		protected function GetTableName(){
			return $this->prefix.'_'.$this->table;
		}
		/*数据库操作 增删改查方法*/
		#增
		/*
		 *成功返回自增ID
		 *失败返回FALSE
		 */
		public function DB_Insert($sql){
			$this->action ="插入数据";
			return	$this->MyExec($sql);
		}
		#删除数据
		/*
		 *成功返回影响行数
		 *失败返回FALSE
		 */
		public function DB_Delete($sql){
			$this->action ="删除数据";
			return	$this->MyExec($sql);
		}
		#改
		/*
		 *成功返回影响行数
		 *失败返回FALSE
		 */
		public function DB_Update($sql){
			$this->action ="更新数据";
			return $this->MyExec($sql);
		}
		#查
		/*查询数据库两种
		  *单条数据DB_GetRow()
		  *多条数据DB_GetAll()
		  */
		  #查询单条数据
		public function DB_GetRow($sql){
			$this->action = "查询单条数据";
			$this->stmt = $this->link->query($sql);
			//设置数据获取模式
			$this->SetMyFetchMode();

			//获取数据
			return $this->stmt->fetch();
		}
		  #查询多条数据
		public function DB_GetAll($sql){
			$this->action = "查询多条数据";
			$this->stmt = $this->link->query($sql);
			$this->SetMyFetchMode();
			$lists = array();
			while($list = $this->stmt->fetch()){
				$lists[]=$list;
			}
			return $lists;
		}
	}