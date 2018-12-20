<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user-group" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php  } ?>
            </div>
          </div>
<style type="text/css">.permission-label {cursor:pointer;display:block;margin:-5px 0;padding:5px 0;text-align:center;}</style>
					<div class="row">
						<div class="col-sm-2">
							<ul class="nav nav-pills nav-stacked" id="permission">
								<?php foreach (array_keys($a_permissions) as $dir) { ?>
								<li><a href="#tab-permission<?php echo $dir; ?>" data-toggle="tab"><?php echo $dir; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						<div class="col-sm-10">
							<div class="tab-content">
								<?php foreach ($a_permissions as $dir => $files) { ?>
								<div class="tab-pane" id="tab-permission<?php echo $dir; ?>">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover">
											<thead>
												<tr>
													<td>&nbsp;</td>
													<td class="text-center"><?php echo $entry_access; ?></td>
													<td class="text-center"><?php echo $entry_modify; ?></td>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($files as $file) { ?>
												<?php $permission = $dir.'/'.$file; ?>
												<tr>
													<td><?php echo $file; ?></td>
													<td><label class="permission-label">
														<?php if (in_array($permission, $access)) { ?>
														<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" class="access" />
														<?php } else { ?>
														<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" class="access" />
														<?php } ?>
													</label></td>
													<td><label class="permission-label">
														<?php if (in_array($permission, $modify)) { ?>
														<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" class="modify" />
														<?php } else { ?>
														<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" class="modify" />
														<?php } ?>
													</label></td>
												</tr>
												<?php } ?>
												<tr>
													<td>&nbsp;</td>
													<td class="text-center"><a onclick="$('#tab-permission<?php echo $dir; ?>').find(':checkbox.access').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('#tab-permission<?php echo $dir; ?>').find(':checkbox.access').prop('checked', false);"><?php echo $text_unselect_all; ?></a></td>
													<td class="text-center"><a onclick="$('#tab-permission<?php echo $dir; ?>').find(':checkbox.modify').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('#tab-permission<?php echo $dir; ?>').find(':checkbox.modify').prop('checked', false);"><?php echo $text_unselect_all; ?></a></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
<script type="text/javascript">
$('#permission a:first').tab('show');
</script>
</form><form style="display:none;">		  
		  
          
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 