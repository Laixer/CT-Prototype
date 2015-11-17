<?php
# Includes
include_once("../../private/conn_db_common.php");
include_once("../inc/restrict_login.php");
include("../inc/switchboard.php");

# User session
$user_id = mysql_real_escape_string($_SESSION['SES_User_id']);

# User's relation for warnings
$rs_relation_qry = sprintf("SELECT TRUE FROM tbl_relation WHERE User_id='%s' AND Relation_type_id=1 LIMIT 1", $user_id);
$rs_relation_result = mysql_query($rs_relation_qry) or die("Error: " . mysql_error());
$rs_relation_row = mysql_fetch_assoc($rs_relation_result);
mysql_free_result($rs_relation_result);

if(!$rs_relation_row){
	$main_warn_message = "U moet uw bedrijfsgegevens nog opgeven";
}

# Title query
$rs_opt_title_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='website_title' LIMIT 1") or die("Error: " . mysql_error());
$rs_opt_title_row = mysql_fetch_assoc($rs_opt_title_result);
mysql_free_result($rs_opt_title_result);

# Keyword query
$rs_opt_keywords_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='website_keywords' LIMIT 1") or die("Error: " . mysql_error());
$rs_opt_keywords_row = mysql_fetch_assoc($rs_opt_keywords_result);
mysql_free_result($rs_opt_keywords_result);

# Description query
$rs_opt_description_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='website_description' LIMIT 1") or die("Error: " . mysql_error());
$rs_opt_description_row = mysql_fetch_assoc($rs_opt_description_result);
mysql_free_result($rs_opt_description_result);

# Footer query
$rs_opt_footer_result = mysql_query("SELECT Option_value FROM tbl_option WHERE Option_name='website_footer' LIMIT 1") or die("Error: " . mysql_error());
$rs_opt_footer_row = mysql_fetch_assoc($rs_opt_footer_result);
mysql_free_result($rs_opt_footer_result);

