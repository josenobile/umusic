<?PHP
class CancionModel extends Cancion {
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
		$pager = new Pager ( $this->con, "(SELECT c.idCancion As idCancion, 
												  c.nombre as Nombre, 
												  c.duracion as Duracion,
												  c.mime as Mime, 
												  c.tamaño_bytes AS TamañoBytes,
												  a.nombre as Album,
												  g.nombre as Genero,
												  u.nombre as Usuario
										   FROM Cancion c 
										   INNER JOIN Album a ON c.Album_IdAlbum= a.idAlbum 
										   INNER JOIN Genero g ON c.Genero_idGenero= g.idGenero
										   INNER JOIN Usuario u ON c.Usuario_idUsuario= u.idUsuario
										   WHERE {$where}) can ", $columns, $this->getNombreId () );
		return $pager;
	}
}?>