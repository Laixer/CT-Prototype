<?php
/**
 * Project hour registration
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
$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE Project_id='%s' AND Project_offer_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], mysql_real_escape_string($_GET['o_id']));
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

# All chapters for this projecy
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_work_inv1_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv2_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());

# Check for empty totals
# 10
//$rs_project_empty_total_10_qry = sprintf("SELECT Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30) LIMIT 1", $rs_project_perm_check_row['Project_id']);
//$rs_project_empty_total_10_result = mysql_query($rs_project_empty_total_10_qry) or die("Error: " . mysql_error());
//$rs_project_empty_total_10_row = mysql_fetch_assoc($rs_project_empty_total_10_result);
# 40
//$rs_project_empty_total_40_qry = sprintf("SELECT Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=40 LIMIT 1", $rs_project_perm_check_row['Project_id']);
//$rs_project_empty_total_40_result = mysql_query($rs_project_empty_total_40_qry) or die("Error: " . mysql_error());
//$rs_project_empty_total_40_row = mysql_fetch_assoc($rs_project_empty_total_40_result);

# All chapters for this projecy
$rs_project_total_qry = sprintf("SELECT *, SUM(1_total) AS Super_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Offerte</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>
<div style="background-color:#FFF">
	<div style="margin: 0 auto; width: 1070px;">
		<div class="entry">
		<div style="height:100px;" id="title">
			<span style="float:right; margin:35px 50px;color:#000;">OFFERTE</span>
			<span style="float:left"><img src="http://static-4.cdnhub.nl/nl/images/logos/van-dale-logo-big.gif" alt="Logo" width="200" height="100"></span>
		</div>
	<div id="content-main">
		<div id="intern">
			<div id="table">
				<table width="100%" style="border:solid 1px #000000">
					<tr>
						<td>&nbsp;</td>
						<td><b><?php echo $rs_project_rel_us_row['Company_name']; ?></b></td>
						<td><?php echo $rs_user_relation_row['Company_name']; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><b>T.A.V. <?php echo $rs_project_rel_us_row['Contact_first_name']." ".$rs_project_rel_us_row['Contact_name']; ?></b></td>
						<td><?php echo $rs_user_relation_row['Address']." ".$rs_user_relation_row['Address_number']; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><b><?php echo $rs_project_rel_us_row['Address']." ".$rs_project_rel_us_row['Address_number']; ?></b></td>
						<td><?php echo $rs_user_relation_row['Zipcode']." ".$rs_user_relation_row['City']; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><b><?php echo $rs_project_rel_us_row['Zipcode']." ".$rs_project_rel_us_row['City']; ?></b></td>
						<td>T: <?php echo $rs_user_relation_row['Phone_1']; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>M: <?php echo $rs_user_relation_row['Phone_2']; ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>E: <?php echo $rs_user_relation_row['Email_1']; ?></td>
					</tr>
					<tr>
						<td><b>Projectnaam</b></td>
						<td><?php echo $rs_project_perm_check_row['Name']; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b>Projectnummer</b></td>
						<td><?php echo $rs_project_perm_check_row['Project_id']; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b>Datum</b></td>
						<td><?php echo date("d-m-Y"); ?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<div style="margin: 15px 0 25px 0">
					<?php echo nl2br($rs_project_offer_row['Pretext']); ?>
				</div>
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<td colspan="2" align="left">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td colspan="2" align="center" style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;border-left:solid 1px #000000"><b>Arbeid</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Materiaal</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Materieel</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Totaalpost</b></td>
						<td align="center" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000;border-right:solid 1px #000000"><b>Totaal</b></td>
						<?php } ?>
					</tr>
					<tr>
						<td colspan="2" align="left"><b>UIT TE VOEREN WERKZAAMHEDEN</b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">Arbeidsuren</td>
						<td align="center" style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">Arbeidskosten</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000;border-right:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						</tr>
					<?php
					while($rs_project_work_inv1_row = mysql_fetch_assoc($rs_project_work_inv1_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND (quant.Invoice_id=10 OR quant.Invoice_id=20 OR quant.Invoice_id=30) AND 1_total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(Material) AS Total_material, SUM(Physical) AS Total_physical, SUM(Post) AS Total_post, SUM(1_total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND (Invoice_id=10 OR Invoice_id=20 OR Invoice_id=30) ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);						
						if($rs_project_quant_operation_num){
					?>
					<tr>
						<td colspan="2"><b><?php echo $rs_project_work_inv1_row['Chapter']; ?></b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-left:solid 1px #000000"></td>
						<td width="115" style="border-left:solid 1px #000000"></td>
						<td width="107" style="border-left:solid 1px #000000"></td>
						<td width="109" style="border-left:solid 1px #000000"></td>
						<td width="124" style="border-left:solid 1px #000000"></td>
						<td width="21" style="border-left:solid 1px #000000"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-left:solid 1px #000000;border-right:solid 1px #000000"></td>
						<?php } ?>
						</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="34">&nbsp;</td>
						<td width="225"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Hour_cost']+$rs_project_quant_operation_row['Hour_cost2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost']+$rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Post']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Post'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="right" style="border-left:solid 1px #000000;border-right:solid 1px #000000"><?php if($rs_project_quant_operation_row['Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<?php } ?>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_post']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_post'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="right" style="border-left:solid 1px #000000;border-right:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<?php } ?>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-left:solid 1px #000000"></td>
						<td width="115" style="border-left:solid 1px #000000"></td>
						<td width="107" style="border-left:solid 1px #000000"></td>
						<td width="109" style="border-left:solid 1px #000000"></td>
						<td width="124" style="border-left:solid 1px #000000"></td>
						<td width="21" style="border-left:solid 1px #000000"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-left:solid 1px #000000;border-right:solid 1px #000000"></td>
						<?php } ?>
						</tr>
					<?php } } ?>
					<tr>
						<td colspan="2">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-top:solid 1px #000000"></td>
						<td width="115" style="border-top:solid 1px #000000"></td>
						<td width="107" style="border-top:solid 1px #000000"></td>
						<td width="109" style="border-top:solid 1px #000000"></td>
						<td width="124" style="border-top:solid 1px #000000"></td>
						<td width="21"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-top:solid 1px #000000"></td>
						<?php } ?>
					</tr>
				</table><br />
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<td colspan="2" align="left">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td colspan="2" align="center" style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;border-left:solid 1px #000000"><b>Arbeid</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Materiaal</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Materieel</b></td>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000"><b>Totaalpost</b></td>
						<td align="center" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="center" style="border-top:solid 1px #000000;border-left:solid 1px #000000;border-right:solid 1px #000000"><b>Totaal</b></td>
						<?php } ?>
						</tr>
					<tr>
						<td colspan="2" align="left"><b>OPGENOMEN STELPOSTEN</b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">Arbeidsuren</td>
						<td align="center" style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">Arbeidskosten</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000">&nbsp;</td>
						<td style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td style="border-bottom:solid 1px #000000;border-left:solid 1px #000000;border-right:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						</tr>
					<?php
					while($rs_project_work_inv2_row = mysql_fetch_assoc($rs_project_work_inv2_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=40 AND 1_total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(Material) AS Total_material, SUM(Physical) AS Total_physical, SUM(Post) AS Total_post, SUM(1_total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=40 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);						
						if($rs_project_quant_operation_num){
					?>
					<tr>
						<td colspan="2"><b><?php echo $rs_project_work_inv2_row['Chapter']; ?></b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-left:solid 1px #000000"></td>
						<td width="115" style="border-left:solid 1px #000000"></td>
						<td width="107" style="border-left:solid 1px #000000"></td>
						<td width="109" style="border-left:solid 1px #000000"></td>
						<td width="124" style="border-left:solid 1px #000000"></td>
						<td width="21" style="border-left:solid 1px #000000"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-left:solid 1px #000000;border-right:solid 1px #000000"></td>
						<?php } ?>
						</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="34">&nbsp;</td>
						<td width="225"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Hour_cost']+$rs_project_quant_operation_row['Hour_cost2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost']+$rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><?php if($rs_project_quant_operation_row['Post']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Post'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="right" style="border-left:solid 1px #000000;border-right:solid 1px #000000"><?php if($rs_project_quant_operation_row['Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<?php } ?>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td align="center" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Total_post']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_post'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right" style="border-left:solid 1px #000000">&nbsp;</td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td align="right" style="border-left:solid 1px #000000;border-right:solid 1px #000000"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<?php } ?>
						</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-left:solid 1px #000000"></td>
						<td width="115" style="border-left:solid 1px #000000"></td>
						<td width="107" style="border-left:solid 1px #000000"></td>
						<td width="109" style="border-left:solid 1px #000000"></td>
						<td width="124" style="border-left:solid 1px #000000"></td>
						<td width="21" style="border-left:solid 1px #000000"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-left:solid 1px #000000;border-right:solid 1px #000000"></td>
						<?php } ?>
						</tr>
					<?php } } ?>
					<tr>
						<td colspan="2">&nbsp;</td>
						<?php if(($rs_project_offer_row['Type'] != 2)&&($rs_project_offer_row['Type'] != 3)){ ?>
						<td width="123" style="border-top:solid 1px #000000"></td>
						<td width="115" style="border-top:solid 1px #000000"></td>
						<td width="107" style="border-top:solid 1px #000000"></td>
						<td width="109" style="border-top:solid 1px #000000"></td>
						<td width="124" style="border-top:solid 1px #000000"></td>
						<td width="21"></td>
						<?php } ?>
						<?php if($rs_project_offer_row['Type'] != 3){ ?>
						<td width="100" style="border-top:solid 1px #000000"></td>
						<?php } ?>
					</tr>
				</table><br />
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<td width="355" align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right"><b>Subtotaal Excl. BTW</b></td>
						<td align="right"><b>&euro;</b></td>
						<td align="right"><?php	echo number_format($rs_project_total_row['Super_total'], 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php if($rs_project_offer_row['Tax'] == 'Y'){ ?>
						<td align="right">BTW bedrag 21%</td>
						<td align="right">&euro;</td>
						<td align="right"><?php
							$total_40_1 = $rs_project_result_row['Salary_1_tax_40']+$rs_project_result_row['Material_1_tax_40']+$rs_project_result_row['Physical_1_tax_40']+$rs_project_result_row['Sum_1_tax_40'];
							$total_40_2 = $rs_project_result_row['Salary_2_tax_40']+$rs_project_result_row['Material_2_tax_40']+$rs_project_result_row['Physical_2_tax_40']+$rs_project_result_row['Sum_2_tax_40'];
							$total_40_3 = $rs_project_result_row['Salary_3_tax_40']+$rs_project_result_row['Material_3_tax_40']+$rs_project_result_row['Physical_3_tax_40']+$rs_project_result_row['Sum_3_tax_40'];
							$total_40_4 = $rs_project_result_row['Salary_4_tax_40']+$rs_project_result_row['Material_4_tax_40']+$rs_project_result_row['Physical_4_tax_40']+$rs_project_result_row['Sum_4_tax_40'];
							$super_40 = $total_40_1+$total_40_2+$total_40_3+$total_40_4;
							echo number_format($super_40, 2, ',', '.');
						?></td>
						<?php } ?>
						</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php if($rs_project_offer_row['Tax'] == 'Y'){ ?>
						<td align="right">BTW bedrag 6%</td>
						<td align="right">&euro;</td>
						<td align="right"><?php
							$total_20_1 = $rs_project_result_row['Salary_1_tax_20']+$rs_project_result_row['Material_1_tax_20']+$rs_project_result_row['Physical_1_tax_20']+$rs_project_result_row['Sum_1_tax_20'];
							$total_20_2 = $rs_project_result_row['Salary_2_tax_20']+$rs_project_result_row['Material_2_tax_20']+$rs_project_result_row['Physical_2_tax_20']+$rs_project_result_row['Sum_2_tax_20'];
							$total_20_3 = $rs_project_result_row['Salary_3_tax_20']+$rs_project_result_row['Material_3_tax_20']+$rs_project_result_row['Physical_3_tax_20']+$rs_project_result_row['Sum_3_tax_20'];
							$total_20_4 = $rs_project_result_row['Salary_4_tax_20']+$rs_project_result_row['Material_4_tax_20']+$rs_project_result_row['Physical_4_tax_20']+$rs_project_result_row['Sum_4_tax_20'];
							$super_20 = $total_20_1+$total_20_2+$total_20_3+$total_20_4;
							echo number_format($super_20, 2, ',', '.');
						?></td>
						<?php } ?>
						</tr>
					<tr>
						<td>&nbsp;</td>
						<td width="123"></td>
						<td width="115"></td>
						<td width="107"></td>
						<td width="54"></td>
						<?php if($rs_project_offer_row['Tax'] == 'Y'){ ?>
						<td width="160" align="right"><b>Totaal Incl. BTW</b></td>
						<td width="40" align="right"><b>&euro;</b></td>
						<td width="100" align="right"><?php	echo number_format(($rs_project_total_row['Super_total']+$super_20+$super_40), 2, ',', '.'); ?></td>
						<?php } ?>
					</tr>
				</table>
				<table width="50%" border="0">
					<tr>
						<td width="20%"><b><u>Bepalingen</u></b></td>
						<td width="80%">&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b>Levertijd</b></td>
						<td>
						<?php if($rs_project_offer_row['Delivery'] == 31){
							echo "in overleg";
						}else if($rs_project_offer_row['Delivery'] == 32){
							echo "direct";
						}else if($rs_project_offer_row['Delivery'] == 1){
							echo "binnen 1 dag na dagtekening offerte";
						}else{
							echo "binnen ".$rs_project_offer_row['Delivery']." dagen na dagtekening offerte";
						}
						?></td>
					</tr>
					<tr>
						<td><b>Betaling</b></td>
						<td>
						<?php if($rs_project_offer_row['Payment'] == 31){
							echo "in overleg";
						}else if($rs_project_offer_row['Payment'] == 32){
							echo "tijdens levering";
						}else if($rs_project_offer_row['Payment'] == 1){
							echo "binnen 1 dag na levering";
						}else{
							echo "binnen ".$rs_project_offer_row['Payment']." dagen na levering";
						}
						?></td>
					</tr>
					<tr>
						<td valign="top"><b>Condities</b></td>
						<td><?php echo nl2br($rs_project_offer_row['Condition']); ?></td>
					</tr>
					<tr>
						<td><b>Stand</b></td>
						<td>
						<?php if($rs_project_offer_row['Ending'] == 91){
							echo "in overleg";
						}else{
							echo "tot ".$rs_project_offer_row['Ending']." dagen na dagtekening offerte";
						}
						?></td>

					</tr>
					<tr>
						<td valign="top"><b>Opmerking</b></td>
						<td><?php echo nl2br($rs_project_offer_row['Note']); ?></td>
					</tr>
				</table>
				<div style="margin: 15px 0 10px 0">
					<?php echo nl2br($rs_project_offer_row['Posttext']); ?>
				</div>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
</div>
</div>
</div>
</body>
</html>