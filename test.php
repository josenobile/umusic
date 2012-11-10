<?PHP
$q = "SELECT idUsuario, Nombre, Apellido, Email, Estado
			FROM   (SELECT idUsuario, nombre as Nombre, apellido as Apellido, email as Email, estado AS Estado FROM Usuario WHERE 1) a
			
			ORDER BY  Estado
						desc
";
require_once 'frontend_tpl_conf.php';echo "<pre>";print_r($con->query($q));echo "</pre>";
$link = mysql_connect("localhost","root","");
mysql_select_db("umusic",$link);

$r = mysql_unbuffered_query($q,$link) or die(mysql_error($link));
echo "<pre>";print_r(mysql_fetch_assoc($r));echo "</pre>";

?>