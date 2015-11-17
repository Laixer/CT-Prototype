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

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

# Object totals
$rs_project_result_qry = sprintf("
SELECT mod_2.Project_id,mod_2.User_id,
mod_2.Salary_amount_1_10 AS Salary_amount_1_10_calc,
mod_2.Salary_amount_1_20 AS Salary_amount_1_20_calc,
mod_2.Salary_amount_1_40 AS Salary_amount_1_40_calc,
mod_2.Salary_1_notax_10 AS Salary_1_notax_10_calc,
mod_2.Salary_1_notax_20 AS Salary_1_notax_20_calc,
mod_2.Salary_1_tax_20 AS Salary_1_tax_20_calc,
mod_2.Salary_1_notax_40 AS Salary_1_notax_40_calc,
mod_2.Salary_1_tax_40 AS Salary_1_tax_40_calc,
mod_2.Material_1_notax_10 AS Material_1_notax_10_calc,
mod_2.Material_1_notax_20 AS Material_1_notax_20_calc,
mod_2.Material_1_tax_20 AS Material_1_tax_20_calc,
mod_2.Material_1_notax_40 AS Material_1_notax_40_calc,
mod_2.Material_1_tax_40 AS Material_1_tax_40_calc,
mod_2.Physical_1_notax_10 AS Physical_1_notax_10_calc,
mod_2.Physical_1_notax_20 AS Physical_1_notax_20_calc,
mod_2.Physical_1_tax_20 AS Physical_1_tax_20_calc,
mod_2.Physical_1_notax_40 AS Physical_1_notax_40_calc,
mod_2.Physical_1_tax_40 AS Physical_1_tax_40_calc,
mod_2.Sum_1_notax_20 AS Sum_1_notax_20_calc,
mod_2.Sum_1_tax_20 AS Sum_1_tax_20_calc,
mod_2.Sum_1_notax_40 AS Sum_1_notax_40_calc,
mod_2.Sum_1_tax_40 AS Sum_1_tax_40_calc,
mod_2.Salary_amount_2_10 AS Salary_amount_2_10_calc,
mod_2.Salary_amount_2_20 AS Salary_amount_2_20_calc,
mod_2.Salary_amount_2_40 AS Salary_amount_2_40_calc,
mod_2.Salary_2_notax_10 AS Salary_2_notax_10_calc,
mod_2.Salary_2_notax_20 AS Salary_2_notax_20_calc,
mod_2.Salary_2_tax_20 AS Salary_2_tax_20_calc,
mod_2.Salary_2_notax_40 AS Salary_2_notax_40_calc,
mod_2.Salary_2_tax_40 AS Salary_2_tax_40_calc,
mod_2.Material_2_notax_10 AS Material_2_notax_10_calc,
mod_2.Material_2_notax_20 AS Material_2_notax_20_calc,
mod_2.Material_2_tax_20 AS Material_2_tax_20_calc,
mod_2.Material_2_notax_40 AS Material_2_notax_40_calc,
mod_2.Material_2_tax_40 AS Material_2_tax_40_calc,
mod_2.Physical_2_notax_10 AS Physical_2_notax_10_calc,
mod_2.Physical_2_notax_20 AS Physical_2_notax_20_calc,
mod_2.Physical_2_tax_20 AS Physical_2_tax_20_calc,
mod_2.Physical_2_notax_40 AS Physical_2_notax_40_calc,
mod_2.Physical_2_tax_40 AS Physical_2_tax_40_calc,
mod_2.Sum_2_notax_20 AS Sum_2_notax_20_calc,
mod_2.Sum_2_tax_20 AS Sum_2_tax_20_calc,
mod_2.Sum_2_notax_40 AS Sum_2_notax_40_calc,
mod_2.Sum_2_tax_40 AS Sum_2_tax_40_calc,
mod_2.Salary_amount_3_10 AS Salary_amount_3_10_calc,
mod_2.Salary_amount_3_20 AS Salary_amount_3_20_calc,
mod_2.Salary_amount_3_40 AS Salary_amount_3_40_calc,
mod_2.Salary_3_notax_10 AS Salary_3_notax_10_calc,
mod_2.Salary_3_notax_20 AS Salary_3_notax_20_calc,
mod_2.Salary_3_tax_20 AS Salary_3_tax_20_calc,
mod_2.Salary_3_notax_40 AS Salary_3_notax_40_calc,
mod_2.Salary_3_tax_40 AS Salary_3_tax_40_calc,
mod_2.Material_3_notax_10 AS Material_3_notax_10_calc,
mod_2.Material_3_notax_20 AS Material_3_notax_20_calc,
mod_2.Material_3_tax_20 AS Material_3_tax_20_calc,
mod_2.Material_3_notax_40 AS Material_3_notax_40_calc,
mod_2.Material_3_tax_40 AS Material_3_tax_40_calc,
mod_2.Physical_3_notax_10 AS Physical_3_notax_10_calc,
mod_2.Physical_3_notax_20 AS Physical_3_notax_20_calc,
mod_2.Physical_3_tax_20 AS Physical_3_tax_20_calc,
mod_2.Physical_3_notax_40 AS Physical_3_notax_40_calc,
mod_2.Physical_3_tax_40 AS Physical_3_tax_40_calc,
mod_2.Sum_3_notax_20 AS Sum_3_notax_20_calc,
mod_2.Sum_3_tax_20 AS Sum_3_tax_20_calc,
mod_2.Sum_3_notax_40 AS Sum_3_notax_40_calc,
mod_2.Sum_3_tax_40 AS Sum_3_tax_40_calc,
mod_7.Salary_amount_4_10 AS Salary_amount_4_10_calc,
mod_7.Salary_amount_4_20 AS Salary_amount_4_20_calc,
mod_7.Salary_amount_4_40 AS Salary_amount_4_40_calc,
mod_7.Salary_4_notax_10 AS Salary_4_notax_10_calc,
mod_7.Salary_4_notax_20 AS Salary_4_notax_20_calc,
mod_7.Salary_4_tax_20 AS Salary_4_tax_20_calc,
mod_7.Salary_4_notax_40 AS Salary_4_notax_40_calc,
mod_7.Salary_4_tax_40 AS Salary_4_tax_40_calc,
mod_7.Material_4_notax_10 AS Material_4_notax_10_calc,
mod_7.Material_4_notax_20 AS Material_4_notax_20_calc,
mod_7.Material_4_tax_20 AS Material_4_tax_20_calc,
mod_7.Material_4_notax_40 AS Material_4_notax_40_calc,
mod_7.Material_4_tax_40 AS Material_4_tax_40_calc,
mod_7.Physical_4_notax_10 AS Physical_4_notax_10_calc,
mod_7.Physical_4_notax_20 AS Physical_4_notax_20_calc,
mod_7.Physical_4_tax_20 AS Physical_4_tax_20_calc,
mod_7.Physical_4_notax_40 AS Physical_4_notax_40_calc,
mod_7.Physical_4_tax_40 AS Physical_4_tax_40_calc,	
more.Salary_amount_1_10 AS Salary_amount_1_10_more,
more.Salary_amount_1_20 AS Salary_amount_1_20_more,
more.Salary_amount_1_40 AS Salary_amount_1_40_more,
more.Salary_1_notax_10 AS Salary_1_notax_10_more,
more.Salary_1_notax_20 AS Salary_1_notax_20_more,
more.Salary_1_tax_20 AS Salary_1_tax_20_more,
more.Salary_1_notax_40 AS Salary_1_notax_40_more,
more.Salary_1_tax_40 AS Salary_1_tax_40_more,
more.Material_1_notax_10 AS Material_1_notax_10_more,
more.Material_1_notax_20 AS Material_1_notax_20_more,
more.Material_1_tax_20 AS Material_1_tax_20_more,
more.Material_1_notax_40 AS Material_1_notax_40_more,
more.Material_1_tax_40 AS Material_1_tax_40_more,
more.Physical_1_notax_10 AS Physical_1_notax_10_more,
more.Physical_1_notax_20 AS Physical_1_notax_20_more,
more.Physical_1_tax_20 AS Physical_1_tax_20_more,
more.Physical_1_notax_40 AS Physical_1_notax_40_more,
more.Physical_1_tax_40 AS Physical_1_tax_40_more,
more.Salary_amount_2_10 AS Salary_amount_2_10_more,
more.Salary_amount_2_20 AS Salary_amount_2_20_more,
more.Salary_amount_2_40 AS Salary_amount_2_40_more,
more.Salary_2_notax_10 AS Salary_2_notax_10_more,
more.Salary_2_notax_20 AS Salary_2_notax_20_more,
more.Salary_2_tax_20 AS Salary_2_tax_20_more,
more.Salary_2_notax_40 AS Salary_2_notax_40_more,
more.Salary_2_tax_40 AS Salary_2_tax_40_more,
more.Material_2_notax_10 AS Material_2_notax_10_more,
more.Material_2_notax_20 AS Material_2_notax_20_more,
more.Material_2_tax_20 AS Material_2_tax_20_more,
more.Material_2_notax_40 AS Material_2_notax_40_more,
more.Material_2_tax_40 AS Material_2_tax_40_more,
more.Physical_2_notax_10 AS Physical_2_notax_10_more,
more.Physical_2_notax_20 AS Physical_2_notax_20_more,
more.Physical_2_tax_20 AS Physical_2_tax_20_more,
more.Physical_2_notax_40 AS Physical_2_notax_40_more,
more.Physical_2_tax_40 AS Physical_2_tax_40_more,
more.Salary_amount_3_10 AS Salary_amount_3_10_more,
more.Salary_amount_3_20 AS Salary_amount_3_20_more,
more.Salary_amount_3_40 AS Salary_amount_3_40_more,
more.Salary_3_notax_10 AS Salary_3_notax_10_more,
more.Salary_3_notax_20 AS Salary_3_notax_20_more,
more.Salary_3_tax_20 AS Salary_3_tax_20_more,
more.Salary_3_notax_40 AS Salary_3_notax_40_more,
more.Salary_3_tax_40 AS Salary_3_tax_40_more,
more.Material_3_notax_10 AS Material_3_notax_10_more,
more.Material_3_notax_20 AS Material_3_notax_20_more,
more.Material_3_tax_20 AS Material_3_tax_20_more,
more.Material_3_notax_40 AS Material_3_notax_40_more,
more.Material_3_tax_40 AS Material_3_tax_40_more,
more.Physical_3_notax_10 AS Physical_3_notax_10_more,
more.Physical_3_notax_20 AS Physical_3_notax_20_more,
more.Physical_3_tax_20 AS Physical_3_tax_20_more,
more.Physical_3_notax_40 AS Physical_3_notax_40_more,
more.Physical_3_tax_40 AS Physical_3_tax_40_more,
less_.Salary_amount_1_10 AS Salary_amount_1_10_less,
less_.Salary_amount_1_20 AS Salary_amount_1_20_less,
less_.Salary_amount_1_40 AS Salary_amount_1_40_less,
less_.Salary_1_notax_10 AS Salary_1_notax_10_less,
less_.Salary_1_notax_20 AS Salary_1_notax_20_less,
less_.Salary_1_tax_20 AS Salary_1_tax_20_less,
less_.Salary_1_notax_40 AS Salary_1_notax_40_less,
less_.Salary_1_tax_40 AS Salary_1_tax_40_less,
less_.Material_1_notax_10 AS Material_1_notax_10_less,
less_.Material_1_notax_20 AS Material_1_notax_20_less,
less_.Material_1_tax_20 AS Material_1_tax_20_less,
less_.Material_1_notax_40 AS Material_1_notax_40_less,
less_.Material_1_tax_40 AS Material_1_tax_40_less,
less_.Physical_1_notax_10 AS Physical_1_notax_10_less,
less_.Physical_1_notax_20 AS Physical_1_notax_20_less,
less_.Physical_1_tax_20 AS Physical_1_tax_20_less,
less_.Physical_1_notax_40 AS Physical_1_notax_40_less,
less_.Physical_1_tax_40 AS Physical_1_tax_40_less,
less_.Sum_1_notax_20 AS Sum_1_notax_20_less,
less_.Sum_1_tax_20 AS Sum_1_tax_20_less,
less_.Sum_1_notax_40 AS Sum_1_notax_40_less,
less_.Sum_1_tax_40 AS Sum_1_tax_40_less,
less_.Salary_amount_2_10 AS Salary_amount_2_10_less,
less_.Salary_amount_2_20 AS Salary_amount_2_20_less,
less_.Salary_amount_2_40 AS Salary_amount_2_40_less,
less_.Salary_2_notax_10 AS Salary_2_notax_10_less,
less_.Salary_2_notax_20 AS Salary_2_notax_20_less,
less_.Salary_2_tax_20 AS Salary_2_tax_20_less,
less_.Salary_2_notax_40 AS Salary_2_notax_40_less,
less_.Salary_2_tax_40 AS Salary_2_tax_40_less,
less_.Material_2_notax_10 AS Material_2_notax_10_less,
less_.Material_2_notax_20 AS Material_2_notax_20_less,
less_.Material_2_tax_20 AS Material_2_tax_20_less,
less_.Material_2_notax_40 AS Material_2_notax_40_less,
less_.Material_2_tax_40 AS Material_2_tax_40_less,
less_.Physical_2_notax_10 AS Physical_2_notax_10_less,
less_.Physical_2_notax_20 AS Physical_2_notax_20_less,
less_.Physical_2_tax_20 AS Physical_2_tax_20_less,
less_.Physical_2_notax_40 AS Physical_2_notax_40_less,
less_.Physical_2_tax_40 AS Physical_2_tax_40_less,
less_.Sum_2_notax_20 AS Sum_2_notax_20_less,
less_.Sum_2_tax_20 AS Sum_2_tax_20_less,
less_.Sum_2_notax_40 AS Sum_2_notax_40_less,
less_.Sum_2_tax_40 AS Sum_2_tax_40_less,
less_.Salary_amount_3_10 AS Salary_amount_3_10_less,
less_.Salary_amount_3_20 AS Salary_amount_3_20_less,
less_.Salary_amount_3_40 AS Salary_amount_3_40_less,
less_.Salary_3_notax_10 AS Salary_3_notax_10_less,
less_.Salary_3_notax_20 AS Salary_3_notax_20_less,
less_.Salary_3_tax_20 AS Salary_3_tax_20_less,
less_.Salary_3_notax_40 AS Salary_3_notax_40_less,
less_.Salary_3_tax_40 AS Salary_3_tax_40_less,
less_.Material_3_notax_10 AS Material_3_notax_10_less,
less_.Material_3_notax_20 AS Material_3_notax_20_less,
less_.Material_3_tax_20 AS Material_3_tax_20_less,
less_.Material_3_notax_40 AS Material_3_notax_40_less,
less_.Material_3_tax_40 AS Material_3_tax_40_less,
less_.Physical_3_notax_10 AS Physical_3_notax_10_less,
less_.Physical_3_notax_20 AS Physical_3_notax_20_less,
less_.Physical_3_tax_20 AS Physical_3_tax_20_less,
less_.Physical_3_notax_40 AS Physical_3_notax_40_less,
less_.Physical_3_tax_40 AS Physical_3_tax_40_less,
less_.Sum_3_notax_20 AS Sum_3_notax_20_less,
less_.Sum_3_tax_20 AS Sum_3_tax_20_less,
less_.Sum_3_notax_40 AS Sum_3_notax_40_less,
less_.Sum_3_tax_40 AS Sum_3_tax_40_less
FROM tvw_result_mod_2 AS mod_2, tvw_result_mod_7 AS mod_7, tvw_result_more AS more,tvw_result_less AS less_
WHERE mod_2.Project_id=%d
AND mod_7.Project_id=mod_2.Project_id
AND more.Project_id=mod_2.Project_id
AND less_.Project_id=mod_2.Project_id
LIMIT 1
", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

$rs_project_profit_qry = sprintf("SELECT * FROM tbl_project_profit WHERE Project_id=%d", $rs_project_perm_check_row['Project_id']);
$rs_project_profit_row = mysql_fetch_assoc(mysql_query($rs_project_profit_qry));

# Check project user
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=1 AND Tax_id=40 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row40_1 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=2 AND Tax_id=40 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row40_2 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=1 AND Tax_id=20 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row20_1 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=2 AND Tax_id=20 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row20_2 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=1 AND Tax_id=10 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row10_1 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=2 AND Tax_id=10 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_hour_row10_2 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));

