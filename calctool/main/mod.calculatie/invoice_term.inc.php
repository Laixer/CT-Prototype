<?php
# Includes
include_once("../../../private/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# User session
$user_id = mysql_real_escape_string($_SESSION['SES_User_id']);

# User's relation for warnings
$rs_relation_qry = sprintf("SELECT TRUE FROM tbl_relation WHERE User_id='%s' AND Relation_type_id=1 LIMIT 1", $user_id);
$rs_relation_result = mysql_query($rs_relation_qry) or die("Error: " . mysql_error());
$rs_relation_row = mysql_fetch_assoc($rs_relation_result);
mysql_free_result($rs_relation_result);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_client_qry = sprintf("SELECT * FROM tbl_relation WHERE relation_id=%d LIMIT 1", $rs_project_perm_check_row['Client_relation_id']);
$rs_project_client_result = mysql_query($rs_project_client_qry) or die("Error: " . mysql_error());
$rs_project_client_row = mysql_fetch_assoc($rs_project_client_result);

$rs_project_relation_qry = sprintf("SELECT * FROM tbl_relation WHERE User_id=%d AND Relation_type_id=1 LIMIT 1",  $user_id);
$rs_project_relation_result = mysql_query($rs_project_relation_qry) or die("Error: " . mysql_error());
$rs_project_relation_row = mysql_fetch_assoc($rs_project_relation_result);

$rs_project_term_all_qry = sprintf("SELECT * FROM tbl_project_term WHERE Project_term_id=%d LIMIT 1", $_GET['t_id']);
$rs_project_term_all_result = mysql_query($rs_project_term_all_qry) or die("Error: " . mysql_error());
$rs_project_term_all_row = mysql_fetch_assoc($rs_project_term_all_result);

$rs_project_invoice_view_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE Project_id=%d", mysql_real_escape_string($_GET['r_id']));
$rs_project_invoice_view_result = mysql_query($rs_project_invoice_view_qry) or die("Error: " . mysql_error());
$rs_project_invoice_view_row = mysql_fetch_assoc($rs_project_invoice_view_result);

$sub_21 = ($rs_project_invoice_view_row['Calc_21']+$rs_project_invoice_view_row['Post_new_21']+$rs_project_invoice_view_row['More_21']+$rs_project_invoice_view_row['Less_21']);
$sub_6 = ($rs_project_invoice_view_row['Calc_6']+$rs_project_invoice_view_row['Post_new_6']+$rs_project_invoice_view_row['More_6']+$rs_project_invoice_view_row['Less_6']);
$sub_0 = ($rs_project_invoice_view_row['Calc_0']+$rs_project_invoice_view_row['Post_new_0']+$rs_project_invoice_view_row['More_0']+$rs_project_invoice_view_row['Less_0']);

$totaal_btw = (($sub_21+$sub_6+$sub_0)+(($sub_21*0.21)+($sub_6*0.06)));

$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

$rs_project_term_qry = sprintf("SELECT * FROM tbl_project_term t JOIN tbl_project_term_type tp ON t.type_id=tp.term_type_id WHERE offer_id=%d ORDER BY priority ASC", $rs_project_offer_row['Offer_id']);
$rs_project_term_result = mysql_query($rs_project_term_qry) or die("Error: " . mysql_error());
$rs_project_term_num = mysql_num_rows($rs_project_term_result);

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
	<div id="title" style="text-align:right">
		<div style="float:right"></div>
		<h1><strong>TERMIJNFACTUUR</strong></h1>
	</div>
    <table cellspacing="0" style="width:100%" bgcolor="#CCCCCC">
    	<tr>
    	  <td width="22%" style="border-top:solid 1px #000; border-left:solid 1px #000;">&nbsp;</td>
    	  <td width="24%" style="border-top:solid 1px #000;">&nbsp;</td>
    	  <td width="2%" style="border-top:solid 1px #000;">&nbsp;</td>
    	  <td width="23%" style="border-top:solid 1px #000;">&nbsp;</td>
    	  <td width="27%" style="border-top:solid 1px #000;">&nbsp;</td>
          <td width="2%" style="border-right:solid 1px #000; border-top:solid 1px #000;">&nbsp;</td>
  	  </tr>
    	<tr>
        <td style="border-left:solid 1px #000;">&nbsp;</td>
        <td><strong><?php echo $rs_project_client_row['Company_name']; ?></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $rs_project_relation_row['Company_name']; ?></td>
        <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
        <td style="border-left:solid 1px #000;">&nbsp;</td>
        <td><strong><?php if($rs_project_client_row['Relation_business_type_id']==20){ echo 't.a.v. '; } echo $rs_project_client_row['Contact_first_name'].' '.$rs_project_client_row['Contact_name']; ?></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $rs_project_relation_row['Address'].' '.$rs_project_relation_row['Address_number']; ?></td>
        <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">&nbsp;</td>
          <td><strong><?php echo $rs_project_client_row['Address'].' '.$rs_project_client_row['Address_number']; ?></strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        <td><?php echo $rs_project_relation_row['Zipcode'].' '.$rs_project_relation_row['City']; ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">&nbsp;</td>
          <td><strong><?php echo $rs_project_client_row['Zipcode'].' '.$rs_project_client_row['City']; ?></strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>T: <?php echo $rs_project_relation_row['Phone_1']; ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>M: <?php echo $rs_project_relation_row['Phone_2']; ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>E: <?php echo $rs_project_relation_row['Email_1']; ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">Debiteurennummer</td>
          <td><?php echo $rs_project_client_row['Relation_id']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;">Factuurnummer</td>
          <td><?php echo $rs_project_term_all_row['Invoice_number']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border-left:solid 1px #000;">Boekhoudkundignummer</td>
        	<td><?php echo $rs_project_term_all_row['Booknumber']; ?></td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td style="border-right:solid 1px #000;">&nbsp;</td>
       	</tr>
        <tr>
        	<td style="border-left:solid 1px #000;">Referentienummer</td>
        	<td><?php echo $rs_project_term_all_row['Reference']; ?></td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td style="border-right:solid 1px #000;">&nbsp;</td>
       	</tr>
        <tr>
          <td style="border-left:solid 1px #000;">Datum</td>
          <td><?php echo date("j-n-Y", strtotime($rs_project_term_all_row['Timestamp_date'])); ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000; border-bottom:solid 1px #000;">&nbsp;</td>
          <td style="border-bottom:solid 1px #000;">&nbsp;</td>
          <td style="border-bottom:solid 1px #000;">&nbsp;</td>
          <td style="border-bottom:solid 1px #000;">&nbsp;</td>
          <td style="border-bottom:solid 1px #000;">&nbsp;</td>
          <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
        </tr>
    </table><br />
    <div>
      <p>Geachte Heer/Mevrouw <?php echo $rs_project_client_row['Contact_name']; ?>,</p>
      <p>Bij deze doe ik u toekomen <?php if($_GET['t_idx']==0){ echo 'de aanbetaling'; }else{ echo 'het '.($_GET['t_idx']+1).'e termijn'; } ?> zoals overeengekomen in onze offerte betreffende het project <?php echo $rs_project_perm_check_row['Name']; ?>.<br />
      Hierin zijn (indien aanwezig) de gestelde stelposten, het meer- en minderwerk naar ratio verwerkt. Op het slottermijn is de specificatie opgenomen.</p>
    </div>
    <div>
<p><?php echo $rs_project_term_all_row['Description']; ?></p>
    </div>
    <strong>TE BETALEN OP BASIS VAN TERMIJNFACTUUR</strong><br />
<table cellspacing="0" style="width:100%">
    	<tr>
    	  <td colspan="2" style="border-top:solid 1px #000;"><strong><?php echo ($_GET['t_idx']+1); ?>e termijn van <?php echo $rs_project_term_num; ?> volgens opgave offerte (excl. BTW)</strong></td>
    	  <td width="2%" style="border-top:solid 1px #000;">&nbsp;</td>
    	  <td colspan="2" style="border-top:solid 1px #000;">&nbsp;</td>
    	  <td width="3%" style="border-top:solid 1px #000;">&nbsp;</td>
          <td width="10%"style="border-top:solid 1px #000;"  align="right" ><strong><?php echo '&euro;'.number_format($rs_project_term_all_row['Amount']); ?></strong></td>
  	  </tr>
    	<tr>
        <td width="22%">&nbsp;</td>
        <td width="24%">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="right">Subtotaal hoog BTW-tarief 21%</td>
        <td>&nbsp;</td>
        <td align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest21'], 2, ',', '.'); ?></td>
        </tr>
        <tr>
        <td >&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="right">Subtotaal laag BTW-tarief 6%</td>
        <td>&nbsp;</td>
        <td align="right" ><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest6'], 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2" align="right">Subtotaal verlegd BTW-tarief 0%</td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest0'], 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td >&nbsp;</td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right">21% BTW over</td>
          <td align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest21'], 2, ',', '.'); ?></td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($subsub_21 = ($rs_project_term_all_row['Rest21']*0.21), 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td width="32%" align="right">6% BTW over</td>
          <td width="7%" align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest6'], 2, ',', '.'); ?></td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($subsub_6 = ($rs_project_term_all_row['Rest6']*0.06), 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2" align="right"><strong>Totaal te betalen Incl. BTW</strong></td>
          <td>&nbsp;</td>
          <td align="right" ><strong><?php $current_total = ($rs_project_term_all_row['Rest21']+$rs_project_term_all_row['Rest6']+$rs_project_term_all_row['Rest0']+$subsub_21+$subsub_6); echo '&euro;'.number_format($current_total, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
		<?php if($_GET['t_idx']!=0){ ?>
        <tr>
          <td colspan="2"><strong>REEDS BETAALD MIDDELS TERMIJNFACTUREN</strong></td>
          <td>&nbsp;</td>
          <td colspan="2" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" style="border-top:solid 1px #000;"><strong>Totaal te betalen factuurbedrag</strong></td>
          <td style="border-top:solid 1px #000;">&nbsp;</td>
          <td colspan="2" style="border-top:solid 1px #000;">&nbsp;</td>
          <td style="border-top:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000;"><strong><?php echo '&euro;'.number_format($totaal_btw, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2">&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
   	</tr>
        <tr>
        	<td>Reeds betaalde termijnfacturen</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2">&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
   	</tr>
	<?php
	$i=0;
	while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){
		if($_GET['t_idx'] == $i){
			break;
		}
		$i++ ?>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td align="right" colspan="2"><?php if($rs_project_term_row['Close']=='Y'){ echo 'Slottermijn '; }else if($i==1){ echo 'Aanbetaling'; }else{ echo $i.'e Termijn'; } ?></td>
        	<td>&nbsp;</td>
        	<td align="right"><?php $this_total = (($rs_project_term_row['Rest21']+$rs_project_term_row['Rest6']+$rs_project_term_row['Rest0'])+(($rs_project_term_row['Rest21']*0.21)+($rs_project_term_row['Rest6']*0.06))); echo '&euro;'.number_format($this_total, 2, ',', '.'); $t_total += $this_total; ?></td>
   	</tr>
	<?php } ?>
        <tr>
        	<td colspan="2"><strong>Subtotaal reeds betaald Incl. BTW</strong></td>
        	<td>&nbsp;</td>
        	<td colspan="2">&nbsp;</td>
        	<td>&nbsp;</td>
        	<td align="right"><strong><?php echo '&euro;'.number_format($t_total, 2, ',', '.'); ?></strong></td>
   	</tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2">&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
   	</tr>
	<?php } ?>
        <tr>
        	<td colspan="2"><strong>Nog openstaand bedrag na deze termijnfactuur</strong></td>
        	<td>&nbsp;</td>
        	<td colspan="2">&nbsp;</td>
        	<td>&nbsp;</td>
        	<td align="right"><strong><?php echo '&euro;'.number_format(($totaal_btw-($t_total+$current_total)), 2, ',', '.'); ?></strong></td>
   	</tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="7" align="center" style="border-left:solid 1px #000;border-right: solid 1px #000; border-top:solid 1px #000;border-bottom: solid 1px #000;"><strong>Betalingscondities: binnen <?php echo $rs_project_term_all_row['Payment_conditions']; ?> dagen na factuurdatum o.v.v. het bovengenoemde debiteuren- en factuurnummer.</strong></td>
        </tr>
    </table>
