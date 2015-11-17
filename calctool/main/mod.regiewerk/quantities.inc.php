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

# All chapters for this projecy
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_work_inv1_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv2_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv3_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());

# Check for empty totals
$rs_project_empty_total_qry = sprintf("SELECT (IFNULL(SUM(Hour_amount), 0)+IFNULL(SUM(Total_1), 0)) AS Total_1, SUM(Total_2) AS Total_2, SUM(Total_3) AS Total_3 FROM tvw_quantities_mod_1 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_result = mysql_query($rs_project_empty_total_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_row = mysql_fetch_assoc($rs_project_empty_total_result);

# Super totals
$rs_project_total_qry = sprintf("SELECT SUM(Hours) AS Total_hours, SUM(Hour_amount) AS Total_hour_amount, (IFNULL(SUM(Material_1), 0)+IFNULL(SUM(Material_2), 0)+IFNULL(SUM(Material_3), 0)) AS Material_total, (IFNULL(SUM(Physical_1), 0)+IFNULL(SUM(Physical_2), 0)+IFNULL(SUM(Physical_3), 0)) AS Physical_total, (IFNULL(SUM(Employment_2), 0)+IFNULL(SUM(Employment_3), 0)) AS Employment_total, (IFNULL(SUM(Total_1), 0)+IFNULL(SUM(Total_2), 0)+IFNULL(SUM(Total_3), 0)+IFNULL(SUM(Hour_amount), 0)) AS Super_total FROM tvw_quantities_mod_1 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_total_result = mysql_query($rs_project_total_qry) or die("Error: " . mysql_error());
$rs_project_total_row = mysql_fetch_assoc($rs_project_total_result);

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
		<span>Uittrekstaat</span>
		<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
		<a class="tooltip" href="javascript:void(0)">
			<img src="../../images/info_icon.png" width="18" height="18" />
			<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
		</a>
		<?php } ?>
	</div>
	<div id="content-main">
		<div id="intern">
			<div id="table">
				<table width="100%" border="0">
					<?php if($rs_project_empty_total_row['Total_1']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Hoofdaanneming</td>
						<td align="center">Arbeidsuren</td>
						<td>Arbeidskosten</td>
						<td>Materiaalkosten</td>
						<td>Materieelkosten</td>
						<td>Totaal (excl. BTW)</td>
					</tr>
					<?php
					while($rs_project_work_inv1_row = mysql_fetch_assoc($rs_project_work_inv1_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_1 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND (Hour_amount IS NOT NULL OR Total_1 IS NOT NULL) ORDER BY o.Priority ASC", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hours) AS Total_hours, SUM(Hour_amount) AS Total_hour_amount, SUM(Material_1) AS Total_material, SUM(Physical_1) AS Total_physical, (IFNULL(SUM(Total_1), 0)+IFNULL(SUM(Hour_amount), 0)) AS Overall_total FROM tvw_quantities_mod_1 WHERE Chapter_id='%s' ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hours) AS Total_hours, SUM(Hour_amount) AS Total_hour_amount, SUM(Material_1) AS Total_material, SUM(Physical_1) AS Total_physical, (IFNULL(SUM(Total_1), 0)+IFNULL(SUM(Hour_amount), 0)) AS Overall_total FROM tvw_quantities_mod_1 WHERE Project_id='%s' ORDER BY Project_id LIMIT 1", $rs_project_work_inv1_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv1_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<td width="131"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hours']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hours']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_amount'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Material_1']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material_1'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Physical_1']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical_1'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Total_1']+$rs_project_quant_operation_row['Hour_amount']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total_1']+$rs_project_quant_operation_row['Hour_amount'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hours']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hours']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_amount'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Hoofdaanneming</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hours']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hours']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_amount'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_empty_total_row['Total_2']){ ?>
					<tr class="tbl-head">
						<td colspan="3" align="left">Onderaanneming</td>
						<td>Arbeidskosten</td>
						<td>Materiaalkosten</td>
						<td>Materieelkosten</td>
						<td>Totaal (excl. BTW)</td>
					</tr>
					<?php
					while($rs_project_work_inv2_row = mysql_fetch_assoc($rs_project_work_inv2_result)){
						# Operations for this chapter
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_1 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND Total_2 IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Material_2) AS Total_material, SUM(Physical_2) AS Total_physical, SUM(Employment_2) AS Total_employment, SUM(Total_2) AS Overall_total FROM tvw_quantities_mod_1 WHERE Chapter_id='%s' ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 2
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Material_2) AS Total_material, SUM(Physical_2) AS Total_physical, SUM(Employment_2) AS Total_employment, SUM(Total_2) AS Overall_total FROM tvw_quantities_mod_1 WHERE Project_id='%s' ORDER BY Project_id LIMIT 1", $rs_project_work_inv2_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="3"><?php echo $rs_project_work_inv2_row['Chapter'];?></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<td width="131"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td colspan="2" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Employment_2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Employment_2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Material_2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material_2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Physical_2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical_2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Total_2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total_2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="3"><b><i>Subtotaal</i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_employment']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_employment'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="3">Totaal Onderaanneming</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_employment']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_employment'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_empty_total_row['Total_3']){ ?>
					<tr class="tbl-head">
						<td colspan="3" align="left">Derden</td>
						<td>Arbeidskosten</td>
						<td>Materiaalkosten</td>
						<td>Materieelkosten</td>
						<td>Totaal (excl. BTW)</td>
					</tr>
					<?php
					while($rs_project_work_inv3_row = mysql_fetch_assoc($rs_project_work_inv3_result)){
						# Operations for this chapter
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_1 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND Total_3 IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv3_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Material_3) AS Total_material, SUM(Physical_3) AS Total_physical, SUM(Employment_3) AS Total_employment, SUM(Total_3) AS Overall_total FROM tvw_quantities_mod_1 WHERE Chapter_id='%s' ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv3_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 2
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Material_3) AS Total_material, SUM(Physical_3) AS Total_physical, SUM(Employment_3) AS Total_employment, SUM(Total_3) AS Overall_total FROM tvw_quantities_mod_1 WHERE Project_id='%s' ORDER BY Project_id LIMIT 1", $rs_project_work_inv3_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="3"><?php echo $rs_project_work_inv3_row['Chapter'];?></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<td width="131"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td colspan="2" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Employment_3']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Employment_3'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Material_3']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Material_3'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Physical_3']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Physical_3'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Total_3']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Total_3'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="3"><b><i>Subtotaal</i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_employment']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_employment'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="3">Totaal Derden</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_employment']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_employment'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="2"><p>Totaal Project<p></td>
						<td align="center"><p><?php if($rs_project_total_row['Total_hours']){ echo str_replace('.', ',', $rs_project_total_row['Total_hours']); }else{ echo "-"; } ?></p></td>
						<td align="right"><p><?php if($rs_project_total_row['Employment_total']+$rs_project_total_row['Total_hour_amount']){ echo '&euro;'.number_format(($rs_project_total_row['Employment_total']+$rs_project_total_row['Total_hour_amount']), 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<td align="right"><p><?php if($rs_project_total_row['Material_total']){ echo '&euro;'.number_format($rs_project_total_row['Material_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<td align="right"><p><?php if($rs_project_total_row['Physical_total']){ echo '&euro;'.number_format($rs_project_total_row['Physical_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<td align="right"><p><?php if($rs_project_total_row['Super_total']){ echo '&euro;'.number_format($rs_project_total_row['Super_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_work_inv3_result);
mysql_free_result($rs_project_work_inv2_result);
mysql_free_result($rs_project_work_inv1_result);
?>