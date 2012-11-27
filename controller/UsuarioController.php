<?PHP
class UsuarioController{
	private $usuario;
	private $aParams;
	private $motorDePlantilas;
	public function UsuarioController(sfTemplateEngine &$engine) {
		$this->usuario = new UsuarioModel();
		$this->aParams = Array ();
		$this->motorDePlantilas = $engine;
		
	}
    public function manejadorDeAcciones() {
		if (@$_REQUEST ['sEcho'] != "") {
			die ( $this->usuario->getPager ( )->getJSON () );
		}
		if(isset($_REQUEST["format"])){
			$_POST["html"] = $this->motorDePlantilas->render ( strtolower($_REQUEST['format']),array("data"=>$this->usuario->getAll()) );
			require "generaFormat.php";
		}
		if (@$_REQUEST ["autoCompleteTerm"] != "") {
                    die(json_encode($this->usuario->listar(array($_REQUEST ["autoCompleteTerm"] => $_GET ['q']), $_REQUEST ["autoCompleteTerm"], "0," . $_REQUEST["limit"], false, $fields = $_REQUEST ["autoCompleteTerm"].", ".$this->usuario->getNombreId())));
		}
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			$this->guardar();
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
	private function guardar() {
		$this->usuario->cargarPorId($_POST [$usuario->getNombreId()]);
		$this->usuario->setValues ( $_POST );		
		if(isset($_POST ["passwordNew"]) && $_POST ["passwordNew"]!="")
			$this->usuario->setPassword(md5($_POST ["passwordNew"]));
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
				"idUsuario" => $this->usuario->getIdUsuario(),
				"nombre" => $this->usuario->getNombre(),
				"apellido" => $this->usuario->getApellido(),
				"email" => $this->usuario->getEmail(),
				"contraseña" => $this->usuario->getContraseña(),
				"estado" => $this->usuario->getEstado(),
				"sesion_activa" => $this->usuario->getSesionActiva(),
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