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
                <input type="text" name="filter_cod_operacion" value="<?php echo $filter_cod_operacion; ?>" placeholder="<?php echo $entry_cod_operacion; ?>" id="input-cod_operacion" class="form-control" />
              </div>
              <div class="form-group">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-offering_name"><?php echo $entry_offering_name; ?></label>
                <input type="text" name="filter_offering_name" value="<?php echo $filter_offering_name; ?>" placeholder="<?php echo $entry_offering_name; ?>" id="input-offering_name" class="form-control" />
              </div>
              <div class="form-group">
              </div> 
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-subofftype"><?php echo $entry_offering_subofftype; ?></label>
                <select name="filter_subofftype" id="input-subofftype" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_subofftype) { ?>
                  <option value="1" selected="selected"><?php echo "Primary"; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo "Primary"; ?></option>
                  <?php } ?>
                  <?php if (!$filter_subofftype && !is_null($filter_subofftype)) { ?>
                  <option value="0" selected="selected"><?php echo "Supplementary"; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo "Supplementary"; ?></option>
                  <?php } ?>
                </select>
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
                  				  
				  <td class="text-left"><?php if ($sort == 'offering_id') { ?>
                    <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_offering_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_id; ?>"><?php echo $column_offering_id; ?></a>
                    <?php } ?></td>
				  
                  <td class="text-left"><?php if ($sort == 'offering_name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_offering_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_offering_name; ?></a>
                    <?php } ?></td>
				  <td class="text-left"><?php echo $column_subofftype; ?></td> 	
                  <td class="text-left"><?php if ($sort == 'sort_order') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>
                    <?php } ?></td>
					
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($offerings) { ?>
                <?php foreach ($offerings as $offering) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($offering['offering_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $offering['offering_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $offering['offering_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $offering['offering_id']; ?></td>
                  <td class="text-left"><?php echo $offering['offering_name']; ?></td>
				  <td class="text-left"><?php echo $offering['subscription_offering_type']; ?></td>
                  <td class="text-left"><?php echo $offering['sort_order']; ?></td>
                  <td class="text-right"><a href="<?php echo $offering['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
	var url = 'index.php?route=catalog/offering&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_offering_name\']').val();

	if (filter_name) {
		url += '&filter_offering_name=' + encodeURIComponent(filter_name);
	}

	var filter_id = $('input[name=\'filter_offering_id\']').val();

	if (filter_id) {
		url += '&filter_offering_id=' + encodeURIComponent(filter_id);
	}
    
	var filter_subofftype = $('select[name=\'filter_subofftype\']').val();

	if (filter_subofftype != '*') {
		url += '&filter_subofftype=' + encodeURIComponent(filter_subofftype);
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