# All backlog query
$rs_backlog_all_result = mysql_query("SELECT *, UNIX_TIMESTAMP(Timestamp_date) AS t_date FROM tbl_backlog WHERE Status != 'Todo' ORDER BY Status ASC, Create_date DESC LIMIT 1") or die("Error: " . mysql_error());
$rs_backlog_all_row = mysql_fetch_assoc($rs_backlog_all_result);
mysql_free_result($rs_backlog_all_result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $rs_opt_title_row['Option_value']; ?></title>
        <meta name="keywords" content="<?php echo $rs_opt_keywords_row['Option_value']; ?>" />
        <meta name="description" content="<?php echo $rs_opt_description_row['Option_value']; ?>" />
        <link href="../css/main_new.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="../css/slider.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="../css/tooltip.css" rel="stylesheet" type="text/css" media="screen" />
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
</head>
<body>
<!-- BETA VERSIE -->
<div style="z-index:10000; width:250px; height:15px; background-color:#F79225; position:fixed; right:10px; bottom:0px; color:#595959; font-weight:bold; padding:5px;">
	<span>&gt;&nbsp;<?php echo $rs_backlog_all_row['Status'].': <a href="?p_id=1101">'.$rs_backlog_all_row['Subject'].'</a>'; ?></span>
	<span style="float:right"><a href="?p_id=1100">Admin panel</a></span>
</div>
<div id="wrapper">
	<div id="header">
		<div id="header-block-proj">
			<a href="?p_id=105"><img src="../images/head_proj.png" alt="Nieuw Project" width="70" height="70" title="Nieuw Project" /></a>
			<div id="header-block-text"><a href="?p_id=105&_utm=<?php echo $__url_session; ?>">Nieuw Project</a></div>
		</div>
		<div id="header-block-fact">
			<a href="?p_id=103"><img src="../images/head_fact.png" alt="Projecten" width="70" height="70" title="Projecten" /></a>
			<div id="header-block-text"><a href="?p_id=103&_utm=<?php echo $__url_session; ?>">Projecten</a></div>
		</div>
		<div id="header-block-rel-new">
			<a href="?p_id=106"><img src="../images/head_rel_new.png" alt="Nieuwe Relatie" width="70" height="70" title="Nieuwe Relatie" /></a>
			<div id="header-block-text"><a href="?p_id=106&_utm=<?php echo $__url_session; ?>">Nieuwe Relatie</a></div>
		</div>
		<div id="header-block-rel">
			<a href="?p_id=101"><img src="../images/head_rel.png" alt="Relaties" width="70" height="70" title="Relaties" /></a>
			<div id="header-block-text"><a href="?p_id=101&_utm=<?php echo $__url_session; ?>">Relaties</a></div>
		</div>
		<div id="header-block-hour">
			<a href="?p_id=103"><img src="../images/head_hour.png" alt="Urenregistratie" width="70" height="70" title="Urenregistratie" /></a>
			<div id="header-block-text"><a href="?p_id=103&_utm=<?php echo $__url_session; ?>">Urenregistratie</a></div>
		</div>
		<div id="header-block-finc">
			<a href="?p_id=103"><img src="../images/head_finc.png" alt="Financieel" width="70" height="70" title="Financieel" /></a>
			<div id="header-block-text"><a href="?p_id=103&_utm=<?php echo $__url_session; ?>">Inkoopfacturen</a></div>
		</div>
		<div id="header-logo"></div>
		<div id="header-block-out">
			<a href="/signout/"><img src="../images/head_out.png" alt="Uitloggen" width="70" height="70" title="Uitloggen" /></a>
			<div id="header-block-text"><a href="/signout/">Uitloggen</a></div>
		</div>
		<div id="header-block-help">
			<a href="javascript:void(0);"><img src="../images/head_help.png" alt="Help" width="70" height="70" title="Help" /></a>
			<div id="header-block-text"><a href="javascript:void(0);">Help</a></div>
		</div>
		<div id="header-block-sett">
			<a href="javascript:void(0);"><img src="../images/head_sett.png" alt="Voorkeuren" width="70" height="70" title="Voorkeuren" /></a>
			<div id="header-block-text"><a href="javascript:void(0);">Voorkeuren</a></div>
		</div>
		<div id="header-block-dbase">
			<a href="?p_id=107"><img src="../images/head_dbase.png" alt="Materialendatabase" width="70" height="70" title="Materialendatabase" /></a>
			<div id="header-block-text"><a href="?p_id=107&_utm=<?php echo $__url_session; ?>">Materialendatabase</a></div>
		</div>
	<!--	<div id="header-block-manl">
			<a href="#"><img src="../images/head_manl.png" alt="Handleiding" title="Handleiding" /></a>
			<div id="header-block-text"><a href="#">Handleiding</a></div>
		</div>-->	
	</div>
	<div id="breadcrumbs">
	<?php
/*	if($array_title){
		for($i = count($array_title)-1; $i >- 1; $i--){
			if(mysql_real_escape_string($_GET['r_id'])){
				echo '<a href="?p_id='.$array_pid[$i].'&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'">'.$array_title[$i].'</a>';
			}else{
				echo '<a href="?p_id='.$array_pid[$i].'&_utm='.$__url_session.'">'.$array_title[$i].'</a>';
			}
			if($i != 0){
				echo '&nbsp;&gt&nbsp';
			}
		}
	}else{
		echo '<a href="?p_id=1&_utm='.$__url_session.'">Home</a>';
	}
*/	?> 
	</div>
	<div id="progress-header">
	<?php
	echo '<a href="?p_id=100&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Home</a>';
	echo '<div class="process-light-light"></div>';
	$rs_progress_qry = sprintf("SELECT * FROM tbl_progress_bar WHERE Page_id='%s' AND Indexing='Y' LIMIT 1", $page_id);
	$rs_progress_result = mysql_query($rs_progress_qry) or die("Error: " . mysql_error());
	$rs_progress_row = mysql_fetch_assoc($rs_progress_result);
	if($rs_progress_row){
		if($rs_progress_row['Module_id'] == 1){
			echo '<a href="?p_id=111&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Project</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=112&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Calculatie</a>';
			echo '<div class="process-light-dark"></div>';
			echo '<a href="?p_id=134&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text-disabled">Stelposten</a>';
			echo '<div class="process-dark-dark"></div>';
			echo '<a href="?p_id=139&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text-disabled">Minderwerk</a>';
			echo '<div class="process-dark-dark"></div>';
			echo '<a href="?p_id=136&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text-disabled">Meerwerk</a>';
			echo '<div class="process-dark-light"></div>';
			echo '<a href="?p_id=130&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Offerte</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=149&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Factuur</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=154&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Winst & Verlies</a>';
			echo '<div class="process-light-light"></div>';
		}else if($rs_progress_row['Module_id'] == 5){
			echo '<a href="?p_id=104&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Project</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=701&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Urenregistratie</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=702&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Inkoopfacturen</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=703&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Uittrekstaat</a>';
			echo '<div class="process-light-light"></div>';
			echo '<a href="?p_id=704&r_id='.mysql_real_escape_string($_GET['r_id']).'&_utm='.$__url_session.'" class="process-text">Eindresultaat</a>';
			echo '<div class="process-light-light"></div>';
		}
	}
	?>
	</div>
	<div id="page">
		<?php if($main_success_message){ echo '<div class="success">'.$main_success_message.'</div>'; } ?>
		<?php if($main_warn_message){ echo '<div class="warning">'.$main_warn_message.'</div>'; } ?>
		<?php if($main_error_message){ echo '<div class="error">'.$main_error_message.'</div>'; } ?>
		<?php
		include($inc_page);
		?>
	</div>
	<div id="content-footer">
		<div id="footer"><?php echo $rs_opt_footer_row['Option_value']; ?></div>
	</div>
</div>
</body>
</html>