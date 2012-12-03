<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Scan Id3 tag</title>
</head>

<body>
Esta operacion borrara todos los registros de la base de datos y volvera a escanear las carpetas en busca de musica!<br />
Si est&aacute; operaci&oacute;n se realiz&oacute; previamente, no necesita repetirla a menos que: Archivos hallan sido renombrados o movidos, los tags hallan sido editados, se cambio el nombre de alguna carpeta de m&uacute;sica
<br />
Si ya la realiz&oacute;, puede continuar a ver los <a href="informes.php">Informes</a> o generar la <a href="lista1.php">Lista</a>.
<br />
Esta operaci&oacute;n tardar&aacute; segun la cantidad de m&uacute;sica, entre 10 y 20 minutos.
<form action="scanpaths.php" enctype="application/x-www-form-urlencoded" method="post">
Digite el directorio para escanear los tags: <br />
<input name="RUTA" type="text" value="D:\RADIOCOMUNICATE OK\MUSITECA" size="128" maxlength="255" />
<br />
<input type="submit" value="Ver Tags" />

</form>


</body>
</html>
