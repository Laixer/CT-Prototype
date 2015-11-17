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

# Submited user data
$option_id = mysql_real_escape_string($_POST['slt_option']);
$chapter_id = mysql_real_escape_string($_POST['slt_chapter']);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

if($option_id){
	$ft_opt_qry = sprintf("AND o.Invoice_option_id='%s'", $option_id);
}

if($chapter_id){
	$ft_chap_qry = sprintf("AND c.Project_chapter_id='%s'", $chapter_id);
}

if(isset($_GET['del_id'])){
	$del_id = mysql_real_escape_string($_GET['del_id']);
	
	$rs_del_op_qry = sprintf("DELETE i.* FROM tbl_project_invoice AS i JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_invoice_id='%s' AND p.User_id='%s'", $del_id, $user_id);
	mysql_query($rs_del_op_qry) or die("Error: " . mysql_error());
	
	$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
	mysql_query($rs_update_module) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['slt_relation'])){
	$relation_id = mysql_real_escape_string($_POST['slt_relation']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$profit = mysql_real_escape_string($_POST['slt_profit']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$invoice_option_id = mysql_real_escape_string($_POST['slt_invoice_option']);
	$operation_id = mysql_real_escape_string($_POST['slt_work']);
	$description = mysql_real_escape_string($_POST['fld_description']);
	$amount = str_replace(',', '.', $amount);
	
	if(!$amount){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$error_message){
		# Check if operation is part of this project
		$rs_project_op_check_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $operation_id, $user_id);
		$rs_project_op_check_result = mysql_query($rs_project_op_check_qry) or die("Error: " . mysql_error());
		if(mysql_num_rows($rs_project_op_check_result)){
			$rs_add_invoice = sprintf("INSERT INTO tbl_project_invoice (Create_date, Project_id, Relation_id, Invoice_option_id, Operation_id, Tax_id, Amount, Profit, Description) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $rs_project_perm_check_row['Project_id'], $relation_id, $invoice_option_id, $operation_id, $tax_id, $amount, $profit, $description);
			mysql_query($rs_add_invoice) or die("Error: " . mysql_error());
		
			$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
			mysql_query($rs_update_module) or die("Error: " . mysql_error());
		}else{
			$error_message = "Deze werkzaamheid is geen onderdeel van dit project";
		}
	}
}

# All invoices query
$rs_invoices_qry = sprintf("SELECT i.Project_invoice_id, i.Amount, i.Profit, i.Description, r.Company_name, t.Tax, c.Chapter, w.Operation, o.Option, stf_total_profit(i.Amount, i.Profit) AS Total FROM tbl_project_invoice AS i JOIN tbl_relation AS r ON r.Relation_id=i.Relation_id JOIN tbl_invoice_option AS o ON o.Invoice_option_id=i.Invoice_option_id JOIN tbl_project_operation AS w ON w.Project_operation_id=i.Operation_id JOIN tbl_project_chapter AS c ON c.Project_chapter_id=w.Chapter_id JOIN tbl_tax AS t ON t.Tax_id=i.Tax_id  JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_id='%s' %s %s AND p.User_id='%s' ORDER BY Project_invoice_id DESC", $rs_project_perm_check_row['Project_id'], $ft_chap_qry, $ft_opt_qry, $user_id);
$rs_invoices_result = mysql_query($rs_invoices_qry) or die("Error: " . mysql_error());
$rs_invoices_num = mysql_num_rows($rs_invoices_result);

# Invoice totals
$rs_invoices_total_qry = sprintf("SELECT SUM(i.Amount) AS Total_amount, SUM(stf_total_profit(i.Amount, i.Profit)) AS Total_total FROM tbl_project_invoice AS i JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_id='%s' AND p.User_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_invoices_total_result = mysql_query($rs_invoices_total_qry) or die("Error: " . mysql_error());
$rs_invoices_total_row = mysql_fetch_assoc($rs_invoices_total_result);

# All invoice
$rs_invoice_result = mysql_query("SELECT * FROM tbl_invoice") or die("Error: " . mysql_error());
$rs_invoice2_result = mysql_query("SELECT * FROM tbl_invoice") or die("Error: " . mysql_error());

# All tax
$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());

# All relations for this user
$rs_project_relations_qry = sprintf("SELECT Relation_id, Company_name FROM tbl_relation WHERE User_id='%s' ORDER BY Timestamp_date", $user_id);
$rs_project_relations_result = mysql_query($rs_project_relations_qry) or die("Error: " . mysql_error());

# All chapters for this project
$rs_project_chap_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_chap_result = mysql_query($rs_project_chap_qry) or die("Error: " . mysql_error());
$rs_project_chap2_result = mysql_query($rs_project_chap_qry) or die("Error: " . mysql_error());

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
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/chapter-mgr/?r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>','Werkzaamheden','width=800,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Werkzaamheden toevoegen" />
		</div>
		<span>Inkoopfacturen</span>
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
						<td colspan="2">Factuur toevoegen</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="147">Bedrijf</td>
						<td width="170">Factuurbedrag (excl BTW)</td>
						<td width="57" align="center">Winst</td>
						<td width="59" align="center">BTW</td>
						<td width="177">Factuur behorende bij <!--<a class="tooltip" href="#"><img src="../../images/info_icon.png" width="18" height="18" /><span class="classic"><div class="tt-title"><?php //echo $rs_tooltip_row['Title']; ?></div><?php //echo $rs_tooltip_row['Message']; ?></span></a>--></td>
						<td width="210">Behorende bij werkzaamheden</td>
						<td width="176">Opmerking</td>
					</tr>
					<form action="" method="post" name="frm_invoice_add" id="frm_invoice_add">
						<tr class="tbl-even">
							<td><a href="#" onclick="document.frm_invoice_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Verwijderen" /></a></td>
							<td>
								<select name="slt_relation" id="slt_relation" style="width: 99%;">
								<?php
								while($rs_project_relations_row = mysql_fetch_assoc($rs_project_relations_result)){
									echo '<option value="' . $rs_project_relations_row['Relation_id'] . '">' . $rs_project_relations_row['Company_name'] . '</option>';
								}
								?>
								</select>
							</td>					
							<td><input style="width: 99%;" type="text" name="fld_amount" id="fld_amount" /></td>
							<td>
								<select style="width: 99%;" name="slt_profit" id="slt_profit">
								<?php for($i=0; $i<=100; $i++){ echo '<option value="'.$i.'">'.$i.'%</option>'; } ?>
								</select>
							</td>
							<td>
								<select name="slt_tax" id="slt_tax" style="width: 99%;">
								<?php
								while($rs_tax_row = mysql_fetch_assoc($rs_tax_result)){
									if($rs_tax_row['Tax_id'] == 40){
										echo '<option selected="selected" value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
									}else{
										echo '<option value="' . $rs_tax_row['Tax_id'] . '">' . $rs_tax_row['Tax'] . '%</option>';
									}
								}
								?>
								</select>
							</td>	
							<td>
								<select name="slt_invoice_option" id="slt_invoice_option" style="width: 99%;">
								<?php
								while($rs_invoice_row = mysql_fetch_assoc($rs_invoice_result)){
									$rs_invoice_option_qry = sprintf("SELECT * FROM tbl_invoice_option WHERE Invoice_id='%s'", $rs_invoice_row['Invoice_id']);
									$rs_invoice_option_result = mysql_query($rs_invoice_option_qry) or die("Error: " . mysql_error());
									echo '<optgroup label="' . $rs_invoice_row['Category'] . '">';
									while($rs_invoice_option_row = mysql_fetch_assoc($rs_invoice_option_result)){
										echo '<option value="' . $rs_invoice_option_row['Invoice_option_id'] . '">' . $rs_invoice_option_row['Option'] . '</option>';
									}
									echo '</optgroup>';
								}
								?>
								</select>
							</td>
							<td>
								<select name="slt_work" id="slt_work" style="width: 99%;">
								<?php
								while($rs_project_chap2_row = mysql_fetch_assoc($rs_project_chap2_result)){
									$rs_project_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' ORDER BY Priority ASC", $rs_project_chap2_row['Project_chapter_id']);
									$rs_project_op_result = mysql_query($rs_project_op_qry) or die("Error: " . mysql_error());
									echo '<optgroup label="'.$rs_project_chap2_row['Chapter'].'">';
									while($rs_project_op_row = mysql_fetch_assoc($rs_project_op_result)){
										echo '<option value="' . $rs_project_op_row['Project_operation_id'] . '">&raquo;&nbsp;' . $rs_project_op_row['Operation'] . '</option>';
									}
									echo '</optgroup>';
								}
								?>
								</select>
							</td>
							<td><input style="width: 99%;" type="text" name="fld_description" id="fld_description" /></td>
						</tr>
					</form>
				</table>
			</div>
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td colspan="2">Filter factuur</td>
						<td colspan="5">
							<form action="" method="post" name="frm_option" id="frm_option">
								<select onchange="document.frm_option.submit()" name="slt_option" id="slt_option">
									<option value="">Geen</option>
									<?php
									while($rs_invoice2_row = mysql_fetch_assoc($rs_invoice2_result)){
										$rs_invoice_option2_qry = sprintf("SELECT * FROM tbl_invoice_option WHERE Invoice_id='%s'", $rs_invoice2_row['Invoice_id']);
										$rs_invoice_option2_result = mysql_query($rs_invoice_option2_qry) or die("Error: " . mysql_error());
										echo '<optgroup label="' . $rs_invoice2_row['Category'] . '">';
										while($rs_invoice_option2_row = mysql_fetch_assoc($rs_invoice_option2_result)){
											if($option_id == $rs_invoice_option2_row['Invoice_option_id']){
												echo '<option selected="selected" value="' . $rs_invoice_option2_row['Invoice_option_id'] . '">' . $rs_invoice_option2_row['Option'] . '</option>';
											}else{
												echo '<option value="' . $rs_invoice_option2_row['Invoice_option_id'] . '">' . $rs_invoice_option2_row['Option'] . '</option>';
											}
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</form>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="2">Filter hoofdstuk</td>
						<td colspan="5">
							<form action="" method="post" name="frm_chapter" id="frm_chapter">
								<select onchange="document.frm_chapter.submit()" name="slt_chapter" id="slt_chapter">
									<option value="">Geen</option>
									<?php while($rs_project_chap_row = mysql_fetch_assoc($rs_project_chap_result)){
										if($chapter_id == $rs_project_chap_row['Project_chapter_id']){
											echo '<option selected="selected" value="'.$rs_project_chap_row['Project_chapter_id'].'">'.$rs_project_chap_row['Chapter'].'</option>';
										}else{
											echo '<option value="'.$rs_project_chap_row['Project_chapter_id'].'">'.$rs_project_chap_row['Chapter'].'</option>';
										}
									} ?>
								</select>
							</form>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="135">Bedrijf</td>
						<td width="44">Bedrag</td>
						<td width="56" align="center">Winst</td>
						<td width="55" align="left">Totaal</td>
						<td width="59" align="center">BTW</td>
						<td width="193">Factuur</td>
						<td width="121">Hoofdstuk</td>
						<td width="149">Werkzaamheden</td>
						<td width="176">Omschrijving</td>
					</tr>
					<?php $i=0; while($rs_invoices_row = mysql_fetch_assoc($rs_invoices_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><a href="?p_id=<?php echo $_GET['p_id']; ?>&r_id=<?php echo $_GET['r_id']; ?>&del_id=<?php echo $rs_invoices_row['Project_invoice_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						<td><?php echo $rs_invoices_row['Company_name']; ?></td>
						<td align="right">&euro;<?php echo number_format($rs_invoices_row['Amount'], 2, ',', '.'); ?></td>
						<td align="center"><?php echo $rs_invoices_row['Profit']; ?>%</td>
						<td align="right">&euro;<?php echo number_format($rs_invoices_row['Total'], 2, ',', '.'); ?></td>
						<td align="center"><?php echo $rs_invoices_row['Tax']; ?>%</td>
						<td><?php echo $rs_invoices_row['Option']; ?></td>
						<td><?php echo $rs_invoices_row['Chapter']; ?></td>
						<td><?php if($rs_invoices_row['Placeholder'] == 'Y'){ echo 'Alles onder hoofdstuk'; }else{ echo $rs_invoices_row['Operation']; } ?></td>
						<td><?php echo $rs_invoices_row['Description']; ?></td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td width="16">&nbsp;</td>
						<td width="135">Totaal</td>
						<td align="right">&euro;<?php echo number_format($rs_invoices_total_row['Total_amount'], 2, ',', '.'); ?></td>
						<td width="56">&nbsp;</td>
						<td width="55" align="right">&euro;<?php echo number_format($rs_invoices_total_row['Total_total'], 2, ',', '.'); ?></td>
						<td width="59">&nbsp;</td>
						<td width="193">&nbsp;</td>
						<td width="121">&nbsp;</td>
						<td width="149">&nbsp;</td>
						<td width="176">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="10" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>
<?php
mysql_free_result($rs_project_chap2_result);
mysql_free_result($rs_project_chap_result);
mysql_free_result($rs_project_relations_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_invoice2_result);
mysql_free_result($rs_invoice_result);
mysql_free_result($rs_invoices_total_result);
mysql_free_result($rs_invoices_result);
?>