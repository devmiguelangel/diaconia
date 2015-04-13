<script type="text/javascript">
 $(document).ready(function(){
     //función que observa los cambios del campo file y obtiene información
	  /*$("input:file").change(function(){
			alert('ingresa');
			//obtenemos un array con los datos del archivo
			var file = $("#update")[0].files[0];
			alert(file);
			//obtenemos el nombre del archivo
			var fileName = file.name;
			//obtenemos la extensión del archivo
			var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
			//obtenemos el tamaño del archivo
			var fileSize = file.size;
			//obtenemos el tipo de archivo image/png ejemplo
			var fileType = file.type;
			//mensaje con la información del archivo
			$("#mesagge").html("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.  Extension:"+fileExtension+" Tipo archivo:"+fileType+"</span>");
	  });*/
	 //VALIDAR LOS CAMPOS DEL FORMULARIO
	 $("#formUpdateImage").submit(function(e){
		  //alert('hola');
		  var data_send = new FormData();
		  alert(data_send);
		  //información del formulario
          //var formData = new FormData($("#formUpdateImag")[0]);
		  //alert(formData);
		  /*
		  var val_auto = $("#txtValorAuto").val();
		  var val_inmueble = $("#txtValorInmueble").val();
		  var val_total_garantia = $("#txtSumaGarantia").val();
		  var plazocredito = $("#txtPlazoCredito").val();
		  var selectTipo = $("#idtipoplazocredito option:selected").val();
		  var sum=0;
		  		  
		  $(this).find('.required').each(function(){
				  //VALIDAR VEHICULO 								  
				  if (val_auto == ''){
					sum++;   
					$("#erro1").html("ingrese valor del vehiculo").fadeIn("slow");
					$("#txtValorAuto").focus();
					g.preventDefault();
				  }else if(val_auto.match(/^[0-9]+$/)){
				 
				  }else{
					 sum++;
					 $("#erro1").html("ingrese solo numeros enteros").fadeIn("slow");
					 $("#txtValorAuto").focus();
					 g.preventDefault();
				  }
				  //VALIDAR INMUEBLE
				  if(val_inmueble == ''){
					 sum++;
					 $("#erro2").html("ingrese valor del inmueble").fadeIn("slow");
					 $("#txtValorInmueble").focus();
					 g.preventDefault(); 
				  }else if(val_inmueble.match(/^[0-9]+$/)){
				 
				  }else{
					 sum++;
					 $("#erro2").html("ingrese solo numeros enteros").fadeIn("slow");
					 $("#txtValorInmueble").focus();
					 g.preventDefault(); 
				  }
				  //VALIDAR GARANTIA TOTAL
				  if(val_total_garantia == ''){
					  sum++;   
					  $("#erro3").html("el valor total no debe ser vacio").fadeIn("slow");
					  $("#txtSumaGarantia").focus();
					  g.preventDefault();
				  }else{
					  $("#erro3").remove().hide().fadeIn('slow');
				      $("#ver_msg3").html('<div id="erro3" class="error2"></div>');
				  }
				  //VALIDAR TIPO DE CREDITO
				  if(selectTipo == ""){
					  sum++;
					  $("#erro5").html("seleccione una opcion").fadeIn("slow");
					  $("#idtipoplazocredito").focus();
					  g.preventDefault();
				  }else{
					  $("#erro5").remove().hide().fadeIn('slow');
				      $("#ver_msg5").html('<div id="erro5" class="error2"></div>');
				  }
				  //VALIDAR PLAZO DE CREDITO
				  if(plazocredito == ''){
					  sum++;   
					  $("#erro4").html("ingrese plazo de cr"+'\u00e9'+"dito").fadeIn("slow");
					  $("#txtPlazoCredito").focus();
					  g.preventDefault();
				  }else if(plazocredito.match(/^[0-9]+$/)){
					   $("#erro4").remove().hide().fadeIn('slow');
				       $("#ver_msg4").html('<div id="erro4" class="error2"></div>');
				  }else{
					  sum++;   
					  $("#erro4").html("ingrese solo numeros enteros").fadeIn("slow");
					  $("#txtPlazoCredito").focus();
					  g.preventDefault(); 
				  }
			  
		  });
		  //alert(sum);
		  
		  if(sum==0){
			 //alert(selectTipo);
			 //VERIFICAMOS DATOS ERRONEOS INGRESADOS
			 //PLAZO DE CREDITO
			 if(selectTipo==1){
				   if(plazocredito<=20){
					   //PROCEDEMOS A GUARDAR 
					   $('#btnGuardarForm').attr('disabled', true); 
					   $('#en_espera').html("<img src='images/loader.gif' width='24' height='24'/> La informaci"+'\u00f3'+"n esta siendo guardada espere porfavor...");
				   }else{
					   $("#erro4").html("el plazo de cr"+'\u00e9'+"dito no debe superar los 20 a"+'\u00f1'+"os").fadeIn("slow");
					   $("#txtPlazoCredito").focus();
					   g.preventDefault();
				   }
		     }else if(selectTipo==2){
				   if(plazocredito<=240){
					   //PROCEDEMOS A GUARDAR 
					   $('#btnGuardarForm').attr('disabled', true); 
					   $('#en_espera').html("La informaci"+'\u00f3'+"n esta siendo guardada espere porfavor...");
				   }else{
					   $("#erro4").html("el plazo de cr"+'\u00e9'+"dito no debe superar los 240 meses").fadeIn("slow");
					   $("#txtPlazoCredito").focus();
					   g.preventDefault();
				   } 
		     }
			 
		 }*/
		 		  
		 e.preventDefault();
         // g.stopPropagation();								 
	 });
 });
</script>
<?php
  //echo base64_decode($_GET['id_slider']);
  echo'<div class="grid_5">
		  <div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Actualizar imagen
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
					  
					  <div class="da-form-row" style="text-align:center;">
						 <img src="gallery/11.jpg" width="150" height="150"/>
					  </div>
					  <div class="da-form-row">
						  <label>Imagen</label>
						  
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 500KB. Se recomienda que la imagen tenga un ancho de 1000px por un alto de 400px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="update"/><br/>
							  <span>gets automatically styled</span> 
						  </div>
					  </div>
					
					  <div class="da-form-row">
						  <div id="mesagge"></div>
					  </div>
					  
					  <div class="da-button-row">  
						  <input type="submit" value="Guardar" class="da-button green" />
					  </div>
				  </form>
			  </div>
		  </div>
	  </div>';
?>