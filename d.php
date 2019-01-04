<?php
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
header("Access-Control-Allow-Origin:*");
#header("Content-Type:text/html;charset=gb2312");
#var_dump($_REQUEST);
#die('1');
define("IS_PROXY",false);
define("REPLAYCOUNT",5);

//$comicfind = array("神明之胄","无耻","十万个冷笑话","杀神","十八禁","18禁","狐妖小红娘","苏苏","情蛊","武神","独尊","色诱","暴走","食尸鬼","行尸走肉");
//$comicreplace = array("神明ZZ","无_耻","10W个冷笑话","杀_神","十八_禁","18_禁","狐妖_小红娘","苏_苏","情_蛊","武_神","色_诱","暴_走","SSG","行尸_走肉");
require('common.php');

$jumpold=false;
function ckf($filename){
	$SBC = Array( // 半角
		':', '/', '<', '>', '"', '?', '\\', '|' , '*'
	);
	$fl=str_split($filename);
	foreach($fl as $k=>$v)
	{
		if(in_array($v,$SBC))
			$fl[$k]=urlencode($v);
	}
	return implode($fl);
}
function SBC_DBC($str, $args2) {
    $DBC = Array(
        '０' , '１' , '２' , '３' , '４' ,
        '５' , '６' , '７' , '８' , '９' ,
        'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
        'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
        'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
        'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
        'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
        'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
        'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
        'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
        'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
        'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
        'ｙ' , 'ｚ' , '－' , '　' , '：' ,
        '．' , '，' , '／' , '％' , '＃' ,
        '！' , '＠' , '＆' , '（' , '）' ,
        '＜' , '＞' , '＂' , '＇' , '？' ,
        '［' , '］' , '｛' , '｝' , '＼' ,
        '｜' , '＋' , '＝' , '＿' , '＾' ,
        '￥' , '￣' , '｀'
    );
	$DBC = Array(
		'：' ,'／' , '＜' , '＞' , '＂' ,'？' ,'＼' ,'｜' ,'×'
	);
	// / \ : * ? \" < > |
    $SBC = Array( // 半角
        '0', '1', '2', '3', '4',
        '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i',
        'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x',
        'y', 'z', '-', ' ', ':',
        '.', ',', '/', '%', '#',
        '!', '@', '&', '(', ')',
        '<', '>', '"', '\'','?',
        '[', ']', '{', '}', '\\',
        '|', '+', '=', '_', '^',
        '$', '~', '`'
    );
	$SBC = Array( // 半角
		':', '/', '<', '>', '"', '?', '\\', '|' , '*'
	);
    if ($args2 == 0) {
        return str_replace($SBC, $DBC, $str);  // 半角到全角
    } else if ($args2 == 1) {
        return str_replace($DBC, $SBC, $str);  // 全角到半角
    } else {
        return false;
    }
}

