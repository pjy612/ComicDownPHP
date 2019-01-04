<?php
function curl_get_contents($url,$timeout=30) { 
	$curlHandle = curl_init(); 
	curl_setopt( $curlHandle , CURLOPT_URL, $url ); 
	//curl_setopt( $curlHandle , CURLOPT_HEADER, 1);
	//curl_setopt( $curlHandle , CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2141.400 QQBrowser/9.5.10219.400');
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 ); 
	//curl_setopt( $curlHandle , CURLOPT_NOBODY, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_FOLLOWLOCATION, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout ); 
	//curl_setopt($curlHandle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	//curl_setopt($curlHandle, CURLOPT_PROXY, "127.0.0.1"); //代理服务器地址
	//curl_setopt($curlHandle, CURLOPT_PROXYPORT, 1080); //代理服务器端口
	//curl_setopt($curlHandle, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
	//curl_setopt($curlHandle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	
	$result = curl_exec( $curlHandle ); 
	curl_close( $curlHandle ); 
	#file_put_contents("Log.txt",date("Y-m-d H:i:s",time())." ".$url."\n",FILE_APPEND);
	return $result; 
} 
$cfilelist=array('http://img5.cdn.u17i.com/17/03/133715/2531531_1488528645_5i400O4uIII5.4ee33_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528647_x3VmM49Tva24.74960_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528649_ScO5s34lOXSF.281df_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528650_IQDa899RdFAl.3d8b7_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528652_UkKe066fg56p.f399e_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528652_zywgDyIGJgEY.da942_svol.jpg',
'http://img5.cdn.u17i.com/17/03/133715/2531531_1488528654_mFQZ9wwdMyrx.df1d0_svol.jpg',);
/*
$img=file_get_contents($cfilelist[0]);
$img=curl_get_contents($cfilelist[0]);
file_put_contents("1.jpg",$img);

*/
$img=file_get_contents('http://img3.u17i.com/18/01/98063/1220_1515743316_gepRr37O7g8A.73bcd_svol.jpg');
$img=curl_get_contents('http://img3.u17i.com/18/01/98063/1220_1515743316_gepRr37O7g8A.73bcd_svol.jpg');
echo $img;
file_put_contents("1.jpg",$img);
exit;

$img1="175_0121.jpg";
$img2="175_0122.jpg";
$r1=getimagesize($img1);
$r2=getimagesize($img2);
echo "<pre>";
var_dump(strpos(getimagesize($img1)['mime'],"jpeg"));
var_dump(getimagesize($img1));
var_dump(@imagecreatefromjpeg($img1));
var_dump(getimagesize("1.gif"));
var_dump(@imagecreatefromgif("1.gif"));
var_dump(getimagesize("1.png"));
var_dump(@imagecreatefrompng("1.png"));

/*
var_dump(@imagecreatefromjpeg( $img1 ));
var_dump(@imagecreatefromjpeg( $img2 ));
var_dump(@imagecreatefrompng( $img1 ));
var_dump(@imagecreatefrompng( $img2 ));
var_dump($r1);
var_dump($r2);
var_dump(get_extension($img1));
*/
function get_extension($file)
{
return pathinfo($file, PATHINFO_EXTENSION);
}