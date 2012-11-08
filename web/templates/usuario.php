<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( 'umusic/web/javascript/usuario.js' );
echo @$msg;
?><h1>Usuario</h1>

<input type="button" id="mostrarFormUsuario" value="Mostrar Formulario" />

<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioUsuario">
	<input type="hidden" name="idUsuario"
		value="" />
	<table width="200" border="0">
		<tr>
			<td>Nombres</td>
			<td><input type="text" name="nombre" id="nombre"
				value="" /></td>
		</tr>
		<tr>
			<td>Apellidos</td>
			<td><input type="text" name="apellido" id="apellido"
				value="" /></td>
		</tr>
        <tr>
			<td>Email</td>
			<td><input type="text" name="email" id="email"
				value="" /></td>
		</tr>
        <tr>
			<td>Contraseña</td>
			<td><input type="password" name="contraseña" id="contraseña"
				value="" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" /><input type="button"
				class="clear" value="Limpiar" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="tUsuario">
	<thead>
		<tr>
			<th>idUsuario</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Email</th>
            <th>Estado</th>
			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>idUsuario</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Email</th>
            <th>Estado</th>
			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>