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
	$objeto = lcfirst ( str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $tabla ) ) ) );
	echo "\r\n";
	?>class <?PHP echo $clase;?>Controller{
	private $<?PHP echo $objeto;?>;
	private $aParams;
	private $motorDePlantilas;
	public function <?PHP echo $clase;?>Controller(sfTemplateEngine &$engine) {
		$this-><?PHP echo $objeto;?> = new <?PHP echo $clase;?>Model();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this-><?PHP echo $objeto;?>->getPager (array(
				   <?PHP	foreach ( $campos as $campo ) {
		if(in_array($campo["Field"],$camposNoFuncionales))
			continue;
		if($campo["Field"]==$primary)
			continue;
		$titulo = ucwords ( str_replace ( "_", " ", $campo["Field"] ) );
		?>
			"<?PHP echo $titulo;?>",
            <?PHP
		}
			?>
			) )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this-><?PHP echo $objeto;?>->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this-><?PHP echo $objeto;?>->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this-><?PHP echo $objeto;?>->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["<?PHP echo $objeto;?>"] ) );
		}		
		$this->mostarPlantilla ();
	}
	private function guardar() {
		$this-><?PHP echo $objeto;?>->cargarPorId($_POST [$this-><?PHP echo $objeto;?>->getNombreId()]);
		$this-><?PHP echo $objeto;?>->setValues ( $_POST );		
		if(isset($_POST ["contraseñaNew"]) && $_POST ["contraseñaNew"]!="")
			$this-><?PHP echo $objeto;?>->setContraseña(md5($_POST ["contraseñaNew"]));
		$this-><?PHP echo $objeto;?>->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this-><?PHP echo $objeto;?>->getId (),
				"id" => $this-><?PHP echo $objeto;?>->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this-><?PHP echo $objeto;?>->cargarPorId ( $id );
		$this->aParams ["<?PHP echo $objeto;?>"] = array (<?PHP
	echo "\r\n";
	foreach ( $campos as $campo ) {
		if(in_array($campo["Field"],$camposNoFuncionales))
				continue;
		?>
				"<?PHP echo $campo["Field"];?>" => $this-><?PHP echo $objeto;?>->get<?PHP echo str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"])));?>(),<?PHP
		echo "\r\n";
	}
		// Foraneas, autocompletar
		$tablasForaneas = array ();
		//var_dump($foraneas);exit;
		foreach ( $foraneas as $campo => $foranea ) {
			if(in_array($campo,$camposNoFuncionales))
				continue;
			$objetoForaneo = lcfirst ( str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $foranea ["tabla"] ) ) ) );
			$nombreCampo = str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $foranea ["tabla"] ) ) );
			$c = @$tablasForaneas [$nombreCampo];
			@$tablasForaneas [$nombreCampo] += 1;
			if ($c > 0) {
				$nombreCampo .= $c;
			}
			?>
				"<?PHP echo $objetoForaneo;?>Autocompletar" => $this-><?PHP echo $objeto;?>->get<?PHP echo $nombreCampo;?>()->_getNombreSignificativo(),<?PHP
				echo "\r\n";
		}
	?>
		)
		;
	}
	private function eliminar($id) {
		$this-><?PHP echo $objeto;?>->cargarPorId ( $id );
		$this-><?PHP echo $objeto;?>->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "<?PHP echo $objeto;?>", $this->aParams );
	}
}<?PHP
	$contenido = ob_get_contents ();
	ob_end_clean ();
	$contenidos [$clase] = $contenido;
}
$lineas = 0;
foreach ( $contenidos as $clase => $codigo ) {
	echo "<h2>{$clase}Controller</h2>";
	if (class_exists ( $clase )) {
		echo "Controlador {$clase}Controller ya existe, saltando<br />";
		continue;
	}
	$resp = eval ( $codigo );
	if ($resp === false)
		die ( "Error al compilar el codigo, el codigo fue <br /><pre>" . printCode ( "<?PHP " . $codigo ) . "</pre>" );
	$codigo = "<" . "?" . "PHP" . "" . $codigo . "\r\n?" . ">";
	$ruta = "controller/{$clase}Controller.php";
	file_put_contents ( $ruta, $codigo ) or die ( "Error al grabar $ruta" );
	chmod ( $ruta, 0777 );
	echo "Guardado $ruta <br />";
	$lineas += count ( explode ( "\n", $codigo ) );
	// echo "<pre>".htmlentities($codigo,ENT_COMPAT,"UTF-8")."</pre>";
	echo "<br />";
}
$fin = microtime ( true );
$total = $fin - $inicio;
echo "$lineas lineas generadas<br />";
echo "hecho en $total segundos";
?>