// JavaScript Document
function habilitadeshabilita(cont){
 
   if($("#elige_"+cont).is(":checked")){
	 alert('V');
	 $("#valoresperado_"+cont).removeAttr("disabled");
	 /*$("#loader").html("<div align='center'><img src='loading.png'/></div>"); 
		 $.get("habilita_desabilita_foto.php?select=seleccionar&nocache="+nocache+"&idfoto="+dato, 
				 function(data){ 
						 alert(data);
						 if (data != "") { 
						    if(data<=10){
								 $("#contenido_selecciona_foto").html(data);
							}else{
							 $(".child").attr( "disabled" , true ); 
							 alert('Lo sentimos el numero maximo de fotos a elegir es de 10');	
							}
						 } 
						 $('#loader').empty(); 
				 } 
		 );*/ 			
   }else{
	 alert('F');
	 $("#valoresperado_"+cont).attr("disabled", true); 
	 /*$("#loader").html("<div align='center'><img src='loading.png'/></div>"); 
		 $.get("habilita_desabilita_foto.php?select=deseleccionar&nocache="+nocache+"&idfoto="+dato, 
				 function(data){ 
						 alert(data);
						 if (data != "") { 
							if(data<=10){ 	 
								 $(".child").removeAttr( "disabled" ); 
								 $("#contenido_selecciona_foto").html(data);
							}
	
						 } 
						 $('#loader').empty(); 
				 } 
		 );*/ 
   }
   
}

$(function(){
   $("#formpregunta").submit(function(){
	 
	  var idcompania=$("#idcompania option:selected").val();
	  alert('idcompania: '+idcompania);
	  if(idcompania!=''){
		  $.ajax({
			  type: "POST",
			  url: "verifica_compania.php",
			  data: { idcompania: idcompania }, //en este caso utilizamos la forma de enviar los datos
			  success: function(data) {
					  alert(data);
					  if(data==1){
					    $("#error_opcion").html("La compañía no esta disponible, actualmente esta siendo utilizada").hide().fadeIn("slow");
		                return false;
					  }else if(data==0){
						  $("#error_opcion").remove().hide().fadeIn('slow');
					  }
					  //$('#error_elige').html(data);
					  /*if(numtotalreg!=1){
						  if(contreg<numtotalreg){ 
							$("#formulariodeudorcodeudor").css("display", "block").hide().fadeIn("slow");
						  }else{
							  $('#boton_continuar').html("<form action='' id='contact-form' method='post' name='formContratar'><input type='submit' value='Continuar'/><input type='hidden' name='accionEnviar' value='ok'/></form>").hide().fadeIn("slow");
						  }
					  }else{
						 $('#boton_continuar').html("<form action='' id='contact-form' method='post' name='formContratar'><input type='submit' value='Continuar'/><input type='hidden' name='accionEnviar' value='ok'/></form>").hide().fadeIn("slow"); 
					  }*/
					  
			   }
		  });
	  }else{
	    $("#error_opcion").html("elija una compañía").hide().fadeIn("slow");
		return false;
	  }
	  var y = $('input:checkbox:checked').size();
	  alert(y);
	  if(y!=0){
		  var sw=1;
		  ids = $('input[type=checkbox]:checked').map(function() {
				return $(this).attr('id');
		  }).get();
		
		  alert('IDS: ' + ids.join(', '));
		  $.each(ids, function(indice,valor) {
			 alert( "indice" + indice + ": " + valor );
			 var numero_id = valor.split('_');
			 alert(numero_id[1]);
			 var combo=$("#valoresperado_"+numero_id[1]+" option:selected").val();
			 alert(combo);
			 if(combo!=''){
			   sw=1;
			 }else{
			   sw=0;	 
			 }
		  });
		  if(sw==1){
			  return true;
		  }else if(sw==0){
			  $("#error_elige").html("elija los valores esperados").hide().fadeIn("slow");
		  }
	  }else{
		 $("#error_elige").html("porfavor seleccione las preguntas que adicionara a la compañia").hide().fadeIn("slow");
		 return false;
	  }
	  //verificamos la cantidad de chekbox tiqueados
	  /*var y = $('input:checkbox:checked').size();
	  alert(y);
	  var vectorT = [];
	  for(j=1; j<=y; j++){
		    var valor=$("input[name=elige"+j+"]:checked").val(); 
			alert(valor);
			vectorT[j-1]=valor;
	  }
	  /*$.each(vectorT, function(indice,valorT) {
		 alert( "indice" + indice + ": " + valorT );
	  });*/	 
											
      return false;
   });
   
});

