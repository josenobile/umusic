<?PHP
class GeneroController{
	private $genero;
	private $aParams;
	private $motorDePlantilas;
	public function GeneroController(sfTemplateEngine &$engine) {
		$this->genero = new GeneroModel();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->genero->getPager ( )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this->genero->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this->genero->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this->genero->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["genero"] ) );
		}		
		$this->mostarPlantilla ();
	}
	private function guardar() {
		$this->genero->cargarPorId($_POST [$genero->getNombreId()]);
		$this->genero->setValues ( $_POST );		
		if(isset($_POST ["passwordNew"]) && $_POST ["passwordNew"]!="")
			$this->genero->setPassword(md5($_POST ["passwordNew"]));
		$this->genero->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->genero->getId (),
				"id" => $this->genero->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->genero->cargarPorId ( $id );
		$this->aParams ["genero"] = array (
				"idGenero" => $this->genero->getIdGenero(),
				"nombre" => $this->genero->getNombre(),
		)
		;
	}
	private function eliminar($id) {
		$this->genero->cargarPorId ( $id );
		$this->genero->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "genero", $this->aParams );
	}
}
?>