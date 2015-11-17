<?php
# Submited user data
$project_id = mysql_real_escape_string($_GET['r_id']);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT * FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);


# Check project user
//$rs_project_total_qry = sprintf("SELECT SUM(Post) AS Total FROM tvw_quantities_mod_2 WHERE Project_id='%s'", $project_id);
//$rs_project_total_result = mysql_query($rs_project_total_qry) or die("Error: " . mysql_error());
//$rs_project_total_row = mysql_fetch_assoc($rs_project_total_result);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['btn_submit'])){
	$delivery = mysql_real_escape_string($_POST['slt_delivery']);
	$payment = mysql_real_escape_string($_POST['slt_payment']);
	$ending = mysql_real_escape_string($_POST['slt_ending']);
	$condition = mysql_real_escape_string($_POST['txt_condition']);
	$pretext = mysql_real_escape_string($_POST['txt_pretext']);
	$posttext = mysql_real_escape_string($_POST['txt_posttext']);
	$note = mysql_real_escape_string($_POST['txt_note']);
	
	if(!$delivery){
		$error_message = "Vul een levertijd in";
	}
	
	if(!$payment){
		$error_message = "Vul een betaling in";
	}
	
	if(!$ending){
		$error_message = "Vul een standdoening in";
	}

	if(!$error_message){
		$rs_add_offer = sprintf("INSERT INTO tbl_project_offer (Create_date, Project_id, Delivery, Payment, Ending, `Condition`, Pretext, Posttext, Note) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $rs_project_perm_check_row['Project_id'], $delivery, $payment, $ending, $condition, $pretext, $posttext, $note);
		mysql_query($rs_add_offer) or die("Error: " . mysql_error());

		$rs_project_module_check_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=6 LIMIT 1", $rs_project_perm_check_row['Project_id']);
		$rs_project_module_check_result = mysql_query($rs_project_module_check_qry) or die("Error: " . mysql_error());
		$rs_project_module_check_row = mysql_fetch_assoc($rs_project_module_check_result);
		$rs_project_module_check_num = mysql_num_rows($rs_project_module_check_result);
		if($rs_project_module_check_num){
			$rs_update_mod = sprintf("UPDATE tbl_project_module SET Module_timestamp_date=NOW() WHERE Project_module_id='%s'", $rs_project_module_check_row['Project_module_id']);
			mysql_query($rs_update_mod) or die("Error: " . mysql_error());
		}else{
			$rs_insert_mod = sprintf("INSERT INTO tbl_project_module (Project_id, Module_id, Module_start_date) VALUES ('%s', '6', NOW())", $rs_project_perm_check_row['Project_id']);
			mysql_query($rs_insert_mod) or die("Error: " . mysql_error());
		}

		$success_message = "Offerte aangemaakt";
	}

}

# Message info
if($success_message){ echo '<div class="success">'.$success_message.'</div>'; }
if($warn_message){ echo '<div class="warning">'.$warn_message.'</div>'; }
if($error_message){ echo '<div class="error">'.$error_message.'</div>'; }
?>
<script type="text/javascript">
function check_box(box){
	if($(box).attr("checked")){
		return true;
	}else{
		return false;
	}
}
function launch_pop(){
	url = '/maintoolv2/shoppinglist-mgr/?r_id=<?php echo $project_id; ?>&_utm=<?php echo $__url_session; ?>';
	url += '&1=' + check_box('#box_1');
	url += '&2=' + check_box('#box_2');
	url += '&3=' + check_box('#box_3');
	url += '&4=' + check_box('#box_4');
	url += '&5=' + check_box('#box_5');
	url += '&6=' + check_box('#box_6');
	url += '&7=' + check_box('#box_7');
	url += '&8=' + check_box('#box_8');
	url += '&note=' + $('#txt_note').val();
	
	window.open(url,'','width=900,height=600,scrollbars=yes,toolbar=no,location=no');
}
</script>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<div style="float: right; font-size: 20px;">
					<!--<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/offer-mgr/?r_id=<?php //echo $project_id; ?>','','width=1100,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Bekijk offerte" />-->
					<!--<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="window.open('/maintoolv2/pdf-mgr/?r_id=<?php //echo $project_id; ?>','','width=1100,height=600,scrollbars=yes,toolbar=no,location=no'); return false" type="button" value="Genereer PDF" />-->
					<input style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=130&r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&_utm=<?php echo $__url_session; ?>'" type="button" value="Terug naar financieel" />
				</div>
				<span>Boodschappenlijst</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic">
				<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<div class="details-head">Samenstelling</div>
				<form action="" method="post" id="frm_offer" name="frm_offer">
				<table width="100%">
					<tr>
						<td colspan="3">Opnemen in de boodschappenlijst</td>
						<td width="154">&nbsp;</td>
						<td width="494">&nbsp;</td>
					</tr>
					<tr>
						<td width="31"><label for="checkbox"></label></td>
						<td colspan="2">
							<b>Aaanneming</b></td>
						<td valign="top">Aantekening</td>
						<td rowspan="7" valign="top"><textarea style="width:90%;height:100%" name="txt_note" id="txt_note"></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td width="31"><input name="box_1" type="checkbox" id="box_1" checked="CHECKED"></td>
						<td width="452">Materiaal</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input name="box_2" type="checkbox" id="box_2" checked="CHECKED"></td>
						<td>Materieel</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2"><b>Onderaanneming</b></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="box_3" id="box_3"></td>
						<td>Materiaal</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="box_4" id="box_4"></td>
						<td>Materieel</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2"><b>Derden</b></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="box_5" id="box_5"></td>
						<td>Materiaal</td>
						<td>&nbsp;</td>
						<td rowspan="2" valign="top"><?php if($rs_project_total_row['Total']){ echo "U heeft totaalpost(en) toegepast in uw offerte, houdt rekening met uw aankopen voor deze posten daar deze niet in deze boodschappenlijst zijn opgenomen. Kijk hiervoor op de <u><a href='?p_id=116&r_id=".$project_id."&_utm=".$__url_session."'>uittrekstaat</a></u>."; } ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="box_6" id="box_6"></td>
						<td>Materieel</td>
						<td>&nbsp;</td>
						</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2"><b>Stelposten</b></td>
						<td>&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td><input type="checkbox" name="box_7" id="box_7"></td>
						<td>Materiaal</td>
						<td>&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td><input type="checkbox" name="box_8" id="box_8"></td>
						<td>Materieel</td>
						<td>&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">&nbsp;</td>
						<td colspan="2">&nbsp;</td>
						<td>&nbsp;</td>
						<td valign="top"><input type="button" name="btn_submit" id="btn_submit" onClick="launch_pop();" value="Genereer boodschappenlijst"></td>
					</tr>
				</table>
				</form>
				<br>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<?php
//mysql_free_result($rs_project_status_result);
//mysql_free_result($rs_project_type_result);
//mysql_free_result($rs_project_all_result);
?>