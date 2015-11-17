<?php

session_start();

if(file_exists("../../private/conn_db_common.php")){
	include_once("../../private/conn_db_common.php");
}else if(file_exists("../../../private/conn_db_common.php")){
	include_once("../../../private/conn_db_common.php");
}else{
	echo "[SAFETY ERROR]: Cannot load safe includes";
}

include_once("restrict_login.php");

$page_id = mysql_real_escape_string($_GET['p_id']);

$rs_switchboard_result = mysql_query("SELECT sw.*, tt.Title, tt.Message FROM tbl_switchboard AS sw LEFT JOIN tbl_tooltip AS tt ON tt.Tooltip_id=sw.Tooltip_id WHERE sw.Switchboard_id='".$page_id."' LIMIT 1");
$rs_switchboard_row = mysql_fetch_assoc($rs_switchboard_result);

if(($rs_switchboard_row) && (file_exists($rs_switchboard_row['Page_url']))){
	$inc_page = $rs_switchboard_row['Page_url'];
	$curr_pid = $page_id;
	$array_title = array();
	$__tooltip = array('Title' => $rs_switchboard_row['Title'], 'Message' => $rs_switchboard_row['Message']);
	$array_pid = array();

	while(true){
		$fc_rs_switchboard_result = mysql_query("SELECT * FROM tbl_switchboard WHERE Switchboard_id='".$curr_pid."' LIMIT 1");
		$fc_rs_switchboard_row = mysql_fetch_assoc($fc_rs_switchboard_result);
		$fc_rs_switchboard_row['Breadcrumb_id'];

		$array_title[] = $fc_rs_switchboard_row['Page_title'];
		$array_pid[] = $fc_rs_switchboard_row['Switchboard_id'];
		
		if($fc_rs_switchboard_row['Breadcrumb_id'] == NULL){
			break;
		}else{
			$curr_pid = $fc_rs_switchboard_row['Breadcrumb_id'];
		}
	}
}else{
	$inc_page = "home.inc.php";
}
?>
