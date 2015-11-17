<?php
# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_result2_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE project_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result2_result = mysql_query($rs_project_result2_qry);
$rs_project_result2_row = mysql_fetch_assoc($rs_project_result2_result);

print_r($rs_project_result2_row);

$sub_21 = ($rs_project_result2_row['Calc_21']+$rs_project_result2_row['Post_new_21']+$rs_project_result2_row['More_21']+$rs_project_result2_row['Less_21']);
$sub_6 = ($rs_project_result2_row['Calc_6']+$rs_project_result2_row['Post_new_6']+$rs_project_result2_row['More_6']+$rs_project_result2_row['Less_6']);
$sub_0 = ($rs_project_result2_row['Calc_0']+$rs_project_result2_row['Post_new_0']+$rs_project_result2_row['More_0']+$rs_project_result2_row['Less_0']);

$rs_project_offer2_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer2_result = mysql_query($rs_project_offer2_qry) or die("Error: " . mysql_error());
$rs_project_offer2_row = mysql_fetch_assoc($rs_project_offer2_result);

$rs_project_term2_qry = sprintf("SELECT * FROM tbl_project_term t JOIN tbl_project_term_type tp ON t.type_id=tp.term_type_id WHERE offer_id=%d ORDER BY priority ASC", $rs_project_offer2_row['Offer_id']);
$rs_project_term2_result = mysql_query($rs_project_term2_qry) or die("Error: " . mysql_error());

while($rs_project_term2_row = mysql_fetch_assoc($rs_project_term2_result)){
	echo '$sub_21    '.$sub_21.'<br />';
	echo '$sub_6    '.$sub_6.'<br />';
	echo '$sub_0    '.$sub_0.'<br />';
	$totaal = ($sub_21+$sub_6+$sub_0);
	echo '$totaal    '.$totaal.'<br />';
	$deel21 = ($sub_21/$totaal);
	echo '$deel21    '.$deel21.'<br />';
	$deel6 = ($sub_6/$totaal);
	echo '$deel6    '.$deel6.'<br />';
	$deel0 = ($sub_0/$totaal);
	echo '$deel0    '.$deel0.'<br />';
	if($rs_project_term2_row['Close']=='Y'){
		$rest21 = ($totaal*$deel21);
	}else{
		$rest21 = ($rs_project_term2_row['Amount']*$deel21);
	}
	if($rs_project_term2_row['Type_id']==3){
		$sub_21 = ($sub_21-$rs_project_term2_row['Rest21']);
	}else{
		$sub_21 = ($sub_21-$rest21);
	}
	echo '$rest21    '.$rest21.'<br />';
	if($rs_project_term2_row['Close']=='Y'){
		$rest6 = ($totaal*$deel6);
	}else{
		$rest6 = ($rs_project_term2_row['Amount']*$deel6);
	}
	if($rs_project_term2_row['Type_id']==3){
		$sub_6 = ($sub_6-$rs_project_term2_row['Rest6']);
	}else{
		$sub_6 = ($sub_6-$rest6);
	}
	echo '$rest6    '.$rest6.'<br />';
	if($rs_project_term2_row['Close']=='Y'){
		$rest0 = ($totaal*$deel0);
	}else{
		$rest0 = ($rs_project_term2_row['Amount']*$deel0);
	}
	if($rs_project_term2_row['Type_id']==3){
		$sub_0 = ($sub_0-$rs_project_term2_row['Rest0']);
	}else{
		$sub_0 = ($sub_0-$rest0);
	}
	echo '$rest0    '.$rest0.'<br />';
}
?>