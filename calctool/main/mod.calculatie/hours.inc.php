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
$chapter_id = mysql_real_escape_string($_POST['slt_chapter']);

# Check project user
$rs_project_perm_check_qry = sprintf("SELECT Project_id FROM tbl_project WHERE Project_id='%s' AND User_id='%s' LIMIT 1", mysql_real_escape_string($_GET['r_id']), $user_id);
$rs_project_perm_check_result = mysql_query($rs_project_perm_check_qry) or die("Error: " . mysql_error());
$rs_project_perm_check_row = mysql_fetch_assoc($rs_project_perm_check_result);

# Project profit
$rs_profit_qry = sprintf("SELECT p.* FROM tbl_project_profit p JOIN tbl_project u ON u.Project_id=p.Project_id WHERE p.Project_id='%s' AND u.User_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
$rs_profit_row =  mysql_fetch_assoc(mysql_query($rs_profit_qry));

if($chapter_id){
	$ft_chap_qry = sprintf("AND o.Chapter_id='%s'", $chapter_id);
}

if(isset($_GET['del_id'])){
	$del_id = mysql_real_escape_string($_GET['del_id']);

	$rs_del_op_qry = sprintf("DELETE h.* FROM tbl_project_calc_hour AS h JOIN tbl_project AS p ON p.Project_id=h.Project_id WHERE h.Project_hour_id='%s' AND p.User_id='%s'", $del_id, $user_id);
	mysql_query($rs_del_op_qry) or die("Error: " . mysql_error());
	
	$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
	mysql_query($rs_update_module) or die("Error: " . mysql_error());
}

$rs_project_module7_qry = sprintf("SELECT * FROM tbl_project_module WHERE Project_id='%s' AND Module_id=7", $rs_project_perm_check_row['Project_id']);
$rs_project_module7_result = mysql_query($rs_project_module7_qry) or die("Error: " . mysql_error());
$rs_project_module7_row = mysql_fetch_assoc($rs_project_module7_result);

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_date'])){
	$date = (strtotime(mysql_real_escape_string($_POST['fld_date'])) + 3600);
	$hour = mysql_real_escape_string($_POST['fld_hour']);
	$option = mysql_real_escape_string($_POST['slt_option']);
	$tax_id = mysql_real_escape_string($_POST['slt_tax']);
	$operation_id = mysql_real_escape_string($_POST['slt_work']);
	$comment = mysql_real_escape_string($_POST['fld_comment']);
	$hour = str_replace(',', '.', $hour);
	
	if($date == 3600){
		$error_message = "Vul een geldige datum in";
	}
	
	if(!$hour){
		$error_message = "Vul uren in";
	}
	
	if(!$error_message){
		# Check if operation is part of this project
		$rs_project_op_check_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $operation_id, $user_id);
		$rs_project_op_check_result = mysql_query($rs_project_op_check_qry) or die("Error: " . mysql_error());
		if(mysql_num_rows($rs_project_op_check_result)){
			# Check this total
			$rs_project_total_check_qry = sprintf("SELECT *, SUM(Amount) AS This_total FROM tbl_project_calc_hour WHERE Project_id='%s' AND Operation_id='%s' GROUP BY Operation_id LIMIT 1", $rs_project_perm_check_row['Project_id'], $operation_id);
			$rs_project_total_check_result = mysql_query($rs_project_total_check_qry) or die("Error: " . mysql_error());
			$rs_project_total_check_row = mysql_fetch_assoc($rs_project_total_check_result);
		
			# Check less
			$rs_project_less_check_qry = sprintf("SELECT Amount from tbl_project_calc_frth_salary WHERE project_id='%s' AND invoice_id=10 AND operation_id='%s' LIMIT 1", $rs_project_perm_check_row['Project_id'], $operation_id);
			$rs_project_less_check_result = mysql_query($rs_project_less_check_qry) or die("Error: " . mysql_error());
			$rs_project_less_check_row = mysql_fetch_assoc($rs_project_less_check_result);

			if($option != 1 && !$rs_project_module7_row){
				$option = 1;
			}

			if($option == 1){
				$rs_calc_sal_tax_qry = sprintf("SELECT tax_id FROM tbl_project_calc_salary WHERE project_id=%d AND operation_id=%d AND invoice_id=10 LIMIT 1", $rs_project_perm_check_row['Project_id'], $operation_id);
				$rs_calc_sal_tax_row =  mysql_fetch_array(mysql_query($rs_calc_sal_tax_qry));
				$tax_id = $rs_calc_sal_tax_row[0];
			}
			
			if($option == 3){
				$rs_calc_sal_tax_qry = sprintf("SELECT tax_id FROM tbl_project_calc_salary WHERE project_id=%d AND operation_id=%d AND invoice_id=40 LIMIT 1", $rs_project_perm_check_row['Project_id'], $operation_id);
				$rs_calc_sal_tax_row =  mysql_fetch_array(mysql_query($rs_calc_sal_tax_qry));
				$tax_id = $rs_calc_sal_tax_row[0];
			}
			
			if((($rs_project_total_check_row['This_total']+$hour) > $rs_project_less_check_row['Amount']) && ($option == 1)){
				$warn_message = "U heeft een overschrijding op post arbeid";
			}
			$rs_add_hour = sprintf("INSERT INTO tbl_project_calc_hour (Create_date, Project_id, Operation_id, Tax_id, Date, Amount, More_work, Comment) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $rs_project_perm_check_row['Project_id'], $operation_id, $tax_id, $date, $hour, $option, $comment);
			mysql_query($rs_add_hour) or die("Error: " . mysql_error());

			// MW
			if($option == 2){
				$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_third_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
				$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));

				$rs_last_hour_qry = sprintf("SELECT Project_hour_id FROM tbl_project_calc_hour WHERE Project_id='%s' ORDER BY Project_hour_id DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
				$rs_last_hour_row =  mysql_fetch_assoc(mysql_query($rs_last_hour_qry));
				
				$rs_add_salary = sprintf("INSERT INTO tbl_project_calc_third_salary (Create_date, Hour_id, Project_id, Invoice_id, Operation_id, Tax_id, Date, Price, Amount, Priority, Comment) VALUES (NOW(), '%s', '%s', '60', '%s', '%s', FROM_UNIXTIME('%s'), '%s', '%s', '%s', '%s')", $rs_last_hour_row['Project_hour_id'], $rs_project_perm_check_row['Project_id'], $operation_id, $tax_id, $date, $rs_profit_row['Hour_salary_sec'], $hour, ($rs_prio_cm_row['Priority']+1), $comment);
				mysql_query($rs_add_salary) or die("Error: " . mysql_error());
			}
			
			// SP
			if($option == 3){
				
				$rs_prio_cm_qry = sprintf("SELECT cm.Priority FROM tbl_project_calc_sec_salary AS cm JOIN tbl_project AS p ON p.Project_id=cm.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $rs_project_perm_check_row['Project_id'], $user_id);
				$rs_prio_cm_row =  mysql_fetch_assoc(mysql_query($rs_prio_cm_qry));
				
				$rs_check_price_qry = sprintf("SELECT Price FROM tbl_project_calc_salary WHERE project_id=%d AND Invoice_id=40 AND operation_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id'], $operation_id);
				$rs_check_price_row = mysql_fetch_assoc(mysql_query($rs_check_price_qry));
				
				if(!empty($rs_check_price_row['Price'])){
					$price = $rs_check_price_row['Price'];
				}else{
					$price = $rs_profit_row['Hour_salary'];
				}

				$rs_last_hour_qry = sprintf("SELECT Project_hour_id FROM tbl_project_calc_hour WHERE Project_id='%s' ORDER BY Project_hour_id DESC LIMIT 1", $rs_project_perm_check_row['Project_id']);
				$rs_last_hour_row =  mysql_fetch_assoc(mysql_query($rs_last_hour_qry));
				
				$rs_add_salary = sprintf("INSERT INTO tbl_project_calc_sec_salary (Create_date, Hour_id, Project_id, Invoice_id, Operation_id, Tax_id, Date, Price, Amount, Priority, Comment) VALUES (NOW(), '%s', '%s', '40', '%s', '%s', FROM_UNIXTIME('%s'), '%s', '%s', '%s', '%s')", $rs_last_hour_row['Project_hour_id'], $rs_project_perm_check_row['Project_id'], $operation_id, $tax_id, $date, $price, $hour, ($rs_prio_cm_row['Priority']+1), $comment);
				mysql_query($rs_add_salary) or die("Error: " . mysql_error());
			}
			
			$rs_update_module = sprintf("UPDATE tbl_project_module AS m INNER JOIN tbl_project AS p ON p.Project_id=m.Project_id SET m.Module_timestamp_date=NOW() WHERE m.Project_id='%s' AND p.User_id='%s' AND m.Module_id=5", $rs_project_perm_check_row['Project_id'], $user_id);
			mysql_query($rs_update_module) or die("Error: " . mysql_error());
		}else{
			$error_message = "Deze werkzaamheid is geen onderdeel van dit project";
		}
	}
}

# All hours query
$rs_hours_qry = sprintf("SELECT h.Project_hour_id, h.Date, h.Amount, h.More_work, h.Comment, o.Operation, c.Chapter, t.Tax FROM tbl_project_calc_hour AS h JOIN tbl_tax AS t ON t.Tax_id=h.Tax_id JOIN tbl_project_operation AS o ON o.Project_operation_id=h.Operation_id JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=h.Project_id WHERE h.Project_id='%s' AND p.User_id='%s' %s ORDER BY Date DESC", $rs_project_perm_check_row['Project_id'], $user_id, $ft_chap_qry);
$rs_hours_result = mysql_query($rs_hours_qry) or die("Error: " . mysql_error());
$rs_hours_num = mysql_num_rows($rs_hours_result);

# All tax
$rs_tax_result = mysql_query("SELECT * FROM tbl_tax") or die("Error: " . mysql_error());

# Hour totals
$rs_hours_total_qry = sprintf("SELECT SUM(Amount) AS Total FROM tbl_project_calc_hour WHERE Project_id=%d LIMIT 1", $rs_project_perm_check_row['Project_id']);
$rs_hours_total_result = mysql_query($rs_hours_total_qry) or die("Error: " . mysql_error());
$rs_hours_total_row = mysql_fetch_assoc($rs_hours_total_result);

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
$(function(){
	$( "#fld_date" ).datepicker({ dateFormat: "dd-mm-yy", dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ], monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ] });
	$('#slt_tax').prop('disabled', true);
});