function validacompania(i) {
  //alert('Cambio el valor de 30 a ' + dato);
  alert(i);
  //VALIDAMOS TASA COMPAÑIA
  var tasacompania = $("#txtTcompania"+i).val();
  if (tasacompania == "") {
     $("#error_tasa").html("ingrese la(s) tasa(s)").fadeIn("slow");
     $("#txtTcompania"+i).css({'border' : '1px solid #FF0000'}).focus();
     return false;
  }else if(tasacompania.match(/^[0-9\.]+$/)){
	  var tasac=parseFloat(tasacompania);
	  $("#error_tasa").remove().hide().fadeIn('slow');
	  $("#txtTcompania"+i).css({'border' : '1px solid #7F9DB9'});
  }else{
	 $("#error_tasa").html("ingrese la(s) tasa(s)").fadeIn("slow");
     $("#txtTcompania"+i).css({'border' : '1px solid #FF0000'}).focus();
     return false; 
  }
  var tasabanco = $("#txtTbanco"+i).val(); 
  var tasab=parseFloat(tasabanco);
  var suma=tasac+tasab;
  $("#txtTfinal"+i).val(suma).hide().fadeIn("slow");
  alert(suma);
  return true;
  
}

function validabanco(i) {
  //VALIDAMOS BANCO
  alert(i);
  var tasabanco = $("#txtTbanco"+i).val();
  if (tasabanco == "") {
     $("#error_tasa").html("ingrese la(s) tasa(s)").fadeIn("slow");
     $("#txtTbanco"+i).css({'border' : '1px solid #FF0000'}).focus();
     return false;
  }else if(tasabanco.match(/^[0-9\.]+$/)){
	  var tasab=parseFloat(tasabanco);
	  $("#error_tasa").remove().hide().fadeIn('slow');
	  $("#txtTbanco"+i).css({'border' : '1px solid #7F9DB9'});
  }else{
	 $("#error_tasa").html("ingrese la(s) tasa(s)").fadeIn("slow");
     $("#txtTbanco"+i).css({'border' : '1px solid #FF0000'}).focus();
     return false; 
  }
  var tasacompania = $("#txtTcompania"+i).val();
  var tasac=parseFloat(tasacompania);
  var suma=tasac+tasab;
  $("#txtTfinal"+i).val(suma).hide().fadeIn("slow");
  alert(suma);
  return true;
}

