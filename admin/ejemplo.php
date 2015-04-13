<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
       $('.page').click(function(){
			 alert('hola mundo');  
	   });
  });
</script>
</head>

<body>
<?php
require_once('config.class.php');
$conexion = new SibasDB();
$myarray = array(1 => 10001, 2 => 20000, 3 => 20001, 4 => 30000);
$myJson = json_encode($myarray);
echo $myJson;echo'<br/>'; 

$nonsequential = array(0=>"foo", 1=>"bar", 2=>"baz", 3=>"blong");
$resp = json_encode($nonsequential);	
echo $resp;

echo'<a href="#" class="page">link</a>';
?>
</body>
</html>