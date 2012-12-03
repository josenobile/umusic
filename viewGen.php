<?PHP
mb_internal_encoding("UTF-8");
// Controller Generator
require_once "lib/DBNative.php";
// require_once 'frontend_tpl_conf.php';
$fileConf = realpath ( dirname ( __FILE__ ) ) . "/config/databases.ini";
if (file_exists ( $fileConf ) && is_readable ( $fileConf )) {
	$aSettings = parse_ini_file ( $fileConf, true );
	define ( "DB_SERVER", $aSettings ["remote_database"] ["server"] );
	define ( "DB_NAME", $aSettings ["remote_database"] ["name"] );
	define ( "DB_USER", $aSettings ["remote_database"] ["user"] );
	define ( "DB_PASS", $aSettings ["remote_database"] ["password"] );
	define ( "DB_SERVER_LOCAL", $aSettings ["local_database"] ["server"] );
	define ( "DB_NAME_LOCAL", $aSettings ["local_database"] ["name"] );
	define ( "DB_USER_LOCAL", $aSettings ["local_database"] ["user"] );
	define ( "DB_PASS_LOCAL", $aSettings ["local_database"] ["password"] );
} else {
	die ( "File configuration was not found!" );
}
if (in_array ( $_SERVER ['SERVER_ADDR'], array (
		"127.0.0.1",
		"localhost",
		"192.168.0.117",
		"192.168.0.128",
		"192.168.1.103",
		"192.168.1.104" 
) )) {
	$mode = "local";
	define ( "DSN", "mysql://" . DB_USER_LOCAL . ":" . DB_PASS_LOCAL . "@" . DB_SERVER_LOCAL . "/" . DB_NAME_LOCAL );
} else {
	$mode = "remote";
	define ( "DSN", "mysql://" . DB_USER . ":" . DB_PASS . "@" . DB_SERVER . "/" . DB_NAME );
}

$camposNoFuncionales = array(
	"ip_address",
	"owner_user_id",
	"updater_user_id",
	"created_at",
	"updated_at",
);

$con = DBNative::get ( DSN );
$utf8 = DBNative::get ()->query ( "SET NAMES 'utf8'" );
$utf81 = DBNative::get ()->query ( "SET character_set_results = 'utf8'" );
$utf82 = DBNative::get ()->query ( "SET character_set_client = 'utf8'" );
$utf83 = DBNative::get ()->query ( "SET character_set_connection = 'utf8'" );
$utf84 = DBNative::get ()->query ( "SET character_set_database = 'utf8'" );
$utf85 = DBNative::get ()->query ( "SET character_set_server = 'utf8'" );
$inicio = microtime ( true );
function printCode($source_code) {
	if (is_array ( $source_code ))
		return false;
	
	$source_code = explode ( "\n", str_replace ( array (
			"\r\n",
			"\r" 
	), "\n", $source_code ) );
	$line_count = 1;
	$formatted_code = '';
	foreach ( $source_code as $code_line ) {
		$formatted_code .= '<tr><td>' . $line_count . '</td>';
		$line_count ++;
		
		if (preg_match ( '/<\?(php)?[^[:graph:]]/', $code_line ))
			$formatted_code .= '<td>' . str_replace ( array (
					'<code>',
					'</code>' 
			), '', highlight_string ( $code_line, true ) ) . '</td></tr>';
		else
			$formatted_code .= '<td>' . @ereg_replace ( '(&lt;\?php&nbsp;)+', '', str_replace ( array (
					'<code>',
					'</code>' 
			), '', highlight_string ( '<?php ' . $code_line, true ) ) ) . '</td></tr>';
	}
	
	return '<table style="font: 1em Consolas, \'andale mono\', \'monotype.com\', \'lucida console\', monospace;">' . $formatted_code . '</table>';
}

$tablas = $con->query ( "SHOW TABLES" );
$contenidos = array ();
$path = $con->getDatabaseName ();
foreach ( $tablas as $tabla ) {
	$tabla = $tabla ["Tables_in_" . $con->getDatabaseName ()];
	$campos = $con->query ( "DESCRIBE $tabla" );
	// Poner en un array los campos pulpitos
	$camposP = array ();
	$primarias = array ();
	foreach ( $campos as $campo ) {
		if ($campo ["Key"] != "PRI")
			$camposP [] = $campo ["Field"];
		else {
			$primarias [] = $campo ["Field"];
			$primary = $campo ["Field"];
		}
	}
	if (empty ( $primary )) {
		echo "Saltando tabla {$tabla}: No tiene una clave primaria<br />";
		continue;
	}
	if (count ( $primarias ) > 1) {
		echo "Saltando tabla {$tabla}: Contiene " . count ( $primarias ) . " campos (" . implode ( ", ", $primarias ) . ") como clave primaria 
		<a href='http://trac.propelorm.org/ticket/359'>Propel</a> - 
		<a href='http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/tutorials/composite-primary-keys.html'>Doctrine</a><br />";
		continue;
	}
	// Inteligencia artificial
	$create = $con->query ( "SHOW CREATE TABLE `$tabla`" );
	if (! isset ( $create [0] ["Create Table"] )) {
		echo "Saltando $tabla por que no es un base tabla<br />";
		continue;
	}
	$lineas = explode ( "\n", $create [0] ["Create Table"] );
	$foraneas = array ();
	// echo "<pre>".print_r($lineas,true)."</pre>";
	foreach ( $lineas as $linea ) {
		if (strpos ( $linea, "CONSTRAINT" ) !== false) 		// posicion cero
		{
			// Parsear
			$pos = strpos ( $linea, "FOREIGN KEY (`" ) + 14;
			$tmp = substr ( $linea, $pos );
			$pos = strpos ( $tmp, "`) REFERENCES `" );
			$campo = substr ( $tmp, 0, $pos ); // Listo el campo
			                                   // echo $campo." --> ";
			$tmp = substr ( $tmp, $pos + 15 );
			
			$pos = strpos ( $tmp, "` (`" );
			$tablatmp = substr ( $tmp, 0, $pos ); // Lista la tabla a la que
			                                      // referencia
			                                      // echo $tabla.".";
			$campor = substr ( $tmp, $pos + 4, strpos ( $tmp, "`) " ) - ($pos + 4) );
			// echo $campor."<br />";
			$foraneas [$campo] = array (
					"tabla" => $tablatmp,
					"campo" => $campor 
			);
		}
	}
	
	ob_start ();
	$clase = str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $tabla ) ) );
	$titulo = ucwords ( str_replace ( "_", " ", $tabla ) );
	$objeto = lcfirst ( str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $tabla ) ) ) );
	echo "\r\n";
	?>$this->extend ( 'layout' );
