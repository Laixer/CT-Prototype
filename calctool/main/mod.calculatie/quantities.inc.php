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
# 30
$rs_project_empty_total_30_qry = sprintf("SELECT 3_Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=30 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_30_result = mysql_query($rs_project_empty_total_30_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_30_row = mysql_fetch_assoc($rs_project_empty_total_30_result);
# 40
$rs_project_empty_total_40_qry = sprintf("SELECT 4_Total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=40 LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_empty_total_40_result = mysql_query($rs_project_empty_total_40_qry) or die("Error: " . mysql_error());
$rs_project_empty_total_40_row = mysql_fetch_assoc($rs_project_empty_total_40_result);

# Super totals
$rs_project_total_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(Hour_cost2) AS Total_hour_cost2, (IFNULL(SUM(1_Material),0)+IFNULL(SUM(2_Material),0)+IFNULL(SUM(3_Material),0)+IFNULL(SUM(4_Material),0)) AS Total_material, (IFNULL(SUM(1_Physical),0)+IFNULL(SUM(2_Physical),0)+IFNULL(SUM(3_Physical),0)+IFNULL(SUM(4_Physical),0)) AS Total_physical, (IFNULL(SUM(1_Post),0)+IFNULL(SUM(2_Post),0)+IFNULL(SUM(3_Post),0)+IFNULL(SUM(4_Post),0)) AS Total_post, (IFNULL(SUM(1_Total),0)+IFNULL(SUM(2_Total),0)+IFNULL(SUM(3_Total),0)+IFNULL(SUM(4_Total),0)) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_total_row = mysql_fetch_assoc(mysql_query($rs_project_total_qry));

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
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=112&amp;r_id=<?php echo $_GET['r_id']; ?>'" value="Aanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=113&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Onderaanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=115&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Stelposten" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=116&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Uittrekstaat" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=117&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Eindresultaat" />
		</div>
		<span>Uittrekstaat Aanneming</span>
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
						<td align="center"><div align="center">Arbeidsuren</div></td>
						<td><div align="center">Arbeidskosten</div></td>
						<td><div align="center">Materiaalkosten</div></td>
						<td><div align="center">Materieelkosten</div></td>
						<!--<td><div align="center">Totaalpost</div></td>-->
						<td><div align="center">Totaal (excl. BTW)</div></td>
					</tr>
					<?php
					while($rs_project_work_inv1_row = mysql_fetch_assoc($rs_project_work_inv1_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=10 AND 1_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=10 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv1_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=10 ORDER BY Project_id LIMIT 1", $rs_project_work_inv1_row['Project_id']);
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
						<td width="229"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_cost']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['1_Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['1_Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['1_Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['1_Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['1_Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['1_Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Aanneming</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ $material_total_1 = $rs_operation_subtotal_row['Total_material']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ $physical_total_1 = $rs_operation_subtotal_row['Total_physical']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ $stotal_1 = $rs_operation_subtotal_row['Overall_total']; echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_empty_total_20_row['2_Total']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Onderaanneming</td>
						<td align="center"><div align="center">Arbeidsuren</div></td>
						<td><div align="center">Arbeidskosten</div></td>
						<td><div align="center">Materiaalkosten</div></td>
						<td><div align="center">Materieelkosten</div></td>
						<!--<td><div align="center">Totaalpost</div></td>-->
						<td><div align="center">Totaal (excl. BTW)</div></td>
					</tr>
					<?php
					while($rs_project_work_inv2_row = mysql_fetch_assoc($rs_project_work_inv2_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=20 AND 2_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Post) AS Total_post, SUM(2_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=20 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv2_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Post) AS Total_post, SUM(2_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=20 ORDER BY Project_id LIMIT 1", $rs_project_work_inv2_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv2_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<!--<td width="98"></td>-->
						<td width="229"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_cost2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['2_Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['2_Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['2_Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['2_Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['2_Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['2_Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Onderaanneming</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ $material_total_2 = $rs_operation_subtotal_row['Total_material']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ $physical_total_2 = $rs_operation_subtotal_row['Total_physical']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ $stotal_2 = $rs_operation_subtotal_row['Overall_total']; echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if(false){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Derden</td>
						<td align="center"><div align="center">Arbeidsuren</div></td>
						<td><div align="center">Arbeidskosten</div></td>
						<td><div align="center">Materiaalkosten</div></td>
						<td><div align="center">Materieelkosten</div></td>
						<!--<td><div align="center">Totaalpost</div></td>-->
						<td><div align="center">Totaal (excl. BTW)</div></td>
					</tr>
					<?php
					while($rs_project_work_inv3_row = mysql_fetch_assoc($rs_project_work_inv3_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=30 AND 3_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv3_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(3_Material) AS Total_material, SUM(3_Physical) AS Total_physical, SUM(3_Post) AS Total_post, SUM(3_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=30 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv3_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(3_Material) AS Total_material, SUM(3_Physical) AS Total_physical, SUM(3_Post) AS Total_post, SUM(3_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=30 ORDER BY Project_id LIMIT 1", $rs_project_work_inv3_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv3_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<!--<td width="98"></td>-->
						<td width="229"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_cost2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['3_Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['3_Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['3_Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['3_Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['3_Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['3_Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Derden</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ $material_total_3 = $rs_operation_subtotal_row['Total_material']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ $physical_total_3 = $rs_operation_subtotal_row['Total_physical']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ $stotal_3 = $rs_operation_subtotal_row['Overall_total']; echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_empty_total_40_row['4_Total']){ ?>
					<tr class="tbl-head">
						<td colspan="2" align="left">Stelposten</td>
						<td align="center"><div align="center">Arbeidsuren</div></td>
						<td><div align="center">Arbeidskosten</div></td>
						<td><div align="center">Materiaalkosten</div></td>
						<td><div align="center">Materieelkosten</div></td>
						<!--<td><div align="center">Totaalpost</div></td>-->
						<td><div align="center">Totaal (excl. BTW)</div></td>
					</tr>
					<?php
					while($rs_project_work_inv4_row = mysql_fetch_assoc($rs_project_work_inv4_result)){
						$rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND quant.Invoice_id=40 AND 4_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv4_row['Project_chapter_id']);
						$rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
						$rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
						# Chapter total
						$rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(4_Material) AS Total_material, SUM(4_Physical) AS Total_physical, SUM(4_Post) AS Total_post, SUM(4_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=40 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv4_row['Project_chapter_id']);
						$rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
						$rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
						# Sub total 1
						$rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(4_Material) AS Total_material, SUM(4_Physical) AS Total_physical, SUM(4_Post) AS Total_post, SUM(4_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=40 ORDER BY Project_id LIMIT 1", $rs_project_work_inv4_row['Project_id']);
						$rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
						$rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
						
						if($rs_project_quant_operation_num){
					?>
					<tr class="tbl-subhead">
						<td colspan="2"><?php echo $rs_project_work_inv4_row['Chapter']; ?></td>
						<td width="115"></td>
						<td width="172"></td>
						<td width="136"></td>
						<td width="98"></td>
						<td width="229"></td>
					</tr>
					<?php $i=0;
					while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
						$i++;
					?>
					<tr>
						<td width="47">&nbsp;</td>
						<td width="300" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
						<td align="center" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_amount']){ echo str_replace('.', ',', $rs_project_quant_operation_row['Hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['Hour_cost2']){ echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['4_Material']){ echo '&euro;'.number_format($rs_project_quant_operation_row['4_Material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['4_Physical']){ echo '&euro;'.number_format($rs_project_quant_operation_row['4_Physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>"><?php if($rs_project_quant_operation_row['4_Total']){ echo '&euro;'.number_format($rs_project_quant_operation_row['4_Total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b><i>Subtotaal</i></b></td>
						<td align="center"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_chaptotal_row['Total_hour_amount']); }else{ echo "-"; } ?></i></b></td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_material']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Total_physical']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
						<td align="right"><b><i><?php if($rs_operation_chaptotal_row['Overall_total']){ echo '&euro;'.number_format($rs_operation_chaptotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?></i></b>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr class="tbl-head">
						<td colspan="2">Totaal Stelposten</td>
						<td align="center"><?php if($rs_operation_subtotal_row['Total_hour_amount']){ echo str_replace('.', ',', $rs_operation_subtotal_row['Total_hour_amount']); }else{ echo "-"; } ?></td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_hour_cost']){ echo '&euro;'.number_format($rs_operation_subtotal_row['Total_hour_cost'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_material']){ $material_total_4 = $rs_operation_subtotal_row['Total_material']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_material'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Total_physical']){ $physical_total_4 = $rs_operation_subtotal_row['Total_physical']; echo '&euro;'.number_format($rs_operation_subtotal_row['Total_physical'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
						<td align="right"><?php if($rs_operation_subtotal_row['Overall_total']){ $stotal_4 = $rs_operation_subtotal_row['Overall_total']; echo '&euro;'.number_format($rs_operation_subtotal_row['Overall_total'], 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="7" align="left">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="2"><p>Totaal Project<p></td>
						<td align="center"><p><?php if($rs_project_total_row['Total_hour_amount']){ echo number_format($rs_project_total_row['Total_hour_amount'], 2, ',', '.'); }else{ echo "-"; } ?></p></td>
						<td align="right"><p><?php if($rs_project_total_row['Total_hour_cost']+$rs_project_total_row['Total_hour_cost2']){ echo '&euro;'.number_format(($rs_project_total_row['Total_hour_cost']+$rs_project_total_row['Total_hour_cost2']), 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<td align="right"><p><?php if($material_total_1+$material_total_2+$material_total_3+$material_total_4){ echo '&euro;'.number_format($material_total_1+$material_total_2+$material_total_3+$material_total_4, 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<td align="right"><p><?php if($physical_total_1+$physical_total_2+$physical_total_3+$physical_total_4){ echo '&euro;'.number_format($physical_total_1+$physical_total_2+$physical_total_3+$physical_total_4, 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
						<!--<td align="right"><p><?php //if($post_total_1+$post_total_2+$post_total_3+$post_total_4){ echo '&euro;'.number_format($post_total_1+$post_total_2+$post_total_3+$post_total_4, 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>-->
						<td align="right"><p><?php if($stotal_1+$stotal_2+$stotal_3+$stotal_4){ echo '&euro;'.number_format($stotal_1+$stotal_2+$stotal_3+$stotal_4, 2, ',', '.'); }else{ echo "-"; } ?>&nbsp;</p></td>
					</tr>
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