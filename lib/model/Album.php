<?PHP 
class Album{
	private $idAlbum;
	private $nombre;
	private $artistaIdArtista;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idAlbum;
	}	public function getNombreId(){
		return "idAlbum";
	}
	public function getIdAlbum(){
		return $this->idAlbum;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getArtistaIdArtista(){
		return $this->artistaIdArtista;
	}
	public function getByArtista($Artista_idArtista){
		return $this->listarObj(array("Artista_idArtista"=>$Artista_idArtista));
	}
	public function getArtista(){
		$artista = new Artista($this->con);
		$artista->cargarPorId($this->artistaIdArtista);
		return $artista;
	}

	//Setters

	public function setIdAlbum($idAlbum){
		$this->idAlbum = $idAlbum;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function setArtistaIdArtista($artistaIdArtista){
		$this->artistaIdArtista = $artistaIdArtista;
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
		if(empty($this->idAlbum)){			
						$this->idAlbum = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			"Artista_idArtista" => $this->artistaIdArtista,
			),"Album");
			return;
		}		return $this->con->autoUpdate(array(
			"nombre" => $this->nombre,
			"Artista_idArtista" => $this->artistaIdArtista,
			),"Album","idAlbum=".$this->getId());
	}
    
	public function cargarPorId($idAlbum){
		if($idAlbum>0){
			$result = $this->con->query("SELECT * FROM `Album`  WHERE idAlbum=".$idAlbum);
			$this->idAlbum = $result[0]['idAlbum'];
			$this->nombre = $result[0]['nombre'];
			$this->artistaIdArtista = $result[0]['Artista_idArtista'];
		return $result[0];
		}
 	}	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE Album");
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
		$rows =$this->con->query("SELECT $fields,idAlbum FROM `Album`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idAlbum"]] = $row;
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
			$obj->cargarPorId($row["idAlbum"]);
			$rowsr[$row["idAlbum"]] = $obj;
		}
		return $rowsr;
	}
	public function eliminar(){
		return $this->con->query("DELETE FROM `Album`  WHERE idAlbum=".$this->getId());
	}
}
?>