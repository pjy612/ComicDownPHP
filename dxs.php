<?php
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
header("Access-Control-Allow-Origin:*");
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
function gbk($str)
{
	#return $str;
	return iconv('UTF-8', 'GBK//IGNORE', $str);
}
if(isset($_REQUEST['cname']) && isset($_REQUEST['no']) && isset($_REQUEST['bname']) && isset($_REQUEST['content']))
{
    $no=$_REQUEST['no'];
    $bname=ckf(SBC_DBC($_REQUEST['bname']));
    $cname=$_REQUEST['cname'];    
    $filename=ckf(SBC_DBC($cname,0));
    $content=$_REQUEST['content'];
    if(!is_dir(gbk($bname)))mkdir(gbk($bname));
    
    $savepath = gbk($bname.'/'."[$no]".$filename.".txt");    
    
    if(file_exists($savepath)){
        $result=array('state'=>'2','msg'=>"{$bname}_[$no]$cname 已存在！");
        die(json_encode($result));
    }
    file_put_contents($savepath,$cname."\r\n".$content);
    $result=array('state'=>'1','msg'=>"{$bname}_[$no]$cname 下载成功！");
    die(json_encode($result));
}
$result=array('state'=>'3','msg'=>"{$bname}_[$no]$cname 下载失败！");
die(json_encode($result));