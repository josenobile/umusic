<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php $this->output('title')?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
	<meta name="author" content="Muhammad Usman">
	<!-- CSS PLANTILLA -->
	<link id="bs-css" href="web/css/bootstrap-cerulean.css" rel="stylesheet">
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	<link href="web/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="web/css/charisma-app.css" rel="stylesheet">
	<link href="web/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href='web/css/fullcalendar.css' rel='stylesheet'>
	<link href='web/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='web/css/chosen.css' rel='stylesheet'>
	<link href='web/css/uniform.default.css' rel='stylesheet'>
	<link href='web/css/colorbox.css' rel='stylesheet'>
	<link href='web/css/jquery.cleditor.css' rel='stylesheet'>
	<link href='web/css/jquery.noty.css' rel='stylesheet'>
	<link href='web/css/noty_theme_default.css' rel='stylesheet'>
	<link href='web/css/elfinder.min.css' rel='stylesheet'>
	<link href='web/css/elfinder.theme.css' rel='stylesheet'>
	<link href='web/css/jquery.iphone.toggle.css' rel='stylesheet'>
	<link href='web/css/opa-icons.css' rel='stylesheet'>
	<link href='web/css/uploadify.css' rel='stylesheet'>
    <!-- CSS anteriores -->
	<?php echo $this->stylesheets; ?>
    <link type="text/css" rel="stylesheet" href="web/css/common.css" />
	<link rel="stylesheet" type="text/css"	href="web/javascript/DataTables-1.9.1/media/css/demo_table_jui.css" />
	<!-- SCRIPTS anteriores -->
	<script type="text/javascript" src="web/javascript/jquery.min.js"></script>
	<script type="text/javascript"	src="web/javascript/jquery.inputautoresize.js"></script>
	<script type="text/javascript"	src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
	<script type="text/javascript"	src="http://jqueryui.com/themeroller/themeswitchertool/"></script>
	<script type="application/javascript"	src="web/javascript/DataTables-1.9.1/media/js/jquery.dataTables.min.js"></script>
	<script type="application/javascript"	src="web/javascript/DataTables-1.9.1/extras/Scroller/media/js/Scroller.min.js"></script>
	<script type="application/javascript"	src="web/javascript/DataTables-1.9.1/extras/ColumnFilter/jquery.dataTables.columnFilter.js"></script>
	<script type="text/javascript" src="web/javascript/jquery.form.js"></script>
	<script type="text/javascript"	src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
	<script type="text/javascript"	src="web/javascript/jquery.autocomplete.js"></script>
	
	<!-- transition / effect library -->
	<script src="web/javascript/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="web/javascript/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="web/javascript/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="web/javascript/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="web/javascript/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="web/javascript/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="web/javascript/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="web/javascript/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="web/javascript/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="web/javascript/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="web/javascript/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="web/javascript/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="web/javascript/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="web/javascript/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src='web/javascript/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='web/javascript/jquery.dataTables.min.js'></script>

	<!-- chart libraries start -->
	<script src="web/javascript/excanvas.js"></script>
	<script src="web/javascript/jquery.flot.min.js"></script>
	<script src="web/javascript/jquery.flot.pie.min.js"></script>
	<script src="web/javascript/jquery.flot.stack.js"></script>
	<script src="web/javascript/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="web/javascript/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<script src="web/javascript/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="web/javascript/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="web/javascript/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="web/javascript/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="web/javascript/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="web/javascript/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="web/javascript/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="web/javascript/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="web/javascript/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="web/javascript/jquery.history.js"></script>
	<!-- application script for Charisma demo -->
	<script src="web/javascript/charisma.js"></script>
	<!-- Ultimos javascript OLD -->
	<script type="text/javascript" src="web/javascript/common.js"></script>
    <?php echo $this->javascripts; ?>
	<!-- The fav icon -->	
	<link rel="shortcut icon" href="web/images/favicon.ico">
</head>

