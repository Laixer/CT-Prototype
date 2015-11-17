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

# Calc 10
$rs_operation_calc_10_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=10 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_calc_10_result = mysql_query($rs_operation_calc_10_qry) or die("Error: " . mysql_error());
$rs_operation_calc_10_row = mysql_fetch_assoc($rs_operation_calc_10_result);
# More 60
$rs_operation_more_60_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Total) AS Overall_total FROM tvw_quantities_more WHERE Project_id='%s' AND Invoice_id=60 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_more_60_result = mysql_query($rs_operation_more_60_qry) or die("Error: " . mysql_error());
$rs_operation_more_60_row = mysql_fetch_assoc($rs_operation_more_60_result);
# Calc 20
$rs_operation_calc_20_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Post) AS Total_post, SUM(2_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=20 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_calc_20_result = mysql_query($rs_operation_calc_20_qry) or die("Error: " . mysql_error());
$rs_operation_calc_20_row = mysql_fetch_assoc($rs_operation_calc_20_result);
# More 70
$rs_operation_more_70_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Total) AS Overall_total FROM tvw_quantities_more WHERE Project_id='%s' AND Invoice_id=70 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_more_70_result = mysql_query($rs_operation_more_70_qry) or die("Error: " . mysql_error());
$rs_operation_more_70_row = mysql_fetch_assoc($rs_operation_more_70_result);
# Calc 30
$rs_operation_calc_30_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(3_Material) AS Total_material, SUM(3_Physical) AS Total_physical, SUM(3_Post) AS Total_post, SUM(3_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=30 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_calc_30_result = mysql_query($rs_operation_calc_30_qry) or die("Error: " . mysql_error());
$rs_operation_calc_30_row = mysql_fetch_assoc($rs_operation_calc_30_result);
# More 80
$rs_operation_more_80_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(3_Material) AS Total_material, SUM(3_Physical) AS Total_physical, SUM(3_Total) AS Overall_total FROM tvw_quantities_more WHERE Project_id='%s' AND Invoice_id=80 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_more_80_result = mysql_query($rs_operation_more_80_qry) or die("Error: " . mysql_error());
$rs_operation_more_80_row = mysql_fetch_assoc($rs_operation_more_80_result);
# Post
$rs_operation_post_qry = sprintf("SELECT SUM(Hour_cost) AS Total_hour_cost, SUM(Total) AS Overall_total FROM tvw_quantities_mod_7 WHERE Project_id='%s'ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_post_result = mysql_query($rs_operation_post_qry) or die("Error: " . mysql_error());
$rs_operation_post_row = mysql_fetch_assoc($rs_operation_post_result);
# Hour 1
$rs_operation_hour_1_qry = sprintf("SELECT SUM(Amount)*po.Hour_salary AS Hour_cost FROM tbl_project_calc_hour AS ph JOIN tbl_project_profit AS po ON po.Project_id=ph.Project_id WHERE ph.Project_id=%s AND ph.More_work=1 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_hour_1_result = mysql_query($rs_operation_hour_1_qry) or die("Error: " . mysql_error());
$rs_operation_hour_1_row = mysql_fetch_assoc($rs_operation_hour_1_result);
# Hour 2
$rs_operation_hour_2_qry = sprintf("SELECT SUM(Amount)*po.Hour_salary_sec AS Hour_cost FROM tbl_project_calc_hour AS ph JOIN tbl_project_profit AS po ON po.Project_id=ph.Project_id WHERE ph.Project_id=%s AND ph.More_work=2 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_hour_2_result = mysql_query($rs_operation_hour_2_qry) or die("Error: " . mysql_error());
$rs_operation_hour_2_row = mysql_fetch_assoc($rs_operation_hour_2_result);
# Invoice 1
$rs_operation_invoice_1_qry = sprintf("SELECT SUM(Amount) AS Invoice_amount FROM tbl_project_calc_invoice WHERE Project_id=%s AND Invoice_option=1 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_invoice_1_result = mysql_query($rs_operation_invoice_1_qry) or die("Error: " . mysql_error());
$rs_operation_invoice_1_row = mysql_fetch_assoc($rs_operation_invoice_1_result);
# Invoice 2
$rs_operation_invoice_2_qry = sprintf("SELECT SUM(Amount) AS Invoice_amount FROM tbl_project_calc_invoice WHERE Project_id=%s AND Invoice_option=2 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_invoice_2_result = mysql_query($rs_operation_invoice_2_qry) or die("Error: " . mysql_error());
$rs_operation_invoice_2_row = mysql_fetch_assoc($rs_operation_invoice_2_result);
# Invoice 3
$rs_operation_invoice_3_qry = sprintf("SELECT SUM(Amount) AS Invoice_amount FROM tbl_project_calc_invoice WHERE Project_id=%s AND Invoice_option=3 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_invoice_3_result = mysql_query($rs_operation_invoice_3_qry) or die("Error: " . mysql_error());
$rs_operation_invoice_3_row = mysql_fetch_assoc($rs_operation_invoice_3_result);
# Invoice 4
$rs_operation_invoice_4_qry = sprintf("SELECT SUM(Amount) AS Invoice_amount FROM tbl_project_calc_invoice WHERE Project_id=%s AND Invoice_option=4 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_invoice_4_result = mysql_query($rs_operation_invoice_4_qry) or die("Error: " . mysql_error());
$rs_operation_invoice_4_row = mysql_fetch_assoc($rs_operation_invoice_4_result);
# Less 10
$rs_operation_less_10_qry = sprintf("SELECT SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post FROM tvw_quantities_less WHERE Project_id='%s' AND Invoice_id=10 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_less_10_result = mysql_query($rs_operation_less_10_qry) or die("Error: " . mysql_error());
$rs_operation_less_10_row = mysql_fetch_assoc($rs_operation_less_10_result);
# Less 20
$rs_operation_less_20_qry = sprintf("SELECT SUM(2_Total) AS Overall_total FROM tvw_quantities_less WHERE Project_id='%s' AND Invoice_id=20 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_less_20_result = mysql_query($rs_operation_less_20_qry) or die("Error: " . mysql_error());
$rs_operation_less_20_row = mysql_fetch_assoc($rs_operation_less_20_result);
# Less 30
$rs_operation_less_30_qry = sprintf("SELECT SUM(3_Total) AS Overall_total FROM tvw_quantities_less WHERE Project_id='%s' AND Invoice_id=30 ORDER BY Project_id LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_operation_less_30_result = mysql_query($rs_operation_less_30_qry) or die("Error: " . mysql_error());
$rs_operation_less_30_row = mysql_fetch_assoc($rs_operation_less_30_result);

$bg_1 = ($rs_operation_calc_10_row['Total_hour_cost']+$rs_operation_more_60_row['Total_hour_cost']);
$bg_2 = ($rs_operation_calc_10_row['Total_material']+$rs_operation_calc_10_row['Total_physical']+$rs_operation_calc_10_row['Total_post']+$rs_operation_more_60_row['Total_material']+$rs_operation_more_60_row['Total_physical']+$rs_operation_more_60_row['Total_post']);
$bg_3 = ($rs_operation_calc_20_row['Total_hour_cost']+$rs_operation_calc_20_row['Total_material']+$rs_operation_calc_20_row['Total_physical']+$rs_operation_calc_20_row['Total_post']+$rs_operation_more_70_row['Total_hour_cost']+$rs_operation_more_70_row['Total_material']+$rs_operation_more_70_row['Total_physical']+$rs_operation_more_70_row['Total_post']);
$bg_4 = ($rs_operation_calc_30_row['Total_hour_cost']+$rs_operation_calc_30_row['Total_material']+$rs_operation_calc_30_row['Total_physical']+$rs_operation_calc_30_row['Total_post']+$rs_operation_more_80_row['Total_hour_cost']+$rs_operation_more_80_row['Total_material']+$rs_operation_more_80_row['Total_physical']+$rs_operation_more_80_row['Total_post']);
$bg_5 = $rs_operation_post_row['Overall_total'];
$bs_1 = ($rs_operation_hour_1_row['Hour_cost']+$rs_operation_hour_2_row['Hour_cost']);
$bs_2 = $rs_operation_invoice_1_row['Invoice_amount'];
$bs_3 = $rs_operation_invoice_2_row['Invoice_amount'];
$bs_4 = $rs_operation_invoice_3_row['Invoice_amount'];
$bs_5 = ($rs_operation_invoice_4_row['Invoice_amount']+$rs_operation_post_row['Total_hour_cost']);

$min_1 = $rs_operation_less_10_row['Total_hour_cost'];
$min_2 = ($rs_operation_less_10_row['Total_material']+$rs_operation_less_10_row['Total_physical']+$rs_operation_less_10_row['Total_post']);
$min_3 = $rs_operation_less_20_row['Overall_total'];
$min_4 = $rs_operation_less_30_row['Overall_total'];

$total_1 = (($bg_1-$bs_1)+$min_1);
$total_2 = (($bg_2-$bs_2)+$min_2);
$total_3 = (($bg_3-$bs_3)+$min_3);
$total_4 = (($bg_4-$bs_4)+$min_4);
$total_5 = ($bg_5-$bs_5);

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
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=130&r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Terug naar financieel" />
		</div>
		<span>Winst  / verlies berekening</span>
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
						<td width="427">Aanneming + Meerwerk op basis van regie</td>
						<td align="center">Facturabel</td>
						<td align="center">Besteed</td>
						<td align="center">Minderwerk</td>
						<td align="center">Winst / verlies</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td>Arbeidskosten</td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bg_1, 2, ',', '.'); ?></td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bs_1, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php echo number_format($min_1, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php if($total_1 < 0){ echo "<font style=\"color:#F00\">".number_format($total_1, 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format($total_1, 2, ',', '.')."</font>"; } ?></td>
					</tr>
					<tr>
						<td>Materiaal / materieel / totaalposten</td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bg_2, 2, ',', '.'); ?></td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bs_2, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php echo number_format($min_2, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php if($total_2 < 0){ echo "<font style=\"color:#F00\">".number_format($total_2, 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format($total_2, 2, ',', '.')."</font>"; } ?></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>Onderaanneming  + Meerwerk op basis van regie</td>
						<td align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td>Onderaanneming</td>
						<td class="tbl-subhead" width="160" align="right">&euro;&nbsp;<?php echo number_format($bg_3, 2, ',', '.'); ?></td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bs_3, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php echo number_format($min_3, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php if($total_3 < 0){ echo "<font style=\"color:#F00\">".number_format($total_3, 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format($total_3, 2, ',', '.')."</font>"; } ?></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<?php if(false){ ?>
					<tr class="tbl-head">
						<td>Derden</td>
						<td align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td>Derden</td>
						<td class="tbl-subhead" width="160" align="right">&euro;&nbsp;<?php echo number_format($bg_4, 2, ',', '.'); ?></td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bs_4, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php echo number_format($min_4, 2, ',', '.'); ?></td>
						<td width="160" align="right">&euro;&nbsp;<?php if($total_4 < 0){ echo "<font style=\"color:#F00\">".number_format($total_4, 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format($total_4, 2, ',', '.')."</font>"; } ?></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<?php } ?>
					<tr class="tbl-head">
						<td>Stelposten</td>
						<td align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td>Stelposten</td>
						<td class="tbl-subhead" width="160" align="right">&euro;&nbsp;<?php echo number_format($bg_5, 2, ',', '.'); ?></td>
						<td width="160" align="right" class="tbl-subhead">&euro;&nbsp;<?php echo number_format($bs_5, 2, ',', '.'); ?></td>
						<td width="160" align="right">&nbsp;</td>
						<td width="160" align="right">&euro;&nbsp;<?php if($total_5 < 0){ echo "<font style=\"color:#F00\">".number_format($total_5, 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format($total_5, 2, ',', '.')."</font>"; } ?></td>
					</tr>
					<tr class="tbl-even">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td>Resultaat</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-even">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Totaal gecalculeerd (Excl. BTW)</td>
						<td align="center" class="tbl-head">&euro;&nbsp;<?php echo number_format($bg_1+$bg_2+$bg_3+$bg_4+$bg_5, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Totaal besteed (Excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head">&euro;&nbsp;<?php echo number_format($bs_1+$bs_2+$bs_3+$bs_4+$bs_5, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td> Totaal minderwerk (Excl. BTW)</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head">&euro;&nbsp;<?php echo number_format($min_1+$min_2+$min_3+$min_4, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td> Resultaat winst / verlies (Excl. BTW)</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head">&euro;&nbsp;<?php if(($total_1+$total_2+$total_3+$total_4+$total_5) < 0){ echo "<font style=\"color:#F00\">".number_format(($total_1+$total_2+$total_3+$total_4+$total_5), 2, ',', '.')."</font>"; }else{ echo "<font style=\"color:#090\">".number_format(($total_1+$total_2+$total_3+$total_4+$total_5), 2, ',', '.')."</font>"; } ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>