<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/jquery/validate/jquery.validate.min.js"></script>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
	  <?php
	       if ($MRHW_ESTADO==0) {
	  ?>		
        <button type="submit" form="formid" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<?php
		   }
	  ?>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form id="formid" name="general">
		
	    <!-- ENCABEZADO -->
        <div class="form-row">
          <div class="row">
		  <div class="col-sm-2">
			        <div class="form-group">
			            <label  for="input-hwmr"><?php echo $entry_corr_mdr; ?></label>
			            <input height="48" readonly type="text" name="hwmr" value="<?php echo $HWMR; ?>" placeholder="<?php echo $entry_corr_mdr; ?>" id="hwmr" class="form-control form-control-sm"/>
			</div>
            </div>	
		  <div class="col-sm-3">	
					<div class="form-group">
						<label  for="input-hwmrno"><?php echo $entry_hwmrno; ?></label>
						<input type="text" style="text-transform:uppercase;" name="hwmrno" value="<?php echo $HWMRNO; ?>" placeholder="<?php echo $entry_hwmrno; ?>" id="hwmrno" class="form-control form-control-sm" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						/>
					</div>			
            </div>
          <div class="col-sm-3">			
					<div class="form-group">
						<label for="input-autonombre"><?php echo $entry_autonombre; ?></label>
						<input type="text" name="autonombre" style="text-transform:uppercase;" value="<?php echo $AUTNOMBRE; ?>" placeholder="<?php echo $entry_autonombre; ?>" id="autonombre" class="form-control form-control-sm" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						/>     							
					</div>			
			</div>
		  <div class="col-sm-3">			
					<div class="form-group">
						<label for="input-perrecibe"><?php echo $entry_perrecibe; ?></label>
						<input type="text" name="perrecibe" style="text-transform:uppercase;" value="<?php echo $PERRECIBE; ?>" placeholder="<?php echo $entry_perrecibe; ?>" id="perrecibe" class="form-control form-control-sm" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						/>     							
					</div>			
			</div>		
		  </div>
		  
		  <div class="row"> 
		  <div class="col-sm-3">	
					<div class="form-group">
						<label  for="input-emprecibe"><?php echo $entry_emprecibe; ?></label>
						<input type="text" style="text-transform:uppercase;" name="emprecibe" value="<?php echo $EMPRECIBE; ?>" placeholder="<?php echo $entry_emprecibe; ?>" id="emprecibe" class="form-control form-control-sm" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						/>
					</div>			
            </div>		 
		  <div class="col-sm-2">	
					<div class="form-group">
						<label for="input-hwviasol"><?php echo $entry_hwviasol; ?></label>
						<select name="hwviasol" id="hwviasol" class="form-control" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						> 
						<option value="*" disabled selected>--Seleccione--</option>
						<?php  
							  foreach ($viaosol as $key => $value) { ?> 
						 <?php if ($key == $filter_viasol) { ?> 
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
						<label  for="input-hwtipsol"><?php echo $entry_hwtipsol; ?></label>
						<select name="hwtipsol" id="hwtipsol" class="form-control"
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						> 
						<option value="*" disabled selected>--Seleccione--</option>
						<?php  
							  foreach ($tiposol as $key => $value) { ?> 
						 <?php if ($key == $filter_tipsol) { ?> 
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
						<label  for="input-hwsolest"><?php echo $entry_hwsolest; ?></label>
						<select name="hwsolest" id="hwsolest" class="form-control"
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						> 
						<option value="*" disabled selected>--Seleccione--</option>
						<?php  
							  foreach ($arr_estado as $key => $value) { ?> 
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
                        <label class="control-label" for="input-date-end"><?php echo $entry_hwfechaentrega; ?></label>
                        <div class="input-group date">
                        <input type="text" name="hwfechaentrega" value="<?php echo $HWFECHAENTREGA; ?>" placeholder="<?php echo $entry_hwfechaentrega; ?>" data-date-format="DD-MM-YYYY" id="hwfechaentrega" class="form-control" 
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						/>
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-default"
						<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
						><i class="fa fa-calendar"></i></button>
                        </span></div>
                        </div>  
		  </div>
		  </div>
        </div>

		<!-- FORMULARIO PARA AGREGAR ARTICULOS -->		

	    <div class="well">
		<div class="row">
			<div class="col-sm-3">			
						<div class="form-group">
							<label for="input-sitio"><?php echo $entry_sitio; ?></label>
							<input type="text" name="registro[sitio]" style="text-transform:uppercase;" value="<?php echo $SITIO; ?>" placeholder="<?php echo $entry_sitio; ?>" id="registro[sitio]" class="form-control form-control-sm" 
							<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
							/>     							
						</div>			
				</div>
			<div class="col-sm-3">			
						<div class="form-group">
							<label for="input-subcuenta"><?php echo $entry_subcuenta; ?></label>
							<input type="text" name="registro[subcuenta]" style="text-transform:uppercase;" value="<?php echo $SUBCUENTA; ?>" placeholder="<?php echo $entry_subcuenta; ?>" id="registro[subcuenta]" class="form-control form-control-sm" 
							<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
							/>     							
						</div>			
				</div>
			<div class="col-sm-3">			
						<div class="form-group">
							<label for="input-serie"><?php echo $entry_serie; ?></label>
							<input type="text" name="registro[serie]" style="text-transform:uppercase;" value="<?php echo $SERIE; ?>" placeholder="<?php echo $entry_serie; ?>" id="registro[serie]" class="form-control form-control-sm" 
							<?php echo ($MRHW_ESTADO==1)?' disabled':''; ?>
							/>     							
						</div>			
				</div>
		</div>
        <div class="row">
              <div class="table-responsive">
                <table id="registro" class="table table-bordered">
				<thead>
					<tr>
						<td class="text-left"><?php echo $column_articulo; ?></td>
						<td class="text-left"><?php echo $column_descripcion; ?></td>
						<td class="text-left"><?php echo $column_linea; ?></td>
						<td class="text-left"><?php echo $column_packing; ?></td>
						<td class="text-left"><?php echo $column_caja; ?></td>
						<td class="text-left"><?php echo $column_cdisp; ?></td>
						<td class="text-left"><?php echo $column_centregada; ?></td>
						<td class="text-left"><?php echo $column_csolicitada; ?></td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<tr id="registro">
						<td class="text-right">
						<input type="text" readonly name="registro[hwartcod]" id="registro[hwartcod]" style="text-transform:uppercase;" placeholder="<?php echo $entry_hwartcod; ?>"  class="form-control icon_search" />
						</td>
						<td class="text-right">
						<input type="text" readonly name="registro[hwartdesc]" id="registro[hwartdesc]" class="form-control" />
						</td>
						<td class="text-right">
						<input type="text" readonly name="registro[hwlinea]" id="registro[hwlinea]" placeholder="<?php echo $entry_hwlinea; ?>" class="form-control" />
						</td>
						<td class="text-right">
						<input type="text" readonly style="text-transform:uppercase;" id="registro[hwpacking]" name="hwpacking" onkeydown="autocompletarPack(this,<?php echo $detalle_row; ?>);"  placeholder="<?php echo $entry_hwpacking; ?>" class="form-control" />
						</td>
						<td class="text-right">
						<input type="text" readonly name="registro[hwcaja]" id="registro[hwcaja]" placeholder="<?php echo $entry_hwcaja; ?>" class="form-control" />
						</td>					  									
						<td class="text-right">
						<input type="text" readonly name="registro[hwdisp]" id="registro[hwdisp]"   placeholder="<?php echo $entry_hwcantdis; ?>" class="form-control" />
						</td>
						<td class="text-right">
						<input type="number" readonly name="registro[hwsolcant]" onchange="autocompletarCant(this)" id="registro[hwsolcant]"  placeholder="<?php echo $entry_hwsolcant; ?>" class="form-control" />
						</td>
						<td class="text-right">
						<input type="text" readonly name="registro[hwsolaent]"  id="registro[hwsolaent]"  placeholder="<?php echo $entry_hwsolcant; ?>" class="form-control" />
						</td>
						<td class="text-left">
						<button type="button" onclick="validar();" data-toggle="tooltip" title="<?php echo $button_detalle_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
						</td>					
						</tr>
				</tbody>
			</table>
		</div>
		</div>		
		</div>  <!-- well -->
		
		<div id='oculto' class="table-responsive" style='display:none;'>

		</div>	  

		<hr>
	   </form>
        <div id="tab-cart">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
						<td class="text-left"><?php echo $column_articulo; ?></td>
						<td class="text-left"><?php echo $column_descripcion; ?></td>
						<td class="text-left"><?php echo $column_linea; ?></td>
						<td class="text-left"><?php echo $column_packing; ?></td>
						<td class="text-left"><?php echo $column_caja; ?></td>
						<td class="text-left"><?php echo $column_csolicitada; ?></td>						
						<td class="text-left"><?php echo $column_centregada; ?></td>
						<td class="text-left"><?php echo $column_sitio; ?></td>
						
						<?php
						 if ($MRHW_ESTADO==0) {  
						?>
                        <td><?php echo $column_action; ?></td>
						<?php
						 }  
						?>
                    </tr>
                  </thead>
                  <tbody id="detalle">
                    <?php if ($solicitud_detalles) { ?>
                    <?php $product_row = 0; ?>
                    <?php foreach ($solicitud_detalles as $order_product) { ?>
                    <tr>
                
                      <td class="text-left"><?php echo $order_product['HWARTCOD']; ?></td>
                      <td class="text-left"><?php echo $order_product['HWARTDESC']; ?></td>
                      <td class="text-right"><?php echo $order_product['HWLINEA']; ?></td>
                      <td class="text-right"><?php echo $order_product['HWPACKING']; ?></td>
					  <td class="text-right"><?php echo $order_product['HWCAJA']; ?></td>
					  <td class="text-right"><?php echo $order_product['HWSOLAENT']; ?></td>
					  <td class="text-right"><?php echo $order_product['HWSOLCANT']; ?></td>		 
                      <td class="text-center"><?php echo $order_product['SITNOM']; ?></td>
					  
					  <?php
						 if ($MRHW_ESTADO==0) {  
						?>
                        
					  <td class="text-left"><button type="button" onclick="elimina(this);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
					  
					  <?php
						 }  
						?>
                    </tr>
                    <?php $product_row++; ?>
                    <?php } ?>
            
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>

            </div>

    </div> <!-- panel-body -->
  </div> <!-- panel-default -->
  </div> <!-- container fluid -->
  </div> <!-- content -->
 <script type="text/javascript"><!-- 
$(document).ready(function() {
    $("#ok").hide();

    $("#formid").validate({
		event: "blur",
		onkeyup: false, //Apaga la validacion mientras se escribe
        rules: {
              autonombre: {
                     required: true, minlength: 1, maxlength: 40,
                     remote: {
                     url: "index.php?route=tracking/solicitud/validaAutorizado&token=<?php echo $token; ?>",
                     type: "post",
                     data: {
                            autonombre: function() {
                              return $('input[name=\'autonombre\']').val();
                            }
                           } 	   
                     }
              },
			  hwmrno: {
                     required: true, minlength: 1, maxlength: 20,
                     remote: {
                     url: "index.php?route=tracking/solicitud/validaMdr&token=<?php echo $token; ?>",
                     type: "post",
                     data: {
                            hwmrno: function() {
                              return $('input[name=\'hwmrno\']').val();
                            },
							hwmr: function() {
                              return $('input[name=\'hwmr\']').val();
                            }
                           } 	   
                     }
              }
        },
        messages: {
            autonombre: {
				required :"Ingrese solicitante",
				minlength:"El largo debe ser >= 1 y <= 40",
				maxlength:"El largo debe ser >= 1 y <= 40",
			    remote: jQuery.validator.format("{0} no esta autorizado!.")
				},
			hwmrno: {
				maxlength:"El largo debe ser <= 20",
				required:"Ingrese MDR",
			    remote: jQuery.validator.format("{0} ya existe!.")
			}
        },
        submitHandler: function(form){
            var dataString = 'autonombre='+$('#autonombres').val();
            $.ajax({
                type: "POST",
                url : "index.php?route=tracking/solicitud/validarSolicitud&token=<?php echo $token; ?>",
                data: dataString,
                success: function(data){
                    $("#ok").html(data);
                    $("#ok").show();
                    //$("#formid").hide();
                }
            });
        },
		highlight: function(element) {
        $(element).css('background', '#ffdddd');
        },
		unhighlight: function(element) {
        $(element).css('background', '#ffffff');
        }
    });
});
 
function cargaArticulos(x){

	document.getElementById('oculto').style.display = 'block';
	
	var hwartcod = x;
	var hwsolest = document.getElementById("hwsolest").value;
    var html ='';
	var row  = 0;
	
	hwartcod = hwartcod.toUpperCase();

		$.ajax({
			url: 'index.php?route=tracking/solicitud/autocompleteCantidad&token=<?php echo $token; ?>',
			type: 'POST',
			data: {hwartcod,hwsolest},
			dataType: "json",
			success: function(data){
				$("#oculto").html('');
				if(data != null && $.isArray(data)){
					
					html= "<p>Lista de articulos con disponibilidad</p>"+
			              "<table id='detalle' class='table table-striped table-bordered table-hover'>"+
				          "<thead>"+
				          "	<tr>"+
				          "		<td class='text-left'><?php echo $column_elige; ?></td>"+
				          "		<td class='text-left'><?php echo $column_linea; ?></td>"+
				          "		<td class='text-left'><?php echo $column_packing; ?></td>"+
				          "		<td class='text-left'><?php echo $column_caja; ?></td>"+
				          "		<td class='text-left'><?php echo $column_cdisp; ?></td>"+
				          "	</tr>"+
				          "</thead>"+
				          "<tbody>";

					$.each(data, function(index, value){
						
					html +=	"<tr name='"+row+"'>"+
					        "<td><input id='"+ row +"' onclick='selOpcion("+row+")' type='radio' name='radio'></td>"+
					        "<td id='linea"+row+"'>" + value.linea + "</td>"+
							"<td id='hwpacking"+row+"'>" + value.hwpacking + "</td>"+
							"<td id='caja"+row+"'>" + value.caja + "</td>"+
							"<td id='dispo"+row+"'>" + value.disponible + "</td>"+
							"</tr>";
						
						
						row+=1;
					});
				} else {
					html="<div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> No hay existencia de este articulo!<button type='button' class='close' data-dismiss='alert'>&times;</button></div>";
				}
				
				html+="</tbody>"+
			          "</table>";
                $("#oculto").append(html);    
			}
		})
}

function refrescar(){
	location.reload();
	//location.reload(true);
	/*var hwmr = document.getElementById("hwmr").value;

		$.ajax({
			url: 'index.php?route=tracking/solicitud/refrescar&token=<?php echo $token; ?>',
			type: 'POST',
			data: {hwmr},
			dataType: "json",
			success: function(data){
 
				$("#detalle").html('');
				if(data != null && $.isArray(data)){

					$.each(data, function(index, value){

					var estado = '<?php echo $MRHW_ESTADO;?>';	
						
					var html="<tr>"+
                          "<td class='text-left'>"+value.hwartcod+"</td>"+
                          "<td class='text-left'>"+value.hwartdesc+"</td>"+
                          "<td class='text-right'>"+value.hwlinea+"</td>"+
                          "<td class='text-right'>"+value.hwpacking+"</td>"+
					      "<td class='text-right'>"+value.hwcaja+"</td>"+
					      "<td class='text-right'>"+value.hwsolaent+"</td>"+
					      "<td class='text-right'>"+value.hwsolcant+"</td>"+	 
                          "<td class='text-center'>"+value.sitnom+"</td>";
					  
					if (estado==0) {
					  html+="<td class='text-center'><button type='button' onclick='elimina(this);' data-toggle='tooltip' title='<?php echo $button_remove; ?>' class='btn btn-danger'><i class='fa fa-minus-circle'></i></button></td>";
					}
                    html+="</tr>";
					$("#detalle").append(html);

					});
				}
			}
		});*/
}

function selOpcion(row) {

	var linea 		=	document.getElementById('linea'+row).innerHTML;
	var packing 	=	document.getElementById('hwpacking'+row).innerHTML;
	var caja 			=	document.getElementById('caja'+row).innerHTML;
	var cantidad 	=	document.getElementById('dispo'+row).innerHTML;

	document.getElementById("registro[hwpacking]").value = packing;
	document.getElementById("registro[hwdisp]").value = cantidad;
	document.getElementById("registro[hwsolcant]").value = cantidad;
	document.getElementById("registro[hwsolaent]").value = cantidad;
	document.getElementById("registro[hwcaja]").value = caja;
	document.getElementById("registro[hwlinea]").value = linea;

}

function autocompletarCant(v){
	var cant = 0;
	var dispo = 0;
	var hwsolaent = 0;
	
    var cant = document.getElementById("registro[hwsolcant]").value;
    var dispo = document.getElementById("registro[hwdisp]").value;
		
	if (dispo > cant){
			hwsolaent = cant;
		}
		else{
			hwsolaent = dispo;
		}
		document.getElementById("registro[hwsolaent]").value = hwsolaent;
}

function validar() {
  	autocompletarCant(this);
  	var hwmr 			= document.getElementById("hwmr").value;
  	var autonombre 		= document.getElementById("autonombre").value;
  	var hwmrno 			= document.getElementById("hwmrno").value;
  	var hwviasol		= document.getElementById("hwviasol").value;
  	var hwtipsol		= document.getElementById("hwtipsol").value;
  	var hwfechaentrega	= document.getElementById("hwfechaentrega").value;
	var clinea = 0;
	var sitnom 		= document.getElementById("registro[sitio]").value;
  	var hwartcod 	= document.getElementById("registro[hwartcod]").value;
  	var hwsolcant 	= document.getElementById("registro[hwsolcant]").value;
  	var hwlinea 	= document.getElementById("registro[hwlinea]").value;
 	var hwpacking	= document.getElementById("registro[hwpacking]").value;
  	var hwcaja 		= document.getElementById("registro[hwcaja]").value;
  	var hwsolest  	= document.getElementById("hwsolest").value;
  	var hwsolaent 	= document.getElementById("registro[hwsolaent]").value;

  if (hwartcod===undefined) {validar=0;} else {validar=1;}

		if (validar==1) {
			if (hwartcod&&hwsolcant) {
				$.ajax({
					url: 'index.php?route=tracking/solicitud/postDetalle&token=<?php echo $token; ?>&hwmr=' + hwmr + '&clinea=' + clinea + '&hwartcod=' +  hwartcod + '&hwsolcant=' + hwsolcant + '&hwlinea=' + hwlinea + '&hwpacking=' + hwpacking + '&hwcaja=' +  hwcaja + '&hwsolest=' + hwsolest + '&hwsolaent=' + hwsolaent + '&sitnom=' + sitnom +
					'&autonombre=' + autonombre + '&hwmrno=' + hwmrno + '&hwviasol=' + hwviasol + '&hwtipsol=' + hwtipsol + '&hwfechaentrega=' + hwfechaentrega,
					dataType: 'json',
					
				});
				
				limpiar();	
			}	
		}	else {
				// adddetalle(); 
		}	
	refrescar();	
}	

function activaInput(v){
	document.getElementById("registro[hwpacking]").readOnly = v;
	document.getElementById("registro[hwdisp]").readOnly = true;
	document.getElementById("registro[hwsolcant]").readOnly = v;
	document.getElementById("registro[hwsolaent]").readOnly = true;
	document.getElementById("registro[hwcaja]").readOnly = v;
	document.getElementById("registro[hwlinea]").readOnly = v;
	document.getElementById("registro[hwartcod]").readOnly = v;
	document.getElementById("registro[hwartdesc]").readOnly = true;
}

 function limpiar(){
	var limpieza = "";
	document.getElementById('oculto').style.display = 'none';
	document.getElementById("registro[hwpacking]").value = limpieza;
	document.getElementById("registro[hwdisp]").value = limpieza;
	document.getElementById("registro[hwsolcant]").value = limpieza;
	document.getElementById("registro[hwsolaent]").value = limpieza;
	document.getElementById("registro[hwcaja]").value = limpieza;
	document.getElementById("registro[hwlinea]").value = limpieza;
	document.getElementById("registro[hwartcod]").value = limpieza;
	document.getElementById("registro[hwartdesc]").value = limpieza;
	document.getElementById("registro[sitio]").value = limpieza;
	document.getElementById("registro[subcuenta]").value = limpieza;
	activaInput(true);
	
	/*refrescar();*/
}

$('input[name=\'registro[subcuenta]\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=tracking/solicitud/llenaSubcuenta&token=<?php echo $token; ?>&subcuenta=' + request + '&estado',
			dataType: 'json',
			success: function(json) {

				if (json != null && $.isArray(json)){	

					response($.map(json, function(item) {
						return {
							label: item['tigosubcta_descrip'],
							value: item['tigosubcta_descrip']
						}
					}));
				} else {
					response('');
				}	
			}
		});
	},
	'select': function(item) {
		$('input[name=\'registro[subcuenta]\']').val(item['label']);
		activaInput(false)
	},
	'focus':  function() {
	$(this).keydown();
	}
});

$('input[name=\'registro[sitio]\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=tracking/solicitud/llenaSitio&token=<?php echo $token; ?>&hwsitio=' + request + '&estado',
			dataType: 'json',
			success: function(json) {

				if (json != null && $.isArray(json)){	

					response($.map(json, function(item) {
						return {
							label: item['sitnom'],
							value: item['sitnom']
						}
					}));
				} else {
					response('');
				}	
			}
		});
	},
	'select': function(item) {
		$('input[name=\'registro[sitio]\']').val(item['label']);
		activaInput(false)
	},
	'focus':  function() {
	$(this).keydown();
	}
});

$('input[name=\'registro[hwartcod]\']').autocomplete({
    'source': function(request, response) {
	  /*var match=$.ui.autocomplete.escapeRegex( request.term );*/	
	  /* http://192.168.10.17/webpicking/admin/index.php?route=tracking/solicitud/llenaArt&token=ve6aD9LY4oVqRAixXIhdlfD7PNeYFKjE&hwartcod=ACC00000
	  */
      $.ajax({
        url: 'index.php?route=tracking/solicitud/llenaArt&token=<?php echo $token; ?>&hwartcod=' + request+'&estado',
        dataType: 'json',
        success: function(json) {

		if (json != null && $.isArray(json)){	
	
     		response($.map(json, function(item) {
            return {
              label: item['artcod'],
              value: item['artcod'],
			  			descr: item['artdes']
            }
          }));
		} else {
		  response('');
		}	
	
		}
      });
    },
    'select': function(item) {
      $('input[name=\'registro[hwartcod]\']').val(item['label']);
		$('input[name=\'registro[hwartdesc]\']').val(item['descr']);
	  	cargaArticulos(item['label']);
    },
	 autoFocus: true
}); 

$('.date').datetimepicker({
	pickTime: false
}); 
--></script>
<?php echo $footer; ?>