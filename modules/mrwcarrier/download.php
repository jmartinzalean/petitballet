<?php 
/*
 * MODULO DE GESTION DE CONEXIONES SOAP CON WEBSERVICE SAGEC DE MRW
 *
 * El módulo hace el uso de la libreria SOAP de PHP5 para conexion y generacion
 * de envios entre Prestashop y el WebService SAGEC de MRW
 *
 * @author MRW Iberia - Dpto. Desarrollo <miguel.delahoz@mrw.es>
 * @copyright (c)2014 - MRW IBERIA
 * @version 1.0.11 - 31/01/2014
 * 
 */

// verify file
if (!isset($_GET['f']) || empty($_GET['f'])) {
	exit();
}
// get filename
$root = "";
$file = basename($_GET['f']);
$path = $root.$file;
$type = '';

if (is_file($path)) {
	$size = filesize($path); 
	if (function_exists('mime_content_type')) {
		$type = mime_content_type($path);
	} else if (function_exists('finfo_file')) {
		$info = finfo_open(FILEINFO_MIME);
		$type = finfo_file($info, $path);
		finfo_close($info);  
	}
	if ($type == '') {
		$type = "application/force-download";
	}
	// Set Headers
	header("Content-Type: $type");
	header("Content-Disposition: attachment; filename=\"$file\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . $size);
	// Download File
	readfile($path);
} else {
	die("File not exist !!");
}
?>