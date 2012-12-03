<?PHP
$this->extend ( 'layout' );
$this->javascripts->add ( 'umusic/web/javascript/cancion.js' );
   
echo @$msg;
?><h1>Cancion</h1>
<input type="button" id="mostrarFormCancion" value="Nueva Cancion" />
<form action="" method="post" enctype="multipart/form-data" id="formularioCancion">
	<input type="hidden" name="idCancion"	value="" />
	<table width="100%" border="0">
    	<tr>
			<td>Nombre</td>
			<td><input type="text" name="nombre" id="nombre" value="" /></td>
        </tr>
        <tr>            
			<td>File</td>
			<td><input type="file" name="cancion" id="cancion"	value="" />
				<input type="submit" value="leer tags"  name="leerTags" class="leerTags" /></td>
       </tr>
	   <tr>
	   		<td colspan="2"><div id="resultTags"></div></td>
	   </tr>
       <tr>				
			<td>Album</td>
			<td><input type="hidden" name="Album_idAlbum" id="Album_idAlbum"	value="" />
	            <input type="text" name="AlbumAutocompletar" id="AlbumAutocompletar" value="" /></td>
       </tr>
       <tr>				
			<td>Genero</td>
			<td><input type="hidden" name="Genero_idGenero" id="Genero_idGenero"	value="" />
	            <input type="text" name="GeneroAutocompletar" id="GeneroAutocompletar" value="" /></td>
       </tr>
       <tr>
			<td colspan="2"><input type="submit" value="Enviar" />
            	<input type="reset" value="Resetear" /></td>
		</tr>
	</table>
</form>
<div id="result"></div>

<table id="tCancion">
	<thead>
		<tr>
			<th>IdCancion</th>
			<th>URL</th>
			<th>Nombre</th>
			<th>Duracion</th>
			<th>Mime</th>
			<th>TamañoBytes</th>
			<th>Album</th>
			<th>Genero</th>
			<th>Usuario</th>
			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>IdCancion</th>
			<th>URL</th>
			<th>Nombre</th>
			<th>Duracion</th>
			<th>Mime</th>
			<th>TamañoBytes</th>
			<th>Album</th>
			<th>Genero</th>
			<th>Usuario</th>
			<th>Action</th>
		</tr>
	</tfoot>
	<tbody>       
    </tbody>
</table>