<?php
	class BingAction extends Action{
		protected $url_left ='http://cn.bing.com/HPImageArchive.aspx?format=js&idx=';
		protected $url_right ='&n=1';
		public function GetBing(){
			$data = defined('DATA') ? DATA : 0;
			$url = $this->url_left.$data.$this->url_right;
			$width = defined('W')? W : 1920;
			$height = defined('H')? H : 1080;
			$json = file_get_contents($url);
			$res=json_decode($json, true);
			$url=$res['images']['0']['url'];
			$url=substr($url,0,strrpos($url,'_'))."_$width".'x'.$height.".jpg";
$html=<<<html
	<html>
	<head>
	<meta name="viewport" content="width=device-width,height=device-height, minimum-scale=0.1">
	<title>Bing</title>
	</head>
	<body style="margin: 0px;">
		<img id="img" style="-webkit-user-select: none; cursor: zoom-in;" src="$url" width="auto" height="auto">
	</body>
	</html>
html;
			echo $html;
		}
	}
?>


    