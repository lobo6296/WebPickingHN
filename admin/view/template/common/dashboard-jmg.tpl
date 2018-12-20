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

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $customer; ?></div>
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $online; ?></div>
    </div>
    <div class="row">
     <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $chart; ?></div>
	 <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embmesactual;?></div>
    </div>
    <div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embanual;?></div>
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embarea;?></div>
    </div> 
    <div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embanuala;?></div>
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embanualm;?></div>
   </div> 	
   <div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $embanualt;?></div>
	
   </div> 		 	
  
   </div>

	<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-mobile"></i> <?php echo "Toneladas"; ?></h3>
    </div>

   <div class="row">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $toneaereo; ?></div>
	  <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $tonemaritimo; ?></div>
    </div>	   
   <div class="row">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $toneterrestre; ?></div>
    </div>	
  </div>
  
	<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-mobile"></i> <?php echo "Clientes con mayor participacion por Tipo de Servicio Ene-Mes Actual"; ?></h3>
    </div>
   <!--
   <div class="row">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $contenedores; ?></div>
	  <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $teus; ?></div>
    </div>	   
	-->
   <div class="row">
      <div class="col-sm-4"><?php echo $clipartia; ?></div>
	  <div class="col-sm-4"><?php echo $clipartim; ?></div>
      <div class="col-sm-4"><?php echo $clipartit; ?></div>
    </div>
	
	
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-map"></i> <?php echo "Destinos con Mayor Participación por Tipo de Servicio"; ?></h3>
    </div>	
    <div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $despartia; ?></div>
    <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $map;?></div>
	</div>

	<div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $despartim; ?></div>
    <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $mapm;?></div>
	</div>

	<div class="row">
	<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $despartit; ?></div>
    <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $mapt;?></div>
	</div>

    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-industry"></i> <?php echo "Industrias con Mayor Participación por Tipo de Servicio"; ?></h3>
    </div>	
	
   <div class="row">
      <div class="col-sm-4"><?php echo $indpartia; ?></div>
	  <div class="col-sm-4"><?php echo $indpartim; ?></div>
      <div class="col-sm-4"><?php echo $indpartit; ?></div>
    </div>	
	
	</div>  
  
  </div>


<?php echo $footer; ?>