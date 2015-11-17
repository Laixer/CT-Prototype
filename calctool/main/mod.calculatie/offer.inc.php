<?php
# Submited user data
$project_id = mysql_real_escape_string($_GET['r_id']);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

$rs_project_result_qry = sprintf("SELECT * FROM tvw_invoice_result WHERE project_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_result_result = mysql_query($rs_project_result_qry);
$rs_project_result_row = mysql_fetch_assoc($rs_project_result_result);

$amount_total = (
	$rs_project_result_row['Calc_21']+
	$rs_project_result_row['Calc_6']+
	$rs_project_result_row['Calc_0']+
	$rs_project_result_row['Post_old_21']+
	$rs_project_result_row['Post_old_6']+
	$rs_project_result_row['Post_old_0']
);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$specification = mysql_real_escape_string($_POST['slt_type']);
	$tax = mysql_real_escape_string($_POST['box_tax']);
	$delivery = mysql_real_escape_string($_POST['slt_delivery']);
	$ending = mysql_real_escape_string($_POST['slt_ending']);
	$offer_type = mysql_real_escape_string($_POST['offer_type']);
	$note = mysql_real_escape_string($_POST['txt_note']);
	$posttext = mysql_real_escape_string($_POST['txt_posttext']);

	if($offer_type == 1){
		$i_p = 0;
		$total_p = 0;
		while(isset($_POST['percent'][$i_p])){
			$total_p += mysql_real_escape_string($_POST['percent'][$i_p]);
			$i_p++;
		}
		
		$i_a=0;
		$total_a = 0;
		while(isset($_POST['amount'][$i_a])){
			$total_a += mysql_real_escape_string($_POST['amount'][$i_a]);
			$i_a++;
		}
		$total_a += mysql_real_escape_string($_POST['amount_res']);
		
/*		if($offer_type == 1){
			if($total_p != 100){
				$error_message = "Termijn percentage moet op 100% uitkomen, er ontbreekt nog ".(100-$total_p)."%";
			}
		}
*/		
		if($offer_type == 1){
			if(round($total_a, 2) != round($amount_total, 2)){
				$error_message = "Termijn bedrag moet overeenkomen met calculatie, er ontbreekt nog ".($amount_total-$total_a). " opgegeven ".$total_a;
			}
		}
	}

	if($tax == 'on'){
		$tax = 'Y';
	}else{
		$tax = 'N';
	}
	
	if($box_description == 'on'){
		$box_description = 'Y';
	}else{
		$box_description = 'N';
	}

	if($offer_type == '1'){
		$offer_type = "Term";
	}else{
		$offer_type = "End";
	}
		

	if(!$error_message){
		$rs_add_offer = sprintf("INSERT INTO tbl_project_offer (Create_date, Specification, Project_id, Deliver_time, Lifetime, Tax, Type, Foot, Note) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $specification, $rs_project_perm_check_row['Project_id'], $delivery, $ending, $tax, $offer_type, $posttext, $note);
		mysql_query($rs_add_offer) or die("Error: " . mysql_error());

		$rs_select_offer = sprintf("SELECT * FROM tbl_project_offer WHERE Project_id='%s' ORDER BY create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
		$rs_select_offer_row = mysql_fetch_assoc(mysql_query($rs_select_offer));

		if($offer_type == "Term"){
			$i_a=0;
			while(isset($_POST['amount'][$i_a])){
				$rs_add_term = sprintf("INSERT INTO tbl_project_term (Create_date, Offer_id, Amount, Priority) VALUES (NOW(), %d, %f, %d)", $rs_select_offer_row['Offer_id'], mysql_real_escape_string($_POST['amount'][$i_a]), ($i_a+1));
				mysql_query($rs_add_term) or die("Error: " . mysql_error());
				$i_a++;
			}
			$rs_add_term = sprintf("INSERT INTO tbl_project_term (Create_date, Offer_id, Amount, Close, Priority) VALUES (NOW(), %d, %f, 'Y', %d)", $rs_select_offer_row['Offer_id'], mysql_real_escape_string($_POST['amount_res']), ($i_a+1));
			mysql_query($rs_add_term) or die("Error: " . mysql_error());
		}else{
			$rs_add_term = sprintf("INSERT INTO tbl_project_term (Create_date, Offer_id, Amount, Close) VALUES (NOW(), %d, %f, 'Y')", $rs_select_offer_row['Offer_id'], $amount_total);
			mysql_query($rs_add_term) or die("Error: " . mysql_error());
		}
		$success_message = "Offerte aangemaakt. <a href=\"/main/mod.calculatie/offer_total.inc.php/?p_id=151&r_id=".$rs_project_perm_check_row['Project_id']."&_utm=".$__url_session."\">Klik hier om hem te bekijken</a>";
	}

}

$rs_project_offer_exist_qry = sprintf("SELECT * FROM tbl_project_offer WHERE project_id=%d ORDER BY Create_date DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_project_offer_exist_result = mysql_query($rs_project_offer_exist_qry) or die("Error: " . mysql_error());
$rs_project_offer_exist_row = mysql_fetch_assoc($rs_project_offer_exist_result);

if($rs_project_offer_exist_row['Offer_id']){
	$rs_project_term_exist_qry = sprintf("SELECT * FROM tbl_project_term WHERE offer_id=%d ORDER BY Priority ASC", $rs_project_offer_exist_row['Offer_id']);
	$rs_project_term_exist_result = mysql_query($rs_project_term_exist_qry) or die("Error: " . mysql_error());
	$rs_project_term_exist_num = mysql_num_rows($rs_project_term_exist_result);
}

# All relations query
$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $rs_project_perm_check_row['Project_id']);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

if($rs_project_module7_row){
	$error_message = "De offerte is al bevestigd. <a href=\"/main/mod.calculatie/offer_total.inc.php/?p_id=151&r_id=".$rs_project_perm_check_row['Project_id']."&_utm=".$__url_session."\" style=\"color:#D8000C\">Klik hier om de offerte te bekijken</a>";
}

# All project status query
$rs_project_status_result = mysql_query("SELECT * FROM tbl_project_status") or die("Error: " . mysql_error());

# All project type query
$rs_project_type_result = mysql_query("SELECT * FROM tbl_project_type") or die("Error: " . mysql_error());

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }

if(!$rs_project_module7_row){
?>
<script type="text/javascript">
$(document).ready(function(){
	var total = <?php echo $amount_total; ?>;
    function calc_amount(el){
		var p = el.val();
		if($.isNumeric(p)&&(p>=0)&&(p<=total)){
			var i = ((total/100)*p).toFixed(2);
			var c1 = el.parent().parent().find('.acalc').val();
			el.parent().parent().find('.acalc').val(i);
			if(!calc_total()){
				el.parent().parent().find('.acalc').val(c1);
			}
		}
	}
	function calc_total(){
		var tpercent = 0;
		var tamount = 0;
		$('.pcalc').each(function(index, element) {
			var t1 = parseFloat(element.value);
			if(t1){
				tpercent += t1;
			}
		});
		$('.acalc').each(function(index, element) {
			var t2 = parseFloat(element.value);
			if(t2){
				tamount += t2;
			}
		});
		
		if(0>tpercent||tpercent>100||total<tamount||0>tamount){
			return false;
		}else{
			$('#percent_res').val(100-tpercent);
			$('#amount_res').val(total-tamount);

			return true;
		}
	}
	$('#terms').blur(function(){
		var q = $(this).val();
		if($.isNumeric(q)&&(q>1)&&(q<=50)){
			$('#tbl-term .lst').remove();
			for(var i=0; i<q; i++){
				if(i%2 == 0){
					var cls = "tbl-odd";
				}else{
					var cls = "tbl-even";
				}
				if(i==(q-1)){
					$('#tbl-term tr:last').before('<tr class="'+cls+' lst"><td>Slottermijn</td><td><input readonly id="amount_res" name="amount_res" style="width:100%;" type="text"/></td></tr>');
				}else{
					$('#tbl-term tr:last').before('<tr class="'+cls+' lst"><td>'+(i+1)+'</td><td><input class="acalc" id="amount_'+i+'" name="amount['+i+']" style="width:100%;" type="text"/></td></tr>');
				}
			}
			$('.acalc').blur(function(){
				calc_total();
			});
		}
	});
	$('.acalc').blur(function(){
		calc_total();
	});
});
</script>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<?php 
			//if(!$rs_project_module7_row){
				if(1){ ?>
			<div id="title">
				<div style="float: right; font-size: 20px;">
					<!--<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/offer-mgr/?r_id=<?php //echo $project_id; ?>','','width=1100,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Bekijk offerte" />-->
					<!--<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/pdf-mgr/?r_id=<?php //echo $project_id; ?>','','width=1100,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Genereer PDF" />-->
				</div>
				<span>Offerte Beheer</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic">
				<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<div class="details-head">Bepalingen</div>
				<form action="" method="post" id="frm_offer" name="frm_offer">
				<table width="100%">
					<tr>
						<td width="144">&nbsp;</td>
						<td colspan="2">&nbsp;</td>
						<td width="138">&nbsp;</td>
						<td width="463">&nbsp;</td>
					</tr>
					<tr>
						<td>Specificatie offerte</td>
						<td colspan="2"><select style="width: 250px" name="slt_type" id="slt_type">
							<option value="1">Gespecificeerd, excl omschrijving</option>
							<option disabled="disabled" value="2">Gespecificeerd, incl omschrijving</option>
							<option disabled="disabled" value="3">Totalen per hoofdstuk</option>
							<option disabled="disabled" value="4">Totalen per werkzaamheid</option>
							<option disabled="disabled" value="5">Totaal voor project</option>
						</select></td>
						<td valign="top">Omschrijving</td>
						<td rowspan="3" valign="top"><textarea style="width:90%;height:100%" name="txt_note" id="txt_note"><?php echo $rs_project_offer_exist_row['Note']; ?></textarea></td>
					</tr>
					<tr>
						<td>BTW weergeven</td>
						<td colspan="2"><input name="box_tax" type="checkbox" id="box_tax" <?php if($rs_project_offer_exist_row['Tax']=='N'){ echo ''; }else{?> checked="CHECKED"<?php } ?>>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Levertijd</td>
						<td colspan="2"><select style="width: 250px" name="slt_delivery" id="slt_delivery">
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==31){ echo 'selected="selected"'; } ?> value="31">in overleg</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==32){ echo 'selected="selected"'; } ?> value="32">direct</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==1){ echo 'selected="selected"'; } ?> value="1">binnen 1 dag na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==2){ echo 'selected="selected"'; } ?> value="2">binnen 2 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==3){ echo 'selected="selected"'; } ?> value="3">binnen 3 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==5){ echo 'selected="selected"'; } ?> value="5">binnen 5 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==7){ echo 'selected="selected"'; } ?> value="7">binnen 7 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==10){ echo 'selected="selected"'; } ?> value="10">binnen 10 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==14){ echo 'selected="selected"'; } ?> value="14">binnen 14 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==15){ echo 'selected="selected"'; } ?> value="15">binnen 15 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==20){ echo 'selected="selected"'; } ?> value="20">binnen 20 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==21){ echo 'selected="selected"'; } ?> value="21">binnen 21 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==28){ echo 'selected="selected"'; } ?> value="28">binnen 28 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Deliver_time']==30){ echo 'selected="selected"'; } ?> value="30">binnen 30 dagen na dagtekening offerte</option>
							</select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">Geldigheid offerte</td>
						<td colspan="2"><select style="width: 250px" name="slt_ending" id="slt_ending">
							<option <?php if($rs_project_offer_exist_row['Lifetime']==91){ echo 'selected="selected"'; } ?> value="91">in overleg</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==7){ echo 'selected="selected"'; } ?> value="7">tot 7 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==14){ echo 'selected="selected"'; } ?> value="14">tot 14 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==21){ echo 'selected="selected"'; } ?> value="21">tot 21 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==28){ echo 'selected="selected"'; } ?> value="28">tot 28 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==30){ echo 'selected="selected"'; } ?> value="30">tot 30 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==60){ echo 'selected="selected"'; } ?> value="60">tot 60 dagen na dagtekening offerte</option>
							<option <?php if($rs_project_offer_exist_row['Lifetime']==90){ echo 'selected="selected"'; } ?> value="90">tot 90 dagen na dagtekening offerte</option>
							</select></td>
						<td valign="top">Afsluiting</td>
						<td rowspan="4" valign="top"><textarea style="width:90%;height:100%" name="txt_posttext" id="txt_posttext"><?php echo $rs_project_offer_exist_row['Foot']; ?></textarea></td>
					</tr>
					<tr>
						<td valign="top">Factuurtype</td>
						<td colspan="2">
							<input type="radio" name="offer_type" value="1" onClick="$('.term').show();" <?php if($rs_project_offer_exist_row['Type']=='Term'){ echo 'checked'; } ?>> Termijn
							<input type="radio" name="offer_type" value="2" onClick="$('.term').hide();" <?php if($rs_project_offer_exist_row['Type']=='Term'){ echo ''; }else{ echo 'checked'; } ?>> Eindfactuur
						</td>
						<td valign="top">&nbsp;</td>
						</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td><span style="<?php if($rs_project_offer_exist_row['Type']=='Term'){ echo ''; }else{ echo 'display:none'; } ?>" class="term">Aantal termijnen</span>&nbsp;</td>
						<td><input style="<?php if($rs_project_offer_exist_row['Type']=='Term'){ echo ''; }else{ echo 'display:none'; } ?>;width:100px;" class="term" id="terms" name="terms" type="text" value="<?php echo $rs_project_term_exist_num; ?>" />&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td width="137">&nbsp;</td>
						<td width="145">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td rowspan="3" valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td colspan="2">&nbsp;</td>
						<td>&nbsp;</td>
						</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td colspan="2">&nbsp;</td>
						<td>&nbsp;</td>
						</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td colspan="2">&nbsp;</td>
						<td>&nbsp;</td>
						<td valign="top"><input type="submit" name="btn_submit" id="btn_submit" value="Maak offerte">
							<?php if($rs_project_offer_exist_row['Offer_id']){ ?>
							<input style="height: 24px;" onclick="document.location='/main/mod.calculatie/offer_total.inc.php/?p_id=151&r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Bekijk laatse offerte" />
							<?php } ?></td>
					</tr>
				</table>
				<div class="details-head term" style="<?php if($rs_project_offer_exist_row['Type']=='Term'){ echo ''; }else{ echo 'display:none'; } ?>">Termijnen</div>
				<table id="tbl-term" class="term" style="<?php if($rs_project_offer_exist_row['Type']=='Term'){ echo ''; }else{ echo 'display:none'; } ?>" width="50%" border="0">
					<tr class="tbl-subhead">
						<td width="178">Termijnnummer</td>
						<td width="269">Bedrag</td>
					</tr>
					<?php
					if($rs_project_offer_exist_row['Offer_id']){
						$q=0; while($rs_project_term_exist_row = mysql_fetch_assoc($rs_project_term_exist_result)){
						if($q%2 == 0){
							$cls = "tbl-odd";
						}else{
							$cls = "tbl-even";
						}
						if($rs_project_term_exist_num==($q+1)){							
							echo '<tr class="'.$cls.' lst"><td>Slottermijn</td><td><input readonly id="amount_res" name="amount_res" style="width:100%;" type="text" value="'.$rs_project_term_exist_row['Amount'].'" /></td></tr>';
						}else{
							echo '<tr class="'.$cls.' lst"><td>'.($q+1).'</td><td><input class="acalc" id="amount_'.$q.'" name="amount['.$q.']" style="width:100%;" type="text" value="'.$rs_project_term_exist_row['Amount'].'" /></td></tr>';
						}
						$q++;
					} } ?>
					<tr class="tbl-subhead">
						<td colspan="3" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
				</form>
                <!--
				<br>
				<div class="details-head">Versies</div>
				<table width="100%" border="0">
					<tr class="tbl-subhead">
						<td width="178">Offertenummer</td>
						<td width="265">Aangemaakt</td>
						<td width="269">Offertebedrag (excl. BTW)</td>
						<td width="269">Status</td>
						<td width="269">Download</td>
					</tr>
					<?php //if($rs_project_offer_num){ ?>
					<?php //$i=0; while($rs_project_offer_row = mysql_fetch_assoc($rs_project_offer_result)){ $i++; ?>
					<tr class="<?php //if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php //echo $rs_project_offer_row['Project_offer_id']; ?></td>
						<td><?php //echo $rs_project_offer_row['Create_date']; ?></td>
						<td>&nbsp;</td>
						<td><?php //if($i == 1){ echo "Open"; }else{ echo "Vervallen"; } ?></td>
						<td><a href="javascript:void(0)" onClick="window.open('/maintoolv2/offer-mgr/?r_id=<?php echo $project_id; ?>&o_id=<?php echo $rs_project_offer_row['Project_offer_id']; ?>','','width=1100,height=600,scrollbars=yes,toolbar=no,location=no'); return false">Bekijk</a></td>
					</tr>
					<?php //} ?>
					<?php //}else{ ?>
					<tr>
						<td colspan="5" align="center">Geen offertes gevonden</td>
					</tr>
					<?php //} ?>
					<tr class="tbl-subhead">
						<td colspan="5" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
                -->
			</div>
			<?php } ?>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php
}
mysql_free_result($rs_project_status_result);
mysql_free_result($rs_project_type_result);
?>