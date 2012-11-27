<?PHP
class CancionController{
	private $cancion;
	private $aParams;
	private $motorDePlantilas;
	public function CancionController(sfTemplateEngine &$engine) {
		$this->cancion = new CancionModel();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->cancion->getPager (array(
				   			"Nombre",
            			"Duracion",
            			"Contenido Binario",
            			"Mime",
            			"Tamaño Bytes",
            			"Album IdAlbum",
            			"Genero IdGenero",
            			"Usuario IdUsuario",
            			) )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this->cancion->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this->cancion->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this->cancion->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["cancion"] ) );
		}		
		$this->mostarPlantilla ();
	}
	private function guardar() {
		$this->cancion->cargarPorId($_POST [$this->cancion->getNombreId()]);
		$this->cancion->setValues ( $_POST );		
		if(isset($_POST ["contraseñaNew"]) && $_POST ["contraseñaNew"]!="")
			$this->cancion->setContraseña(md5($_POST ["contraseñaNew"]));
		$this->cancion->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->cancion->getId (),
				"id" => $this->cancion->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->cancion->cargarPorId ( $id );
		$this->aParams ["cancion"] = array (
				"idCancion" => $this->cancion->getIdCancion(),
				"nombre" => $this->cancion->getNombre(),
				"duracion" => $this->cancion->getDuracion(),
				"contenido_binario" => $this->cancion->getContenidoBinario(),
				"mime" => $this->cancion->getMime(),
				"tamaño_bytes" => $this->cancion->getTamañoBytes(),
				"Album_idAlbum" => $this->cancion->getAlbumIdAlbum(),
				"Genero_idGenero" => $this->cancion->getGeneroIdGenero(),
				"Usuario_idUsuario" => $this->cancion->getUsuarioIdUsuario(),
				"albumAutocompletar" => $this->cancion->getAlbum()->_getNombreSignificativo(),
				"generoAutocompletar" => $this->cancion->getGenero()->_getNombreSignificativo(),
				"usuarioAutocompletar" => $this->cancion->getUsuario()->_getNombreSignificativo(),
		)
		;
	}
	private function eliminar($id) {
		$this->cancion->cargarPorId ( $id );
		$this->cancion->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "cancion", $this->aParams );
	}
}
?>