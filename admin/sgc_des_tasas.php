<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	if($op==1){$valor='success';}elseif($op==2){$valor='error';}
  ?>
  $(function(){
    //PLUGIN AMBIENCE
    <?php if($msg!=''){ ?>
		 $.ambiance({message: "<?php echo $msg;?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=des_tasas&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>

<?php
include('config.class.php');
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
$base_datos = new DB_bisa();
$conexion = $base_datos->connectDB();
//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
	
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
		} else {
			//SI LOS DATOS NO SON CORRECTOS, MOSTRAMOS EL FORM DE LOGIN CON EL MENSAJE DE ERROR
			session_unset();
		    session_destroy();
			mostrar_login_form(2);
		}
	} else {
		//SI NO HA HECHO CLICK EN EL FORM, MOSTRAMOS EL FORMULARIO DE LOGIN
		session_unset();
		session_destroy();
		mostrar_login_form(1);
	}
}


//FUNCION PARA MOSTRAR EL SGC PARA ADMINISTRACION DE USUARIOS
function mostrar_pagina($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $lugar) {			
?>
       
	<!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
	<div id="da-wrapper" class="fluid">
    
        <!-- Header -->
        <div id="da-header">
        
        	<div id="da-header-top">
                
                <!-- Container -->
                <div class="da-container clearfix">
                    
                    <!-- Logo Container. All images put here will be vertically centere -->
                    <div id="da-logo-wrap">
                        <?php logo_container();?>
                    </div>
                                      
                    <!-- Header Toolbar Menu -->
                    <div id="da-header-toolbar" class="clearfix">
                        <?php header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                                    
                </div>
            </div>
            
            <div id="da-header-bottom">
                <?php header_bottom('i',$_GET['var'],1);?>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            
            <!-- Container -->
            <div class="da-container clearfix">
            
                <!-- Sidebar -->
                <div id="da-sidebar-separator"></div>
                <div id="da-sidebar">
                
                    <!-- Main Navigation -->
                    <div id="da-main-nav" class="da-button-container">
                        <?php main_navegation($lugar,$id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                    
                </div>
                
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                    
                    	<div class="grid_4">
                           <?php
                            //NECESITO SABER SI DEBO CREAR UN NUEVO USUARIO
							if(isset($_GET['crear'])) {
						
								agregar_nueva_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								
								if(isset($_GET['listartasas'])){
									//LISTAMOS LAS TASAS PARA EDITAR 
								    listar_tasas_editar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion); 
							    }elseif(isset($_GET['agregartasa'])){
								    //AGREGAMOS TASAS NUEVAS DE ACUERDO A LOS PRODUCTOS
									agregar_tasas_nuevas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion); 
							    }else {
									//LISTAMOS LAS COMPAÑIAS ACTIVAS PARA AÑADIR-EDITAR O ELIMINAR TASAS
									listar_companias_activas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}
							}
							
						   ?>
                        </div>
                                                  
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<?php footer();?>
        </div>
        
    </div>

<?php
}

//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function listar_companias_activas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
$selectFor="select 
			  id_compania,
			  nombre,
			  logo,
			  if(activado=1,'habilitado','deshabilitado') as activado
			from
			  s_compania
			where
			  activado=1
			order by id_compania asc;";
$res=mysql_query($selectFor,$conexion);		  

echo'
<div class="da-panel collapsible">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			Compañías de Seguros - Editar Tasas 
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th>Titulo Compañía</th>
					<th style="width:100px;">Estado</th>
					<th style="width:200px; text-align:center">Imagen</th>
					<th></th>
				</tr>
			</thead>
			<tbody>';
			  $num=mysql_num_rows($res);
			  if($num>0){
					while($regi=mysql_fetch_array($res)){
						echo'<tr>
								<td>'.$regi['nombre'].'</td>
								<td>'.$regi['activado'].'</td>
								<td style="text-align:center;">';
								   if($regi['logo']!=''){
									   if(file_exists('../images/'.$regi['logo'])){  
									      $imagen = getimagesize('../images/'.$regi['logo']);    //Sacamos la información
                                          $ancho = $imagen[0];              //Ancho
                                          $alto = $imagen[1]; 
										  echo'<img src="../images/'.$regi['logo'].'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
									   }else{
										  echo'no existe el archivo fisico';   
									   }
								   }else{
									  echo'no existe el nombre del archivo en la base de datos';   
								   }
						   echo'</td>
								<td class="da-icon-column">
								   <ul class="action_user">
									  <li style="margin-right:5px;"><a href="?l=des_tasas&idcompania='.base64_encode($regi['id_compania']).'&listartasas=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="Editar tasas"></a></li>';
								 echo'<li style="margin-right:5px;"><a href="?l=des_tasas&idcompania='.base64_encode($regi['id_compania']).'&agregartasa=v&var='.$_GET['var'].'" class="create da-tooltip-s" title="Agregar tasas"></a></li>';
								 //echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'" class="darbaja da-tooltip-s" title="Desactivar"></a></li>';  
									 
							  echo'</ul>	
								</td>
							</tr>';
					}			
			  }else{
			     echo'<tr><td colspan="7">
				          <div class="da-message info">
                               No existe registros alguno, ingrese nuevos registros
                          </div>
				      </td></tr>';
			  }
	   echo'</tbody>
		</table>
	</div>
</div>';
}

