<?php

	//加密：  
    //密钥 把数据装入二进制（更安全）  
    $key = pack('H*', "bcb04b7e103a0cd8");  
    echo 'key::'.$key.'<Br><Br>';  
	
	$t=unpack('H*',$key);
	#$t=array(1,2,3,4,5);
	list($key, $val) = each($t);
	
	print_r($key);
	echo '<Br><Br>'; 
	print_r($val);
	
	echo '<Br><Br>'; 
	print_r($t);
	echo '<Br><Br>'; 
	#echo "key unpack:: " . unpack('H',$key) . "<br><br>\n";  
    //看下二进制数据长度  
    $key_size =  strlen($key);  
    echo "Key size: " . $key_size . "<br><br>\n";  
      
    $plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";  
  
    # create a random IV to use with CBC encoding  
    $iv_size = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_CBC);  
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    #$iv = pack('H*', "bcb04b7e103a0cd8"); 
    $iv_size =  strlen($iv);  
	echo "IV:: " . $iv . "<br><br>\n";  
	
	echo "IV size " . $iv_size . "<br><br>\n";  
	
    $ciphertext = mcrypt_encrypt(MCRYPT_DES, $key, $plaintext, MCRYPT_MODE_CBC, $iv);  
  
    $ciphertext = $iv . $ciphertext;  
      
    $ciphertext_base64 = base64_encode($ciphertext);  
    //输出密文（每次都不一样，更安全）  
    echo  '<Br><br>jia mi:::'.$ciphertext_base64 . "\n";  
  
//解密：  
    $ciphertext_dec = base64_decode($ciphertext_base64);      
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);      
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);
    $plaintext_dec = mcrypt_decrypt(MCRYPT_DES, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);  
      
    echo  '<Br><br>jie mi:::'.$plaintext_dec . "\n";  
    exit; 