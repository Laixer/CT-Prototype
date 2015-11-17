<?php
#cleanup todo

# Includes
include_once("../../../private_html/conn_db_common.php");
include_once("../../inc/restrict_login.php");

# Project data id
$project_id = mysql_real_escape_string($_GET['r_id']);

# Move operation up
if(isset($_GET['up_op_id'])){
	$up_op_id = mysql_real_escape_string($_GET['up_op_id']);

	$rs_up_this_op_qry = sprintf("SELECT o.* FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $up_op_id, $user_id);
	$rs_up_this_op_row = mysql_fetch_assoc(mysql_query($rs_up_this_op_qry));
	$chapter = $rs_up_this_op_row['Chapter_id'];

	$rs_up_op_qry = sprintf("SELECT opr.* FROM tbl_project_operation AS opr JOIN tbl_project_operation AS slct ON slct.Chapter_id=opr.Chapter_id WHERE slct.Project_operation_id='%s' AND opr.Priority < slct.Priority ORDER BY opr.Priority DESC LIMIT 1", $rs_up_this_op_row['Project_operation_id']);
	$rs_up_op_row =  mysql_fetch_assoc(mysql_query($rs_up_op_qry));
	if($rs_up_op_row){
		$rs_up_prev_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_up_this_op_row['Priority'], $rs_up_op_row['Project_operation_id']);
		mysql_query($rs_up_prev_op_qry);
	
		$rs_up_next_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_up_op_row['Priority'], $rs_up_this_op_row['Project_operation_id']);
		mysql_query($rs_up_next_op_qry);
	}

}

# Move operation down
if(isset($_GET['down_op_id'])){
	$down_op_id = mysql_real_escape_string($_GET['down_op_id']);

	$rs_down_this_op_qry = sprintf("SELECT o.* FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s' LIMIT 1", $down_op_id, $user_id);
	$rs_down_this_op_row = mysql_fetch_assoc(mysql_query($rs_down_this_op_qry));
	$chapter = $rs_down_this_op_row['Chapter_id'];

	$rs_down_op_qry = sprintf("SELECT opr.* FROM tbl_project_operation AS opr JOIN tbl_project_operation AS slct ON slct.Chapter_id=opr.Chapter_id WHERE slct.Project_operation_id='%s' AND opr.Priority > slct.Priority ORDER BY opr.Priority ASC LIMIT 1", $rs_down_this_op_row['Project_operation_id']);
	$rs_down_op_row =  mysql_fetch_assoc(mysql_query($rs_down_op_qry));
	if($rs_down_op_row){
		$rs_down_prev_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_down_this_op_row['Priority'], $rs_down_op_row['Project_operation_id']);
		mysql_query($rs_down_prev_op_qry);
	
		$rs_down_next_op_qry = sprintf("UPDATE tbl_project_operation SET Priority='%s' WHERE Project_operation_id='%s'", $rs_down_op_row['Priority'], $rs_down_this_op_row['Project_operation_id']);
		mysql_query($rs_down_next_op_qry);
	}

}

# Move chapter up
if(isset($_GET['up_chap_id'])){
	$up_chap_id = mysql_real_escape_string($_GET['up_chap_id']);

	$rs_up_this_chap_qry = sprintf("SELECT * FROM tbl_project_chapter AS chp JOIN tbl_project AS p ON chp.Project_id=p.Project_id WHERE chp.Project_chapter_id='%s' AND p.User_id='%s' LIMIT 1", $up_chap_id, $user_id);
	$rs_up_this_chap_row =  mysql_fetch_assoc(mysql_query($rs_up_this_chap_qry));

	$rs_up_chap_qry = sprintf("SELECT chp.* FROM tbl_project_chapter AS chp JOIN tbl_project_chapter AS slct ON slct.Project_id=chp.Project_id WHERE slct.Project_chapter_id='%s' AND chp.Priority < slct.Priority ORDER BY chp.Priority DESC LIMIT 1", $rs_up_this_chap_row['Project_chapter_id']);
	$rs_up_chap_row =  mysql_fetch_assoc(mysql_query($rs_up_chap_qry));
	if($rs_up_chap_row){
		$rs_up_prev_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_up_this_chap_row['Priority'], $rs_up_chap_row['Project_chapter_id']);
		mysql_query($rs_up_prev_chap_qry);
	
		$rs_up_next_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_up_chap_row['Priority'], $rs_up_this_chap_row['Project_chapter_id']);
		mysql_query($rs_up_next_chap_qry);
	}

}

