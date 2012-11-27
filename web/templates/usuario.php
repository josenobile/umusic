<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( '/web/javascript/usuario.js' );
echo @$msg;
?><h1>Usuario</h1>
<input type="button" id="mostrarFormUsuario" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioUsuario">
	<input type="hidden" name="idUsuario"		value="" />
	<table width="100%" border="0">
    <tr>            <td>Nombre</td>
			<td><input type="text" name="nombre" id="nombre"	value="" /></td>
                    </tr>
        <tr>            <td>Apellido</td>
			<td><input type="text" name="apellido" id="apellido"	value="" /></td>
                    </tr>
        <tr>            <td>Email</td>
			<td><input type="text" name="email" id="email"	value="" /></td>
                    </tr>
        <tr>            <td>Contraseña</td>
			<td><input type="text" name="contraseña" id="contraseña"	value="" /></td>
                    </tr>
        <tr>            <td>Estado</td>
			<td><input type="text" name="estado" id="estado"	value="" /></td>
                    </tr>
        <tr>            <td>Sesion Activa</td>
			<td><input type="text" name="sesion_activa" id="sesion_activa"	value="" /></td>
                    </tr>
        <tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="tUsuario">
	<thead>
		<tr>   			<th>IdUsuario</th>
            			<th>Nombre</th>
            			<th>Apellido</th>
            			<th>Email</th>
            			<th>Contraseña</th>
            			<th>Estado</th>
            			<th>Sesion Activa</th>
            			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   			<th>IdUsuario</th>
            			<th>Nombre</th>
            			<th>Apellido</th>
            			<th>Email</th>
            			<th>Contraseña</th>
            			<th>Estado</th>
            			<th>Sesion Activa</th>
            			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>
?>