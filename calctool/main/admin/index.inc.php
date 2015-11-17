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
		$rs_add = sprintf("INSERT INTO tbl_backlog (Create_date, Subject, Description) VALUES (NOW(), '%s', '%s')", $subject, $description);
		mysql_query($rs_add) or die("Error: " . mysql_error());
	}
}

# All backlog query
$rs_backlog_all_result = mysql_query("SELECT *, UNIX_TIMESTAMP(Timestamp_date) AS t_date FROM tbl_backlog ".$ft_status_qry." ORDER BY Status ASC, Create_date DESC") or die("Error: " . mysql_error());
$rs_backlog_all_num = mysql_num_rows($rs_backlog_all_result);
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">
				<span>Admin panel</span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<table width="100%" border="0">
					<tr class="tbl-subhead">
						<td width="146">Onderdeel</td>
						<td width="1048">Omschrijving</td>
					</tr>
					<tr class="tbl-odd">
						<td><a href="?p_id=1102">Gebruikers</a></td>
						<td>Beheer van gebruikers</td>
					</tr>
					<tr class="tbl-even">
						<td><a href="?p_id=1105">Sessie</a></td>
						<td>Sessie van gebruiker overnemen</td>
					</tr>
					<tr class="tbl-odd">
						<td><a href="?p_id=1103">Switchboard</a></td>
						<td>Beheer van pagina's en tooltips</td>
					</tr>
					<tr class="tbl-even">
						<td><a href="?p_id=1104">Tooltips</a></td>
						<td>Toevoegen of bewerken van tooltips</td>
					</tr>
					<tr class="tbl-odd">
						<td><a href="javascript:void(0)" onClick="window.open('/main/admin/serverinfo.pop.inc.php','','width=850,height=600,scrollbars=yes,toolbar=no,location=no'); return false">Server info</a></td>
						<td>Server en script informatie</td>
					</tr>
					<tr class="tbl-even">
						<td><a href="?p_id=1101">Backlog</a></td>
						<td>Toevoegen van backlog items</td>
					</tr>
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