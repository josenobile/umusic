<?PHP
class AlbumController{
	private $album;
	private $aParams;
	private $motorDePlantilas;
	public function AlbumController(sfTemplateEngine &$engine) {
		$this->album = new AlbumModel();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->album->getPager ( )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this->album->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this->album->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this->album->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["album"] ) );
		}		
		$this->mostarPlantilla ();
	}
	private function guardar() {
		$this->album->cargarPorId($_POST [$album->getNombreId()]);
		$this->album->setValues ( $_POST );		
		if(isset($_POST ["passwordNew"]) && $_POST ["passwordNew"]!="")
			$this->album->setPassword(md5($_POST ["passwordNew"]));
		$this->album->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->album->getId (),
				"id" => $this->album->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->album->cargarPorId ( $id );
		$this->aParams ["album"] = array (
				"idAlbum" => $this->album->getIdAlbum(),
				"nombre" => $this->album->getNombre(),
				"Artista_idArtista" => $this->album->getArtistaIdArtista(),
				"artistaAutocompletar" => $this->album->getArtista()->_getNombreSignificativo(),
		)
		;
	}
	private function eliminar($id) {
		$this->album->cargarPorId ( $id );
		$this->album->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "album", $this->aParams );
	}
}
?>