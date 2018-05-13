<?php
/**
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author    YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_carrier`;';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_recoger`;';//previous version
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_configuration`;';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_preregister`;';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_preregister_errors`;';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_schedule`;';//previous version
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_request`;';
