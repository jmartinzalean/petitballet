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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_carrier` (
  `code` varchar(5) NOT NULL,
  `title` varchar(50) NOT NULL,
  `delay` varchar(80) NOT NULL,
  `id_reference` int(10) unsigned NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = "INSERT INTO `"._DB_PREFIX_."correos_carrier` (`code`, `title`, `delay`, `id_reference`) VALUES
('S0030', 'Paquete Internacional Prioritario', 'Entrega a domicilio hasta 7 días', 0),
('S0033', 'Postal EXPRÉS Nacional', 'Entrega a domicilio en 1-2 días', 0),
('S0034', 'Postal EXPRÉS Internacional ', 'Entrega a domicilio hasta 5 días', 0),
('S0132', 'Paq Estándar Domicilio', 'Entrega a domicilio en 2-3 días', 0),
('S0133', 'Paq Estándar Oficina', 'Recogida en la oficina que Vd. elija (2-3 días)', 0),
('S0198', 'Postal EXPRÉS LISTA', 'Definir...', 0),
('S0235', 'Paq Premium Domicilio','Entrega a domicilio en 1-2 días', 0),
('S0236', 'Paq Premium Oficina','Recogida en la oficina que Vd. elija (1-2 días)', 0),
('S0175', 'Paq Premium CityPaq', 'Entrega en CorreosPaq en 1-2 días', 0),
('S0176', 'Paq Premium CityPaq', 'Entrega a domicilio en 1-2 días', 0),
('S0177', 'Paq Estándar CityPaq', 'Entrega en HomePaq o CityPaq en 2-3 días', 0),
('S0360', 'Paquete Internacional LIGHT', 'Entrega a domicilio hasta 7 días', 0),
('S0178', 'Paq Estándar CityPaq', 'Entrega a domicilio en 2-3 días', 0);";

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_preregister`;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_preregister` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `id_carrier` int(10) unsigned NOT NULL,
  `carrier_code` varchar(5) NOT NULL,
  `code_expedition` varchar(16) DEFAULT NULL,
  `date_response` datetime NOT NULL,
  `shipment_code` varchar(23) DEFAULT NULL,
  `shipment_customs_code` varchar(23) DEFAULT NULL,
  `label_printed` timestamp NULL DEFAULT NULL,
  `exported` timestamp NULL DEFAULT NULL,
  `manifest` timestamp NULL DEFAULT NULL,
  `weight` decimal(17,2) NOT NULL DEFAULT \'0.00\',
  `insurance` decimal(17,2) NOT NULL DEFAULT \'0.00\',
  `id_collection` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_preregister_errors`;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_preregister_errors` (
  `id_order` int(10) unsigned NOT NULL,
  `error` varchar(255) DEFAULT NULL,
   `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_configuration`;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_configuration` (
         `name` VARCHAR( 50 ) NOT NULL ,
         `value` text NULL,
       PRIMARY KEY (`name`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
$sql[] ="INSERT INTO `"._DB_PREFIX_."correos_configuration` (`name`, `value`) VALUES
   ('cashondelivery_modules', 'cashondelivery,megareembolso,codfee,reembolsocargo,".
   "cashondeliveryplus,cashondeliveryfee'),
   ('S0236_postalcodes', '05001,01015,02071,03005,03202,04005,06007,07011,07700,07800,08010,".
   "08012,08016,08020,08027,08029,08034,08038,08205,08221,08301,08905,08911,08921,09001,10001,".
   "11011,11202,11404,11500,12001,13003,14005,15001,15402,15770,16071,17005,18011,19001,20018,".
   "21007,22006,23009,24004,25005,26007,27003,28002,28005,28007,28008,28015,28016,28022,28023,".
   "28025,28039,28041,28043,28100,28806,28905,28910,28923,28931,28941,29004,29018,30100,30201,".
   "31013,32001,33011,33206,34070,35014,36004,36210,37001,38108,39002,40001,41007,42001,43001,".
   "43202,44003,45003,45600,46007,46010,47001,48003,49003,50004,51001,52001'),
    ('url_data', 'https://preregistroenvios.correos.es/preregistroenvios'),
   ('url_data_pre', 'https://preregistroenviospre.correos.es/preregistroenvios'),
   ('url_homepaq', 'https://online.correospaq.es/correospaqws/HomepaqWSService'),
   ('url_homepaq_pre', 'https://onlinepre.correospaq.es/correospaqws/HomepaqWSService'),
   ('url_office_locator', 'http://localizadoroficinas.correos.es/localizadoroficinas'),
   ('url_office_locator_pre', 'http://localizadoroficinaspre.correos.es/localizadoroficinas'),
   ('url_servicepaq', 'https://online.correospaq.es/correospaqws/CorreospaqService'),
   ('url_servicepaq_pre', 'https://onlinepre.correospaq.es/correospaqws/CorreospaqService'),
   ('url_tracking', 'https://online.correos.es/servicioswebLocalizacionMI/localizacionMI.asmx');";

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'correos_request`;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum(\'quote\',\'order\') COLLATE utf8_spanish_ci NOT NULL DEFAULT \'quote\',
  `id_cart` int(10) unsigned NOT NULL DEFAULT \'0\',
  `id_order` int(10) unsigned NOT NULL,
  `id_carrier` int(10) unsigned NOT NULL,
  `reference` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `data` text COLLATE utf8_spanish_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_cart` (`id_cart`,`id_carrier`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_collection` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(155) NOT NULL,
  `reference_code` varchar(100) DEFAULT NULL,
  `collection_data` text NOT NULL,
  `collection_date` date NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;';
