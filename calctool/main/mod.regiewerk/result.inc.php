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
$rs_project_result_qry = sprintf("SELECT * FROM tvw_result_mod_1 WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

# All total notax
$rs_project_notax_qry = sprintf("SELECT SUM(stf_total_profit(Amount, Profit)) AS Total FROM tbl_project_invoice WHERE Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_notax_result = mysql_query($rs_project_notax_qry);
$rs_project_notax_row = mysql_fetch_assoc($rs_project_notax_result);

# All total tax
$rs_project_tax_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(Amount, Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_result = mysql_query($rs_project_tax_qry) or die("Error: " . mysql_error());
$rs_project_tax_row = mysql_fetch_assoc($rs_project_tax_result);

# All total tax only
$rs_project_tax_only_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(Amount, Profit), t.Tax)) AS Total_tax_only FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_only_result = mysql_query($rs_project_tax_only_qry) or die("Error: " . mysql_error());
$rs_project_tax_only_row = mysql_fetch_assoc($rs_project_tax_only_result);

# Cululatives 40 1
$rs_project_tax_40_1_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=40 AND (i.Invoice_option_id=10 OR i.Invoice_option_id=20) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_40_1_result = mysql_query($rs_project_tax_40_1_qry) or die("Error: " . mysql_error());
$rs_project_tax_40_1_row = mysql_fetch_assoc($rs_project_tax_40_1_result);

# Cululatives 20 1
$rs_project_tax_20_1_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=20 AND (i.Invoice_option_id=10 OR i.Invoice_option_id=20) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_20_1_result = mysql_query($rs_project_tax_20_1_qry) or die("Error: " . mysql_error());
$rs_project_tax_20_1_row = mysql_fetch_assoc($rs_project_tax_20_1_result);

# Cululatives 40 2
$rs_project_tax_40_2_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=40 AND (i.Invoice_option_id=40 OR i.Invoice_option_id=50 OR i.Invoice_option_id=60) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_40_2_result = mysql_query($rs_project_tax_40_2_qry) or die("Error: " . mysql_error());
$rs_project_tax_40_2_row = mysql_fetch_assoc($rs_project_tax_40_2_result);

# Cululatives 20 2
$rs_project_tax_20_2_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=20 AND (i.Invoice_option_id=40 OR i.Invoice_option_id=50 OR i.Invoice_option_id=60) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_20_2_result = mysql_query($rs_project_tax_20_2_qry) or die("Error: " . mysql_error());
$rs_project_tax_20_2_row = mysql_fetch_assoc($rs_project_tax_20_2_result);

# Cululatives 40 3
$rs_project_tax_40_3_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=40 AND (i.Invoice_option_id=80 OR i.Invoice_option_id=90 OR i.Invoice_option_id=100) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_40_3_result = mysql_query($rs_project_tax_40_3_qry) or die("Error: " . mysql_error());
$rs_project_tax_40_3_row = mysql_fetch_assoc($rs_project_tax_40_3_result);

# Cululatives 20 3
$rs_project_tax_20_3_qry = sprintf("SELECT SUM(stf_tax(stf_total_profit(i.Amount, i.Profit), t.Tax)) AS Total FROM tbl_project_invoice AS i JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id WHERE i.Project_id='%s' AND i.Tax_id=20 AND (i.Invoice_option_id=80 OR i.Invoice_option_id=90 OR i.Invoice_option_id=100) LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_tax_20_3_result = mysql_query($rs_project_tax_20_3_qry) or die("Error: " . mysql_error());
$rs_project_tax_20_3_row = mysql_fetch_assoc($rs_project_tax_20_3_result);

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
		<span>Eindresultaat</span>
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
					<tr class="tbl-head">
						<td>Hoofdaanneming</td>
						<td align="center">Manuren</td>
						<td>&nbsp;</td>
						<td align="left">Bedrag (excl. BTW)</td>
						<td>&nbsp;</td>
						<td align="center">BTW</td>
						<td>&nbsp;</td>
						<td align="left">BTW bedrag</td>
						<td>&nbsp;</td>
					</tr>
					<?php if($rs_project_result_row['Salary_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td width="89" align="center"><?php echo number_format($rs_project_result_row['Salary_total_40'], 2, ',', '.'); ?></td>
						<td width="20">&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td width="126" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_notax_20']){ ?>
					<tr>
						<td width="387"><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_total_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Salary_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_1){ echo "Loonkosten"; $head_sal_1=1; } ?></td>
						<td align="center"><?php echo number_format($rs_project_result_row['Salary_total_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td class="tbl-subhead" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Salary_10'], 2, ',', '.'); ?></td>
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
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_1_10'], 2, ',', '.'); ?></td>
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
					<?php if($rs_project_result_row['Physical_1_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_1){ echo "Materieelkosten"; $head_phys_1=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="127" align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_1_10'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">0%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td><b><i>Totaal Hoofdaanneming</i></b></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_notax_1']+$rs_project_result_row['Salary_notax_40']+$rs_project_result_row['Salary_notax_20']+$rs_project_result_row['Salary_10']){ echo '&euro;'.number_format(($rs_project_result_row['Total_notax_1']+$rs_project_result_row['Salary_notax_40']+$rs_project_result_row['Salary_notax_20']+$rs_project_result_row['Salary_10']), 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_tax_1']+$rs_project_result_row['Salary_tax_40']+$rs_project_result_row['Salary_tax_20']){ echo '&euro;'.number_format(($rs_project_result_row['Total_tax_1']+$rs_project_result_row['Salary_tax_40']+$rs_project_result_row['Salary_tax_20']), 2, ',', '.'); }else{ echo "-"; } ?></td>
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
					<?php if($rs_project_result_row['Employment_2_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_2_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_2_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Employment_2_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_2_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_2_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Employment_2_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_2){ echo "Loonkosten"; $head_sal_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_2_10'], 2, ',', '.'); ?></td>
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
					<?php if($rs_project_result_row['Material_2_10']){ ?>
					<tr>
						<td><?php if(!$head_mat_2){ echo "Materiaalkosten"; $head_mat_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_2_10'], 2, ',', '.'); ?></td>
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
					<?php if($rs_project_result_row['Physical_2_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_2){ echo "Materieelkosten"; $head_phys_2=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_2_10'], 2, ',', '.'); ?></td>
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
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_notax_2']){ echo '&euro;'.number_format($rs_project_result_row['Total_notax_2'], 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_tax_2']){ echo '&euro;'.number_format($rs_project_result_row['Total_tax_2'], 2, ',', '.'); }else{ echo "-"; } ?></td>
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
					<?php if($rs_project_result_row['Employment_3_notax_40']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td width="89">&nbsp;</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="127" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_3_notax_40'], 2, ',', '.'); ?></td>
						<td width="81">&nbsp;</td>
						<td width="117" align="center">21%</td>
						<td width="20">&nbsp;</td>
						<td class="tbl-subhead" width="126" align="right"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_3_tax_40'], 2, ',', '.'); ?></td>
						<td width="89">&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Employment_3_notax_20']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_3_notax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td align="center">6%</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_3_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_result_row['Employment_3_10']){ ?>
					<tr>
						<td><?php if(!$head_sal_3){ echo "Loonkosten"; $head_sal_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Employment_3_10'], 2, ',', '.'); ?></td>
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
					<?php if($rs_project_result_row['Material_3_10']){ ?>
					<tr>
						<td><?php if(!$head_mat_3){ echo "Materiaalkosten"; $head_mat_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Material_3_10'], 2, ',', '.'); ?></td>
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
					<?php if($rs_project_result_row['Physical_3_10']){ ?>
					<tr>
						<td><?php if(!$head_phys_3){ echo "Materieelkosten"; $head_phys_3=1; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_result_row['Physical_3_10'], 2, ',', '.'); ?></td>
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
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_notax_3']){ echo '&euro;'.number_format($rs_project_result_row['Total_notax_3'], 2, ',', '.'); }else{ echo "-"; } ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php if($rs_project_result_row['Total_tax_3']){ echo '&euro;'.number_format($rs_project_result_row['Total_tax_3'], 2, ',', '.'); }else{ echo "-"; } ?></td>
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
					<tr class="tbl-head">
						<td>Cumulatieven</td>
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
						<td>Calculatief te factureren excl. BTW</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" class="tbl-head"><?php echo '&euro;'.number_format(($rs_project_notax_row['Total']+$rs_project_result_row['Salary_notax_40']+$rs_project_result_row['Salary_notax_20']+$rs_project_result_row['Salary_10']), 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php if($rs_project_tax_40_1_row['Total']+$rs_project_result_row['Salary_tax_40']){ ?>
					<tr>
						<td>BTW bedrag Hoofdaanneming belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_40_1_row['Total']+$rs_project_result_row['Salary_tax_40'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_tax_20_1_row['Total']+$rs_project_result_row['Salary_tax_20']){ ?>
					<tr>
						<td>BTW bedrag Hoofdaanneming belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_20_1_row['Total']+$rs_project_result_row['Salary_tax_20'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_tax_40_2_row['Total']){ ?>
					<tr>
						<td>BTW bedrag Onderaanneming belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_40_2_row['Total'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_tax_20_2_row['Total']){ ?>
					<tr>
						<td>BTW bedrag Onderaanneming belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_20_2_row['Total'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_tax_40_3_row['Total']){ ?>
					<tr>
						<td>BTW bedrag Derden belast met 21%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_40_3_row['Total'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<?php if($rs_project_tax_20_3_row['Total']){ ?>
					<tr>
						<td>BTW bedrag Derden belast met 6%</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right" class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_tax_20_3_row['Total'], 2, ',', '.'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td>Te factureren BTW bedrag</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="tbl-head" align="center"><?php echo '&euro;'.number_format($rs_project_tax_only_row['Total_tax_only']+$rs_project_result_row['Salary_tax_40']+$rs_project_result_row['Salary_tax_20'], 2, ',', '.'); ?></td>
					</tr>
					<tr class="tbl-head">
						<td>Calculatief te factureren incl. BTW</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center"><?php echo '&euro;'.number_format($rs_project_notax_row['Total']+$rs_project_result_row['Salary_notax_40']+$rs_project_result_row['Salary_notax_20']+$rs_project_tax_row['Total']+$rs_project_result_row['Salary_tax_40']+$rs_project_result_row['Salary_tax_20'], 2, ',', '.'); ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_tax_20_3_result);
mysql_free_result($rs_project_tax_40_3_result);
mysql_free_result($rs_project_tax_20_2_result);
mysql_free_result($rs_project_tax_40_2_result);
mysql_free_result($rs_project_tax_20_1_result);
mysql_free_result($rs_project_tax_40_1_result);
mysql_free_result($rs_project_tax_only_result);
mysql_free_result($rs_project_tax_result);
mysql_free_result($rs_project_notax_result);
mysql_free_result($rs_project_result_result);
mysql_free_result($rs_project_perm_check_result);
?>