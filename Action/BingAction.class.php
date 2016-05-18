<?php
	class BingAction extends Action{
		#定义两个类成员变量  必应壁纸的api  
		protected $url_left ='http://cn.bing.com/HPImageArchive.aspx?format=js&idx=';
		protected $url_right ='&n=1';
		public function GetBing(){
			#这个参数是获取今天壁纸还是前几天的默认为0今天
			$data = defined('DATA') ? DATA : 0;
			$url = $this->url_left.$data.$this->url_right;
			$json = file_get_contents($url);
			$res=json_decode($json, true);
			$title = $res['images']['0']['msg']['0']['text'];
			$url=$res['images']['0']['url'];
			#如果发现定义了W H常量代表URL传参过来了w h的值 那么开始构造壁纸的url
			if(defined('W')||defined('H')){
				$width=W;
				$height=H;
				$url=substr($url,0,strrpos($url,'_'))."_$width".'x'.$height.".jpg";
				#因为获取到了用户传入的宽 高 那么 $js $body_js两个参数就为空，防止运行自动获取屏幕的宽高
				$js='';
				$body_js='';
			}else{
				$url=substr($url,0,strrpos($url,'_'))."_";
				$body_js = "imgUrl()";
				#如果没有url没有传壁纸的高 宽参数就创建变量 $js 来获取到当前用户的分辨率 并且修改img标签里面的src地址
$js=<<<js
	<script>
		var url="$url";
		function imgUrl(){
			var image=document.getElementById("image"); 
            image.setAttribute("src",url+screen.width+'x'+screen.height+".jpg"); 
		}
	</script>
js;
			}
$html=<<<html
	<html>
	<head>
	<meta name="viewport" content="width=device-width,height=device-height, minimum-scale=0.1">
	<title>$title</title>
<style type="text/css">
 body {
	background-image: url(http://www.lzi520.com/Public/sdshare/images/bg.png);
	background-repeat: repeat;
	background-color: #9cd9f2;
}
</style>
$js
</head>
	<body onload="$body_js">
		<div>
			 <img id="image" src="$url">
		</div>
	</body>
	</html>
html;
			echo $html;
		}
	}
?>


    