$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=3 AND Tax_id=40 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_sphour_row40 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=3 AND Tax_id=20 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_sphour_row20 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));
$rs_project_hour_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_hour WHERE Project_id=%d AND More_work=3 AND Tax_id=10 GROUP BY Tax_id", $rs_project_perm_check_row['Project_id']);
$rs_project_sphour_row10 = mysql_fetch_assoc(mysql_query($rs_project_hour_qry));

$rs_project_invoice_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_invoice WHERE Project_id=%d AND Invoice_option=1", $rs_project_perm_check_row['Project_id']);
$rs_project_invoice_row40 = mysql_fetch_assoc(mysql_query($rs_project_invoice_qry));
$rs_project_invoice_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_invoice WHERE Project_id=%d AND Invoice_option=2", $rs_project_perm_check_row['Project_id']);
$rs_project_invoice_row20 = mysql_fetch_assoc(mysql_query($rs_project_invoice_qry));
$rs_project_invoice_qry = sprintf("SELECT SUM(Amount) AS t FROM tbl_project_calc_invoice WHERE Project_id=%d AND Invoice_option=4", $rs_project_perm_check_row['Project_id']);
$rs_project_invoice_row10 = mysql_fetch_assoc(mysql_query($rs_project_invoice_qry));

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
<?php if(!$hide_page){ ?>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
		  <input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=150&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Eindresultaat Project" />
		</div>
		<span>Winst &amp; Verlies</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic">
			<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
	<div id="content-main">
		<div id="intern">
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td>Aanneming</td>
						<td align="left"><div align="center">Calculatie</div></td>
						<td><div align="center">Meerwerk</div></td>
						<td align="center"><div align="center">Minderwerk</div></td>
						<td align="center">Totaal</td>
						<td align="center"><div align="center">Kosten</div></td>
						<td align="left"><div align="center"></div></td>
					</tr>
					<tr>
						<td>Arbeidskosten</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_40_calc'], 2, ',', '.'); ?></td>
						<td width="80" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_40_more'], 2, ',', '.'); ?></td>
						<td width="116" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_40_less'], 2, ',', '.'); ?></td>
						<td width="117" align="right" class="tbl-subhead"><?php $subtotal_salary_1_40 = $rs_project_result_row['Salary_1_notax_40_calc']+$rs_project_result_row['Salary_1_notax_40_more']+$rs_project_result_row['Salary_1_notax_40_less']; echo '&euro;'.number_format($subtotal_salary_1_40, 2, ',', '.'); ?></td>
						<td width="115" align="center"><?php $sub_1 = ($rs_project_hour_row40_1['t']*$rs_project_profit_row['Hour_salary'])+($rs_project_hour_row40_2['t']*$rs_project_profit_row['Hour_salary_sec']); echo '&euro;'.number_format($sub_1, 2, ',', '.'); ?></td>
						<td width="127" align="right" class="tbl-subhead"><?php $t1 = $subtotal_salary_1_40-$sub_1; if($t1 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t1, 2, ',', '.'); if($t1 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td width="383">&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_20_calc'], 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_20_more'], 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_20_less'], 2, ',', '.'); ?></td>
						<td align="right" class="tbl-subhead"><?php $subtotal_salary_1_20 = $rs_project_result_row['Salary_1_notax_20_calc']+$rs_project_result_row['Salary_1_notax_20_more']+$rs_project_result_row['Salary_1_notax_20_less']; echo '&euro;'.number_format($subtotal_salary_1_20, 2, ',', '.'); ?></td>
						<td align="center"><?php $sub_2 =($rs_project_hour_row20_1['t']*$rs_project_profit_row['Hour_salary'])+($rs_project_hour_row20_2['t']*$rs_project_profit_row['Hour_salary_sec']); echo '&euro;'.number_format($sub_2, 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php $t2 = $subtotal_salary_1_20-$sub_2; if($t2 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t2, 2, ',', '.'); if($t2 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_10_calc'], 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_10_more'], 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_10_less'], 2, ',', '.'); ?></td>
						<td align="right" class="tbl-subhead"><?php $subtotal_salary_1_10 = $rs_project_result_row['Salary_1_notax_10_calc']+$rs_project_result_row['Salary_1_notax_10_more']+$rs_project_result_row['Salary_1_notax_10_less']; echo '&euro;'.number_format($subtotal_salary_1_10, 2, ',', '.'); ?></td>
						<td align="center"><?php $sub_3 = ($rs_project_hour_row10_1['t']*$rs_project_profit_row['Hour_salary'])+($rs_project_hour_row10_2['t']*$rs_project_profit_row['Hour_salary_sec']); echo '&euro;'.number_format($sub_3, 2, ',', '.'); ?></td>
						<td class="tbl-subhead" align="right"><?php $t3 = $subtotal_salary_1_10-$sub_3; if($t3 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t3, 2, ',', '.'); if($t3 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td>Materiaal / materieel kosten</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_40_calc']+$rs_project_result_row['Physical_1_notax_40_calc']+$rs_project_result_row['Material_1_notax_10_calc']+$rs_project_result_row['Physical_1_notax_10_calc'], 2, ',', '.'); ?></td>
						<td width="80" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_40_more']+$rs_project_result_row['Physical_1_notax_40_more']+$rs_project_result_row['Material_1_notax_10_more']+$rs_project_result_row['Physical_1_notax_10_more'], 2, ',', '.'); ?></td>
						<td width="116" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_40_less']+$rs_project_result_row['Physical_1_notax_40_less']+$rs_project_result_row['Material_1_notax_10_less']+$rs_project_result_row['Physical_1_notax_10_less'], 2, ',', '.'); ?></td>
						<td width="117" align="right" class="tbl-subhead">
						<?php $subtotal_material_1_40 = $rs_project_result_row['Material_1_notax_40_calc']+$rs_project_result_row['Material_1_notax_40_more']+$rs_project_result_row['Material_1_notax_40_less'];
						 $subtotal_physical_1_40 = $rs_project_result_row['Physical_1_notax_40_calc']+$rs_project_result_row['Physical_1_notax_40_more']+$rs_project_result_row['Physical_1_notax_40_less'];
						$subtotal_material_1_10 = $rs_project_result_row['Material_1_notax_10_calc']+$rs_project_result_row['Material_1_notax_10_more']+$rs_project_result_row['Material_1_notax_10_less'];
						$subtotal_physical_1_10 = $rs_project_result_row['Physical_1_notax_10_calc']+$rs_project_result_row['Physical_1_notax_10_more']+$rs_project_result_row['Physical_1_notax_10_less']; echo '&euro;'.number_format($subtotal_physical_1_10+$subtotal_material_1_10+$subtotal_material_1_40+$subtotal_physical_1_40, 2, ',', '.'); ?></td>
						<td width="115" align="center"><?php $sub_4 = $rs_project_invoice_row40['t']; echo '&euro;'.number_format($sub_4, 2, ',', '.'); ?></td>
						<td width="127" align="right" class="tbl-subhead"><?php $t4 = ($subtotal_physical_1_10+$subtotal_material_1_10+$subtotal_physical_1_40+$subtotal_material_1_40)-$sub_4; if($t4 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t4, 2, ',', '.'); if($t4 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>Onderaanneming</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td>Arbeids / materiaal / materieel kosten</td>
						<td class="tbl-subhead" width="126" align="right">
							<?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_40_calc']+$rs_project_result_row['Material_2_notax_40_calc']+$rs_project_result_row['Physical_2_notax_40_calc']+$rs_project_result_row['Salary_2_notax_20_calc']+$rs_project_result_row['Physical_2_notax_10_calc']+$rs_project_result_row['Material_2_notax_10_calc']+$rs_project_result_row['Salary_2_notax_10_calc'], 2, ',', '.'); ?></td>
						<td width="80" class="tbl-subhead" align="right">
							<?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_40_more']+$rs_project_result_row['Material_2_notax_40_more']+$rs_project_result_row['Physical_2_notax_40_more']+$rs_project_result_row['Salary_2_notax_20_more']+$rs_project_result_row['Material_2_notax_10_more']+$rs_project_result_row['Physical_2_notax_10_more']+$rs_project_result_row['Salary_2_notax_10_more'], 2, ',', '.'); ?></td>
						<td width="116" class="tbl-subhead" align="right">
							<?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_40_less']+$rs_project_result_row['Material_2_notax_40_less']+$rs_project_result_row['Physical_2_notax_40_less']+$rs_project_result_row['Salary_2_notax_20_less']+$rs_project_result_row['Material_2_notax_10_less']+$rs_project_result_row['Physical_2_notax_10_less']+$rs_project_result_row['Salary_2_notax_10_less'], 2, ',', '.'); ?></td>
						<td width="117" align="right" class="tbl-subhead">
							<?php $subtotal_salary_2_40 = $rs_project_result_row['Salary_2_notax_40_calc']+$rs_project_result_row['Salary_2_notax_40_more']+$rs_project_result_row['Salary_2_notax_40_less'];
						$subtotal_material_2_40 = $rs_project_result_row['Material_2_notax_40_calc']+$rs_project_result_row['Material_2_notax_40_more']+$rs_project_result_row['Material_2_notax_40_less'];
						$subtotal_physical_2_40 = $rs_project_result_row['Physical_2_notax_40_calc']+$rs_project_result_row['Physical_2_notax_40_more']+$rs_project_result_row['Physical_2_notax_40_less'];
						$subtotal_salary_2_20 = $rs_project_result_row['Salary_2_notax_20_calc']+$rs_project_result_row['Salary_2_notax_20_more']+$rs_project_result_row['Salary_2_notax_20_less'];
						$subtotal_material_2_10 = $rs_project_result_row['Material_2_notax_10_calc']+$rs_project_result_row['Material_2_notax_10_more']+$rs_project_result_row['Material_2_notax_10_less'];
						$subtotal_physical_2_10 = $rs_project_result_row['Physical_2_notax_10_calc']+$rs_project_result_row['Physical_2_notax_10_more']+$rs_project_result_row['Physical_2_notax_10_less'];
						$subtotal_salary_2_10 = $rs_project_result_row['Salary_2_notax_10_calc']+$rs_project_result_row['Salary_2_notax_10_more']+$rs_project_result_row['Salary_2_notax_10_less']; echo '&euro;'.number_format($subtotal_salary_2_20+$subtotal_material_2_10+$subtotal_physical_2_10+$subtotal_salary_2_10+$subtotal_material_2_40+$subtotal_physical_2_40+$subtotal_salary_2_40, 2, ',', '.'); ?></td>
						<td width="115" align="center"><?php $sub_5 = $rs_project_invoice_row20['t']; echo '&euro;'.number_format($sub_5, 2, ',', '.'); ?></td>
						<td class="tbl-subhead" width="127" align="right"><?php $t5 = ($subtotal_salary_2_20+$subtotal_material_2_10+$subtotal_physical_2_10+$subtotal_salary_2_10+$subtotal_material_2_40+$subtotal_physical_2_40+$subtotal_salary_2_40)-$sub_5; if($t5 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t5, 2, ',', '.'); if($t4 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>Stelposten</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td>Arbeidskosten</td>
						<td class="tbl-subhead" width="126" align="right"><?php $subtotal_salary_3_40 = $rs_project_result_row['Salary_4_notax_40_calc']; echo '&euro;'.number_format($subtotal_salary_3_40, 2, ',', '.'); ?></td>
						<td width="80">&nbsp;</td>
						<td width="116" align="center">&nbsp;</td>
						<td width="117" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($subtotal_salary_3_40, 2, ',', '.'); ?></td>
						<td width="115" align="center"><?php echo '&euro;'.number_format($subtotal_salary_3_40, 2, ',', '.'); ?></td>
						<td class="tbl-subhead" width="127" align="right"><?php $t6 = $subtotal_salary_3_40-($rs_project_sphour_row40['t']*$rs_project_profit_row['Hour_salary']); if($t6 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t6, 2, ',', '.'); if($t6 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php $subtotal_salary_3_20 = $rs_project_result_row['Salary_4_notax_20_calc']; echo '&euro;'.number_format($subtotal_salary_3_20, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($subtotal_salary_3_20, 2, ',', '.'); ?></td>
						<td align="center"><?php echo '&euro;'.number_format($subtotal_salary_3_20, 2, ',', '.'); ?></td>
						<td align="right" class="tbl-subhead"><?php $t7 = $subtotal_salary_3_20-($rs_project_sphour_row20['t']*$rs_project_profit_row['Hour_salary']); if($t7 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t7, 2, ',', '.'); if($t7 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php $subtotal_salary_3_10 = $rs_project_result_row['Salary_4_notax_10_calc']; echo '&euro;'.number_format($subtotal_salary_3_10, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($subtotal_salary_3_10, 2, ',', '.'); ?></td>
						<td align="center"><?php echo '&euro;'.number_format($subtotal_salary_3_10, 2, ',', '.'); ?></td>
						<td align="right" class="tbl-subhead"><?php $t8 = $subtotal_salary_3_10-($rs_project_sphour_row10['t']*$rs_project_profit_row['Hour_salary']); if($t8 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t8, 2, ',', '.'); if($t8 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td>Materiaal / materieel kosten</td>
						<td class="tbl-subhead" width="126" align="right"><?php $subtotal_material_3_40 = $rs_project_result_row['Material_4_notax_40_calc'];
						$subtotal_physical_3_40 = $rs_project_result_row['Physical_4_notax_40_calc'];
						$subtotal_material_3_10 = $rs_project_result_row['Material_4_notax_10_calc'];
						$subtotal_physical_3_10 = $rs_project_result_row['Physical_4_notax_10_calc']; echo '&euro;'.number_format($subtotal_physical_3_40+$subtotal_material_3_40+$subtotal_physical_3_10+$subtotal_material_3_10, 2, ',', '.'); ?></td>
						<td width="80">&nbsp;</td>
						<td width="116" align="center">&nbsp;</td>
						<td width="117" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($subtotal_material_3_40+$subtotal_physical_3_40+$subtotal_material_3_10+$subtotal_physical_3_10, 2, ',', '.'); ?></td>
						<td width="115" align="center"><?php echo '&euro;'.number_format($rs_project_invoice_row10['t'], 2, ',', '.'); ?></td>
						<td class="tbl-subhead" width="127" align="right"><?php $t9 = ($subtotal_material_3_40+$subtotal_physical_3_40+$subtotal_material_3_10+$subtotal_physical_3_10)-$rs_project_invoice_row10['t']; if($t9 < 0){ echo '<font color="#FF0000">'; } echo '&euro;'.number_format($t9, 2, ',', '.'); if($t9 < 0){ echo '</font>'; } ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_result_result);
mysql_free_result($rs_project_perm_check_result);
?>