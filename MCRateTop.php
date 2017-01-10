<?php

/////// Настройка подключения к БД ///////
$host = 'localhost'; ///Адрес БД
$database = 'mcrate'; ///Нужная БД
$user = 'mcrate'; ///Пользователь БД
$pass = 'PASS'; ///Пароль БД

/////// Настройка таблицы топа ///////
$table = 'MCRateTop'; ///Название таблицы топа
$field_id = 'id'; ///Поле id
$field_nick = 'username'; ///Поле логина(ника,юзернейма)
$field_amount = 'amount'; ///Поле количества голосов
$field_allamount = 'all_amount'; ///Поле количества голосов за все время
$field_date = 'date'; ///Поле даты последнего голоса
$topsource = 'source'; /// Источник голоса

/////// Настройка секретного слова ///////
$secret_word = 'SECRETPASS'; ///Секретное слово, прописывается на странице получения скрипта
$add_amount = 1; ///Кол-во голосов добавляемых к текущему

function mcrate_sec_script($filter){
	//$filter = mysqli_real_escape_string(mysqli_real_escape_string($filter)); 
	$filter = str_replace('<script>', '', $filter); 
	$filter = str_replace('</script>', '', $filter); 
	$filter = str_replace('<script', '', $filter); 
	$filter = str_replace('--', '', $filter); 
	$filter = str_replace('---', '', $filter); 
	$filter = strip_tags($filter); 
	$filter = stripslashes($filter); 
	$filter = htmlspecialchars($filter); 
	$filter = trim($filter); 
	return $filter; 
} 
 
try{
	if(isset($_REQUEST['nick']) && isset($_REQUEST['hash'])){
		$nick = strip_tags($_REQUEST['nick']);
		$hash = strip_tags($_REQUEST['hash']);
		
		if (empty($nick) or empty($hash) or empty($secret_word)) die('No data - no add!');  
		
		$nick = mcrate_sec_script($nick); 
		$hash = mcrate_sec_script($hash); 
		$secret_word = mcrate_sec_script($secret_word);
		$hashproject = md5(md5($nick.$secret_word.'mcrate'));
		
		if ($hash != $hashproject) die('Bad hash - no add!');
		
		$db = mysqli_connect($host,$user,$pass,$database);
		if(mysqli_connect_errno()) die('No connection BD!');
		
		$result1 = mysqli_query($db,"SELECT $field_id FROM $table WHERE $field_nick='$nick'"); 
		$res1 = mysqli_fetch_array($result1); 
		$id_user = $res1["$field_id"];
		
		if (empty($id_user)) {
			mysqli_query ($db,"INSERT INTO $table ($field_nick,$field_amount,$field_allamount,$field_date,$topsource) VALUES('$nick','0','0',NOW(),'MCRate')"); 
		}
		$results = mysqli_query($db,"SELECT $field_amount FROM $table WHERE $field_nick='$nick'"); 
		$res = mysqli_fetch_array($results) or die('No amount - no add!'); 
		$amount_old = $res["$field_amount"]; 
		if ($amount_old == '') $amount_old = '0';
		$amount_old = intval($amount_old);
		$amount_new = $amount_old + $add_amount; 
		$amount_new = intval($amount_new);  
		$update = mysqli_query($db,"UPDATE $table SET $field_amount = '$amount_new', $field_date = NOW(), $topsource= 'MCRate', $field_allamount = $field_allamount + 1  WHERE $field_nick = '$nick'"); 
		if($update == TRUE){
			die('Report: Update Data');
		} else{
			die('Error: No Update Data');
		}
		mysqli_close($db);
	} else {
		die('NO DATA - NICK or HASH!');
	}
} catch(Exception $e) {
	die("Fatal System Error: " . $e->getMessage());
}