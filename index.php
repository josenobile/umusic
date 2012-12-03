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
		require_once('radiolistpro/id3/getid3/getid3.php');
		require_once('radiolistpro/id3/demos/demo.audioinfo.class.php');
		$libro = new CancionController ( $engine );
		$libro->manejadorDeAcciones ();
		break;
	case "album" :
		require FRONTEND_PATH_CONTROLLERS . "/AlbumController.php";
		$libro = new AlbumController ( $engine );
		$libro->manejadorDeAcciones ();
		break;
	case "genero" :
		require FRONTEND_PATH_CONTROLLERS . "/GeneroController.php";
		$libro = new GeneroController ( $engine );
		$libro->manejadorDeAcciones ();
		break;
	case "loadCancion" :
		require FRONTEND_PATH_CONTROLLERS . "/LoadCancion.php";
		if (! empty ( $_GET ["id"] )) {
			$loadDoc = new LoadDocument ( $_GET ["id"] );
		}
		break;
	default :
		$aParams ['user'] = 'super admin';
		echo $engine->render ( 'index', $aParams );
		break;
}
?>