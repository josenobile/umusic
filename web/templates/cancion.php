<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( '/web/javascript/cancion.js' );
echo @$msg;
?><h1>Cancion</h1>
<input type="button" id="mostrarFormCancion" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioCancion">
	<input type="hidden" name="idCancion"		value="" />
	<table width="100%" border="0">
    <tr>            <td>Nombre</td>
			<td><input type="text" name="nombre" id="nombre"	value="" /></td>
                    </tr>
        <tr>            <td>Duracion</td>
			<td><input type="text" name="duracion" id="duracion"	value="" /></td>
                    </tr>
        <tr>            <td>Contenido Binario</td>
			<td><input type="text" name="contenido_binario" id="contenido_binario"	value="" /></td>
                    </tr>
        <tr>            <td>Mime</td>
			<td><input type="text" name="mime" id="mime"	value="" /></td>
                    </tr>
        <tr>            <td>Tamaño Bytes</td>
			<td><input type="text" name="tamaño_bytes" id="tamaño_bytes"	value="" /></td>
                    </tr>
        <tr>				
			<td>Album IdAlbum</td>
			<td>
            	<input type="hidden" name="Album_idAlbum" id="Album_idAlbum"	value="" />
	            <input type="text" name="AlbumAutocompletar" id="AlbumAutocompletar" value="" />
            </td>
		
               </tr>
        <tr>				
			<td>Genero IdGenero</td>
			<td>
            	<input type="hidden" name="Genero_idGenero" id="Genero_idGenero"	value="" />
	            <input type="text" name="GeneroAutocompletar" id="GeneroAutocompletar" value="" />
            </td>
		
               </tr>
        <tr>				
			<td>Usuario IdUsuario</td>
			<td>
            	<input type="hidden" name="Usuario_idUsuario" id="Usuario_idUsuario"	value="" />
	            <input type="text" name="UsuarioAutocompletar" id="UsuarioAutocompletar" value="" />
            </td>
		
               </tr>
        <tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>

<div id="result"></div>

<table id="tCancion">
	<thead>
		<tr>   			<th>IdCancion</th>
            			<th>Nombre</th>
            			<th>Duracion</th>
            			<th>Contenido Binario</th>
            			<th>Mime</th>
            			<th>Tamaño Bytes</th>
            			<th>Album IdAlbum</th>
            			<th>Genero IdGenero</th>
            			<th>Usuario IdUsuario</th>
            			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   			<th>IdCancion</th>
            			<th>Nombre</th>
            			<th>Duracion</th>
            			<th>Contenido Binario</th>
            			<th>Mime</th>
            			<th>Tamaño Bytes</th>
            			<th>Album IdAlbum</th>
            			<th>Genero IdGenero</th>
            			<th>Usuario IdUsuario</th>
            			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>
?>