<?PHP
class ArtistaController{
	private $artista;
	private $aParams;
	private $motorDePlantilas;
	public function ArtistaController(sfTemplateEngine &$engine) {
		$this->artista = new ArtistaModel();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->artista->getPager ( )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this->artista->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this->artista->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this->artista->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["artista"] ) );
		}		
		$this->mostarPlantilla ();
	}
	private function guardar() {
		$this->artista->cargarPorId($_POST [$artista->getNombreId()]);
		$this->artista->setValues ( $_POST );		
		if(isset($_POST ["passwordNew"]) && $_POST ["passwordNew"]!="")
			$this->artista->setPassword(md5($_POST ["passwordNew"]));
		$this->artista->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->artista->getId (),
				"id" => $this->artista->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->artista->cargarPorId ( $id );
		$this->aParams ["artista"] = array (
				"idArtista" => $this->artista->getIdArtista(),
				"nombre" => $this->artista->getNombre(),
		)
		;
	}
	private function eliminar($id) {
		$this->artista->cargarPorId ( $id );
		$this->artista->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "artista", $this->aParams );
	}
}
?>