function curl_get_contents($url,$timeout=30) { 
	$curlHandle = curl_init();
	curl_setopt( $curlHandle , CURLOPT_URL, $url ); 
    //curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	//curl_setopt( $curlHandle , CURLOPT_HEADER, 1);
	//curl_setopt( $curlHandle , CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.4549.400 QQBrowser/9.7.12900.400');
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 ); 
	//curl_setopt( $curlHandle , CURLOPT_NOBODY, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_FOLLOWLOCATION, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout ); 
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
	if(IS_PROXY){
        curl_setopt($curlHandle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        curl_setopt($curlHandle, CURLOPT_PROXY, "127.0.0.1"); //代理服务器地址
        curl_setopt($curlHandle, CURLOPT_PROXYPORT, 1080); //代理服务器端口
        //curl_setopt($curlHandle, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
        curl_setopt($curlHandle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	}
	
	$result = curl_exec( $curlHandle ); 
	curl_close( $curlHandle ); 
    //echo strlen($result)."<br>";
	#file_put_contents("Log.txt",date("Y-m-d H:i:s",time())." ".$url."\n",FILE_APPEND);
    if(strlen($result)<=0)
    {
        $result=file_get_contents($url);
    }
	return $result; 
} 
function gbk($str)
{
	#return $str;
	return iconv('UTF-8', 'GBK//IGNORE', $str);
}
function get_extension($file)
{
	$ext=pathinfo($file, PATHINFO_EXTENSION);
	if(strpos($ext,'jpg')||strpos($ext,'gif')||strpos($ext,'png'))
		return $ext;
	return "jpg";
}
function alog($str)
{
	file_put_contents("Log.txt",date("Y-m-d H:i:s",time())." ".$str."\n",FILE_APPEND);
}
function checkImg($file)
{
	if(is_file($file))
	{
		$image=getimagesize($file);
		#alog($file.var_export($image,true));
		if($image){
			$mime=$image["mime"];
			if(strpos($mime,'jpeg')) return @imagecreatefromjpeg($file);
			if(strpos($mime,'gif')) return @imagecreatefromgif($file);
			if(strpos($mime,'png')) return @imagecreatefrompng($file);
			return true;
		}
	}
	return false;
}
function renameDir($dir,$max)
{
	$baseDir=gbk($dir);
	$sd=scandir($baseDir);
	$m=strlen($max);
	foreach($sd as $d)
	{
		//echo $d;
		if(preg_match('/^\[(\d+)\](.*)/',$d,$c))
		{
			#print_r($c);
			$no=$c[1];
			if(strlen($no)!=$m){
				$no=str_pad($no,$m,"0",STR_PAD_LEFT);
				$fname="[$no]".$c[2];
				#echo gbk($dir).'/'.$fname.'<br>';
				rename($baseDir.'/'.$d,$baseDir.'/'.$fname);
			}
		}
	}
}
if(isset($_REQUEST['comicName']) && isset($_REQUEST['cfilename']) && isset($_REQUEST['cfilelist']))
{
	$comicName=$_REQUEST['comicName'];	
	$comicName=str_replace($comicfind,$comicreplace,$comicName);
	$comicName=ckf(SBC_DBC($comicName,0));
	$filename=$_REQUEST['cfilename'];
	$filename=str_replace($comicfind,$comicreplace,$filename);
	$time=$_REQUEST['ctime'];
	$maxno=intval($_REQUEST['maxno']);
	@renameDir($comicName,$maxno);
	$fno=intval($_REQUEST['no'])+1;
	$no=str_pad($fno,strlen($maxno),"0",STR_PAD_LEFT);
	$filename=ckf(SBC_DBC($filename,0));
	$savename = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]").".zip");
	$savepath = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]"));
	$filelist=$_REQUEST['cfilelist'];
	if($jumpold && !is_null($time))
		if(strtotime($time)<strtotime('2017-01-01')||strtotime($time)<strtotime('-31 days'))
		{
			$result=array('state'=>'3','msg'=>"[$no]".$filename.(is_null($time)?"":"[$time]")." 时间太早跳过！");
			die(json_encode($result));
		}
	/*
	if(is_file($savename)){
		$result=array('state'=>'2','msg'=>"[$no]".$filename."[$time].zip已存在！");
		die(json_encode($result));
	}
	*/
	if(!is_dir(gbk($comicName)))mkdir(gbk($comicName));
	if(!is_dir($savepath))mkdir($savepath);
	if(count($filelist)>0)
	{
		/*
		$zip=new ZipArchive();
		if($zip->open($savename,ZipArchive::OVERWRITE)===TRUE){
			foreach($filelist as $k=>$v){
				$img=curl_get_contents($v);
				$k=intval($k)+1;
				$k=str_pad($k,3,"0",STR_PAD_LEFT);
				$cname=$no.'_'.$k.'.jpg';
				$zip->addFromString($cname,$img);//假设加入的文件名是image.txt，在当前路径下
			}
			$zip->close(); 
		}
		*/
		$rc=0;
		$errorList=[];
		while(true){
			if($rc>REPLAYCOUNT){
				file_put_contents(gbk("Error_".$comicName."_".$filename.".txt"),$_REQUEST."\r\n".var_export($errorList,true));
				$result=array('state'=>'-1','msg'=>"[$no]".$filename.(is_null($time)?"":"[$time]")." 下载失败！");
				die(json_encode($result));
			}
			$alldown=true;
			foreach($filelist as $k=>$v){
				$k=intval($k)+1;
				$k=str_pad($k,3,"0",STR_PAD_LEFT);
				$cname=$fno.'_'.$k.'.'.get_extension($v);
				//if(is_file($savepath.'/'.$cname) && getimagesize($savepath.'/'.$cname))
				if(checkImg($savepath.'/'.$cname))
				{
					continue;
				}else{
					#alog(var_export($errorList,true));
					array_push($errorList,$savepath.'/'.$cname);
				}
				$alldown=false;
				$img=curl_get_contents($v);
				file_put_contents($savepath.'/'.$cname,$img);
			}
			if($alldown)
				break;
			$rc++;
		}
		$result=array('state'=>'1','msg'=>"[$no]".$filename.(is_null($time)?"":"[$time]")." 下载完成！");
		die(json_encode($result));
	}
}
else if(isset($_REQUEST['comicName']) && isset($_REQUEST['filename'])&& isset($_REQUEST['filelist']))
{
	#var_dump($_REQUEST);
	$comicName=$_REQUEST['comicName'];
	$comicName=str_replace($comicfind,$comicreplace,$comicName);
	$filename=$_REQUEST['filename'];
	$filename=str_replace($comicfind,$comicreplace,$filename);
	$time=$_REQUEST['time'];
	$maxno=$_REQUEST['maxno'];
	$fno=intval($_REQUEST['no'])+1;
	$no=str_pad($fno,strlen($maxno),"0",STR_PAD_LEFT);
	$filename=ckf(SBC_DBC($filename,0));
	$savename = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]").".zip");
	$savepath = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]"));
	$filelist=$_REQUEST['filelist'];
	
	if(!is_dir(gbk($comicName)))mkdir(gbk($comicName));
	if(!is_dir($savepath))mkdir($savepath);
	if(count($filelist)>0)
	{
		while(true){
			$alldown=true;
			foreach($filelist as $k=>$v){
				$k=intval($k)+1;
				$k=str_pad($k,3,"0",STR_PAD_LEFT);
				$cname=$fno.'_'.$k.'.jpg';
				if(is_file($savepath.'/'.$cname) && getimagesize($savepath.'/'.$cname) && @imagecreatefromjpeg($savepath.'/'.$cname))
					continue;
				$alldown=false;
				$img=curl_get_contents($v);
				file_put_contents($savepath.'/'.$cname,$img);
			}
			if($alldown)
				break;
		}
		#if(!is_dir(gbk($comicName)))mkdir(gbk($comicName));
		#copy($savename,gbk($comicName.'/'."[$time]".$filename.".zip"));
		die('1');
	}
}
else if(isset($_REQUEST['filename'])&& isset($_REQUEST['filelist']))
{
	$filename=$_REQUEST['filename'];
	$filename=str_replace($comicfind,$comicreplace,$filename);
	$time=$_REQUEST['time'];
	$filename=ckf($filename);
	$savename = gbk("".is_null($time)?"":"[$time]".".zip");
	$filelist=$_REQUEST['filelist'];
	if(count($filelist)>0)
	{
		$zip=new ZipArchive();
		if($zip->open($savename,ZipArchive::OVERWRITE)===TRUE){
			foreach($filelist as $data){
				$img=curl_get_contents($data['value']);
				$zip->addFromString($data['key'].'.jpg',$img);//假设加入的文件名是image.txt，在当前路径下
			}
			$zip->close(); 
		}
		
		#if(!is_dir(gbk($comicName)))mkdir(gbk($comicName));
		#copy($savename,gbk($comicName.'/'."[$time]".$filename.".zip"));
		die('1');
		
	}
}
else if(isset($_REQUEST['t']))
{
	/*
	$zip=new ZipArchive; 
	$filename="你好.zip";
	$filename = iconv('UTF-8', 'GBK//IGNORE', $filename);
	if($zip->open($filename,ZipArchive::OVERWRITE)===TRUE){ 
	  $img=file_get_contents('http://img9.cdn.u17i.com/16/11/75859/4431881_1479719881_3yLg8Y8383he.87625_svol.jpg');
	  $zip->addFromString('image.jpg',$img);//假设加入的文件名是image.txt，在当前路径下 
	  $zip->close(); 
	  die('1');
	} 
	
	*/
	/*
	$filename="星STAR_196 / \ : * ? \" < > | 第一百七十三话 帮手[u17]";
	$SBC = Array( // 半角
		':', '/', '<', '>', '"', '?', '\\', '|' , '*'
	);
	$fl=str_split($filename);
	foreach($fl as $k=>$v)
	{
		if(in_array($v,$SBC))
			$fl[$k]=urlencode($v);
	}
	$fl=implode($fl);
	var_dump($fl);
	*/
}
//$img=file_get_contents("http://img8.u17i.com/17/01/38368/1594_1485077316_WfcBbP9Yp7VV.21e24_svol.jpg");
//file_put_contents("1.jpg",$img);
die('-1');
?>
