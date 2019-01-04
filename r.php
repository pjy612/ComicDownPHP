<?php
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
header("Content-Type:text/html;charset=gb2312");
header("Access-Control-Allow-Origin:*");
function gbk($str)
{
	#return $str;
	return iconv('UTF-8', 'GBK//IGNORE', $str);
}
?>
<pre>
<?php
function ReplaceComicName($cname,$rdic)
{
	
}

$comicfind = array("神明之胄");
$comicreplace = array("神明_之胄");
$a="神明之胄_123456_神明之胄";
$b="VIPABC_123456_VIPABC";
print_r(str_replace($comicfind,$comicreplace,$a));

$dir=$_REQUEST['comic'];
$max=$_REQUEST['max'];
echo gbk($dir).'<br>';
echo $max.'<br>';;

echo $m.'<br>';;
$sd=scandir(gbk($dir));
#print_r($sd);

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
			$no=str_pad($no,$m,"0",STR_PAD_LEFT);
			$fname="[$no]".$c[2];
			#echo gbk($dir).'/'.$fname.'<br>';
			rename($baseDir.'/'.$d,$baseDir.'/'.$fname);
		}
	}
}
?>