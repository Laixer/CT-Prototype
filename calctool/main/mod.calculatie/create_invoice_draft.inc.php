<?php
// KAN WEG 
# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# Submited user data
$term_id = mysql_real_escape_string($_GET['t_id']);
$term_idx = $_GET['t_idx'];

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_result_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE project_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

$amount_total = ($rs_project_result_row['Calc_21']+$rs_project_result_row['Calc_6']+$rs_project_result_row['Calc_0']+$rs_project_result_row['Post_new_21']+$rs_project_result_row['Post_new_6']+$rs_project_result_row['Post_new_0']+$rs_project_result_row['More_21']+$rs_project_result_row['More_6']+$rs_project_result_row['More_0']+$rs_project_result_row['Less_21']+$rs_project_result_row['Less_6']+$rs_project_result_row['Less_0']);

$sub_21 = ($rs_project_result_row['Calc_21']+$rs_project_result_row['Post_new_21']+$rs_project_result_row['More_21']+$rs_project_result_row['Less_21']);
$sub_6 = ($rs_project_result_row['Calc_6']+$rs_project_result_row['Post_new_6']+$rs_project_result_row['More_6']+$rs_project_result_row['Less_6']);
$sub_0 = ($rs_project_result_row['Calc_0']+$rs_project_result_row['Post_new_0']+$rs_project_result_row['More_0']+$rs_project_result_row['Less_0']);

# All chapters for this project
$rs_project_chap_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_chap_result = mysql_query($rs_project_chap_qry) or die("Error: " . mysql_error());

$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

$rs_project_term_qry = sprintf("SELECT * FROM tbl_project_term t JOIN tbl_project_term_type tp ON t.type_id=tp.term_type_id WHERE offer_id=%d ORDER BY priority ASC", $rs_project_offer_row['Offer_id']);
$rs_project_term_result = mysql_query($rs_project_term_qry) or die("Error: " . mysql_error());

$rs_project_term_all_qry = sprintf("SELECT * FROM tbl_project_term WHERE Project_term_id=%d LIMIT 1", $term_id);
$rs_project_term_all_result = mysql_query($rs_project_term_all_qry) or die("Error: " . mysql_error());
$rs_project_term_all_row = mysql_fetch_assoc($rs_project_term_all_result);

# No projects have been found
if(!$rs_project_perm_check_row){
	$error_message = "Er zijn geen gegevens gevonden";
	$hide_page = 1;
}

$i=0;
$rest_slot = $amount_total;
while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){
	if($term_idx==$i){
		$amount = $rs_project_term_row['Amount'];
		if($rs_project_term_row['Close']!='Y'){
			$rest_slot -= $rs_project_term_row['Amount'];
		}
//		echo '$sub_21    '.$sub_21.'<br />';
//		echo '$sub_6    '.$sub_6.'<br />';
//		echo '$sub_0    '.$sub_0.'<br />';
		$totaal = ($sub_21+$sub_6+$sub_0);
//		echo '$totaal    '.$totaal.'<br />';
		$deel21 = ($sub_21/$totaal);
//		echo '$deel21    '.$deel21.'<br />';
		$deel6 = ($sub_6/$totaal);
//		echo '$deel6    '.$deel6.'<br />';
		$deel0 = ($sub_0/$totaal);
//		echo '$deel0    '.$deel0.'<br />';
		if($rs_project_term_row['Close']=='Y'){
			$rest21 = ($totaal*$deel21);
		}else{
			$rest21 = ($rs_project_term_row['Amount']*$deel21);
		}
		if($rs_project_term_row['Type_id']==3){
			$sub_21 = ($sub_21-$rs_project_term_row['Rest21']);
		}else{
			$sub_21 = ($sub_21-$rest21);
		}
		//echo '$rest21    '.$rest21.'<br />';
		if($rs_project_term_row['Close']=='Y'){
			$rest6 = ($totaal*$deel6);
		}else{
			$rest6 = ($rs_project_term_row['Amount']*$deel6);
		}
		if($rs_project_term_row['Type_id']==3){
			$sub_6 = ($sub_6-$rs_project_term_row['Rest6']);
		}else{
			$sub_6 = ($sub_6-$rest6);
		}
		//echo '$rest6    '.$rest6.'<br />';
		if($rs_project_term_row['Close']=='Y'){
			$rest0 = ($totaal*$deel0);
		}else{
			$rest0 = ($rs_project_term_row['Amount']*$deel0);
		}
		if($rs_project_term_row['Type_id']==3){
			$sub_0 = ($sub_0-$rs_project_term_row['Rest0']);
		}else{
			$sub_0 = ($sub_0-$rest0);
		}
		//echo '$rest0    '.$rest0.'<br />';
		break;
	}
	$i++;
}

//echo '$rest21    '.$rest21.'<br />';
//echo '$rest6    '.$rest6.'<br />';
//echo '$rest0    '.$rest0.'<br />';

$rs_add_chapter_qry = sprintf("INSERT INTO tbl_project_invoice_draft (Create_date, Term_id, Amount, Rest21, Rest6, Rest0) VALUES (NOW(), %d, %f, %f, %f, %f)", $rs_project_term_all_row['Project_term_id'], $amount, $rest21, $rest6, $rest0);
mysql_query($rs_add_chapter_qry) or die("Error: " . mysql_error());
header("Location: /main/mod.calculatie/invoice_term.inc.php/?r_id=".$rs_project_perm_check_row['Project_id']);
?>