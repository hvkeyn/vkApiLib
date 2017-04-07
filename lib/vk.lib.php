<?php

// Функция отправки сообщения юзеру
function send( $message , $uid, $chat_id='user', $attache='', $dop_str='' ) {
    global $mtoken;
	usleep(333333);
	// Формируем начало сообщения 
	$url = 'access_token='.$mtoken.'&message='.urlencode($message);
	if($chat_id == 'chat') $url .= '&chat_id='.$uid;
	else $url .= '&user_id='.$uid;
		
	if(!$attache) $url .= $dop_str; 
	else $url .= '&attachment='.$attache.''.$dop_str; 
	
    $html = gotourl('https://api.vk.com/method/messages.send','','','',$url); //",'','','',"
	//file_get_contents('https://api.vk.com/method/messages.send?'.$url); //,'','','', $url
	//file_put_contents(dirname(__FILE__).'/../log.txt', '|Запрос:https://api.vk.com/method/messages.send?'.$url.' найдены данные:'.$html."\r\n");
	// проверяем на капчу
	testCapcha($html, 'send', $uid, $message, $chat_id, $attache, '', '', '', $mtoken);
	return $html;
}

function get( $count, $ttoken='', $dop_str='' ) {
    global $mtoken;
	if($ttoken) $mtoken = $ttoken;
	$html = gotourl('https://api.vk.com/method/messages.get','','','','access_token='.$mtoken.'&count='.$count.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log.txt', '|Запрос:https://api.vk.com/method/messages.get?'.$url.' найдены данные:'.$html."\r\n");
	testCapcha($html, 'get', $count, $ttoken, '', '', '', '', '', $mtoken);
	return $html;
}

// Получаем записи сообщества
function getWall($owner_id='', $domain='', $count=1, $offset='', $filter='', $extended=0, $fields='', $dop_str='') {
    global $mtoken;
	$try = 5;
    while ( --$try > 0) {
	$html = gotourl('https://api.vk.com/method/wall.get','','','','access_token='.$mtoken.'&owner_id='.$owner_id.'&domain='.$domain.'&count='.$count.'&offset='.$offset.'&filter='.$filter.'&extended='.$extended.'&fields='.$fields.''.$dop_str);
	//echo 'https://api.vk.com/method/wall.get?access_token='.$mtoken.'&user_id='.$user_id.'&type='.$type.'&owner_id='.$owner_id.'&item_id='.$item_id.''.$dop_str;
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Запрос:https://api.vk.com/method/friends.get?access_token='.$token.'&user_id='.$user_id.'&count='.$count.'&order='.$order.'&list_id='.$list_id.'&fields='.$fields.'&name_case='.$name_case.'&offset='.$offset.''.$dop_str.' найдены данные:'.$html."\r\n");
	testCapcha($html, 'getWall', $owner_id, $domain, $count, $offset, '', '', '', $mtoken);
	//else $html = gotourl("https://api.vk.com/method/friends.get?access_token=".$token."&user_id=".$user_id."&count=".$count."&order=".$order."&list_id=".$list_id."&fields=".$fields."&name_case=".$name_case."&offset=".$offset);
	if($html && !preg_match('|"error_msg":"Too many requests per second"|', $html)) $try = 0;
	else usleep(333333);
	}
	return $html;
}

// Получаем комментарии сообщества
function getWallComments($owner_id='', $post_id='', $offset=0, $count=100, $sort='', $need_likes=0, $start_comment_id='', $dop_str='') {
    global $mtoken;
	$try = 5;
    while ( --$try > 0) {
	$html = gotourl('https://api.vk.com/method/wall.getComments','','','','access_token='.$mtoken.'&owner_id='.$owner_id.'&post_id='.$post_id.'&offset='.$offset.'&count='.$count.'&sort='.$sort.'&need_likes='.$need_likes.''.$dop_str);
	//echo 'https://api.vk.com/method/wall.get?access_token='.$mtoken.'&owner_id='.$owner_id.'&message='.$message.'&from_group='.$from_group.'&signed='.$signed.'&attachments='.$attachments.''.$dop_str;
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Запрос:https://api.vk.com/method/friends.get?access_token='.$token.'&user_id='.$user_id.'&count='.$count.'&order='.$order.'&list_id='.$list_id.'&fields='.$fields.'&name_case='.$name_case.'&offset='.$offset.''.$dop_str.' найдены данные:'.$html."\r\n");
	testCapcha($html, 'getWallComments', $owner_id, $post_id, $offset, $count, $sort, $need_likes, $start_comment_id, $mtoken);
	//else $html = gotourl("https://api.vk.com/method/friends.get?access_token=".$token."&user_id=".$user_id."&count=".$count."&order=".$order."&list_id=".$list_id."&fields=".$fields."&name_case=".$name_case."&offset=".$offset);
	if($html && !preg_match('|"error_msg":"Too many requests per second"|', $html)) $try = 0;
	else usleep(333333);
	}
	return $html;
}

// Получаем темы обсуждений
function getBoardTopics( $group_id='', $topic_ids='', $count=100, $offset=0, $order=1, $extended=0, $preview=0, $preview_length=90, $dop_str='') {
    global $mtoken ;
	$html = gotourl('https://api.vk.com/method/board.getTopics','','','','access_token='.$mtoken.'&group_id='.$group_id.'&topic_ids='.$topic_ids.'&count='.$count.'&offset='.$offset.'&order='.$order.'&extended='.$extended.'&preview='.$preview.'&preview_length='.$preview_length.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log1.txt', 'Запрос:https://api.vk.com/method/groups.getById?access_token='.$token.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str.' найдены данные:'.$html."\r\n", FILE_APPEND);
	// проверяем на капчу
	testCapcha($html, 'getBoardTopics', $group_id, $topic_ids, $count, $offset, $order, $extended, $preview, $mtoken); 
	return $html;
}

// Получаем сообщения темы обсуждений
function getBoardComments( $group_id='', $topic_id='', $count=100, $offset=0, $extended=1, $sort='desc', $start_comment_id='', $need_likes=0, $dop_str='') {
    global $mtoken ;
	$html = gotourl('https://api.vk.com/method/board.getComments','','','','access_token='.$mtoken.'&group_id='.$group_id.'&topic_id='.$topic_id.'&count='.$count.'&offset='.$offset.'&need_likes='.$need_likes.'&extended='.$extended.'&sort='.$sort.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log1.txt', 'Запрос:https://api.vk.com/method/groups.getById?access_token='.$token.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str.' найдены данные:'.$html."\r\n", FILE_APPEND);
	// проверяем на капчу
	testCapcha($html, 'getBoardComments', $group_id, $topic_id, $count, $offset, $start_comment_id, $extended, $sort, $mtoken); 
	return $html;
}

// Получение группы по ID
function getGroupsById( $group_ids='', $group_id='', $fields='contacts', $dop_str='') {
    global $mtoken ;
	$html = gotourl('https://api.vk.com/method/groups.getById','','','','access_token='.$mtoken.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log1.txt', 'Запрос:https://api.vk.com/method/groups.getById?access_token='.$token.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str.' найдены данные:'.$html."\r\n", FILE_APPEND);
	// проверяем на капчу
	testCapcha($html, 'getGroupsById', $group_ids, $group_id, $fields, '', '', '', '', $mtoken); 
	return $html;
}

// Возвращает список товаров в сообществе.
function getMarket( $owner_id='', $album_id='', $count=200, $offset=0, $extended=0, $dop_str='') {
    global $mtoken ;
	$html = gotourl('https://api.vk.com/method/market.get','','','','access_token='.$mtoken.'&owner_id='.$owner_id.'&album_id='.$album_id.'&count='.$count.'&offset='.$offset.'&extended='.$extended.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log1.txt', 'Запрос:https://api.vk.com/method/groups.getById?access_token='.$token.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str.' найдены данные:'.$html."\r\n", FILE_APPEND);
	// проверяем на капчу
	testCapcha($html, 'getMarket', $owner_id, $album_id, $count, $offset, $extended, '', '', $mtoken); 
	return $html;
}

// Возвращает список комментариев к товару.
function getMarketComments($owner_id='', $item_id='', $count=100, $offset=0, $extended=1, $sort='desc', $start_comment_id='', $need_likes=0, $fields='', $dop_str='') {
    global $mtoken ;
	$html = gotourl('https://api.vk.com/method/market.getComments','','','','access_token='.$mtoken.'&owner_id='.$owner_id.'&item_id='.$item_id.'&count='.$count.'&offset='.$offset.'&need_likes='.$need_likes.'&extended='.$extended.'&sort='.$sort.''.$dop_str);
	//file_put_contents(dirname(__FILE__).'/../log1.txt', 'Запрос:https://api.vk.com/method/groups.getById?access_token='.$token.'&group_ids='.$group_ids.'&group_id='.$group_id.'&fields='.$fields.''.$dop_str.' найдены данные:'.$html."\r\n", FILE_APPEND);
	// проверяем на капчу
	testCapcha($html, 'getMarketComments', $owner_id, $item_id, $count, $offset, $extended, $sort, $fields, $mtoken); 
	return $html;
}

// Получение городов
function getCitys($country_id=1, $count=1000, $need_all=0, $dop_str='') {
    global $mtoken;
	$try = 5;
    while ( --$try > 0) {
	$html = gotourl('https://api.vk.com/method/database.getCities','','','','access_token='.$mtoken.'&country_id='.$country_id.'&count='.$count.'&need_all='.$need_all.''.$dop_str);
	//echo 'https://api.vk.com/method/riends.add?access_token='.$mtoken.'&user_id='.$user_id.'&text='.$text.'&follow='.$follow.''.$dop_str;
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Запрос:https://api.vk.com/method/friends.get?access_token='.$token.'&user_id='.$user_id.'&count='.$count.'&order='.$order.'&list_id='.$list_id.'&fields='.$fields.'&name_case='.$name_case.'&offset='.$offset.''.$dop_str.' найдены данные:'.$html."\r\n");
	testCapcha($html, 'getCitys', $country_id, $count, $need_all, '', '', '', '', $mtoken);
	//else $html = gotourl("https://api.vk.com/method/friends.get?access_token=".$token."&user_id=".$user_id."&count=".$count."&order=".$order."&list_id=".$list_id."&fields=".$fields."&name_case=".$name_case."&offset=".$offset);
	if($html && !preg_match('|"error_msg":"Too many requests per second"|', $html)) $try = 0;
	else usleep(333333);
	}
	return $html;
}

// Получение альбомов
function getAlbums($owner_id, $count='', $offset='', $need_system = 0, $need_covers=0, $photo_sizes=0, $dop_str='') {
    global $mtoken;
	$try = 5;
    while ( --$try > 0) {
	$html = gotourl('https://api.vk.com/method/photos.getAlbums','','','','access_token='.$mtoken.'&owner_id='.$owner_id.'&count='.$count.'&offset='.$offset.'&need_system='.$need_system.'&need_covers='.$need_covers.'&photo_sizes='.$photo_sizes.''.$dop_str);
	//echo 'https://api.vk.com/method/wall.post?access_token='.$mtoken.'&owner_id='.$owner_id.'&message='.$message.'&from_group='.$from_group.'&signed='.$signed.'&attachments='.$attachments.''.$dop_str;
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Запрос:https://api.vk.com/method/friends.get?access_token='.$token.'&user_id='.$user_id.'&count='.$count.'&order='.$order.'&list_id='.$list_id.'&fields='.$fields.'&name_case='.$name_case.'&offset='.$offset.''.$dop_str.' найдены данные:'.$html."\r\n");
	testCapcha($html, 'getAlbums', $owner_id, $count, $offset, $need_system, '', '', '', $mtoken);
	//else $html = gotourl("https://api.vk.com/method/friends.get?access_token=".$token."&user_id=".$user_id."&count=".$count."&order=".$order."&list_id=".$list_id."&fields=".$fields."&name_case=".$name_case."&offset=".$offset);
	if($html && !preg_match('|"error_msg":"Too many requests per second"|', $html)) $try = 0;
	else usleep(333333);
	}
	return $html;
}

// Функция проверки капчи в процессе работы скрипта на VK
function testCapcha($messages, $step, $uid='', $message='', $attache='', $tmp_dop1='', $tmp_dop2='', $tmp_dop3='', $tmp_dop4='', $token=''){
    //global $token ;
    global $gkey;
	global $gkey_link;
	//$gkey = $gkey_array[mt_rand(0, sizeof($gkey_array)-1)];;
	// Попыток ввода капчи, на случай таковой
        $try = 10;
		$capch_jpg = uniqid().'.jpg';
        while ( // Если с нас просят капчу
        preg_match('|"captcha_sid":"([0-9]+)"|', $messages, $c)
        && preg_match('|"error_msg":"Captcha needed"|', $messages)
        && --$try > 0 // И попытки ввода еще остались
        )
        { // Скачиваем капчу
            file_put_contents(
                    dirname(__FILE__).'/../captcha/'.$capch_jpg, 
                    file_get_contents('https://api.vk.com/captcha.php?sid=' . $c[1])
            );
			$cod = recognize(dirname(__FILE__).'/../captcha/'.$capch_jpg, $gkey, 1, $gkey_link);
			//echo 'ПОЛУЧАЕМ КАПЧУ:'.$cod.'<br>';
			file_put_contents(dirname(__FILE__).'/../log.txt', 'Капча '.$capch_jpg.' для сайта:'.$gkey_link.' с ключом:'.$gkey.' найдена:'.$cod."\r\n", FILE_APPEND);
         // Разгадываем капчу и повторяем запрос //gotourl("https://api.vk.com/method/messages.getLongPollServer?access_token=".$token . '&captcha_sid=' . $c[1] . '&captcha_key='.$cod );
		 if($cod){
			unlink(dirname(__FILE__).'/../captcha/'.$capch_jpg);
			unset($capch_jpg);
			 switch ($step) {
            case 'get': 
			return get($uid, $message, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'send': 
			return send($message, $uid, $attache, $tmp_dop1, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getWall': 
			return getWall($uid, $message, $attache, $tmp_dop1, '', 0, '', '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getWallComments': 
			return getWallComments($uid, $message, $attache, $tmp_dop1, $tmp_dop2, $tmp_dop3, $tmp_dop4, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;		
			case 'getBoardTopics': 
			return getBoardTopics($uid, $message, $attache, $tmp_dop1, $tmp_dop2, $tmp_dop3, $tmp_dop4, '', '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getBoardComments': 
			return getBoardComments($uid, $message, $attache, $tmp_dop1, $tmp_dop3, $tmp_dop4, $tmp_dop2, '', '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getMarket': 
			return getMarket($uid, $message, $attache, $tmp_dop1, $tmp_dop2, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getMarketComments': 
			return getMarketComments($uid, $message, $attache, $tmp_dop1, '', $tmp_dop2, '', '', $tmp_dop3, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getAlbums': 
			return getAlbums($uid, $message, $attache, $tmp_dop1, 0, 0,'&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getGroupsById': 
			return getGroupsById($uid, $message, $attache, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'getCitys': 
			return getCitys($uid, $message, $attache, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'upload_doc': 
			return upload_doc($uid, $message, $attache, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'upload_image': 
			return upload_image($uid, $message, $attache, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			case 'upload_image_wall': 
			return upload_image_wall($uid, $message, $attache, '&captcha_sid=' . $c[1] . '&captcha_key='.$cod);
			break;
			}
		 }	
        }//end while
}

  /**
  * Заливка фото в личные сообщения (например JPG файл)
  *
  * @param bool $gid
  * @param $file
  * @return bool|string
  */
  function upload_image_wall($group_id, $file, $rez=0 ,$dop_str=''){
    global $mtoken;
	$group_id=-1*$group_id;
	//$try = 10;
   // while ( --$try > 0) {
	$attachments=false;
    //if(!is_string($file)) return false;
    //if(!function_exists('curl_init')) return false;
//."gid=".intval($gid)
if($rez == 0 || $rez == 1){
	$html = file_get_contents('https://api.vk.com/method/photos.getWallUploadServer?access_token='.$mtoken.'&group_id='.$group_id.''.$dop_str);
	//echo "STEP1:https://api.vk.com/method/photos.getMessagesUploadServer?access_token=".$token.''.$dop_str."<br>";
    //$response = $this->api('docs.getUploadServer', array('gid'=> intval($gid)));
	// проверяем на капчу
	//echo "STEP1_test: ".$html.'|Gid:'.$group_id.'|File:'.$file."|1<br>";
    testCapcha($html, 'upload_image_wall', $group_id, $file, 1,'',$mtoken);
	$html = json_decode($html);

    if(!isset($html->response->upload_url)) return false;
	
	$rez = 0;
} 
if($rez == 0 || $rez == 2){
    //$file = realpath($file);
//echo $file.'|||'.$path;

    //if(!$file) return false;
    //$files['photo'] = /*(class_exists('CURLFile', false)) ? new CURLFile($file) : */'@' . $file;
	
    if($rez == 2 && !isset($html->response->upload_url)) {
	$upload_url = $group_id; 
	$group_id = false;
	} else $upload_url = $html->response->upload_url;

    $ch = curl_init($upload_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible;)');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' =>  '@' . $file));//$files);

	$tmp_ulpoad_data = curl_exec($ch);
	//echo "STEP2:$upload_url |".''.$dop_str."<br>";
	testCapcha($tmp_ulpoad_data, 'upload_image_wall', $upload_url, $file, 2,'',$mtoken);
    $upload_data = json_decode($tmp_ulpoad_data, true);
	//echo "STEP2_test: ".$tmp_ulpoad_data.'|<br>Gid:'.$upload_url.'|<br>File:'.$file.'|<br>FileS:'.$upload_data['photo']."|<br>2<br>";
	var_dump($upload_data);
	//echo $upload_data['photo'].'<br><br>'.http_build_query($upload_data);
	$rez = 0;
} 
if($rez == 0 || $rez == 3){
	if($rez == 3) {
	$full_file = $file; 
	} else $full_file = '&group_id='.$group_id.'&server='.$upload_data['server'].'&photo='.str_replace('\"','"',$upload_data['photo']).'&hash='.$upload_data['hash'];
	
	//echo "STEP3:https://api.vk.com/method/photos.saveMessagesPhoto?access_token=".$token."&file=".$full_file.''.$dop_str."<br>";
	//echo "STEP3:https://api.vk.com/method/photos.saveMessagesPhoto?access_token=".$token.$full_file.''.$dop_str."<br>";
    $html = file_get_contents('https://api.vk.com/method/photos.saveWallPhoto?access_token='.$mtoken.''.$full_file.''.$dop_str);
	//echo "STEP3_test: ".$html.'|Gid:'.$gid.'|photo:'.$full_file."|3<br>";
	testCapcha($html, 'upload_image_wall', $group_id, $full_file, 3,'',$mtoken);
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Данные:'.$html."\r\n");
	
	$html = json_decode($html, true);

	print_r($html);

    if(count($html) > 0){

      foreach($html as $photo){

        $attachments = $photo[0]['id'];//'photo'.$photo[0]['owner_id'].'_'.$photo[0]['pid'];
      }
    }
	$rez=0;
}
	//if($attachments && !preg_match('|"error_msg":"Too many requests per second"|', $attachments)) $try = 0;
	//else usleep(333333);
  //}
    return $attachments;
  }

  /**
  * Заливка фото в личные сообщения (например JPG файл)
  *
  * @param bool $gid
  * @param $file
  * @return bool|string
  */
  function upload_image($gid = false, $file, $rez=0 ,$dop_str=''){
    global $mtoken ;
	//$try = 10;
   // while ( --$try > 0) {
	$attachments=false;
    //if(!is_string($file)) return false;
    //if(!function_exists('curl_init')) return false;
//."gid=".intval($gid)
if($rez == 0 || $rez == 1){
	$html = file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?access_token='.$mtoken.''.$dop_str);
	//echo "STEP1:https://api.vk.com/method/photos.getMessagesUploadServer?access_token=".$token.''.$dop_str."<br>";
    //$response = $this->api('docs.getUploadServer', array('gid'=> intval($gid)));
	// проверяем на капчу
	//echo "STEP1_test: ".$html.'|Gid:'.$gid.'|File:'.$file."|1<br>";
    testCapcha($html, 'upload_image', $gid, $file, 1,'',$mtoken);
	$html = json_decode($html);

    if(!isset($html->response->upload_url)) return false;
	
	$rez = 0;
} 
if($rez == 0 || $rez == 2){
    //$file = realpath($file);
//echo $file.'|||'.$path;

    //if(!$file) return false;
    //$files['photo'] = /*(class_exists('CURLFile', false)) ? new CURLFile($file) : */'@' . $file;
	
    if($rez == 2 && !isset($html->response->upload_url)) {
	$upload_url = $gid; 
	$gid = false;
	} else $upload_url = $html->response->upload_url;

    $ch = curl_init($upload_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible;)');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' =>  '@' . $file));//$files);

	$tmp_ulpoad_data = curl_exec($ch);
	//echo "STEP2:$upload_url |".''.$dop_str."<br>";
	testCapcha($tmp_ulpoad_data, 'upload_image', $upload_url, $file, 2,'',$mtoken);
    $upload_data = json_decode($tmp_ulpoad_data, true);
	//echo "STEP2_test: ".$tmp_ulpoad_data.'|<br>Gid:'.$upload_url.'|<br>File:'.$file.'|<br>FileS:'.$upload_data['photo']."|<br>2<br>";
	//var_dump($upload_data);
	//echo $upload_data['photo'].'<br><br>'.http_build_query($upload_data);
	$rez = 0;
} 
if($rez == 0 || $rez == 3){
	if($rez == 3) {
	$full_file = $file; 
	} else $full_file = '&server='.$upload_data['server'].'&photo='.str_replace('\"','"',$upload_data['photo']).'&hash='.$upload_data['hash'];
	
	//echo "STEP3:https://api.vk.com/method/photos.saveMessagesPhoto?access_token=".$token."&file=".$full_file.''.$dop_str."<br>";
    $html = file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?access_token='.$mtoken.''.$full_file.''.$dop_str);
	//echo "STEP3_test: ".$html.'|Gid:'.$gid.'|photo:'.$full_file."|3<br>";
	testCapcha($html, 'upload_image', $gid, $full_file, 3,'',$mtoken);
	//file_put_contents(dirname(__FILE__).'/../log.txt', 'Данные:'.$html."\r\n");
	
	$html = json_decode($html, true);

	//print_r($html);

    if(count($html) > 0){

      foreach($html as $photo){

        $attachments = $photo[0]['id'];//'photo'.$photo[0]['owner_id'].'_'.$photo[0]['pid'];
      }
    }
	$rez=0;
}
	//if($attachments && !preg_match('|"error_msg":"Too many requests per second"|', $attachments)) $try = 0;
	//else usleep(333333);
  //}
    return $attachments;
  }

  /**
  * Заливка документа (например GIF файл)
  *
  * @param bool $gid
  * @param $file
  * @return bool|string
  */
  function upload_doc($gid = false, $file, $rez=0 ,$dop_str=''){
    global $mtoken;
	//$try = 10;
    //while ( --$try > 0) {
	$attachment=false;
    //if(!is_string($file)) return false;
    //if(!function_exists('curl_init')) return false;
//."gid=".intval($gid)
if($rez == 0 || $rez == 1){
	$html = gotourl('https://api.vk.com/method/docs.getUploadServer','','','','access_token='.$mtoken.''.$dop_str); //('https://api.vk.com/method/docs.getUploadServer?access_token='.$mtoken.''.$dop_str);
	//echo "STEP1:https://api.vk.com/method/docs.getUploadServer?access_token=".$token.''.$dop_str."<br>";
    //$response = $this->api('docs.getUploadServer', array('gid'=> intval($gid)));
	// проверяем на капчу
	//echo "STEP1_test: ".$html.'|Gid:'.$gid.'|File:'.$file."|1<br>";
    testCapcha($html, 'upload_doc', $gid, $file, 1,'',$mtoken);
	$html = json_decode($html);

    if(!isset($html->response->upload_url)) return false;
	
	$rez = 0;
} 
if($rez == 0 || $rez == 2){
    //$file = realpath($file);
//echo $file.'|||'.$path;

    //if(!$file) return false;
    $files['file'] = (class_exists('CURLFile', false)) ? new CURLFile($file) : '@' . $file;
	
    if($rez == 2 && !isset($html->response->upload_url)) {
	$upload_url = $gid; 
	$gid = false;
	} else $upload_url = $html->response->upload_url;

    $ch = curl_init($upload_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible;)');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $files);

	$tmp_ulpoad_data = curl_exec($ch);
	//echo "STEP2:$upload_url |".''.$dop_str."<br>";
	testCapcha($tmp_ulpoad_data, 'upload_doc', $upload_url, $file, 2,'',$mtoken);
    $upload_data = json_decode($tmp_ulpoad_data, true);
	//echo "STEP2_test: ".$tmp_ulpoad_data.'|Gid:'.$upload_url.'|File:'.$file.'|FileS:'.$upload_data['file']."|2<br>";
	//var_dump($upload_data);
	//echo $upload_data['file'].'<br><br>'.http_build_query($upload_data);
	$rez = 0;
} 
if($rez == 0 || $rez == 3){
	if($rez == 3) {
	$full_file = $file; 
	} else $full_file = $upload_data['file'];
	
	//echo "STEP3:https://api.vk.com/method/docs.save?access_token=".$token."&file=".$full_file.''.$dop_str."<br>";
    $html = gotourl('https://api.vk.com/method/docs.save','','','','access_token='.$mtoken.'&file='.$full_file.''.$dop_str); //$this->api('docs.save', $upload_data); title=test
	//echo "STEP3_test: ".$html.'|Gid:'.$gid.'|File:'.$full_file."|3<br>";
	testCapcha($html, 'upload_doc', $gid, $full_file, 3,'',$mtoken);
	
	$html = json_decode($html, true);
	
	//print_r($html);

    if(count($html) > 0){

      foreach($html as $photo){

        $attachment = 'doc'.$photo[0]['owner_id'].'_'.$photo[0]['did'];
      }
    }
	$rez=0;
}
	//if($attachment && !preg_match('|"error_msg":"Too many requests per second"|', $attachment)) $try = 0;
	//else usleep(333333);
	//}
    return $attachment;
  }

?>