//FUNCION QUE PERMITE LISTAR LAS TASAS PARA EDITAR
function listar_tasas_editar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    //INICIAMOS EL ARRAY CON LOS ERRORES
	$errArr['errortasacom'] = '';
	$errArr['errortasaban'] = '';
	$errArr['errortasafin'] = '';
	$errFlag = false;
	
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		$numerotasas=3;//No TASAS A EDITAR
		$t=1;
		while($t<=$numerotasas){
		    
			
			if($t==1){//COLUMNA TASA COMPAÑIA
				  $tc=1;
				  $indicetc=0;
				  $arrtc[]=array();
				  $swtc=0;
				   //iteramos hasta la cantidad de productos
				  while($tc<=$_POST['num_prod']){
					    $valor_tc=$_POST["txtTcompania".$tc];
					    $numtc=validar_numero($valor_tc);
					    if($numtc==1) {
							$arrtc[$indicetc]=$tc;
							$swtc=1;
							$indicetc++; 
						}elseif($numtc==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtc==3){
							$arrtc[$indicetc]=$tc;
							$swtc=1; 
							$indicetc++;
						}
						$tc++;
				  }
				  if($swtc==0){
		             
				  }elseif($swtc==1){
						$errArr['errortasacom'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtc as $datotc){
							 $errArr['errortasacom'] .= $datotc.',';
						}
						$errArr['errortasacom'] .= '&nbsp;de la columna Tasa Compañía';			
						$errFlag = true;
						unset($arrtc);
				  }
			
			}elseif($t==2){//COLUMNA TASA BANCO
			      $tb=1;
				  $indicetb=0;
				  $arrtb[]=array();
				  $swtb=0;
				   //iteramos hasta la cantidad de productos
				  while($tb<=$_POST['num_prod']){
					    $valor_tb=$_POST["txtTbanco".$tb];
					    $numtb=validar_numero($valor_tb);
					    if($numtb==1) {
							$arrtb[$indicetb]=$tb;
							$swtb=1;
							$indicetb++; 
						}elseif($numtb==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtb==3){
							$arrtb[$indicetb]=$tb;
							$swtb=1; 
							$indicetb++;
						}
						$tb++;
				  }
				  if($swtb==0){
		
				  }elseif($swtb==1){
						$errArr['errortasaban'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtb as $datotb){
							 $errArr['errortasaban'] .= $datotb.',';
						}
						$errArr['errortasaban'] .= '&nbsp;de la columna Tasa Banco';			
						$errFlag = true;
						unset($arrtb);
				  }
					
			}elseif($t==3){//COLUMNA TASA FINAL
			      $tf=1;
				  $indicetf=0;
				  $arrtf[]=array();
				  $swtf=0;
				   //iteramos hasta la cantidad de productos
				  while($tf<=$_POST['num_prod']){
					    $valor_tf=$_POST["txtTfinal".$tf];
					    $numtf=validar_numero($valor_tf);
					    if($numtf==1) {
							$arrtf[$indicetf]=$tf;
							$swtf=1;
							$indicetf++; 
						}elseif($numtf==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtf==3){
							$arrtf[$indicetf]=$tf;
							$swtf=1; 
							$indicetf++;
						}
						$tf++;
				  }
				  if($swtf==0){
		
				  }elseif($swtf==1){
						$errArr['errortasafin'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtf as $datotf){
							 $errArr['errortasafin'] .= $datotf.',';
						}
						$errArr['errortasafin'] .= '&nbsp;de la columna Tasa Final';			
						$errFlag = true;
						unset($arrtf);
				  }
			
			}
			
		  $t++;	
		
		}//FIN WHILE NUMERO TASAS
		
		//VEMOS SI NO HUBO ERRORES*/
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			$j=1;								 
			while($j<=$_POST['num_prod']){
				$tasacompania=$_POST["txtTcompania".$j];
				$tasabanco=$_POST["txtTbanco".$j];
				$tasafinal=$_POST["txtTfinal".$j];
				$idtasa=$_POST["idtasa".$j];
				$idprcia=$_POST["idprcia".$j];
				$update = "UPDATE s_tasa_de SET tasa_cia=".$tasacompania.", tasa_banco=".$tasabanco.", tasa_final=".$tasafinal." WHERE id_tasa=".$idtasa." and id_prcia=".$idprcia.";"; 
				$rsudate = mysql_query($update, $conexion);
				$j++;
			}		
			
			if(mysql_errno($conexion)==0){
			    $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_tasas&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			    exit;
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " .mysql_error($conexion);
			    header('Location: index.php?l=des_tasas&var='.$_GET['var'].'&op=2&msg='.$mensaje);
				exit;
			} 
		}//CERRAMOS LLAVE ELSE SI NO HUBIERON ERRORES
        
	} else {
	  //MUESTRO FORM PARA EDITAR LAS TASAS
	  mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZMOS EL FORMULARIO CON LAS TASAS PARA SE EDITADAS
function mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
?>	
<script type="text/javascript">
  $(function(){
	  $('#btnCancelar').click(function(){
		  var variable=$('#var').prop('value');  
		  $(location).attr('href', 'index.php?l=des_tasas&var='+variable); 
	  });  
  });
</script>	
<?php    
	$idcompania=base64_decode($_GET['idcompania']);
	$selectCia="select
				   id_compania,
				   nombre
				from
				   s_compania
				where
				   activado=1 and id_compania=".$idcompania."
				limit 
				   0,1;";
    $filacia=mysql_fetch_array(mysql_query($selectCia,$conexion));
	
	//SACAMOS LAS TASAS
	$selectTs="select
				  spc.id_prcia,
				  spc.nombre,
				  std.tasa_cia,
                  std.tasa_banco,
                  std.tasa_final,
                  std.id_tasa  
				from  
				  s_producto_cia as spc
				  inner join s_producto as sp on (sp.id_producto=spc.id_producto)
				  inner join s_tasa_de as std on (std.id_prcia=spc.id_prcia)
				where
				  spc.id_compania=".$idcompania." and sp.activado=1;";		  
	$resu=mysql_query($selectTs,$conexion);			  		  

echo'
<div class="da-panel collapsible" style="width:780px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    Compañia de Seguros - '.$filacia['nombre'].' - Editar Tasas
		</span>
	</div>
	<div class="da-panel-content">';
	 $num=mysql_num_rows($resu);
	 if($num>0){
	  echo'<form class="da-form" name="frmUsuario" action="" method="post">
	     		<div class="da-form-row" style="padding:0px;">
				  <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th><b>No</b></th>
								<th style="width:200px;"><b>Producto</b></th>
								<th><b>Tasa Compañia</b></th>
								<th><b>Tasa Banco</b></th>
								<th><b>Tasa Final</b></th>
							</tr>
						</thead>
						<tbody>';
						 
								$i=1;
								$suma_total=0;
								while($regi=mysql_fetch_array($resu)){
									if(isset($_POST["txtTcompania".$i])) $tcompania = $_POST["txtTcompania".$i]; else $tcompania = $regi['tasa_cia']; 
									if(isset($_POST["txtTbanco".$i])) $tbanco = $_POST["txtTbanco".$i]; else $tbanco = $regi['tasa_banco']; 
									if(isset($_POST["txtTfinal".$i])) $tfinal = $_POST["txtTfinal".$i]; else $tfinal = $regi['tasa_final']; 
									$suma_total=$tcompania+$tbanco;
									echo'<tr>
											<td>'.$i.'</td>
											<td>'.$regi['nombre'].'</td>
											<td><input type="text" name="txtTcompania'.$i.'" id="'.$i.'-txtTcompania" value="'.$regi['tasa_cia'].'" class="validatasa"/><span class="errorMessage" id="errortcia'.$i.'"></span></td>
											<td><input type="text" name="txtTbanco'.$i.'" id="'.$i.'-txtTbanco" value="'.$regi['tasa_banco'].'" class="validatasa"/><span class="errorMessage" id="errortban'.$i.'"></span></td>
											<td><input type="text" name="txtTfinal'.$i.'" id="'.$i.'-txtTfinal" value="'.$suma_total.'" readonly="readonly" class="validatasa"/><input type="hidden" name="idtasa'.$i.'" id="idtasa'.$i.'" value="'.$regi['id_tasa'].'"/><input type="hidden" name="idprcia'.$i.'" id="idprcia'.$i.'" value="'.$regi['id_prcia'].'"/></td>
										</tr>';
										$i++;
								}			
						  
						  echo'<tr><td colspan="5">
								  <span class="errorMessage">'.$errArr['errortasacom'].'</span>
								  <span class="errorMessage">'.$errArr['errortasaban'].'</span>
								  <span class="errorMessage">'.$errArr['errortasafin'].'</span>
							   </td></tr>';
				   echo'</tbody>
					</table>
				  </div>	
		        </div>
			    <div class="da-button-row">
				   <input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
				   <input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="num_prod" value="'.$num.'" id="num_prod"/>
				   <input type="hidden" id="var" value="'.$_GET['var'].'"/>
			    </div>	
	       </form>';
     }else{
		 echo'<div class="da-message info">
				  No existe registros alguno, ingrese nuevos registros
			  </div>';
	 }		   	
echo'</div>
</div>';
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_tasas_nuevas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	//INICIAMOS EL ARRAY CON LOS ERRORES
	$errArr['errortasacom'] = '';
	$errArr['errortasaban'] = '';
	$errArr['errortasafin'] = '';
	$errFlag = false;
     
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
			
		$numerotasas=3;
		$t=1;
		while($t<=$numerotasas){
		    
			
			if($t==1){//COLUMNA TASA COMPAÑIA
				  $tc=1;
				  $indicetc=0;
				  $arrtc[]=array();
				  $swtc=0;
				   //iteramos hasta la cantidad de productos
				  while($tc<=$_POST['num_prod']){
					    $valor_tc=$_POST["txtTcompania".$tc];
					    $numtc=validar_numero($valor_tc);
					    if($numtc==1) {
							$arrtc[$indicetc]=$tc;
							$swtc=1;
							$indicetc++; 
						}elseif($numtc==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtc==3){
							$arrtc[$indicetc]=$tc;
							$swtc=1; 
							$indicetc++;
						}
						$tc++;
				  }
				  if($swtc==0){
		             
				  }elseif($swtc==1){
						$errArr['errortasacom'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtc as $datotc){
							 $errArr['errortasacom'] .= $datotc.',';
						}
						$errArr['errortasacom'] .= '&nbsp;de la columna Tasa Compa&ntilde;&iacute;a';			
						$errFlag = true;
						unset($arrtc);
				  }
			
			}elseif($t==2){//COLUMNA TASA BANCO
			      $tb=1;
				  $indicetb=0;
				  $arrtb[]=array();
				  $swtb=0;
				   //iteramos hasta la cantidad de productos
				  while($tb<=$_POST['num_prod']){
					    $valor_tb=$_POST["txtTbanco".$tb];
					    $numtb=validar_numero($valor_tb);
					    if($numtb==1) {
							$arrtb[$indicetb]=$tb;
							$swtb=1;
							$indicetb++; 
						}elseif($numtb==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtb==3){
							$arrtb[$indicetb]=$tb;
							$swtb=1; 
							$indicetb++;
						}
						$tb++;
				  }
				  if($swtb==0){
		
				  }elseif($swtb==1){
						$errArr['errortasaban'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtb as $datotb){
							 $errArr['errortasaban'] .= $datotb.',';
						}
						$errArr['errortasaban'] .= '&nbsp;de la columna Tasa Banco';			
						$errFlag = true;
						unset($arrtb);
				  }
					
			}elseif($t==3){//COLUMNA TASA FINAL
			      $tf=1;
				  $indicetf=0;
				  $arrtf[]=array();
				  $swtf=0;
				   //iteramos hasta la cantidad de productos
				  while($tf<=$_POST['num_prod']){
					    $valor_tf=$_POST["txtTfinal".$tf];
					    $numtf=validar_numero($valor_tf);
					    if($numtf==1) {
							$arrtf[$indicetf]=$tf;
							$swtf=1;
							$indicetf++; 
						}elseif($numtf==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtf==3){
							$arrtf[$indicetf]=$tf;
							$swtf=1; 
							$indicetf++;
						}
						$tf++;
				  }
				  if($swtf==0){
		
				  }elseif($swtf==1){
						$errArr['errortasafin'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtf as $datotf){
							 $errArr['errortasafin'] .= $datotf.',';
						}
						$errArr['errortasafin'] .= '&nbsp;de la columna Tasa Final';			
						$errFlag = true;
						unset($arrtf);
				  }
			
			}
			
		  $t++;	
		
		}
								
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
						
			$j=1;								 
			while($j<=$_POST['num_prod']){
				$tasacompania=$_POST["txtTcompania".$j];
				$tasabanco=$_POST["txtTbanco".$j];
				$tasafinal=$_POST["txtTfinal".$j];
				$idprcia=$_POST["idprcia".$j];
				
				$insert = "INSERT INTO s_tasa_de(id_tasa, id_prcia, tasa_cia, tasa_banco, tasa_final) "
			      ."VALUES(NULL, ".$idprcia.", ".$tasacompania.", ".$tasabanco.", ".$tasafinal.")"; 
				$resu = mysql_query($insert, $conexion);
				$j++;
			}
			//METEMOS A LA TABLA TBLHOMENOTICIAS
			if(mysql_errno($conexion)==0){
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=des_tasas&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " .mysql_error($conexion);
			    header('Location: index.php?l=des_tasas&var='.$_GET['var'].'&op=2&msg='.$mensaje);
				exit;
			}	
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){

   
    $idcompania=base64_decode($_GET['idcompania']);
	$selectCia="select
				   id_compania,
				   nombre
				from
				   s_compania
				where
				   activado=1 and id_compania=1
				limit 
				   0,1;";
    $filacia=mysql_fetch_array(mysql_query($selectCia,$conexion));
		
	$selectTs="select 
					spc.id_prcia,
					spc.nombre,
					spc.id_compania,
					spc.id_producto
				from
					s_producto_cia as spc
						inner join
					s_producto as sp ON (sp.id_producto = spc.id_producto)
						left join
					s_tasa_de as std ON (std.id_prcia = spc.id_prcia)
				where
					spc.id_compania = ".$idcompania." and sp.activado = 1
						and not exists( select 
							std2.id_prcia
						from
							s_tasa_de as std2
						where
							std2.id_prcia = spc.id_prcia);";
	$resu=mysql_query($selectTs,$conexion);			  
	
echo'
<div class="da-panel collapsible" style="width:780px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    Compañia de Seguros - '.$filacia['nombre'].'<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Agregar Tasas
		</span>
	</div>
	<div class="da-panel-content">';
	 $num=mysql_num_rows($resu);
	 if($num>0){
	  echo'<form class="da-form" name="frmTasas" action="" method="post" id="frmTasas">
	     		<div class="da-form-row" style="padding:0px;">
				   <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th><b>No</b></th>
								<th style="width:200px;"><b>Producto</b></th>
								<th><b>Tasa Compañia</b></th>
								<th><b>Tasa Banco</b></th>
								<th><b>Tasa Final</b></th>
							</tr>
						</thead>
						<tbody>';
						 
								$i=1;
								$suma_total=0;
								while($regi=mysql_fetch_array($resu)){
									if(isset($_POST["txtTcompania".$i])) $tcompania = $_POST["txtTcompania".$i]; else $tcompania = ''; 
									if(isset($_POST["txtTbanco".$i])) $tbanco = $_POST["txtTbanco".$i]; else $tbanco = ''; 
									if(isset($_POST["txtTfinal".$i])) $tfinal = $_POST["txtTfinal".$i]; else $tfinal = ''; 
									if((!empty($tcompania)) and (!empty($tbanco))){
									   $suma_total=$tcompania+$tbanco;
									}else{
									   $suma_total=0;
									}
									echo'<tr>
											<td>'.$i.'</td>
											<td>'.$regi['nombre'].'</td>
											<td><input type="text" name="txtTcompania'.$i.'" id="'.$i.'-txtTcompania" value="'.$tcompania.'" class="validatasa"/><span class="errorMessage" id="errortcia'.$i.'"></span></td>
											<td><input type="text" name="txtTbanco'.$i.'" id="'.$i.'-txtTbanco" value="'.$tbanco.'" class="validatasa"/><span class="errorMessage" id="errortban'.$i.'"></span></td>
											<td><input type="text" name="txtTfinal'.$i.'" id="'.$i.'-txtTfinal" value="'.$suma_total.'" readonly="readonly" class="validatasa"/><input type="hidden" name="idprcia'.$i.'" id="idprcia'.$i.'" value="'.$regi['id_prcia'].'"/></td>
										</tr>';
										$i++;
								}			
						  
						  echo'<tr><td colspan="5">
								  <span class="errorMessage">'.$errArr['errortasacom'].'</span>
								  <span class="errorMessage">'.$errArr['errortasaban'].'</span>
								  <span class="errorMessage">'.$errArr['errortasafin'].'</span>
							   </td></tr>';
				   echo'</tbody>
					</table>
				   </div>	
		        </div>
			    <div class="da-button-row">
				   <input type="reset" value="Reset" class="da-button gray left"/>
				   <input type="submit" value="Guardar" class="da-button green" name="btnSaveTasas" id="btnSaveTasas"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="num_prod" value="'.$num.'" id="num_prod"/>
			    </div>	
	       </form>';
     }else{
		 echo'<div class="da-message info">
				  No existe registros alguno, para ingresar nuevas tasas añada un nuevo producto en la seccion [Administrar productos] de la pestaña [Desgravamen] del menu lateral izquierdo.
			  </div>';
	 }		   	
echo'</div>
</div>';
}

