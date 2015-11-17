<?php
include_once("../../private/conn_db_common.php");
include_once("../inc/restrict_login.php");

//print_r($_POST);

$user_id = mysql_real_escape_string($_SESSION['SES_User_id']);
$project_id = mysql_real_escape_string($_GET['r_id']);

$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $project_id);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_new_chapter'])){
	$chapter = mysql_real_escape_string($_POST['fld_new_chapter']);

	$rs_exist_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c WHERE c.Project_id='%s' AND c.Chapter='%s' LIMIT 1", $project_id, $chapter);
	$rs_exist_row = mysql_fetch_assoc(mysql_query($rs_exist_qry));
	
	if($rs_exist_row['TRUE']){
		echo 2;
	}else{
		if(!$rs_project_module7_row){
			$rs_prio_chap_qry = sprintf("SELECT c.Priority FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
			$rs_prio_chap_row = mysql_fetch_assoc(mysql_query($rs_prio_chap_qry));
	
			$rs_add_chapter_qry = sprintf("INSERT INTO tbl_project_chapter (Create_date, Project_id, Chapter, Priority) VALUES (NOW(), '%s', '%s', '%s')", $project_id, $chapter, ($rs_prio_chap_row['Priority']+1));
			mysql_query($rs_add_chapter_qry) or die("Error: " . mysql_error());
			echo $chapter;
		}
	}
}

?>