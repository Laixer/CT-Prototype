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

# All chapters for this projecy
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_id='%s' AND p.User_id='%s' ORDER BY c.Priority ASC", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_project_work_inv0_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv1_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv2_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv3_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());
$rs_project_work_inv4_result = mysql_query($rs_project_work_qry) or die("Error: " . mysql_error());

$rs_project_term_all_qry = sprintf("SELECT * FROM tbl_project_term WHERE Project_term_id=%d LIMIT 1", $_GET['t_id']);
$rs_project_term_all_result = mysql_query($rs_project_term_all_qry) or die("Error: " . mysql_error());
$rs_project_term_all_row = mysql_fetch_assoc($rs_project_term_all_result);

$rs_project_invoice_view_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE Project_id=%d", mysql_real_escape_string($_GET['r_id']));
$rs_project_invoice_view_result = mysql_query($rs_project_invoice_view_qry) or die("Error: " . mysql_error());
$rs_project_invoice_view_row = mysql_fetch_assoc($rs_project_invoice_view_result);

$sub_21 = ($rs_project_invoice_view_row['Calc_21']+$rs_project_invoice_view_row['Post_new_21']+$rs_project_invoice_view_row['More_21']+$rs_project_invoice_view_row['Less_21']);
$sub_6 = ($rs_project_invoice_view_row['Calc_6']+$rs_project_invoice_view_row['Post_new_6']+$rs_project_invoice_view_row['More_6']+$rs_project_invoice_view_row['Less_6']);
$sub_0 = ($rs_project_invoice_view_row['Calc_0']+$rs_project_invoice_view_row['Post_new_0']+$rs_project_invoice_view_row['More_0']+$rs_project_invoice_view_row['Less_0']);

$rs_project_offer_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_result = mysql_query($rs_project_offer_qry) or die("Error: " . mysql_error());
$rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result);

$rs_project_term_qry = sprintf("SELECT * FROM tbl_project_term t JOIN tbl_project_term_type tp ON t.type_id=tp.term_type_id WHERE offer_id=%d ORDER BY priority ASC", $rs_project_offer_row['Offer_id']);
$rs_project_term_result = mysql_query($rs_project_term_qry) or die("Error: " . mysql_error());

$rest21 = 0;
$rest6 = 0;
$rest0 = 0;
while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){
	if($rs_project_term_row['Close'] == 'N'){
		$rest21 += $rs_project_term_row['Rest21'];
		$rest6 += $rs_project_term_row['Rest6'];
		$rest0 += $rs_project_term_row['Rest0'];
	}
}

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<div id="page-bgtop">
	<div id="title" style="text-align:right">
		<div style="float:right"></div>
		<h1><strong><?php if($_GET['t']==1){ echo 'SLOTTERMIJN'; }else{ echo 'FACTUUR'; } ?></strong></h1>
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
          <td>T: <?php echo $rs_project_relation_row['Phone_1']; ?></td></td>
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
      <p>
	  <?php if($_GET['t']==1){ ?>
	  Bij deze doe ik u toekomen het slottermijn zoals overeengekomen in onze offerte betreffende het project 
	  <?php }else{ ?>
	  Bij deze doe ik u toekomen de eindfactuur zoals overeengekomen in onze offerte betreffende het project 
	  <?php } echo $rs_project_perm_check_row['Name']; ?>.<br />
Hierin zijn (indien aanwezig) de gestelde stelposten, het meer- en minderwerk gespecificeerd.</p>
    </div>
    <div>
