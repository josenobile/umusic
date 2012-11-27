<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( '/web/javascript/genero.js' );
echo @$msg;
?><h1>Genero</h1>
<input type="button" id="mostrarFormGenero" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioGenero">
	<input type="hidden" name="idGenero"		value="" />
	<table width="100%" border="0">
    <tr>            <td>Nombre</td>
			<td><input type="text" name="nombre" id="nombre"	value="" /></td>
                    </tr>
        <tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="tGenero">
	<thead>
		<tr>   			<th>IdGenero</th>
            			<th>Nombre</th>
            			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   			<th>IdGenero</th>
            			<th>Nombre</th>
            			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>
?>