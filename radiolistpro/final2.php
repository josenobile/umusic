<?PHP
//ob_start('ob_gzhandler');
require_once('id3/getid3/getid3.php');
require_once('id3/demos/demo.audioinfo.class.php'); 
$opciones = $_POST['opciones'];
function microtime_float()
{
    list($useg, $seg) = explode(" ", microtime());
    return ((float)$useg + (float)$seg);
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
$tiempo_inicio = microtime_float();
ignore_user_abort(FALSE);
set_time_limit(60);//Un minuto es sificiente, para recorrer arrays y comparar cadenas, y unas cuantas consultas de inserción sql sin indices en sus columnas
ini_set("memory_limit","1024M");   
require_once('../../Connections/radiocomunicate.php'); ?><?php

$nombreLista = $_POST['nombreLista'];
$insertSQL = "INSERT INTO listas (nombre) VALUES ('{$nombreLista}')";
$Result1 = mysql_query($insertSQL, $radiocomunicate) or die(mysql_error());
$listaId = mysql_insert_id();
if($listaId<1){
die('Error al grabar la lista en la linea: '.__LINE__);
}
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

$postartista_pistas = "-1";
if (isset($_GET['ARTISTA'])) {
  $postartista_pistas = $_GET['ARTISTA'];
}
mysql_select_db($database_radiocomunicate, $radiocomunicate);
$query_pistas = sprintf("SELECT * FROM hacerlist_audiofiles", GetSQLValueString($postartista_pistas, "text"));
$pistas = mysql_query($query_pistas, $radiocomunicate) or die(mysql_error());
$row_pistas = mysql_fetch_assoc($pistas);
$totalRows_pistas = mysql_num_rows($pistas);
@ob_flush();
$autoKill = array();
$artistas = array();
$titulos = array();
$generos = array();
$hashes = array();
$mainLista = array();
echo "<pre>";
//print_r($_POST);
	foreach($_POST['artistas'] as $genero){
	$k++;
		if(trim($genero) != ''){
		$generos[] = trim($genero);
		echo "Artista #{$k}: {$genero}\r\n";
		}
	}
$artistaOK = array();
$titulosOK = array();
//echo "Realizando algunos calculos iniciales...\r\n";
?>
<?php do { ?>
<?PHP
//$musica[] = array('RUTA'=>$row_pistas['path'],'TITULO'=>$row_pistas['titulo'],'ARTISTA'=>$row_pistas['artista'],'GENERO'=>$row_pistas['genero'],'LONGITUD'=>$row_pistas['longitud']);

ob_end_flush();flush();
$genero = $row_pistas['genero'];
$fileName = $row_pistas['path'];
$titulo = $row_pistas['titulo'];
$artista = $row_pistas['artista'];
$longitud = $row_pistas['longitud'];
$id = $row_pistas['id'];
//Main Lista carge
$mainLista[] = array('TITULO'=>$titulo,'ARTISTA'=>$artista,'GENERO'=>$genero,'FILENAME'=>$fileName,'LONGITUD'=>$longitud,'ID'=>$id);
//
//carga de arrays
//Conteo especial
	/*$sePuede = true;
	if(in_array($genero,$generos)){
	//Usar el mismo criterio de comparacion en las condiciones del generador de la lista
	foreach($artistaOK as $artistaAR){
	if(@stristr($artistaAR,$artista)!== FALSE || @stristr($artista,$artistaAR)!==FALSE){
	$sePuede = false;	
	}
	if(in_array($titulo,$titulos)){
	$sePuede = false;
	}
	}		
	if($sePuede == TRUE){
	$artistaOK[] = $artista;
	}
	}*/
	/*
$artistas[] = strtoupper($artista);
$titulos[] = strtoupper($titulo);
$generosDB[] = strtoupper($genero);*/
//$hashes[] = md5_file($file);
//echo "Archivo: ".$file."\r\n";
//echo "Titulo: ".$titulo."\r\n";
//echo "Artista: ".$artista."\r\n";
//echo "Genero: ".$genero."\r\n\r\n";
?>
<?php } while ($row_pistas = mysql_fetch_assoc($pistas)); ?>
<?PHP
/*$artistas_d = array_unique($artistas);
$titulos_d = array_unique($titulos);
$generos_d = array_unique($generosDB);*/
//print_r($mainLista);
//Inicializo valores
$lista = array();
$k=0;

//print_r($generos);
//echo "Encontre ".count($artistaOK)." Artistas diferentes en los generos solicitados\r\n";
//Organizar por generos
$lGeneros = array();
foreach($mainLista as $archivitos){
//$artista = $archivitos['ARTISTA']; 
$path = $archivitos['FILENAME'];
$ppath = explode("/",$path);
$artista = $ppath[3].'/'.$ppath[4];
$lGeneros[$artista][] = $archivitos;
}
//print_r($lGeneros);

//Recorrer el numero de intervalos dados por post user

//echo "VOY A EMPEZAR MI TRABAJO\r\n";
$j=0;
$dias = intval($_POST['tiempo_dias']);
$horas = intval($_POST['tiempo_horas']);
$minutos = intval($_POST['tiempo_minutos']);
$segundos = intval($_POST['tiempo_segundos']);

$tiempo_segundos = $segundos+$minutos*60+$horas*60*60+$dias*24*60*60;
echo "Voy a realizar una lista de ".$dias." D&iacute;as ".$horas." Horas ".$minutos." Minutos ".$segundos." Segundos\r\n";
echo "Pasando eso a segundos son: ".$tiempo_segundos."\r\n";
echo "</pre>";
?>

<table cellpadding="1" cellspacing="1" border="1">
<tr><td>#</td><td>GENERO<td>ARTISTA</td><td>TITULO</td><td>LENGHT</td></tr>
<?php
//Obtener el tiempo en segundos que debe durar la lista:


$tiempo_total = 0;
$numero_intentos = 20;
/*$nuGeneros = count($generos);
$voyGeneros = 0;*/
while(true){
//Valido si queda musica para buscar, en los generos solicitados
$bueno = false;
foreach($generos as $genero){
if(count($lGeneros[$genero])>0){$bueno=true;}
}//foreach
if($bueno===false){//Si no queda ni uno bueno, pues terminamos
?>
<script type="text/javascript">
alert("Fin no hay mas musica que se pueda poner");
</script>
<?PHP
break;
}
//Fin de validar
foreach($generos as $genero){
$voyGeneros++;

while(true){
if(count($lGeneros[$genero])<1){break 3;}
//echo "<pre>$genero :";echo count($lGeneros[$genero]);echo "</pre>";
//echo "Razon = $razon";
$razon = 'nada';
//if(count($lGeneros[$genero])<1){continue 2;}else{
/*?>
<script type="text/javascript">
alert("Del genero <?PHP echo $genero;?> quedan <?PHP echo count($lGeneros[$genero]);?> pistas");
</script>
<?PHP*/
//}//Siga 
/*?>
<script type="text/javascript">
//alert("Inicio while");
</script>
<?PHP*/
//$lGeneros -> Contiene la musica indizada por generos
$cuenta = count($lGeneros[$genero]);

$aleatorio = mt_rand(0,$cuenta-1);
$posibleMP3 = $lGeneros[$genero][$aleatorio];

//:::::::::::::::VERIFICACIONES:::::::::::::::::::::::
if(strlen($posibleMP3['TITULO'].$posibleMP3['ARTISTA'])<1){
flush();
continue;//No existe el indice
$razon = 'titulo y artista vacios';
}
				//condiciones
				//Verificar titulo diferente
$posibleTitulo = $posibleMP3['TITULO'];
$posibleArtista = $posibleMP3['ARTISTA'];
				//Fin verificar titulo diferente
				$sePuede = TRUE;
				
					foreach($lista as $cancion){
					flush();
						if($cancion['TITULO'] == $posibleTitulo){//repetir o no repetir titulo, ese es el dilema
						$sePuede = FALSE;
						//echo "(N: {$cuenta}) (try: {$cuantos}) (rand: {$aleatorio}) NO agregu&eacute;: ".$posibleMP3['FILENAME']." - TITULO IGUAL A: ".$cancion["FILENAME"]."\r\n";
						unset($lGeneros[$genero][$aleatorio]);//Para que me sirva bien el aleatorio tengo que reindexar todo
						$copyGeneros = $lGeneros[$genero];
						$lGeneros[$genero] = NULL;
						foreach($copyGeneros as $pistasXYZ){
						$lGeneros[$genero][] = $pistasXYZ;
						}
						flush();/*?>
						<script type="text/javascript">
                       // alert("continue 2, en titulo");
                        </script>
                        <?PHP*/
						$razon = 'El posible titulo: $posibleTitulo ya estaba en {$cancion["TITULO"]}';
						continue 2;//Vuelva a intentarlo
						
						}
					}//foreach de lista actual, EN BUSCA DE TITULO
				
				//Verificar que no se halla puesto una cancion del artista en la lista
					foreach($lista as $cancion){
					flush();
					//echo ".";
					//@ob_flush();
						if(false){
						//echo "(N: {$cuenta}) (try: {$cuantos}) (rand: {$aleatorio}) NO agregu&eacute;: ".$posibleMP3['FILENAME']." - ARTISTA IGUAL A: ".$cancion["FILENAME"]."\r\n";
						$sePuede = FALSE;
						unset($pistasGenero[$aleatorio],$lGeneros[$genero][$aleatorio]);
						$copyGeneros = $lGeneros[$genero];
						$lGeneros[$genero] = NULL;
						foreach($copyGeneros as $pistasXYZ){
						$lGeneros[$genero][] = $pistasXYZ;
						}
						flush();
						$razon = 'Lontitudes de artista o titulo, o artista o titulo iguales';
						continue 2;//Vuelva a intentarlo
						}
					}//foreach de lista actual, EN BUSCA DE artista
if($posibleMP3['LONGITUD']<1){$sePuede = FALSE;
						unset($pistasGenero[$aleatorio],$lGeneros[$genero][$aleatorio]);
						$copyGeneros = $lGeneros[$genero];
						$lGeneros[$genero] = NULL;
						foreach($copyGeneros as $pistasXYZ){
						$lGeneros[$genero][] = $pistasXYZ;
						}}
					if($sePuede==TRUE){
					$lista[] = $posibleMP3;//Almaceno la información del mp3 en el array lista
					//Grabo en la base de datos
					 $insertSQL = sprintf("INSERT INTO lista_audio (audioId, listaId) VALUES (%s, %s)",
                       GetSQLValueString($posibleMP3['ID'], "int"),
                       GetSQLValueString($listaId, "int"));
$Result1 = mysql_query($insertSQL, $radiocomunicate) or die(mysql_error());
					//Fin de grabar
					/*unset($lGeneros[$genero][$aleatorio]);//Descartar el MP3, que se uso
					$copyGeneros = $lGeneros[$genero];
						$lGeneros[$genero] = NULL;
						foreach($copyGeneros as $pistasXYZ){
							$lGeneros[$genero][] = $pistasXYZ;
						}*/
					$i++;//Cada ves que encuentra una cancion que si, suma la i
					//
					$j++;
					echo "<tr><td>".$j."</td><td>".$posibleMP3['GENERO']."</td><td>".$posibleMP3['FILENAME']."</td><td>" .$posibleMP3['TITULO']."</td><td>".
					intval($posibleMP3['LONGITUD']/60).":".((($posibleMP3['LONGITUD']/60)-intval($posibleMP3['LONGITUD']/60))*60).
					"</td></tr>\r\n";
					@ob_flush();
					flush();
					$tiempo_total += $posibleMP3['LONGITUD'];
						if($tiempo_total>=$tiempo_segundos){
						flush();
						/*?>
						<script type="text/javascript">
                      //  alert("Break, Finaliza, termino por tiempo cumplido");
                        </script>
                        <?PHP*/
						$razon = 'Se termino la lista satisfactoriamente';

						break 3;//Finaliza
						}
					flush();
					/*?>
					<script type="text/javascript">
                    //alert("continue 2, en para pasar al siguiente genero");
                    </script>
                    <?PHP*/
					$razon = 'se agrego exitosamente el mp3: '.$posibleMP3['FILENAME'];
					continue 2;//Continue para poner la siguiente pista, salta el while, y sigue con el otro genero
					//Pero que pasa, si no he
					}
//:::::::::::::::VERIFICACIONES:::::::::::::::::::::::


//echo "<tr><td>".$j."</td><td>".$posibleMP3['GENERO']."</td><td>".$posibleMP3['ARTISTA']."</td><td>" .$posibleMP3['TITULO']."</td><td>" .$posibleMP3['LONGITUD']."</td></tr>\r\n";


//$hashes_d = array_unique($hashes);
}//while infinito
}//foreach de generos
}//while infinito
?>
</table>
<?PHP
//Poner los pisadores o para el caso de winamp separadores
$cadaPistas = $_POST['cadaPistas'];
//$carpetaPisadores = str_replace("\\","/",$_POST['carpetaPisadores']);
//Leer los pisadores del directorio específicado
//$pisadores = listdir($carpetaPisadores);
$pisadores = $_POST['separadores'];
//Filtrar archivos de musica, aqui hay una expción, el WAV se incluye por que no me importa que no tenga tags, solo que contenga audio y pueda calcular su duración
$pisadoresMatriz = array();
foreach($pisadores as $pisador){
if($pisador == 'NINGUNO'){
continue;
}
$info = pathinfo($pisador);
$ext = strtoupper($info['extension']);
	if("MP3" == $ext || "WMA" == $ext|| "WAV" == $ext){//Windows Media Audio o MP3
	//echo "\r\nArchivo: ".$file."\r\n";
	$au = new AudioInfo();
	$info = $au->Info($pisador);
	$duracion = intval($info["playing_time"]);//Por alguna razón, el winamp y el windows media redondean la duración hacia abajo, por ejemplo hay un pisador que dura 3.7 segundos, pero los reproductores dicen 3 segundos, entonces me ajustó a ellos
	if($duracion<1){continue;}
	$pisadoresMatriz[] = array('RUTA'=>$pisador,'DURACION'=>$duracion);
	}//IF que identifica el tipo de archivo
}//Foreach que filtra música
//Fin filtrar
//Recorrer la lista y añadir los pisadores
//print_r($pisadoresMatriz);
$nuevaLista = array();
$i=0;//Para contar por cual archivo de musica vamos
$j=0;//Para identificar por cual pisador vamos
foreach($lista as $archivoMP3){
$nuevaLista[] = $archivoMP3;
if($i%$cadaPistas == 0){
if(!isset($pisadoresMatriz[$j])){//Se acabo
$j=0;//Reinició la j a al indice cero
}
//echo $j;
//print_r($pisadoresMatriz[$j]);
$nuevaLista[] = array('FILENAME'=>$pisadoresMatriz[$j]['RUTA'],'LONGITUD'=>$pisadoresMatriz[$j]['DURACION']);
$tiempo_total += $pisadoresMatriz[$j]['DURACION'];
$j++;
}//if de verificar si hay que agregar el pisador
$i++;
}//Foreach que recorre la lista
//Fin de recorrer la lista y añadir los pisadores

//Fin de leer los pisadores de directorio específicado
$lista = $nuevaLista;//Actualizo la lista
//Fin de poner separadores

////////////////////
$nuevaLista = array();
$cadaDiscos = $_POST['cadaDiscos'];
$discosMatriz = array();
foreach($_POST['rutaDisco'] as $disco){
$disco = trim($disco);
if(strlen($disco)>0 && file_exists($disco)){
$discosMatriz[] = $disco;
}
}
echo "Se repetiran discos cada {$cadaDiscos} en el siguiente orden: <br /><pre>";
print_r($discosMatriz);
echo '</pre>';
$i=0;//Para contar por cual archivo de musica vamos
$j=0;//Para identificar por cual pisador vamos
foreach($lista as $archivoMP3){
//echo $i."<br />";
//echo "hello: ".$archivoMP3["FILENAME"]."<br />";
$nuevaLista[] = $archivoMP3;
if($i%$cadaDiscos == 0){
//echo "Residuo cero: {$i}".$discosMatriz[$j]."<br />";
if(!isset($discosMatriz[$j])){//Se acabo
//echo "No isset: \$discosMatriz[{$j}]<br />";
$j=0;//Reinició la j a al indice cero

}else{

}
//echo $j;
//print_r($pisadoresMatriz[$j]);
//echo "Se agrego: ".$discosMatriz[$j]."<br />";
//Se que sale más "barato" obtener la duración buscando en la database que usando la clase para obtener la duración, pero menos código hacerlo así.
$au = new AudioInfo();
	$info = $au->Info($discosMatriz[$j]);
	$duracion = intval($info->playing_time);//Por alguna razón, el winamp y el windows media redondean la duración hacia abajo, por ejemplo hay un pisador que dura 3.7 segundos, pero los reproductores dicen 3 segundos, entonces me ajusto a ellos
	
	if($duracion<1){continue;}
$nuevaLista[] = array('FILENAME'=>$discosMatriz[$j],'LONGITUD'=>$duracion);
//print_r( $nuevaLista[count($nuevaLista)-1])."<br />";
$tiempo_total += $duracion;
$j++;
}//if de verificar si hay que agregar el pisador

$i++;
}//Foreach que recorre la lista
//Fin de recorrer la lista y añadir los pisadores

//Fin de leer los pisadores de directorio específicado
$lista = $nuevaLista;//Actualizo la lista
//Fin de poner separadores
///////////////



//INicio de copy and paste de pisadores en cuñas
//Poner los pisadores o para el caso de winamp separadores
$cadaCunas = intval($_POST['cadaCunas']);
echo "Se ponen las cuñas cada {$cadaCunas} pistas\r\n<br />";
//$carpetaPisadores = str_replace("\\","/",$_POST['carpetaPisadores']);
//Leer los pisadores del directorio específicado
//$pisadores = listdir($carpetaPisadores);
$cunas = $_POST['cunas'];
$cunas2 = array();
foreach($cunas as $cuna){
if($cuna == 'NINGUNO'){continue;}
$cunas2[] = $cuna;
}
$cunas = $cunas2;
echo "En el siguiente orden: \r\n<br />";
echo "<pre>";
print_r($cunas);
echo "</pre>";
//Filtrar archivos de musica, aqui hay una expción, el WAV se incluye por que no me importa que no tenga tags, solo que contenga audio y pueda calcular su duración
$cunasMatriz = array();
foreach($cunas as $cuna){
if($cuna == 'NINGUNO'){
continue;
}

$info = pathinfo($cuna);
$ext = strtoupper($info['extension']);
	if("MP3" == $ext || "WMA" == $ext|| "WAV" == $ext){//Windows Media Audio o MP3 o tambien wav, solo para cuñas por que no tengo que validar que se repita.
	//echo "\r\nArchivo: ".$file."\r\n";
	$au = new AudioInfo();
	$info = $au->Info($cuna);
	$duracion = intval($info->playing_time);//Por alguna razón, el winamp y el windows media redondean la duración hacia abajo, por ejemplo hay un pisador que dura 3.7 segundos, pero los reproductores dicen 3 segundos, entonces me ajusto a ellos
	if($duracion<1){continue;}
	$cunasMatriz[] = array('RUTA'=>$cuna,'DURACION'=>$duracion);
	}//IF que identifica el tipo de archivo
}//Foreach que filtra música
//Fin filtrar
//Recorrer la lista y añadir los pisadores
//print_r($pisadoresMatriz);
$nuevaLista = array();
$i=0;//Para contar por cual archivo de musica vamos
$j=0;//Para identificar por cual pisador vamos
foreach($lista as $archivoMP3){

if($cadaCunas!=0 && $i%$cadaCunas == 0){
if(!isset($cunasMatriz[$j])){//Se acabo
$j=0;//Reinició la j a al indice cero
}
//echo $j;
//print_r($pisadoresMatriz[$j]);
$nuevaLista[] = array('FILENAME'=>$cunasMatriz[$j]['RUTA'],'LONGITUD'=>$cunasMatriz[$j]['DURACION']);
$tiempo_total += $cunasMatriz[$j]['DURACION'];
$j++;
}//if de verificar si hay que agregar el pisador
$nuevaLista[] = $archivoMP3;
$i++;
}//Foreach que recorre la lista
//Fin de recorrer la lista y añadir los pisadores

//Fin de leer los pisadores de directorio específicado
$lista = $nuevaLista;//Actualizo la lista
//Fin de poner separadores



//Fin de copy and paste de pisadores en cuñas

//:::::::::::::::::::::::::::::::::::::::::::::::
//Lo siguiente está comentado, por que funciono como se quería, y no se encontró una lógica coherente, que hiciera lo que se quería, esto no se pudo hacer no por programación, sino por lógica comun, o sea no se pudo simular en papel o en otra cosa lo que el siguiente trozo de código se pretendía que hiciera.
/*
//Hay un detalle no se puede hacer igual que en los pisadores, por que me pone una cuña luego la otra cuña, con el cada cuanto de la anterior, y así sucesivamente se vuelve un sancocho
$cada_Cunas = $_POST['cadaCunas'];
//$carpetaPisadores = str_replace("\\","/",$_POST['carpetaPisadores']);
//Leer los pisadores del directorio específicado
//$pisadores = listdir($carpetaPisadores);
$cunaes = $_POST['cunas'];

//Filtrar archivos de musica, aqui hay una expción, el WAV se incluye por que no me importa que no tenga tags, solo que contenga audio y pueda calcular su duración
$cunaesMatriz = array();
foreach($cunaes as $cuna){
$info = pathinfo($cuna);
$ext = strtoupper($info['extension']);
	if("MP3" == $ext || "WMA" == $ext|| "WAV" == $ext){//Windows Media Audio o MP3
	//echo "\r\nArchivo: ".$file."\r\n";
	$au = new AudioInfo();
	$info = $au->Info($cuna);
	$duracion = intval($info->playing_time);//Por alguna razón, el winamp y el windows media redondean la duración hacia abajo, por ejemplo hay un cuna que dura 3.7 segundos, pero los reproductores dicen 3 segundos, entonces me ajustó a ellos
	$cunaesMatriz[] = array('RUTA'=>$cuna,'DURACION'=>$duracion);
	}//IF que identifica el tipo de archivo
}//Foreach que filtra música
//Fin filtrar
//Recorrer la lista y añadir los cunaes
//print_r($cunaesMatriz);
$nuevaLista = array();
$i=0;//Para contar por cual archivo de musica vamos
$j=0;//Para identificar por cual cuna vamos
foreach($cunaesMatriz as $cuna){
$nuevaLista = array();
foreach($lista as $archivoMP3){
$nuevaLista[] = $archivoMP3;
$cadaCunas = $cada_Cunas[$j];
//echo "Cada Cunas: ".$cadaCunas;
if($i%$cadaCunas == 0){

//echo $j;
//print_r($cunaesMatriz[$j]);
$nuevaLista[] = array('FILENAME'=>$cunaesMatriz[$j]['RUTA']);
$tiempo_total += $cunaesMatriz[$j]['DURACION'];

}//if de verificar si hay que agregar el cuna
$i++;
}//Foreach que recorre la lista
//Fin de recorrer la lista y añadir los cunaes
$lista = $nuevaLista;//Actualizo la lista
$j++;
}//foreach que recorre la lista de cuñas
//Fin de leer los cunaes de directorio específicado
$lista = $nuevaLista;//Actualizo la lista
//Fin de poner separadores





*/
//Fin del código que no se pudo escribir, por que no hubo una lógica que hiciera lo que se pretendía

//:::::::::::::::::::::::::::::::::::::::::::::::


//echo "# Artistas: ".count($artistas_d)."\r\n";
//echo "# Titulos: ".count($titulos_d)."\r\n";
//echo "# Generos: ".count($generos_d)."\r\n";
//echo "# Artistas Diferentes: ".count($hashes)."\r\n";
/*
echo "LISTADO DE GENEROS: \r\n";
print_r($generos_d);
echo "LISTADO DE ARTISTAS: \r\n";
print_r($artistas_d);
echo "LISTADO DE TITULOS: \r\n";
print_r($titulos_d);*/
//Procedo a generar la lista meu sin #EXTINF, por que se considera obsoleto, NO PORQUE NO PUEDA LEER LA DURACION DE MP3 O WMA, INCLUSO CON VELOCIDAD DE BIT's VARIABLE, tags ASF, ID3v1, ID3v2 (Todas las versiones)
//var_dump($lGeneros[$genero]);
echo '</pre>';
$m3uFile = '';
$duracion = 0;
$nuevaLista = array();
$tiempo_total =0;
//echo '<pre>';print_r($lista);echo '</pre>';
foreach($lista as $archivoMP3){

if($duracion>=$tiempo_segundos){
continue;
}
if(strlen($archivoMP3['FILENAME'])>0 && !file_exists($archivoMP3['FILENAME'])){

echo 'El siguiente MP3, no se agregara a la lista, no existe, vuelva a <a href="index2.php" target="_blank">escanear</a> los MP3, para actualizar la base de datos: <br />'.$archivoMP3['FILENAME'].'<br />';
}else{
if($archivoMP3['LONGITUD']<1){
/*echo '<pre>';
print_r($archivoMP3);
echo '</pre>';*/
}
$nuevaLista[] = $archivoMP3;
$duracion += $archivoMP3['LONGITUD'];
//echo "Duracion: {$duracion}+{$archivoMP3[LONGITUD]}={$archivoMP3['FILENAME']}<br />";
$m3uFile .= str_replace("/","\\",$archivoMP3['FILENAME'])."\r\n";
//echo $archivoMP3['FILENAME']."<br />";
}
}
$lista = $nuevaLista;
$tiempo_total = $duracion;
echo "# La lista (".$nombreLista.") se genero con: ".count($lista)." pistas que duran ".seconds2FormatTime($tiempo_total)." , se solicitaron: ".seconds2FormatTime($tiempo_segundos)."<br />\r\n";
if($tiempo_total<$tiempo_segundos){
echo "Faltaron: ".seconds2FormatTime(($tiempo_segundos-$tiempo_total))." para completar el tiempo<br />\r\n";
}else{
echo "Se pasaron: ".seconds2FormatTime(($tiempo_total-$tiempo_segundos))." segundos del tiempo solicitado<br />\r\n";
}
$tiempo_final = microtime_float();
$tiempo_usado = $tiempo_final-$tiempo_inicio;
echo "La lista tardo ".$tiempo_usado." segundos en hacerse<br />\r\n";

if($opciones != 'Prueba'){
$dirM3U = '\\\\Radiocomunicate\LISTA DE REPRODUCCION RADIOCOMUNICATE\\';
//$nombreLista = 'ULTRA CROSSOVER';
$ruta = $dirM3U.$nombreLista.'.m3u';
if(file_exists($ruta)){
echo 'El archivo de lista de reproduccion ya existe: <br />'.$ruta.'<br />';
$nuevoFile = $ruta.'-'.time().'.m3u';
copy($ruta,$nuevoFile);
echo 'La lista vieja fue copiada por seguridad a: <br />'.$nuevoFile.'<br />';
}
touch($ruta);
file_put_contents($ruta,$m3uFile);
echo 'La lista fue guardada en: <br />'.$ruta.'<br />';
//echo 'Se puede abrir por red pulsando <a href="file:///\\Radiocomunicate\LISTA DE REPRODUCCION RADIOCOMUNICATE\\'.$nombreLista.'.m3u'.'">aqui</a><br />';
}//if de es una prueba
//echo '<pre>'.$m3uFile.'</pre>';
$i=0;
foreach($lista as $mp3){
$i++;
echo $i." - ".str_replace("//Radiocomunicate/C/wamp/radiocomunicate.com/RADIOCOMUNICATE OK/","",str_replace("//Radiocomunicate/C/wamp/radiocomunicate.com/RADIOCOMUNICATE OK/MUSITECA/","",$mp3['FILENAME']))."<br />\r\n<br />\r\n";
}

?>