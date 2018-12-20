<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <a onclick="downloadData('excel');"  target="_blank" data-toggle="tooltip" title="<?php echo $button_excel; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a> 
	  <!--<a onclick="downloadData('pdf');"  target="_blank" data-toggle="tooltip" title="<?php echo $button_pdf; ?>" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></a>-->
	  <!--<a href="<?php echo $pdf;?>&pdf=true" target="_blank" data-toggle="tooltip" title="Print as PDF" class="btn btn-info"><i class="fa fa-file-pdf-o"></i></a>-->
	  <!--
	  <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> 
	  <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  -->
	  </div>	
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
		
  <form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="export" class="form-horizontal">
  <input type="hidden" name="tipo" id="tipo">
  <input type="hidden" name="date_start" value="<?php echo $filter_date_start;?>">
  <input type="hidden" name="date_end" value="<?php echo $filter_date_end;?>">
  <input type="hidden" name="hwartcod" value="<?php echo $filter_hwartcod;?>">
  </form>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
		  
            <div class="col-sm-6">			  
              <div class="form-group">
                <label class="control-label" for="input-hwartcod"><?php echo $entry_hwartcod; ?></label>
                <input type="text" name="filter_hwartcod" value="<?php echo $filter_hwartcod; ?>" placeholder="<?php echo $entry_hwartcod; ?>" id="input-hwartcod" class="form-control" />
              </div>	
            </div>		  
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
              </div>			  
			  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
            </div>

          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-right"><?php echo $column_hwartcod; ?></td>
                <td class="text-right"><?php echo $column_hwartdesc; ?></td>
                <td class="text-right"><?php echo $column_hwcaja; ?></td>
                <td class="text-left"><?php echo $column_hwpacking; ?></td>
				        <td class="text-left"><?php echo $column_hwserie; ?></td>
                <td class="text-right"><?php echo $column_fechaing; ?></td>
                <td class="text-right"><?php echo $column_existencia; ?></td>
                <td class="text-right"><?php echo $column_disponible; ?></td>
				</tr>
            </thead>
	
            <tbody>
              <?php if ($stock) { ?>
              <?php foreach ($stock as $s) { ?>
              <tr>
                <td class="text-left"><?php echo $s['hwartcod']; ?></td>
                <td class="text-left"><?php echo $s['hwartdesc']; ?></td>
                <td class="text-right"><?php echo $s['hwcaja']; ?></td>
                <td class="text-left"><?php echo $s['hwpacking']; ?></td>             
				        <td class="text-left"><?php echo $s['hwserie']; ?></td>
                <td class="text-right"><?php echo $s['hwfechaing']; ?></td>
                <td class="text-right"><?php echo $s['existencia']; ?></td>
                <td class="text-right"><?php echo $s['disponible']; ?></td>
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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/bybomnumber&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_hwartcod = $('input[name=\'filter_hwartcod\']').val();

	if (filter_hwartcod) {
		url += '&filter_hwartcod=' + encodeURIComponent(filter_hwartcod);
	}	
	
	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,
	 format: 'dd/MM/yyyy hh:mm:ss',
});

function downloadData(t) {
$("#tipo").val(t);
$('#export').submit();
}
//--></script></div>
<?php echo $footer; ?>