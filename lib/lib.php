<?php
// !!!!!!! наша универсальная функция приема и оптарвки данных. ммм - конфетка :]
function gotourl($url='', $userAgent='Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', $proxy='', $auth='', $post='', $referer='', $cookiesfile='', $httpopt='', $followlocation=1, $header_answer=0)  
    {  
		//$try = 5;
	  //while ( --$try > 0) {
        $cl = curl_init();    
        curl_setopt($cl, CURLOPT_URL, $url);  
        if($header_answer==1) curl_setopt($cl, CURLOPT_HEADER, 1);  
		else curl_setopt($cl, CURLOPT_HEADER, 0);
		
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($cl, CURLOPT_TIMEOUT, 30); 
		curl_setopt($cl, CURLOPT_CONNECTTIMEOUT, 10);
//if(strtolower((substr($url,0,5))=='https')) { // если соединяемся с https
		curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, false);
//}

        curl_setopt($cl, CURLOPT_USERAGENT, $userAgent);//'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		if(empty($cookiesfile)){
		//echo 'ПУТЬ: '.dirName(__FILE__).'/cookie.txt<br>';
        curl_setopt($cl, CURLOPT_COOKIEJAR, dirname(__FILE__)."/../cookie.txt"); 
        curl_setopt($cl, CURLOPT_COOKIEFILE, dirname(__FILE__)."/../cookie.txt");
		//file_put_contents('log.txt', "Cookie: ".dirName(__FILE__)."/cookie.txt\r\n", FILE_APPEND);
		} else {
        curl_setopt($cl, CURLOPT_COOKIEJAR, $cookiesfile); 
        curl_setopt($cl, CURLOPT_COOKIEFILE, $cookiesfile);
		//file_put_contents('log.txt', "Cookie: $cookiesfile\r\n", FILE_APPEND);
		}
     // указываем заголовки для браузера
        if($httpopt) curl_setopt($cl, CURLOPT_HTTPHEADER, $httpopt);
        if($followlocation) curl_setopt($cl, CURLOPT_FOLLOWLOCATION, 1); 
        if (!empty($post)) {
			curl_setopt($cl, CURLOPT_POST, 1); 
			curl_setopt($cl, CURLOPT_POSTFIELDS, $post);
		} else {
			curl_setopt($cl, CURLOPT_POST, 0);
		}
		//if($post == '!POST_OPT!') curl_setopt($cl, CURLOPT_POST, 1);
		if (!empty($proxy)) { 
		//curl_setopt($cl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		//curl_setopt($cl, CURLOPT_HTTPPROXYTUNNEL, 1);
		//curl_setopt($cl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);		
		curl_setopt($cl, CURLOPT_PROXY, $proxy); 
		if (!empty($auth)) curl_setopt($cl, CURLOPT_PROXYUSERPWD, $auth);
		}
        if (!empty($referer)) curl_setopt($cl, CURLOPT_REFERER, $referer);  
        $ex=curl_exec($cl);
		//if($ex) $try=0;
	  //}		
    if (curl_error($cl)) {
	    //$numerror = curl_errno($cl);
		$error = curl_error($cl);
		curl_close($cl);
		//file_put_contents('log.txt', "Curl error: $error\r\n", FILE_APPEND);
		return "Curl error: $error<br>";
        //return "Curl error #: " . $numerror . " - " . $error . ".<br>";
	} else { curl_close($cl); return $ex; }  
    }
	