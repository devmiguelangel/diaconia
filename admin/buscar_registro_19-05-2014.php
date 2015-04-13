<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  if($_POST['opcion']=='buscar_agencia'){
	  $select="select
				id_agencia,
				codigo,
				agencia
			  from
				s_agencia
			  where
				id_depto=".$_POST['id_departamento']." and id_ef='".$_POST['identidadf']."'
			  order by 
				id_agencia asc;";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $num_regi = $res->num_rows;
	  if($_POST['required']=='f'){
		  if($num_regi>0){$var='';}else{$var='<option value="">Ninguno</option>';}
		  echo'<select name="id_agencia" id="id_agencia" style="width:200px; font-size:12px;">';
				  echo $var;
				  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
					 echo'<option value="'.$regi['id_agencia'].'">'.$regi['agencia'].'</option>';
				  }
		  echo'</select>';  	  
	  }elseif($_POST['required']=='v'){
		  if($num_regi>0){  
			  ?>
			  <script type="text/javascript">
				 $(document).ready(function(){
					$("#btnUsuario").removeAttr("disabled");						
				 });
			  </script>
			 <?php
			  echo'<select name="id_agencia" id="id_agencia" style="width:200px; font-size:12px;">';
					  echo $var;
					  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						 echo'<option value="'.$regi['id_agencia'].'">'.$regi['agencia'].'</option>';
					  }
			  echo'</select>'; 
		  }else{
			   ?>
                 <script type="text/javascript">
				   $(document).ready(function(){
					  $("#btnUsuario").attr("disabled", true);						
				   });
				</script>
               <?php
			   if($_POST['tipo_sesion']=='ROOT'){ 
			  echo'<span style="color:#d64d24;">Se evidencio que existe un implante para esta Entidad Financiera, el departamento elegido no tiene agencias por favor ingrese nuevas agencias</span><br/>
			  <a href="?l=agencia&var=age">Agregar agencias</a>';
			   }else{
				    $selectBs="select
								  count(su.id_usuario) as num_regi
								from
								  s_usuario as su
								  inner join s_usuario_permiso as up on (up.id_usuario=su.id_usuario)
								  inner join s_usuario_tipo as ut on (ut.id_tipo=su.id_tipo)
								where
								  su.id_usuario='".$_POST['id_usuario_sesion']."' and ut.codigo='".$_POST['tipo_sesion']."' and up.pagina='agencia';";
					$res = $conexion->query($selectBs,MYSQLI_STORE_RESULT);
					$regi = $res->fetch_array(MYSQLI_ASSOC);
					if($regi['num_regi']>0){
						echo'<span style="color:#d64d24;">Se evidencio que existe uno o mas implantes para esta Entidad Financiera, el departamento elegido no tiene agencias por favor ingrese nuevas agencias</span><br/>
			  <a href="?l=agencia&var=age">Agregar agencias</a>';
					}else{
						echo'<span style="color:#d64d24;">Se evidencio que existe uno o mas implantes para esta Entidad Financiera, el departamento elegido no tiene agencias.</span> <br/><b>Nota:</b> Consulte con su administrador';
					}			   
			   }
		  }
	  }
  }if($_POST['opcion']=='buscar_agencia_multiple'){
	  $select="select
				id_agencia,
				codigo,
				agencia
			  from
				s_agencia
			  where
				id_depto=".$_POST['id_departamento']." and id_ef='".$_POST['identidadf']."'
			  order by 
				id_agencia asc;";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $numreg = $res->num_rows;
	  if($numreg>0){
	 		 ?>
			 <script type="text/javascript">
				 $(document).ready(function(){
					$("#btnUsuario").removeAttr("disabled");						
				 });
			  </script>
			 <?php
             echo'<select name="idmultiple_agency[]" id="idmultiple_agency" class="requerid" style="width:250px;" size="5" multiple="multiple">';
			  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
				 echo'<option value="'.$regi['id_agencia'].'">'.$regi['agencia'].'</option>';
			  }
	  echo'</select>';
	  echo'<span class="errorMessage" id="errormultiagency"></span>';
	  }else{
		echo'<span class="errorMessage">no existe agencias actuales, ingrese nuevas agencias en el departamento seleccionado de la Entidad Financiera</span>';
		?>
          <script type="text/javascript">
             $(document).ready(function(){
				$("#btnUsuario").attr("disabled", true);						
			 });
          </script>
        <?php
	  }
  }elseif($_POST['opcion']=='buscar_entidad'){
  ?>
    <script type="text/javascript">
       $(document).ready(function() {
           $('#idmultiple').change(function(){
			  var id_entidad=$(this).prop('value');
			  var usuario=$('#txtIdusuario').prop('value');
			  var variable = $('#txtTipousuario option:selected').prop('value');
			  var vec = variable.split('|');
			  var tipousuario = vec[1];
			  if(id_entidad!=''){
				   $("#txtIdusuario").removeAttr("disabled");
				   $("#departamento").removeAttr("disabled");
				   var dataString = 'usuario='+ usuario +'&id_ef='+ id_entidad +'&tipousuario='+ tipousuario;
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
									$("#error_usuario").html("El usuario: "+results[1]+" ya existe ingrese otro usuario o seleccione otra entidad financiera").fadeIn("slow");
									$("#txtIdusuario").css({'border' : '1px solid #d44d24'});
									$("#btnUsuario").attr("disabled", true);
									//e.stopPropagation();
								}
							   
						 }
					});
			  }
		   });
		   
		   //VERIFICAMOS SELECT IDENTIDAD
		   $('#identidadf').change(function(){
			   var id_ef=$(this).prop('value');
			   //alert(id_ef);
			   var variable = $('#txtTipousuario option:selected').prop('value');
			   var vec = variable.split('|');
			   var tipousuario = vec[1];
			   var usuario=$('#txtIdusuario').prop('value');
			   if(id_ef!=''){
				    $('#departamento option[value=""]').prop('selected',true);
					$('#content-agency').fadeOut('slow');
					$("#txtIdusuario").removeAttr("disabled");
					if(tipousuario!='IMP'){
					  $("#departamento").removeAttr("disabled");
					}else{
					  $("#regional").removeAttr("disabled");
					  var identidadf=$('#identidadf option:selected').prop('value');
					  $('#content-regional').fadeIn('slow');
					  visualizar_regional(identidadf); 	
					}
					if(usuario!=''){
						 var dataString = 'usuario='+ usuario +'&id_ef='+ id_ef +'&tipousuario='+ tipousuario;
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
										  $("#error_usuario").html("El usuario: "+results[1]+" ya existe ingrese otro usuario o seleccione otra entidad financiera").fadeIn("slow");
										  $("#txtIdusuario").css({'border' : '1px solid #d44d24'});
										  $("#btnUsuario").attr("disabled", true);
										  //e.stopPropagation();
									  }
									 
							   }
						  });
				    }else{
						//alert(tipousuario);
						if(tipousuario=='ADM'){
							$('#DES').fadeOut('slow'); $('#AUT').fadeOut('slow');
							$('#TRD').fadeOut('slow'); $('#TREM').fadeOut('slow');
							$('#TH').fadeOut('slow');
							 var dataString = 'idef='+id_ef+'&opcion=busca_producto_entidad';
							 $.ajax({
								   async: true,
								   cache: false,
								   type: "POST",
								   url: "buscar_registro.php",
								   data: dataString,
								   dataType:"json",
								   success: function(datavec) {
										$.each(datavec, function( index, value ) {
										     //alert(value);
											 if(value=='DE'){
												$('#DES').fadeIn('slow');
											 }else if(value=='AU'){
												$('#AUT').fadeIn('slow');
											 }else if(value=='TRD'){
												$('#TRD').fadeIn('slow'); 
											 }else if(value=='TRM'){
												$('#TREM').fadeIn('slow');  
											 }else if(value=='TH'){
												$('#TH').fadeIn('slow'); 
											 }
										});    
								   }
							 });
						}
					}
			   }else{
				    $("#txtIdusuario").attr("disabled", true);
					$('#content-agency').fadeOut('slow');
					$('#departamento option[value=""]').prop('selected',true); 
					if(tipousuario!='IMP'){
					    $("#departamento").attr("disabled", true); 
					}else{
						$("#regional").attr("disabled", true);
				    }
			   }   
		   });
		   
		   //VISUALIZAR REGIONAL
		   function visualizar_regional(identidadf){
			   var dataString = 'identidadf='+identidadf+'&opcion=buscar_regional';
			   //alert(dataString);
			   $.ajax({
					 async: true,
					 cache: false,
					 type: "POST",
					 url: "buscar_registro.php",
					 data: dataString,
					 success: function(datareturn) {
						  //alert(datareturn);
						  $('#response-regional').html(datareturn);
							
					 }
			   });   
		   }
		   
       });
    </script>
  <?php		  
		  
		  $selectEF="select
						id_ef,
						nombre,
						codigo,
						activado
					  from
						s_entidad_financiera
					  where
						activado=1;";
		  $resef = $conexion->query($selectEF,MYSQLI_STORE_RESULT);
		  if($_POST['tipousuario']!='FAC'){
			  echo'<select name="identidadf" id="identidadf" class="requerid" style="width:200px;">';
					echo'<option value="">Seleccionar...</option>';
					while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['id_ef'].'">'.$regief['nombre'].'</option>';  
					}
			  echo'</select>';
		  }else{
			  echo'<select name="idmultiple[]" id="idmultiple" class="requerid" style="width:200px;" size="5" multiple="multiple">';
					while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['id_ef'].'">'.$regief['nombre'].'</option>';  
					}
			  echo'</select>';
		  }
		  echo'<span class="errorMessage" id="erroref"></span>';
		  
  }elseif($_POST['opcion']=='buscar_producto'){
	    $select="select
				  id_home,
				  id_ef,
				  producto,
				  producto_nombre	
				from
				  s_sgc_home
				where
				  id_ef='".$_POST['idefin']."' and producto!='H';";
		$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
		echo'<select name="idhome" id="idhome" class="required" style="width:170px;">';
					echo'<option value="">Seleccionar...</option>';
					while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['id_home'].'">'.$regief['producto_nombre'].'</option>';  
					}
		echo'</select>
		     <span class="errorMessage" id="errorproducto"></span>';
				  
  }elseif($_POST['opcion']=='buscar_producto_ocupacion'){
	    $select="select
				  id_home,
				  id_ef,
				  producto,
				  producto_nombre
				from
				  s_sgc_home
				where
				  id_ef='".$_POST['idefin']."' and producto!='H';";
		$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
		echo'<select name="producto" id="producto" class="required requerid" style="width:170px;">';
					echo'<option value="">Seleccionar...</option>';
					while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';  
					}
		echo'</select>
		     <span class="errorMessage" id="errorproducto"></span>';
	  
  }elseif($_POST['opcion']=='buscar_producto_correo'){
	    $select="select
				  id_home,
				  id_ef,
				  producto,
				  producto_nombre	
				from
				  s_sgc_home
				where
				  id_ef='".$_POST['idefin']."' and producto!='H';";
		$sql = $conexion->query($select, MYSQLI_STORE_RESULT);
		echo'<select name="producto" id="producto" class="required" style="width:230px;">';
					echo'<option value="">Seleccionar...</option>';
					while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>'; 
						  echo'<option value="F'.$regief['producto'].'">Facultativo '.$regief['producto_nombre'].'</option>';  
					}
					echo'<option value="CO">Contactos</option>';
		echo'</select>
		     <span class="errorMessage" id="errorproducto"></span>';
	  
  }elseif($_POST['opcion']=='buscar_regional'){
  ?>
    <script type="text/javascript">
       $(document).ready(function() {
		//SELECT REGIONAL -> DEPARTAMENTO AGENCIA
			   $('#regional').change(function(){
					var variables=$(this).prop('value');
					var vec = variables.split('|');
					var id_depto=vec[0];
					var id_ef=vec[1];
					//alert('id_dpto:'+id_depto+' id_ef:'+id_ef);
					var dataString = 'id_departamento='+id_depto+'&identidadf='+id_ef+'&opcion=buscar_agencia_multiple';
				    //alert(dataString);
				    $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							 $('#content-agency').fadeIn('slow');
							 $('#response-loading').html(datareturn);			
						 }
				    });   
			   });
       });
	</script>    
  <?php	  
	      /*$selectReg="select
					   id_depto,
					   departamento,
					   codigo 
					from
					  s_departamento
					where
					  tipo_re=1 and id_ef='".$_POST['identidadf']."';";
		  $rsreg=mysql_query($selectReg,$conexion);
		  echo'<select name="regional" id="regional" class="requerid" style="width:200px;">';
				echo'<option value="">Seleccionar...</option>';
				while($filareg=mysql_fetch_array($rsreg)){
				   echo'<option value="'.$filareg['id_depto'].'">'.$filareg['departamento'].'</option>';    
				}
		  echo'</select>';*/
	 $selectReg="select
				   id_depto,
				   departamento,
				   codigo 
				from
				  s_departamento
				where
				  tipo_dp=1;";
	  $rsreg = $conexion->query($selectReg,MYSQLI_STORE_RESULT);
	  echo'<select name="regional" id="regional" class="requerid" style="width:200px;">';
			echo'<option value="">Seleccionar...</option>';
			while($filareg = $rsreg->fetch_array(MYSQLI_ASSOC)){
			   echo'<option value="'.$filareg['id_depto'].'|'.$_POST['identidadf'].'">'.$filareg['departamento'].'</option>';    
			}
	  echo'</select>';  
	   echo'<span class="errorMessage" id="errorregional"></span>';
  }elseif($_POST['opcion']=='buscar_ef_home'){
	  $busca="select
				   count(id_home) as num
				from
				  s_sgc_home
				where
				  id_ef='".$_POST['id_ef']."' and implante=1;";
	  $res = $conexion->query($busca,MYSQLI_STORE_RESULT);			  
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num']>0){
		  echo 1;
	  }else{
		  echo 0;
	  }			  
  }elseif($_POST['opcion']=='busca_implante'){
	  $select="select
				 id_home,
				 id_ef,
				 count(implante) as num_implante
			  from
				s_sgc_home
			  where
				id_ef='".$_POST['id_ef']."' and implante=1;";
	  $res = $conexion->query($select, MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  echo $regi['num_implante'];			
  }elseif($_POST['opcion']=='busca_producto_entidad'){
	  $i=0; $vec=array();
	  $select="select
				  id_home,
				  id_ef,
				  producto
				from
				 s_sgc_home
				where
				  id_ef='".$_POST['idef']."' and producto!='H';";
	  $sql = $conexion->query($select,MYSQLI_STORE_RESULT);
	  while($regi = $sql->fetch_array(MYSQLI_ASSOC)){
		  $vec[$i]=$regi['producto'];
		  $i++;
	  }
	  echo json_encode($vec);			  
  }elseif($_POST['opcion']=='busca_formapago'){
	  $select="select
				  count(id_forma_pago) as num_reg
				from
				  s_forma_pago
				where
				  codigo='".$_POST['forma_pago_code']."' 
				      and id_ef='".$_POST['id_ef']."' 
					  and producto='".$_POST['producto']."';";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num_reg']>0){
		  echo 1;
	  }else{
		  echo 0;
	  }				  
  }elseif($_POST['opcion']=='buscar_asigancion'){
	  $select="  select
		  count(id_ef_cia) as num
		from
		  s_ef_compania
		where
		  id_ef='".$_POST['idefin']."' and producto='DE' and activado=1;";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $reg = $res->fetch_array(MYSQLI_ASSOC);	  
	  if($reg['num']>0){
		 echo 1;  
	  }else{
		 echo 0; 
	  }	  
  }elseif($_POST['opcion']=='busca_productos_mod'){
  ?>
    <script type="text/javascript">
       $(document).ready(function() {
          //VERIFICAMOS SI EXISTE ALGUNA MODALIDAD ACTIVADA 
		  //EN ALGUN PRODUCTO DE LA ENTIDAD FINANCIERA
		  $('#productoMod').change(function(){
			  var data = $(this).prop('value');
			  //alert(data);
			  var vec = data.split('|');
			  var producto_code = vec[0];
			  var identidadf = vec[1];
			  $.post("buscar_registro.php",
				  {id_ef:identidadf,producto:producto_code,opcion:"busca_mod_active"},
				  function(data, textStatus, jqXHR){
					 //alert(data);
					 if(data==2){
					    $('#errorproductomod').html('El producto de la Entidad Financiera no tiene activado la modalidad, porfavor active la modalidad para reaizar un nuevo registro');
					    $("#frmAdiModalidad :submit").attr("disabled", true); 
					 }else if(data==1){
						$('#errorproductomod').hide('slow');
						$("#frmAdiModalidad :submit").removeAttr("disabled"); 
					 }
				  }
			  );				  
		  });
       });
    </script>
  <?php	    
		$select="select
				  id_home,
				  id_ef,
				  producto,
				  producto_nombre
				from
				  s_sgc_home
				where
				  id_ef='".$_POST['idefin']."' and producto!='H';";
		$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
		echo'<select name="productoMod" id="productoMod" class="required requerid" style="width:170px;">';
					echo'<option value="">Seleccionar...</option>';
					while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
						  echo'<option value="'.$regief['producto'].'|'.base64_encode($regief['id_ef']).'">'.$regief['producto_nombre'].'</option>';  
					}
		echo'</select>
		     <span class="errorMessage" id="errorproductomod"></span>';
	  
  }elseif($_POST['opcion']=='busca_mod_active'){
	  $select = "select
				  count(id_home) as num_regi
				from
				  s_sgc_home
				where
				  id_ef='".base64_decode($_POST['id_ef'])."' and producto='".$_POST['producto']."' and modalidad=true;";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num_regi']>0){
		  echo 1;
	  }else{
		  echo 2;
	  }			  
  }
  

?>