<body>
	<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="index.php"> <img alt="Charisma Logo" src="web/images/logo20.png" /> <span>UNIVALLE MUSIC</span></a>
	 			<!-- theme selector starts -->
				<div class="btn-group pull-right theme-container" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-tint"></i><span class="hidden-phone"> Cambio tema / Diseño</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" id="themes">
						<li><a data-value="classic" href="#"><i class="icon-blank"></i> Classic</a></li>
						<li><a data-value="cerulean" href="#"><i class="icon-blank"></i> Cerulean</a></li>
						<li><a data-value="cyborg" href="#"><i class="icon-blank"></i> Cyborg</a></li>
						<li><a data-value="redy" href="#"><i class="icon-blank"></i> Redy</a></li>
						<li><a data-value="journal" href="#"><i class="icon-blank"></i> Journal</a></li>
						<li><a data-value="simplex" href="#"><i class="icon-blank"></i> Simplex</a></li>
						<li><a data-value="slate" href="#"><i class="icon-blank"></i> Slate</a></li>
						<li><a data-value="spacelab" href="#"><i class="icon-blank"></i> Spacelab</a></li>
						<li><a data-value="united" href="#"><i class="icon-blank"></i> United</a></li>
					</ul>
				</div>
				<!-- theme selector ends -->
				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> Admin</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#">Perfil</a></li>
						<li class="divider"></li>
						<li><a href="login.php">Logout</a></li>
					</ul>
				</div>
				<!-- user dropdown ends -->
				<div class="top-nav nav-collapse">
					<ul class="nav">
						<li>
							<form class="navbar-search pull-left">
								<input placeholder="Search" class="search-query span2" name="query" type="text">
							</form>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<?php } ?>
	<div class="container-fluid">
		<div class="row-fluid">
		<?php if(!isset($no_visible_elements) || !$no_visible_elements) { ?>
			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Menu</li>
						<li><a class="ajax-link" href="index.php"><i class="icon-home"></i><span class="hidden-tablet"> Inicio</span></a></li>
						<li><a class="ajax-link" href="index.php?ac=usuario"><i class="icon-eye-open"></i><span class="hidden-tablet"> Usuario</span></a></li> 
						<li><a class="ajax-link" href="index.php?ac=cancion"><i class="icon-edit"></i><span class="hidden-tablet"> Canción</span></a></li>
					<!--	<li><a class="ajax-link" href="peaje.php"><i class="icon-list-alt"></i><span class="hidden-tablet"> Peaje</span></a></li>
						<li><a class="ajax-link" href="taller.php"><i class="icon-font"></i><span class="hidden-tablet"> Taller</span></a></li>
						<li><a class="ajax-link" href="lavadero.php"><i class="icon-picture"></i><span class="hidden-tablet"> Lavadero</span></a></li>
						<li><a class="ajax-link" href="cliente.php"><i class="icon-align-justify"></i><span class="hidden-tablet"> Cliente</span></a></li>
						<li><a class="ajax-link" href="formaPago.php"><i class="icon-calendar"></i><span class="hidden-tablet"> Forma de Pago</span></a></li>
						<li><a class="ajax-link" href="reserva.php"><i class="icon-th"></i><span class="hidden-tablet"> Reserva</span></a></li>
						<li><a class="ajax-link" href="movimientos.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Movimientos</span></a></li>
						<li><a class="ajax-link" href="multa.php"><i class="icon-star"></i><span class="hidden-tablet"> Multa</span></a></li>
						<li><a class="ajax-link" href="tarifa.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Tarifa</span></a></li>
						<li><a class="ajax-link" href="promocion.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Promocion</span></a></li>
						<li><a class="ajax-link" href="factura.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Factura</span></a></li>
						<li><a class="ajax-link" href="contrato.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Contrato</span></a></li>
						<li><a class="ajax-link" href="usuario.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Usuario</span></a></li>
						<li><a class="ajax-link" href="funcionalidad.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Funcionalidad</span></a></li>
						<li><a class="ajax-link" href="perfil.php"><i class="icon-folder-open"></i><span class="hidden-tablet"> Perfil</span></a></li>
						<li><a href="sucursal.php"><i class="icon-ban-circle"></i><span class="hidden-tablet"> Sucursal</span></a></li>
                       -->
					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
		
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>Usted necesita tener <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>.</p>
				</div>
			</noscript>
			<div id="content" class="span10">
				<?php $this->output('content')?>
   			</div><!--/#content.span10-->
		<?php } ?>
	 	</div> <!-- row-fluid -->
	    <?php if(!isset($no_visible_elements) || !$no_visible_elements) { ?>
			<hr>
			<div class="modal hide fade" id="myModal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>Settings</h3>
				</div>
				<div class="modal-body">
					<p>Here settings can be configured...</p>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn" data-dismiss="modal">Close</a>
					<a href="#" class="btn btn-primary">Save changes</a>
				</div>
			</div>
			<footer> Copyright &copy; <?PHP echo date("Y"); ?> Developed by WWW TEAM<br />
				Page processed in <?PHP echo round(microtime(true) - $GLOBALS["start_time"], 3); ?> seconds
			</footer>
		<?php } ?>
	</div><!--/.fluid-container-->
	
</body>

</html>
<?PHP echo "<!-- " . memory_get_peak_usage() . "-" . memory_get_peak_usage(true) . " -->"; ?>
