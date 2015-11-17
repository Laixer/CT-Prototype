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

//print_r($rs_project_perm_check_row);

# All chapters for this projecy
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_work_inv1_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv2_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv3_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv4_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());

# Check for empty totals
# 10
$rs_project_empty_total_10_qry = sprintf("SELECT 1_Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=10 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_10_result = mysql_query($rs_project_empty_total_10_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_10_row = mysql_fetch_assoc($rs_project_empty_total_10_result);
# 20
$rs_project_empty_total_20_qry = sprintf("SELECT 2_Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=20 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_20_result = mysql_query($rs_project_empty_total_20_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_20_row = mysql_fetch_assoc($rs_project_empty_total_20_result);
# 40
$rs_project_empty_total_40_qry = sprintf("SELECT 4_Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=40 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_40_result = mysql_query($rs_project_empty_total_40_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_40_row = mysql_fetch_assoc($rs_project_empty_total_40_result);

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
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=132&r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Terug naar urenregistratie" />
		</div>
		<span>Uittrekstaat</span>
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
					<?php if($rs_project_empty_total_10_row['1_Total']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Aanneming</td>
						<td align="center">Gecalculeerde uren</td>
						<td align="center">Geregistreerde uren</td>
						<td align="center">Verschil</td>
					</tr>
					<?php
					while($rs_project_work_inv1_row = mysql_fetch_assoc($rs_project_work_inv1_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=10 AND 1_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
					
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv1_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
						$rs_operation_hours_qry = sprintf("SELECT SUM(Amount) AS total FROM tbl_project_calc_hour hr WHERE project_id=%d AND operation_id=%d AND More_work=1 GROUP BY operation_id", $rs_project_work_inv1_row['Project_id'], $rs_project_quant_operation_row['Operation_id']);
						$rs_operation_hours_result = mysql_query($rs_operation_hours_qry) or die("Error: " . mysql_error());
						$rs_operation_hours_row = mysql_fetch_assoc($rs_operation_hours_result);
						
						# sum
						$rs_calc_sum_qry = sprintf("SELECT cs.Project_calc_sum_id, cs.Unit, cs.Price, cs.Amount, cs.Tax_id, t.Tax FROM tbl_project_calc_sum AS cs JOIN tbl_project AS p ON p.Project_id=cs.Project_id JOIN tbl_tax AS t ON t.Tax_id=cs.Tax_id WHERE cs.Project_id='%s' AND cs.Invoice_id='10' AND cs.Operation_id='%s' LIMIT 1", $rs_project_work_inv1_row['Project_id'], $rs_project_quant_operation_row['Operation_id']);
						$rs_calc_sum_result = mysql_query($rs_calc_sum_qry) or die("Error: " . mysql_error());
						$rs_calc_sum_row = mysql_fetch_assoc($rs_calc_sum_result);

					if(!$rs_calc_sum_row){ ?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_operation_hours_row['total']){ echo number_format($rs_operation_hours_row['total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php $t = ($rs_project_quant_operation_row['Hour_amount']-$rs_operation_hours_row['total']); if($t > 0){ echo '<font color="#009900">'.number_format($t, 2, ',', '.').'</font>'; }else{ echo '<font color="#FF0000">'.number_format($t, 2, ',', '.').'</font>'; } ?></td>
					</tr>
					<?php }else{ ?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">NB</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_calc_sum_row['Amount']){ echo number_format($rs_calc_sum_row['Amount'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">-</td>
					</tr>
					<?php } } ?>
					<?php } } ?>
					<tr>
						<td colspan="5" align="left">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="2" align="left">Meerwerk</td>
						<td align="center">Gecalculeerde uren</td>
						<td align="center">Geregistreerde uren</td>
						<td align="center">Verschil</td>
					</tr>
					<?php
					while($rs_project_work_inv3_row = mysql_fetch_assoc($rs_project_work_inv3_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_more AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=60 AND 1_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv3_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv3_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
						$rs_operation_hours_qry = sprintf("SELECT SUM(Amount) AS total FROM tbl_project_calc_hour hr WHERE project_id=%d AND operation_id=%d AND More_work=2 GROUP BY operation_id", $rs_project_work_inv3_row['Project_id'], $rs_project_quant_operation_row['Operation_id']);
						$rs_operation_hours_result = mysql_query($rs_operation_hours_qry) or die("Error: " . mysql_error());
						$rs_operation_hours_row = mysql_fetch_assoc($rs_operation_hours_result);
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_operation_hours_row['total']){ echo number_format($rs_operation_hours_row['total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php $t = ($rs_project_quant_operation_row['Hour_amount']-$rs_operation_hours_row['total']); if($t > 0){ echo '<font color="#009900">'.number_format($t, 2, ',', '.').'</font>'; }else{ echo '<font color="#FF0000">'.number_format($t, 2, ',', '.').'</font>'; } ?></td>
					</tr>
					<?php } ?>
					<?php } } ?>
					<tr>
						<td colspan="5" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_empty_total_40_row['4_Total']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Stelposten</td>
						<td align="center">Gecalculeerde uren</td>
						<td align="center">Geregistreerde uren</td>
						<td align="center">Verschil</td>
					</tr>
					<?php
					while($rs_project_work_inv4_row = mysql_fetch_assoc($rs_project_work_inv4_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=40 AND 4_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv4_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);

						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv4_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
						$rs_operation_hours_qry = sprintf("SELECT SUM(Amount) AS total FROM tbl_project_calc_hour hr WHERE project_id=%d AND operation_id=%d AND More_work=3 GROUP BY operation_id", $rs_project_work_inv4_row['Project_id'], $rs_project_quant_operation_row['Operation_id']);
						$rs_operation_hours_result = mysql_query($rs_operation_hours_qry) or die("Error: " . mysql_error());
						$rs_operation_hours_row = mysql_fetch_assoc($rs_operation_hours_result);
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_operation_hours_row['total']){ echo number_format($rs_operation_hours_row['total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php $t = ($rs_project_quant_operation_row['Hour_amount']-$rs_operation_hours_row['total']); if($t > 0){ echo '<font color="#009900">'.number_format($t, 2, ',', '.').'</font>'; }else{ echo '<font color="#FF0000">'.number_format($t, 2, ',', '.').'</font>'; } ?></td>
					</tr>
					<?php } ?>
					<?php } } ?>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_work_inv4_result);
mysql_free_result($rs_project_work_inv3_result);
mysql_free_result($rs_project_work_inv2_result);
mysql_free_result($rs_project_work_inv1_result);
?>