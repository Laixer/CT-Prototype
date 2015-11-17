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

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

if(isset($_GET['del_id'])){
	$del_id = mysql_real_escape_string($_GET['del_id']);
	
	$rs_del_op_qry = sprintf("DELETE i.* FROM tbl_project_calc_invoice AS i JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_invoice_id='%s' AND p.User_id='%s'", $del_id, $user_id);
	mysql_query($rs_del_op_qry) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['slt_relation'])){
	$relation_id = mysql_real_escape_string($_POST['slt_relation']);
	$amount = mysql_real_escape_string($_POST['fld_amount']);
	$option_id = mysql_real_escape_string($_POST['slt_option']);
	$description = mysql_real_escape_string($_POST['fld_description']);
	$amount = str_replace(',', '.', $amount);
	
	if(!$amount){
		$error_message = "Vul een factuurbedrag in";
	}
	
	if(!$error_message){
		$rs_add_invoice = sprintf("INSERT INTO tbl_project_calc_invoice (Create_date, Project_id, Relation_id, Invoice_option, Amount, Description) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s')", $rs_project_perm_check_row['Project_id'], $relation_id, $option_id, $amount, $description);
		mysql_query($rs_add_invoice) or die("Error: " . mysql_error());
	}
}

# All invoices query
$rs_invoices_qry = sprintf("SELECT i.*, r.Company_name FROM tbl_project_calc_invoice AS i JOIN tbl_relation AS r ON r.Relation_id=i.Relation_id JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_id=%d AND p.User_id=%d ORDER BY Project_invoice_id DESC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_invoices_result = mysql_query($rs_invoices_qry) or die("Error: " . mysql_error());
$rs_invoices_num = mysql_num_rows($rs_invoices_result);

# Invoice totals
$rs_invoices_total_qry = sprintf("SELECT SUM(i.Amount) AS Total_amount FROM tbl_project_calc_invoice AS i JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_id='%s' AND p.User_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_invoices_total_result = mysql_query($rs_invoices_total_qry) or die("Error: " . mysql_error());
$rs_invoices_total_row = mysql_fetch_assoc($rs_invoices_total_result);

# All invoice
$rs_invoice_result = mysql_query("SELECT * FROM tbl_invoice") or die("Error: " . mysql_error());
$rs_invoice2_result = mysql_query("SELECT * FROM tbl_invoice") or die("Error: " . mysql_error());

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
<script>
function chg_op(val){
	if(val==1){
		$('.ha').show();
		$('.oa').hide();
		$('.ov').hide();
		$('.sp').hide();
	}else if(val==2){
		$('.ha').hide();
		$('.oa').show();
		$('.ov').hide();
		$('.sp').hide();
	}else if(val==3){
		$('.ha').hide();
		$('.oa').hide();
		$('.ov').show();
		$('.sp').hide();
	}else if(val==4){
		$('.ha').hide();
		$('.oa').hide();
		$('.ov').hide();
		$('.sp').show();
	}
}

$(document).ready(function(){
	$('.oa').hide();
	$('.ov').hide();
	$('.sp').hide();
});
</script>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=130&r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Terug naar financieel" />
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
					</tr>
					<tr class="tbl-subhead">
						<td width="21">&nbsp;</td>
						<td width="185">Bedrijf</td>
						<td width="184">Factuurbedrag (excl BTW)</td>
						<td width="184">Factuur behorende bij <!--<a class="tooltip" href="#"><img src="../../images/info_icon.png" width="18" height="18" /><span class="classic"><div class="tt-title"><?php //echo $rs_tooltip_row['Title']; ?></div><?php //echo $rs_tooltip_row['Message']; ?></span></a>--></td>
						<td width="223">Opmerking</td>
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
								<select name="slt_option" id="slt_option" style="width: 99%;" onChange="chg_op(this.value)">
									<option value="1">Hoofdaanneming</option>
									<option value="2">Onderaanneming</option>
									<option value="4">Stelposten</option>
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
						<td colspan="2">
							<form action="" method="post" name="frm_option" id="frm_option">
								<select onchange="document.frm_option.submit()" name="slt_option" id="slt_option">
									<option value="">Geen</option>
									
								</select>
							</form>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="37">&nbsp;</td>
						<td width="166">Bedrijf</td>
						<td width="186">Factuurbedrag (excl BTW)</td>
						<td width="184">Factuur behorende bij
							<!--<a class="tooltip" href="#"><img src="../../images/info_icon.png" width="18" height="18" /><span class="classic"><div class="tt-title"><?php //echo $rs_tooltip_row['Title']; ?></div><?php //echo $rs_tooltip_row['Message']; ?></span></a>--></td>
						<td width="233">Opmerking</td>
					</tr>
					<?php $i=0; while($rs_invoices_row = mysql_fetch_assoc($rs_invoices_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><a href="?p_id=<?php echo $_GET['p_id']; ?>&r_id=<?php echo $_GET['r_id']; ?>&del_id=<?php echo $rs_invoices_row['Project_invoice_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						<td><?php echo $rs_invoices_row['Company_name']; ?></td>
						<td align="right">&euro;<?php echo number_format($rs_invoices_row['Amount'], 2, ',', '.'); ?></td>
						<td align="right"><?php if($rs_invoices_row['Invoice_option'] == 1){ echo "Hoofdaanneming"; }else if($rs_invoices_row['Invoice_option'] == 2){ echo "Onderaanneming"; }else if($rs_invoices_row['Invoice_option'] == 3){ echo "Derden"; }else{ echo "Stelposten"; } ?></td>
						<td><?php echo $rs_invoices_row['Description']; ?></td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td width="37">&nbsp;</td>
						<td width="166">Totaal</td>
						<td align="right">&euro;<?php echo number_format($rs_invoices_total_row['Total_amount'], 2, ',', '.'); ?></td>
						<td width="184" align="right">&nbsp;</td>
						<td width="233">&nbsp;</td>
					</tr>
					<tr class="tbl-head">
						<td colspan="5" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
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
mysql_free_result($rs_invoice2_result);
mysql_free_result($rs_invoice_result);
mysql_free_result($rs_invoices_total_result);
mysql_free_result($rs_invoices_result);
?>