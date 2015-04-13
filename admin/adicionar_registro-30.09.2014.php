<?php
require_once('config.class.php');
$conexion = new SibasDB();
?>
<style type="text/css">
 .errorMessage{
	display:block;
	color:#d44d24;
	font-size:11px;
	width:100% !important;
	margin-top:1px;
	padding:0 !important;
 }
 .loading-fac{
	background: #FFFFFF url(images/loading30x30.gif) top center no-repeat;
	height: 0px;
	margin: 10px 0;
	text-align: center;
	font-size: 90%;
	font-weight: bold;
	color: #0075AA;
}
</style>
<script type="text/javascript">
	var lang = new Lang("es");
	lang.dynamic("en", "js/langpack/en.json");
</script>
<script type="text/javascript">
$(document).ready(function(e) {
        //ADICIONAR CORREO
		$('#formCreaCorreo').submit(function(e){
			    var producto = $("#producto option:selected").prop('value');
				var idefin = $('#idefin option:selected').prop('value');
				var nombre = $('#txtNombre').prop('value');
				var correo = $('#txtCorreo').prop('value');
				var sum=0;
				$(this).find('.required').each(function(){					 
						 if(producto!=''){
							 $('#errorproducto').hide('slow');
						 }else{
							 sum++;
							 $('#errorproducto').show('slow');
							 //$('#errorproducto').html('seleccione producto');
						 }
						 
						 if(idefin!=''){
							 $('#errorentidad').hide('slow');
						 }else{
							 sum++;
							 $('#errorentidad').show('slow');
							 //$('#errorentidad').html('seleccione entidad financiera');
						 }
						 
						 if(nombre!=''){
							 if(nombre.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
								 $('#errornombre_a').hide('slow');
								 $('#errornombre_b').hide('slow');
							 }else{
								 sum++;
							     $('#errornombre_a').hide('slow');
								 $('#errornombre_b').show('slow');
							     //$('#errornombre_b').html('ingrese solo caracteres');
							 }
						 }else{
							 sum++;
							 $('#errornombre_b').hide('slow');
							 $('#errornombre_a').show('slow');
							 //$('#errornombre_a').html('ingrese nombre');
						 }
						 
						 if(correo!=''){
							 if(correo.match(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/)){
								 $('#errorcorreo').hide('slow');
							 }else{
								sum++;
							    $('#errorcorreo').show('slow');
							    //$('#errorcorreo').html('ingrese correo electronico');
							 }
						 }else{
							 sum++;
							 $('#errorcorreo').show('slow');
							 //$('#errorcorreo').html('ingrese correo electronico');
					     }
				});
				if(sum==0){
					 $("#formCreaCorreo :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'producto='+producto+'&nombre='+nombre+'&correo='+correo+'&idefin='+idefin+'&opcion=crearcorreo';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se adiciono correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
				}else{
				   e.preventDefault();  
				}						
		});
		
		//EDITAR CORREO
		$('#formEditaCorreo').submit(function(e){
			    var producto = $("#producto option:selected").prop('value');
				var idefin = $('#idefin option:selected').prop('value');
				var nombre = $('#txtNombre').prop('value');
				var correo = $('#txtCorreo').prop('value');
				var idcorreo = $('#idcorreo').prop('value');
				var id_ef = $('#id_ef').prop('value');
				var sum=0;
				$(this).find('.required').each(function(){					 
						 if(producto!=''){
							 $('#errorproducto').hide('slow');
						 }else{
							 sum++;
							 $('#errorproducto').show('slow');
							 $('#errorproducto').html('seleccione producto');
						 }
						 
						 if(idefin!=''){
							 $('#errorentidad').hide('slow');
						 }else{
							 sum++;
							 $('#errorentidad').show('slow');
							 $('#errorentidad').html('slow');
						 }
						 
						 if(nombre!=''){
							 if(nombre.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
								 $('#errornombre').hide('slow');
							 }else{
								 sum++;
							     $('#errornombre').show('slow');
							     $('#errornombre').html('ingrese solo caracteres');
							 }
						 }else{
							 sum++;
							 $('#errornombre').show('slow');
							 $('#errornombre').html('ingrese nombre');
						 }
						 
						 if(correo!=''){
							 if(correo.match(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/)){
								 $('#errorcorreo').hide('slow');
							 }else{
								sum++;
							    $('#errorcorreo').show('slow');
							    $('#errorcorreo').html('ingrese correo electronico');
							 }
						 }else{
							 sum++;
							 $('#errorcorreo').show('slow');
							 $('#errorcorreo').html('ingrese correo electronico');
					     }
				});
				if(sum==0){
					 $("#formEditaCorreo :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'producto='+producto+'&nombre='+nombre+'&correo='+correo+'&idcorreo='+idcorreo+'&idefin='+idefin+'&id_ef='+id_ef+'&opcion=editarcorreo';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se edito correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
				}else{
				   e.preventDefault();  
				}					
		});
		
		//CREAR AGENCIA
		$('#formCreaAgencia').submit(function(e){
			 var id_depto = $('#id_depto option:selected').prop('value');
			 var idefin = $('#idefin option:selected').prop('value');
			 var agencia = $.trim($('#txtAgencia').prop('value'));
			 var codigo = $.trim($('#txtCodigo').prop('value'));
			 var emitir = $('input:radio[name=emitir]:checked').val();
			 var resp_emi = $('#resp_emi').prop('value');
			 var sum=0;
			 $(this).find('.required').each(function(){	
			     if(id_depto!=''){
					 $('#errorregion').hide('slow');
				 }else{
					sum++;
					$('#errorregion').show('slow');
					//$('#errorregion').html('seleccione regional');  
				 }
				 
				 if(idefin!=''){
					 $('#errorentidad').hide('slow');
				 }else{
					 sum++;
					 $('#errorentidad').show('slow');
					 //$('#errorentidad').html('seleccione entidad financiera');
				 }
				 
				 if(agencia!=''){
					 if(agencia.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\S]+$/)){
						 $('#erroragencia_a').hide('slow');
						 $('#erroragencia_b').hide('slow');
					 }else{
					    sum++;
						$('#erroragencia_a').hide('slow');
						$('#erroragencia_b').show('slow');
						//$('#erroragencia').html('ingrese solo carcateres'); 	 
					 }
				 }else{
					 sum++;
					 $('#erroragencia_b').hide('slow');
					 $('#erroragencia_a').show('slow');
					 //$('#erroragencia').html('ingrese la agencia');
				 }
				 
				 if(codigo!=''){
					 if(codigo.match(/^[0-9A-Z\s\D]+$/)){
						 $('#errorcodigo').hide('slow');
					 }else{
						sum++;
						$('#errorcodigo').show('slow'); 
						//$('#errorcodigo').html('ingrese solo alafanumericos');
					 }
				 }
				 if(resp_emi==1){
					 //SELECCIONAR RESPUESTA EMITIR
					 if( $("#formCreaAgencia input[name='emitir']:radio").is(':checked')) {
						  $('#erroremitir').hide('slow');
					 }else{
						  sum++;
						  $('#erroremitir').show('slow');
						  $('#erroremitir').html('seleccione al menos una respuesta'); 
					 }
				 }
			 });
			 if(sum==0){
					 $("#formCreaAgencia :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'id_depto='+id_depto+'&agencia='+agencia+'&codigo='+codigo+'&idefin='+idefin+'&emitir='+emitir+'&resp_emi='+resp_emi+'&opcion=crear_agencia';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se adiciono correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
				}else{
				   e.preventDefault();  
				}					
		});
		
		//EDITAR AGENCIA
		$('#formEditaAgencia').submit(function(e){
			 var idefin = $('#idefin option:selected').prop('value');
			 var id_depto = $('#id_depto option:selected').prop('value');
			 var agencia = $('#txtAgencia').prop('value');
			 var codigo = $('#txtCodigo').prop('value');
			 var idagencia = $('#idagencia').prop('value');
			 var id_ef = $('#id_ef').prop('value');
			 //var resp_emi = $('#resp_emi').prop('value');
             var emitir = $('input:radio[name=emitir]:checked').val() 
			 //alert(emitir);
			 var sum=0;
			 $(this).find('.required').each(function(){	
			     if(id_depto!=''){
					 $('#errorregion').hide('slow');
				 }else{
					sum++;
					$('#errorregion').show('slow');
					//$('#errorregion').html('seleccione regional');  
				 }
				 
				 if(idefin!=''){
					 $('#errorentidad').hide('slow');
				 }else{
					 sum++;
					 $('#errorentidad').show('slow');
					 //$('#errorentidad').html('seleccione entidad financiera');
				 }
				 
				 if(agencia!=''){
					 if(agencia.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\S]+$/)){
						 $('#erroragencia').hide('slow');
					 }else{
					    sum++;
						$('#erroragencia').show('slow');
						$('#erroragencia').html('ingrese solo carcateres'); 	 
					 }
				 }else{
					 sum++;
					 $('#erroragencia').show('slow');
					 //$('#erroragencia').html('ingrese la agencia');
				 }
				 
				 if(codigo!=''){
					 if(codigo.match(/^[0-9A-Z\s\-]+$/)){
						 $('#errorcodigo')
					 }else{
						sum++;
						$('#errorcodigo').show('slow'); 
						//$('#errorcodigo').html('ingrese solo carcateres y numeros');
					 }
				 }
			 });
			 if(sum==0){
					 $("#formEditaAgencia :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'id_depto='+id_depto+'&agencia='+agencia+'&codigo='+codigo+'&idagencia='+idagencia+'&idefin='+idefin+'&id_ef='+id_ef+'&emitir='+emitir+'&opcion=editar_agencia';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se edito correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
				}else{
				   e.preventDefault();  
				}					
		});
		
		//CREAR SUCURSAL
		$('#formCreaSucursal').submit(function(e){
			 var sucursal = $('#txtSucursal').prop('value');
			 var codigo = $('#txtCodigo').prop('value');
			 var checkci = $('#tipoci:checked').val();
			 var checkreg = $('#tiporeg:checked').val();
			 var checkdep = $('#tipodepto:checked').val();
			 var idefin = $('#idefin option:selected').prop('value');
			 var sum=0; var chek=0;
			 
			 var miarray = new Array();
			 miarray=[ "tipoci", "tiporeg", "tipodepto" ];
			 $(this).find('.required').each(function(){	
			     				 
				 if(sucursal!=''){
					 if(sucursal.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						 $('#errorsucursal_a').hide('slow');
						 $('#errorsucursal_b').hide('slow');
					 }else{
					    sum++;
						$('#errorsucursal_a').hide('slow');
						$('#errorsucursal_b').show('slow');
						//$('#errorsucursal').html('ingrese solo carcateres'); 	 
					 }
				 }else{
					 sum++;
					 $('#errorsucursal_b').hide('slow');
					 $('#errorsucursal_a').show('slow');
					 //$('#errorsucursal_a').html('ingrese la agencia');
				 }
				 
				 if(codigo!=''){
					 if(codigo.match(/^[A-Z0-9]+$/)){
						 $('#errorcodigo').hide('slow');
					 }else{
						sum++;
						$('#errorcodigo').show('slow'); 
						//$('#errorcodigo').html('ingrese solo alfanumerico');
					 }
				 }
				 
				 $.each( miarray, function( i, l ){
					 if($('#'+l).is(":checked")){
							chek++;       
					 }
				 });
				 if(chek!=0){
					 $('#errortipo').hide('slow');
				 }else{
					 sum++;
					 $('#errortipo').show('slow');
					 //$('#errortipo').html('Debe de seleccionar el tipo');
				 }
				 
				 if(checkreg=='regional'){
					 if(idefin!=''){
						 $('#errorentidad').hide('slow');
					 }else{
						 sum++;
						 $('#errorentidad').show('slow');
						 $('#errorentidad').html('seleccione entidad financiera');
					 }
				 } 
				
			 });
			 if(sum==0){
					 $("#formCreaSucursal :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'sucursal='+sucursal+'&codigo='+codigo+'&tipoci='+checkci+'&tiporeg='+checkreg+'&tipodepto='+checkdep+'&idefin='+idefin+'&opcion=crear_sucursal';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se adiciono correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
					 e.preventDefault();
				}else{
				   e.preventDefault();  
				}					
		});
		
		$('#tipo').change(function(){
			 var tipo=$(this).prop('value');
			 if(tipo=='regional'){
				 $('#content-entidadf').fadeIn('slow');
			 }else{
				 $('#content-entidadf').fadeOut('slow');
			 }	 
	    });
		
		//EDITAR SUCURSAL
		$('#formEditarSucursal').submit(function(e){
			 var sucursal = $('#txtSucursal').prop('value');
			 var codigo = $('#txtCodigo').prop('value');
			 var id_depto = $('#id_depto').prop('value');
			 //var tipo = $('#tipo option:selected').prop('value');
			 var idefin = $('#idefin option:selected').prop('value');
			 var sum=0;
			 $(this).find('.required').each(function(){	
			     				 
				 if(sucursal!=''){
					 if(sucursal.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						 $('#errorsucursal_a').hide('slow');
						 $('#errorsucursal_b').hide('slow');
					 }else{
					    sum++;
						$('#errorsucursal_a').hide('slow');
						$('#errorsucursal_b').show('slow');
						//$('#errorsucursal').html('ingrese solo carcateres'); 	 
					 }
				 }else{
					 sum++;
					 $('#errorsucursal_b').hide('slow');
					 $('#errorsucursal_a').show('slow');
					 //$('#errorsucursal').html('ingrese la agencia');
				 }
				 
				 if(codigo!=''){
					 if(codigo.match(/^[A-Z0-9]+$/)){
						 $('#errorcodigo')
					 }else{
						sum++;
						$('#errorcodigo').show('slow'); 
						//$('#errorcodigo').html('ingrese solo carcateres');
					 }
				 }/*else{
					 sum++;
					 $('#errorcodigo').show('slow');
					 $('#errorcodigo').html('ingrese codigo de sucursal');
				 }
				 if(tipo!=''){
					 $('#errortipo').hide('slow');
					 if(tipo=='regional'){
						 if(idefin!=''){
							 $('#errorentidad').hide('slow');
						 }else{
							 sum++;
							 $('#errorentidad').show('slow');
							 $('#errorentidad').html('seleccione entidad financiera');
						 }
					 }
				 }else{
					 sum++;
					 $('#errortipo').show('slow');
					 $('#errortipo').html('seleccione tipo');
				 }*/
				 
				 
				 
			 });
			 if(sum==0){
					 $("#formEditarSucursal :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'sucursal='+sucursal+'&codigo='+codigo+'&id_depto='+id_depto+'&idefin='+idefin+'&opcion=editar_sucursal';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se actualizo correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
				}else{
				   e.preventDefault();  
				}					
		});
		
		//ADICIONAR COMPAÑIA A ENTIDAD FINANCIERA (EDITAR) 
		$('#formEditaAdCia').submit(function(e){
			 var idefin = $('#idefin option:selected').prop('value');
             var idcompania = $('#idcompania option:selected').prop('value');
			 var id_ef_cia = $('#id_ef_cia').prop('value');
			 var producto =$('#producto option:selected').prop('value');
			 var sum=0;
			 $(this).find('.requerid').each(function(){	
			     if(idefin!=''){
					 $('#errorentidad').hide('slow');
					 if(producto!=''){
						 $('#errorproducto').hide('slow');
					 }else{
						 sum++;
						 $('#errorproducto').show('slow');
						 $('#errorproducto').html('seleccione producto');
					 }
				 }else{
					sum++;
					$('#errorentidad').show('slow');
					$('#errorentidad').html('seleccione entidad financiera');  
				 }
				 
				 if(idcompania!=''){
					 $('#errorcompania').hide('slow');
				 }else{
					sum++;
					$('#errorcompania').show('slow');
					$('#errorcompania').html('seleccione compañia');  
				 }
				 
				 
			 });
			 if(sum==0){
					 $("#formEditaAdCia :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'idefin='+idefin+'&idcompania='+idcompania+'&id_ef_cia='+id_ef_cia+'&opcion=editar_adciaef';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se edito correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
			 }else{
				   e.preventDefault();  
			 }												 
		});
		
		//ADICIONAR COMPAÑIA A ENTIDAD FINANCIERA (EDITAR) 
		$('#formCreaAdCia').submit(function(e){
			 var idefin = $('#idefin option:selected').prop('value');
             var idcompania = $('#idcompania option:selected').prop('value');
			 var producto =$('#producto option:selected').prop('value');
			 var sum=0; var sw=0;
			 $(this).find('.requerid').each(function(){	
			     if(idefin!=''){
					 $('#errorentidad').hide('slow');
					 if(producto!=''){
						 $('#errorproducto').hide('slow');
					 }else{
						 sum++;
						 $('#errorproducto').show('slow');
						 $('#errorproducto').html('seleccione producto');
					 }
				 }else{
					sum++;
					$('#errorentidad').show('slow');
					//$('#errorentidad').html('seleccione entidad financiera');  
				 }
				 
				 if(idcompania!=''){
					$('#errorcompania').hide('slow');
				 }else{
					sum++;
					$('#errorcompania').show('slow');
					//$('#errorcompania').html('seleccione compañia');  
				 } 
			 });
			 
			 if(sum==0){
					 var dataString = 'idefin='+idefin+'&idcompania='+idcompania+'&producto='+producto+'&opcion=buscar_compania';
					 //alert(dataString);
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									$('#errorcompania').hide('slow');
									$("#formCreaAdCia :submit").attr("disabled", true);
									e.preventDefault();
									agregar_compania_entidad(idefin,idcompania,producto);
								  }else if(datareturn==2){
									$('#errorcompania').show('slow');
					                $('#errorcompania').html('esta compañía ya esta añadida seleccione otra compañía'); 
								  } 
						   }
					 });
					 e.preventDefault(); 
					
			 }else{
				   e.preventDefault();  
			 }												 
		});
		
		//AI LA COMPAÑIA NO EXISTE EN LA ENTIDAD FINANCIERA PROCEDEMOS A AGREGAR
		function agregar_compania_entidad(idefin,idcompania,producto){
		   var dataString = 'idefin='+idefin+'&idcompania='+idcompania+'&producto='+producto+'&opcion=crear_adciaef';
		   //alert (dataString);
		   //ejecutando ajax 
		   $.ajax({
				 async: true,
				 cache: false,
				 type: "POST",
				 url: "accion_registro.php",
				 data: dataString,
				 beforeSend: function(){
					  $("#response-loading").css({
						  'height': '30px'
					  });
				 },
				 complete: function(){
					  $("#response-loading").css({
						  "background": "transparent"
					  });
				 },
				 success: function(datareturn) {
						//alert(datareturn);
						if(datareturn==1){
						   $('#response-loading').html("Se añadio correctamente el registro");
							window.setTimeout('location.reload()', 3000); 
						}else if(datareturn==2){
						   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
						   e.preventDefault();
						}
						
				 }
		   });	
		}
		
		//AGREGAR TIPO DE VEHICULO
		$('#frmAdTipVehi').submit(function(e){
			var idefin = $('#idefin option:selected').prop('value');
			var tip_vehiculo = $('#txtTipVehi').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(idefin!=''){
					 $('#errorentidad').hide('slow');
				 }else{
					 sum++;
					 $('#errorentidad').show('slow');
					 //$('#errorentidad').html('seleccione entidad financiera');
				 }
				 if(tip_vehiculo!=''){
					 if(tip_vehiculo.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\d\D]+$/)){
						 $('#errortipvehi_a').hide('slow');
						 $('#errortipvehi_b').hide('slow');
					 }else{
						 sum++;
						 $('#errortipvehi_a').hide('slow');
						 $('#errortipvehi_b').show('slow');
						 //$('#errortipvehi_b').html('ingrese solo caracteres');
					 }
				 }else{
					 sum++;
					 $('#errortipvehi_b').hide('slow');
					 $('#errortipvehi_a').show('slow');
					 //$('#errortipvehi_a').html('ingrese el tipo de vehiculo');
				 }
            });
			if(sum==0){
				$("#frmAdTipVehi :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'idefin='+idefin+'&tip_vehiculo='+tip_vehiculo+'&opcion=agregar_tipvehic';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se agrego correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al agregar el registro, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
			}else{
				e.preventDefault();
			}
			
		});
		
		//EDITAR TIPO DE VEHICULO
		$('#frmEdiTipVehi').submit(function(e){
			var id_ef = $('#id_ef').prop('value');
			var idtipovh = $('#idtipovh').prop('value');
			var tip_vehiculo = $('#txtTipVehi').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
				 if(tip_vehiculo!=''){
					 if(tip_vehiculo.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\S]+$/)){
						 $('#errortipvehi').hide('slow');
					 }else{
						 sum++;
						 $('#errortipvehi').show('slow');
						 $('#errortipvehi').html('ingrese solo caracteres');
					 }
				 }else{
					 sum++;
					 $('#errortipvehi').show('slow');
					 //$('#errortipvehi').html('ingrese el tipo de vehiculo');
				 }
            });
			if(sum==0){
				$("#frmEdiTipVehi :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'id_ef='+id_ef+'&idtipovh='+idtipovh+'&tip_vehiculo='+tip_vehiculo+'&opcion=editar_tipvehic';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									 $('#response-loading').html("Se edito correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
			}else{
				e.preventDefault();
			}
			
		});
		
		//AGREGAR MARCA DE AUTO
		$('#frmAdMarca').submit(function(e){
			 var idefin=$('#idefin option:selected').prop('value');
			 var marca_auto=$('#txtMarcaAuto').prop('value');
			 var sum=0;
			 $(this).find('.required').each(function() {
                   if(idefin!=''){
					   $('#errorentidad').hide('slow');
				   }else{
					   sum++;
					   $('#errorentidad').show('slow');
					   //$('#errorentidad').html('seleccione entidad financiera');
				   }
				   if(marca_auto!=''){
					   if(marca_auto.match(/^[A-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						  $('#errormarcauto_a').hide('slow');
						  $('#errormarcauto_b').hide('slow');
					   }else{
						  sum++;
						  $('#errormarcauto_a').hide('slow');
						  $('#errormarcauto_b').show('slow');
						  //$('#errormarcauto_b').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errormarcauto_b').hide('slow');
					   $('#errormarcauto_a').show('slow');
					   //$('#errormarcauto_a').html('ingrese marca de auto');
				   }  
             });
			 if(sum==0){
				     $("#frmAdMarca :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'idefin='+idefin+'&marca_auto='+marca_auto+'&opcion=agregar_marca_auto';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==0){
								     $('#errormarcauto').show('slow');
									 $('#errormarcauto').html("Ya existe esta marca de auto, ingrese otra marca o elija otra Entidad Financiera");
									 $("#frmAdMarca :submit").removeAttr('disabled');
									 e.preventDefault();
								  }else if(datareturn==1){
									 $('#errormarcauto').hide('slow');
									 $('#response-loading').html("Se agrego correctamente el registro");
									 window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#errormarcauto').hide('slow');
									 $('#response-loading').html("Hubo un error al agregar el registro, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
		     }else{
			    e.preventDefault();
			 }
		});
		
		//EDITAR MARCA AUTO
		$('#frmEdMarca').submit(function(e){
			 var marca_auto=$('#txtMarcaAuto').prop('value');
			 var id_ef=$('#id_ef').prop('value');
			 var id_marca=$('#id_marca').prop('value');
			 var sum=0;
			 $(this).find('.required').each(function() {
                  if(marca_auto!=''){
					   if(marca_auto.match(/^[A-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						  $('#errormarcauto_a').hide('slow');
						  $('#errormarcauto_b').hide('slow');
					   }else{
						  sum++;
						  $('#errormarcauto_a').hide('slow');
						  $('#errormarcauto_b').show('slow');
						  //$('#errormarcauto').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errormarcauto_b').hide('slow');
					   $('#errormarcauto_a').show('slow');
					   //$('#errormarcauto').html('ingrese marca de auto');
				   }  
             });
			 if(sum==0){
				     $("#frmEdMarca :submit").attr("disabled", true);
					 e.preventDefault();
					 var dataString = 'id_ef='+id_ef+'&marca_auto='+marca_auto+'&id_marca='+id_marca+'&opcion=editar_marca_auto';
					 //alert (dataString);
					 //ejecutando ajax 
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   beforeSend: function(){
								$("#response-loading").css({
									'height': '30px'
								});
						   },
						   complete: function(){
								$("#response-loading").css({
									"background": "transparent"
								});
						   },
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==0){
								     $('#errormarcauto').show('slow');
									 $('#errormarcauto').html("Ya existe esta marca de auto, ingrese otra marca");
									 $("#frmEdMarca :submit").removeAttr('disabled');
									 e.preventDefault();
								  }else if(datareturn==1){
									 $('#errormarcauto').hide('slow');
									 $('#response-loading').html("Se edito correctamente el registro");
									  window.setTimeout('location.reload()', 3000); 
								  }else if(datareturn==2){
									 $('#errormarcauto').hide('slow');
									 $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
									 e.preventDefault();
								  }
								  
						   }
					 });
			 }else{
			    e.preventDefault();	 
		     }
		});
		
		//AGREGAR MODELO AUTO
		$('#frmAdModelo').submit(function(e){
		    var modelo_auto=$('#txtModeloAuto').prop('value');
			var id_marca=$('#id_marca').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(modelo_auto!=''){
					   if(modelo_auto.match(/^[0-9A-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\S]+$/)){
						  $('#errormodeauto_a').hide('slow');
						  $('#errormodeauto_b').hide('slow');
					   }else{
						  sum++;
						  $('#errormodeauto').show('slow');
						  $('#errormodeauto').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errormodeauto_b').hide('slow');
					   $('#errormodeauto_a').show('slow');
					   //$('#errormodeauto').html('ingrese modelo de auto');
				   }   
            });
			if(sum==0){
				   $("#frmAdModelo :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'id_marca='+id_marca+'&modelo_auto='+modelo_auto+'&opcion=agregar_modelo_auto';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==0){
									$('#errormodeauto_b').show('slow');
									//$('#errormodeauto').html("El modelo de auto ya existe, ingrese otro modelo");
									$("#frmAdModelo :submit").removeAttr('disabled');
								    e.preventDefault();
								}else if(datareturn==1){
								    $('#errormodeauto_b').hide('slow');
									$('#response-loading').html("Se agrego correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								    $('#response-loading').html("Hubo un error al agregar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault(); 	
			}
		});
		
		//EDITAR MODELO DE AUTO
		$('#frmEditModelo').submit(function(e){
			var modelo_auto=$('#txtModeloAuto').prop('value');
			var id_marca=$('#id_marca').prop('value');
			var id_modelo = $('#id_modelo').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(modelo_auto!=''){
					   if(modelo_auto.match(/^[0-9A-Z\s\-]+$/)){
						  $('#errormodeauto').hide('slow');
					   }else{
						  sum++;
						  $('#errormodeauto').show('slow');
						  $('#errormodeauto').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errormodeauto').show('slow');
					   //$('#errormodeauto').html('ingrese modelo de auto');
				   }   
            });
			if(sum==0){
				   $("#frmEditModelo :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'id_marca='+id_marca+'&id_modelo='+id_modelo+'&modelo_auto='+modelo_auto+'&opcion=editar_modelo_auto';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==0){
								    $('#errormodeauto').show('slow');
									$('#errormodeauto').html("El modelo de auto ya existe, ingrese otro modelo");
									$("#frmAdModelo :submit").removeAttr('disabled');
								    e.preventDefault();
								}else if(datareturn==1){
								    $('#errormodeauto').hide('slow');
									$('#response-loading').html("Se edito correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#errormodeauto').hide('slow');
								   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault(); 	
			}
		});
		
		//AGREGAR TIPO PRODUCTO
		$('#formAdTip').submit(function(e){
			var id_ef=$('#idefin option:selected').prop('value');
			var tip_producto=$('#tip_producto').prop('value');
			var sum=0;
			$(this).find('.requerid').each(function() {
                 if(id_ef!=''){
					$('#errorentidad').hide('slow');
				 }else{
					sum++;
					$('#errorentidad').show('slow');
					$('#errorentidad').html('seleccione entidad financiera'); 
				 }
				 if(tip_producto!=''){
					 $('#errortipproducto').hide('slow'); 
				 }else{
					sum++;
					$('#errortipproducto').show('slow');
					$('#errortipproducto').html('seleccione tipo registro'); 
				 }
            });
			if(sum==0){
					 var dataString = 'id_ef='+id_ef+'&tip_producto='+tip_producto+'&opcion=buscar_tip_produ';
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "accion_registro.php",
						   data: dataString,
						   success: function(datareturn) {
								  //alert(datareturn);
								  if(datareturn==1){
									$('#errortipproducto').hide('slow');
									$("#formAdTip :submit").attr("disabled", true);
									e.preventDefault();
									agregar_tipo_producto(id_ef,tip_producto);
								  }else if(datareturn==2){
									$('#errortipproducto').show('slow');
					                $('#errortipproducto').html('el tipo de producto ya esta añadido seleccione otro o en su caso seleccione otra entidad'); 
								  } 
						   }
					 });
					 e.preventDefault(); 
					
			 }else{
				   e.preventDefault();  
			 }			
	    });
		
		//AI LA COMPAÑIA NO EXISTE EN LA ENTIDAD FINANCIERA PROCEDEMOS A AGREGAR
		function agregar_tipo_producto(id_ef,tip_producto){
		   var dataString = 'id_ef='+id_ef+'&tip_producto='+tip_producto+'&opcion=crear_tip_producto';
		   //alert (dataString);
		   //ejecutando ajax 
		   $.ajax({
				 async: true,
				 cache: false,
				 type: "POST",
				 url: "accion_registro.php",
				 data: dataString,
				 beforeSend: function(){
					  $("#response-loading").css({
						  'height': '30px'
					  });
				 },
				 complete: function(){
					  $("#response-loading").css({
						  "background": "transparent"
					  });
				 },
				 success: function(datareturn) {
						//alert(datareturn);
						if(datareturn==1){
						   $('#response-loading').html("Se añadio correctamente el registro");
							window.setTimeout('location.reload()', 3000); 
						}else if(datareturn==2){
						   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
						   e.preventDefault();
						}
						
				 }
		   });	
		}
		
		//ADICIONAR PRODUCTOS
		$('#frmAdProductos').submit(function(e){
			 var productos=$('#txtProductos').prop('value');
			 var id_ef_cia=$('#id_ef_cia').prop('value');
			 var id_producto=$('#id_producto').prop('value');
			 var sum=0;
			 $(this).find('.requerid').each(function() {
                  if(productos!=''){
					  if(productos.match(/^[a-zA-Z\s]+$/)){
						  $('#errorproductos_a').hide('slow');
						  $('#errorproductos_b').hide('slow');
					  }else{
						  sum++;
					      $('#errorproductos_a').hide('slow');
						  $('#errorproductos_b').show('slow');
					      //$('#errorproductos').html('ingrese solo caracteres');  
					  }
				  }else{
					sum++;
					$('#errorproductos_b').hide('slow');
					$('#errorproductos_a').show('slow');
					//$('#errorproductos').html('ingrese producto');  
				  }
             });
			 if(sum==0){
				   $("#frmAdProductos :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'productos='+productos+'&id_ef_cia='+id_ef_cia+'&id_producto='+id_producto+'&opcion=ingresa_producto';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==1){
								   $('#response-loading').html("Se agrego correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al agregar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault(); 	
			}
		});
		
		//EDITAR PRODUCTOS
		$('#frmEditaProdu').submit(function(e){
			 var productos=$('#txtProductos').prop('value');
			 var id_ef_cia=$('#id_ef_cia').prop('value');
			 var id_producto=$('#id_producto').prop('value');
			 var idprcia=$('#idprcia').prop('value');
			 var sum=0;
			 $(this).find('.required').each(function() {
                 if(productos!=''){
					  if(productos.match(/^[a-zA-Z\s]+$/)){
						  $('#errorproductos_a').hide('slow');
						  $('#errorproductos_b').hide('slow');
					  }else{
						  sum++;
					      $('#errorproductos_a').hide('slow');
						  $('#errorproductos_b').show('slow');
					      //$('#errorproductos').html('ingrese solo caracteres');  
					  }
				  }else{
					sum++;
					$('#errorproductos_b').hide('slow');
					$('#errorproductos_a').show('slow');
					//$('#errorproductos').html('ingrese producto');  
				  }
             });
			 if(sum==0){
				   $("#frmEditaProdu :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'productos='+productos+'&id_ef_cia='+id_ef_cia+'&id_producto='+id_producto+'&idprcia='+idprcia+'&opcion=editar_producto';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==1){
								   $('#response-loading').html("Se edito correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault(); 	
			}
		});
		
		//FORMULARIO CREA PREGUNTA DESGRAVAMEN
		$('#frmAdPregunta').submit(function(e){
			var pregunta=$('#txtPregunta').val();
			var respuesta=$('#txtRespuesta option:selected').prop('value');
			var id_ef_cia=$('#id_ef_cia').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(pregunta!=''){
					$('#errorpregunta').hide('slow');
				 }else{
				   sum++;
				   $('#errorpregunta').show('slow');
				   //$('#errorpregunta').html('ingrese la pregunta');	 
				 }
				 if(respuesta!=''){
					 $('#errorespuesta').hide('slow');
				 }else{
					 sum++;
					 $('#errorespuesta').show('slow');
					 //$('#errorespuesta').html('seleccione respuesta');
				 }
            });
			if(sum==0){
				   $("#frmAdPregunta :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'pregunta='+pregunta+'&id_ef_cia='+id_ef_cia+'&respuesta='+respuesta+'&opcion=crea_pregunta';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==1){
								   $('#response-loading').html("Se creo correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al crear el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
		});
		
		//FORMULARIO EDITAR PREGUNTA DESGRAVAMEN
		$('#frmEdiPregunta').submit(function(e){
			var pregunta=$('#txtPregunta').val();
			var respuesta=$('#txtRespuesta option:selected').prop('value');
			var id_ef_cia=$('#id_ef_cia').prop('value');
			var id_pregunta=$('#id_pregunta').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(pregunta!=''){
					$('#errorpregunta').hide('slow');
				 }else{
				   sum++;
				   $('#errorpregunta').show('slow');
				   $('#errorpregunta').html('ingrese la pregunta');	 
				 }
				 if(respuesta!=''){
					 $('#errorespuesta').hide('slow');
				 }else{
					 sum++;
					 $('#errorespuesta').show('slow');
					 $('#errorespuesta').html('seleccione respuesta');
				 }
            });
			if(sum==0){
				   $("#frmEdiPregunta :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'pregunta='+pregunta+'&id_ef_cia='+id_ef_cia+'&id_pregunta='+id_pregunta+'&respuesta='+respuesta+'&opcion=edita_pregunta';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
								if(datareturn==1){
								   $('#response-loading').html("Se edito correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
		});
		
		/*CREA PRODUCTO EXTRA*/
		$('#frmAdProdExtra').submit(function(e){
			var rango_min_bs = $('#txtRgMinBs').prop('value');
			var rango_max_bs = $('#txtRgMaxBs').prop('value');
			var rango_min_usd = $('#txtRgMinUsd').prop('value');
			var rango_max_usd = $('#txtRgMaxUsd').prop('value');
			var hospitalario = $('#txtHospitalario').prop('value');
			var censatia = $('#txtCesantia').prop('value');
			var vida = $('#txtVida').prop('value');
			var prima = $('#txtPrima').prop('value');
			var id_ef_cia = $('#id_ef_cia').prop('value');
			
			var sum=0;
			$(this).find('.required').each(function() {
                
				if(rango_min_bs!=''){
				   if(rango_min_bs.match(/^[0-9\.]+$/)){
					   $('#errorminbs').hide('slow');
				   }else{
					   sum++;
					   $('#errorminbs').show('slow');
					   $('#errorminbs').html('Ingrese solo numeros');
				   }	
				}else{
					sum++;
					$('#errorminbs').show('slow');
					$('#errorminbs').html('Ingrese el rango mínimo');
				}
				
				if(rango_max_bs!=''){
					if(rango_max_bs.match(/^[0-9\.]+$/)){
						$('#errormaxbs').hide('slow');
					}else{
						sum++;
						$('#errormaxbs').show('slow');
						$('#errormaxbs').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errormaxbs').show('slow');
					$('#errormaxbs').html('Ingrese el rango máximo');
				}
				
				if(rango_min_usd!=''){
				   $('#errorminusd').hide('slow');
			    }else{
				   sum++;	
				   $('#errorminusd').show('slow');
				   $('#errorminusd').html('ingrese el rango mínimo');	
			    }
				
				if(rango_max_usd!=''){
				   $('#errormaxusd').hide('slow');
			    }else{
				   sum++;
				   $('#errormaxusd').show('slow');
				   $('#errormaxusd').html('Ingrese el rango máximo');	
			    }
				
				if(hospitalario!=''){
					if(hospitalario.match(/^[0-9\.]+$/)){
						$('#errorhospitalario').hide('slow');
					}else{
						sum++;
					    $('#errorhospitalario').show('slow');
					    $('#errorhospitalario').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorhospitalario').show('slow');
					$('#errorhospitalario').html('Ingrese hospitalario');
			    }
				
				if(vida!=''){
					if(vida.match(/^[0-9\.]+$/)){
						$('#errorvida').hide('slow');
					}else{
						sum++;
					    $('#errorvida').show('slow');
					    $('#errorvida').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorvida').show('slow');
					$('#errorvida').html('Ingrese vida');
				}
				
				if(censatia!=''){
					if(censatia.match(/^[0-9\.]+$/)){
						$('#errorcesantia').hide('slow');
					}else{
						sum++;
					    $('#errorcesantia').show('slow');
					    $('#errorcesantia').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorcesantia').show('slow');
					$('#errorcesantia').html('Ingrese cesantia');
				}
				
				if(prima!=''){
				   	if(prima.match(/^[0-9\.]+$/)){
						$('#errorprima').hide('slow');
					}else{
						sum++;
					    $('#errorprima').show('slow');
					    $('#errorprima').html('Ingrese prima');
					}
				}else{
					sum++;
					$('#errorprima').show('slow');
					$('#errorprima').html('Ingrese prima');
				}
				
            });
			if(sum==0){
				   $("#frmAdProdExtra :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'rango_min_bs='+rango_min_bs+'&rango_max_bs='+rango_max_bs+'&rango_min_usd='+rango_min_usd+'&rango_max_usd='+rango_max_usd+'&hospitalario='+hospitalario+'&vida='+vida+'&cesantia='+censatia+'&prima='+prima+'&id_ef_cia='+id_ef_cia+'&opcion=crea_prodextra';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
						        if(datareturn==0){
								   $("#frmAdProdExtra :submit").removeAttr('disabled');
								   $('#errormaxbs').show('slow');
						           $('#errormaxbs').html('Ingrese un rango consecutivo al anterior');
								   e.preventDefault();	
								}else if(datareturn==1){
								   $('#response-loading').html("Se creo correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al crear el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
		});
		
		//EDITAMOS LOS DATOS PRODUCTO EXTRA
		$('#frmEdiProdExtra').submit(function(e){
			var rango_min_bs = $('#txtRgMinBs').prop('value');
			var rango_max_bs = $('#txtRgMaxBs').prop('value');
			var rango_min_usd = $('#txtRgMinUsd').prop('value');
			var rango_max_usd = $('#txtRgMaxUsd').prop('value');
			var hospitalario = $('#txtHospitalario').prop('value');
			var censatia = $('#txtCesantia').prop('value');
			var vida = $('#txtVida').prop('value');
			var prima = $('#txtPrima').prop('value');
			var id_ef_cia = $('#id_ef_cia').prop('value');
			var id_pr_extra = $('#id_pr_extra').prop('value');
			
			var sum=0;
			$(this).find('.required').each(function() {
				if(rango_min_bs!=''){
				   if(rango_min_bs.match(/^[0-9\.]+$/)){
					   $('#errorminbs').hide('slow');
				   }else{
					   sum++;
					   $('#errorminbs').show('slow');
					   $('#errorminbs').html('Ingrese solo numeros');
				   }	
				}else{
					sum++;
					$('#errorminbs').show('slow');
					$('#errorminbs').html('Ingrese el rango mínimo');
				}
				if(rango_max_bs!=''){
					if(rango_max_bs.match(/^[0-9\.]+$/)){
						$('#errormaxbs').hide('slow');
					}else{
						sum++;
						$('#errormaxbs').show('slow');
						$('#errormaxbs').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errormaxbs').show('slow');
					$('#errormaxbs').html('Ingrese el rango máximo');
				}
				if(rango_min_usd!=''){
				   $('#errorminusd').hide('slow');
			    }else{
				   sum++;	
				   $('#errorminusd').show('slow');
				   $('#errorminusd').html('ingrese el rango mínimo');	
			    }
				if(rango_max_usd!=''){
				   $('#errormaxusd').hide('slow');
			    }else{
				   sum++;
				   $('#errormaxusd').show('slow');
				   $('#errormaxusd').html('Ingrese el rango máximo');	
			    }
				if(hospitalario!=''){
					if(hospitalario.match(/^[0-9\.]+$/)){
						$('#errorhospitalario').hide('slow');
					}else{
						sum++;
					    $('#errorhospitalario').show('slow');
					    $('#errorhospitalario').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorhospitalario').show('slow');
					$('#errorhospitalario').html('Ingrese hospitalario');
			    }
				
				if(vida!=''){
					if(vida.match(/^[0-9\.]+$/)){
						$('#errorvida').hide('slow');
					}else{
						sum++;
					    $('#errorvida').show('slow');
					    $('#errorvida').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorvida').show('slow');
					$('#errorvida').html('Ingrese vida');
				}
				
				if(censatia!=''){
					if(censatia.match(/^[0-9\.]+$/)){
						$('#errorcesantia').hide('slow');
					}else{
						sum++;
					    $('#errorcesantia').show('slow');
					    $('#errorcesantia').html('Ingrese solo numeros');
					}
				}else{
					sum++;
					$('#errorcesantia').show('slow');
					$('#errorcesantia').html('Ingrese cesantia');
				}
				
				if(prima!=''){
				   	if(prima.match(/^[0-9\.]+$/)){
						$('#errorprima').hide('slow');
					}else{
						sum++;
					    $('#errorprima').show('slow');
					    $('#errorprima').html('Ingrese prima');
					}
				}else{
					sum++;
					$('#errorprima').show('slow');
					$('#errorprima').html('Ingrese prima');
				}									
			});
			if(sum==0){
				   $("#frmEdiProdExtra :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'rango_min_bs='+rango_min_bs+'&rango_max_bs='+rango_max_bs+'&rango_min_usd='+rango_min_usd+'&rango_max_usd='+rango_max_usd+'&hospitalario='+hospitalario+'&vida='+vida+'&cesantia='+censatia+'&prima='+prima+'&id_ef_cia='+id_ef_cia+'&id_pr_extra='+id_pr_extra+'&opcion=edita_prodextra';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
						        if(datareturn==1){
								   $('#response-loading').html("Se edito correctamente el registro");
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html("Hubo un error al editar el registro, consulte con su administrador");
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
			
		});
		
		//SACAMOS EL VALOR RANGO MINIMO BS EN DOLARES
		$('#txtRgMinBs').blur(function(e){
			var minimo_bs = $(this).val();
			//VERIFICAMOS SI LA CASILLA ESTA VACIA
			 e.preventDefault();
			if (minimo_bs == "") {
			   $('#errorminbs').show('slow');
			   $('#errorminbs').html('ingrese un monto minimo');
			   $('#errorminbs').focus();
			}else if(minimo_bs.match(/^[0-9\.]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
				$('#errorminbs').hide('slow');
				var equiv = parseInt(minimo_bs/7);
				$('#txtRgMinUsd').prop('value',equiv).hide().show('slow');
			}else{
			   $('#errorminbs').show('slow');
			   $('#errorminbs').html('ingrese solo numeros enteros').fadeIn('slow');
			   $('#errorminbs').focus();
			}
        });
		
		//SACAMOS EL VALOR RANGO MAXIMO BS EN DOLARES
		$('#txtRgMaxBs').blur(function(e){
			var maximo_bs = $(this).val();
			//VERIFICAMOS SI LA CASILLA ESTA VACIA
			 e.preventDefault();
			if (maximo_bs == "") {
			   $('#errormaxbs').show('slow');
			   $('#errormaxbs').html('ingrese un monto máximo');
			   $('#errormaxbs').focus();
			}else if(maximo_bs.match(/^[0-9\.]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
				$('#errormaxbs').hide('slow');
				var equiv = parseInt(maximo_bs/7);
				$('#txtRgMaxUsd').prop('value',equiv).hide().show('slow');
			}else{
			   $('#errormaxbs').show('slow');
			   $('#errormaxbs').html('ingrese solo numeros enteros').fadeIn('slow');
			   $('#errormaxbs').focus();
			}
        });
		
		$('#txtCodigo').keyup(function() {
           $(this).val($(this).val().toUpperCase());
        });
		
		//CONVERSOR MAYUSCULAS MARCA DE AUTO
		$('#txtMarcaAuto').keyup(function() {
           $(this).val($(this).val().toUpperCase());
        });
		//CONVERSOR MAYUSCULAS MODELO DE AUTO
		$('#txtModeloAuto').keyup(function() {
           $(this).val($(this).val().toUpperCase());
        });
		
		$('#txtProductos').keyup(function(){
		   $(this).val($(this).val().toUpperCase());
	    });
				
		
		/*CHANGES BUSCAR PRODUCTO - CORREOS ELECTRONICOS*/
		$('#formCreaCorreo #idefin').change(function(){
			var idefin=$(this).prop('value');
			if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto_correo';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		});
		
		$('#formEditaCorreo #idefin').change(function(){
			var idefin=$(this).prop('value');
			if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto_correo';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		});
		
		$('#formCreaAgencia #idefin').change(function(){
			var id_ef=$(this).prop('value');
			if(idefin!=''){
				  var dataString = 'id_ef='+id_ef+'&opcion=buscar_ef_home';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  if(datareturn==1){
							     $('#content-emision').fadeIn('slow');
								 $('#resp_emi').prop('value',datareturn);
							  }else if(datareturn==0){
								 $('#content-emision').fadeOut('slow');
								 $('#resp_emi').prop('value',datareturn); 
							  }
						 }
				   });
			  }else{
				  $('#content-emision').fadeOut('slow');
			  }
		});
		
		$('#formEditaAgencia #idefin').change(function(){
			var id_ef=$(this).prop('value');
			if(idefin!=''){
				  var dataString = 'id_ef='+id_ef+'&opcion=buscar_ef_home';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  if(datareturn==1){
							     $('#content-emision').fadeIn('slow');
								 $('#resp_emi').prop('value',datareturn);
							  }else if(datareturn==0){
								 $('#content-emision').fadeOut('slow');
								 $('#resp_emi').prop('value',datareturn); 
							  }
						 }
				   });
			  }else{
				  $('#content-emision').fadeOut('slow');
			  }
		});
		
		//BUSCAR PRODUCTO ADICIONAR COMPAÑIA A ENTIDAD
		$('#formCreaAdCia #idefin').change(function(){
			var idefin=$(this).prop('value');
			if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto_ocupacion';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		});
		
		//CHECKBOX TIPO CI
		$('#formCreaSucursal #tipoci').click(function(){
		     if($('#tipoci').is(":checked")){
				 if($('#tiporeg').is(":checked")){
					$('#tiporeg').prop('checked', false);
					$('#tiporeg').attr('disabled', true);
				 }else{
					$('#tiporeg').attr('disabled', true); 
				 }
			 }else{
				 if($('#tipodepto').is(':checked')){
				    //se mantiene desabilitado el checkbox regional
				 }else{
					 $('#tiporeg').removeAttr('disabled');
				 }
			 }
	    });
		
		//CHECKBOX TIPO REGION
		$('#formCreaSucursal #tiporeg').click(function(){
			   if($('#tiporeg').is(":checked")){
				   if($('#tipoci').is(":checked")){
					  $('#tipoci').prop('checked', false);
					  $('#tipoci').attr('disabled', true); 
				   }else{
					  $('#tipoci').attr('disabled', true);  
				   }
				   if($('#tipodepto').is(":checked")){
					  $('#tipodepto').prop('checked', false);
					  $('#tipodepto').attr('disabled', true); 
				   }else{
					  $('#tipodepto').attr('disabled', true);  
				   }
				   $('#content-entidadf').fadeIn('slow');
			   }else{
				   if($('#tipoci').is(":checked")){
					  $('#tipoci').removeAttr("disabled"); 
				   }else{
					  $('#tipoci').removeAttr("disabled");  
				   }
				   if($('#tipodepto').is(":checked")){
					  $('#tipodepto').removeAttr("disabled"); 
				   }else{
					  $('#tipodepto').removeAttr("disabled");  
				   }
				   $('#content-entidadf').fadeOut('slow');
			   }
		});
		
		//CHECKBOX TIPO DEPARTAMENTO
		$('#formCreaSucursal #tipodepto').click(function(){
		     if($('#tipodepto').is(":checked")){
				 if($('#tiporeg').is(":checked")){
					$('#tiporeg').prop('checked', false);
					$('#tiporeg').attr('disabled', true);
				 }else{
					$('#tiporeg').attr('disabled', true); 
				 }
			 }else{
				 if($('#tipoci').is(':checked')){
				    //se mantiene desabilitado el checkbox regional
				 }else{
					 $('#tiporeg').removeAttr('disabled');
				 }
			 }
	    });
		
		//BUSCAR PRODUCTO ADICIONAR COMPAÑIA A ENTIDAD
		$('#formAdTip #idefin').change(function(){
			var idefin=$(this).prop('value');
			//alert(idefin);
			
			var dataString = 'idefin='+idefin+'&opcion=buscar_asigancion';
			$.ajax({
				   async: true,
				   cache: false,
				   type: "POST",
				   url: "buscar_registro.php",
				   data: dataString,
				   success: function(datareturn) {
						//alert(datareturn);
						if(datareturn==1){
							$('#errorentidad').hide('slow');
							$('#tip_producto').removeAttr('disabled');
							$("#formAdTip :submit").removeAttr('disabled');
					    }else if(datareturn==0){
							$('#errorentidad').show('slow');
							$('#errorentidad').html('Verifique que el producto este en la Compañia asignada a la Entidad Financiera y posteriormente este activado');
							$('#tip_producto').attr('disabled',true);
							$("#formAdTip :submit").attr("disabled", true);
					    }
				   }
			});
			  
		});
		
		//MARCA TARJETA
		$('#frmAdMarcaTarjeta').submit(function(e){
			var marcatarjeta=$('#marcatj option:selected').prop('value');
			var id_ef_cia=$('#id_ef_cia').prop('value');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(marcatarjeta!=''){
					    $('#errormarcatarjeta').hide('slow');
				 }else{
				   sum++;
				   $('#errormarcatarjeta').show('slow');
				   //$('#errormarcatarjeta').html('seleccione la marca de tarjeta');	 
				 }
				 
            });
			if(sum==0){
				   $("#frmAdMarcaTarjeta :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'marcatarjeta='+marcatarjeta+'&id_ef_cia='+id_ef_cia+'&opcion=crea_marcatarjeta';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
						        if(datareturn==0){
								   $("#frmAdMarcaTarjeta :submit").removeAttr('disabled');
								   $('#response-loading').html('<div style="color:#d44d24;">esta marca ya existe, seleccione otra marca</div>');
								   e.preventDefault();
								   
								}else if(datareturn==1){
								   $('#response-loading').html('<div style="color:#62a426;">Se creo correctamente el registro</div>');
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html('<div style="color:#d44d24;">Hubo un error al crear el registro, consulte con su administrador</div>');
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
		});
		
		//TIPO DE TARJETA
		$('#frmAdTipoTarjeta').submit(function(e){
			var tipotarjeta = $('#tipo_tarjeta option:selected').prop('value');
			var vec = tipotarjeta.split('|');
			var sum=0;
			$(this).find('.required').each(function() {
                 if(tipotarjeta!=''){
					 $('#errortipotarjeta_a').hide('slow');
					 $('#errortipotarjeta_b').hide('slow');
				 }else{
				     sum++;
				     $('#errortipotarjeta_b').hide('slow');
					 $('#errortipotarjeta_a').show('slow');
				     //$('#errortipotarjeta').html('seleccione tipo de tarjeta');	 
				 }
				 
            });
			if(sum==0){
				   $("#frmAdTipoTarjeta :submit").attr("disabled", true);
				   e.preventDefault();
				   var dataString = 'tipotarjeta='+vec[0]+'&codigo='+vec[1]+'&opcion=crea_tipotarjeta';
				   //alert (dataString);
				   //ejecutando ajax 
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "accion_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#response-loading").css({
								  'height': '30px'
							  });
						 },
						 complete: function(){
							  $("#response-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
								//alert(datareturn);
						        if(datareturn==0){
								   $("#frmAdTipoTarjeta :submit").removeAttr('disabled');
								   $('#errortipotarjeta_a').hide('slow');
								   $('#errortipotarjeta_b').show('slow');
								   //$('#errortipotarjeta_b').html('el tipo de tarjeta ya existe, seleccione otro');
								   e.preventDefault();
								   
								}else if(datareturn==1){
								   $('#response-loading').html('<div style="color:#62a426;">Se creo correctamente el registro</div>');
									window.setTimeout('location.reload()', 3000); 
								}else if(datareturn==2){
								   $('#response-loading').html('<div style="color:#d44d24;">Hubo un error al crear el registro, consulte con su administrador</div>');
								   e.preventDefault();
								}
								
						 }
				   });
			}else{
			   e.preventDefault();
			}
		});
});  
</script>
<?php
if($_GET['opcion']=='crear_correos'){
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef);";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef)
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
    }	  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Crear nuevo correo electrónico</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaCorreo" id="formCreaCorreo">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item large">
							   <select id="idefin" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
						    </div>	   
					  </div>
					  <div class="da-form-row" style="display: none;" id="content-entidadf">
						  <label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
						  <div class="da-form-item large">
							<span id="entidad-loading" class="loading-entf"></span>
						  </div>
					  </div> 
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Nombre</span></b></label>
						   <div class="da-form-item large">
						     <input class="textbox required" type="text" id="txtNombre" value="" autocomplete="off"/>
						     <span class="errorMessage" id="errornombre_a" lang="es" style="display:none;">ingrese nombre</span>
							 <span class="errorMessage" id="errornombre_b" lang="es" style="display:none;">ingrese solo caracteres</span>
						   </div>	 
					  </div>
					   <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Correo electronico</span></b></label>
						   <div class="da-form-item large">
						    <input class="textbox required" type="text" id="txtCorreo" value="" autocomplete="off"/>
						    <span class="errorMessage" id="errorcorreo" lang="es" style="display:none;">ingrese correo electronico</span>
						   </div>	
					  </div>					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green" lang="es"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='editar_correo'){//EDITAR CORREOS ELECTRONICOS
	$select="select
			  id_correo,
			  correo,
			  nombre,
			  producto,
			  id_ef
			from
			  s_correo
			where
			  id_correo=".base64_decode($_GET['idcorreo']).";";
	$res = $conexion->query($select,MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	$res->free();
	//SACAMOS ENTIDADES FINANCIERAS
	if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		  $selectEf="select 
						ef.id_ef, ef.nombre, ef.logo, ef.activado
					from
						s_entidad_financiera as ef
					where
						ef.activado = 1
							and exists( select 
								sh.id_ef
							from
								s_sgc_home as sh
							where
								sh.id_ef = ef.id_ef);";
	}else{
		 $selectEf="select 
						  ef.id_ef, ef.nombre, ef.logo, ef.activado
					  from
						  s_entidad_financiera as ef
					  where
						  ef.activado = 1 
							and exists( select 
								sh.id_ef
							from
								s_sgc_home as sh
							where
								sh.id_ef = ef.id_ef)
							  and ef.id_ef = '".base64_decode($_GET['id_ef_sesion'])."';";
	}	  
	 $resenfi = $conexion->query($selectEf,MYSQLI_STORE_RESULT);		  
	echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Editar correo electrónico</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditaCorreo" id="formEditaCorreo">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item large">
							   <select id="idefin" class="requerid">';
								  echo'<option value="">seleccione...</option>';
								  while($regienfi = $resenfi->fetch_array(MYSQLI_ASSOC)){
									 if($regienfi['id_ef']==$regi['id_ef']){  
									   echo'<option value="'.$regienfi['id_ef'].'" selected>'.$regienfi['nombre'].'</option>';  
									 }else{
										echo'<option value="'.$regienfi['id_ef'].'">'.$regienfi['nombre'].'</option>';   
									 }
								  }
								  $resenfi->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es"></span>
						   </div>   
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Nombre</span></b></label>
						   <div class="da-form-item large">
						     <input class="textbox required" type="text" id="txtNombre" value="'.$regi['nombre'].'" autocomplete="off"/>
						     <span class="errorMessage" id="errornombre" lang="es"></span>
						   </div>	 
					  </div>
					   <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Correo electrónico</span></b></label>
						   <div class="da-form-item large">
							   <input class="textbox required" type="text" id="txtCorreo" value="'.$regi['correo'].'" autocomplete="off"/>
							   <span class="errorMessage" id="errorcorreo" lang="es"></span>
						   </div>	   
					  </div>
					  <div class="da-form-row" id="content-entidadf">
						   <label style="text-align:right;"><b><span lang="es">Producto</span>:</b></label>
						   <div class="da-form-item large">
						     <span id="entidad-loading" class="loading-entf">';
								 $select="select
											id_home,
											id_ef,
											producto,
											producto_nombre	
										  from
											s_sgc_home
										  where
											id_ef='".$regi['id_ef']."' and producto!='H';";
								  $sql = $conexion->query($select,MYSQLI_STORE_RESULT);
								  echo'<select name="producto" id="producto" class="required" style="width:230px;">';
											  echo'<option value="" lang="es">seleccionar...</option>';
											  while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
												  if($regi['producto']==$regief['producto']){	
													echo'<option value="'.$regief['producto'].'" selected>'.$regief['producto_nombre'].'</option>';
													echo'<option value="F'.$regief['producto'].'">Facultativo '.$regief['producto_nombre'].'</option>';   
												  }elseif($regi['producto']=='F'.$regief['producto']){
													echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';
													echo'<option value="F'.$regief['producto'].'" selected>Facultativo '.$regief['producto_nombre'].'</option>';  
												  }elseif($regi['producto']=='CO'){
											          echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';
													  echo'<option value="F'.$regief['producto'].'">Facultativo '.$regief['producto_nombre'].'</option>';   
													  echo'<option value="CO" selected>Contactos</option>';
													  
												  }else{
													  echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';
													  echo'<option value="F'.$regief['producto'].'">Facultativo '.$regief['producto_nombre'].'</option>';
													   echo'<option value="CO">Contactos</option>';   
												  }
											  }
								  echo'</select>
									   <span class="errorMessage" id="errorproducto" lang="es"></span>
							 </span> 		
						   </div>		
					  </div>   					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idcorreo" value="'.base64_decode($_GET['idcorreo']).'"/>
						  <input type="hidden" id="id_ef" value="'.base64_decode($_GET['id_ef']).'"/>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='crear_agencia'){
	 //SACAMOS LOS DEPARTAMENTOS POR REGION
	 $select="select
				  id_depto,
				  departamento,
				  codigo
				from
				  s_departamento
				where
				  tipo_re=1 or tipo_dp=1;";
	 $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	 //SACAMOS LAS ENTIDADES FINANCIERAS ACTIVADAS
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef);";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef)
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
	}  
	 $res1 = $conexion->query($select1, MYSQLI_STORE_RESULT);			  
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Añadir nueva agencia</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaAgencia" id="formCreaAgencia">						
					  <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item">
							   <select id="idefin" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
							</div>   
					  </div>
					  <div class="da-form-row">
						   <label style="width:135px; padding-right:5px; text-align:right; "><b><span lang="es">Departamento/Sucursal</span></b></label>
						   <div class="da-form-item">
							   <select id="id_depto" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									echo'<option value="'.$regi['id_depto'].'">'.$regi['departamento'].'</option>';  
								  }
								  $res->free();
						  echo'</select>
							   <span class="errorMessage" id="errorregion" lang="es" style="display:none;">seleccione regional</span>
						   </div>	   
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Agencia</span></b></label>
						   <div class="da-form-item">
						     <input class="textbox required" type="text" id="txtAgencia" value="" autocomplete="off"/>
						     <span class="errorMessage" id="erroragencia_a" lang="es" style="display:none;">ingrese la agencia</span>
							 <span class="errorMessage" id="erroragencia_b" lang="es" style="display:none;">ingrese solo alfanumericos</span>
						   </div>	 
					  </div>
					   <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Codigo</span></b></label>
						   <div class="da-form-item">
						     <input class="textbox required" type="text" id="txtCodigo" value="" autocomplete="off"/>
						     <span class="errorMessage" id="errorcodigo"></span>
						   </div>	 
					  </div>  					  
					  <div class="da-form-row" style="display:none" id="content-emision">
						  <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Emitir</span></b></label>
						  <div class="da-form-item">
							  <ul class="da-form-list inline">
								  <li><input type="radio" name="emitir" id="rd-1" value="1" class="required"/> <label lang="es">Si</label></li>
								  <li><input type="radio" name="emitir" id="rd-2" value="0" class="required"/> <label>No</label></li>
							  </ul>
							  <span class="errorMessage" id="erroremitir"></span>
						  </div>
					  </div>
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green" lang="es"/>   
						  <div id="response-loading" class="loading-fac left"></div>
						  <input type="hidden" id="resp_emi" value=""/>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='editar_agencia'){
	 $select="select
				  id_depto,
				  departamento,
				  codigo
				from
				  s_departamento
				where
				  tipo_re=1 or tipo_dp=1;";
	 $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	 
	 $buscarAge="select 
					sa.id_agencia, 
					sa.codigo, 
					sa.agencia, 
					sa.id_depto, 
					sa.id_ef,
					sd.departamento,
					sa.emision
				from
					s_agencia as sa
					inner join s_departamento as sd on (sd.id_depto=sa.id_depto)
				where
					sa.id_ef = '".base64_decode($_GET['id_ef'])."' and sa.id_agencia='".base64_decode($_GET['idagencia'])."' and sa.id_depto=".base64_decode($_GET['id_depto']).";";
	 $resib = $conexion->query($buscarAge,MYSQLI_STORE_RESULT);
	 $regibusq = $resib->fetch_array(MYSQLI_ASSOC);
	 $resib->free();
	 //SACAMOS LAS ENTIDADES FINANCIERAS ACTIVADAS
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef);";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef)
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
	}  
		  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 
	 $busca="select
				   count(id_home) as num
				from
				  s_sgc_home
				where
				  id_ef='".$regibusq['id_ef']."' and implante=1;";
	  $resbu = $conexion->query($busca, MYSQLI_STORE_RESULT);
	  $regibusca = $resbu->fetch_array(MYSQLI_ASSOC);
	  $resbu->free();
	  if($regibusca['num']>0){
		  $display='';
		  $res_emi=1;
	  }else{
		  $display='style="display:none"';
		  $res_emi=0;
	  }								  
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Editar Agencia
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditaAgencia" id="formEditaAgencia">						
					  <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item">
							   <select id="idefin" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									   if($regi1['id_ef']==$regibusq['id_ef']){
										  echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
									   }else{
										  echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									   }
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
							</div>   
					  </div>
					  <div class="da-form-row">
						   <label style="width:135px; text-align:right; padding-right:5px;"><b><span lang="es">Departamento/Sucursal</span></b></label>
						   <div class="da-form-item">
							   <select id="id_depto" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									if($regi['id_depto']==$regibusq['id_depto']){
									   echo'<option value="'.$regi['id_depto'].'" selected>'.$regi['departamento'].'</option>';  
									}else{
									   echo'<option value="'.$regi['id_depto'].'">'.$regi['departamento'].'</option>';  
									}
								  }
								  $res->free();
						  echo'</select>
							   <span class="errorMessage" id="errorregion" lang="es" style="display:none">seleccione regional</span>
							</div>   
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Agencia</span></b></label>
						   <div class="da-form-item">
							   <input class="textbox required" type="text" id="txtAgencia" value="'.$regibusq['agencia'].'" autocomplete="off"/>
							   <span class="errorMessage" id="erroragencia" lang="es" style="display:none;">ingrese la agencia</span>
						   </div>	   
					  </div>
					   <div class="da-form-row">
						   <label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Codigo</span></b></label>
						   <div class="da-form-item">
							   <input class="textbox required" type="text" id="txtCodigo" value="'.$regibusq['codigo'].'" autocomplete="off"/>
							   <span class="errorMessage" id="errorcodigo" lang="es" style="display:none">ingrese solo alfanumericos</span>
						   </div>	   
					  </div>
					  <div class="da-form-row" '.$display.' id="content-emision">
							<label style="text-align:right; width:135px; padding-right:5px;"><b><span lang="es">Emitir</span></b></label>
							<div class="da-form-item">
								<ul class="da-form-list inline">';
								   if($regibusq['emision']==1){	
									  echo'<li><input type="radio" name="emitir" id="rd-1" value="1" class="required" checked/> <label>Si</label></li>';
								   }else{
									  echo'<li><input type="radio" name="emitir" id="rd-1" value="1" class="required"/> <label>Si</label></li>'; 
								   }
								   if($regibusq['emision']==0){
									  echo'<li><input type="radio" name="emitir" id="rd-2" value="0" class="required" checked/> <label>No</label></li>';
								   }else{
									  echo'<li><input type="radio" name="emitir" id="rd-2" value="0" class="required"/> <label>No</label></li>'; 
								   }
						   echo'</ul>
								<span class="errorMessage" id="errorfactura"></span>
							</div>
						</div>  					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green" lang="es"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idagencia" value="'.base64_decode($_GET['idagencia']).'"/>
						  <input type="hidden" id="id_ef" value="'.base64_decode($_GET['id_ef']).'"/>
						  <input type="hidden" id="id_depto" value="'.base64_decode($_GET['id_depto']).'"/>
						  <input type="hidden" id="resp_emi" value="'.$res_emi.'"/>
						  
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='crear_sucursal'){		  
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef);";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef)
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
    }
	$res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	$num_reg = $res1->num_rows;	  
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Añadir Nuevo Departamento/Sucursal</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaSucursal" id="formCreaSucursal">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Departamento/Sucursal</span></b></label>
						   <div class="da-form-item large" style="text-align:right;">
						     <input class="textbox required" type="text" id="txtSucursal" value="" autocomplete="off" style="width:290px;"/>
						     <span class="errorMessage" id="errorsucursal_a" lang="es" style="display:none;">ingrese la agencia</span>
							 <span class="errorMessage" id="errorsucursal_b" lang="es" style="display:none;">ingrese solo alfanumericos</span>
						   </div>	 
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Codigo</span></b></label>
						   <div class="da-form-item large" style="text-align:right;">
						     <input class="textbox required" type="text" id="txtCodigo" value="" autocomplete="off" maxlength="3" style="width:290px;"/>
						     <span class="errorMessage" id="errorcodigo" lang="es" style="display:none;">ingrese solo alfanumericos</span>
						   </div>	 
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right; margin-right:300px;"><b><span lang="es">Tipo</span></b></label><br/>
						   <div style="text-align:center; padding-left:15px; padding-right:15px;">
							  <table border="0" cellpadding="0" cellpadding="0" style="width:100%;" align="left"> 
							     <tr>
								   <td style="width:33%; text-align:center;">';
								     if(base64_decode($_GET['tipo_sesion'])!='ADM'){
								        echo'<input type="checkbox" name="tipoci" id="tipoci" value="ci" class="required"/>CI';
									 }else{
										echo'&nbsp;'; 
									 }
							  echo'</td>';
							        if(base64_decode($_GET['tipo_sesion'])!='ADM'){
								   echo'<td style="width:33%; text-align:center;">
										   <input type="checkbox" name="tiporeg" id="tiporeg" value="regional" class="required"/><span lang="es">Regional</span>
									    </td>';
									}else{
								   echo'<td style="width:33%; text-align:left;">
										   <input type="checkbox" name="tiporeg" id="tiporeg" value="regional" class="required"/><span lang="es">Regional</span>
									    </td>';
									
									}
							  echo'<td style="widht:34%; text-align:center;">';
								     if(base64_decode($_GET['tipo_sesion'])!='ADM'){
							          echo'<input type="checkbox" name="tipodepto" id="tipodepto" value="depto" class="required"/><span lang="es">Departamento</span>';
									 }else{
										echo'&nbsp;'; 
								     }
							  echo'</td>
							    </tr>
								<tr>
								 <td colspan="3" style="text-align:center;"><span class="errorMessage" id="errortipo" lang="es" style="display:none;">debe seleccionar al menos un tipo</span></td> 
								</tr>
							  </table>	   
					       </div>	   
					  </div>
					  <div class="da-form-row" style="display: none;" id="content-entidadf">
						   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item large">
							   <select id="idefin" class="requerid" style="width:290px;">';
								  echo'<option value="">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es"></span>
						    </div>	   
					  </div>  	 					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green" lang="es"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
		  </div>';
}elseif($_GET['opcion']=='editar_sucursal'){
	$buscarSuc="select
				   id_depto,
				   departamento,
				   codigo,
				   id_ef,
				   tipo_ci,
				   tipo_re,
				   tipo_dp  
				 from
				   s_departamento
				 where
				   id_depto=".base64_decode($_GET['id_depto'])."   
				 limit 0,1;";
	 $res = $conexion->query($buscarSuc,MYSQLI_STORE_RESULT);			 
	 $regi = $res->fetch_array(MYSQLI_ASSOC);
	 $res->free();
	 if($regi['tipo_ci']==1){
		 $tipo='ci';
		 $display='style="display: none;"';
     }elseif($regi['tipo_re']==1){
		 $tipo='regional';
		 $display='';
	 }elseif($regi['tipo_dp']==1){
		 $tipo='depto';
		 $display='style="display: none;"';
	 }else{
		 $tipo='';
		 $display='style="display: none;"';
	 }
	 //SACAMOS ENTIDADES FINANCIERAS
	if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		  $selectEf="select 
						ef.id_ef, ef.nombre, ef.logo, ef.activado
					from
						s_entidad_financiera as ef
					where
						ef.activado = 1
							and exists( select 
								sh.id_ef
							from
								s_sgc_home as sh
							where
								sh.id_ef = ef.id_ef);";
	}else{
		 $selectEf="select 
						  ef.id_ef, ef.nombre, ef.logo, ef.activado
					  from
						  s_entidad_financiera as ef
					  where
						  ef.activado = 1 
							and exists( select 
								sh.id_ef
							from
								s_sgc_home as sh
							where
								sh.id_ef = ef.id_ef)
							  and ef.id_ef = '".base64_decode($_GET['id_ef_sesion'])."';";
	}	  
	 $resenfi = $conexion->query($selectEf,MYSQLI_STORE_RESULT);			
	echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Editar Departamento/Sucursal</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditarSucursal" id="formEditarSucursal">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Departamento/Sucursal</span></b></label>
						   <div class="da-form-item large" style="text-align:right;">
							   <input class="textbox required" type="text" id="txtSucursal" value="'.$regi['departamento'].'" autocomplete="off" style="width:290px;"/>
							   <span class="errorMessage" id="errorsucursal_a" lang="es" style="display:none;">ingrese la agencia</span>
							   <span class="errorMessage" id="errorsucursal_b" lang="es" style="display:none;">ingrese solo alfanumericos</span>
						   </div>	   
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Codigo</b></label>
						   <div class="da-form-item large" style="text-align:right;">
							   <input class="textbox required" type="text" id="txtCodigo" value="'.$regi['codigo'].'" autocomplete="off" maxlength="3" style="width:290px;"/>
							   <span class="errorMessage" id="errorcodigo" lang="es" style="display:none">ingrese solo alfanumericos</span>
						   </div>	   
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right; margin-right:300px;"><b><span lang="es">Tipo</span></b></label><br/>
						   <div style="text-align:center; padding-left:15px; padding-right:15px;">
							  <table border="0" cellpadding="0" cellpadding="0" style="width:100%;" align="left"> 
							     <tr>
								   <td style="width:33%; text-align:center;">';
								     if($regi['tipo_ci']==1){
									   echo'<input type="checkbox" name="tipoci" class="tipoci" checked disabled/>CI';
									 }else{
									   echo'<input type="checkbox" name="tipoci" class="tipoci" disabled/>CI';
									 }
							  echo'</td>
							       <td style="width:33%; text-align:center;">';
							         if($regi['tipo_re']==1){
							            echo'<input type="checkbox" name="tiporeg" class="tiporeg" checked disabled/><span lang="es">Regional</span>';
									 }else{
								        echo'<input type="checkbox" name="tiporeg" class="tiporeg" disabled/><span lang="es">Regional</span>'; 
									 }
									 
							  echo'</td>
							       <td style="widht:34%; text-align:center;">';
							         if($regi['tipo_dp']==1){
							           echo'<input type="checkbox" name="tipodepto" class="tipodepto" checked disabled/><span lang="es">Departamento</span>';
									 }else{
									   echo'<input type="checkbox" name="tipodepto" class="tipodepto" disabled/><span lang="es">Departamento</span>';
									 }
							echo'  </td> 
							    </tr>
							  </table>	   
					       </div>	   
					  </div>
					  <div class="da-form-row" '.$display.' id="content-entidadf">
						   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item large" style="text-align:right;">
							   <select id="idefin" class="requerid" style="width:290px;">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $resenfi->fetch_array(MYSQLI_ASSOC)){
									  if($regi1['id_ef']==$regi['id_ef']){
									   echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
									  }else{
										echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									  }
								  }
								  $resenfi->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad"></span>
						    </div>	   
					  </div>  	 	  	 					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green" lang="es"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="id_depto" value="'.base64_decode($_GET['id_depto']).'"/>
					  </div>
				  </form>
			  </div>
		  </div>';
}elseif($_GET['opcion']=='editar_adciaef'){
	 $select1="select
				  id_ef,
				  nombre,
				  codigo,
				  activado
				from
				  s_entidad_financiera
				where
				  activado=1;";		  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 
	 $select2="select
				  id_compania,
				  nombre,
				  producto
				from
				  s_compania
				where
				  activado=1;";
	 $res2 = $conexion->query($select2,MYSQLI_STORE_RESULT);						  
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Editar Compañia Entidad
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditaAdCia" id="formEditaAdCia">						
					  <div class="da-form-row">
						   <label style="width:190px;"><b>Entidad Financiera</b></label>
						   <select id="idefin" class="requerid">';
						      echo'<option value="">seleccione...</option>';
							  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
								if($regi1['id_ef']==base64_decode($_GET['id_ef'])){
								   echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
								}else{
								   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								}
							  }
							  $res1->free();
					  echo'</select>
						   <span class="errorMessage" id="errorentidad"></span>
					  </div>
					  <div class="da-form-row">
						   <label style="width:190px;"><b>Compañia de Seguros</b></label>
						   <select id="idcompania" class="requerid">';
						      echo'<option value="">seleccione...</option>';
							  while($regi2 = $res2->fetch_array(MYSQLI_ASSOC)){
								if($regi2['id_compania']==base64_decode($_GET['id_compania'])){
								   echo'<option value="'.$regi2['id_compania'].'" selected>'.$regi2['nombre'].'</option>';  
								}else{
								   echo'<option value="'.$regi2['id_compania'].'">'.$regi2['nombre'].'</option>';  
								}
							  }
							  $res2->free();
					  echo'</select>
						   <span class="errorMessage" id="errorcompania"></span>
					  </div>					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='crear_adciaef'){
	  //VERIFICAMOS QUE LAS ENTIDADES FINANCIERAS ESTEN ACTIVADAS
	  $select1="select
				  id_ef,
				  nombre,
				  codigo,
				  activado
				from
				  s_entidad_financiera
				where
				  activado=1;";		  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	
	 //VERIFICAMOS QUE LAS COMPAÑIAS ESTEN ACTIVADAS
	 $select2="select
				  id_compania,
				  nombre
				from
				  s_compania
				where
				  activado=1;";
	 $res2 = $conexion->query($select2,MYSQLI_STORE_RESULT);
	 $num2 = $res2->num_rows;						  
	 echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Añadir Compañía de Seguros a Entidad Financiera</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaAdCia" id="formCreaAdCia">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						   <div class="da-form-item large">
							   <select id="idefin" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
							</div>   
					  </div>
					  <div class="da-form-row" style="display: none;" id="content-entidadf">
						  <label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
						  <div class="da-form-item large">
							<span id="entidad-loading" class="loading-entf"></span>
						  </div>
					  </div> 
					  <div class="da-form-row">
						   <label style="text-align:right;"><b><span lang="es">Compañia de Seguros</span></b></label>
						   <div class="da-form-item large">
							   <select id="idcompania" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi2 = $res2->fetch_array(MYSQLI_ASSOC)){
									   echo'<option value="'.$regi2['id_compania'].'">'.$regi2['nombre'].'</option>';  
								  }
								  $res2->free();
						  echo'</select>
							   <span class="errorMessage" id="errorcompania" lang="es" style="display:none;">seleccione compañia</span>
							</div>   
					  </div>					  
					  <div class="da-button-row">';
					      if($num2>0){
					         echo'<input type="submit" value="Guardar" class="da-button green" lang="es"/>';
						  }else{
							 echo'<input type="submit" value="Guardar" class="da-button green" disabled lang="es"/>'; 
						  }
						  echo'<div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='crear_tipovehi'){//AGREGAR TIPO DE VEHICULO
	//SACAMOS LAS ENTIDADES FINANCIERAS ACTIVADAS
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef and sh.producto='AU');";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef and sh.producto='AU')
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
	}
	$res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	$num_reg = $res1->num_rows;  
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Tipo de Vehiculo</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdTipVehi" id="frmAdTipVehi" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 <select id="idefin" class="required" style="width:160px;">';
								echo'<option value="" lang="es">seleccione...</option>';
								while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								}
								$res1->free();
						echo'</select>
							 <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Tipo de Vehículo</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtTipVehi" value="" class="required"/>
							<span class="errorMessage" id="errortipvehi_a" lang="es" style="display:none;">ingrese el tipo de vehiculo</span>
							<span class="errorMessage" id="errortipvehi_b" lang="es" style="display:none;">ingrese solo carcateres</span>
						</div>
					</div>							
					<div class="da-button-row">';
					  if($num_reg>0){	
						echo'<input type="submit" value="Guardar" class="da-button green" lang="es"/>';
					  }else{
					    echo'<input type="submit" value="Guardar" class="da-button green" disabled lang="es"/>';	  
				      }
					  echo'<div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';
}elseif($_GET['opcion']=='editar_tipvehi'){
	$select="select
			   atv.id_tipo_vh,
			   atv.id_ef,
			   atv.vehiculo,
			   sef.nombre
			from
			  s_au_tipo_vehiculo as atv
			  inner join s_entidad_financiera as sef on (sef.id_ef=atv.id_ef) 
			where
			  atv.id_ef='".base64_decode($_GET['id_ef'])."' and atv.id_tipo_vh='".base64_decode($_GET['idtipovh'])."';";
	$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
	$regi = $sql->fetch_array(MYSQLI_ASSOC);
	$sql->free();
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Tipo de Vehiculo</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEdiTipVehi" id="frmEdiTipVehi" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.$regi['nombre'].'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Tipo de Vehículo</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtTipVehi" class="required" value="'.$regi['vehiculo'].'"/>
							<span class="errorMessage" id="errortipvehi" lang="es" style="display:none;">ingrese el tipo de vehiculo</span>
						</div>
					</div>							
					<div class="da-button-row">
					  <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					  <input type="hidden" id="id_ef" value="'.base64_decode($_GET['id_ef']).'"/>
					  <input type="hidden" id="idtipovh" value="'.base64_decode($_GET['idtipovh']).'"/>
					<div id="response-loading" class="loading-fac" lang="es"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';		  
}elseif($_GET['opcion']=='crear_marca_auto'){//AGREGAR MARCA DE AUTO
	//SACAMOS LAS ENTIDADES FINANCIERAS ACTIVADAS
	 if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef and sh.producto='AU');";		 
	}else{
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef and sh.producto='AU')
						  and ef.id_ef='".base64_decode($_GET['id_ef_sesion'])."';";		
	}
	$res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	$num_reg = $res1->num_rows;
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Marca de Auto</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdMarca" id="frmAdMarca" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 <select id="idefin" class="required" style="width:160px;">';
								echo'<option value="" lang="es">seleccione...</option>';
								while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
								}
								$res1->free();
						echo'</select>
							 <span class="errorMessage" id="errorentidad" lang="es" style="display:none;">seleccione entidad financiera</span>
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Marca de Auto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtMarcaAuto" value="" class="required"/>
							<span class="errorMessage" id="errormarcauto_a" lang="es" style="display:none;">ingrese marca de auto</span>
							<span class="errorMessage" id="errormarcauto_b" lang="es" style="display:none;">ingrese solo caracteres</span>
						</div>
					</div>							
					<div class="da-button-row">';
					  if($num_reg>0){	
						echo'<input type="submit" value="Guardar" class="da-button green" lang="es"/>';
					  }else{
					    echo'<input type="submit" value="Guardar" class="da-button green" disabled lang="es"/>';	  
				      }
					  echo'<div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  
}elseif($_GET['opcion']=='editar_marca_auto'){
	$select="select
				aum.id_marca,
				aum.id_ef,
				aum.marca,
				ef.nombre 
			  from
				s_au_marca as aum
				inner join s_entidad_financiera as ef on (ef.id_ef=aum.id_ef)
			  where
				aum.id_ef='".base64_decode($_GET['id_ef'])."' and aum.id_marca='".base64_decode($_GET['id_marca'])."';";
    $res = $conexion->query($select, MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Marca de Auto</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEdMarca" id="frmEdMarca" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.$regi['nombre'].'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Marca de Auto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtMarcaAuto" value="'.$regi['marca'].'" class="required"/>
							<span class="errorMessage" id="errormarcauto_a" lang="es" style="display:none;">ingrese marca de auto</span>
							<span class="errorMessage" id="errormarcauto_b" lang="es" style="display:none;">ingrese solo caracteres</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_ef" value="'.base64_decode($_GET['id_ef']).'"/>
					   <input type="hidden" id="id_marca" value="'.base64_decode($_GET['id_marca']).'"/>
					<div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  				
}elseif($_GET['opcion']=='crear_modelo_auto'){
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Modelo de Auto</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdModelo" id="frmAdModelo" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['entidad']).'
						 </div>	 
					</div>
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Marca</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['marca']).'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Modelo de Auto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtModeloAuto" value="" class="required"/>
							<span class="errorMessage" id="errormodeauto_a" lang="es" style="display:none;">ingrese modelo de auto</span>
							<span class="errorMessage" id="errormodeauto_b" lang="es" style="display:none;">El modelo de auto ya existe, ingrese otro modelo</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_marca" value="'.base64_decode($_GET['id_marca']).'"/>	
					   <div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  
}elseif($_GET['opcion']=='editar_modelo_auto'){
    $select="select
			  id_modelo,
			  id_marca,
			  modelo
			from
			  s_au_modelo
			where
			  id_modelo='".base64_decode($_GET['id_modelo'])."' and id_marca='".base64_decode($_GET['id_marca'])."';";
	$res = $conexion->query($select, MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	$res->free();
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Modelo de Auto</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEditModelo" id="frmEditModelo" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['entidad']).'
						 </div>	 
					</div>
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Marca</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['marca']).'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Modelo de Auto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtModeloAuto" value="'.$regi['modelo'].'" class="required"/>
							<span class="errorMessage" id="errormodeauto" lang="es" style="display:none;">ingrese modelo de auto</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_marca" value="'.base64_decode($_GET['id_marca']).'"/>
					   <input type="hidden" id="id_modelo" value="'.base64_decode($_GET['id_modelo']).'"/>	
					   <div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  		  
}elseif($_GET['opcion']=='crear_tipo_producto'){
//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
if(base64_decode($_GET['tipo_sesion'])=='ROOT'){
	  $selectEf="select 
					ef.id_ef, ef.nombre, ef.logo, ef.activado
				from
					s_entidad_financiera as ef
				where
					ef.activado = 1
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='DE');";
}else{
	 $selectEf="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1 
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='DE')
						  and ef.id_ef = '".base64_decode($_GET['id_ef_sesion'])."';";
}
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_regi_ef = $resef->num_rows;
	echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  <span lang="es">Agregar nuevo registro</span>
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formAdTip" id="formAdTip">						
					  <div class="da-form-row">
						   <label style="width:120px; text-align:right;"><b><span lang="es">Entidad Financiera</span>:</b></label>
						   <div class="da-form-item large">
							   <select id="idefin" class="requerid">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($regi1 = $resef->fetch_array(MYSQLI_ASSOC)){
									if($regi1['id_ef']==base64_decode($_GET['id_ef'])){
									   echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
									}else{
									   echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									}
								  }
								  $resef->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad" lang="es"></span>
						   </div>	   
					  </div>
					  <div class="da-form-row">
						   <label style="width:120px; text-align:right;"><b><span lang="es">Tipo de registro</span>:</b></label>
						   <div class="da-form-item large">
							   <select id="tip_producto" class="requerid">
								   <option value="" lang="es">seleccione...</option>
								   <option value="PRODUCTO">Con Producto Crediticio</option>
								   <option value="NO PRODUCTO">Sin Producto Crediticio</option>
							   </select>
							   <span class="errorMessage" id="errortipproducto" lang="es"></span>
						   </div>	   
					  </div>					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
		  </div>';  
}elseif($_GET['opcion']=='agregar_productos'){
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Productos</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdProductos" id="frmAdProductos" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['entidad']).'
						 </div>	 
					</div>	
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['compania']).'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtProductos" value="" class="requerid"/>
							<span class="errorMessage" id="errorproductos_a" lang="es" style="display:none;">ingrese producto</span>
							<span class="errorMessage" id="errorproductos_b" lang="es" style="display:none;">ingrese solo caracteres</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
					   <input type="hidden" id="id_producto" value="'.base64_decode($_GET['id_producto']).'"/>
					   <div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  
}elseif($_GET['opcion']=='editar_productos'){
	$select="select
			  id_prcia,
			  nombre,
			  id_ef_cia,
			  id_producto
			from
			  s_producto_cia
			where
			  id_ef_cia='".base64_decode($_GET['id_ef_cia'])."' and id_producto=".base64_decode($_GET['id_producto'])." and id_prcia=".base64_decode($_GET['idprcia']).";";
    $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	$res->free();
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Productos</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEditaProdu" id="frmEditaProdu" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['entidad']).'
						 </div>	 
					</div>
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['compania']).'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
						<div class="da-form-item large">
							<input type="text" id="txtProductos" value="'.$regi['nombre'].'" class="required"/>
							<span class="errorMessage" id="errorproductos_a" lang="es" style="display:none;">ingrese producto</span>
							<span class="errorMessage" id="errorproductos_b" lang="es" style="display:none;">ingrese solo caracteres</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
					   <input type="hidden" id="id_producto" value="'.base64_decode($_GET['id_producto']).'"/>
					   <input type="hidden" id="idprcia" value="'.base64_decode($_GET['idprcia']).'"/>
					   <div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  			  
}elseif($_GET['opcion']=='crear_pregunta'){
    echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Crear Pregunta</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdPregunta" id="frmAdPregunta" action="" method="post">
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['entidad']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['compania']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row" id="content-entidadf" style="display:none;">
						<label text-align:right; margin-right:10px;><b><span lang="es">Producto</span></b></label>
						<div class="da-form-item small">
						  <span id="entidad-loading" class="loading-entf">';
							   $select="select
										  id_home,
										  producto,
										  case producto
											when 'DE' then 'Desgravamen'
											when 'TR' then 'Todoriesgo'
											when 'AU' then 'Automotores'
										  end as producto_text,	
										  id_ef
										from
										  s_sgc_home
										where
										  id_ef='".base64_decode($_GET['id_ef'])."' and producto!='H';";
								$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
								echo'<select name="producto" id="producto" class="required" style="width:170px;">';
											echo'<option value="">Seleccionar...</option>';
											while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
											  echo'<option value="'.$regief['producto'].'">'.$regief['producto_text'].'</option>';
											}
											$sql->free();
								echo'</select>
									 <span class="errorMessage" id="errorproducto"></span>';
					 echo'</span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Pregunta</span></b></label>
						<div class="da-form-item large">
							<textarea id="txtPregunta" name="txtPregunta" style="height:70px;" class="required"></textarea>
							<span class="errorMessage" id="errorpregunta" lang="es" style="display:none;">ingrese la pregunta</span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Respuesta esperada</span></b></label>
						<div class="da-form-item large">';
						echo'<select name="txtRespuesta" id="txtRespuesta" style="width:120px;" class="required">
						        <option value="" lang="es">seleccione...</option>
								<option value="1" lang="es">Si</option>
								<option value="0" lang="es">No</option>
						     </select>
							 <span class="errorMessage" id="errorespuesta" lang="es" style="display:none;">seleccione respuesta</span>';
						/*	
						echo'<select name="txtRespuesta" id="txtRespuesta" style="width:120px;">
								<option value=""';
								 if($txtRespuesta=='') 
									 echo 'selected'; 
						  echo '>Seleccionar...</option>
								<option value="1"';
								 if($txtRespuesta=='1') 
									 echo 'selected'; 
						  echo '>si</option>'
							 .'<option value="0"'; 
								 if($txtRespuesta=='0') 
									 echo 'selected';
						 echo '>no</option>
							</select>
							<span class="errorMessage" id="errorespuesta"></span>';*/
					echo'</div>
					</div>												
					<div class="da-button-row">
						<input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta" lang="es"/>
						<input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
						<div id="response-loading" class="loading-fac"></div>
					</div>
				</form>
			</div>
		</div>'; 	
}elseif($_GET['opcion']=='edita_pregunta'){
	$selectFor="select
				   id_pregunta,
				   pregunta,
				   orden,
				   respuesta, 
				   producto,
				   id_ef_cia
				from
				  s_pregunta
				where
				  id_ef_cia='".base64_decode($_GET['id_ef_cia'])."' and id_pregunta=".base64_decode($_GET['idpregunta'])." and producto='DE';";
	$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	$res->free();		  
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Pregunta</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEdiPregunta" id="frmEdiPregunta" action="" method="post">
					<div class="da-form-row">
						<label style="text-align:right; text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['entidad']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['compania']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row" id="content-entidadf" style="display:none;">
						<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
						<div class="da-form-item small">
						  <span id="entidad-loading" class="loading-entf">';
							   $select="select
										  id_home,
										  producto,
										  case producto
											when 'DE' then 'Desgravamen'
											when 'TR' then 'Todoriesgo'
											when 'AU' then 'Automotores'
										  end as producto_text,	
										  id_ef
										from
										  s_sgc_home
										where
										  id_ef='".base64_decode($_GET['id_ef'])."' and producto!='H';";
								$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
								echo'<select name="producto" id="producto" class="required" style="width:170px;">';
											echo'<option value="">Seleccionar...</option>';
											while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
											  echo'<option value="'.$regief['producto'].'">'.$regief['producto_text'].'</option>';
											}
											$sql->free();
								echo'</select>
									 <span class="errorMessage" id="errorproducto" lang="es"></span>';
					 echo'</span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Pregunta</span></b></label>
						<div class="da-form-item large">
							<textarea id="txtPregunta" name="txtPregunta" style="height:70px;" class="required">'.$regi['pregunta'].'</textarea>
							<span class="errorMessage" id="errorpregunta"></span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Respuesta esperada</span></b></label>
						<div class="da-form-item large">';
						echo'<select name="txtRespuesta" id="txtRespuesta" style="width:120px;">
								<option value=""';
								 if($regi['respuesta']=='') 
									 echo 'selected'; 
						  echo ' lang="es">seleccione...</option>
								<option value="1"';
								 if($regi['respuesta']=='1') 
									 echo 'selected'; 
						  echo '>Si</option>'
							 .'<option value="0"'; 
								 if($regi['respuesta']=='0') 
									 echo 'selected';
						 echo '>No</option>
							</select>
							<span class="errorMessage" id="errorespuesta" lang="es"></span>';
					echo'</div>
					</div>												
					<div class="da-button-row">
						<input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta" lang="es"/>
						<input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
						<input type="hidden" id="id_pregunta" value="'.base64_decode($_GET['idpregunta']).'"/>
						<div id="response-loading" class="loading-fac"></div>
					</div>
				</form>
			</div>
		</div>'; 	
}elseif($_GET['opcion']=='crear_prodextra'){
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Crear Poducto Extra</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdProdExtra" id="frmAdProdExtra" action="" method="post">
					<div class="da-form-row">
						<label style="text-align:right; text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['entidad']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['compania']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row" style="padding-left:5px; padding-right:5px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
						   <tr>
						     <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Mínimo</span> (Bs)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtRgMinBs" id="txtRgMinBs" value="" style="width:125px;"/>
									<span class="errorMessage" id="errorminbs" lang="es"></span>
								</div>
							 </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Máximo</span> (Bs)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtRgMaxBs" id="txtRgMaxBs" value="" style="width:125px;"/>
									<span class="errorMessage" id="errormaxbs"></span>
								</div>
							 </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Mínimo</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
								  <input class="textbox required" type="text" name="txtRgMinUsd" id="txtRgMinUsd" value="" style="width:125px;" readonly="readonly"/>
								  <span class="errorMessage" id="errorminusd"></span>
								</div>
						     </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Máximo</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
								  <input class="textbox required" type="text" name="txtRgMaxUsd" id="txtRgMaxUsd" value="" style="width:125px;" readonly="readonly"/>
								  <span class="errorMessage" id="errormaxusd"></span>
								</div>
							 </td>
						   </tr>
						   <tr><td colspan="4">&nbsp;</td></tr>
						   <tr>
						      <td style="text-align:center;">
							    <lable><b><span lang="es">Hospitalario (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtHospitalario" id="txtHospitalario" value="" style="width:125px;"/>
									<span class="errorMessage" id="errorhospitalario"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Vida (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtVida" id="txtVida" value="" style="width:125px;"/>
									<span class="errorMessage" id="errorvida"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Cesantía (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtCesantia" id="txtCesantia" value="" style="width:125px;"/>
									<span class="errorMessage" id="errorcesantia"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Prima</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtPrima" id="txtPrima" value="" style="width:125px;"/>
									<span class="errorMessage" id="errorprima"></span>
								</div>
							  </td>
						   </tr>
						</table>
					</div>							
					<div class="da-button-row">
						<input type="submit" value="Guardar" class="da-button green" name="btnSave" id="btnSave" lang="es"/>
						<input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
						<div id="response-loading" class="loading-fac"></div>
					</div>
				</form>
			</div>
		</div>'; 	
}elseif($_GET['opcion']=='edita_prodextra'){
	$select="select
			  sdpe.id_pr_extra,
			  sdpe.id_ef_cia,
			  sdpe.rango,
			  sdpe.pr_hospitalario,
			  sdpe.pr_vida,
			  sdpe.pr_cesante,
			  sdpe.pr_prima
			from
			 s_de_producto_extra as sdpe
			 inner join s_ef_compania as efcia on (efcia.id_ef_cia=sdpe.id_ef_cia)
			where
			  sdpe.id_ef_cia='".base64_decode($_GET['id_ef_cia'])."' and efcia.producto='DE' and sdpe.id_pr_extra='".base64_decode($_GET['id_pr_extra'])."';";
	$res = $conexion->query($select,MYSQLI_STORE_RESULT);
	$regi = $res->fetch_array(MYSQLI_ASSOC);
	$jsonData = $regi['rango'];
	$phpArray = json_decode($jsonData, true);
	echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Editar Poducto Extra</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmEdiProdExtra" id="frmEdiProdExtra" action="" method="post">
					<div class="da-form-row">
						<label style="text-align:right; text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['entidad']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
						<div class="da-form-item small">
							'.base64_decode($_GET['compania']).'						
							<input type="hidden" name="idcompania" id="idcompania" value=""/>
						</div>
					</div>
					<div class="da-form-row" style="padding-left:5px; padding-right:5px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
						   <tr>
						     <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Mínimo</span> (Bs)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtRgMinBs" id="txtRgMinBs" value="'.$phpArray[0].'" style="width:125px;"/>
									<span class="errorMessage" id="errorminbs"></span>
								</div>
							 </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Máximo</span> (Bs)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtRgMaxBs" id="txtRgMaxBs" value="'.$phpArray[1].'" style="width:125px;"/>
									<span class="errorMessage" id="errormaxbs"></span>
								</div>
							 </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Mínimo</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
								  <input class="textbox required" type="text" name="txtRgMinUsd" id="txtRgMinUsd" value=" '.$phpArray[2].'" style="width:125px;" readonly="readonly"/>
								  <span class="errorMessage" id="errorminusd"></span>
								</div>
						     </td>
							 <td style="width:25%; text-align:center;">
							    <lable><b><span lang="es">Rango Máximo</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
								  <input class="textbox required" type="text" name="txtRgMaxUsd" id="txtRgMaxUsd" value="'.$phpArray[3].'" style="width:125px;" readonly="readonly"/>
								  <span class="errorMessage" id="errormaxusd"></span>
								</div>
							 </td>
						   </tr>
						   <tr><td colspan="4">&nbsp;</td></tr>
						   <tr>
						      <td style="text-align:center;">
							    <lable><b><span lang="es">Hospitalario (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtHospitalario" id="txtHospitalario" value="'.$regi['pr_hospitalario'].'" style="width:125px;"/>
									<span class="errorMessage" id="errorhospitalario"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Vida (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtVida" id="txtVida" value="'.$regi['pr_vida'].'" style="width:125px;"/>
									<span class="errorMessage" id="errorvida"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Cesantía (USD)</span></b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtCesantia" id="txtCesantia" value="'.$regi['pr_cesante'].'" style="width:125px;"/>
									<span class="errorMessage" id="errorcesantia"></span>
								</div>
							  </td>
							  <td style="text-align:center;">
							    <lable><b><span lang="es">Prima</span> (USD)</b></lable>
								<div class="da-form-item large" style="margin-left:0;">
									<input class="textbox required" type="text" name="txtPrima" id="txtPrima" value="'.$regi['pr_prima'].'" style="width:125px;"/>
									<span class="errorMessage" id="errorprima"></span>
								</div>
							  </td>
						   </tr>
						</table>
					</div>							
					<div class="da-button-row">
						<input type="submit" value="Guardar" class="da-button green" name="btnSave" id="btnSave"/>
						<input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
						<input type="hidden" id="id_pr_extra" value="'.base64_decode($_GET['id_pr_extra']).'"/>
						<div id="response-loading" class="loading-fac"></div>
					</div>
				</form>
			</div>
		</div>'; 	
			  
}elseif($_GET['opcion']=='crear_marcatarjeta'){
	   $marcas = array("Visa", "MasterCard", "American Express", "Diners", "JCB");
	   $result = count($marcas);
	   echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Marca de la Tarjeta</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdMarcaTarjeta" id="frmAdMarcaTarjeta" action="" method="post">
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span>:</b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['entidad']).'
						 </div>	 
					</div>	
					<div class="da-form-row">
						 <label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span>:</b></label>
						 <div class="da-form-item large">
							 '.base64_decode($_GET['compania']).'
						 </div>	 
					</div>		
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Marca de la Tarjeta</span>:</b></label>
						<div class="da-form-item large">
							<select id="marcatj" class="required" style="width:200px;">';
								echo'<option value="" lang="es">seleccione...</option>';
								for($i=0;$i<$result;$i++){
								  echo'<option value="'.$marcas[$i].'">'.$marcas[$i].'</option>';  
								}
					  echo'</select>
							<span class="errorMessage" id="errormarcatarjeta" lang="es" style="display:none;">seleccione la marca de tarjeta</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
					   <div id="response-loading" class="loading-fac"></div>';
			   echo'</div>
				</form>
			</div>
		</div>';  
}elseif($_GET['opcion']=='crea_tipotarjeta'){
	   $tarjeta = array("Crédito", "Débito");
	   $code = array("TC","TD");
	   $result = count($tarjeta);
	   echo'<div class="da-panel">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<span lang="es">Agregar Tipo de Tarjeta</span>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmAdTipoTarjeta" id="frmAdTipoTarjeta" action="" method="post">
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Tipo de Tarjeta</span>:</b></label>
						<div class="da-form-item large">
							<select id="tipo_tarjeta" class="required" style="width:200px;">';
								echo'<option value="" lang="es">seleccione...</option>';
								for($i=0;$i<$result;$i++){
								  echo'<option value="'.$tarjeta[$i].'|'.$code[$i].'">'.$tarjeta[$i].'</option>';  
								}
					  echo'</select>
							<span class="errorMessage" id="errortipotarjeta_a" lang="es" style="display:none;">seleccione tipo de tarjeta</span>
						</div>
					</div>							
					<div class="da-button-row">
					   <div class="errorMessage" id="errortipotarjeta_b" lang="es" style="display:none;">el tipo de tarjeta ya existe, seleccione otro</div>
					   <input type="submit" value="Guardar" class="da-button green" lang="es"/>
					   <div id="response-loading" class="loading-fac"></div>
					   ';
			   echo'</div>
				</form>
			</div>
		</div>';  
}
?>