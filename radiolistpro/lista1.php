<?PHP
//ob_implicit_flush(1);
ob_start('ob_gzhandler');
set_time_limit(0);
ini_set("memory_limit","-1");   
require_once('../../Connections/radiocomunicate.php'); ?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
function listdir($start_dir='.') {
//echo ". ";
  $files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
      # loop through the files, skipping . and .., and recursing if necessary
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir . '/' . $file;
      if ( is_dir($filepath) )
        $files = array_merge($files, listdir($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } elseif(is_file($start_dir)) {
  $files = array($start_dir);
  }else{
    # false if the function was called with an invalid non-directory argument
    $files = false;
  }
  return $files;
}//list dir

$postartista_pistas = "-1";
if (isset($_GET['ARTISTA'])) {
  $postartista_pistas = $_GET['ARTISTA'];
}
mysql_select_db($database_radiocomunicate, $radiocomunicate);
$query_pistas = sprintf("SELECT * FROM hacerlist_audiofiles", GetSQLValueString($postartista_pistas, "text"));
$pistas = mysql_query($query_pistas, $radiocomunicate) or die(mysql_error());
$row_pistas = mysql_fetch_assoc($pistas);
$totalRows_pistas = mysql_num_rows($pistas);

$generos = array();
$duracion = 0;
$pisticas = array();
$pistas_total = 0;
$titulos = array();
$artistas = array();
?>
<?php do { ?>
<?PHP
//$musica[] = array('RUTA'=>$row_pistas['path'],'TITULO'=>$row_pistas['titulo'],'ARTISTA'=>$row_pistas['artista'],'GENERO'=>$row_pistas['genero'],'LONGITUD'=>$row_pistas['longitud']);
$generos[$row_pistas['genero']] = true;//Agrupar Generos
$duracion += $row_pistas['longitud'];
$pisticas[$row_pistas['artista'].$row_pistas['titulo']] = TRUE;
$titulos[$row_pistas['titulo']] = TRUE;
$artistas[$row_pistas['artista']] = TRUE;
$pistas_total++;
?>
  <?php } while ($row_pistas = mysql_fetch_assoc($pistas)); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>interfaz</title>
<link type="text/css" href="jquery-ui-1.7.2.custom.css" media="all" rel="stylesheet" />
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.7.2.custom.min.js"></script>
</head>

<body>
<?PHP
//Formatear en tiempo
$dias = 0;
$horas = 0;
$minutos = 0;
$segundos = $duracion;
if($segundos>=60){
$minutos = intval($segundos/60);
$segundos = intval((($segundos/60)-floor($segundos/60))*60);
}
if($minutos>=60){
$horas = intval($minutos/60);
$minutos = intval((($minutos/60)-floor($minutos/60))*60);
}
if($horas>=24){
$dias = intval($horas/24);
$horas = intval((($horas/24)-floor($horas/24))*24);
}
echo " Total Duraci&oacute;n: ".$dias." D&iacute;as ".$horas." Horas ".$minutos." Minutos ".$segundos." Segundos<br />";
echo "Pistas de m&uacute;sica &uacute;nica: ".count($pisticas)." <br />";
echo "Titulos &uacute;nicos: ".count($titulos)." <br />";
echo "Artistas &uacute;nicos: ".count($artistas)." <br />";
echo "Pistas de m&uacute;sica: ".$pistas_total." <br />";
?>
<form action="final.php" method="post" enctype="application/x-www-form-urlencoded" target="_blank" name="hacerLista" id="hacerLista">
<?PHP /* ?>
Seleccione el numero de veces que debe repetir este ciclo: <select name="nLoops"><option value="1">1</option><?PHP
for($i=2;$i<=10000;$i++){
echo "<option value=\"{$i}\">{$i}</option>\r\n";
}
?>
</select><br />
<?PHP */ ?>
Indique la cantidad de tiempo que debe durar la lista: 
D&iacute;as: 
<select name="tiempo_dias">
<option value="0">0</option>
<?PHP
for($i=1;$i<=$dias;$i++){
echo '<option value="'.$i.'">'.$i.'</option>'."\r\n";
}
?>
</select>
Horas: 
<select name="tiempo_horas" id="tiempo_horas" onchange="document.getElementById('duracion').value = this.value+'H'">
<?PHP
for($i=0;$i<24;$i++){
echo '<option value="'.$i.'">'.$i.'</option>'."\r\n";
}
?>
</select>
Minutos: 
<select name="tiempo_minutos">
<?PHP
for($i=0;$i<60;$i++){
echo '<option value="'.$i.'">'.$i.'</option>'."\r\n";
}
?>
</select>
Segundos: 
<select name="tiempo_segundos">
<?PHP
for($i=0;$i<60;$i++){
echo '<option value="'.$i.'">'.$i.'</option>'."\r\n";
}
?>
</select>

<br />
<?PHP
/*
Carpeta Separadores: <input name="carpetaPisadores" type="text" value="\\Radiocomunicate\C\wamp\radiocomunicate.com\RADIOCOMUNICATE OK\PISADORES GENERALES RADIOCOMUNICATE.COM\RADIOCOMUNICATE" size="155" maxlength="255" />
*/?>
<br />
Cada Cuantas Pistas poner separadores: <select name="cadaPistas">
<option value="1">1</option>
<?PHP
for($i=2;$i<=10;$i++){
echo "<option value=\"{$i}\">{$i}</option>\r\n";
}
?>
</select><br />
<?PHP
//Carpeta separadores:

//$carpetaPisadores = '//Radiocomunicate/MUSICA/RADIOCOMUNICATE OK/PISADORES - SEPARADORES RADIOCOMUNICATE.COM';
$carpetaPisadores = 'D:/RADIOCOMUNICATE OK/PISADORES - SEPARADORES RADIOCOMUNICATE.COM';
$separadoresProgramas = listdir($carpetaPisadores);
$cuentaPisadores = count($separadoresProgramas)-2;?>
<div class="separadores">
<select name="separadores[]">
<option value="NINGUNO">NINGUNO</option>
<?PHP
foreach($separadoresProgramas as $separador){
if($separador == '.' || $separador == '..'){continue;}
$info = pathinfo($separador);
$name = $info['basename'];
$algo = explode("/",$separador);
$nombrePrograma = $algo[count($algo)-2];
?>
<option value="<?PHP echo $separador;?>"><?PHP echo $nombrePrograma;?> - <?PHP echo $name;?></option><?PHP
}//foreach de scandir de la carpeta separadores
?>
</select>
<input type="button" value="+" />  - <input type="button" value="-" />
</div>
<br />
<?PHP
$rutaCuñas = 'D:/RADIOCOMUNICATE OK/CUÑAS RADIOCOMUNICATE.COM';//con ñ, no se las otras versiones de php, pero esta la 5.2.8 con la que estoy trabajando funciona bien, eso es universalidad
$rutaCuñas2= 'D:/RADIOCOMUNICATE OK/PROMOS';
$rutaCuñas3= 'D:/RADIOCOMUNICATE OK/SALUDOS DE ARTISTAS A RADIOCOMUNICATE.COM';
$rutaCuñas4 = 'D:/RADIOCOMUNICATE OK/INTROS - CORTINAS Y EFECTOS';
$cuñas = array_merge(listdir($rutaCuñas),listdir($rutaCuñas2),listdir($rutaCuñas3),listdir($rutaCuñas4));
$cuentaCuñas = count($cuñas)-2;?><br />Cu&ntilde;as: <select name="cadaCunas">
<?PHP
for($j=0;$j<100;$j++){
?>
<option value="<?PHP echo $j;?>"><?PHP echo $j;?></option>
<?PHP
}//for
?>
</select><br />
<div class="cunas">
<select name="cunas[]">
<option value="NINGUNO">NINGUNO</option>
<?PHP
foreach($cuñas as $cuña){
if($cuña == '.' || $cuña == '..'){continue;}
$info = pathinfo($cuña);
$name = $info['basename'];
$algo = explode("/",$cuña);
$nombreCuña = $algo[count($algo)-2];
?>
<option value="<?PHP echo $cuña;?>"><?PHP echo $nombreCuña;?> - <?PHP echo $name;?></option><?PHP
}//for de pisadores/separadores
?>
</select>
<input type="button" value="+" />  - <input type="button" value="-" />
</div>
<br />

Generos: <br />
<?PHP
//Generos
//for($i=1;$i<=count($generos);$i++){
//echo "#".$i;
?>
<div class="generos">
<select name="generos[]">
<option value="">Seleccione</option>
<?PHP
foreach($generos as $genero => $nada){
echo "<option value=\"{$genero}\">{$genero}</option>";
}

?>
</select>
<input type="button" value="+" />  - <input type="button" value="-" />
</div>
<br />
<?PHP
//}
?><br />
<a href="../../formaBusqueda.php" target="_blank">Repetir Discos</a>: <br />
Cada Cuantas Pistas repetir discos: <select name="cadaDiscos">
<option value="1">1</option>
<?PHP
for($i=2;$i<=100;$i++){
echo "<option value=\"{$i}\">{$i}</option>\r\n";
}
?>
</select><br />
<?PHP
//for($i=1;$i<=3;$i++){
?>
<div class="repetiDiscos">
<input name="rutaDisco[]" type="text" size="127" maxlength="255" id="rutaDisco<?PHP $laId=uniqid();echo $laId;?>" onclick="this.value=window.clipboardData.getData('Text')" />
<input type="button" value="+" />  - <input type="button" value="-" />
</div>
<br />
<?PHP
//}//for repite disco
?>
<br />
D&iacute;a Inicio: <input type="text" name="diainicio" id="diainicio" value="<?PHP echo date("d-M-Y");?>" /> D&iacute;a Fin:  <input type="text" name="diafin" value="<?PHP echo date("d-M-Y");?>" id="diafin" /> Nombre de la lista: <input type="text" id="nombreL" name="nombreL" value="ULTRA CROSSOVER" /> Duraci&oacute;n: <input name="duracion" type="text" id="duracion" value="" size="4" />
<br />
Nombre de la lista: <input name="nombreLista" id="nombreLista" type="text" value="" size="60"  />
<br />
Opciones:
<select name="opciones" id="opciones">
  <option value="Prueba">Prueba</option>
  <option value="Guardar" selected="selected">Guardar</option>
</select>
<br />
<input type="submit" value="Iniciar" />
</form>
<script type="text/javascript">
	$(function() {
		$(".separadores").each(function () {
			$(this).children().eq(1).click(function () {
				$(this).parent().after($(this).parent().clone(true));
			});
			$(this).children().eq(2).click(function () {
			if($(".separadores").length==1){
				alert("No me puedes eliminar");
			}
			else
			{
				$(this).parent().remove();
			}
			});
		});
		
		//cunas
		$(".cunas").each(function () {
			$(this).children().eq(1).click(function () {
				$(this).parent().after($(this).parent().clone(true));
			});
			$(this).children().eq(2).click(function () {
			if($(".cunas").length==1){
				alert("No me puedes eliminar");
			}
			else
			{
				$(this).parent().remove();
			}
			});
		});
		//generos
		$(".generos").each(function () {
			$(this).children().eq(1).click(function () {
				$(this).parent().after($(this).parent().clone(true));
			});
			$(this).children().eq(2).click(function () {
			if($(".generos").length==1){
				alert("No me puedes eliminar");
			}
			else
			{
				$(this).parent().remove();
			}
			});
		});
			//repetiDiscos
		$(".repetiDiscos").each(function () {
			$(this).children().eq(1).click(function () {
				$(this).parent().after($(this).parent().clone(true));
			});
			$(this).children().eq(2).click(function () {
			if($(".repetiDiscos").length==1){
				alert("No me puedes eliminar");
			}
			else
			{
				$(this).parent().remove();
			}
			});
		});
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		dateFormat: 'dd/mm/yy', firstDay: 0,
		isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#diainicio,#diafin").datepicker({dateFormat: 'dd-M-yy'});
	$("#nombreLista").click(function () {
		$(this).val($("#diainicio").val()+' '+$("#diafin").val()+' '+$("#nombreL").val()+' '+$("#duracion").val());
	});
	
});
function pegar(obj)
{
   try{
  obj.value=window.clipboardData.getData("Text");
   }
   catch(e){
	   alert(e);
   }
}
</script>
</body>
</html>