# Move chapter down
if(isset($_GET['down_chap_id'])){
	$down_chap_id = mysql_real_escape_string($_GET['down_chap_id']);

	$rs_down_this_chap_qry = sprintf("SELECT * FROM tbl_project_chapter AS chp JOIN tbl_project AS p ON chp.Project_id=p.Project_id WHERE chp.Project_chapter_id='%s' AND p.User_id='%s' LIMIT 1", $down_chap_id, $user_id);
	$rs_down_this_chap_row =  mysql_fetch_assoc(mysql_query($rs_down_this_chap_qry));

	$rs_down_chap_qry = sprintf("SELECT chp.* FROM tbl_project_chapter AS chp JOIN tbl_project_chapter AS slct ON slct.Project_id=chp.Project_id WHERE slct.Project_chapter_id='%s' AND chp.Priority > slct.Priority ORDER BY chp.Priority ASC LIMIT 1", $rs_down_this_chap_row['Project_chapter_id']);
	$rs_down_chap_row =  mysql_fetch_assoc(mysql_query($rs_down_chap_qry));
	if($rs_down_chap_row){
		$rs_down_prev_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_down_this_chap_row['Priority'], $rs_down_chap_row['Project_chapter_id']);
		mysql_query($rs_down_prev_chap_qry);
	
		$rs_down_next_chap_qry = sprintf("UPDATE tbl_project_chapter SET Priority='%s' WHERE Project_chapter_id='%s'", $rs_down_chap_row['Priority'], $rs_down_this_chap_row['Project_chapter_id']);
		mysql_query($rs_down_next_chap_qry);
	}

}

# Delete chapter
if(isset($_GET['del_chap_id'])){
	$del_chap_id = mysql_real_escape_string($_GET['del_chap_id']);
	
	$rs_del_op_qry = sprintf("DELETE o FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Chapter_id='%s' AND p.User_id='%s'", $del_chap_id, $user_id);
	mysql_query($rs_del_op_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Dit hoofdstuk is aan een projecteigenschap gekoppeld";
		}else{
			die("Error: " . mysql_error());
		}
	}
	
	$rs_del_chap_qry = sprintf("DELETE c FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE c.Project_chapter_id='%s' AND p.User_id='%s'", $del_chap_id, $user_id);
	mysql_query($rs_del_chap_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan dit hoofdstuk";
		}else{
			die("Error: " . mysql_error());
		}
	}else{
		header("Location: ?r_id=".$_GET['r_id']);
		exit();
	}
}

# Delete operation
if(isset($_GET['del_op_id'])){
	$del_op_id = mysql_real_escape_string($_GET['del_op_id']);
	
	$rs_del_op_qry = sprintf("DELETE o FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Project_operation_id='%s' AND p.User_id='%s'", $del_op_id, $user_id);
	mysql_query($rs_del_op_qry);
	if(mysql_error()){
		if(mysql_errno() == 1451){
			$error_message = "Er zijn projectgegevens gekoppeld aan deze werkzaamheid";
		}else{
			die("Error: " . mysql_error());
		}
	}else{
		header("Location: ?r_id=".$_GET['r_id']);
		exit();
	}
}

# Change operation
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_chg_operation'])){
	$operation = mysql_real_escape_string($_POST['fld_chg_operation']);
	$description = mysql_real_escape_string($_POST['fld_chg_description']);
	$operation_id = mysql_real_escape_string($_POST['fld_chg_op_id']);

	# Check is this is the users chapter
	$rs_project_op_check_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND o.Project_operation_id='%s' LIMIT 1", $user_id, $operation_id);
	$rs_project_op_check_result = mysql_query($rs_project_op_check_qry);
	if(mysql_num_rows($rs_project_op_check_result)){
		$rs_chg_op_qry = sprintf("UPDATE tbl_project_operation SET Operation='%s', Description='%s' WHERE Project_operation_id='%s'", $operation, $description, $operation_id);
		mysql_query($rs_chg_op_qry) or die("Error: " . mysql_error());
	}
}

# Change chapter
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_chg_chap'])){
	$chapter = mysql_real_escape_string($_POST['fld_chg_chap']);
	$chapter_id = mysql_real_escape_string($_POST['fld_chg_chap_id']);

	# Check is this is the users chapter
	$rs_project_chap_check_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND c.Project_chapter_id='%s' LIMIT 1", $user_id, $chapter_id);
	$rs_project_chap_check_result = mysql_query($rs_project_chap_check_qry);
	if(mysql_num_rows($rs_project_chap_check_result)){
		$rs_chg_op_qry = sprintf("UPDATE tbl_project_chapter SET Chapter='%s'WHERE Project_chapter_id='%s'", $chapter, $chapter_id);
		mysql_query($rs_chg_op_qry) or die("Error: " . mysql_error());
	}
}

