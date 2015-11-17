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

# Check for empty totals
# 40
$rs_project_empty_total_40_qry = sprintf("SELECT Total FROM tvw_quantities_mod_7 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
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
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=134&amp;r_id=<?php echo $_GET['r_id']; ?>'" value="Terug naar stelposten stellen" />
		</div>
		<span>Uittrekstaat Stelposten stellen</span>
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
					<?php if($rs_project_empty_total_40_row['Total']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Stelposten</td>
						<td align="center"><div align="center">Arbeidsuren</div></td>
						<td><div align="center">Arbeidskosten</div></td>
						<td><div align="center">Materiaalkosten</div></td>
						<td><div align="center">Materieelkosten</div></td>
						<td><div align="center">Totaal (excl. BTW)</div></td>
						<td><div align="center">Vuistregel</div></td>
					</tr>
					<?php
					while($rs_project_work_inv1_row = mysql_fetch_assoc($rs_project_work_inv1_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_7 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(Material) AS Total_material, SUM(Physical) AS Total_physical, SUM(Total) AS Overall_total FROM tvw_quantities_mod_7 WHERE Chapter_id='%s' ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(Material) AS Total_material, SUM(Physical) AS Total_physical, SUM(Total) AS Overall_total FROM tvw_quantities_mod_7 WHERE Project_id='%s'ORDER BY Project_id LIMIT 1", $rs_project_work_inv1_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv1_row['Chapter']; ?></td>
						<td width="114"></td>
						<td width="140"></td>
						<td width="131"></td>
						<td width="108"></td>
						<td width="138"></td>
						<td width="124"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$rs_project_old_qry = sprintf("SELECT 4_total FROM tvw_quantities_mod_2 WHERE operation_id='%s' LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
						$rs_project_old_result = mysql_query($rs_project_old_qry) or die("Error: " . mysql_error());
						$rs_project_old_row = mysql_fetch_array($rs_project_old_result);
						$i++;
					?>
					<tr>
						<td width="40">&nbsp;</td>
						<td width="239" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_cost']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<?php
						$t1 = $rs_project_quant_operation_row['Total'] - $rs_project_old_row['0'];
						$t2 = (($t1 / $rs_project_old_row['0'])*100);
						if(($t2 < 10)&&($t2 > -10)){
							echo '<font color="#006600">'.number_format($t2, 2, ',', '.').'%</font>';
						}else{
							echo '<font color="#FF0000">'.number_format($t2, 2, ',', '.').'%</font>';
						}
						?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Stelposten</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_work_inv1_result);
?>