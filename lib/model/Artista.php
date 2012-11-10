<?PHP 
class Artista{
	private $idArtista;
	private $nombre;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idArtista;
	}	public function getNombreId(){
		return "idArtista";
	}
	public function getIdArtista(){
		return $this->idArtista;
	}
	public function getNombre(){
		return $this->nombre;
	}

	//Setters

	public function setIdArtista($idArtista){
		$this->idArtista = $idArtista;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
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
		if(empty($this->idArtista)){			
						$this->idArtista = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			),"Artista");
			return;
		}		return $this->con->autoUpdate(array(
			"nombre" => $this->nombre,
			),"Artista","idArtista=".$this->getId());
	}
    
	public function cargarPorId($idArtista){
		if($idArtista>0){
			$result = $this->con->query("SELECT * FROM `Artista`  WHERE idArtista=".$idArtista);
			$this->idArtista = $result[0]['idArtista'];
			$this->nombre = $result[0]['nombre'];
		return $result[0];
		}
 	}	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE Artista");
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
		$rows =$this->con->query("SELECT $fields,idArtista FROM `Artista`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idArtista"]] = $row;
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
			$obj->cargarPorId($row["idArtista"]);
			$rowsr[$row["idArtista"]] = $obj;
		}
		return $rowsr;
	}
	public function eliminar(){
		return $this->con->query("DELETE FROM `Artista`  WHERE idArtista=".$this->getId());
	}
}
?>