# New operation
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_operation'])){
	$operation = mysql_real_escape_string($_POST['fld_operation']);
	$description = mysql_real_escape_string($_POST['fld_description']);
	$chapter = mysql_real_escape_string($_POST['fld_chapter']);

	$rs_exist_qry = sprintf("SELECT TRUE FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id WHERE c.Project_id='%s' AND o.Operation='%s' LIMIT 1", $project_id, $operation);
	$rs_exist_row = mysql_fetch_assoc(mysql_query($rs_exist_qry));
	
	if($rs_exist_row['TRUE']){
		$error_message = "Er bestaat al een werkzaamheid met deze naam in dit project";
	}else{
		$rs_prio_op_qry = sprintf("SELECT o.Priority FROM tbl_project_operation AS o JOIN tbl_project_chapter AS c ON c.Project_chapter_id=o.Chapter_id JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE o.Chapter_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $chapter, $user_id);
		$rs_prio_op_row = mysql_fetch_assoc(mysql_query($rs_prio_op_qry));
		
		# Check is this is the users chapter
		$rs_project_chap_check_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.User_id='%s' AND c.Project_chapter_id='%s' LIMIT 1", $user_id, $chapter);
		$rs_project_chap_check_result = mysql_query($rs_project_chap_check_qry);
		if(mysql_num_rows($rs_project_chap_check_result)){
			$rs_add_op_qry = sprintf("INSERT INTO tbl_project_operation (Create_date, Chapter_id, Operation, Description, Priority) VALUES (NOW(), '%s', '%s', '%s', '%s')", $chapter, $operation, $description, ($rs_prio_op_row['Priority']+1));
			mysql_query($rs_add_op_qry) or die("Error: " . mysql_error());
		}
	}
}

# New chapter
if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['fld_new_chapter'])){
	$chapter = mysql_real_escape_string($_POST['fld_new_chapter']);

	$rs_exist_qry = sprintf("SELECT TRUE FROM tbl_project_chapter AS c WHERE c.Project_id='%s' AND c.Chapter='%s' LIMIT 1", $project_id, $chapter);
	$rs_exist_row = mysql_fetch_assoc(mysql_query($rs_exist_qry));

	if($rs_exist_row['TRUE']){
		$error_message = "Er bestaat al een hoofdstuk met deze naam in dit project";
	}else{
		$rs_prio_chap_qry = sprintf("SELECT c.Priority FROM tbl_project_chapter AS c JOIN tbl_project AS p ON p.Project_id=c.Project_id WHERE p.Project_id='%s' AND p.User_id='%s' ORDER BY Priority DESC LIMIT 1", $project_id, $user_id);
		$rs_prio_chap_row =  mysql_fetch_assoc(mysql_query($rs_prio_chap_qry));
	
		$rs_add_chapter_qry = sprintf("INSERT INTO tbl_project_chapter (Create_date, Project_id, Chapter, Priority) VALUES (NOW(), '%s', '%s', '%s')", $project_id, $chapter, ($rs_prio_chap_row['Priority']+1));
		mysql_query($rs_add_chapter_qry) or die("Error: " . mysql_error());
	}
}

