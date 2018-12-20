<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />

<!--
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script src="admin/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="admin/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="admin/view/javascript/summernote/summernote.js"  type="text/javascript" ></script>
<script src="admin/view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="admin/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="admin/view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="admin/view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="admin/view/javascript/summernote/summernote.css" rel="stylesheet" />
<link href="admin/view/stylesheet/stylesheet.css" type="text/css" rel="stylesheet" media="screen" />

<link href="admin/view/javascript/bootstrap/css/minoral.css" rel="stylesheet">

-->

<!--
    <link rel="icon" type="image/ico" href="upload/image/login/favicon.ico" />
     Bootstrap -->
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="admin/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">

<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="admin/view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a></div>
  <?php if ($logged) { ?>
  <ul class="nav pull-right">
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left"><?php echo $alerts; ?></span> <i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo "Suscripciones"; ?></li>
        <li><a href="<?php echo $processing_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $processing_status_total; ?></span><?php echo "Errores"; ?></a></li>
        <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo "Promociones"; ?></li>
        <li><a href="<?php echo "Errores"; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo "Errores"; ?></a></li>		
        <li><a href="<?php echo $text_exito_presta; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo "Exitosos"; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo "Prestamos"; ?></li>
        <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
        <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-chrome fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo "Ayuda"; ?></li>
        <li><a href="https://plus.tigo.com.gt/vas/" target="_blank"><?php echo "VAS Reports"; ?></a></li>
        <li><a href="http://172.22.52.224/vas/" target="_blank"><?php echo "VAS Reports Desarrollo"; ?></a></li>
        <li><a href="https://<?php echo $ip_cbs; ?>:8081" target="_blank"><?php echo "CBS"; ?></a></li>
		<li class="divider"></li>
		<li><a href="http://130.1.112.52/jkmanager/" target="_blank"><?php echo "JK Manager"; ?></a></li>
		<li class="divider"></li>
		<li><a href="http://localhost/xampp/" target="_blank"><?php echo "XAMPP Admin"; ?></a></li>
		<li><a href="http://localhost/phpmyadmin/" target="_blank"><?php echo "phpMyAdmin";?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo "Herramientas"; ?></li>
        <li><a href="http://fontawesome.io/icons/" target="_blank"><?php echo "Font Awesome"; ?></a></li>
        <li><a href="https://html-online.com/editor/" target="_blank"><?php echo "HTML Online Editor"; ?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-puzzle-piece fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo "Juegos Online"; ?></li>
        <li><a href="http://www.chess.com/" target="_blank"><?php echo "Chess"; ?></a></li>
      </ul>
    </li>	
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>


