<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			
		   <?php
			   if ($offering['subscription_offering_type']=='Primary') {
            ?>		
            <li><a href="#tab-attribute" data-toggle="tab"><?php echo "Accounts"; ?></a></li>
			<?php
			   } else {   
			 ?>
            <li><a href="#tab-detalle" data-toggle="tab"><?php echo "Supplementary Det"; ?></a></li>
			<?php
			   }
			?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="tab-content">
				<!--offering_id-->
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-offering_id"><?php echo $entry_offering_id; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering_id" 
					         value="<?php echo $offering['offering_id'];?>" placeholder="<?php echo $entry_offering_id; ?>" 
							 id="input-offering_id" class="form-control" />
                    </div>
				</div>
				<!--offering_name -->					
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-offering_name"><?php echo $entry_offering_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering_name" 
					         value="<?php echo $offering['offering_name'];?>" placeholder="<?php echo $entry_offering_name; ?>" 
							 id="input-offering_name" class="form-control" />
                    </div>					
				</div>
				<!--Payment Mode -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-payment_mode"><?php echo $entry_payment_mode; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="payment_mode" 
					         value="<?php echo $offering['payment_mode']; ?>" placeholder="<?php echo $entry_payment_mode; ?>" 
							 id="input-payment_mode" class="form-control" />
                    </div>					
				</div>				

				<!--Subscription Offering Type -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-subscription_offering_type"><?php echo $entry_subofftype; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="subscription_offering_type" 
					         value="<?php echo $offering['subscription_offering_type']; ?>" placeholder="<?php echo $entry_subofftype; ?>" 
							 id="input-subscription_offering_type" class="form-control" />
                    </div>					
				</div>					

				<!--Subscription Offering Short Name -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-offering_short_name"><?php echo $entry_offering_short_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering_short_name" 
					         value="<?php echo $offering['offering_short_name']; ?>" placeholder="<?php echo $entry_offering_short_name; ?>" 
							 id="input-offering_short_name" class="form-control" />
                    </div>					
				</div>					

				<!--Offering Code -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-offering_code"><?php echo $entry_offering_code; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering_code" 
					         value="<?php echo $offering['offering_code']; ?>" placeholder="<?php echo $entry_offering_code; ?>" 
							 id="input-offering_code" class="form-control" />
                    </div>					
				</div>

				<!-- Catalog -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-catalog"><?php echo $entry_catalog; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="catalog" 
					         value="<?php echo $offering['catalog']; ?>" placeholder="<?php echo $entry_catalog; ?>" 
							 id="input-catalog" class="form-control" />
                    </div>					
				</div>

				<!-- Rent Charge -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-rent_charge"><?php echo $entry_rent_charge; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering[][rent_charge]" 
					         value="<?php echo $offering['rent_charge']; ?>" placeholder="<?php echo $entry_rent_charge; ?>" 
							 id="input-rent_charge" class="form-control" />
                    </div>					
				</div>

				<!-- Sort Order -->					
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort_order"><?php echo $entry_sort_order; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offering[][sort_order]" 
					         value="<?php echo $offering['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" 
							 id="input-sort_order" class="form-control" />
                    </div>					
				</div>
              </div>
            </div>
		    <?php
			   if ($offering['subscription_offering_type']=='Primary') {
            ?>
            <div class="tab-pane" id="tab-attribute">
              <div class="table-responsive">
                <table id="attribute" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_account_name; ?></td>
                      <td class="text-left"><?php echo $entry_balancetype; ?></td>
					  <td class="text-left"><?php echo $entry_account_id; ?></td>
					  <td class="text-left"><?php echo $entry_comverse_name; ?></td>
					  <td class="text-left"><?php echo $entry_sort_order; ?></td>
                      <td class="text-left"><?php echo $entry_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $account_row = 0; ?>
                    <?php foreach ($accounts as $account) { ?>
                    <tr id="attribute-row<?php echo $account_row; ?>">
                      <td class="text-left" style="width: 20%;">
                        <input type="hidden" name="accounts[<?php echo $account_row; ?>][offering_id]" value="<?php echo $account['offering_id']; ?>" />
					    <input type="text" name="accounts[<?php echo $account_row; ?>][account_name]" value="<?php echo $account['account_name']; ?>" placeholder="<?php echo $entry_account_name; ?>" class="form-control" />
					  </td>
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="accounts[<?php echo $account_row; ?>][balancetype]" value="<?php echo $account['balancetype']; ?>" placeholder="<?php echo $entry_balancetype; ?>" class="form-control" />
					  </td>
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="accounts[<?php echo $account_row; ?>][account_id]" value="<?php echo $account['account_id']; ?>" placeholder="<?php echo $entry_account_id; ?>" class="form-control" />
					  </td>
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="accounts[<?php echo $account_row; ?>][comverse_name]" value="<?php echo $account['comverse_name']; ?>" placeholder="<?php echo $entry_comverse_name; ?>" class="form-control" />
					  </td>					  
                      <td class="text-left" style="width: 10%;">
					    <input type="text" name="accounts[<?php echo $account_row; ?>][sort_order]" value="<?php echo $account['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
					  </td>		
                      <td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $account_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $account_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
			<?php 
			   }
			?>

		    <?php
			   if ($offering['subscription_offering_type']!='Primary') {
            ?>			
            <div class="tab-pane" id="tab-detalle">
              <div class="table-responsive">
                <table id="detalle" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_corr_id; ?></td>
                      <td class="text-left"><?php echo $entry_free_unit; ?></td>
					  <td class="text-left"><?php echo $entry_free_unit_amount; ?></td>
					  <td class="text-left"><?php echo "Unidad"; ?></td>
					  <td class="text-left"><?php echo "Validez"; ?></td>
					  <td class="text-left"><?php echo "Recurrencia"; ?></td>
                      <td class="text-left"><?php echo $entry_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $suppdet_row = 0; ?>
                    <?php foreach ($supplementaryDet as $suppdet) { ?>
                    <tr id="detalle-row<?php echo $suppdet_row; ?>">
                      <td class="text-left" style="width: 20%;">
                        <input type="hidden" name="supplementaryDet[<?php echo $suppdet_row; ?>][offering_id]" value="<?php echo $suppdet['offering_id']; ?>" />
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][correlativo]" value="<?php echo $suppdet['correlativo']; ?>" placeholder="<?php echo $entry_corr_id; ?>" class="form-control" />
					  </td>
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][account_name]" value="<?php echo $suppdet['account_name']; ?>" placeholder="<?php echo $entry_free_unit; ?>" class="form-control" />
					  </td>

                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][amount]" value="<?php echo $suppdet['amount']; ?>" placeholder="<?php echo $entry_free_unit_amount; ?>" class="form-control" />
					  </td>
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][unit]" value="<?php echo $suppdet['unit']; ?>" placeholder="<?php echo $entry_expiring_date; ?>" class="form-control" />
					  </td>					  
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][validity]" value="<?php echo $suppdet['validity']; ?>" placeholder="<?php echo $entry_expiring_date; ?>" class="form-control" />
					  </td>		            				  
                      <td class="text-left" style="width: 20%;">
					    <input type="text" name="supplementaryDet[<?php echo $suppdet_row; ?>][recurrency]" value="<?php echo $suppdet['recurrency']; ?>" placeholder="<?php echo $entry_expiring_date; ?>" class="form-control" />
					  </td>							  
                      <td class="text-left"><button type="button" onclick="$('#detalle-row<?php echo $suppdet_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $suppdet_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
		    <?php
			   }
            ?>				
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript"><!--

//--></script>
  <script type="text/javascript"><!--
var account_row = <?php echo $account_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + account_row + '">';
	html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + account_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + account_row + '][attribute_id]" value="" /></td>';
	html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><textarea name="product_attribute[' + account_row + '][product_attribute_description][][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + account_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

	$('#attribute tbody').append(html);

	attributeautocomplete(account_row);

	account_row++;
}

function attributeautocomplete(account_row) {
	$('input[name=\'product_attribute[' + account_row + '][name]\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'product_attribute[' + account_row + '][name]\']').val(item['label']);
			$('input[name=\'product_attribute[' + account_row + '][attribute_id]\']').val(item['value']);
		}
	});
}

$('#attribute tbody tr').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script>
  <script type="text/javascript"><!--
var option_row = <?php echo $option_row; ?>;

//--></script>
  <script type="text/javascript"><!--
var option_value_row = <?php echo $option_value_row; ?>;

//--></script>
  <script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

//--></script>
  <script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

//--></script>
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

//--></script>
  <script type="text/javascript"><!--
var recurring_row = <?php echo $recurring_row; ?>;


//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>
