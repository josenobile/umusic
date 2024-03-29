<?PHP 
class Genero{
	private $idGenero;
	private $nombre;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
    
    public function _getNombreSignificativo(){
    	return $this->getNombre();//Formar aqui un String con un nombre para usar en los autocompletar
    }
    
	//Getters
	public function getId(){
		return $this->idGenero;
	}    
	public function getNombreId(){
		return "idGenero";
	}
	public function getIdGenero(){
		return $this->idGenero;
	}
	public function getNombre(){
		return $this->nombre;
	}

	//Setters

	public function setIdGenero($idGenero){
		$this->idGenero = $idGenero;
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
		if(empty($this->idGenero)){			
			$this->idGenero = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			),"Genero");
			return;
		}
		return $this->con->autoUpdate(array(
			"nombre" => $this->nombre,
			),"Genero","idGenero=".$this->getId());
	}
    
	public function cargarPorId($idGenero){
		if($idGenero>0){
			$result = $this->con->query("SELECT * FROM `Genero`  WHERE idGenero=".$idGenero);
			$this->idGenero = $result[0]['idGenero'];
			$this->nombre = $result[0]['nombre'];
		return $result[0];
		}
 	}
 	private function setCommonValuesInsert(){
		global $session;
		$this->setOwnerUserId($session->userInfo["user_id"]);
		$this->setCreatedAt(date("Y-m-d H:i:s"));
		$ips =  explode(",",!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER["REMOTE_ADDR"]);
		$this->setIpAddress(array_shift($ips));
		$this->setCommonValuesUpdate();
	}
	private function setCommonValuesUpdate(){
		global $session;
		$this->setUpdaterUserId($session->userInfo["user_id"]);
		$this->setUpdatedAt(date("Y-m-d H:i:s"));
		$ips =  explode(",",!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER["REMOTE_ADDR"]);
		$this->setIpAddress(array_shift($ips));
	}
	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE Genero");
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
		$rows =$this->con->query("SELECT $fields,idGenero FROM `Genero`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idGenero"]] = $row;
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
			$obj->cargarPorId($row["idGenero"]);
			$rowsr[$row["idGenero"]] = $obj;
		}
		return $rowsr;
	}
	public function eliminar(){
		return $this->con->query("DELETE FROM `Genero`  WHERE idGenero=".$this->getId());
	}
}
?>