$this->javascripts->add ( '<?PHP echo $path;?>/web/javascript/<?PHP echo $objeto;?>.js' );
echo @$msg;
?><h1><?PHP echo $titulo;?></h1>
<input type="button" id="mostrarForm<?PHP echo $clase;?>" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formulario<?PHP echo $clase;?>">
	<input type="hidden" name="<?PHP echo $primary;?>"		value="" />
	<table width="100%" border="0">
    <?PHP	foreach ( $campos as $campo ) {
		if(in_array($campo["Field"],$camposNoFuncionales))
			continue;
		if($campo["Field"]==$primary)
			continue;	
		$nombreAutoComplete =  ucwords ( str_replace ( "_", " ", $foraneas[$campo["Field"]]["tabla"] ) );
		?><tr><?PHP
		if(isset($foraneas[$campo["Field"]])){
		?>				
			<td><?PHP echo ucwords(str_replace("_"," ",$campo["Field"]));?></td>
			<td>
            	<input type="hidden" name="<?PHP echo $campo["Field"];?>" id="<?PHP echo $campo["Field"];?>"	value="" />
	            <input type="text" name="<?PHP echo $nombreAutoComplete;?>Autocompletar" id="<?PHP echo $nombreAutoComplete;?>Autocompletar" value="" />
            </td>
		
       <?PHP 
		}else{
			?>
            <td><?PHP echo ucwords(str_replace("_"," ",$campo["Field"]));?></td>
			<td><input type="<?PHP if($campo["Field"] == "contraseña") echo "password";else "text"; ?>" name="<?PHP echo $campo["Field"];if($campo["Field"] == "password") echo "New";?>" id="<?PHP echo $campo["Field"];if($campo["Field"] == "password") echo "New";?>"	value="" /></td>
            <?PHP
		}?>
        </tr>
        <?PHP
		}	
	   
	   ?><tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="t<?PHP echo $tabla;?>">
	<thead>
		<tr>   <?PHP	foreach ( $campos as $campo ) {
		if(in_array($campo["Field"],$camposNoFuncionales))
				continue;
		$titulo = ucwords ( str_replace ( "_", " ", $campo["Field"] ) );
		?>
			<th><?PHP echo $titulo;?></th>
            <?PHP
		}
			?>
			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   <?PHP	foreach ( $campos as $campo ) {
		if(in_array($campo["Field"],$camposNoFuncionales))
				continue;
		$titulo = ucwords ( str_replace ( "_", " ", $campo["Field"] ) );
		?>
			<th><?PHP echo $titulo;?></th>
            <?PHP
		}
			?>
			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table><?PHP
	$contenido = ob_get_contents ();
	ob_end_clean ();
	$contenidos [$clase] = $contenido;
}
$lineas = 0;
foreach ( $contenidos as $clase => $codigo ) {
	echo "<h2>{$clase} UI</h2>";
	if (class_exists ( $clase )) {
		echo "Controlador {$clase} UI ya existe, saltando<br />";
		continue;
	}
	$objeto = lcfirst ( str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $clase ) ) ) );
	//$resp = eval ( $codigo );
	//if ($resp === false)
	//	die ( "Error al compilar el codigo, el codigo fue <br /><pre>" . printCode ( "<?PHP " . $codigo ) . "</pre>" );
	$codigo = "<" . "?" . "PHP" . "" . $codigo;
	$ruta = "web/templates/{$objeto}.php";
	file_put_contents ( $ruta, $codigo ) or die ( "Error al grabar $ruta" );
	chmod ( $ruta, 0777 );
	if(is_readable($ruta) === TRUE)
		echo "Guardado $ruta <br />";
	$lineas += count ( explode ( "\n", $codigo ) );
	//echo "<pre>".htmlentities($codigo,ENT_COMPAT,"UTF-8")."</pre>";
	echo printCode (  $codigo );
	echo "<br />";
}
$fin = microtime ( true );
$total = $fin - $inicio;
echo "$lineas lineas generadas<br />";
echo "hecho en $total segundos";
?>