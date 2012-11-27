<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( 'umusic/web/javascript/artista.js' );
echo @$msg;
?><h1>Artista</h1>
<input type="button" id="mostrarFormArtista" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioArtista">
	<input type="hidden" name="idArtista"		value="" />
	<table width="100%" border="0">
    <tr>            <td>Nombre</td>
			<td><input type="" name="nombre" id="nombre"	value="" /></td>
                    </tr>
        <tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="tArtista">
	<thead>
		<tr>   			<th>IdArtista</th>
            			<th>Nombre</th>
            			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   			<th>IdArtista</th>
            			<th>Nombre</th>
            			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>