<?php
/**
 * - Markup correction
 * - Code Safety
 *	 - Escape
 *	 - User based selection
 * - Freeing results
 * - Error handling
 */

# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_rel_us_qry = sprintf("SELECT * FROM tbl_relation WHERE Relation_id='%s' LIMIT 1", $rs_project_perm_check_row['Client_relation_id']);
$rs_project_rel_us_result = mysql_query($rs_project_rel_us_qry) or die("Error: " . mysql_error());
$rs_project_rel_us_row = mysql_fetch_assoc($rs_project_rel_us_result);

# User's relation for homepage
$rs_user_relation_qry = sprintf("SELECT * FROM tbl_relation WHERE User_id='%s' AND Relation_type_id=1 LIMIT 1", $user_id);
$rs_user_relation_result = mysql_query($rs_user_relation_qry) or die("Error: " . mysql_error());
$rs_user_relation_row = mysql_fetch_assoc($rs_user_relation_result);

$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

# This offer
$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

# All chapters for this projecy
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_work_inv1_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv2_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());

# Check for empty totals
# 10
$rs_project_empty_total_10_qry = sprintf("SELECT Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_10_result = mysql_query($rs_project_empty_total_10_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_10_row = mysql_fetch_assoc($rs_project_empty_total_10_result);
# 40
$rs_project_empty_total_40_qry = sprintf("SELECT Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=40 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_40_result = mysql_query($rs_project_empty_total_40_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_40_row = mysql_fetch_assoc($rs_project_empty_total_40_result);

# All chapters for this projecy
$rs_project_total_qry = sprintf("SELECT *, SUM(Total) AS Super_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_total_result = mysql_query($rs_project_total_qry) or die("Error: " . mysql_error());
$rs_project_total_row = mysql_fetch_assoc($rs_project_total_result);

# Object totals
$rs_project_result_qry = sprintf("SELECT * FROM tvw_result_mod_2 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

# No projects have been found
if(!$rs_project_perm_check_row){
	$error_message = "Er zijn geen gegevens gevonden";
	$hide_page = 1;
}

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }

$p = pdf_new();

/*  open new PDF file; insert a file name to create the PDF on disk */
if (pdf_begin_document($p, "", "") == 0) {
    die("Error: " . PDF_get_errmsg($p));
}

pdf_set_info($p, "Author", "Martín Gonzalez");
pdf_set_info($p, "Title", "Test PDF");
pdf_set_info($p, "Creator", "Martín Gonzalez");
pdf_set_info($p, "Subject", "Test  PDF");

pdf_begin_page_ext($p, 595, 842, "");

$font = pdf_load_font($p, "Helvetica-Bold", "winansi", "");

pdf_setfont($p, $font, 20.0);
//PDF_set_text_pos($p, 450, 780);
//PDF_show($p, "OFFERTE");
pdf_show_xy($p, "OFFERTE", 450, 790);
//PDF_continue_text($p, "(says PHP)");

// Afbeeldingobject maken
$Afbeelding1 = pdf_load_image($p, "auto", "../../images/van-dale-logo-big.gif", "");

PDF_place_image($p, $Afbeelding1, 10, 770, 0.6);

pdf_setlinewidth($p, 0.3);
PDF_rect($p, 10, 635, 575, 115);
PDF_stroke($p);

pdf_setfont($p, $font, 10.0);

pdf_show_xy($p, $rs_project_rel_us_row['Company_name'], 230, 735);
pdf_show_xy($p, "T.A.V. ".$rs_project_rel_us_row['Contact_first_name']." ".$rs_project_rel_us_row['Contact_name'], 230, 720);
pdf_show_xy($p, $rs_project_rel_us_row['Address']." ".$rs_project_rel_us_row['Address_number'], 230, 705);
pdf_show_xy($p, $rs_project_rel_us_row['Zipcode']." ".$rs_project_rel_us_row['City'], 230, 690);

pdf_show_xy($p, "Projectnaam", 15, 675);
pdf_show_xy($p, "Projectnummer", 15, 660);
pdf_show_xy($p, "Datum", 15, 645);

$font = pdf_load_font($p, "Helvetica", "winansi", "");
pdf_setfont($p, $font, 10.0);

pdf_show_xy($p, $rs_user_relation_row['Company_name'], 430, 735);
pdf_show_xy($p, $rs_user_relation_row['Address']." ".$rs_user_relation_row['Address_number'], 430, 720);
pdf_show_xy($p, $rs_user_relation_row['Zipcode']." ".$rs_user_relation_row['City'], 430, 705);
pdf_show_xy($p, "T: ".$rs_user_relation_row['Phone_1'], 430, 690);
pdf_show_xy($p, "M: ".$rs_user_relation_row['Phone_2'], 430, 675);
pdf_show_xy($p, "E: ".$rs_user_relation_row['Email_1'], 430, 660);

pdf_show_xy($p, $rs_project_perm_check_row['Name'], 150, 675);
pdf_show_xy($p, $rs_project_perm_check_row['Project_id'], 150, 660);
pdf_show_xy($p, date("d-m-Y"), 150, 645);

$pretext = explode("\n", $rs_project_offer_row['Pretext']);
for($i=0; $i<count($pretext); $i++){
	pdf_show_xy($p, $pretext[$i], 10, 610-($i*20));
}

$font = pdf_load_font($p, "Helvetica-Bold", "winansi", "");
pdf_setfont($p, $font, 10.0);

pdf_moveto($p, 140, 510);//H1
pdf_lineto($p, 280, 510);//H1
pdf_moveto($p, 205, 510);//V1
pdf_lineto($p, 205, 100);//V1
pdf_moveto($p, 280, 530);//V2
pdf_lineto($p, 280, 100);//V2
pdf_moveto($p, 140, 490);//H2
pdf_lineto($p, 585, 490);//H2
pdf_moveto($p, 355, 530);//V3
pdf_lineto($p, 355, 100);//V3
pdf_moveto($p, 435, 530);//V4
pdf_lineto($p, 435, 100);//V4
pdf_moveto($p, 515, 530);//V5
pdf_lineto($p, 515, 100);//V5
pdf_stroke($p);

PDF_rect($p, 140, 100, 445, 430);
PDF_stroke($p);

pdf_show_xy($p, "WERKZAAMHEDEN", 10, 495);
pdf_show_xy($p, "Arbeid", 190, 515);
pdf_show_xy($p, "Totaal", 535, 515);
pdf_show_xy($p, "Totaalpost", 450, 515);
pdf_show_xy($p, "Materieel", 375, 515);
pdf_show_xy($p, "Materiaal", 295, 515);

pdf_show_xy($p, "Hoofdstuk 1", 10, 475);

$font = pdf_load_font($p, "Helvetica", "winansi", "");
pdf_setfont($p, $font, 10.0);
pdf_show_xy($p, "Arbeidsuren", 145, 495);
pdf_show_xy($p, "Arbeidskosten", 210, 495);

for($q=0;$q<18;$q++){
	pdf_show_xy($p, "abcdefghijklmnopqrstuvwxyzabc $q", 30, (455-($q*20)));
}

PDF_end_page_ext($p, "");

PDF_end_document($p, "");

$buf = PDF_get_buffer($p);
$len = strlen($buf);

header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=test.pdf");
print $buf;

PDF_delete($p);
?>
