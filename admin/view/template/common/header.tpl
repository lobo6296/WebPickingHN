<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery.treetable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/datetimepicker/moment.js"></script>
<script type="text/javascript" src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/scrolltopcontrol/scrolltopcontrol.js"></script>

<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script type="text/javascript" src="view/javascript/common.js"></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="view/javascript/font-awesome/css/font-awesome.min.css"  />
<link rel="stylesheet" type="text/css" href="view/javascript/summernote/summernote.css"/>
<link rel="stylesheet" type="text/css" href="view/stylesheet/detail.css"/>
<link rel="stylesheet" type="text/css" href="view/stylesheet/macro-dashboard.css"/>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="view/stylesheet/tdt.css" media="screen"/>
<!--
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
-->
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a></div>
  <?php if ($logged) { ?>
  <!--
  	<div class="hidden-xs hidden-sm hidden-md pull-left" id="topclock">
		<i class="fa fa-calendar fa-lg iclock"></i>	
		<div class="clock hidden-xs hidden-sm">
			<div id="DateTimeWrap"></div>
			<ul class="showTime">
				<li id="hours"></li>
				<li id="point">:</li>
				<li id="min"></li>
				<li id="point">:</li>
				<li id="sec"></li>
			</ul>   
		</div>
	</div>
-->
    <ul class="nav pull-right">
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left">
	<?php echo $alerts; ?></span> 
	<i class="fa fa-bar-chart-o"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo "Stock Report"; ?></li>
        <li>
		    <a href="<?php echo $stockreport; ?>" style="display: block; overflow: auto;">
			<?php echo "01. Stock Report"; ?></a></li>
        <li>
		    <a href="<?php echo $bypackinglist; ?>">
			<span class="label label-success pull-right"></span><?php echo "02. By Packing List"; ?></a></li>
        <li>
		    <a href="<?php echo $bymovements; ?>">
			<span class="label label-success pull-right"></span><?php echo "03. By Movements"; ?></a></li>			
        <li>
		    <a href="<?php echo $bybomnumber; ?>">
			<span class="label label-success pull-right"></span><?php echo "04. By BOM Number"; ?></a></li>						
        <li>
		    <a href="<?php echo $damaged; ?>">
			<span class="label label-success pull-right"></span><?php echo "05. Damaged"; ?></a></li>	
			<li class="divider"></li>
        <li>
		    <a href="<?php echo $averangeoccupancy; ?>">
			<span class="label label-success pull-right"></span><?php echo "06. Averange Occupancy."; ?></a>
			</li>			
			<li class="divider">
			</li>				
        <li class="dropdown-header"><?php echo "Deliveries"; ?></li>
		
        <li>
		    <a href="<?php echo $bydata; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $bydata; ?></span><?php echo "07. By Date"; ?></a></li>

        <li>
		    <a href="<?php echo $packinglist; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $packinglist; ?></span><?php echo "08. By Packing List"; ?></a></li>			
		
        <li>
		    <a href="<?php echo $bysite; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $bysite; ?></span><?php echo "09. By Site"; ?></a></li>

        <li>
		    <a href="<?php echo $bysiteindetailthemovement; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $bysiteindetailthemovement; ?></span><?php echo "10. By Site in Detail the Movement"; ?></a></li>			
			
        <li class="divider"></li>

        <li>
		    <a href="<?php echo $inbounds; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $inbounds; ?></span><?php echo "11. Inbounds"; ?></a></li>
		<li>
		    <a href="<?php echo $outbounds; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $outbounds; ?></span><?php echo "12. Outbounds"; ?></a></li>
		<li>
		    <a href="<?php echo $returns; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $returns; ?></span><?php echo "13. Returns"; ?></a></li>
        <li>
		    <a href="<?php echo $overtime; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $overtime; ?></span><?php echo "14. Overtime"; ?></a></li>
        <li>
		    <a href="<?php echo $generalstockbycode; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $generalstockbycode; ?></span><?php echo "15. General Stock By Code"; ?></a></li>		
        <li>
		    <a href="<?php echo $summaryofmovement; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $summaryofmovement; ?></span><?php echo "16. Summary of Movement"; ?></a></li>
        <li>
		    <a href="<?php echo $inboundbydate; ?>" style="display: block; overflow: auto;">
			<span class="label label-warning pull-right"><?php echo $inboundbydate; ?></span><?php echo "17. Inbound by date"; ?></a></li>			
       
      </ul>
    </li>

	
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>
<script type="text/javascript"><!--
$(document).ready(function() {
var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; 
var dayNames= ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]

var newDate = new Date();
newDate.setDate(newDate.getDate());
$('#DateTimeWrap').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval(function() {
	var seconds = new Date().getSeconds();
	$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
},1000);
	
setInterval(function() {
	var minutes = new Date().getMinutes();
	$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
},1000);
	
setInterval(function() {
	var hours = new Date().getHours();
	$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
}, 1000);
	
}); 
//--></script>