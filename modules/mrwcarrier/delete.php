<?php

/*
 *
 * @author MRW Iberia - Dpto. Integración <pablo.tribaldos@mrw.es>
 * @copyright (c)2017 - MRW IBERIA
 * @version 3.5 - 06/03/2017
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

if (is_file($path)) {
	unlink($path);
	echo "Log borrado!";
} else {
	die("File not exist !!");
}

echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";

?>