# Select all chapters for this project
$rs_project_work_qry = sprintf("SELECT c.* FROM tbl_project AS p JOIN tbl_project_chapter AS c ON c.Project_id=p.Project_id WHERE p.User_id='%s' AND c.Project_id='%s' ORDER BY Priority ASC", $user_id, $project_id);
$rs_project_work_result = mysql_query($rs_project_work_qry);
$rs_project_work2_result = mysql_query($rs_project_work_qry);
$rs_project_work_num = mysql_num_rows($rs_project_work_result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Werkzaamheden Toevoegen</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="../../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<script language="javascript"> 
//function toggle(count)
//{
//	var oRows = document.getElementById('tbl_' + count).getElementsByTagName('tr');
//
//	for(i=1;i<oRows.length-1;i++)
//	{
//		var index = document.getElementById('tbl_' + count).rows[i].rowIndex;
//		if(document.getElementById('tbl_' + count).rows[i].style.display == 'none')
//		{
//			document.getElementById('tbl_' + count).rows[i].style.display = '';
//			document.getElementById('frm_op_add_' + count).style.display = '';
//			document.getElementById('tgl_' + count).innerHTML = '-';
//						document.getElementById('tgl_' + count).getElementsByTagName('form').style.display = '';
//		}
//		else
//		{
//			document.getElementById('tbl_' + count).rows[i].style.display = 'none';
//			document.getElementById('frm_op_add_' + count).style.display = 'none';
//			document.getElementById('tgl_' + count).innerHTML = '+';
//		}
//	}
//}
//function init_toggle()
//{
<?php 
//	while($rs_project_work2_row = mysql_fetch_assoc($rs_project_work2_result)){
//		if($chapter != $rs_project_work2_row['Project_chapter_id']){
//			echo 'toggle(' . $rs_project_work2_row['Project_chapter_id'] . ');' . "\n";
//		}
//	} 
?>
//}
</script>
<!--<body onload="init_toggle()">-->
<body>
<div style="background-color:#FFF">
	<div style="margin: 0 auto; width: 770px;">
		<div class="entry">
		<?php if($error_message){ echo '<div class="error">'.$error_message.'</div>'; } ?>
			<div id="title">Werkzaamheden toevoegen</div>
					<table width="100%" border="0">
						<tr class="tbl-subhead">
							<td colspan="2" class="tbl-head">Hoofdstuk toevoegen</td>
						</tr>
						<form name="frm_chap_add" id="frm_chap_add" action="" method="post">
						<tr>
							<td width="18"><a href="#" onclick="document.frm_chap_add.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
							<td width="472"><input name="fld_new_chapter" type="text" id="fld_new_chapter" style="width: 99%;" maxlength="50" /></td>
						</tr>
						</form>
					</table>
					<?php if($rs_project_work_num){ ?>
					<?php $i=0; while($rs_project_work_row = mysql_fetch_assoc($rs_project_work_result)){ $i++;
						# Select all operations for this project
						$rs_project_work_op_qry = sprintf("SELECT * FROM tbl_project_operation WHERE Chapter_id='%s' ORDER BY Priority ASC", $rs_project_work_row['Project_chapter_id']);
						$rs_project_work_op_result = mysql_query($rs_project_work_op_qry);
						$rs_project_work_op_num = mysql_num_rows($rs_project_work_op_result);
					?><br />
					<table id="tbl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" width="100%" border="0">
					<!--<tr class="tbl-head">
							<td colspan="3"><div style="float:right"><a href="?r_id=<?php echo $_GET['r_id']; ?>&up_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/up.png" width="16" height="16" alt="Hoofdstuk omhoog" title="Hoofdstuk omhoog" /></a><a href="?r_id=<?php echo $_GET['r_id']; ?>&down_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/down.png" width="16" height="16" alt="Hoofdstuk omlaag" title="Hoofdstuk omlaag" /></a></div><div style="padding-left:2px;">[ <a href="#" onclick="toggle('<?php echo $rs_project_work_row['Project_chapter_id']; ?>');"><span id="tgl_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">-</span></a> ]&nbsp;<?php echo $rs_project_work_row['Chapter']; ?><a href="?r_id=<?php echo $_GET['r_id']; ?>&del_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"></a></div></td>
							<td width="16"><a href="?r_id=<?php echo $_GET['r_id']; ?>&del_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						</tr>-->
						<tr class="tbl-head">
							<td width="16"><a href="#" onclick="document.frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" alt="Opslaan" title="Opslaan" /></a></td>
							<td colspan="2">
								<div style="float:right">
									<a href="?r_id=<?php echo $_GET['r_id']; ?>&up_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/up.png" width="16" height="16" alt="Hoofdstuk omhoog" title="Hoofdstuk omhoog" /></a>
									<a href="?r_id=<?php echo $_GET['r_id']; ?>&down_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/down.png" width="16" height="16" alt="Hoofdstuk omlaag" title="Hoofdstuk omlaag" /></a>
								</div>
								<div style="padding-left:2px;">
									<form action="" method="post" name="frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_chap_chg_<?php echo $rs_project_work_row['Project_chapter_id']; ?>">
										<input name="fld_chg_chap" id="fld_chg_chap" type="text" style="width: 90%; height:15px; border: none; background-color:transparent; color:#FFF" value="<?php echo $rs_project_work_row['Chapter']; ?>" />
										<input type="hidden" name="fld_chg_chap_id" id="fld_chg_chap_id" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" />
									</form>
								</div>
							</td>
							<td width="16"><a href="?r_id=<?php echo $_GET['r_id']; ?>&del_chap_id=<?php echo $rs_project_work_row['Project_chapter_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						</tr>
						<tr class="tbl-subhead">
							<td width="16">&nbsp;</td>
							<td width="209">Uit te voeren werkzaamheden</td>
							<td width="441">Omschrijving werkzaamheden voor op de factuur</td>
							<td width="16">&nbsp;</td>
						</tr>
						<?php if($rs_project_work_op_num){ ?>
						<?php while($rs_project_work_op_row = mysql_fetch_assoc($rs_project_work_op_result)){ ?>
						<form name="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" id="frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>" action="" method="post">
						<tr>
							<td><a href="#" onclick="document.frm_op_chg_<?php echo $rs_project_work_op_row['Project_operation_id']; ?>.submit()"><img src="../../images/valid.png" width="16" height="16" alt="Opslaan" title="Opslaan" /></a></td>
							<!--<td><?php //echo $rs_project_work_op_row['Operation']; ?></td>-->
							<td><input name="fld_chg_operation" type="text" id="fld_chg_operation" style="width: 99%; height:15px; border-color:#ccc;" maxlength="80" value="<?php echo $rs_project_work_op_row['Operation']; ?>" /><input type="hidden" name="fld_chg_op_id" id="fld_chg_op_id" value="<?php echo $rs_project_work_op_row['Project_operation_id'] ?>" /></td>
							<!--<td><div style="float:right"><a href="?r_id=<?php //echo $_GET['r_id']; ?>&up_op_id=<?php //echo $rs_project_work_op_row['Project_operation_id']; ?>"><img src="../../images/up.png" width="16" height="16" alt="Werkzaamheid omhoog" title="Werkzaamheid omhoog" /></a><a href="?r_id=<?php //echo $_GET['r_id']; ?>&down_op_id=<?php //echo $rs_project_work_op_row['Project_operation_id']; ?>"><img src="../../images/down.png" width="16" height="16" alt="Werkzaamheid omlaag" title="Werkzaamheid omlaag" /></a></div><?php //echo $rs_project_work_op_row['Description']; ?></td>-->
							<td><div style="float:right"><a href="?r_id=<?php echo $_GET['r_id']; ?>&up_op_id=<?php echo $rs_project_work_op_row['Project_operation_id']; ?>"><img src="../../images/up.png" width="16" height="16" alt="Werkzaamheid omhoog" title="Werkzaamheid omhoog" /></a><a href="?r_id=<?php echo $_GET['r_id']; ?>&down_op_id=<?php echo $rs_project_work_op_row['Project_operation_id']; ?>"><img src="../../images/down.png" width="16" height="16" alt="Werkzaamheid omlaag" title="Werkzaamheid omlaag" /></a></div><input name="fld_chg_description" type="text" id="fld_chg_description" style="width: 90%; height:15px; border-color:#ccc;" maxlength="400" value="<?php echo $rs_project_work_op_row['Description']; ?>" /></td>
							<td><a href="?r_id=<?php echo $_GET['r_id']; ?>&del_op_id=<?php echo $rs_project_work_op_row['Project_operation_id']; ?>"><img src="../../images/remove.png" width="16" height="16" alt="Verwijderen" title="Verwijderen" /></a></td>
						</tr>
						</form>
						<?php } ?>
						<?php } ?>
						<form name="frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" id="frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>" action="" method="post">
						<tr>
							<td><a href="#" onclick="document.frm_op_add_<?php echo $rs_project_work_row['Project_chapter_id']; ?>.submit()"><img src="../../images/add.png" width="16" height="16" alt="Toevoegen" title="Toevoegen" /></a></td>
							<td><input name="fld_operation" type="text" id="fld_operation" style="width: 99%;" maxlength="80" /><input type="hidden" name="fld_chapter" id="fld_chapter" value="<?php echo $rs_project_work_row['Project_chapter_id']; ?>" /></td>
							<td colspan="2"><input name="fld_description" type="text" id="fld_description" style="width: 99%;" maxlength="400" /></td>
							</tr>
						</form>
					</table>
					<?php } ?>
					<?php }else{ ?>
			<div style="text-align:center">Dit project bevat nog geen werkzaamheden</div>
					<?php } ?>
				</div>
		<div style="clear: both; font-size:9px">&nbsp;</div>
	</div>
</div>
</div>
</div>
</body>
</html>