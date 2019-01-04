<?php
//require_once('common.php');
//$comicfind = array("神明之胄","无耻","十万个冷笑话","杀神","十八禁","18禁","狐妖小红娘","苏苏","情蛊","武神","独尊","色诱","暴走","食尸鬼","行尸走肉");
//$comicreplace = array("神明ZZ","无_耻","10W个冷笑话","杀_神","十八_禁","18_禁","狐妖_小红娘","苏_苏","情_蛊","武_神","色_诱","暴_走","SSG","行尸_走肉");
$comicfind=array();
$comicreplace=array();
require("common.php");

print_r($comicfind);
$filename='神明之胄狐妖小红娘';
$filename=str_replace($comicfind,$comicreplace,$filename);
echo $filename;
exit; 


/*
http://consultant.dev85.vipjr.com/ConsultantSystem/?conSn=xxx&de=1
*/