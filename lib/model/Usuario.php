<?PHP 
class Usuario{
	private $idUsuario;
	private $nombre;
	private $apellido;
	private $email;
	private $contraseña;
	private $estado;
	private $sesionActiva;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
    
    public function _getNombreSignificativo(){
    	return $this->getNombre();//Formar aqui un String con un nombre para usar en los autocompletar
    }
    
	//Getters
	public function getId(){
		return $this->idUsuario;
	}    
	public function getNombreId(){
		return "idUsuario";
	}
	public function getIdUsuario(){
		return $this->idUsuario;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getContraseña(){
		return $this->contraseña;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function getSesionActiva(){
		return $this->sesionActiva;
	}

	//Setters

	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function setApellido($apellido){
		$this->apellido = $apellido;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function setContraseña($contraseña){
		$this->contraseña = $contraseña;
	}
	public function setEstado($estado){
		$this->estado = $estado;
	}
	public function setSesionActiva($sesionActiva){
		$this->sesionActiva = $sesionActiva;
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
		if(empty($this->idUsuario)){			
			$this->idUsuario = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			"apellido" => $this->apellido,
			"email" => $this->email,
			"contraseña" => $this->contraseña,
			"estado" => $this->estado,
			"sesion_activa" => $this->sesionActiva,
			),"Usuario");
			return;
		}
		return $this->con->autoUpdate(array(
			"nombre" => $this->nombre,
			"apellido" => $this->apellido,
			"email" => $this->email,
			"contraseña" => $this->contraseña,
			"estado" => $this->estado,
			"sesion_activa" => $this->sesionActiva,
			),"Usuario","idUsuario=".$this->getId());
	}
    
	public function cargarPorId($idUsuario){
		if($idUsuario>0){
			$result = $this->con->query("SELECT * FROM `Usuario`  WHERE idUsuario=".$idUsuario);
			$this->idUsuario = $result[0]['idUsuario'];
			$this->nombre = $result[0]['nombre'];
			$this->apellido = $result[0]['apellido'];
			$this->email = $result[0]['email'];
			$this->contraseña = $result[0]['contraseña'];
			$this->estado = $result[0]['estado'];
			$this->sesionActiva = $result[0]['sesion_activa'];
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
			$campos = $this->con->query("DESCRIBE Usuario");
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
		$rows =$this->con->query("SELECT $fields,idUsuario FROM `Usuario`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idUsuario"]] = $row;
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
			$obj->cargarPorId($row["idUsuario"]);
			$rowsr[$row["idUsuario"]] = $obj;
		}
		return $rowsr;
	}
	public function eliminar(){
		return $this->con->query("DELETE FROM `Usuario`  WHERE idUsuario=".$this->getId());
	}
}
?>