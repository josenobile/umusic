<?PHP
require_once 'frontend_tpl_conf.php';

$aParams = array ();

switch (@$_GET ["ac"]) {
	case "usuario" :
		require FRONTEND_PATH_CONTROLLERS . "/UsuarioController.php";
		$libro = new UsuarioController ( $engine );
		$libro->manejadorDeAcciones ();
		break;
	case "cancion" :
		require FRONTEND_PATH_CONTROLLERS . "/CancionController.php";
		$libro = new CancionController ( $engine );
		$libro->manejadorDeAcciones ();
		break;
	default :
		$aParams ['user'] = 'super admin';
		echo $engine->render ( 'index', $aParams );
		break;
}
?>