$(function(){
	  $(".validatasa").blur(function(e){
			 var valor=$(this).prop('id');
			 var vec=valor.split('-');
			 var id=vec[0];
			 var tipo=vec[1];
			 if(tipo=='txtTcompania'){
			 	 //alert(id);
				 var tasacompania = $('#'+id+'-txtTcompania').prop('value');
				 //alert(tasacompania);
				 if (tasacompania=='') {
				   //alert('hola');
				   e.preventDefault();
				   $("#errortcia"+id).html("ingrese la tasa");
				   $('#'+id+'-txtTcompania').css({'border' : '1px solid #d44d24'}).focus();
				   $("#btnSaveTasas").attr("disabled", true);
				 }else if(tasacompania.match(/^[0-9\.]+$/)){
					 $("#errortcia"+id).hide('slow');
					 //$("#errortcia"+id).show();
	                 $('#'+id+'-txtTcompania').css({'border' : '1px solid #D1D1D1'});
					 
					 var tasabanco = $('#'+id+'-txtTbanco').prop('value');
					 if(tasabanco==''){
						 e.preventDefault();
						 $("#errortban"+id).html("ingrese la tasa");
				         $('#'+id+'-txtTbanco').css({'border' : '1px solid #d44d24'}).focus();
						 $("#btnSaveTasas").attr("disabled", true);
					 }else if(tasabanco.match(/^[0-9\.]+$/)){
						 $("#btnSaveTasas").removeAttr("disabled");
						 $("#errortban"+id).hide('slow');
						 //$("#errortban"+id).show();
	                     $('#'+id+'-txtTbanco').css({'border' : '1px solid #D1D1D1'});
						  var tasac = parseFloat(tasacompania);
						  var tasab = parseFloat(tasabanco);
						  var suma = tasac + tasab;
						   //alert(suma);
						   $('#'+id+'-txtTfinal').prop('value',suma.toFixed(3)).hide().fadeIn("slow");
					 }else{
						 e.preventDefault();
						 $("#errortban"+id).html("ingrese solo numeros");
				         $('#'+id+'-txtTbanco').css({'border' : '1px solid #d44d24'}).focus();
						 $("#btnSaveTasas").attr("disabled", true);
					 } 
				 }else{
					e.preventDefault();  
					$("#errortcia"+id).html("ingrese solo numeros");
				    $('#'+id+'-txtTcompania').css({'border' : '1px solid #d44d24'}).focus();
					$("#btnSaveTasas").attr("disabled", true);
				 }
				 
			 }else if(tipo=='txtTbanco'){
				 //alert(id);
				 var tasabanco = $('#'+id+'-txtTbanco').prop('value');
				 //alert(tasabanco);
				 if(tasabanco==''){
					 e.preventDefault();
					 $("#errortban"+id).html("ingrese la tasa");
				     $('#'+id+'-txtTbanco').css({'border' : '1px solid #d44d24'}).focus();
					 $("#btnSaveTasas").attr("disabled", true);
				 }else if(tasabanco.match(/^[0-9\.]+$/)){ 
					 $("#errortban"+id).hide('slow');
					 //$("#errortban"+id).show();
	                 $('#'+id+'-txtTbanco').css({'border' : '1px solid #D1D1D1'});
					 
					 var tasacompania = $('#'+id+'-txtTcompania').prop('value');
					 if (tasacompania=='') {
					   e.preventDefault();
					   $("#errortcia"+id).html("ingrese la tasa");
					   $('#'+id+'-txtTcompania').css({'border' : '1px solid #d44d24'}).focus();
					   $("#btnSaveTasas").attr("disabled", true);
					 }else if(tasacompania.match(/^[0-9\.]+$/)){
						  $("#btnSaveTasas").removeAttr("disabled");
						  $("#errortcia"+id).hide('slow');
						  //$("#errortcia"+id).show();
	                      $('#'+id+'-txtTcompania').css({'border' : '1px solid #D1D1D1'});
						  var tasac = parseFloat(tasacompania);
						  var tasab = parseFloat(tasabanco);
						  var suma = tasac + tasab;
						   //alert(suma);
						   $('#'+id+'-txtTfinal').prop('value',suma.toFixed(3)).hide().fadeIn("slow");
					 }else{
						e.preventDefault();
						$("#errortcia"+id).html("ingrese solo numeros");
					    $('#'+id+'-txtTcompania').css({'border' : '1px solid #d44d24'}).focus();
						$("#btnSaveTasas").attr("disabled", true); 
					 }
				 }else{
				     e.preventDefault();
					 $("#errortban"+id).html("ingrese solo numeros");
				     $('#'+id+'-txtTbanco').css({'border' : '1px solid #d44d24'}).focus();
					 $("#btnSaveTasas").attr("disabled", true);
				 }
			 }
			
	  });
	  /*
	  $("#frmTasas").submit(function(e){
		  var num_prod=$('#num_prod').prop('value');
		  var i=1;
		  while(i<=3){
			  
		  }
		  alert(num_prod);
		  e.preventDefault();
		  e.stopPropagation();
	  });
	  */
 });		