function chg_op(val){
	if(val==1){
		$('.ha').show();
		$('.sp').hide();
		$('.mw').hide();
		$('#slt_tax').prop('disabled', true);
	}else if(val==2){
		$('.ha').hide();
		$('.sp').hide();
		$('.mw').show();
		$('#slt_tax').prop('disabled', false);
	}else if(val==3){
		$('.ha').hide();
		$('.mw').hide();
		$('.sp').show();
		$('#slt_tax').prop('disabled', true);
	}
}

$(document).ready(function(){
	$('.sp').hide();
	$('.mw').hide();
});
</script>
<div id="page-bgtop">
	<div id="title">
		<div style="float:right">
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=130&amp;r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&amp;_utm=<?php echo $__url_session; ?>'" value="Terug naar Financieel" />
			<input name="" type="button" style="height: 24px; background: #FFF url('../images/add.png') no-repeat top left; padding-left: 20px; background-size: 16px; background-position-x: 2px; background-position-y: 2px;" onclick="document.location='?p_id=148&amp;r_id=<?php echo $rs_project_perm_check_row['Project_id']; ?>&amp;_utm=<?php echo $__url_session; ?>'" value="Uittrekstaat Urenregistratie" />
		</div>
		<span>Urenregistratie</span>
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
						<td colspan="2">Uren toevoegen</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="17"><div align="center"></div></td>
						<td width="150"><div align="center">Datum</div></td>
						<td width="144"><div align="center">Uren</div></td>
						<td width="190"><div align="center">Behorende bij</div></td>
						<td width="175"><div align="center">BTW</div></td>
						<td width="224"><div align="center">Werkzaamheden</div></td>
						<td width="274"><div align="center">Opmerking</div></td>
					</tr>
					<form action="" method="post" name="frm_hour_add" id="frm_hour_add">
						<tr class="tbl-even">
							<td><a href="#" onclick="document.frm_hour_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
							<td><input style="width: 99%;" type="text" name="fld_date" id="fld_date" /></td>												
							<td><input style="width: 99%;" type="text" name="fld_hour" id="fld_hour" /></td>
							<td>
								<select name="slt_option" id="slt_option" style="width: 99%;" onChange="chg_op(this.value)">
									<option value="1">Hoofdaanneming</option>
									<?php if($rs_project_module7_row){ ?>
									<option value="2">Meerwerk op basis van regie</option>
									<option value="3">Stelpost</option>
									<?php } ?>
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
								<select name="slt_work" id="slt_work" style="width: 99%;">
								<?php
									while($rs_project_chap2_row = mysql_fetch_assoc($rs_project_chap2_result)){
										$rs_project_op_ha_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=10 ORDER BY Priority ASC", $rs_project_chap2_row['Project_chapter_id']);
										$rs_project_op_ha_result = mysql_query($rs_project_op_ha_qry) or die("Error: " . mysql_error());
										$rs_project_op_ha_num = mysql_num_rows($rs_project_op_ha_result);

										$rs_project_op_sp_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=40 ORDER BY Priority ASC", $rs_project_chap2_row['Project_chapter_id']);
										$rs_project_op_sp_result = mysql_query($rs_project_op_sp_qry) or die("Error: " . mysql_error());
										$rs_project_op_sp_num = mysql_num_rows($rs_project_op_sp_result);
										
										$rs_project_op_mw_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' AND Invoice_id=60 ORDER BY Priority ASC", $rs_project_chap2_row['Project_chapter_id']);
										$rs_project_op_mw_result = mysql_query($rs_project_op_mw_qry) or die("Error: " . mysql_error());
										$rs_project_op_mw_num = mysql_num_rows($rs_project_op_mw_result);

										if($rs_project_op_ha_num && (!$rs_project_op_sp_num) && (!$rs_project_op_mw_num)){
											$class = 'ha';
										}
										if($rs_project_op_sp_num && (!$rs_project_op_ha_num) && (!$rs_project_op_mw_num)){
											$class = 'sp';
										}
										if($rs_project_op_mw_num && (!$rs_project_op_ha_num) && (!$rs_project_op_sp_num)){
											$class = 'mw';
										}

										if($rs_project_op_ha_num || $rs_project_op_sp_num || $rs_project_op_mw_num){
											echo '<optgroup class="'.$class.'" label="'.$rs_project_chap2_row['Chapter'].'">';
											while($rs_project_op_ha_row = mysql_fetch_assoc($rs_project_op_ha_result)){
												echo '<option class="ha" value="' . $rs_project_op_ha_row['Project_operation_id'] . '">&raquo;&nbsp;' . $rs_project_op_ha_row['Operation'] . '</option>';
											}
											while($rs_project_op_sp_row = mysql_fetch_assoc($rs_project_op_sp_result)){
												echo '<option class="sp" value="' . $rs_project_op_sp_row['Project_operation_id'] . '">&raquo;&nbsp;' . $rs_project_op_sp_row['Operation'] . '</option>';
											}
											while($rs_project_op_mw_row = mysql_fetch_assoc($rs_project_op_mw_result)){
												echo '<option class="mw" value="' . $rs_project_op_mw_row['Project_operation_id'] . '">&raquo;&nbsp;' . $rs_project_op_mw_row['Operation'] . '</option>';
											}
											echo '</optgroup>';
										}
									}
								?>
								</select>
							</td>
							<td><input style="width: 99%;" type="text" name="fld_comment" id="fld_comment" /></td>
						</tr>
					</form>
				</table>
			</div>
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-head">
						<td colspan="2">Filter hoofdstuk</td>
						<td>
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
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="tbl-subhead">
						<td width="17">&nbsp;</td>
						<td width="152"><div align="center">Datum</div></td>
						<td width="66"><div align="center">Uren</div></td>
						<td width="199"><div align="center">Behorende bij</div></td>
						<td width="73"><div align="center">BTW</div></td>
						<td width="168"><div align="center">Hoofdstuk</div></td>
						<td width="223"><div align="center">Werkzaamheden</div></td>
						<td width="272"><div align="center">Opmerking</div></td>
					</tr>
					<?php $i=0; while($rs_hours_row = mysql_fetch_assoc($rs_hours_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><a href="?p_id=<?php echo $_GET['p_id']; ?>&r_id=<?php echo $_GET['r_id']; ?>&del_id=<?php echo $rs_hours_row['Project_hour_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						<td><div align="center"><?php echo date("d-m-Y", $rs_hours_row['Date']); ?></div></td>
						<td><div align="center"><?php echo $rs_hours_row['Amount']; ?></div></td>
						<td><?php if($rs_hours_row['More_work'] == 1){ echo "Hoofdaanneming"; }else if($rs_hours_row['More_work'] == 2){ echo "Meerwerk op basis van regie"; }else{ echo "Stelpost"; } ?></td>
						<td><div align="center"><?php echo $rs_hours_row['Tax']; ?>%</div></td>
						<td><?php echo $rs_hours_row['Chapter']; ?></td>
						<td><?php if($rs_hours_row['Placeholder'] == 'Y'){ echo "Alle bijbehorende werkzaamheden"; }else{ echo $rs_hours_row['Operation']; } ?></td>
						<td><?php echo $rs_hours_row['Comment']; ?></td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td width="17">&nbsp;</td>
						<td width="152">Totaal</td>
						<td width="66"><div align="center"><?php echo $rs_hours_total_row['Total']; ?></div></td>
						<td width="199">&nbsp;</td>
						<td width="73">&nbsp;</td>
						<td width="168">&nbsp;</td>
						<td width="223">&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
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
<?php  
mysql_free_result($rs_project_chap2_result);
mysql_free_result($rs_project_chap_result);
mysql_free_result($rs_hours_total_result);
mysql_free_result($rs_tax_result);
mysql_free_result($rs_hours_result);
?>