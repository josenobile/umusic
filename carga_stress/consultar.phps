<?PHP
set_time_limit(15000);
require "conexion.php";
ob_start("ob_gzhandler");
header("Content-Type: text/plain");
echo "id,documento_pasajero,fecha_entrada,minutos_entrada_estacion,nombre_estacion_origen,nombre_estacion_destino";
$r = mysql_unbuffered_query("SELECT * FROM registro_viajes LIMIT 15000",$link);
while($row=mysql_fetch_assoc($r)){
	echo "{$row["id"]},{$row["documento_pasajero"]},{$row["fecha_entrada"]},{$row["minutos_entrada_estacion"]},{$row["nombre_estacion_origen"]},{$row["nombre_estacion_destino"]}\r\n";
	flush();
	ob_flush();
	usleep((1/16)*1000000);//hacer una pausa para que salgan de uno en uno
}
?>