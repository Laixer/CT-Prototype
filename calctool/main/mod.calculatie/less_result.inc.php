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
$rs_project_result_qry = sprintf("SELECT * FROM tvw_result_less WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
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
<?php if(!$hide_page){ ?>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=139&amp;r_id=<?php echo $_GET['r_id']; ?>'" value="Aanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=140&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Onderaanneming" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=143&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Uittrekstaat" />
			<input style="height: 24px; background: #FFF url('../images/next.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.location='?p_id=145&r_id=<?php echo $_GET['r_id']; ?>'" type="button" value="Eindresultaat" />
		</div>
		<span>Eindresultaat Minderwerk</span>
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
						<td align="center">Manuren</td>
						<td>&nbsp;</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">BTW</td>
						<td>&nbsp;</td>
						<td align="left">BTW bedrag</td>
						<td>&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Salary_amount_1_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td width="89" align="center"><?php echo number_format($rs_project_result_row['Salary_amount_1_40'], 2, ',', '.'); ?></td>
						<td width="20">&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_1_20']){ ?>
					<tr>
						<td width="387"><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_1_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_1_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_1_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_1_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Material_1_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_mat_1){ echo "Materiaalkosten"; $head_mat_1=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_1_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_mat_1){ echo "Materiaalkosten"; $head_mat_1=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_1_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_mat_1){ echo "Materiaalkosten"; $head_mat_1=1; } ?></td
						><td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Physical_1_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_phys_1){ echo "Materieelkosten"; $head_phys_1=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_1_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_phys_1){ echo "Materieelkosten"; $head_phys_1=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_notax_20'], 2, ',', '.'); ?></td>
						<td align="right">&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_tax_20'], 2, ',', '.'); ?></td>
						<td align="right">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_1_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_1){ echo "Materieelkosten"; $head_phys_1=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td><b><i>Totaal Aanneming</i></b></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_1_notax = $rs_project_result_row['Salary_1_notax_40']+$rs_project_result_row['Salary_1_notax_20']+$rs_project_result_row['Salary_1_notax_10']+$rs_project_result_row['Material_1_notax_40']+$rs_project_result_row['Material_1_notax_20']+$rs_project_result_row['Material_1_notax_10']+$rs_project_result_row['Physical_1_notax_40']+$rs_project_result_row['Physical_1_notax_20']+$rs_project_result_row['Physical_1_notax_10']+$rs_project_result_row['Sum_1_notax_40']+$rs_project_result_row['Sum_1_notax_20']+$rs_project_result_row['Sum_1_notax_10']){ echo '&euro;'.number_format($total_1_notax, 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_1_tax = $rs_project_result_row['Salary_1_tax_40']+$rs_project_result_row['Salary_1_tax_20']+$rs_project_result_row['Material_1_tax_40']+$rs_project_result_row['Material_1_tax_20']+$rs_project_result_row['Physical_1_tax_40']+$rs_project_result_row['Physical_1_tax_20']+$rs_project_result_row['Sum_1_tax_40']+$rs_project_result_row['Sum_1_tax_20']){ echo '&euro;'.number_format($total_1_tax, 2, ',', '.'); }else{ echo "-"; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">BTW</td>
						<td>&nbsp;</td>
						<td align="left">BTW bedrag</td>
						<td>&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Salary_amount_2_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td width="89" align="center"><?php echo number_format($rs_project_result_row['Salary_amount_2_40'], 2, ',', '.'); ?></td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_2_20']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_2_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_2_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_2_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_2_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Material_2_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_mat_2){ echo "Materiaalkosten"; $head_mat_2=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_2_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_mat_2){ echo "Materiaalkosten"; $head_mat_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_2_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_mat_2){ echo "Materiaalkosten"; $head_mat_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Physical_2_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_phys_2){ echo "Materieelkosten"; $head_phys_2=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_2_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_phys_2){ echo "Materieelkosten"; $head_phys_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_2_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_2){ echo "Materieelkosten"; $head_phys_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td><b><i>Totaal Onderaanneming</i></b></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_2_notax = $rs_project_result_row['Salary_2_notax_40']+$rs_project_result_row['Salary_2_notax_20']+$rs_project_result_row['Salary_2_notax_10']+$rs_project_result_row['Material_2_notax_40']+$rs_project_result_row['Material_2_notax_20']+$rs_project_result_row['Material_2_notax_10']+$rs_project_result_row['Physical_2_notax_40']+$rs_project_result_row['Physical_2_notax_20']+$rs_project_result_row['Physical_2_notax_10']+$rs_project_result_row['Sum_2_notax_40']+$rs_project_result_row['Sum_2_notax_20']+$rs_project_result_row['Sum_2_notax_10']){ echo '&euro;'.number_format($total_2_notax, 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_2_tax = $rs_project_result_row['Salary_2_tax_40']+$rs_project_result_row['Salary_2_tax_20']+$rs_project_result_row['Material_2_tax_40']+$rs_project_result_row['Material_2_tax_20']+$rs_project_result_row['Physical_2_tax_40']+$rs_project_result_row['Physical_2_tax_20']+$rs_project_result_row['Sum_2_tax_40']+$rs_project_result_row['Sum_2_tax_20']){ echo '&euro;'.number_format($total_2_tax, 2, ',', '.'); }else{ echo "-"; } ?></td>
					</tr>
					<tr class="tbl-even">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php if(false){ ?>
					<tr class="tbl-head">
						<td>Derden</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">BTW</td>
						<td>&nbsp;</td>
						<td align="left">BTW bedrag</td>
						<td>&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Salary_amount_3_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td width="89" align="center"><?php echo number_format($rs_project_result_row['Salary_amount_3_40'], 2, ',', '.'); ?></td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_3_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_3_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_3_20']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_3_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_3_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_3_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_amount_3_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_amount_3_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_3_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Material_3_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_mat_3){ echo "Materiaalkosten"; $head_mat_3=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_3_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_mat_3){ echo "Materiaalkosten"; $head_mat_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Material_3_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_mat_3){ echo "Materiaalkosten"; $head_mat_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Physical_3_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_phys_3){ echo "Materieelkosten"; $head_phys_3=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_3_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_phys_3){ echo "Materieelkosten"; $head_phys_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Physical_3_notax_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_3){ echo "Materieelkosten"; $head_phys_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_notax_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td><b><i>Totaal Derden</i></b></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_3_notax = $rs_project_result_row['Salary_3_notax_40']+$rs_project_result_row['Salary_3_notax_20']+$rs_project_result_row['Salary_3_notax_10']+$rs_project_result_row['Material_3_notax_40']+$rs_project_result_row['Material_3_notax_20']+$rs_project_result_row['Material_3_notax_10']+$rs_project_result_row['Physical_3_notax_40']+$rs_project_result_row['Physical_3_notax_20']+$rs_project_result_row['Physical_3_notax_10']+$rs_project_result_row['Sum_3_notax_40']+$rs_project_result_row['Sum_3_notax_20']+$rs_project_result_row['Sum_3_notax_10']){ echo '&euro;'.number_format($total_3_notax, 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($total_3_tax = $rs_project_result_row['Salary_3_tax_40']+$rs_project_result_row['Salary_3_tax_20']+$rs_project_result_row['Material_3_tax_40']+$rs_project_result_row['Material_3_tax_20']+$rs_project_result_row['Physical_3_tax_40']+$rs_project_result_row['Physical_3_tax_20']+$rs_project_result_row['Sum_3_tax_40']+$rs_project_result_row['Sum_3_tax_20']){ echo '&euro;'.number_format($total_3_tax, 2, ',', '.'); }else{ echo "-"; } ?></td>
					</tr>
					<tr class="tbl-even">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr class="tbl-head">
						<td>Cumulatieven minderwerk</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Calculatief te factureren (excl. BTW)</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php echo '&euro;'.number_format(($total_1_notax+$total_2_notax+$total_3_notax+$total_4_notax), 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<!---------------------------------------
					-----------------------------------------
					----------------------------------------->
					<?php if($tax_total_1 = $rs_project_result_row['Salary_1_tax_40']+$rs_project_result_row['Material_1_tax_40']+$rs_project_result_row['Physical_1_tax_40']+$rs_project_result_row['Sum_1_tax_40']){ ?>
					<tr>
						<td>BTW bedrag Aanneming belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_1, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($tax_total_2 = $rs_project_result_row['Salary_1_tax_20']+$rs_project_result_row['Material_1_tax_20']+$rs_project_result_row['Physical_1_tax_20']+$rs_project_result_row['Sum_1_tax_20']){ ?>
					<tr>
						<td>BTW bedrag Aanneming belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_2, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($tax_total_3 = $rs_project_result_row['Salary_2_tax_40']+$rs_project_result_row['Material_2_tax_40']+$rs_project_result_row['Physical_2_tax_40']+$rs_project_result_row['Sum_2_tax_40']){ ?>
					<tr>
						<td>BTW bedrag Onderaanneming belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_3, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($tax_total_4 = $rs_project_result_row['Salary_2_tax_20']+$rs_project_result_row['Material_2_tax_20']+$rs_project_result_row['Physical_2_tax_20']+$rs_project_result_row['Sum_2_tax_20']){ ?>
					<tr>
						<td>BTW bedrag Onderaanneming belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_4, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if(false){ if($tax_total_5 = $rs_project_result_row['Salary_3_tax_40']+$rs_project_result_row['Material_3_tax_40']+$rs_project_result_row['Physical_3_tax_40']+$rs_project_result_row['Sum_3_tax_40']){ ?>
					<tr>
						<td>BTW bedrag Derden belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_5, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($tax_total_6 = $rs_project_result_row['Salary_3_tax_20']+$rs_project_result_row['Material_3_tax_20']+$rs_project_result_row['Physical_3_tax_20']+$rs_project_result_row['Sum_3_tax_20']){ ?>
					<tr>
						<td>BTW bedrag Derden belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($tax_total_6, 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } } ?>
					<tr>
						<td>Te factureren BTW bedrag</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="tbl-head" align="center"><?php echo '&euro;'.number_format(($tax_total_1+$tax_total_2+$tax_total_3+$tax_total_4+$tax_total_5+$tax_total_6+$tax_total_7+$tax_total_8), 2, ',', '.'); ?></td>
					</tr>
					<tr class="tbl-head">
						<td>Calculatief te factureren (incl. BTW)</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center"><?php echo '&euro;'.number_format((($total_1_notax+$total_2_notax+$total_3_notax+$total_4_notax+$tax_total_1+$tax_total_2+$tax_total_3+$tax_total_4+$tax_total_5+$tax_total_6+$tax_total_7+$tax_total_8)), 2, ',', '.'); ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_result_result);
mysql_free_result($rs_project_perm_check_result);
?>