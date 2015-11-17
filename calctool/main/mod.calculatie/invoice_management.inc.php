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

$rs_project_result_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE project_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

$amount_total = ($rs_project_result_row['Calc_21']+$rs_project_result_row['Calc_6']+$rs_project_result_row['Calc_0']+$rs_project_result_row['Post_new_21']+$rs_project_result_row['Post_new_6']+$rs_project_result_row['Post_new_0']+$rs_project_result_row['More_21']+$rs_project_result_row['More_6']+$rs_project_result_row['More_0']+$rs_project_result_row['Less_21']+$rs_project_result_row['Less_6']+$rs_project_result_row['Less_0']);

$sub_21 = ($rs_project_result_row['Calc_21']+$rs_project_result_row['Post_new_21']+$rs_project_result_row['More_21']+$rs_project_result_row['Less_21']);
$sub_6 = ($rs_project_result_row['Calc_6']+$rs_project_result_row['Post_new_6']+$rs_project_result_row['More_6']+$rs_project_result_row['Less_6']);
$sub_0 = ($rs_project_result_row['Calc_0']+$rs_project_result_row['Post_new_0']+$rs_project_result_row['More_0']+$rs_project_result_row['Less_0']);

function generate_invoice_nr($nr){
	global $user_id;
	global $rs_project_perm_check_row;
	return 'TF-'.$user_id.'-'.$rs_project_perm_check_row['Project_id'].'-'.sprintf("%02s", $nr);
}

