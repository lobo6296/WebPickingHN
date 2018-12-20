<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <a href="<?php echo $add; ?>" data-toggle="tooltip" 
	     title="<?php echo $button_add; ?>" 
		 class="btn btn-primary"><i class="fa fa-plus"></i>
	  </a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-offering').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-cod_operacion"><?php echo $entry_cod_operacion; ?></label>
                <input type="text" name="filter_cod_operacion" value="<?php echo $filter_cod_operacion; ?>" 
				       placeholder="<?php echo $entry_cod_operacion; ?>" 
					   id="input-cod_operacion" class="form-control" />
              </div>
              <div class="form-group">
              </div>
            </div>
			
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-descripcion_operacion"><?php echo $entry_descripcion_operacion; ?></label>
                <input type="text" name="filter_descripcion_operacion" value="<?php echo $filter_descripcion_operacion; ?>" 
				       placeholder="<?php echo $entry_descripcion_operacion; ?>" 
					   id="input-descripcion_operacion" class="form-control" />
              </div>
              <div class="form-group">
              </div> 
            </div>
			
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-metodo"><?php echo $entry_metodo; ?></label>
                <input type="text" name="filter_metodo" value="<?php echo $filter_metodo; ?>" 
				       placeholder="<?php echo $entry_metodo; ?>" 
					   id="input-metodo" class="form-control" />
              </div>
              <div class="form-group">

              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
			
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-offering">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  				  
				  <td class="text-left"><?php if ($sort == 'cod_operacion') { ?>
                    <a href="<?php echo $sort_id; ?>" 
					   class="<?php echo strtolower($order); ?>"><?php echo $column_cod_operacion; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_id; ?>"><?php echo $column_cod_operacion; ?></a>
                    <?php } ?></td>
				  
                  <td class="text-left"><?php if ($sort == 'column_descripcion_operacion') { ?>
                    <a href="<?php echo $sort_name; ?>" 
					   class="<?php echo strtolower($order); ?>"><?php echo $column_descripcion_operacion; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_descripcion_operacion; ?></a>
                    <?php } ?></td>
					
				  <td class="text-left"><?php echo $column_namespace; ?></td> 	

				  <td class="text-left"><?php echo $column_metodo; ?></td> 	
				  
				  <td class="text-left"><?php echo $column_activo; ?></td>
			
					
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($operaciones) { ?>
                <?php foreach ($operaciones as $operacion) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($operacion['cod_operacion'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $operacion['cod_operacion']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $operacion['cod_operacion']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $operacion['cod_operacion']; ?></td>
                  <td class="text-left"><?php echo $operacion['descripcion_operacion']; ?></td>
				  <td class="text-left"><?php echo $operacion['namespace']; ?></td>
                  <td class="text-left"><?php echo $operacion['metodo']; ?></td>
                  <td class="text-left"><?php echo $operacion['activo']; ?></td>
                  <td class="text-right"><a href="<?php echo $operacion['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/operacion&token=<?php echo $token; ?>';

	var filter_cod_operacion = $('input[name=\'filter_cod_operacion\']').val();

	if (filter_cod_operacion) {
		url += '&filter_cod_operacion=' + encodeURIComponent(filter_cod_operacion);
	}

	var filter_descripcion = $('input[name=\'filter_descripcion_operacion\']').val();

	if (filter_descripcion) {
		url += '&filter_descripcion_operacion=' + encodeURIComponent(filter_descripcion);
	}
    
	var filter_metodo = $('input[name=\'filter_metodo\']').val();

	if (filter_metodo) {
		url += '&filter_metodo=' + encodeURIComponent(filter_metodo);
	}
	
	location = url;
});
//--></script>
  <script type="text/javascript"><!--
  
$('input[name=\'filter_offering_id\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/offering/autocomplete&token=<?php echo $token; ?>&filter_offering_id=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['offering_id'],
						value: item['offering_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_offering_id\']').val(item['label']);
	}
});  
  
$('input[name=\'filter_offering_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/offering/autocomplete&token=<?php echo $token; ?>&filter_offering_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['offering_name'],
						value: item['offering_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_offering_name\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>