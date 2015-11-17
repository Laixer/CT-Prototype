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

print_r($rs_project_result_row);

$sub_21 = ($rs_project_result_row['Calc_21']+$rs_project_result_row['Post_new_21']+$rs_project_result_row['More_21']+$rs_project_result_row['Less_21']);
$sub_6 = ($rs_project_result_row['Calc_6']+$rs_project_result_row['Post_new_6']+$rs_project_result_row['More_6']+$rs_project_result_row['Less_6']);
$sub_0 = ($rs_project_result_row['Calc_0']+$rs_project_result_row['Post_new_0']+$rs_project_result_row['More_0']+$rs_project_result_row['Less_0']);

//-----

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
	<div id="title">
		<div style="float:right"></div>
		<span>Termijn tussentabel</span>
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
						<td width="105">Onderdeel</td>
						<td width="186">Restant</td>
                        <td width="149">Verhouding</td>
                        <td width="137">Restant</td>
						<td width="107">BTW</td>
						<td width="140">Betaald</td>
						<td width="132">&nbsp;</td>
						<td width="121">&nbsp;</td>
					</tr>
					<?php $i=0; while($rs_project_term_row = mysql_fetch_assoc($rs_project_term_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php if($rs_project_term_row['Close']=='Y'){ echo 'Slottermijn'; }else if($i==1){ echo 'Aanbetaling'; }else{ echo $i.'e Termijn'; } ?></td>
						<td align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><?php if($rs_project_term_row['Type_id']==3){ echo "Ja"; }else{ echo "Nee"; }?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Subtotaal 21</td>
						<td align="left"><?php echo $sub_21; ?></td>
                        <td>
							<a href="javascript:void(0);" class="ivo" data-id="fnr-<?php echo $i; ?>"></a>
						</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Subtotaal 6</td>
						<td><?php echo $sub_6; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="left"></td>
						<td align="left">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Subtotaal 0</td>
						<td><?php echo $sub_0; ?></td>
   						<td>&nbsp;</td>
   						<td>&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Totaal</td>
						<td><?php $totaal = ($sub_21+$sub_6+$sub_0); echo $totaal; ?></td>
   						<td>&nbsp;</td>
   						<td>&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td align="left">Deel21</td>
						<td align="center">&nbsp;</td>
						<td><?php $deel21 = ($sub_21/$totaal); echo $deel21; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Deel6</td>
						<td>&nbsp;</td>
   						<td><?php $deel6 = ($sub_6/$totaal); echo $deel6; ?></td>
   						<td>&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td>Deel0</td>
						<td>&nbsp;</td>
   						<td><?php $deel0 = ($sub_0/$totaal); echo $deel0; ?></td>
   						<td>&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td align="left">Rest21</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<?php
							$rest21 = ($rs_project_term_row['Amount']*$deel21);
							if($rs_project_term_row['Type_id']==3){
								$sub_21 = ($sub_21-$rs_project_term_row['Rest21']);
							}else{
								$sub_21 = ($sub_21-$rest21);
							}
							echo $rest21; ?>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td align="left">Rest6</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td><?php
							$rest6 = ($rs_project_term_row['Amount']*$deel6);
							if($rs_project_term_row['Type_id']==3){
								$sub_6 = ($sub_6-$rs_project_term_row['Rest6']);
							}else{
								$sub_6 = ($sub_6-$rest6);
							}
							echo $rest6; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<tr style="display:" class="fnr-<?php echo $i; ?>  <?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td align="left">Rest0</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<?php
							$rest0 = ($rs_project_term_row['Amount']*$deel0);
							if($rs_project_term_row['Type_id']==3){
								$sub_0 = ($sub_0-$rs_project_term_row['Rest0']);
							}else{
								$sub_0 = ($sub_0-$rest0);
							}
							echo $rest0; ?>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<?php } ?>
					<tr class="tbl-head">
						<td colspan="8" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
		  </div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php } ?>