<?php
if(($rs_FORMS = $link->get_home_forms($_SESSION['idEF'])) !== FALSE){
	if($rs_FORMS->num_rows > 0){
		while($row_FORMS = $rs_FORMS->fetch_array(MYSQLI_ASSOC)){
?>
<h3>Formularios - <?=$row_FORMS['f_producto_text'];?></h3>
<a href="file_form/<?=$row_FORMS['f_archivo'];?>" target="_blank" class="list-forms"><?=$row_FORMS['f_titulo'];?></a>
<?php
		}
	}else{
		echo 'No existen formularios';
	}
}
?>