if(isset($_GET['del_id'])){
	$del_id = mysql_real_escape_string($_GET['del_id']);
	
	$rs_del_op_qry = sprintf("DELETE i.* FROM tbl_project_calc_invoice AS i JOIN tbl_project AS p ON p.Project_id=i.Project_id WHERE i.Project_invoice_id='%s' AND p.User_id='%s'", $del_id, $user_id);
	mysql_query($rs_del_op_qry) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['ftmid'])){
	$id = mysql_real_escape_string($_POST['ftmid']);
	$booknr = mysql_real_escape_string($_POST['booknr']);
	$ref = mysql_real_escape_string($_POST['ref']);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Booknumber='%s', Reference='%s' WHERE Project_term_id=%d", $booknr, $ref, $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['dtmid'])){
	$id = mysql_real_escape_string($_POST['dtmid']);
	$desc = mysql_real_escape_string($_POST['desc']);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Description='%s' WHERE Project_term_id=%d", $desc, $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['pcmid'])){
	$id = mysql_real_escape_string($_POST['pcmid']);
	$pc = mysql_real_escape_string($_POST['pc']);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Payment_conditions=%d WHERE Project_term_id=%d", $pc, $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['create'])){
	$id = mysql_real_escape_string($_POST['stmid']);
	$idx = mysql_real_escape_string($_POST['stmidx']);

	$invoice_number = generate_invoice_nr($idx);
	$rst21 = mysql_real_escape_string($_POST['rst21']);
	$rst6 = mysql_real_escape_string($_POST['rst6']);
	$rst0 = mysql_real_escape_string($_POST['rst0']);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Invoice_number='%s',Rest21=%f,Rest6=%f,Rest0=%f WHERE Project_term_id=%d", $invoice_number, $rst21, $rst6, $rst0, $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['status'])){
	$id = mysql_real_escape_string($_POST['stmid']);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Type_id=2 WHERE Project_term_id=%d", $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['ammid'])){
	$id = mysql_real_escape_string($_POST['ammid']);
	$am = mysql_real_escape_string($_POST['am']);
	$am = str_replace(',', '.', $am);

	$rs_update_term = sprintf("UPDATE tbl_project_term SET Amount=%f WHERE Project_term_id=%d", $am, $am, $id);
	mysql_query($rs_update_term) or die("Error: " . mysql_error());

	$rs_project_o_qry = sprintf("SELECT Offer_id FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
	$rs_project_o_result = mysql_query($rs_project_o_qry) or die("Error: " . mysql_error());
	$rs_project_o_row = mysql_fetch_assoc($rs_project_o_result);
	
	$rs_project_count_qry = sprintf("SELECT amount FROM tbl_project_term WHERE offer_id=%d ORDER BY priority DESC LIMIT 1,100", $rs_project_o_row['Offer_id']);
	$rs_project_count_result = mysql_query($rs_project_count_qry) or die("Error: " . mysql_error());
	
	$total=0;
	while($rs_project_count_row = mysql_fetch_assoc($rs_project_count_result)){
		$total += $rs_project_count_row['amount'];
	}
	$left = ($amount_total-$total);
	echo $left;
	if($left > 1){
		$rs_update_term = sprintf("UPDATE tbl_project_term SET Amount=%f WHERE offer_id=%d AND Close='Y'", $left, $rs_project_o_row['Offer_id']);
		mysql_query($rs_update_term) or die("Error: " . mysql_error());
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

$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

$rs_project_term_qry = sprintf("SELECT * FROM tbl_project_term t JOIN tbl_project_term_type tp ON t.type_id=tp.term_type_id WHERE offer_id=%d ORDER BY priority ASC", $rs_project_offer_row['Offer_id']);
$rs_project_term_result = mysql_query($rs_project_term_qry) or die("Error: " . mysql_error());

# No projects have been found
if(!$rs_project_perm_check_row){
	$error_message = "Er zijn geen gegevens gevonden";
	$hide_page = 1;
}

$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $rs_project_perm_check_row['Project_id']);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

if(!$rs_project_module7_row){
	$error_message = "Er dient eerst een opdrachtbevestiging te zijn";
}

//include('term_table.fn.inc.php');

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<?php if(!$hide_page){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$('.ivi').click(function(){
		var i = $(this).attr('data-id');
		$('#'+i).toggle("slow");
	});
	$('.ivo').click(function(){
		var i = $(this).attr('data-id');
		$('.'+i).toggle("slow");
	});
});
</script>
<div id="page-bgtop">
	<?php if($rs_project_module7_row){ ?>
	<div id="title">
		<span>Factuur Beheer</span>
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
					<tr class="tbl-subhead">
						<td width="166">Onderdeel</td>
						<td width="186">Factuurbedrag (excl BTW)</td>
                        <td width="233">Factuurnummer</td>
                        <td width="233">Omschrijving</td>
						<td width="233">Betalingscondities</td>
						<td width="233">Aangemaakt</td>
						<td width="233">Download</td>
						<td width="233">Status</td>
					</tr>
					<?php
					$i=0;
					$rest_slot = $amount_total;
					$close = true;
					echo '--------------trace--------------<br />';
					while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){
						if($rs_project_term_row['Close']!='Y'){
							$rest_slot -= $rs_project_term_row['Amount'];
						}
						echo '$sub_21    '.$sub_21.'<br />';
						echo '$sub_6    '.$sub_6.'<br />';
						echo '$sub_0    '.$sub_0.'<br />';
						$totaal = ($sub_21+$sub_6+$sub_0);
						echo '$totaal    '.$totaal.'<br />';
						$deel21 = ($sub_21/$totaal);
						echo '$deel21    '.$deel21.'<br />';
						$deel6 = ($sub_6/$totaal);
						echo '$deel6    '.$deel6.'<br />';
						$deel0 = ($sub_0/$totaal);
						echo '$deel0    '.$deel0.'<br />';
						if($rs_project_term_row['Close']=='Y'){
							$rest21 = ($totaal*$deel21);
						}else{
							$rest21 = ($rs_project_term_row['Amount']*$deel21);
						}
						if($rs_project_term_row['Type_id']==3){
							$sub_21 = ($sub_21-$rs_project_term_row['Rest21']);
						}else{
							$sub_21 = ($sub_21-$rest21);
						}
						echo '$rest21    '.$rest21.'<br />';
						if($rs_project_term_row['Close']=='Y'){
							$rest6 = ($totaal*$deel6);
						}else{
							$rest6 = ($rs_project_term_row['Amount']*$deel6);
						}
						if($rs_project_term_row['Type_id']==3){
							$sub_6 = ($sub_6-$rs_project_term_row['Rest6']);
						}else{
							$sub_6 = ($sub_6-$rest6);
						}
						echo '$rest6    '.$rest6.'<br />';
						if($rs_project_term_row['Close']=='Y'){
							$rest0 = ($totaal*$deel0);
						}else{
							$rest0 = ($rs_project_term_row['Amount']*$deel0);
						}
						if($rs_project_term_row['Type_id']==3){
							$sub_0 = ($sub_0-$rs_project_term_row['Rest0']);
						}else{
							$sub_0 = ($sub_0-$rest0);
						}
						echo '$rest0    '.$rest0.'<br />';
						echo '----------------------------------<br />';
						$i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php if($rs_project_term_row['Close']=='Y'){ echo 'Slottermijn '; }else if($i==1){ echo 'Aanbetaling'; }else{ echo $i.'e Termijn'; } ?></td>
						<td align="right">
							<?php if($rs_project_term_row['Close']=='Y'){ echo number_format($rest_slot, 2, ',', '.'); }else{ ?>
							<a href="javascript:void(0);" class="ivi" data-id="am-<?php echo $i; ?>"><?php echo number_format($rs_project_term_row['Amount'], 2, ',', '.'); ?></a>
							<?php } ?>
						</td>
                        <td>
							<a href="javascript:void(0);" class="ivo" data-id="fnr-<?php echo $i; ?>"><?php echo generate_invoice_nr($i); ?></a>
						</td>
                        <td align="center">
							<a href="javascript:void(0);" class="ivi" data-id="desc-<?php echo $i; ?>">T</a>
						</td>
						<td>
							<a href="javascript:void(0);" class="ivi" data-id="pc-<?php echo $i; ?>">
							<?php echo $rs_project_term_row['Payment_conditions'] . ' dagen'; ?></a>
						</td>
						<td><?php echo date("d-m-Y", strtotime($rs_project_term_row['Timestamp_date'])); ?></td>
						<td>
						<?php
							if(($rs_project_term_row['Type']=='Gefactureerd')||($rs_project_term_row['Invoice_number'])){
								if($rs_project_term_row['Close']=='Y'){ ?>
								<input style="height: 24px;" onclick="document.location='/main/mod.calculatie/invoice_total.inc.php?r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&t_id=<?php echo $rs_project_term_row['Project_term_id']; ?>&t=1&_utm=<?php echo $__url_session; ?>'" type="button" value="Bekijk factuur" />
								<?php }else{ ?>
								<input style="height: 24px;" onclick="document.location='/main/mod.calculatie/invoice_term.inc.php?r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&t_id=<?php echo $rs_project_term_row['Project_term_id']; ?>&t_idx=<?php echo ($i-1); ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Bekijk factuur" />
						<?php }
							}else if($close){ ?>
								<form id="frm-status" name="frm-status" method="post" action="">
									<input type="hidden" name="stmid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
									<input type="hidden" name="rst21" value="<?php echo $rest21; ?>" />
									<input type="hidden" name="rst6" value="<?php echo $rest6; ?>" />
									<input type="hidden" name="rst0" value="<?php echo $rest0; ?>" />
									<input type="hidden" name="stmidx" value="<?php echo $i; ?>" />
									<input type="submit" style="height: 24px;" name="create" value="Maak factuur" />
								</form>
							<?php } ?>
						</td>
						<td>
							<?php
							if($rs_project_term_row['Type']=='Gefactureerd'){
								echo 'Gefactureerd';
							}else{
								if($close&&$rs_project_term_row['Invoice_number']){ ?>
								<form id="frm-status" name="frm-status" method="post" action="">
									<input type="hidden" name="stmid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
									<input type="submit" style="height: 24px;" name="status" value="Factureren" />
								</form>
							<?php }else{ echo 'Open'; } }?>
						</td>
					</tr>
					<form id="frmam<?php echo $i; ?>" name="frmam<?php echo $i; ?>" method="post" action="">
                    <input type="hidden" name="ammid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
					<tr style="display:none" id="am-<?php echo $i; ?>" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">Factuurbedrag</td>
						<td colspan="5">
							<?php if($rs_project_term_row['Type']=='Betaald'){ echo '&euro;&nbsp;'.number_format($rs_project_term_row['Amount'], 2, ',', '.'); }else{ ?>
							<input type="text" id="am" name="am" value="<?php echo number_format($rs_project_term_row['Amount'], 2, ',', '.'); ?>" />
							<?php } ?>
						</td>
						<td align="right">
							<?php if($rs_project_term_row['Type']!='Betaald'){ ?>
							<input style="height: 24px;" onclick="document.frmam<?php echo $i; ?>.submit();" type="button" value="Opslaan" />
							<?php } ?>
							</td>
					</tr>
                    </form>
					<form id="frmpc<?php echo $i; ?>" name="frmpc<?php echo $i; ?>" method="post" action="">
                    <input type="hidden" name="pcmid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
					<tr style="display:none" id="pc-<?php echo $i; ?>" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">Betaling binnen</td>
						<td colspan="5">
							<?php if($rs_project_term_row['Type']=='Betaald'){ echo $rs_project_term_row['Payment_conditions'].' dagen'; }else{ ?>
							<select name="pc" id="pc"><option value="7" label="7 dagen">7</option><option value="14" label="14 dagen">14</option><option value="30" label="30 dagen">30</option></select>
							<?php } ?>
						</td>
						<td align="right">
							<?php if($rs_project_term_row['Type']!='Betaald'){ ?>
							<input style="height: 24px;" onclick="document.frmpc<?php echo $i; ?>.submit();" type="button" value="Opslaan" />
							<?php } ?>
							</td>
					</tr>
                    </form>
                    <form id="frmdesc<?php echo $i; ?>" name="frmdesc<?php echo $i; ?>" method="post" action="">
                    <input type="hidden" name="dtmid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
					<tr style="display:none" id="desc-<?php echo $i; ?>" class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">Omschrijving op de factuur</td>
						<td colspan="5">
							<?php if($rs_project_term_row['Type']=='Betaald'){ echo $rs_project_term_row['Description']; }else{ ?>
							<textarea id="desc" name="desc"><?php echo $rs_project_term_row['Description']; ?></textarea>
							<?php } ?>
						</td>
						<td align="right">
							<?php if($rs_project_term_row['Type']!='Betaald'){ ?>
							<input style="height: 24px;" onclick="document.frmdesc<?php echo $i; ?>.submit();" type="button" value="Opslaan" />
							<?php } ?>
							</td>
					</tr>
                    </form>
                    <form id="frmfnr<?php echo $i; ?>" name="frmfnr<?php echo $i; ?>" method="post" action="">
                    <input type="hidden" name="ftmid" value="<?php echo $rs_project_term_row['Project_term_id']; ?>" />
					<tr style="display:none" class="fnr-<?php echo $i; ?> <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">Factuurnummering</td>
   						<td colspan="2">Factuurnummering CalculatieTool</td>
						<td colspan="3" align="center"><?php echo generate_invoice_nr($i); ?></td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:none" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">&nbsp;</td>
   						<td colspan="2">Eigen boekhoudkundignummer</td>
						<td colspan="3" align="center">
							<?php if($rs_project_term_row['Type']=='Betaald'){ echo $rs_project_term_row['Booknumber']; }else{ ?>
							<input id="booknr" name="booknr" style="width:99%" value="<?php echo $rs_project_term_row['Booknumber']; ?>" />
							<?php } ?>
						</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:none" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td colspan="2">&nbsp;</td>
   						<td colspan="2">Referentienummer opdrachtgever</td>
						<td colspan="3" align="center">
							<?php if($rs_project_term_row['Type']=='Betaald'){ echo $rs_project_term_row['Reference']; }else{ ?>
							<input id="ref" name="ref" style="width:99%" value="<?php echo $rs_project_term_row['Reference']; ?>" />
							<?php } ?>
						</td>
						<td align="right">
							<?php if($rs_project_term_row['Type']!='Betaald'){ ?>
							<input style="height: 24px;" onclick="document.frmfnr<?php echo $i; ?>.submit();" type="button" value="Opslaan" />
							<?php } ?>
						</td>
					</tr>
                    </form>
					<?php
						if($rs_project_term_row['Type']=='Gefactureerd'){
							$close = true;
						}else{
							$close = false;
						}
					} ?>
					<tr class="tbl-head">
						<td colspan="8" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
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
