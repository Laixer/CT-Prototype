<?php
# User's relation for homepage
$rs_user_relation_qry = sprintf("SELECT * FROM tbl_relation WHERE User_id='%s' AND Relation_type_id=1 LIMIT 1", $user_id);
$rs_user_relation_result = mysql_query($rs_user_relation_qry) or die("Error: " . mysql_error());
$rs_user_relation_row = mysql_fetch_assoc($rs_user_relation_result);
mysql_free_result($rs_user_relation_result);

# Message query
$rs_message_result = mysql_query("SELECT Message, DATE_FORMAT(Create_date, '%e-%c-%Y') AS fCreate_date FROM tbl_message ORDER BY Create_date DESC LIMIT 3") or die("Error: " . mysql_error());
?>
<div id="page-bgtop">
	<div id="content-left">
		<div id="intern" style="height:300px;">
			<div id="title">
				<span><?php echo $rs_user_relation_row['Company_name']; ?></span>
				<?php if($__tooltip['Title'] || $__tooltip['Message']){ ?>
				<a class="tooltip" href="javascript:void(0)">
					<img src="../../images/info_icon.png" width="18" height="18" />
					<span class="classic"><div class="tt-title"><?php echo $__tooltip['Title']; ?></div><?php echo $__tooltip['Message']; ?></span>
				</a>
				<?php } ?>
			</div>
			<div id="logo"><a href="?p_id=102&r_id=<?php echo $rs_user_relation_row['Relation_id']; ?>&_utm=<?php echo $__url_session; ?>"><img src="http://static-4.cdnhub.nl/nl/images/logos/van-dale-logo-big.gif" alt="Logo" width="200" height="100" /></a></div>
			<div class="details-head">Bedrijfsgegevens</div>
			<div class="details"><?php echo $rs_user_relation_row['Business']; ?></div>
			<div class="details"><?php echo $rs_user_relation_row['Phone_1']; ?></div>
			<div class="details"><?php echo $rs_user_relation_row['Email_1']; ?></div>
			<div class="group">
				<div class="details-head">Contactgegevens</div>
				<div class="details"><?php echo $rs_user_relation_row['Contact_first_name']; ?></div>
				<div class="details"><?php echo $rs_user_relation_row['Contact_name']; ?></div>
			</div>
			<div class="group">
				<div class="details-head">Adresgegevens</div>
				<div class="details"><?php echo $rs_user_relation_row['Address'].' '.$rs_user_relation_row['Address_number']; ?></div>
				<div class="details"><?php echo $rs_user_relation_row['Zipcode']; ?></div>
				<div class="details"><?php echo $rs_user_relation_row['City']; ?></div>
			</div>
		</div>
		<div class="entry">
			<div class="entry-header">Mededelingen</div>
			<ul class="entry-list">
				<?php $i=0; while($rs_message_row = mysql_fetch_assoc($rs_message_result)){ $i++; ?>
				<li class="entry-list-message" <?php if($i%2){ echo 'style="background-color:#E4E4E4"'; } ?>><?php echo $rs_message_row['fCreate_date']; ?> | <?php echo $rs_message_row['Message']; ?>... <a href="#">Lees Meer &gt;</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div id="content-right">
		<div id="banner">
			<ul class="bjqs">
				<li><img src="../images/banner01.jpg" title="Automatically generated caption"></li>
				<li><img src="../images/banner02.jpg" title="Automatically generated caption"></li>
				<li><img src="../images/banner03.jpg" title="Automatically generated caption"></li>
			</ul>
		</div>
		<div class="entry">
			<div class="entry-header">Laatste wijzigingen</div>
			<ul class="entry-list">
				<li class="entry-list-message" style="background-color:#E4E4E4">Uren toegevoegd in project <b>Test</b> <a href="#">Lees Meer &gt;</a></li>
				<li class="entry-list-message">Nieuw hoofdstuk toegevoegd in project <b>MijnProject</b> <a href="#">Lees Meer &gt;</a></li>
				<li class="entry-list-message" style="background-color:#E4E4E4">Project <b>Lol</b> is afgesloten</a></li>
			</ul>
		</div>
	</div>
	<div style="clear: both; font-size:9px">&nbsp;</div>
</div>
<script src="../js/libs/jquery-1.6.2.min.js"></script>
<script src="../js/slider.js"></script>
<script>
$(document).ready(function(){
	$('#banner').bjqs({
		'animation' : 'slide',
		'width' : 660,
		'height' : 300
	});
});
</script>
<?php
mysql_free_result($rs_message_result);
?>