<?php
	class Application{
		/*初始化
		 *1.定义字符集
		 *2.定义系统常量
		 *3.定义错误信息处理
		 *4.自动加载
		 *5.session开启
		 *6.加载配置文件
		 *7.获取Url
		 *8.权限验证
		 *9.分发
		*/
		#1.定义字符集
		private static function SetHeader(){
			header("Content-type:text/html;charset=utf8");
		}
		#2.定义系统目录常量
		private static function SetConst(){
			/*
			 *系统根目录
			 *核心类目录
			 *系统配置文件目录
			 *系统公共文件目录
			 *系统后台目录
			 *系统扩展插件目录
			 */
			define("ROOT_DIR",str_replace("/Core",'',str_replace("\\","/",__DIR__)));
			define("CORE_DIR",  ROOT_DIR.'/Core');
			define("CONF_DIR",  ROOT_DIR.'/Conf');
			define("PUBLIC_DIR",ROOT_DIR.'/Public');
			define("ADMIN_DIR", ROOT_DIR.'/Admin');
			define("LIB_DIR",   ROOT_DIR.'/Lib');
			define("MODEL_DIR", ROOT_DIR.'/Model');
			define("ACTION_DIR",ROOT_DIR.'/Action');
			define("UPLOAD_DIR",ROOT_DIR.'/Upload');
		}
		#3.定义错误信息显示问题
		private static function SetErrors(){
			/*
			 *1.是否提示错误
			 *2.是否显示错误
			*/
			ini_set("errors_reporting",1);
			ini_set("display_errors",1);
		}
		#4.定义自动加载函数
		private static function SetAutoLoad(){
			/*
			 *1.注册自动加载Application类下的LoadCore函数
			 *2.注册自动加载Application类下的LoadAdmin函数
			 *3.注册自动加载Application类下的LoadModel函数
			 *4.注册自动加载Application类下的LoadAction函数
			 *5.注册自动加载Application类下的LoadLib函数
			*/
			spl_autoload_register(array("Application","LoadCore"));
			spl_autoload_register(array("Application","LoadAdmin"));
			spl_autoload_register(array("Application","LoadModel"));
			spl_autoload_register(array('Application','LoadAction'));
			spl_autoload_register(array("Application","LoadLib"));
		}
		/*自动加载函数
		 *1.自动加载核心类函数
		 *2.自动加载模型类函数
		 *3.自动加载后台类函数
		 *4.自动加载扩展函数(扩展函数是以扩展文件名首字母大写命名文件夹，加载非类的php文件)
		*/
		private static function LoadCore($class){
			if(is_file(CORE_DIR."/$class.class.php")){
				include_once CORE_DIR."/$class.class.php";
			}
		}
		private static function LoadAdmin($class){
			if(is_file(ADMIN_DIR."/$class.class.php")){
				include_once ADMIN_DIR."/$class.class.php";
			}
		}
		private static function LoadModel($class){
			if(is_file(MODEL_DIR."/$class.class.php")){
				include_once MODEL_DIR."/$class.class.php";
			}
		}
		private static function LoadLib($class){
			if(is_file(LIB_DIR."/$class/$class.class.php")){
				include_once LIB_DIR."/$class/$class.class.php";
			}
		}
		private static function LoadAction($class){
			if(is_file(ACTION_DIR."/$class.class.php")){
				include_once ACTION_DIR."/$class.class.php";
			}
		}
		#5.Session开启
		private static function SessionStart(){
			@session_start();
		}
		#6.加载配置文件
		private static function LoadConfig(){
			$GLOBALS['config']=include_once CONF_DIR."/config.php";
			define('HOST',$GLOBALS['config']['server']['host']);
		}
		#7.配置URL
		private static function SetUrl(){
			/*
			 *1.获取到全局变量$GLOBALS变量中的配置文件的变量URL模式 如果是1就是index.php?这种传参模式 如果不是1就是pathinfo模式
			 *2.获取地址栏中的模型，方法参数，如果获取不到参数则 $module $action 分别为privilege login调用登录
			 *3.并且将$module转换为首字母大写并且后面跟上Action  $action为全部小写 因为类区分大小写，方法不区分
			 *
			 *
			*/
			if($GLOBALS['config']['urlmodel']==1){
				$module = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : 'privilege';
				$action = isset($_REQUEST['ac'])  ? $_REQUEST['ac'] : 'login';
				$module = strtolower($module);
				$action = strtoupper($action);
				$module = ucfirst($module);
				define("MODULE",$module);
				define("ACTION",$action);
			}else{
				/*pathinifo模式
				 *1.讲获取到请求URL参数按照/分割并且存入到数组$lists中
				 *2.判断$lists中数量  如果小于1 则没有接收到传过来的参数 就申明变量 模型为privilege 方法为login让用户登录
				 *3.循环2次以内然后将$lists中前两个参数分别复制给变量 $module $action 
				 *4.循环大于2次之后判断$lists中的参数数量是否为偶数如果为偶数则表明参数 参数值成双成对出现 进行赋值
				 *5.如果参数个数为奇数，判断是否为3个 如果是三个就说明有参数没参数值，就让参数值为空 否则提示参数错误
				*/
				$url_list = explode('/',$_SERVER['REQUEST_URI']);
				$names = array('module','action');
				//申明一个数组用于接收网址里面的所有参数
				$lists=array();
				for($i=0;$i<count($url_list);$i++){
					if($url_list[$i]!=''){
						$lists[]=$url_list[$i];
					}			
				}
				#这种情况是没有传参过来
				//事先申明$action参数防止因为没有$action这个变量导致提示notice问题
				$action='';
				if(count($lists)<1){
					$module = "privilege";
					$action = "login";
				}else{
					for($i=0;$i<count($lists);$i++){
						if($i<2)
						{
							$$names[$i]=$lists[$i];
						}else{
							if(count($lists)%2==0){
								define(strtoupper($lists[$i]),$lists[$i+1]);
								$i++;
							}else{
								if(count($lists)==3){
									define(strtoupper($lists[$i]),'');
								}else{
									echo "参数错误";
									exit;
								}
							}
						}		
					}
				}
				#当获取到的参数值为一个的时候，这个时候是没有方法名字的所以申明一个方法变量
				#防止$action为空，产生错误
				if($action==''){
					$action='noaction';
				}
				$module = strtolower($module);
				$action = strtoupper($action);
				$module = ucfirst($module);
				define("MODULE",$module);
				define("ACTION",$action);
			}			

		}
		#8.权限验证
		private static function SetPrivilege(){
			if(MODULE!='Privilege'&&(ACTION!='login'&&ACTION!='captcha'&&ACTION!='signin')){
				if(!$_SESSION['user']){
					header("Location:".HOST);
				}
			}
		}
		#9.分配
		private static function SetModuleAndAction(){
			/*
			 *判断是否有这个类和这个类的方法调用CallAble方法
			 *有这个类之后 new一个对象
			 *并且调用这个方法
			*/
			$module = MODULE.'Action';
			if(Application::CalledAble($module,ACTION)){
				$module = new $module();
				$action = ACTION;			
				$module->$action();
			}							
		}
		#10.判断方法是否存在
		private static function CalledAble($mod,$ac){
			/*
			 *1.如果这个类不存在就调用Lib方法是否存在扩展的php文件如果还是没有就输出类不存在然后返回false
			 *2.如果类存在，就判断方法是否存在，如果不存在就输出方法不存在返回false
			*/
			if(!class_exists($mod)){
				if(!Application::Lib(MODULE,$ac)){
					echo $mod."类不存在";
					return false;
				}
				return false;
			}else{
				if(!method_exists($mod,$ac)){
					echo $ac."方法不存在";
					//exit;
					return false;
				}
			}
			return true;
		}
		#11.增加扩展插件功能
		private static function Lib($lib,$ac){
			/*
			 *1.判断是否存在扩展php文件
			 *2.判断格式为 扩展名字 扩展文件
			 *3.如果存在就包含非类php文件
			*/
			if(is_file(LIB_DIR."/$lib/$lib.php")){
				include_once LIB_DIR."/$lib/$lib.php";
				return true;
			}else{
				if(is_file(LIB_DIR."/$lib/$ac.php")){
					include_once LIB_DIR."/$lib/$ac.php";
					return true;
				}else{
					return false;
				}
			}
		}
		public static function Run(){
			Application::SetErrors();
			Application::SetHeader();
			Application::SetConst();
			Application::SessionStart();
			Application::SetAutoLoad();
			Application::LoadConfig();
			Application::SetUrl();
			Application::SetPrivilege();
			Application::SetModuleAndAction();
		}
	}