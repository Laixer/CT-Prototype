<?php

if(file_exists("../../private/conn_db_common.php")){
	include_once("../../private/conn_db_common.php");
}else if(file_exists("../../../private/conn_db_common.php")){
	include_once("../../../private/conn_db_common.php");
}else{
	echo "[SAFETY ERROR]: Cannot load safe includes";
}
	
$user_id = $_SESSION['SES_User_id'];

if(!$user_id){
	session_destroy();
	header("Location: /");
	exit();
}

$rs_restrict_result = mysql_query("SELECT * FROM tbl_user WHERE User_id='".$user_id."' LIMIT 1") or die("Error: " . mysql_error());
$rs_restrict_row = mysql_fetch_assoc($rs_restrict_result);
mysql_free_result($rs_restrict_result);

if(($rs_restrict_row['Banned'] == 'Y') || ($rs_restrict_row['Confirmed'] == 'N') || ($rs_restrict_row['User_id'] < 0)){
	session_destroy();
	header("Location: /");
	exit();
}

$__url_session = date("H").substr(md5(uniqid()), -25);

mysql_query("UPDATE tbl_user SET Timestamp_date=NOW() WHERE User_id='".$rs_restrict_row['User_id']."' LIMIT 1") or die("Error: " . mysql_error());
?>
