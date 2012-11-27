<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( '/web/javascript/album.js' );
echo @$msg;
?><h1>Album</h1>
<input type="button" id="mostrarFormAlbum" value="Mostrar Formulario" />
<form action="" method="post"
	enctype="application/x-www-form-urlencoded" id="formularioAlbum">
	<input type="hidden" name="idAlbum"		value="" />
	<table width="100%" border="0">
    <tr>            <td>Nombre</td>
			<td><input type="text" name="nombre" id="nombre"	value="" /></td>
                    </tr>
        <tr>				
			<td>Artista IdArtista</td>
			<td>
            	<input type="hidden" name="Artista_idArtista" id="Artista_idArtista"	value="" />
	            <input type="text" name="ArtistaAutocompletar" id="ArtistaAutocompletar" value="" />
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

<table id="tAlbum">
	<thead>
		<tr>   			<th>IdAlbum</th>
            			<th>Nombre</th>
            			<th>Artista IdArtista</th>
            			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>   			<th>IdAlbum</th>
            			<th>Nombre</th>
            			<th>Artista IdArtista</th>
            			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>
?>