<p><?php echo $rs_project_term_all_row['Description']; ?></p>
    </div>
	<br />
    <table cellspacing="0" style="width:100%">
        <tr>
          <td width="49%" style="border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><strong>Aanneemsom volgens offerte (Excl. stelposten)</strong></td>
          <td colspan="3" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Arbeid</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materiaal</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materieel</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;">BTW</td>
          <td width="3%" style="border-right:solid 1px #000;">&nbsp;</td>
          <td width="18%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Totaal Excl. BTW</strong></td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;border-right:solid 1px #000;border-top:solid 1px #000;">&nbsp;</td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Uren</em></td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Kosten</em></td>
          <td width="11%" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><em>BTW</em></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php
        while($rs_project_work_inv0_row = mysql_fetch_assoc($rs_project_work_inv0_result)){
            $rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_mod_2 AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND (quant.Invoice_id=10 OR quant.Invoice_id=20) AND 1_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv0_row['Project_chapter_id']);
            $rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
            $rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
            # Chapter total
            $rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Chapter_id='%s' AND Invoice_id=10 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv0_row['Project_chapter_id']);
            $rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
            $rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
            # Sub total 1
            $rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_mod_2 WHERE Project_id='%s' AND Invoice_id=10 ORDER BY Project_id LIMIT 1", $rs_project_work_inv0_row['Project_id']);
            $rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
            $rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
            
            if($rs_project_quant_operation_num){
        ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><strong><?php echo $rs_project_work_inv0_row['Chapter']; ?></strong></td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php $i=0;
        while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
            $rs_project_old_qry = sprintf("SELECT 4_total FROM tvw_quantities_mod_2 WHERE operation_id='%s' LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
            $rs_project_old_result = mysql_query($rs_project_old_qry) or die("Error: " . mysql_error());
            $rs_project_old_row = mysql_fetch_array($rs_project_old_result);
			
			$rs_tax0_qry = sprintf("SELECT * FROM tbl_project_calc_salary WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax0_result = mysql_query($rs_tax0_qry) or die("Error: " . mysql_error());
			$rs_tax0_row = mysql_fetch_assoc($rs_tax0_result);
			
			$rs_tax1_qry = sprintf("SELECT * FROM tbl_project_calc_material WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax1_result = mysql_query($rs_tax1_qry) or die("Error: " . mysql_error());
			$rs_tax1_row = mysql_fetch_assoc($rs_tax1_result);
			
            $i++;
        ?>
        <?php if($rs_project_quant_operation_row['Invoice_id']=='10'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Material'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Total'], 2, ',', '.'); $material_1 += $rs_project_quant_operation_row['1_Total']; ?></td>
        </tr>
        <?php }elseif($rs_project_quant_operation_row['Invoice_id']=='20'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost2'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Material'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Total'], 2, ',', '.'); $physical_1 += $rs_project_quant_operation_row['2_Total']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
          <td style="border-left:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
        </tr>
        <?php } } ?>
        <tr>
          <td bgcolor="#CCCCCC" style="border-left:solid 1px #000;border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><em>Subtotaal</em></strong></td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td align="right" bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><?php echo '&euro;'.number_format($material_1+$physical_1, 2, ',', '.'); ?></strong></td>
        </tr>
    </table>
    <br />
    <table cellspacing="0" style="width:100%">
        <tr>
          <td width="49%" style="border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><strong>Specificatie stellen stelpost(en)</strong></td>
          <td colspan="3" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Arbeid</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materiaal</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materieel</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;">BTW</td>
          <td width="3%" style="border-right:solid 1px #000;">&nbsp;</td>
          <td width="18%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Totaal Excl. BTW</strong></td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;border-right:solid 1px #000;border-top:solid 1px #000;">&nbsp;</td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Uren</em></td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Kosten</em></td>
          <td width="11%" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><em>BTW</em></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
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
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><strong><?php echo $rs_project_work_inv1_row['Chapter']; ?></strong></td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php $i=0;
        while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
            $rs_project_old_qry = sprintf("SELECT 4_total FROM tvw_quantities_mod_2 WHERE operation_id='%s' LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
            $rs_project_old_result = mysql_query($rs_project_old_qry) or die("Error: " . mysql_error());
            $rs_project_old_row = mysql_fetch_array($rs_project_old_result);
			
			$rs_tax0_qry = sprintf("SELECT * FROM tbl_project_calc_sec_salary WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax0_result = mysql_query($rs_tax0_qry) or die("Error: " . mysql_error());
			$rs_tax0_row = mysql_fetch_assoc($rs_tax0_result);
			
			$rs_tax1_qry = sprintf("SELECT * FROM tbl_project_calc_sec_material WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax1_result = mysql_query($rs_tax1_qry) or die("Error: " . mysql_error());
			$rs_tax1_row = mysql_fetch_assoc($rs_tax1_result);
			
            $i++;
        ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Hour_cost'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Material'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['Total'], 2, ',', '.'); $post_total_1 += $rs_project_quant_operation_row['Total']; ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td style="border-left:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
        </tr>
        <?php } } ?>
        <tr>
          <td bgcolor="#CCCCCC" style="border-left:solid 1px #000;border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><em>Subtotaal</em></strong></td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td align="right" bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><?php echo number_format($post_total_1, 2, ',', '.'); ?></strong></td>
        </tr>
    </table><br />
	<table cellspacing="0" style="width:100%">
        <tr>
          <td width="49%" style="border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><strong>Specificatie meerwerk</strong></td>
          <td colspan="3" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Arbeid</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materiaal</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materieel</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;">BTW</td>
          <td width="3%" style="border-right:solid 1px #000;">&nbsp;</td>
          <td width="18%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Totaal Excl. BTW</strong></td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;border-right:solid 1px #000;border-top:solid 1px #000;">&nbsp;</td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Uren</em></td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Kosten</em></td>
          <td width="11%" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><em>BTW</em></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php
        while($rs_project_work_inv2_row = mysql_fetch_assoc($rs_project_work_inv2_result)){
            $rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_more AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND (quant.Invoice_id=60 OR quant.Invoice_id=70) AND 2_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv2_row['Project_chapter_id']);
            $rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
            $rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
            # Chapter total
            $rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Total) AS Overall_total FROM tvw_quantities_more WHERE Chapter_id='%s' AND Invoice_id=70 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv2_row['Project_chapter_id']);
            $rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
            $rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
            # Sub total 1
            $rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost2) AS Total_hour_cost, SUM(2_Material) AS Total_material, SUM(2_Physical) AS Total_physical, SUM(2_Total) AS Overall_total FROM tvw_quantities_more WHERE Project_id='%s' AND Invoice_id=70 ORDER BY Project_id LIMIT 1", $rs_project_work_inv2_row['Project_id']);
            $rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
            $rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
            
            if($rs_project_quant_operation_num){
        ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><strong><?php echo $rs_project_work_inv2_row['Chapter']; ?></strong></td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php $i=0;
        while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
            $rs_project_old_qry = sprintf("SELECT 4_total FROM tvw_quantities_mod_2 WHERE operation_id='%s' LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
            $rs_project_old_result = mysql_query($rs_project_old_qry) or die("Error: " . mysql_error());
            $rs_project_old_row = mysql_fetch_array($rs_project_old_result);
			
			$rs_tax0_qry = sprintf("SELECT * FROM tbl_project_calc_third_salary WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax0_result = mysql_query($rs_tax0_qry) or die("Error: " . mysql_error());
			$rs_tax0_row = mysql_fetch_assoc($rs_tax0_result);
			
			$rs_tax1_qry = sprintf("SELECT * FROM tbl_project_calc_third_material WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax1_result = mysql_query($rs_tax1_qry) or die("Error: " . mysql_error());
			$rs_tax1_row = mysql_fetch_assoc($rs_tax1_result);
			
            $i++;
        ?>
        <?php if($rs_project_quant_operation_row['Invoice_id']=='60'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['Hour_cost']){ $hour_cost = $rs_project_quant_operation_row['Hour_cost']; }else{ $hour_cost = $rs_project_quant_operation_row['Hour_cost2']; } echo '&euro;'.number_format($hour_cost, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Material'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Total'], 2, ',', '.'); $more_total_1 += $rs_project_quant_operation_row['1_Total']; ?></td>
        </tr>
        <?php }elseif($rs_project_quant_operation_row['Invoice_id']=='70'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['Hour_cost']){ $hour_cost = $rs_project_quant_operation_row['Hour_cost']; }else{ $hour_cost = $rs_project_quant_operation_row['Hour_cost2']; } echo '&euro;'.number_format($hour_cost, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Material'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Total'], 2, ',', '.'); $more_total_1 += $rs_project_quant_operation_row['2_Total']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
          <td style="border-left:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
        </tr>
        <?php } } ?>
        <tr>
          <td bgcolor="#CCCCCC" style="border-left:solid 1px #000;border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><em>Subtotaal</em></strong></td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td align="right" bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><?php echo number_format($more_total_1, 2, ',', '.'); ?></strong></td>
        </tr>
    </table><br />
	<table cellspacing="0" style="width:100%">
        <tr>
          <td width="49%" style="border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><strong>Specificatie minderwerk</strong></td>
          <td colspan="3" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Arbeid</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materiaal</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Materieel</strong></td>
          <td width="11%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;">BTW</td>
          <td width="3%" style="border-right:solid 1px #000;">&nbsp;</td>
          <td width="18%" rowspan="2" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><strong>Totaal Excl. BTW</strong></td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000;border-right:solid 1px #000;border-top:solid 1px #000;">&nbsp;</td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Uren</em></td>
          <td width="8%" style="border-top:solid 1px #000; border-right:solid 1px #000;" align="center"><em>Kosten</em></td>
          <td width="11%" align="center" style="border-top:solid 1px #000; border-right:solid 1px #000;"><em>BTW</em></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-top:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php
        while($rs_project_work_inv3_row = mysql_fetch_assoc($rs_project_work_inv3_result)){
            $rs_project_quant_operation_qry = sprintf("SELECT quant.*, o.Operation FROM tvw_quantities_less AS quant JOIN tbl_project_operation AS o ON o.Project_operation_id=quant.Operation_id WHERE quant.Chapter_id='%s' AND (quant.Invoice_id=10 OR quant.Invoice_id=20) AND 1_Total IS NOT NULL ORDER BY o.Priority ASC", $rs_project_work_inv3_row['Project_chapter_id']);
            $rs_project_quant_operation_result = mysql_query($rs_project_quant_operation_qry) or die("Error: " . mysql_error());
            $rs_project_quant_operation_num = mysql_num_rows($rs_project_quant_operation_result);
            # Chapter total
            $rs_operation_chaptotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_less WHERE Chapter_id='%s' AND Invoice_id=10 ORDER BY Chapter_id LIMIT 1", $rs_project_work_inv3_row['Project_chapter_id']);
            $rs_operation_chaptotal_result = mysql_query($rs_operation_chaptotal_qry) or die("Error: " . mysql_error());
            $rs_operation_chaptotal_row = mysql_fetch_assoc($rs_operation_chaptotal_result);
            # Sub total 1
            $rs_operation_subtotal_qry = sprintf("SELECT SUM(Hour_amount) AS Total_hour_amount, SUM(Hour_cost) AS Total_hour_cost, SUM(1_Material) AS Total_material, SUM(1_Physical) AS Total_physical, SUM(1_Post) AS Total_post, SUM(1_Total) AS Overall_total FROM tvw_quantities_less WHERE Project_id='%s' AND Invoice_id=10 ORDER BY Project_id LIMIT 1", $rs_project_work_inv3_row['Project_id']);
            $rs_operation_subtotal_result = mysql_query($rs_operation_subtotal_qry) or die("Error: " . mysql_error());
            $rs_operation_subtotal_row = mysql_fetch_assoc($rs_operation_subtotal_result);
            
            if($rs_project_quant_operation_num){
        ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><strong><?php echo $rs_project_work_inv3_row['Chapter']; ?></strong></td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
        </tr>
		<?php $i=0;
        while($rs_project_quant_operation_row = mysql_fetch_assoc($rs_project_quant_operation_result)){
            $rs_project_old_qry = sprintf("SELECT 4_total FROM tvw_quantities_mod_2 WHERE operation_id='%s' LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
            $rs_project_old_result = mysql_query($rs_project_old_qry) or die("Error: " . mysql_error());
            $rs_project_old_row = mysql_fetch_array($rs_project_old_result);
			
			$rs_tax0_qry = sprintf("SELECT * FROM tbl_project_calc_frth_salary WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax0_result = mysql_query($rs_tax0_qry) or die("Error: " . mysql_error());
			$rs_tax0_row = mysql_fetch_assoc($rs_tax0_result);
			
			$rs_tax1_qry = sprintf("SELECT * FROM tbl_project_calc_frth_material WHERE operation_id=%d GROUP BY operation_id LIMIT 1", $rs_project_quant_operation_row['Operation_id']);
			$rs_tax1_result = mysql_query($rs_tax1_qry) or die("Error: " . mysql_error());
			$rs_tax1_row = mysql_fetch_assoc($rs_tax1_result);

            $i++;
        ?>
        <?php if($rs_project_quant_operation_row['Invoice_id']=='10'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['Hour_cost']){ $hour_cost = $rs_project_quant_operation_row['Hour_cost']; }else{ $hour_cost = $rs_project_quant_operation_row['Hour_cost2']; } echo '&euro;'.number_format($hour_cost, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['1_Material']){ $material_t1 = $rs_project_quant_operation_row['1_Material']; }else{ $material_t1 = $rs_project_quant_operation_row['2_Material']; } echo '&euro;'.number_format($material_t1, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['1_Total'], 2, ',', '.'); $less_total_1 += $rs_project_quant_operation_row['1_Total']; ?></td>
        </tr>
        <?php }elseif($rs_project_quant_operation_row['Invoice_id']=='20'){ ?>
        <tr>
          <td style="border-left:solid 1px #000; border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Operation'];?></td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo $rs_project_quant_operation_row['Hour_amount']; ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['Hour_cost2']){ $hour_cost = $rs_project_quant_operation_row['Hour_cost2']; }else{ $hour_cost = $rs_project_quant_operation_row['Hour_cost2']; } echo '&euro;'.number_format($hour_cost, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax0_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax0_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php if($rs_project_quant_operation_row['2_Material']){ $material_t1 = $rs_project_quant_operation_row['2_Material']; }else{ $material_t1 = $rs_project_quant_operation_row['2_Material']; } echo '&euro;'.number_format($material_t1, 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><span class="tbl-subhead"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Physical'], 2, ',', '.'); ?></span></td>
          <td align="right" style="border-right:solid 1px #000;"><?php if($rs_tax1_row['Tax_id'] == '40'){ echo '21%'; }elseif($rs_tax1_row['Tax_id']=='20'){ echo '6%'; }else{ echo '0%'; } ?></td>
          <td style="border-right:solid 1px #000;">&nbsp;</td>
          <td align="right" style="border-right:solid 1px #000;"><?php echo '&euro;'.number_format($rs_project_quant_operation_row['2_Total'], 2, ',', '.'); $less_total_1 += $rs_project_quant_operation_row['2_Total']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
          <td style="border-left:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
        </tr>
        <?php } } ?>
        <tr>
          <td bgcolor="#CCCCCC" style="border-left:solid 1px #000;border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><em>Subtotaal</em></strong></td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;">&nbsp;</td>
          <td style="border-right: solid 1px #000;">&nbsp;</td>
          <td align="right" bgcolor="#CCCCCC" style="border-bottom:solid 1px #000;border-right: solid 1px #000;"><strong><?php echo number_format($less_total_1, 2, ',', '.'); ?></strong></td>
        </tr>
    </table><br />
<table cellspacing="0" style="width:100%">
    	<tr>
    	  <td width="22%">&nbsp;</td>
    	  <td width="12%" >&nbsp;</td>
    	  <td width="1%" >&nbsp;</td>
    	  <td colspan="2" ><strong>TE BETALEN OP BASIS VAN UITGEVOERDE WERKZAAMHEDEN</strong></td>
    	  <td width="2%" >&nbsp;</td>
          <td width="9%" >&nbsp;</td>
  	  </tr>
    	<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="right" style="border-top:solid 1px #000;">Subtotaal hoog BTW-tarief 21%</td>
        <td style="border-top:solid 1px #000;">&nbsp;</td>
        <td style="border-top:solid 1px #000;" align="right"><?php echo '&euro;'.number_format($sub_21, 2, ',', '.'); ?></td>
        </tr>
        <tr>
        <td >&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="right">Subtotaal laag BTW-tarief 6%</td>
        <td>&nbsp;</td>
        <td align="right" ><?php echo '&euro;'.number_format($sub_6, 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2" align="right">Subtotaal verlegd BTW-tarief 0%</td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($sub_0, 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2" align="right"><strong>Subtotaal te betalen Exlc. BTW</strong></td>
          <td>&nbsp;</td>
          <td align="right"><?php echo '&euro;'.number_format($sub_21+$sub_6+$sub_0, 2, ',', '.'); ?></td>
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
          <td align="right"><?php echo '&euro;'.number_format($sub_21, 2, ',', '.'); ?></td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($subsub_21 = ($sub_21*0.21), 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td width="44%" align="right">6% BTW over</td>
          <td width="10%" align="right"><?php echo '&euro;'.number_format($sub_6, 2, ',', '.'); ?></td>
          <td>&nbsp;</td>
          <td align="right" ><?php echo '&euro;'.number_format($subsub_6 = ($sub_6*0.06), 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2" align="right"><strong>Totaal te betalen Incl. BTW</strong></td>
          <td>&nbsp;</td>
          <td align="right" ><strong><?php echo '&euro;'.number_format($sub_21+$sub_6+$sub_0+$subsub_21+$subsub_6, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2"><strong>REEDS BETAALD MIDDELS TERMIJNFACTUREN</strong></td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
   	</tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right" style="border-top:solid 1px #000;">Subtotaal hoog BTW-tarief 21%</td>
        	<td style="border-top:solid 1px #000;">&nbsp;</td>
        	<td style="border-top:solid 1px #000;" align="right"><?php echo '&euro;'.number_format($rest21, 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right">Subtotaal laag BTW-tarief 6%</td>
        	<td>&nbsp;</td>
        	<td align="right" ><?php echo '&euro;'.number_format($rest6, 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right">Subtotaal verlegd BTW-tarief 0%</td>
        	<td>&nbsp;</td>
        	<td align="right" ><?php echo '&euro;'.number_format($rest0, 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right"><strong>Subtotaal te betalen Exlc. BTW</strong></td>
        	<td>&nbsp;</td>
        	<td align="right"><?php echo '&euro;'.number_format($rest21+$rest6+$rest0, 2, ',', '.'); ?></td>
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
        	<td align="right"><?php echo '&euro;'.number_format($rest21, 2, ',', '.'); ?></td>
        	<td>&nbsp;</td>
        	<td align="right" ><?php echo '&euro;'.number_format($subsubsub_21 = ($rest21*0.21), 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td align="right">6% BTW over</td>
        	<td align="right"><?php echo '&euro;'.number_format($rest6, 2, ',', '.'); ?></td>
        	<td>&nbsp;</td>
        	<td align="right" ><?php echo '&euro;'.number_format($subsubsub_6 = ($rest6*0.06), 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right"><strong>Totaal te betalen Incl. BTW</strong></td>
        	<td>&nbsp;</td>
        	<td align="right" ><strong><?php echo '&euro;'.number_format($rest21+$rest6+$rest0+$subsubsub_21+$subsubsub_6, 2, ',', '.'); ?></strong></td>
   	</tr>
        <?php if($_GET['t']==1){ ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2"><strong>NOG TE BETALEN BEDRAG</strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right" style="border-top:solid 1px #000;">Subtotaal hoog BTW-tarief 21%</td>
        	<td style="border-top:solid 1px #000;">&nbsp;</td>
        	<td style="border-top:solid 1px #000;" align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest21'], 2, ',', '.'); ?></td>
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
        	<td colspan="2" align="right"><strong>Subtotaal te betalen Exlc. BTW</strong></td>
        	<td>&nbsp;</td>
        	<td align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest21']+$rs_project_term_all_row['Rest6']+$rs_project_term_all_row['Rest0'], 2, ',', '.'); ?></td>
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
        	<td align="right" ><?php echo '&euro;'.number_format($subt_21 = ($rs_project_term_all_row['Rest21']*0.21), 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td align="right">6% BTW over</td>
        	<td align="right"><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest6'], 2, ',', '.'); ?></td>
        	<td>&nbsp;</td>
        	<td align="right" ><?php echo '&euro;'.number_format($subt_6 = ($rs_project_term_all_row['Rest6']*0.06), 2, ',', '.'); ?></td>
   	</tr>
        <tr>
        	<td >&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td colspan="2" align="right"><strong>Totaal te betalen Incl. BTW</strong></td>
        	<td>&nbsp;</td>
        	<td align="right" ><strong><?php echo '&euro;'.number_format($rs_project_term_all_row['Rest21']+$rs_project_term_all_row['Rest6']+$rs_project_term_all_row['Rest0']+$subt_21+$subt_6, 2, ',', '.'); ?></strong></td>
   	</tr>
		<?php
//		$i=0;
//		while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){
//			if($_GET['t_idx'] == $i){
//				break;
//			}
//			$i++ ?>
		<?php } ?>
		<?php //} ?>
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
