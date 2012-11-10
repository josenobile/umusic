<?PHP
class AreaController {
	private $area;
	private $aParams;
	private $motorDePlantilas;
	public function AreaController(sfTemplateEngine &$engine) {
		$this->area = new AreaModel ();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
	}
	public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->area->getPager ( array (
					"idArea",
					"Codigo",
					"Area",
					"Descripcion" 
			) )->getJSON () );
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar ( $_POST ["idArea"] );
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["area"] ) );
		}
		$this->consultar ();
		$this->mostarPlantilla ();
	}
	private function guardar($id) {
		$this->area->cargarPorId ( $id );
		$this->area->setValues ( $_POST );
		$this->area->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->area->getId (),
				"id" => $this->area->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->area->cargarPorId ( $id );
		$this->aParams ["area"] = array (
				"idArea" => $this->area->getId (),
				"area" => $this->area->getArea (),
				"codigo" => $this->area->getCodigo (),
				"descripcion" => $this->area->getDescripcion () 
		)
		;
	}
	private function eliminar($id) {
		$this->area->cargarPorId ( $id );
		$this->area->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function consultar() {
		$this->aParams ["areas"] = array ();
		$areas = $this->area->listarObj ();
		foreach ( $areas as $area ) {
			$this->aParams ["areas"] [] = array (
					"idArea" => $area->getId (),
					"area" => $area->getArea (),
					"codigo" => $area->getCodigo (),
					"descripcion" => $area->getDescripcion () 
			);
		}
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "area", $this->aParams );
	}
}
?>