<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
     
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
			<div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-cormdr"><?php echo $entry_cormdr; ?></label>
                <input type="text" name="filter_cormdr" value="<?php echo $filter_cormdr; ?>" placeholder="<?php echo $entry_cormdr; ?>" id="input-cormdr" class="form-control" />
              </div>
              <div class="form-group">
              </div>
            </div>
            
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-estado"><?php echo $entry_estado; ?></label>
                 <select name="filter_estado" id="input-group" class="form-control"> 
				 <option value="*" disabled selected>--Seleccione--</option>
				 <?php  
				      foreach ($status as $key => $value) { ?> 
                 <?php if ($key == $filter_estado) { ?> 
				 <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?>
				 </option> 
				 <?php } 
				 else { ?> 
				 <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php } ?> 
				 <?php } ?> 
				 </select>
              </div>
            </div>
			
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo "Otro estado"; ?></label>
                 <select name="filter_status" id="input-group" class="form-control"> 
				 <option value="0" selected>--Todos--</option>
				 <?php  
				      foreach ($estado as $key => $value) { ?> 
                 <?php if ($key == $filter_status) { ?> 
				 <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?>
				 </option> 
				 <?php } 
				 else { ?> 
				 <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php } ?> 
				 <?php } ?> 
				 </select>
              </div>
            </div>			
			
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-starttime"><?php echo $column_starttime; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_starttime" value="<?php echo $filter_starttime; ?>" placeholder="<?php echo $entry_starttime; ?>" data-date-format="DD-MM-YYYY" id="input-startime" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
				 </div>
              </div>
			  </div>
			  <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-endtime"><?php echo $column_endtime; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_endtime" value="<?php echo $filter_endtime; ?>" placeholder="<?php echo $entry_endtime; ?>" data-date-format="DD-MM-YYYY" id="input-endtime" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'hwmr') { ?>
                    <a href="<?php echo $sort_hwmr; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_md; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_hwmr; ?>"><?php echo $column_md; ?></a>
                    <?php } ?>
                  </td>
                  
                  <td class="text-center"><?php echo $column_fsolicitud; ?></td>
				  <td class="text-center"><?php echo $column_fentrega ?></td>
				  <td class="text-center"><?php echo $column_entregado; ?></td>
				  <td class="text-center"><?php echo $column_estado; ?></td>
                  <td class="text-center"><?php echo "Otro estado"; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($solicitudes) { 
                     foreach ($solicitudes as $solicitud) { 
                ?>
                <tr bgcolor="<?php echo $solicitud['color'];?>" style="color:#000000;">
				  <td class="text-center"><?php echo $solicitud['hwmr']; ?></td>
                  
                  <td class="text-center"><?php echo $solicitud['hwfechasol']; ?></td>
                  <td class="text-center"><?php echo $solicitud['hwfechaentrega']; ?></td>
                  <td class="text-center"><?php echo $solicitud['hwentregado']; ?></td>
                  <td class="text-center"><?php echo $solicitud['mrhw_estado']; ?></td>
                  <td class="text-center"><?php echo $solicitud['estado']; ?></td>
                </tr>
                  
                <?php
                }
                
                 } else { ?>
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
  
  /*
    Como probar el autocomplete:
	
	 http://192.168.10.17/webpicking/admin/index.php?route=report/solicitud/autocomplete&token=2ddLpvR5LiAipU7L46isnQUTUBCqN4zR&filter_mdr=AJAJUSTE
  */
  
  $('input[name=\'filter_mdr\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=report/solicitud/autocomplete&token=<?php echo $token; ?>&filter_mdr=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['mdr'],
              value: item['mdr']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'filter_mdr\']').val(item['label']);
    }
});  

	function doSearch(){
					url = 'index.php?route=report/solicitud&token=<?php echo $token; ?>';
					var estado=0;
					var filter_cormdr = $('input[name=\'filter_cormdr\']').val();
					var filter_mdr = $('input[name=\'filter_mdr\']').val();
					var filter_starttime = $('input[name=\'filter_starttime\']').val();
					var filter_endtime = $('input[name=\'filter_endtime\']').val();
					
					var filter_estado = document.getElementById("input-group"); 
					if (filter_estado) { estado = filter_estado.options[filter_estado.selectedIndex].value; 
					                     url += '&filter_estado=' + encodeURIComponent(estado); }
										 
					var filter_status = $('select[name=\'filter_status\']').val();
	
	                if (filter_status) {
		              url += '&filter_status=' + encodeURIComponent(filter_status);
	                }					 
										 
								   
					if (filter_cormdr != 0) {
								   url += '&filter_cormdr=' + encodeURIComponent(filter_cormdr);
					}
					
					if (filter_mdr != 0) {
								   url += '&filter_mdr=' + encodeURIComponent(filter_mdr);
					}

					if (filter_starttime) {
						url += '&filter_starttime=' + encodeURIComponent(filter_starttime);
					}

					if (filter_endtime) {
						url += '&filter_endtime=' + encodeURIComponent(filter_endtime);
					}

					location = url;    
	}  
	  
	$('#input-mdr').keydown(function(e) {
					if (e.keyCode == 13) {
					  doSearch();      
					}
	});   

	$('#button-filter').on('click', function() {
					  doSearch();
	});

//--></script> 

  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 