/*---------SUMAR TASAS---------

$(function(){
	 var idnum=$("#idnum").val();
	 alert(idnum);
	 $("#txtTcompania"+idnum).blur(function(event){
		 var tasacompania = $('#txtTcompania'+idnum).val();
		 alert(tasacompania);
     });											 

});--*/

//USUARIOS
/*
function verificarusuario(){
     //alert('ingresa');
	 var idusuario=$("#txtIdusuario").val();
	 //alert(idusuario);
	 var dataString = 'idusuario='+ idusuario ;
	 //ejecutando ajax
	 $.ajax({
		   type: "POST",
		   url: "buscar_idusuario.php",
		   data: dataString,
		   success: function(data) {
			      //alert(data);
				  var results = data.split("|");
				  if(results[0]==1){
					  $("#error_usuario").remove().hide().fadeIn('slow');
	                  $("#txtIdusuario").css({'border' : '1px solid #7F9DB9'});
					  $("#btnUsuario").removeAttr("disabled");
					  return true;
				  }else if(results[0]==2){
					  $("#ver_msg").append("<div id='ver_msg'><div class='error2' id='error_usuario'></div></div>");
					  $("#error_usuario").html("El usuario: "+results[1]+" ya existe ingrese otro").fadeIn("slow");
					  $("#txtIdusuario").css({'border' : '1px solid #FF0000'}).focus();
					  $("#btnUsuario").attr("disabled", true);
					  return false;
				  }
				 
		   }
	  });
}*/


//FUNCION QUE PERMITE ELIMINAR EL CORREO
function elimina_correo(idcorreo){
  //alert(idcorreo);
  var dataString = 'idcorreo='+ idcorreo +"&operacion=eliminar";
  //alert (dataString);
  //ejecutando ajax
  $.ajax({
	   type: "GET",
	   url: "crea_correo_electronico.php",
	   data: dataString,
	   success: function(datareturn) {
			  //alert(datareturn);
			  //if(datareturn==1){
			  $("#mensage_elimina").html("Se elimino correctanmente el correo electronico");
			  window.setTimeout('location.reload()', 1250); 
			  //}else{
				//  return false;
			  //}
	   }
  });
}
//FUNCION QUE PERMITE ELIMINAR EL CORREO AUTOMATICO
function elimina_correo_automatico(idcorreo){
  //alert(idcorreo);
  var dataString = 'idcorreo='+ idcorreo +"&operacion=eliminar";
  //alert (dataString);
  //ejecutando ajax
  $.ajax({
	   type: "GET",
	   url: "crea_correo_electronico_automatico.php",
	   data: dataString,
	   success: function(datareturn) {
			  //alert(datareturn);
			  //if(datareturn==1){
			  $("#mensage_elimina").html("Se elimino correctanmente el correo electronico");
			  window.setTimeout('location.reload()', 1250); 
			  //}else{
				//  return false;
			  //}
	   }
  });
}

