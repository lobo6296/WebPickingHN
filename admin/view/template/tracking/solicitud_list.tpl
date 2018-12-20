<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
	  <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-user').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                <label class="control-label" for="input-cormdr"><?php echo $entry_cormdr; ?></label>
                <input type="text" name="filter_cormdr" value="<?php echo $filter_cormdr; ?>" placeholder="<?php echo $entry_cormdr; ?>" id="input-cormdr" class="form-control" />
              </div>
              <div class="form-group">
              </div>
            </div>
            
        </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'hwmr') { ?>
                    <a href="<?php echo $sort_hwmr; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_mdr; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_hwmr; ?>"><?php echo $column_mdr; ?></a>
                    <?php } ?></td>

                   
					
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fsolicitud; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_fsolicitud; ?></a>
                    <?php } ?></td>
					<td class="text-center"><?php echo "Otro Estado"; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($solicitudes) { ?>
                <?php foreach ($solicitudes as $solicitud) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($solicitud['hwmr'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $solicitud['hwmr']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $solicitud['hwmr']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $solicitud['hwmr']; ?></td>
				  
                  <td class="text-left"><?php echo $solicitud['mrhw_estadodes']; ?></td>
                  <td class="text-left"><?php echo $solicitud['hwfechasol']; ?></td>
				  <td class="text-left"><?php echo $solicitud['estado']; ?></td>
                  <td class="text-right"><a href="<?php echo $solicitud['edit']; ?>" data-toggle="tooltip" title="<?php echo ($solicitud['mrhw_estado']==0)?$button_edit:$button_view; ?>" class="btn <?php echo ($solicitud['mrhw_estado']==0)?'btn-primary':'btn-info'; ?>"><i class="<?php echo $solicitud['clase']; ?>"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
</div>
<div id="confirmBox">
    <div class="message"></div>
    <span class="yes">Yes</span>
    <span class="no">No</span>
</div>
<script type="text/javascript">
  function doConfirm(msg, yesFn, noFn)
  {
      var confirmBox = $("#confirmBox");
      confirmBox.find(".message").text(msg);
      confirmBox.find(".yes,.no").unbind().click(function()
      {
          confirmBox.hide();
      });
      confirmBox.find(".yes").click(yesFn);
      confirmBox.find(".no").click(noFn);
      confirmBox.show();
  }

  function doSearch(){
					url = 'index.php?route=tracking/solicitud/getList&token=<?php echo $token; ?>';
          
					var filter_cormdr = $('input[name=\'filter_cormdr\']').val();
					var filter_mdr = $('input[name=\'filter_mdr\']').val();

					if (filter_cormdr != 0) {
								   url += '&filter_cormdr=' + encodeURIComponent(filter_cormdr);
					}
					
					if (filter_mdr != 0) {
								   url += '&filter_mdr=' + encodeURIComponent(filter_mdr);
					}
         
					location = url;    
	}  

  $('#button-filter').on('click', function() {
					  doSearch();
	});
</script>
<?php echo $footer; ?> 