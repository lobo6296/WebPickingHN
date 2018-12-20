<?php echo $header; ?><?php echo $column_left; ?>
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
		
		
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val('');
		
		$('#coupon-product' + item['value']).remove();
		
		$('#coupon-product').append('<div id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_product[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#coupon-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');
		
		$('#coupon-category' + item['value']).remove();
		
		$('#coupon-category').append('<div id="coupon-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#coupon-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
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