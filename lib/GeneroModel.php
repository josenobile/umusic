<?PHP
class GeneroModel extends Genero {
	public function _construct() {
		parent::__construct ();
	}
	public function getPager(Array $columns, Array $filters = array()) {
		$whereA = array ();
		foreach ( $filters as $filter => $value )
			$whereA [] = $filter . " = " . $this->con->quote ( $value );
		$where = implode ( " AND ", $whereA );
		if ($where == '')
			$where = 1;
		$pager = new Pager ( $this->con, "(SELECT idUsuario As idUsuario, nombre as Nombre, apellido as Apellido, email as Email, estado AS Estado FROM Usuario WHERE {$where}) a", $columns, $this->getNombreId () );
		return $pager;
	}
}?>