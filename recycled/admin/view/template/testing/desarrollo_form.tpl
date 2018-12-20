<?php echo $header; ?><?php echo $column_left; ?><?php echo $stats; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	    <button type="submit" form="form-test" data-toggle="tooltip" title="<?php echo $button_test; ?>" class="btn btn-default"><i class="fa fa-gear"></i></button>
        <!--
		<button type="submit" form="form-prueba" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		-->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form." ".$cod_prueba."-".$nombre_prueba; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-test" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($cod_prueba) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-celular"><?php echo $entry_numero_celular; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="celular" value="<?php echo $numero_celular; ?>" placeholder="<?php echo $entry_numero_celular; ?>" id="input-celular" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>

			  <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-acreditacion"><?php echo $entry_acreditacion; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="acreditacion" value="<?php echo $acreditacion; ?>" placeholder="<?php echo $entry_acreditacion; ?>" id="input-acreditacion" class="form-control" />
                  <?php if ($error_acreditacion) { ?>
                  <div class="text-danger"><?php echo $error_acreditacion; ?></div>
                  <?php } ?>
                </div>
              </div>

			  <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-modelo"><?php echo $entry_modelo; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="modelo" value="<?php echo $modelo; ?>" placeholder="<?php echo $entry_modelo; ?>" id="input-modelo" class="form-control" />
                  <?php if ($error_modelo) { ?>
                  <div class="text-danger"><?php echo $error_modelo; ?></div>
                  <?php } ?>
                </div>
              </div>

            </div>
            <?php if ($cod_prueba) { ?>
            <div class="tab-pane" id="tab-history">
              <div id="history"></div>
            </div>
            <?php } ?>
          </div>
        </form>	
		<div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-stethoscope"></i> <?php echo "Test Result"; ?></h3>
        </div>
		<?php if ($resultado) {?>
		  <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo "Acreditacion"; ?></td>
                <td class="text-left"><?php echo "Nombre"; ?></td>
				<td class="text-left"><?php echo "MTR"; ?></td>
				<td class="text-left"><?php echo "Condicion Evaluada"; ?></td>
				<td class="text-left"><?php echo "Resultado"; ?></td>
				<td width="15%" class="text-center"><b>Status</b></td>
              </tr>
            </thead>
			<tbody>
              <tr>
                <td class="text-left"><?php echo $cod_acreditacion; ?></td>
				<td class="text-left"><?php echo $nombre_acreditacion; ?></td>
                <td class="text-left"><?php echo $texto_comentario; ?></td>
                <td class="text-left"><?php echo $cod_condicion; ?></td>
				<td class="text-left"><?php echo $resultado; ?></td>
				<td class="text-center"><?php if ($resultado==0) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?></td>
              </tr>		
			</tbody>
				  </table>
		</div>
		
		<?php } ?>		
		
		
		<?php if ($detalleCondicion) {?>		
		  <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo "Condicion"; ?></td>
                <td class="text-left"><?php echo "Correlativo"; ?></td>
				<td class="text-left"><?php echo "Descripcion"; ?></td>
				<td class="text-left"><?php echo "Valor Condicion"; ?></td>
				<td class="text-left"><?php echo "Operador Logico"; ?></td>
				<td width="15%" class="text-center"><b>Status</b></td>
              </tr>
            </thead>
			<tbody>
              <?php foreach ($detalleCondicion as $detalle) { ?>
              <tr>
                <td class="text-left"><?php echo $detalle['cod_condicion']; ?></td>
				<td class="text-left"><?php echo $detalle['cod_det_condicion']; ?></td>
                <td class="text-left"><?php echo $detalle['descripcion_condicion']; ?></td>
                <td class="text-left"><?php echo $detalle['valor_condicion']; ?></td>
				<td class="text-left"><?php echo $detalle['operador_logico']; ?></td>
				<td class="text-center"><?php if ($detalle['cod_det_condicion']< $resultado or $resultado==0) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { if ($detalle['cod_det_condicion'] == $resultado) {?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } else { ?>
				  <span class="text-warning"><i class="fa fa-question-circle"></i></span>
				  <?php }  
				  } ?></td>
              </tr>
              <?php } ?>
		
			</tbody>
				  </table>
		</div>
		
		<?php } ?>
		<!--
		-->
	  <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12">
	  
	  <?php if ($promociones) {?>		
		  <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo "Promocion"; ?></td>
                <td class="text-left"><?php echo "Nombre"; ?></td>
				<td class="text-left"><?php echo "Fecha"; ?></td>
              </tr>
            </thead>
			<tbody>
              <?php foreach ($promociones as $promocion) { ?>
              <tr>
                <td class="text-left"><?php echo $promocion['cod_promocion']; ?></td>
				<td class="text-left"><?php echo $promocion['nombre_promocion']; ?></td>
                <td class="text-left"><?php echo $promocion['fecha_hora']; ?></td>
              </tr>
              <?php } ?>
			</tbody>
		    </table>
		   </div>	
		<?php } ?> 
	  </div>
	  
      <div class="col-lg-8 col-md-12 col-sm-12 col-sx-12">  
	  		<?php if ($detalleRespuesta) {?>		
		  <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo "Fecha"; ?></td>
                <td class="text-left"><?php echo "Mensaje"; ?></td>
              </tr>
            </thead>
			<tbody>
              <?php foreach ($detalleRespuesta as $drespuesta) { ?>
              <tr>
                <td class="text-left"><?php echo $drespuesta['fecha_hora']; ?></td>
				<td class="text-left"><?php echo $drespuesta['mensaje']; ?></td>
              </tr>
              <?php } ?>
			</tbody>
		    </table>
		   </div>	
		<?php } ?>	  
	  </div>
	  
      </div>
	  <!--
		-->
	  <div class="row">	
	  	  <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12">	  
	  <?php if ($billeteras) {?>		
		  <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo "Billetera"; ?></td>
                <td class="text-left"><?php echo "Fecha Expiracion"; ?></td>
				<td class="text-left"><?php echo "Monto"; ?></td>
              </tr>
            </thead>
			<tbody>
              <?php foreach ($billeteras as $billetera) { ?>
              <tr>
                <td class="text-left"><?php echo $billetera['billetera']; ?></td>
				<td class="text-left"><?php echo $billetera['fecha_expira']; ?></td>
                <td class="text-left"><?php echo $billetera['valor']; ?></td>
              </tr>
              <?php } ?>
			</tbody>
		    </table>
		   </div>	
		<?php } ?>
	  
	  </div>
	  </div>	
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>	
		
      </div>
    </div>
  </div>
  <?php if ($cod_prueba) { ?>
  <script type="text/javascript"><!--
$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	
	$('#history').load(this.href);
});			

$('#history').load('index.php?route=testing/desarrollo/history&token=<?php echo $token; ?>&cod_prueba=<?php echo $cod_prueba; ?>');
//--></script>
  <?php } ?>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>