<?PHP
set_time_limit(30);
$inicio = microtime(true);
require "conexion.php";
function generarString($longitud,$tipo){
	$caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$largoCaracteres = strlen($caracteres);
	$numeros = "0123456789";
	$largoNumeros = strlen($numeros);
	$return = "";
	if($tipo=="alfabetico")
		for($i=0;$i<$longitud;$i++)
			$return .= $caracteres{mt_rand(0,$largoCaracteres-1)};
	if($tipo=="numerico")
		for($i=0;$i<$longitud;$i++)
			$return .= $numeros{mt_rand(0,$largoNumeros-1)};
	if($tipo=="alfanumerico"){
		$tipos = array("alfabetico","numerico");
		for($i=0;$i<$longitud;$i++)
			$return .= generarString(1,$tipos[mt_rand(0,1)]);
	}
	if($tipo=="fechahora")
		$return = date("Y-m-d H:i:s",mt_rand(0,mt_getrandmax()));
	return $return;
}
/*echo "String alfabetico=".generarString(30,"alfabetico")."<br />";
echo "String numerico=".generarString(30,"numerico")."<br />";
echo "String alfabetico=".generarString(30,"alfanumerico")."<br />";
echo "String fechahora=".generarString(0,"fechahora")."<br />";*/
$q = "INSERT INTO `registro_viajes` (
	  `documento_pasajero` ,
	 `fecha_entrada` ,
	 `minutos_entrada_estacion` ,
	 `nombre_estacion_origen` ,
	 `nombre_estacion_destino` 
	)
	VALUES";
$values = array();
for($i=0;$i<15000;$i++){
	$values[] = 
	 "(".generarString(12,"numerico").",
	 '".generarString(0,"fechahora")."',
	 '".generarString(2,"numerico")."',
	 '".generarString(mt_rand(1,255),"alfanumerico")."',
	 '".generarString(mt_rand(1,255),"alfanumerico")."')";
}
$total = microtime(true)-$inicio;
echo "Se generaron 15000 registros aleatorios para la base de datos en ".(microtime(true)-$inicio)." segundos<br />";
$inicio = microtime(true);
mysql_query($q." ".implode(",",$values),$link);
echo "Se insertaron 15000 registros en la base de datos en ".(microtime(true)-$inicio)." segundos";
?>