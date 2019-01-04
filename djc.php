<?php
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
#header("Content-Type:text/html;charset=gb2312");
header("Access-Control-Allow-Origin:*");
#var_dump($_REQUEST);
#die('1');
define("IS_PROXY",false);
define("REPLAYCOUNT",5);
$jumpold=false;
//$comicfind = array("神明之胄","斗破苍穹","狐妖小红娘","君临臣下","君临","全职高手","无耻","杀神","暴君","十八禁","18禁","行尸走肉");
//$comicreplace = array("神明_之胄","斗破_苍穹","狐妖_小红娘","君L臣下","君L","全职_高手","无_耻","杀_神","暴_君","十八_禁","18_禁","行尸_走肉");
require_once('common.php');

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
	//curl_setopt( $curlHandle , CURLOPT_HEADER, 1);
	//curl_setopt( $curlHandle , CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2141.400 QQBrowser/9.5.10219.400');
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 ); 
	//curl_setopt( $curlHandle , CURLOPT_NOBODY, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_FOLLOWLOCATION, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout ); 
	if(IS_PROXY){
	curl_setopt($curlHandle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	curl_setopt($curlHandle, CURLOPT_PROXY, "127.0.0.1"); //代理服务器地址
	curl_setopt($curlHandle, CURLOPT_PROXYPORT, 1080); //代理服务器端口
	//curl_setopt($curlHandle, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
	curl_setopt($curlHandle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	}
	
	$result = curl_exec( $curlHandle ); 
	curl_close( $curlHandle ); 
	#file_put_contents("Log.txt",date("Y-m-d H:i:s",time())." ".$url."\n",FILE_APPEND);
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
if(isset($_REQUEST['comicName']) && isset($_REQUEST['cfilename']) && isset($_REQUEST['cfilelist']))
{
	
	$comicName=$_REQUEST['comicName'];
	$comicName=str_replace($comicfind,$comicreplace,$comicName);
	$filename=$_REQUEST['cfilename'];
	$filename=str_replace($comicfind,$comicreplace,$filename);
	$time=$_REQUEST['ctime'];
	$maxno=$_REQUEST['maxno'];
	$fno=intval($_REQUEST['no'])+1;
	$no=str_pad($fno,strlen('0000'),"0",STR_PAD_LEFT);
	$filename=ckf(SBC_DBC($filename,0));
	$savename = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]").".zip");
	$savepath = gbk($comicName.'/'."[$no]".$filename.(is_null($time)?"":"[$time]"));
	$filelist=$_REQUEST['cfilelist'];
	
	if(strlen($comicName)==0){
		$result=array('state'=>'4','msg'=>"[$no]".$filename.(is_null($time)?"":"[$time]")." 资料不全！");
		die(json_encode($result));
	}
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
				//if($v=="http://dajiaochong.517w.com/statics/images/shop.png")continue;
				//if(is_file($savepath.'/'.$cname) && getimagesize($savepath.'/'.$cname))
				if(checkImg($savepath.'/'.$cname))
				{
					continue;
				}else{
					#alog(var_export($errorList,true));
					array_push($errorList,array($v,$savepath.'/'.$cname));
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
/*
echo '123123';
$img=curl_get_contents("http://pic.fxdm.cc/tu/undefined/君临臣下/384集 家暴现场/59eb10b4b5.jpg");
$img=curl_get_contents("http://pic.fxdm.cc/tu/undefined/%E5%90%9B%E4%B8%B4%E8%87%A3%E4%B8%8B/384%E9%9B%86%20%E5%AE%B6%E6%9A%B4%E7%8E%B0%E5%9C%BA/59c72401ff.jpg");
echo $img;
file_put_contents("1.jpg",$img);*/
die('-1');
?>
