<?php
# Submited user data
$status_id = mysql_real_escape_string($_POST['sl_status']);

if($status_id){
	$ft_status_qry = sprintf("WHERE Status='%s'", $status_id);
}

if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_subject'])){
	$subject = mysql_real_escape_string($_POST['fld_subject']);
	$description = mysql_real_escape_string($_POST['fld_description']);

	if($subject && $description){
		$rs_add = sprintf("INSERT INTO tbl_backlog (Create_date, User_id, Subject, Description) VALUES (NOW(), '%s', '%s', '%s')", $user_id, $subject, $description);
		mysql_query($rs_add) or die("Error: " . mysql_error());
	}
}

# All backlog query
$rs_page_all_result = mysql_query("SELECT *, UNIX_TIMESTAMP(Timestamp_date) AS t_date FROM tbl_switchboard") or die("Error: " . mysql_error());
$rs_page_all_num = mysql_num_rows($rs_page_all_result);
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<span>Switchboard</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<form action="" method="post" name="backlog_add" id="backlog_add">
					<table width="33%">
						<tr>
							<td colspan="2" class="tbl-head">Nieuw item</td>
						</tr>
						<tr>
							<td width="56%" class="tbl-subhead">Onderwerp</td>
							<td width="44%" align="right"><input name="fld_subject" type="text" disabled id="fld_subject" maxlength="15"></td>
						</tr>
						<tr>
							<td valign="top" class="tbl-subhead">Omschrijving</td>
							<td align="right"><textarea name="fld_description" disabled id="fld_description"></textarea></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left"><input type="button" name="btn_submit" id="btn_submit" value="Invoeren"></td>
						</tr>
					</table>
				</form>
				<br>
				<table width="100%" border="0">
					<tr class="tbl-subhead">
						<td width="108">Pagina ID</td>
						<td width="212">Pagina</td>
						<td width="658">Pad</td>
						<td width="208">Broodkruimel</td>
					</tr>
					<?php $i=0; while($rs_page_all_row = mysql_fetch_assoc($rs_page_all_result)){ $i++; ?>
					<tr class="<?php if($i%2){ echo "tbl-odd"; }else{ echo "tbl-even"; } ?>">
						<td><?php echo $rs_page_all_row['Switchboard_id']; ?></td>
						<td><?php echo $rs_page_all_row['Page_title']; ?></td>
						<td><?php echo $rs_page_all_row['Page_url']; ?></td>
						<td><?php echo $rs_page_all_row['Breadcrumb_id']; ?></td>
					</tr>
					<?php } ?>
					<tr class="tbl-subhead">
						<td colspan="6" align="center"><a href="#">&lt;&lt;</a> [ 1 ] <a href="#">&gt;&gt;</a></td>
					</tr>
				</table>
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