/*REALIZAMOS LOS PROCESOS PARA HACER LOS CAMBIOS DE CONTRASEÑAS*/
$(function(){
	/*VERIFICAMOS SI EXISTE EL USUARIO*/ 	    
    $("#txtIdusuario").blur(function(e){
			 //alert("This input field has lost its focus.");
			 //alert('ingresa');
			 var usuario = $("#txtIdusuario").prop('value');
			 var variable = $('#txtTipousuario option:selected').prop('value');
			 var vec = variable.split('|');
			 var tipousuario = vec[1];
			 if(tipousuario!='FAC'){
			    var identidadf = $('#identidadf option:selected').prop('value');
			 }else{
			    var identidadf = $('#idmultiple option:selected').prop('value');	 
			 }
			 //alert(usuario);
			 if(usuario!=''){
			 	 var dataString = 'usuario='+ usuario +'&id_ef='+ identidadf+ '&tipousuario='+ tipousuario;
				 //alert(dataString);
				 $.ajax({
					   async:true,
					   cache:false,
					   type: "POST",
					   url: "buscar_idusuario.php",
					   data: dataString,
					   success: function(data) {
							  //alert(data);
							  var results = data.split("|");
							  if(results[0]==1){
								  //$("#error_usuario").remove().hide().fadeIn('slow');
								  $("#ver_msg").html("<div id='ver_msg'><div class='errorMessage' id='error_usuario'></div></div>");
								  $("#txtIdusuario").css({'border' : '1px solid #7F9DB9'});
								  $("#btnUsuario").removeAttr("disabled");
								  return true;
							  }else if(results[0]==2){
								  $("#ver_msg").html("<div id='ver_msg'><div class='errorMessage' id='error_usuario'></div></div>");
								  $("#error_usuario").html("El usuario: "+results[1]+" ya existe ingrese otro usuario").fadeIn("slow");
								  $("#txtIdusuario").css({'border' : '1px solid #d44d24'}).focus();
								  $("#btnUsuario").attr("disabled", true);
								  e.stopPropagation();
							  }
							 
					   }
				  });
			 }else{
				   $("#error_usuario").html("Ingrese nombre de usuario").fadeIn("slow");
				   $("#txtIdusuario").css({'border' : '1px solid #d44d24'}).focus();
				   $("#btnUsuario").attr("disabled", true);
				   e.stopPropagation();
			 }
	  });
	
	//VERIFICAMOS SI EL PASWORD NUEVO INGRESADO EXISTE EN LA DB
	  $('input[name="txtPassNuevo"]').blur(function(e){
			 var nuevo_pass = $('input[name="txtPassNuevo"]').val();	
             var idusuario = $("#idusuario").val(); 
			 //alert(nuevo_pass);
			 //alert(idusuario);
			 if(nuevo_pass!=''){
				 var dataString = 'nuevo_pass='+ nuevo_pass +'&idusuario='+ idusuario;
			    //ejecutando ajax
			 	$.ajax({
				   type: "POST",
				   url: "buscar_password.php",
				   data: dataString,
				   success: function(data) {
					      //alert(data); 
						  if(data==1){
							  $("#erro_pass_nuevo").html("La nueva contrase&ntilde;a es igual a la actual").fadeIn("slow");
							  $("#txtPassword").css({'border' : '1px solid #d44d24'}).focus();
							  $('#txtPassRepite').attr("disabled",true);
							  e.preventDefault();
						  }else if(data==2){							  
							  $("#erro_pass_nuevo").remove().hide().fadeIn('slow');
							  $("#txtPassword").css({'border' : '1px solid #B2B09B'});
							  $("#ver_msg2").html('<div id="ver_msg2"><label class="errorMessage" id="erro_pass_nuevo"></label></div>');
							  $("#txtPassRepite").removeAttr("disabled");
							  e.preventDefault();
							  //$("#btn_cambiar_pass").removeAttr("disabled");
							  //return true;
						  } 
				   }
			 });
			 }else{
				  $("#erro_pass_nuevo").html("Ingrese una contrase&ntilde;a").fadeIn("slow");
				  $("#txtPassword").css({'border' : '1px solid #d44d24'}).focus();
				  $('#txtPassRepite').attr("disabled",true);
				  e.preventDefault();
			 }
	  });
	
	//CONFIRMAR CONTRASENIA
	$('#txtPassword2').blur(function(e){
		   var password_repite = $("#txtPassword2").val();							 
		   var password_nuevo = $("#txtPassword").val();
		   //alert(password_repite);
		   //alert(password_nuevo);
		   if(password_nuevo==password_repite){
			   $("#btnUsuario").removeAttr("disabled");
			   $("#txtPassword2").css({'border' : '1px solid #D1D1D1'});
			   $("#ver_msg2").html('<div id="ver_msg2"><div class="errorMessage" id="error_contrasenia_igual"></div></div>');
		   }else{
			   $("#error_contrasenia_igual").html("Las contrase&ntilde;as tienen que ser iguales");	
			   $("#txtPassword2").css({'border' : '1px solid #d44d24'});
			   $("#btnUsuario").attr("disabled", true);
			   //e.preventDefault();  
		   }
		   
	});
	
	
	//VERIFICACION PASSWORD ACTUAL
	$('#txtPassActual').blur(function(e){
		 var password_actual=$("#txtPassActual").val();
		 var idusuario=$("#idusuario").val();
		 //alert(password_actual);
		 //alert(idusuario);
		 //ejecutando ajax
		 if(password_actual!=''){
		 	var dataString = 'password_actual='+ password_actual +'&dato=1&idusuario='+ idusuario ;
			$.ajax({
			   async:true,
			   cache:false,
			   type: "POST",
			   url: "operaciones_contrasenia.php",
			   data: dataString,
			   success: function(data) {
					  //alert(data);
					  if(data==1){
						  $("#ver_msg1").html('<div id="ver_msg1"><div class="bienMessage" id="erro_pass_actual">contrase&ntilde;a actual correcta</div></div>');
						  $('input[name="txtPassNuevo"]').removeAttr("disabled");
						  //$("#btn_cancelar_pass").removeAttr("disabled");
						  setTimeout(function() {
							  // Do something after 2 seconds
							 $("#ver_msg1").html('<div id="ver_msg1"><div class="errorMessage" id="erro_pass_actual"></div></div>');
						  }, 3000);
						  
					  }else if(data==2){
						  $("#erro_pass_actual").html("contrase&ntilde;a actual incorrecta");
						  $("#txtPassActual").focus();
						  $('input[name="txtPassNuevo"]').attr("disabled", true);
						  e.preventDefault();
					  }
					  
			   }
		    });
		 }else{
			  $("#erro_pass_actual").html("ingrese la contrase&ntilde;a actual");
			  $("#txtPassActual").focus();
			  $('input[name="txtPassNuevo"]').attr("disabled", true);
			  e.preventDefault();
		 }
		 //e.preventDefault();
	});
    
    //VERIFIACION NUEVO PASSWORD
    $('#txtPassNuevo').blur(function(e){
		   var password_nuevo = $("#txtPassNuevo").val();
		   var password_repetir = $("#txtPassRepite").val();
		   var cantidad = password_nuevo.length;
		   if(cantidad>=6){
			     $("#ver_msg2").html("<div id='ver_msg2'><div class='error2' id='erro_pass_nuevo'></div></div>");
				 if(password_nuevo==password_repetir){
					 $("#btn_cambiar_pass").removeAttr("disabled");
					 //$("#ver_msg3").html("<div id='ver_msg3'><div class='error2' id='erro_pass_repetir'></div></div>");
					 $("#ver_msg3").html("<div id='ver_msg3'><div class='msgbien'>contrase&ntilde;as iguales</div></div>");
				 }else{
				     $("#erro_pass_repetir").html("Las contrase&ntilde;as tienen que ser iguales");	
					 $("#btn_cambiar_pass").attr("disabled", true);
				 }
		   }else{
			     $("#erro_pass_nuevo").html("ingrese contrase&ntilde;a m&iacute;nimo 6 caracteres");
			     //$("#txtPassNuevo").focus();
			     $("#btn_cambiar_pass").attr("disabled", true);
			     //$("#btn_cancelar_pass").attr("disabled", true); 
		   }
		   e.preventDefault();
		   e.stopPropagation();
    });
	
	
	//VERIFICAR NUEVO PASSWORD REPETIR
	$('#txtPassRepite').blur(function(e){
		   var password_repite = $("#txtPassRepite").val();							 
		   var password_nuevo = $('input[name="txtPassNuevo"]').val();
		   //alert(password_nuevo);
		   //alert(password_repite);
		   if(password_nuevo==password_repite){
			   $("#btn_cambiar_pass").removeAttr("disabled");
			   //$("#ver_msg3").html("<div id='ver_msg3'><div class='error2' id='erro_pass_repetir'></div></div>");
			   $("#ver_msg3").html('<div id="ver_msg3"><div class="bienMessage">contrase&ntilde;as iguales</div></div>');
			   $("#txtPassRepite").css({'border' : '1px solid #B2B09B'})
			   setTimeout(function() {
				   // Do something after 2 seconds
				   $("#ver_msg3").html('<div id="ver_msg3"><div class="errorMessage" id="error_contrasenia_igual"></div></div>');
			   }, 3000);
		   }else{
			   $("#error_contrasenia_igual").html("Las contrase&ntilde;as tienen que ser iguales");	
			   $("#txtPassRepite").css({'border' : '1px solid #d44d24'}).focus();
			   e.preventDefault();
			   //$("#btn_cambiar_pass").attr("disabled", true);
		   }
		   
		   //e.preventDefault();
		   //e.stopPropagation();
	});
	
	
	//SELECCIONAR EL TIPO DE USUARIO
	$("#txtTipousuario").change(function() {
		var variable = $(this).val();
		var vec = variable.split('|');
		var tipousuario = vec[1];
		if(tipousuario!=''){
			 if(tipousuario=='ADM'){//administrador
				   $(".child").attr('checked', false);
				   //deshabilitados
				   //$('input[name="cbOfic"], input[name="cbImpl"], input[name="cbFacu"]').attr( "disabled" , true);
				   //habilitados
				   $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"], input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbCreaU"], input[name="cbTipc"], input[name="cbTh"]').attr( "disabled" , false);
			 }else if(tipousuario=='OPR'){//operador
			       $(".child").attr('checked', false);
	               //deshabilitados
				   $('input[name="cbCreaU"], input[name="cbCia"],  input[name="cbDes"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"], input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr( "disabled" , true );
				   //habilitados
				   $('input[name="cbIni"], input[name="cbFor"]').attr( "disabled" , false);
		     }else if(tipousuario=='FAC'){//facultativo
				   $(".child").attr('checked', false);
				   //deshabilitados 
				   $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"], input[name="cbCreaU"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"], input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr( "disabled" , true );
				   //habilitados
				   //$('input[name="cbFacu"]').attr( "disabled" , false);
			 }else if(tipousuario=='LOG'){//logueado
				  $(".child").attr('checked', false);
				  //deshabilitados
				  $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"],  input[name="cbCreaU"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"], input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr("disabled",true);
				  //habilitados
				  //$('input[name="cbOfic"]').attr( "disabled" , false);
			 }else if(tipousuario=='CRU'){//crearusuario
				  $(".child").attr('checked', false);
				  //deshabilitados 
				  $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"],  input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"],  input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr( "disabled" , true );
				  
				  //habilitados
				  $('input[name="cbCreaU"]').attr( "disabled" , false);
				  $('input[name="cbCreaU"]').attr('checked', true);
			 }else if(tipousuario=='IMP'){//logueado
				  $(".child").attr('checked', false);
				  //deshabilitados
				  $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"],  input[name="cbCreaU"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"],  input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr("disabled",true);
				  //habilitados
				  //$('input[name="cbImpl"]').attr( "disabled" , false);
			 }else if(tipousuario=='REP'){
				  $(".child").attr('checked', false);
				  //deshabilitados
				  $('input[name="cbIni"], input[name="cbFor"], input[name="cbCia"],  input[name="cbDes"],  input[name="cbCreaU"], input[name="cbPol"], input[name="cbEmail"], input[name="cbOcupa"], input[name="cbAgen"],  input[name="cbAuto"], input[name="cbTrd"], input[name="cbTrem"], input[name="cbForpag"], input[name="cbEstado"], input[name="cbCertMed"], input[name="cbDepSuc"], input[name="cbTipc"], input[name="cbTh"]').attr("disabled",true);
			 }
	    }else{
			 $(".child").attr( "disabled" , false);
	    }
		/*$('input[type=checkbox]').each( function() {			
			if($("input[name=cbHom]:checked").length == 1){
				this.checked = true;
			} else {
				this.checked = false;
			}
		});*/
		//if($("input[name=cbHom], input[name=cbHom]").is(':checked')) {  
          //  alert("Está activado");
			
        //} else {  
            //alert("No está activado");  
        //}
		/*if($("input[name=cbCom]").is(':checked')) {  
            alert("Está activado");
			$("input[name=cbCom]").attr('checked', false); 
        } else {  
            alert("No está activado");  
        }*/
		//$(".child").attr( "disabled" , true );
		
	
	});
	
});
/*-------------------VISUALIZAR FECHA---------------------------*/
function ver_fecha(){
 
   if($("#fecha").is(":checked")){
	 //alert('V');
	 var id_fecha=$('#fecha:checked').val();
	 //alert(id_fecha);
		 if(id_fecha==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?fec="+id_fecha+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#date').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }			
   }else{
   				$('#date').html("").hide().fadeIn('slow');
   }
}

/*-------------------VISUALIZAR CLIENTE NOMBRE---------------------------*/
function ver_cliente_nombre(){
    if($("#clienten").is(":checked")){
	 //alert('V');
	 var id_clienten=$('#clienten:checked').val();
	 //alert(id_clienten);
		if(id_clienten==""){
		//		 alert('elija algo');
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?cliente="+id_clienten+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#name').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	}else{
			$('#name').html("").hide().fadeIn('slow');
	}
}

/*-------------------VISUALIZAR CLIENTE CI---------------------------*/
function ver_cliente_ci(){
    if($("#clientec").is(":checked")){
	 //alert('V');
	 var id_clientec=$('#clientec:checked').val();
	 //alert(id_clientec);
		if(id_clientec==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?cliente="+id_clientec+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#ci').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	 }else{
		 $('#ci').html("").hide().fadeIn('slow');
	 }
   
}

/*-------------------VISUALIZAR AGENCIA---------------------------*/
function ver_agencia(){
    if($("#agencia").is(":checked")){
	 //alert('V');
	 var id_agencia=$('#agencia:checked').val();
	 //alert(id_clientec);
		if(id_agencia==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?agencia="+id_agencia+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#agen').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	 }else{
	 		$('#agen').html("").hide().fadeIn('slow');
	 }
   
}

/*-------------------VISUALIZAR POLIZA AUTO---------------------------*/
function ver_poliza_au(){
    if($("#polau").is(":checked")){
	 //alert('V');
	 var id_polau=$('#polau:checked').val();
	 //alert(id_clientec);
		if(id_polau==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?poliza="+id_polau+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#poliza_au').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	 }else{
	 	$('#poliza_au').html("").hide().fadeIn('slow');
	 }
   
}

/*-------------------VISUALIZAR POLIZA TR---------------------------*/
function ver_poliza_tr(){
    if($("#poltr").is(":checked")){
	 //alert('V');
	 var id_poltr=$('#poltr:checked').val();
	 //alert(id_clientec);
		if(id_poltr==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?poliza="+id_poltr+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#poliza_tr').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	 }else{
		$('#poliza_tr').html("").hide().fadeIn('slow');	 
	 }
   
}

/*-------------------VISUALIZAR POLIZA DE---------------------------*/
function ver_poliza_de(){
    if($("#polde").is(":checked")){
	 //alert('V');
	 var id_polde=$('#polde:checked').val();
	 //alert(id_polde);
		if(id_polde==""){
		//		 alert('elija algo');
				 
		//         alert('hola 1');
		 }else{
			//alert('entreeeee'); 
			 $.get("rep_fecha.php?poliza="+id_polde+"&nocache="+new Date().getTime(),   
				  function(data){ 
					   if(data != "") { 
							 //$('#loading_datoselect').empty();
							 $('#poliza_de').html(data).hide().fadeIn('slow');
					   } 
					   //$('#loading').empty(); 
				   } 																							    
			  );
		 }
	 }else{
			$('#poliza_de').html("").hide().fadeIn('slow'); 
	 }
}