<?PHP
class UsuarioController {
	private $usuario;
	private $aParams;
	private $motorDePlantilas;
	public function UsuarioController(sfTemplateEngine &$engine) {
		$this->usuario = new UsuarioModel ();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
	}
	public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->usuario->getPager ( array (
				"idUsuario",
				"Nombre",
				"Apellido",
				"Email",
				"Estado"
			) )->getJSON () );
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar ( $_POST ["idUsuario"] );
		}
		if (@$_GET ["accion"] == "eliminar" && $_GET ["id"] > 0) {
			$this->eliminar ( intval ( $_GET ["id"] ) );
		}
		if (@$_GET ["accion"] == "editar" && $_GET ["id"] > 0) {
			$this->cargarPorId ( intval ( $_GET ["id"] ) );
			die ( json_encode ( $this->aParams ["usuario"] ) );
		}
		$this->mostarPlantilla ();
	}
	private function guardar($id) {
		$this->usuario->cargarPorId ( $id );
		$this->usuario->setValues ( $_POST );
		if($this->usuario->getEstado()=='')
			$this->usuario->setEstado(1);
		if($this->usuario->getSesionActiva()=="")
			$this->usuario->setSesionActiva(0);
		$this->usuario->setContraseña(md5($this->usuario->getContraseña()));
		$this->usuario->save ();
		$resp = json_encode ( array (
				"msg" => "El registro fue grabado. ID=" . $this->usuario->getId (),
				"id" => $this->usuario->getId () 
		) );
		die ( $resp );
	}
	public function cargarPorId($id) {
		$this->usuario->cargarPorId ( $id );
		$this->aParams ["usuario"] = array (
				"idUsuario" => $this->usuario->getId (),
				"nombre" => $this->usuario->getNombre (),
				"apellido" => $this->usuario->getApellido (),
				"email" => $this->usuario->getEmail (),
				"contraseña" => $this->usuario->getContraseña (),
				"estado" => $this->usuario->getEstado (),
				"sesionActiva" => $this->usuario->getSesionActiva ()
		)
		;
	}
	private function eliminar($id) {
		$this->usuario->cargarPorId ( $id );
		$this->usuario->eliminar ();
		$this->aParams ["message"] = "El registro fue eliminado";
		$resp = json_encode ( array (
				"msg" => $this->aParams ["message"] 
		) );
		die ( $resp );
	}
	private function mostarPlantilla() {
		echo $this->motorDePlantilas->render ( "usuario", $this->aParams );
	}
}
?>