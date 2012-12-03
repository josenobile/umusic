<?php require_once('../../Connections/radiocomunicate.php'); ?><?php
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
function seconds2FormatTime($second3){
       
        //print $second3;
        if ($second3==0)
        {
            $h3=0;
        }
        else
        {
            $h3=floor($second3/3600);//find total hours
        }
           
        $remSecond=$second3-($h3*3600);//get remaining seconds
        if ($remSecond==0)
        {
            $m3=0;
        }
        else
        {
            $m3=floor($remSecond/60);// for finding remaining  minutes
        }
           
        $s3=$remSecond-(60*$m3);
       
        if($h3==0)//formating result.
        {
            $h3="00";
        }
        if($m3==0)
        {
            $m3="00";
        }
        if($s3==0)
        {
            $s3="00";
        }
           
        return "$h3:$m3:$s3";
}//Function seconds2FormatTime
mysql_select_db($database_radiocomunicate, $radiocomunicate);
$query_Recordset1 = "SELECT * FROM hacerlist_audiofiles";
$Recordset1 = mysql_query($query_Recordset1, $radiocomunicate) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>HacerList: Reportes</title>
</head>

<body>

<?PHP
$musica = array();
?>
<?php do { ?>
<?PHP
$musica[] = array('RUTA'=>$row_Recordset1['path'],'TITULO'=>$row_Recordset1['titulo'],'ARTISTA'=>$row_Recordset1['artista'],'GENERO'=>$row_Recordset1['genero'],'LONGITUD'=>$row_Recordset1['longitud']);
?>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
<pre>
<?php
$generos = array();
$artistas = array();
$temporal = array();
foreach($musica as $pista){
//if(strlen($pista['ARTISTA'])<1 || strlen($pista['TITULO'])<1){
//echo $pista['RUTA']."<br />";
//print_r($pista);
//}
//echo $pista['ARTISTA']." ".$pista['TITULO']." ".$pista['GENERO']." ".$pista['LONGITUD']." <br />";
//Contar musica por genero y artista
//Detectar artistas en forma avanzada
//SE HACEN VARIOS FOREACH, UNO PARA BUSCAR EL DEL BUCLE ACTUAL EN EL ARRAY QUE YA ESTÁ GUARDADO
//OTRO PARA BUSCAR CADA UNO DE LOS QUE ESTÁN GUARDADOS EN EL ARRAY EN EL ACTUAL
//SI NINGUNA DE LAS BUSQUEDAS ES EXITOSA, ENTONCES EL ARTISTA ES EL DE LA ITERACCIÓN ACTUAL

//Verificar si lo que voy a buscar ya está en el array
/*if(isset($temporal[$pista['ARTISTA']])===TRUE){
//Todo OK
}
else{
$listo = false;
//1er Foreach
foreach($temporal as $artista => $algo){
if($listo !== TRUE){
if($artista!=$pista['ARTISTA'] &&@strpos($artista,$pista['ARTISTA'])!==FALSE){
echo $pista['ARTISTA']." -> ".$artista."\r\n";
$realArtista = $pista['ARTISTA'];
$temporal[$realArtista] = true;
$listo = true;
}//if de duos
}//if de todavía no se ha encontrado
}//1er foreach


//2do foreach
if($listo !== TRUE){
foreach($temporal as $artista => $algo){
if($listo !== TRUE){
if($artista!=$pista['ARTISTA'] &&@strpos($pista['ARTISTA'],$artista)!==FALSE){
echo $artista." -> ".$pista['ARTISTA']."\r\n";
$realArtista = $artista;
$temporal[$realArtista] = true;
$listo = true;
}//if de duos
}//if de todavía no se ha encontrado
}//1er foreach
}//IF de todavía no se ha encontrado
//si no tuvo exito entonces...
if($listo === FALSE){
$realArtista = $pista['ARTISTA'];
$temporal[$realArtista] = true;
}

}//if de ya no existe
*/

$realArtista = $pista['ARTISTA'];
//ARTISTA
@$artistas[$realArtista]['PISTAS']++;
@$artistas[$realArtista]['TIEMPO'] = $pista['LONGITUD']+@$artistas[$realArtista]['TIEMPO'];
//GENERO
@$generos[$pista['GENERO']]['PISTAS']++;
@$generos[$pista['GENERO']]['TIEMPO'] = $pista['LONGITUD']+@$generos[$pista['GENERO']]['TIEMPO'];

if(intval($pista['LONGITUD'])<1){echo "NO SE SUMÓ LA DURACI&Oacute;N DE: ".$pista['RUTA']."\r\n";}
}//foreach
/*print_r($generos);
print_r($artistas);*/
?>  
 </pre> 
<table cellpadding="1" cellspacing="1" border="1">
<tr><td>GENERO</td><td>#</td><td>Tiempo</td></tr>
<?PHP
foreach($generos as $genero => $datos){
?>
<tr>
<td><a href="pistasPorGenero.php?genero=<?PHP echo $genero;?>" target="_blank"><?PHP echo $genero;?></a></td>
<td><?PHP echo $datos['PISTAS'];?></td>
<td><?PHP echo seconds2FormatTime($datos['TIEMPO']);?></td>
</tr>
<?PHP
}
?>
</table>
<?PHP
/*
?>
<table cellpadding="1" cellspacing="1" border="0">
<tr><td>ARTISTA</td><td>#</td><td>Tiempo</td></tr>
<?PHP
foreach($artistas as $astista => $datos){
?>
<tr>
<td><?PHP echo $astista;?></td>
<td><?PHP echo $datos['PISTAS'];?></td>
<td><?PHP echo $datos['TIEMPO'];?></td>
</tr>
<?PHP
}
?>
</table>
<?PHP
*/
?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
