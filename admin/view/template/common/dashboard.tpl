<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <h1>&nbsp;&nbsp;<?php echo "Cuenta: ".$cuenta; ?></h1> 
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $ingresado; ?></div>
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $enproceso; ?></div>	  
	  <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $finalizado; ?></div>
	  <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $demorado; ?></div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $porvencer; ?></div>
	  <div class="col-lg-3 col-md-3 col-sm-6"></div>
	  <div class="col-lg-6 col-md-3 col-sm-6 "><?php echo $chart; ?></div>
    </div> 	
    <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12"></div>
      <div class="col-lg-8 col-md-12 col-sm-12 col-sx-12"> <?php echo $recent; ?> </div>
    </div>  
   </div>
  </div>
<?php echo $footer; ?>