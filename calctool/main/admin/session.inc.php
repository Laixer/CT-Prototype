<?php
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['sl_user'])){
	$user_id = mysql_real_escape_string($_POST['sl_user']);
	$_SESSION['SES_User_id'] = $user_id;
}

# All user query
$rs_user_all_result = mysql_query("SELECT * FROM tbl_user WHERE User_id > 0") or die("Error: " . mysql_error());
$rs_user_all_num = mysql_num_rows($rs_user_all_result);
?>
<div id="page-bgtop">
	<div id="content-main">
		<div id="intern">
			<div id="title">Sessie
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic">
				<div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="table">
				<form action="" method="post" name="session_chg" id="session_chg">
					<table width="33%">
						<tr>
							<td colspan="2" class="tbl-head">Overnemen</td>
						</tr>
						<tr>
							<td width="52%" class="tbl-subhead">Gebruiker</td>
							<td width="48%" align="right">
							<select name="sl_user" id="sl_user" style="width:99%">
								<?php while($rs_user_all_row = mysql_fetch_assoc($rs_user_all_result)){ ?>
								<option value="<?php echo $rs_user_all_row['User_id']; ?>"><?php echo $rs_user_all_row['Name']; ?></option>
								<?php } ?>
							</select>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left"><input type="submit" name="btn_submit" id="btn_submit" value="Overnemen"></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>