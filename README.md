# WangPan
个人网盘
目录说明
1：Core 核心文件类 包含了网站入口文件Application核心类 DB数据库类 Action动作类
2：Lib  扩展文件夹 包含了网站非类的功能文件 命名格式是：文件夹首字母大写其余小写 文件命名规则是与文件夹同名但是全部小写 
3：Model 模型类文件 用于操作数据库的各种类
4：Action 控制器类 用于各种功能实现，调用模型类完成功能的实现
5：Conf 配置文件
a:网站支持Pathinfo模式 访问方式为 网址+控制器+方法后面参数名与参数值成对出现
比如访问登录页面  www.lzi520.com/privilege/login   privilege为控制器在Action文件夹中，login为PrivilegeAction中的一个方法叫login
b:Lib下的文件访问功能
比如访问Lib下的sz文件夹下的sz.php文件 因为他不是类 我们又想直接访问这个php文件 不需要方法之类的 就可以用
www.lzi520.com/index/index 即可
