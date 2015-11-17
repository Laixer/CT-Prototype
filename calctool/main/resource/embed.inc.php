<?php
# Includes
include_once("../../../private_html/conn_db_common.php");
include_once("../../inc/restrict_login.php");

$u_id = mysql_real_escape_string($_GET['u_id']);
$r_id = mysql_real_escape_string($_GET['r_id']);

# Check if type Zelf is already used
if($u_id == $user_id){
	$rs_resource_qry = sprintf("SELECT * FROM tbl_resource AS r JOIN tbl_resource_type AS t ON t.Resource_type_id=r.Type_id WHERE User_id='%s' AND Resource_id='%s' LIMIT 1", $user_id, mysql_real_escape_string($_GET['r_id']));
	$rs_resource_result = mysql_query($rs_resource_qry) or die("Error: " . mysql_error());
	$rs_resource_row = mysql_fetch_assoc($rs_resource_result);
}

if($rs_resource_row){
	header("Content-type: ".$rs_resource_row['Ctype']);
	readfile("../../../private_content/".$rs_resource_row['Path']);
}
?>