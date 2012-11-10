<?PHP 
class Cancion{
	private $idCancion;
	private $nombre;
	private $duracion;
	private $contenidoBinario;
	private $mime;
	private $tamañoBytes;
	private $albumIdAlbum;
	private $generoIdGenero;
	private $usuarioIdUsuario;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idCancion;
	}	public function getNombreId(){
		return "idCancion";
	}
	public function getIdCancion(){
		return $this->idCancion;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getDuracion(){
		return $this->duracion;
	}
	public function getContenidoBinario(){
		return $this->contenidoBinario;
	}
	public function getMime(){
		return $this->mime;
	}
	public function getTamañoBytes(){
		return $this->tamañoBytes;
	}
	public function getAlbumIdAlbum(){
		return $this->albumIdAlbum;
	}
	public function getGeneroIdGenero(){
		return $this->generoIdGenero;
	}
	public function getUsuarioIdUsuario(){
		return $this->usuarioIdUsuario;
	}
	public function getByAlbum($Album_idAlbum){
		return $this->listarObj(array("Album_idAlbum"=>$Album_idAlbum));
	}
	public function getAlbum(){
		$album = new Album($this->con);
		$album->cargarPorId($this->albumIdAlbum);
		return $album;
	}
	public function getByGenero($Genero_idGenero){
		return $this->listarObj(array("Genero_idGenero"=>$Genero_idGenero));
	}
	public function getGenero(){
		$genero = new Genero($this->con);
		$genero->cargarPorId($this->generoIdGenero);
		return $genero;
	}
	public function getByUsuario($Usuario_idUsuario){
		return $this->listarObj(array("Usuario_idUsuario"=>$Usuario_idUsuario));
	}
	public function getUsuario(){
		$usuario = new Usuario($this->con);
		$usuario->cargarPorId($this->usuarioIdUsuario);
		return $usuario;
	}

	//Setters

	public function setIdCancion($idCancion){
		$this->idCancion = $idCancion;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function setDuracion($duracion){
		$this->duracion = $duracion;
	}
	public function setContenidoBinario($contenidoBinario){
		$this->contenidoBinario = $contenidoBinario;
	}
	public function setMime($mime){
		$this->mime = $mime;
	}
	public function setTamañoBytes($tamañoBytes){
		$this->tamañoBytes = $tamañoBytes;
	}
	public function setAlbumIdAlbum($albumIdAlbum){
		$this->albumIdAlbum = $albumIdAlbum;
	}
	public function setGeneroIdGenero($generoIdGenero){
		$this->generoIdGenero = $generoIdGenero;
	}
	public function setUsuarioIdUsuario($usuarioIdUsuario){
		$this->usuarioIdUsuario = $usuarioIdUsuario;
	}
	//LLena todos los atributos de la clase sacando los valores de un array
	function setValues($array){
		foreach($array as $key => $val){
			$key = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
			if(property_exists($this,$key))
				$this->$key = $val;
		}
	}
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idCancion)){			
						$this->idCancion = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			"duracion" => $this->duracion,
			"contenido_binario" => $this->contenidoBinario,
			"mime" => $this->mime,
			"tamaño_bytes" => $this->tamañoBytes,
			"Album_idAlbum" => $this->albumIdAlbum,
			"Genero_idGenero" => $this->generoIdGenero,
			"Usuario_idUsuario" => $this->usuarioIdUsuario,
			),"Cancion");
			return;
		}		return $this->con->autoUpdate(array(
			"nombre" => $this->nombre,
			"duracion" => $this->duracion,
			"contenido_binario" => $this->contenidoBinario,
			"mime" => $this->mime,
			"tamaño_bytes" => $this->tamañoBytes,
			"Album_idAlbum" => $this->albumIdAlbum,
			"Genero_idGenero" => $this->generoIdGenero,
			"Usuario_idUsuario" => $this->usuarioIdUsuario,
			),"Cancion","idCancion=".$this->getId());
	}
    
	public function cargarPorId($idCancion){
		if($idCancion>0){
			$result = $this->con->query("SELECT * FROM `Cancion`  WHERE idCancion=".$idCancion);
			$this->idCancion = $result[0]['idCancion'];
			$this->nombre = $result[0]['nombre'];
			$this->duracion = $result[0]['duracion'];
			$this->contenidoBinario = $result[0]['contenido_binario'];
			$this->mime = $result[0]['mime'];
			$this->tamañoBytes = $result[0]['tamaño_bytes'];
			$this->albumIdAlbum = $result[0]['Album_idAlbum'];
			$this->generoIdGenero = $result[0]['Genero_idGenero'];
			$this->usuarioIdUsuario = $result[0]['Usuario_idUsuario'];
		return $result[0];
		}
 	}	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE Cancion");
			$listicos = array();
			foreach($campos as $campo){
				$tmp = explode("(",$campo["Type"]);
				$listicos[$campo["Field"]] = $tmp[0];
			}
			foreach($filtros as $filtro => $valor){
				if($valor === NULL){
					$whereA[] = $filtro." IS NULL";
					continue;
				}
				if($listicos[$filtro] == "int")
					$whereA[] = $filtro." = ".floatval($valor);
				else
					$whereA[] = $filtro." LIKE '%".$this->con->escape($valor)."%'";			
			}

		}else{
			foreach($filtros as $filtro => $valor){
				if($valor === NULL){
					$whereA[] = $filtro." IS NULL";
					continue;
				}
				$whereA[] = $filtro." = ".$this->con->quote($valor);
			}
		}
		$where = implode(" AND ",$whereA);
		if($where == '')
			$where = 1;
		if ($orderBy != "")
			$orderBy = "ORDER BY $orderBy";
		$rows =$this->con->query("SELECT $fields,idCancion FROM `Cancion`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idCancion"]] = $row;
            else
            	$rowsI[] = $row;
		}
		return $rowsI;
	}
	//como listar, pero retorna un array de objetos
	function listarObj($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*'){
		$rowsr = array();
		$rows = $this->listar($filtros, $orderBy, $limit, $exactMatch, '*');
		foreach($rows as $row){
			$obj = clone $this;
			$obj->cargarPorId($row["idCancion"]);
			$rowsr[$row["idCancion"]] = $obj;
		}
		return $rowsr;
	}
	public function eliminar(){
		return $this->con->query("DELETE FROM `Cancion`  WHERE idCancion=".$this->getId());
	}
}
?>