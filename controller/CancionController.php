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
						"idCancion",
				   		"Nombre",
						"Nombre",
            			"Duracion",
            			"Mime",
            			"TamañoBytes",
            			"Album",
            			"Genero",
            			"Usuario",
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
			if(isset ($_POST['leerTags'])){
				$msg="";
				if(@$_FILES["cancion"]["size"]>0){
					$au = new AudioInfo();
					$contenidoBin = $_FILES["cancion"]["tmp_name"];
					$info = $au->Info($contenidoBin);
					if(!isset($info["error"])){
						$duracion = $info["playing_time"];
						$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v2"]['title'])));//idv3v2
						$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v2"]['artist'])));//idv3v2
						if($titulo == "")
							$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['title'])));//asf
						if($artista == "")
							$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['artist'])));//asf
						
						if($titulo == "")
							$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v1"]['title'])));//idv3v1
						if($artista == "")
							$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v1"]['artist'])));//idv3v1	
							
						if($artista == "")
							$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['albumartist'])));//asf
							
						$msg = "<pre>".print_r($info["tags"],true)."</pre>";
					}else{ 
						$msg = @$info["error"];
					}
					
				}else{
					$msg="Debe seleccionar un archivo a evaluar";
				}
				$resp = json_encode($msg);
				die ( $resp );
			}else{
				$this->guardar();
			}
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
		global $session;
		$msg="";
		$this->cancion->cargarPorId($_POST [$this->cancion->getNombreId()]);
		$this->cancion->setValues ( $_POST );
		if (@$_FILES["cancion"]["error"] > 0){
		  	$msg.="Error: " . $_FILES["cancion"]["error"] . "<br>";
		}else{
			if(@$_FILES["cancion"]["size"]>0){
				//Ajustar otros valores
				$this->cancion->setDuracion(0);
				$this->cancion->setContenidoBinario(file_get_contents($_FILES["cancion"]["tmp_name"]));
				$this->cancion->setMime($_FILES["cancion"]["type"]);
				$this->cancion->setTamañoBytes($_FILES["cancion"]["size"]);
				$this->cancion->setUsuarioIdUsuario($session->userInfo["user_id"]);
				
		 	}
		}
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
				"AlbumAutocompletar" => $this->cancion->getAlbum()->_getNombreSignificativo(),
				"GeneroAutocompletar" => $this->cancion->getGenero()->_getNombreSignificativo(),
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