//FUNCION PARA EDITAR UN USUARIO
function editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {

	$errFlag = false;
	$errArr['errorpregunta'] = '';
	$errArr['errorcompania'] = '';
	$errArr['errorespuesta'] = '';
	

	$idpregunta = base64_decode($_GET['idpregunta']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
       
        //VALIDAR TITULO
		$num=validar_pregunta($_POST['txtPregunta']);
		if($num==1){
			$errArr['errorpregunta'] = "Ingrese la pregunta";
			$errFlag = true;
		}elseif($num==2){
		  //DATOS CORRECTOS
		}elseif($num==3){
		   $errArr['errorpregunta'] = "Ingrese solo caracteres";
		   $errFlag = true;
		}
		
		//VALIDAR COMPANIA
		$num=validar_select($_POST['idcompania']);
		if($num==1){
			$errArr['errorcompania'] = "seleccione compañia";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
		
		//VALIDAR RESPUESTA
		echo $_POST['txtRespuesta'];
		$num=validar_select_boolean($_POST['txtRespuesta']);
		if($num==1){
			$errArr['errorespuesta'] = "seleccione respuesta";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
				
        //VEMOS SI TODO SE VALIDO BIEN
        if($errFlag) {
            //SI HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
            mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
        } else {
            //SEGURIDAD
			$txtpregunta = mysql_real_escape_string($_POST['txtPregunta']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update ="UPDATE s_pregunta SET pregunta='".$txtpregunta."',";
            $update.=" id_compania=".$_POST['idcompania'].", respuesta=".$_POST['txtRespuesta']." ";
            $update.="WHERE id_pregunta=".$idpregunta." LIMIT 1;";
            //echo $update;
            $rsu = mysql_query($update, $conexion);

            if(mysql_errno($conexion)==0){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " . mysql_error($conexion);
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=2&msg='.$mensaje);
				exit;
            }

        }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
    
	$idpregunta = base64_decode($_GET['idpregunta']);

	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				  sp.id_pregunta,
				  sp.id_compania,
				  sp.pregunta,
				  sp.orden,
				  sp.respuesta,
				  sp.producto,
				  sc.nombre
				from
				  s_pregunta as sp
				  inner join s_compania as sc on (sc.id_compania=sp.id_compania)
				where
				  sc.activado=1 and sp.id_pregunta=".$idpregunta."
				order by
				  sp.orden asc;";
	$rs = mysql_query($select, $conexion);
	$num = mysql_num_rows($rs);
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num) {

		$fila = mysql_fetch_array($rs);

		if(isset($_POST['txtPregunta'])) $txtPregunta = $_POST['txtPregunta']; else $txtPregunta = $fila['pregunta'];
        if(isset($_POST['idcompania'])) $idcompania = $_POST['idcompania']; else $idcompania = $fila['id_compania'];
        if(isset($_POST['txtRespuesta'])) $txtRespuesta = $_POST['txtRespuesta']; else $txtRespuesta = $fila['respuesta'];
		
		echo'<div class="da-panel" style="width:650px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						Crear Pregunta
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmUsuario" action="" method="post" enctype="multipart/form-data">
						<div class="da-form-row">
							<label>Compañía de Seguros</label>
							<div class="da-form-item small">';
								$selectCia="select
											  id_compania,
											  nombre
											from
											  s_compania
											where
											  activado=1;";
								$regcia=mysql_query($selectCia,$conexion);
								echo'<select name="idcompania" id="idcompania">';
									  echo'<option value="">Seleccionar...</option>';
									  while($filacia=mysql_fetch_array($regcia)){
										 if($filacia['id_compania']==$idcompania){
											echo'<option value="'.$filacia['id_compania'].'" selected>'.$filacia['nombre'].'</option>';
										 }else{
											echo'<option value="'.$filacia['id_compania'].'">'.$filacia['nombre'].'</option>';  
										 }  
									  }
								echo'</select>';
								
								echo'<span class="errorMessage">'.$errArr['errorcompania'].'</span>
							</div>
						</div>
						<div class="da-form-row">
							<label>Pregunta</label>
							<div class="da-form-item large">
								<textarea id="txtPregunta" name="txtPregunta" style="height:70px;">'.$txtPregunta.'</textarea>
								<span class="errorMessage">'.$errArr['errorpregunta'].'</span>
							</div>
						</div>
						<div class="da-form-row">
							<label>Respuesta</label>
							<div class="da-form-item large">
								<select name="txtRespuesta" id="txtRespuesta" style="width:120px;">
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
								<span class="errorMessage">'.$errArr['errorespuesta'].'</span>
							</div>
						</div>												
						<div class="da-button-row">
							<input type="reset" value="Reset" class="da-button gray left"/>
							<input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta"/>
							<input type="hidden" name="accionGuardar" value="checkdatos"/>
						</div>
					</form>
				</div>
			 </div>';
	
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=des_preguntas&var='.$_GET['var']);
	}
}

//FUNCION QUE PERMITE DAR BAJA AL USUARIO
function desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idcompania = base64_decode($_GET['idcompania']);

	
	if(isset($_POST['btnBajaCompania'])) {
		
		$update ="UPDATE s_compania SET activado=0 "
				."WHERE id_compania = ".$idcompania." LIMIT 1";
			$rs = mysql_query($update, $conexion);

		if(mysql_errno($conexion)==0){
			//SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			$mensaje='se desactivo la compañia '.$idusuario.' correctamente';
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.$mensaje);
		} else{
			$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " . mysql_error($conexion);
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.$mensaje);
		} 
	}elseif(isset($_POST['btnCancelar'])){ 
		//MOSTRAMOS LA LISTA DE USUARIOS
		header('Location: index.php?l=compania&var='.$_GET['var']);
	}else {
		//MOSTRAMOS EL FORMULARIO PARA DAR BAJA COMPANIA
		mostrar_dar_baja_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_baja_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    $idcompania = base64_decode($_GET['idcompania']);
	$selectCia="select nombre from s_compania where id_compania=".$idcompania.";";
	$regCia=mysql_fetch_array(mysql_query($selectCia,$conexion));
	echo'<div style="text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
	echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
	echo '<tr><td align="center" width="100%" style="height:60px;">';
	echo 'Al deshabilitar la compañia, '
		.'se actualizara en la base de datos, est&aacute; seguro de deshabilitar la compañia <b>'.$regCia['nombre'].'</b> de forma permanente?';
	echo '</td></tr>
	      <tr> 
	      <td align="center">
	      <input class="da-button green" type="submit" name="btnBajaCompania" value="Desactivar"/>'
		.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
		  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>';
	echo '</td></tr></table></form>';
	echo'</div>';
}

//FUNCION QUE PERMITE DAR ALTA UN REGISTRO
function activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idcompania = base64_decode($_GET['idcompania']);

	
	if(isset($_POST['btnAltaCompania'])) {
		
		$update ="UPDATE s_compania SET activado=1 "
				."WHERE id_compania = ".$idcompania." LIMIT 1";
			$rs = mysql_query($update, $conexion);

		if(mysql_errno($conexion)==0){
			//SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			$mensaje='se desactivo la compañia '.$idusuario.' correctamente';
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.$mensaje);
		} else{
			$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " . mysql_error($conexion);
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.$mensaje);
		} 
	}elseif(isset($_POST['btnCancelar'])){ 
		//MOSTRAMOS LA LISTA DE USUARIOS
		header('Location: index.php?l=compania&var='.$_GET['var']);
	}else {
		//MOSTRAMOS EL FORMULARIO PARA DAR BAJA COMPANIA
		mostrar_dar_alta_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_alta_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    $idcompania = base64_decode($_GET['idcompania']);
	$selectCia="select nombre from s_compania where id_compania=".$idcompania.";";
	$regCia=mysql_fetch_array(mysql_query($selectCia,$conexion));
	echo'<div style="text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
	echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
	echo '<tr><td align="center" width="100%" style="height:60px;">';
	echo 'Al habilitar la compañia, '
		.'se actualizara en la base de datos, est&aacute; seguro de habilitar la compañia <b>'.$regCia['nombre'].'</b> de forma permanente?';
	echo '</td></tr>
	      <tr> 
	      <td align="center">
	      <input class="da-button green" type="submit" name="btnAltaCompania" value="Desactivar"/>'
		.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
		  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>';
	echo '</td></tr></table></form>';
	echo'</div>';
}
?>