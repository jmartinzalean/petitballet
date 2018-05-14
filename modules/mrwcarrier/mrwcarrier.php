<?php

/*
 * MODULO DE GESTION DE CONEXIONES SOAP CON WEBSERVICE SAGEC DE MRW
 *
 * El módulo hace el uso de la libreria SOAP de PHP5 para conexion y generacion
 * de envios entre Prestashop y el WebService SAGEC de MRW
 *
 * @author MRW Iberia - Dpto. Integación
 * @copyright (c)2016 - MRW IBERIA
 * @version 4.6.3 - 13/11/2017
 *
 */

class MrwCarrier extends Module
{

    private $_postErrors = array();
    private $debug_activo;
    private $agrupar_bultos;
    private $gestion_bultos;
    private $success;
    public  $executing = false;

    public function __construct()
    {
        $this->name = 'mrwcarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '4.6.3';
        $this->displayName = 'MRW-Spain Official Module - ' . $this->name;
        $this->author = 'MRW IBERIA';
        $this->processing = 1;
        $this->urlTracking = 'http://www.mrw.es/seguimiento_envios/MRW_historico_nacional.asp?enviament=@';
        $this->rename = strtoupper($this->name) . '_'; // texto para anteponer a los nombres de las variables globales

        parent::__construct();

        if (!Configuration::get($this->rename . 'ENTORNO') or
            !Configuration::get($this->rename . 'FRANQUICIA') or
            !Configuration::get($this->rename . 'ABONADO') or
            !Configuration::get($this->rename . 'USUARIO') or
            !Configuration::get($this->rename . 'PASSWORD') or
            !Configuration::get($this->rename . 'BEFORE_SENDING_MRW') or
            !Configuration::get($this->rename . 'AFTER_SENDING_MRW') or
            !Configuration::get($this->rename . 'LAST_EXECUTE_MRW')) {
            $this->warning = $this->l('No tienes completado los parametros de conexión al web service de MRW');
        }

        $this->description = $this->l('Módulo para integrar su tienda Prestashop con gestión de envios MRW-SAGEC');
        $this->confirmUninstall = $this->l('Está seguro que desea desinstalar y eliminar los datos de conexión al webservice MRW?');
        if ($this->active && !Configuration::get($this->rename . 'ACTIVE_DEBUG_MRW')) {
            Configuration::updateValue($this->rename . 'ACTIVE_DEBUG_MRW', '0');
        }

        $this->debug_activo = Configuration::get($this->rename . 'ACTIVE_DEBUG_MRW');
    }

    /**
     * Procesa la instalaci??el m??o
     * @return Bool
     */
    public function install()
    {

        if (!$this->checkVersion()){
            return false;
        }
        else {
                $this->writeToFileParam('Iniciando proceso de instalacion (' . date("Y-m-d H:i:s") . ')');

                
                if ( strncmp(_PS_VERSION_, "1.5", 3) == 0 || strncmp(_PS_VERSION_, "1.6", 3) == 0 || strncmp(_PS_VERSION_, "1.7", 3) == 0){
                    // Install admin tab 1.6
                    if (!$this->installTab('AdminParentOrders', 'AdminMrwCarrier', 'MRW'))
                    return false;
                }

                //$this->writeToFileParam('Carrier Entrega en Franquicia creado: (' . $lastStandardId . ')');
                //Configuration::updateValue($this->rename . 'CARRIER_ID_MRW', $lastId); // Creamos registro en configuration con el valor del id Carrier creado
                Configuration::updateValue($this->rename . 'LAST_EXECUTE_MRW', time()); // Creamos fecha de registros
                Configuration::updateValue($this->rename . 'ACTIVE_DEBUG_MRW', '0'); // Creamos registro en configuration para activar el debug

                //Si ya existen las tablas se realizan las transformaciones de las mismas. Por retrocompatibilidad.
                $sql_carrier_mrw = 'Show tables like \'' . _DB_PREFIX_ . $this->name . '_mrw\'';
                $rowCarrier = Db::getInstance()->ExecuteS($sql_carrier_mrw);

                if ($rowCarrier) {
                    //Se realiza la comprobación si anteriormente se ha realizado una instalación
                    $sql_carrier_mrw = 'SELECT column_name FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'state\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('Install rowCarrier-> ' . $sql_carrier_mrw);
                    }

                    $rowCarrier = Db::getInstance()->ExecuteS($sql_carrier_mrw);

                    if ($rowCarrier && isset($rowCarrier)) {
                        //Se van modificando los campos de la tabla si no existen
                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw CHANGE `state` `print` INT( 11 ) NULL DEFAULT NULL';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install->  No se ha modificado campo state a Print');
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `cant` INT NOT NULL DEFAULT 1';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install-> No se ha añadido  campo cant');
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `service` varchar(11) NOT NULL';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> No se ha añadido  campo service');
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `subscriber` varchar(11) NOT NULL';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> No se ha añadido  campo subscriber');
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `saturday` INT NOT NULL DEFAULT 0';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> No se ha añadido  campo saturday');
                        }

                        $existsIdShopQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'id_shop\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                        $existsIdShop = Db::getInstance()->getValue($existsIdShopQuery);
                        $this->writeToFileParam('Existe la columna idshop1?' . $existsIdShop);
                        
                        if($existsIdShop == 0){
                            $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `id_shop` INT(11) NOT NULL DEFAULT 1';
                            if (!Db::getInstance()->Execute($sql)) {
                                $this->writeToFileParam('Install -> No se ha añadido  campo id_shop');
                            }
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `agency` INT NOT NULL DEFAULT 0';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> No se ha añadido  campo agency');
                        }

                        $existsBackReturnQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'backReturn\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                        $existsBackReturn = Db::getInstance()->getValue($existsBackReturnQuery);
                        $this->writeToFileParam('Existe la columna BackReturn1?' . $existsBackReturn);
                            
                        if($existsBackReturn == 0){
                            $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `backReturn` INT NOT NULL DEFAULT 0';
                            if (!Db::getInstance()->Execute($sql)) {
                                $this->writeToFileParam('Install -> No se ha añadido  campo backReturn');
                            }
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw DROP `link_mrw`';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> Eliminado campo link_mrw');
                        }
                    } else {
                        //Versiones 2.0.1 en adelante
                        //Se realiza la comprobación si anteriormente se ha realizado una instalación
                        $sql_carrier_mrw = 'SELECT column_name FROM information_schema.COLUMNS
                                            WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                            AND COLUMN_NAME= \'saturday\'
                                            AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam('Install rowCarrier-> ' . $sql_carrier_mrw);
                        }

                        $rowCarrier = Db::getInstance()->ExecuteS($sql_carrier_mrw);
                        if ($rowCarrier && isset($rowCarrier)) {
                            $existsIdShopQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'id_shop\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                            $existsIdShop = Db::getInstance()->getValue($existsIdShopQuery);
                            $this->writeToFileParam('Existe la columna idshop1?' . $existsIdShop);
                        
                            if($existsIdShop == 0){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `id_shop` INT(11) NOT NULL DEFAULT 1';
                                $this->writeToFileParam('Install rowCarrier-> ' . $sql);
                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install -> No se ha añadido  campo id_shop');
                                }
                            }

                            $existsBackReturnQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'backReturn\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsBackReturn = Db::getInstance()->getValue($existsBackReturnQuery);
                            $this->writeToFileParam('Existe la columna BackReturn1?' . $existsBackReturn);
                        
                            if($existsBackReturn == 0){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `backReturn` INT NOT NULL DEFAULT 0';
                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install -> No se ha añadido  campo backReturn');
                                }
                            }

                            $existsIdQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'id\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsId = Db::getInstance()->getValue($existsIdQuery);
                            $this->writeToFileParam('Existe la columna Id?' . $existsId);

                            if($existsId){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw CHANGE `id` `id_mrwcarrier_mrw` int(11) NOT NULL AUTO_INCREMENT';

                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install->  No se ha modificado campo id a id_mrwcarrier_mrw');
                                }
                            }

                            $existsWarehouseQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'mrw_warehouse\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsWarehouse = Db::getInstance()->getValue($existsWarehouseQuery);
                            $this->writeToFileParam('Existe la columna mrw_warehouse?' . $existsId);

                            if(!$existsWarehouse){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `mrw_warehouse` INT NOT NULL DEFAULT 0';

                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install->  No se ha modificado campo id a id_mrwcarrier_mrw');
                                }
                            }

                            $existsSlotsQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\' AND COLUMN_NAME= \'mrw_slot\' AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsSlot = Db::getInstance()->getValue($existsSlotsQuery);
                            $this->writeToFileParam('Existe la columna mrw_slot?' . $existsId);

                            if(!$existsSlot){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `mrw_slot` INT NOT NULL DEFAULT 0';

                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install->  No se ha podido añadir el campo mrw_slot');
                                }
                            }

                        } else {
                            //Versión 2.1.3 en adelante
                            //Se realiza la comprobación si anteriormente se ha realizado una instalación
                            $sql_carrier_mrw = 'SELECT column_name FROM information_schema.COLUMNS
                                                WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                                AND COLUMN_NAME= \'id_shop\'
                                                AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                            if ($this->debug_activo == '1') {
                                $this->writeToFileParam('Install rowCarrier-> ' . $sql_carrier_mrw);
                            }

                            $rowCarrier = Db::getInstance()->ExecuteS($sql_carrier_mrw);
                            if ($rowCarrier && isset($rowCarrier)) {
                                $existsBackReturnQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'backReturn\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                                $existsBackReturn = Db::getInstance()->getValue($existsBackReturnQuery);
                                $this->writeToFileParam('Existe la columna BackReturn1?' . $existsBackReturn);
                            
                                if($existsBackReturn == 0){
                                    $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `backReturn` INT NOT NULL DEFAULT 0';
                                    if (!Db::getInstance()->Execute($sql)) {
                                        $this->writeToFileParam('Install -> No se ha añadido  campo backReturn');
                                    }
                                }


                                $existsIdQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'id\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                                $existsId = Db::getInstance()->getValue($existsIdQuery);
                                $this->writeToFileParam('Existe la columna Id?' . $existsId);

                                if($existsId){
                                    $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw CHANGE `id` `id_mrwcarrier_mrw` int(11) NOT NULL AUTO_INCREMENT';

                                    if (!Db::getInstance()->Execute($sql)) {
                                        $this->writeToFileParam('Install->  No se ha modificado campo id a id_mrwcarrier_mrw');
                                    }
                                }

                            $existsWarehouseQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'mrw_warehouse\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsWarehouse = Db::getInstance()->getValue($existsWarehouseQuery);
                            $this->writeToFileParam('Existe la columna mrw_warehouse?' . $existsId);

                            if(!$existsWarehouse){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `mrw_warehouse` INT NOT NULL DEFAULT 0';

                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install->  No se ha modificado campo id a id_mrwcarrier_mrw');
                                }
                            }

                            $existsSlotsQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\' AND COLUMN_NAME= \'mrw_slot\' AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';
                                        
                            $existsSlot = Db::getInstance()->getValue($existsSlotsQuery);
                            $this->writeToFileParam('Existe la columna mrw_slot?' . $existsId);

                            if(!$existsSlot){
                                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_mrw ADD `mrw_slot` INT NOT NULL DEFAULT 0';

                                if (!Db::getInstance()->Execute($sql)) {
                                    $this->writeToFileParam('Install->  No se ha podido añadir el campo mrw_slot');
                                }
                            }
                        }
                    }
                }
                }else {
                    $sql_carrier_mrw =
                    'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . $this->name . '_mrw
                    (
                        id_mrwcarrier_mrw int(11) NOT NULL AUTO_INCREMENT,
                        id_shop int(11) NOT NULL DEFAULT 1,
                        order_id int(11) NOT NULL,
                        send_num_mrw VARCHAR(100) NULL,
                        params_mrw VARCHAR(255) NULL,
                        date DATE NOT NULL,
                        print INT NULL,
                        cant  INT NOT NULL DEFAULT 1,
                        subscriber varchar(11) NOT NULL,
                        service varchar(11) NOT NULL,
                        saturday INT NOT NULL DEFAULT 0,
                        agency INT NOT NULL DEFAULT 0,
                        backReturn INT NOT NULL DEFAULT 0,
                        mrw_warehouse INT NOT NULL DEFAULT 0,
                        mrw_slot INT NOT NULL DEFAULT 0,
                        PRIMARY KEY (id_mrwcarrier_mrw)
                    ) CHARACTER SET utf8 COLLATE utf8_general_ci';
                    $sql_carrier_mrw_res = Db::getInstance()->Execute($sql_carrier_mrw);

                    if (!$sql_carrier_mrw_res) {
                        $this->writeToFileParam('ERROR al crear las tablas');
                        return false;
                    }
                    $this->writeToFileParam('Creada tabla: (' . _DB_PREFIX_ . $this->name . '_mrw)');
                }

                //Si ya existen las tablas se realizan las transformaciones de las mismas. Por retrocompatibilidad.
                $sql_subscriber_mrw = 'Show tables like \'' . _DB_PREFIX_ . $this->name . '_subs\'';
                $rowSubscriber = Db::getInstance()->ExecuteS($sql_subscriber_mrw);

                if ($rowSubscriber) {
                    //Se realiza la comprobación si anteriormente se ha realizado una instalación
                    $sql_subscriber_mrw = 'SELECT column_name FROM information_schema.COLUMNS
                                           WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_subs\'
                                           AND COLUMN_NAME= \'id_shop\'
                                           AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('Install rowSubscriber-> ' . $sql_subscriber_mrw);
                    }

                    $rowSubscriber = Db::getInstance()->ExecuteS($sql_subscriber_mrw);

                    if ($rowSubscriber && isset($rowSubscriber)) {   
                        $existsIdShopQuery = 'SELECT COUNT(*) FROM information_schema.COLUMNS
                                        WHERE table_name = \'' . _DB_PREFIX_ . $this->name . '_mrw\'
                                        AND COLUMN_NAME= \'id_shop\'
                                        AND TABLE_SCHEMA= \'' . _DB_NAME_ . '\'';

                        $existsIdShop = Db::getInstance()->getValue($existsIdShopQuery);
                        $this->writeToFileParam('Existe la columna idshop2?' . $existsIdShop);

                        if($existsIdShop == 0){

                            $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_subs ADD `id_shop` INT(11) NOT NULL DEFAULT 1';
                            if (!Db::getInstance()->Execute($sql)) {
                                $this->writeToFileParam('Install -> No se ha añadido  campo id_shop');
                            }
                        }

                        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . $this->name . '_subs ADD `id_name` varchar(25) NOT NULL';
                        if (!Db::getInstance()->Execute($sql)) {
                            $this->writeToFileParam('Install -> No se ha añadido  campo id_name');
                        }
                    }
                } else {
                    //La nueva tabla necesaria para control de abonados
                    $sql_mrw_subscriber =
                    'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . $this->name . '_subs
                        (
                            id_subscriber int(11) NOT NULL AUTO_INCREMENT,
                            id_shop       int(11) NOT NULL DEFAULT 1,
                            id_name       varchar(25) NOT NULL,
                            environment varchar(10) NOT NULL,
                            agency varchar(10) NOT NULL,
                            subscriber varchar(10) NOT NULL,
                            department varchar(100) NOT NULL,
                            user varchar(100) NOT NULL,
                            password varchar(100) NOT NULL,
                            service varchar (5) NOT NULL,
                            is_default varchar(1) default "S",
                            date DATE NOT NULL,
                            PRIMARY KEY (id_subscriber)
                        ) CHARACTER SET utf8 COLLATE utf8_general_ci';
                    $sql_mrw_subscriber = Db::getInstance()->Execute($sql_mrw_subscriber);
                    if (!$sql_mrw_subscriber) {
                        $this->writeToFileParam('ERROR al crear la tabla ' . _DB_PREFIX_ . $this->name . '_subs)');
                        return false;
                    }
                    $this->writeToFileParam('Creada tabla: (' . _DB_PREFIX_ . $this->name . '_subs)');

                }
                //Nueva tabla para controlar duplicidad en generación automática de etiquetas.
                $sql_mrw_test = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . $this->name .  '_test ( check_field tinyint(1) NOT NULL DEFAULT 0) CHARACTER SET utf8 COLLATE utf8_general_ci';
                $sql_mrw_test = Db::getInstance()->Execute($sql_mrw_test);
                if (!$sql_mrw_test) {
                    $this->writeToFileParam('ERROR al crear la tabla ' . _DB_PREFIX_ . $this->name .  '_test)');
                    return false;
                }

                //Crear campo check
                $sql_mrw_test = 'INSERT INTO ' . _DB_PREFIX_ . $this->name .  '_test (check_field) VALUES (0)';
                $sql_mrw_test = Db::getInstance()->Execute($sql_mrw_test);

                //Poner valor a 0 por defecto para que puedan entrar
                $sql_mrw_test =  'UPDATE ' . _DB_PREFIX_ . $this->name .  '_test SET check_field = 0';
                $sql_mrw_test = Db::getInstance()->Execute($sql_mrw_test);
                if (!$sql_mrw_test) {
                    $this->writeToFileParam('ERROR el introducir el valor 0 en el campo check_field de la tabla' . _DB_PREFIX_ . $this->name .  '_test)');
                    return false;
                }
                //Fin controlar duplicidad

                $originate = _PS_MODULE_DIR_ . $this->name . '/download.php';
                $destiny = _PS_DOWNLOAD_DIR_ . 'ticket_mrw/download.php';
                $originateht = _PS_MODULE_DIR_ . $this->name . '/htaccess.txt';
                $destinyht = _PS_DOWNLOAD_DIR_ . 'ticket_mrw/.htaccess';
                $base = _PS_DOWNLOAD_DIR_ . '/ticket_mrw/';
                if (!is_dir($base)) {
                    @mkdir($base, 0777); // creamos directorio con permisos
                    copy($originate, $destiny);
                    copy($originateht, $destinyht);
                } else {
                    copy($originate, $destiny);
                    copy($originateht, $destinyht);
                }
                if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
                    //Si estamos en Prestashop 1.4.x debemos copiar las traducciones a la carpeta del modulo
                    //En 1.5.x están en "/modules/nombremodulo/traslations/"
                    $lng_en_orig = _PS_MODULE_DIR_ . $this->name . '/translations/en.php';
                    $lng_en_dest = _PS_MODULE_DIR_ . $this->name . '/en.php';
                    $lng_es_orig = _PS_MODULE_DIR_ . $this->name . '/translations/es.php';
                    $lng_es_dest = _PS_MODULE_DIR_ . $this->name . '/es.php';
                    $lng_pt_orig = _PS_MODULE_DIR_ . $this->name . '/translations/pt.php';
                    $lng_pt_dest = _PS_MODULE_DIR_ . $this->name . '/pt.php';
                    copy($lng_en_orig, $lng_en_dest);
                    copy($lng_es_orig, $lng_es_dest);
                    copy($lng_pt_orig, $lng_pt_dest);
                }
                $this->writeToFileParam('Instalación finalizada');
                return true;
            }
    }

    /**
     * Procesa la desinstalación del módulo
     * @return Bool
     */
    public function uninstall()
    {
        // Uninstall Config
        $success = //Configuration::deleteByName($this->rename . 'URLWEBSERVICE') &&
        Configuration::deleteByName($this->rename . 'ENTORNO')
        && Configuration::deleteByName($this->rename . 'FRANQUICIA')
        && Configuration::deleteByName($this->rename . 'ABONADO')
        && Configuration::deleteByName($this->rename . 'DEPARTAMENTO')
        && Configuration::deleteByName($this->rename . 'USUARIO')
        && Configuration::deleteByName($this->rename . 'PASSWORD')
        && Configuration::deleteByName($this->rename . 'BEFORE_SENDING_MRW')
        && Configuration::deleteByName($this->rename . 'AFTER_SENDING_MRW')
        && Configuration::deleteByName($this->rename . 'CARRIER_ID_MRW')
        && Configuration::deleteByName($this->rename . 'LAST_EXECUTE_MRW')
        && Configuration::deleteByName($this->rename . 'DNI_REQUIRED_MRW')
        && Configuration::deleteByName($this->rename . 'TICKET_MRW')
        && Configuration::deleteByName($this->rename . 'SERVICE_MRW')
        && Configuration::deleteByName($this->rename . 'AGRUPAR_BULTOS')
        && Configuration::deleteByName($this->rename . 'GESTION_BULTOS')
        && Configuration::deleteByName($this->rename . 'DESGLOSE_BULTOS')
        && Configuration::deleteByName($this->rename . 'MESSAGE_TYPE_MRW')
        && Configuration::deleteByName($this->rename . 'MAIL_WHENSENT_MRW')
        && Configuration::deleteByName($this->rename . 'ACTIVE_DEBUG_MRW');

        // Uninstall Module
        $success &= parent::uninstall()
        && $this->unregisterHook('backOfficeFooter')
        && $this->unregisterHook('updateCarrier')
        && $this->unregisterHook('adminOrder')
        && $this->unregisterHook('DisplayBackOfficeHeader');

        // Uninstall SQL subscribers
        $sql_drop_table_A = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . $this->name . '_subs';
        if (!Db::getInstance()->Execute($sql_drop_table_A)) {
            $success &= false;
        }

        // Uninstall SQL check mrw
        $sql_drop_table_B = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . $this->name .  '_test';
        if (!Db::getInstance()->Execute($sql_drop_table_B)) {
            $success &= false;
        }

        // Uninstall admin tab
        if (!$this->uninstallTab('AdminMrwCarrier'))
            return false;

        return $success;
    }

    /**
     * Permite procesar y visualizar información en la página de configuración del módulo
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier
     */
    public function getContent()
    {
        $output = '
                  <div style="width:90%">
                  <h2 style="float:left">MRW Carrier</h2>
                  <img style="float:right; padding-top:20px" src="../modules/' . $this->name . '/logo.gif" alt="mrw">
                  </div>
                   ';

        if (Tools::isSubmit('submitMRW')) {
            //No se hacen las comprobaciones si se borran los abonados
            if (Tools::getValue('action') != 'delete' && Tools::getValue('action') != 'add') {
                if (!$this->isSubscriber()) {
                    $this->_postErrors[] = $this->l('Debe rellenar todos los campos del formulario');
                }
            }
            if (!$this->_postErrors) {
                $entorno = Tools::getValue('mrw_entorno');
                $urlmrw = Tools::getValue('mrw_url');
                if ($entorno == 'mrw_pre') {
                    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                        // SSL connection
                        $url = 'https://sagec-test.mrw.es/MRWEnvio.asmx';
                    }
                    else { $url = 'http://sagec-test.mrw.es/MRWEnvio.asmx'; }
                } else if ($entorno == 'mrw_pro') {
                    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                        // SSL connection
                        $url = 'https://sagec.mrw.es/MRWEnvio.asmx';
                    }
                    else { $url = 'https://sagec.mrw.es/MRWEnvio.asmx'; }
                } else {
                    $url = '';
                }

                $frq = Tools::getValue('mrw_franquicia');
                $abn = Tools::getValue('mrw_abonado');
                $dpt = Tools::getValue('mrw_departamento');
                $usr = Tools::getValue('mrw_usuario');
                $psw = Tools::getValue('mrw_clave');
                $service_type = Tools::getValue('mrw_servicio');

                Configuration::updateValue($this->rename . 'ENTORNO', $entorno);
                Configuration::updateValue($this->rename . 'FRANQUICIA', $frq);
                Configuration::updateValue($this->rename . 'ABONADO', $abn);
                Configuration::updateValue($this->rename . 'DEPARTAMENTO', $dpt);
                Configuration::updateValue($this->rename . 'USUARIO', $usr);
                Configuration::updateValue($this->rename . 'PASSWORD', $psw);
                Configuration::updateValue($this->rename . 'SERVICE_MRW', $service_type);

                //Actualización de la BBDD de Abonados
                $this->_postProcessSubscriber();
                $paso2 = '';
                if (Configuration::get($this->rename . 'BEFORE_SENDING_MRW') == null or Configuration::get($this->rename . 'AFTER_SENDING_MRW') == null) {
                    $paso2 = '<br><img src="' . _PS_IMG_ . 'admin/forbbiden.gif" alt="nok" />&nbsp;' . $this->l('Ahora deberá completar la configuración del módulo en la pestaña "Configuración Avanzada"');
                }

                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)) {
                    $output .= '
                    <div class="conf confirm" style="clear:both"><img src="../img/admin/ok.gif" alt="" title="" />
                    ' . $this->l('Datos de abonado MRW Actualizados') . '
                    ' . $paso2 . '
                    </div>';
        
                }

                else if (strncmp(_PS_VERSION_, "1.7", 3) == 0){
                    $output .= '
                    <div class="conf confirm" style="clear:both">
                                        <img src="../img/admin/enabled.gif" alt="" title="" />
                                        ' . $this->l('Datos de abonado MRW Actualizados') . '
                                        ' . $paso2 . '
                    </div>';                    
                }

            } else {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('ERRORES:');
                }

                foreach ($this->_postErrors as $err) {
                    $output .= '<div class="alert error" style="clear:both"><img src="' . _PS_IMG_ . 'admin/forbbiden.gif" alt="nok" />&nbsp;' . $err . '<br></div>';
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam($err);
                    }
                }
            }
        }

        if (Tools::isSubmit('submitFrmConfig')) {
            if (!$this->isSubscriber()) {
                $this->_postErrors[] = $this->l('Debe rellenar todos los campos del formulario "Datos de abonado MRW"');
            }

            if (Tools::getValue('mrw_before_sending') == null or Tools::getValue('mrw_after_sending') == null) {
                $this->_postErrors[] = $this->l('Debe configurar los códigos de estado de pedidos que provocarán el procesamiento de los envios a través de MRW');
            }

            if (Tools::getValue('listCarrier') == null) {
                $this->_postErrors[] = $this->l('Es necesario tener seleccionado por lo menos un transportista MRW');
            }

            if (!$this->_postErrors) {
                $febore_sending = Tools::getValue('mrw_before_sending');
                $after_sending = Tools::getValue('mrw_after_sending');
                $mail_whensent = (strtoupper(Tools::getValue('mail_whensent_mrw')) == 'ON') ? 1 : 0;
                $dni_required = (Tools::getValue('op_dni') == 'on' or Tools::getValue('op_dni') == 'ON') ? 1 : 0;
                $gen_ticket = (Tools::getValue('op_ticket') == 'on' or Tools::getValue('op_ticket') == 'ON') ? 1 : 0;
                $agr_bultos = (Tools::getValue('agr_bultos') == 'on' or Tools::getValue('agr_bultos') == 'ON') ? 1 : 0;
                $ges_bultos = (Tools::getValue('ges_bultos') == 'on' or Tools::getValue('ges_bultos') == 'ON') ? 1 : 0;
                $dgl_bultos = (Tools::getValue('dgl_bultos') == 'on' or Tools::getValue('dgl_bultos') == 'ON') ? 1 : 0;

                $message_type = Tools::getValue('mrw_message');
                $listTrans = '';
                foreach (Tools::getValue('listCarrier') as $key => $item) {
                    if ($key <= 0) {
                        $listTrans = $item;
                    } else {
                        $listTrans .= ',' . $item;
                    }
                }

                Configuration::updateValue($this->rename . 'BEFORE_SENDING_MRW', $febore_sending);
                Configuration::updateValue($this->rename . 'AFTER_SENDING_MRW', $after_sending);
                Configuration::updateValue($this->rename . 'MAIL_WHENSENT_MRW', $mail_whensent);
                Configuration::updateValue($this->rename . 'CARRIER_ID_MRW', $listTrans);
                Configuration::updateValue($this->rename . 'DNI_REQUIRED_MRW', $dni_required);
                Configuration::updateValue($this->rename . 'TICKET_MRW', $gen_ticket);
                Configuration::updateValue($this->rename . 'AGRUPAR_BULTOS', $agr_bultos);
                Configuration::updateValue($this->rename . 'MESSAGE_TYPE_MRW', $message_type);
                Configuration::updateValue($this->rename . 'GESTION_BULTOS', $ges_bultos);
                Configuration::updateValue($this->rename . 'DESGLOSE_BULTOS', $dgl_bultos);


                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)) {

                    $output .= '
                    <div class="conf confirm" style="clear:both">
                        <img src="../img/admin/ok.gif" alt="" title="" />
                        ' . $this->l('Datos de configuración Actualizados') . '
                    </div>';
                }

                else if (strncmp(_PS_VERSION_, "1.7", 3) == 0){
                    $output .= '
                    <div class="conf confirm" style="clear:both">
                        <img src="../img/admin/enabled.gif" alt="" title="" />
                        ' . $this->l('Datos de configuración Actualizados') . '
                    </div>';   
                }

            } else {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('ERRORES:');
                }

                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)) {

                    foreach ($this->_postErrors as $err) {
                    $output .= '<div class="alert error" style="clear:both"><img src="' . _PS_IMG_ . 'admin/warning.gif" alt="nok" />&nbsp;' . $err . '<br></div>';
                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam($err);
                        }
                    }

                }
                else if (strncmp(_PS_VERSION_, "1.7", 3) == 0){

                    foreach ($this->_postErrors as $err) {
                    $output .= '<div class="alert error" style="clear:both"><img src="' . _PS_IMG_ . 'admin/error2.gif" alt="nok" />&nbsp;' . $err . '<br></div>';
                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam($err);
                        }
                    }

                }   
            }
        }

        if (Tools::isSubmit('submitFrmDebug')) {

            if (!$this->isSubscriber()) {
                $this->_postErrors[] = $this->l('Debe rellenar todos los campos del formulario "Datos de abonado MRW"');
            }
            if (Tools::getValue('ActivarDebug') == null) {
                $this->_postErrors[] = $this->l('No ha llegado el valor de activación del registro');
            }
            if (!$this->_postErrors) {
                $debug_activado = (Tools::getValue('ActivarDebug') == '1' ? '1' : '0');
                Configuration::updateValue($this->rename . 'ACTIVE_DEBUG_MRW', $debug_activado);
                if ($debug_activado == '1') {
                    $this->writeToFileParam('Activando debug (' . date('Y-m-d H:i:s') . ')');
                } else {
                    $this->writeToFileParam('Desactivando debug (' . date('Y-m-d H:i:s') . ')');
                }

                $this->debug_activo = $debug_activado;
            } else {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('ERRORES:');
                }

                foreach ($this->_postErrors as $err) {
                    $output .= '<div class="alert error" style="clear:both"><img src="' . _PS_IMG_ . 'admin/forbbiden.gif" alt="nok" />&nbsp;' . $err . '<br></div>';
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam($err);
                    }
                }
            }
        }

        return $output . $this->displayForm();
    }

    /**
     * Procesa contenido para mostrar contenido de la página de configuración del módulo. Información General del módulo.
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier
     */
    public function displayForm()
    {
        if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {
            $_tab = 'AdminParentShipping';
            $_subtab = 'AdminCarriers';
            $prm = 'controller';
        } else if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
            $_tab = 'AdminShipping';
            $_subtab = 'AdminCarriers';
            $prm = 'tab';
        }

        $processSoap = (class_exists('SoapClient')) ? '' : '<div class="warn" style="color:#FF0000">' . $this->l('No se ha detectado la Clase SOAP en el servidor. Asegúrese de tener activo la libreria SOAP de php. Es imprescindible tener activa esta libreria para el funcionamiento del modulo mrw') . '</div>';
        $pathDir = _PS_DOWNLOAD_DIR_ . '/ticket_mrw';
        $newfile = 'myfile.txt';
        $pathModuleLog = _PS_MODULE_DIR_ . $this->name . '/' . $this->name . '.log';
        $testDirTicketMRW = $this->verificFileDir($pathDir, $newfile);

        $procDirectory = ($testDirTicketMRW['dir'] != 'direxist') ? $testDirTicketMRW['dir'] : '';
        $procFileTikect = ($testDirTicketMRW['file'] != 'filecreateok') ? $testDirTicketMRW['file'] : '';
        $procFileLog = (is_writable($pathModuleLog)) ? '' : '<div class="warn" style="color:#FF0000">' . $this->l('El archivo de logs mrwcarrier.log no tiene permiso de escritura.') . '</div>';

        $output = '
        <div style="width:90%"><fieldset class="space" style="clear:both">
            <legend><img src="../img/admin/unknown.gif" alt="" class="middle" />' . $this->l('Cómo usar MRWCarrier') . '</legend>
            <div style="float:left; width:60%">
                        ' . $processSoap . '
                        ' . $procDirectory . '
                        ' . $procFileTikect . '
                        ' . $procFileLog . '
                <p>' . $this->l('Antes de empezar, asegúrese de tener los datos de SAGEC MRW') . '</p>
                <p>' . $this->l('En primer lugar debe configurar la conexión con MRW<br />Introduzca los siguientes datos que le habrán sido facilitados por su franquicia:') . '</p>
                <ul style="list-style-type:disc; margin-left:30px;">
                <li>' . $this->l('- Nº de franquicia') . '</li>
                <li>' . $this->l('- Nº de abonado') . '</li>
                <li>' . $this->l('- Departamento') . '</li>
                <li>' . $this->l('- Usuario') . '</li>
                <li>' . $this->l('- Contraseña') . '</li>
                </ul>
                <p>' . $this->l('En segundo lugar deberá configurar el resto de parámetros del módulo en la pestaña "Configuración Avanzada"') . '</p>
                <p>' . $this->l('Por último deberá configurar los parametros del transportista MRW que se ha creado al instalar el modulo.') . '<br /> &raquo; <a href="index.php?' . $prm . '=AdminCarriers&token=' . $this->getAdminTabToken($_tab, $_subtab) . '">' . $this->l('Administrar Transportistas') . '</a></p>
            </div>
            <div style="float:right; width:40%;"><a href="http://www.mrw.es/" target="_blank"><img src="../modules/' . $this->name . '/img_oficina.jpg" alt="MRW Iberia - Nos movemos por ti"></a></div>
        </fieldset></div>';
        $sel_entorno = Configuration::get($this->rename . 'ENTORNO');
        $sel_pre = ($sel_entorno == 'mrw_pre') ? ' selected' : '';
        $sel_pro = ($sel_entorno == 'mrw_pro') ? ' selected' : '';

        $output .= $this->displayFormSetting();
        return $output;
    }

    /**
     * Procesa contenido para mostrar en las capas de "Datos de abonado MRW", "Configuración Avanzada" y "Ayuda - Soporte"
     * @return String $html, html
     * @display Backoffice->Modules->MRW Carrier -> Datos de confgiruación módulo MRW
     */
    public function displayFormSetting()
    {
        $selTab1 = (isset($_POST['fmrMRW'])) ? 'selected' : '';
        $selTab2 = (isset($_POST['fmrConfigMRW'])) ? 'selected' : '';

        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0))
        {

            $html = '
                <fieldset class="space">
                    <legend><img src="../img/admin/cog.gif" alt="" class="middle" />' . $this->l('Datos de configuración módulo MRW') . ' - (' . $this->name . ')</legend>
                <ul id="menuTab">
                    <li id="menuTab1" class="menuTabButton selected"><img src="../img/admin/supplier.gif" alt="" class="middle" />' . $this->l('Datos de abonado MRW') . '</li>
                    <li id="menuTab2" class="menuTabButton"><img src="../img/admin/prefs.gif" alt="" class="middle" />' . $this->l('Configuración Avanzada') . '</li>
                    <li id="menuTab4" class="menuTabButton"><img src="../img/admin/unknown.gif" alt="" class="middle" />' . $this->l('Ayuda - Soporte') . '</li>
                </ul>
                <div id="tabList">
                    <div id="menuTab1Sheet" class="tabItem selected">' . $this->displayFormSubscriber() . '</div>
                    <div id="menuTab2Sheet" class="tabItem">' . $this->displayFrmConfig() . '</div>
                    <div id="menuTab4Sheet" class="tabItem">' . $this->displayInstall() . '</div>
                </div>
                <br clear="left" />
                <br />
                <style>
                    #menuTab { float: left; padding: 0; margin: 0; text-align: left; }
                    #menuTab li { text-align: left; float: left; display: inline; padding: 5px; padding-right: 10px; background: #EFEFEF; font-weight: bold; cursor: pointer; border-left: 1px solid #EFEFEF; border-right: 1px solid #EFEFEF; border-top: 1px solid #EFEFEF; margin-left:5px }
                    #menuTab li.menuTabButton.selected { background: #FFF6D3; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC; }
                    #tabList { clear: left; }
                    .tabItem { display: none; }
                    .tabItem.selected { display: block; background: #FFFFF0; border: 1px solid #CCCCCC; padding: 10px; padding-top: 20px; }
                </style>
                <script>
                    $(".menuTabButton").click(function () {
                      $(".menuTabButton.selected").removeClass("selected");
                      $(this).addClass("selected");
                      $(".tabItem.selected").removeClass("selected");
                      $("#" + this.id + "Sheet").addClass("selected");
                    });
                </script>
                </fieldset>
            ';
            if (isset($_GET['id_tab'])) {
                $html .= '<script>
                      $(".menuTabButton.selected").removeClass("selected");
                      $("#menuTab' . Tools::getValue('id_tab') . '").addClass("selected");
                      $(".tabItem.selected").removeClass("selected");
                      $("#menuTab' . Tools::getValue('id_tab') . 'Sheet").addClass("selected");
                </script>';
            }
        }
        else if (strncmp(_PS_VERSION_, "1.7", 3) == 0){
             $html = '
                <div style="width:90%"><fieldset class="space">
                    <legend><img src="../img/admin/asterisk.gif" alt="" class="middle" />' . $this->l('Datos de configuración módulo MRW') . ' - (' . $this->name . ')</legend>
                <ul id="menuTab">
                    <li id="menuTab1" class="menuTabButton selected"><img src="../img/admin/coupon.gif" alt="" class="middle" />' . $this->l('Datos de abonado MRW') . '</li>
                    <li id="menuTab2" class="menuTabButton"><img src="../img/admin/edit.gif" alt="" class="middle" />' . $this->l('Configuración Avanzada') . '</li>
                    <li id="menuTab4" class="menuTabButton"><img src="../img/admin/unknown.gif" alt="" class="middle" />' . $this->l('Ayuda - Soporte') . '</li>
                </ul>
                <div id="tabList">
                    <div id="menuTab1Sheet" class="tabItem selected">' . $this->displayFormSubscriber() . '</div>
                    <div id="menuTab2Sheet" class="tabItem">' . $this->displayFrmConfig() . '</div>
                    <div id="menuTab4Sheet" class="tabItem">' . $this->displayInstall() . '</div>
                </div>
                <br clear="left" />
                <br />
                <style>
                    #menuTab { float: left; padding: 0; margin: 0; text-align: left; }
                    #menuTab li { text-align: left; float: left; display: inline; padding: 5px; padding-right: 10px; background: #EFEFEF; font-weight: bold; cursor: pointer; border-left: 1px solid #EFEFEF; border-right: 1px solid #EFEFEF; border-top: 1px solid #EFEFEF; margin-left:5px }
                    #menuTab li.menuTabButton.selected { background: #FFF6D3; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC; }
                    #tabList { clear: left; }
                    .tabItem { display: none; }
                    .tabItem.selected { display: block; background: #FFFFF0; border: 1px solid #CCCCCC; padding: 10px; padding-top: 20px; }
                </style>
                <script>
                    $(".menuTabButton").click(function () {
                      $(".menuTabButton.selected").removeClass("selected");
                      $(this).addClass("selected");
                      $(".tabItem.selected").removeClass("selected");
                      $("#" + this.id + "Sheet").addClass("selected");
                    });
                </script>
                </fieldset></div>
            ';
            if (isset($_GET['id_tab'])) {
                $html .= '<script>
                      $(".menuTabButton.selected").removeClass("selected");
                      $("#menuTab' . Tools::getValue('id_tab') . '").addClass("selected");
                      $(".tabItem.selected").removeClass("selected");
                      $("#menuTab' . Tools::getValue('id_tab') . 'Sheet").addClass("selected");
                </script>';
            }
        }

        return $html;
    }

    /**
     * Procesa contenido para mostrar en la capa "Datos de abonado MRW" de la página de configuración del módulo
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier->Datos de abonado MRW
     */
    private function displayFormSubscriber()
    {
        // Display header
        $output = '<p><b>' . $this->l('En esta sección, puede añadir todos los abonados disponibles') . '</b></p><br />
        <table class="table tableDnD" cellpadding="0" cellspacing="0" width="90%">
            <thead>
                <tr class="nodrag nodrop">
                    <th>' . $this->l('Nombre') . '</th>
                    <th>' . $this->l('Entorno') . '</th>
                    <th>' . $this->l('Franquicia') . '</th>
                    <th>' . $this->l('Abonado') . '</th>
                    <th>' . $this->l('Departamento') . '</th>
                    <th>' . $this->l('Usuario') . '</th>
                    <th>' . $this->l('Password') . '</th>
                    <th>' . $this->l('Servicio') . '</th>
                    <th>' . $this->l('Por defecto') . '</th>
                    <th>' . $this->l('Acciones') . '</th>
                </tr>
            </thead>
            <tbody>';

        // Loading subscriber list. Depends on the version
        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
            $shop = 1;
        } else {
            $shop = $this->context->shop->id;
        }

        $configCategoryList = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_subs` where id_shop= ' . $shop);

        if (!$configCategoryList) {
            $output .= '<tr><td colspan="10">' . $this->l('No hay Abonados de MRW configurados') . '</td></tr>';
        }

        //Loading each and every Subscriber, including the Edit/Delete option
        foreach ($configCategoryList as $k => $c) {
            $alt = 0;
            if ($k % 2 != 0) {
                $alt = ' class="alt_row"';
            }

            $output .= '
                <tr' . $alt . '>
                    <td>' . $c['id_name'] . '</td>
                    <td>' . $c['environment'] . '</td>
                    <td>' . $c['agency'] . '</td>
                    <td>' . $c['subscriber'] . '</td>
                    <td>' . $c['department'] . '</td>
                    <td>' . $c['user'] . '</td>
                    <td>' . $c['password'] . '</td>
                    <td>' . $c['service'] . '</td>
                    <td>' . $c['is_default'] . '</td>
                    <td>
                        <a href="index.php?tab=' . Tools::safeOutput(Tools::getValue('tab')) . '&configure=' . Tools::safeOutput(Tools::getValue('configure')) . '&token=' . Tools::safeOutput(Tools::getValue('token')) . '&tab_module=' . Tools::safeOutput(Tools::getValue('tab_module')) . '&module_name=' . Tools::safeOutput(Tools::getValue('module_name')) . '&id_tab=1&action=edit&id_subscriber=' . (int) ($c['id_subscriber']) . '" style="float: left;">
                            <img src="' . _PS_IMG_ . 'admin/edit.gif" />
                        </a>
                        <form action="index.php?tab=' . Tools::safeOutput(Tools::getValue('tab')) . '&configure=' . Tools::safeOutput(Tools::getValue('configure')) . '&token=' . Tools::safeOutput(Tools::getValue('token')) . '&tab_module=' . Tools::safeOutput(Tools::getValue('tab_module')) . '&module_name=' . Tools::safeOutput(Tools::getValue('module_name')) . '&id_tab=1&action=deleteSub&id_subscriber=' . (int) ($c['id_subscriber']) . '" method="post" class="form" style="float: left;">
                            <input name="submitMRW" type="image" src="' . _PS_IMG_ . 'admin/delete.gif" OnClick="return confirm(\'' . $this->l('¿Estás seguro que quiere eliminar el Abonado?') . '\');" />
                        </form>
                    </td>
                </tr>';
        }

        $output .= '
            </tbody>
        </table><br /><br />';

        // Add or Edit Category Configuration
        $servAct = '';
        $serviceMRW = '';
        if (Tools::getValue('action') == 'edit') {
            // Loading config
            $configSelected = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_subs` where id_subscriber= ' . (int) (Tools::getValue('id_subscriber')));
            $output .= '<p align="center"><b>' . $this->l('Actualizar Abonado') . ' (<a href="index.php?tab=' . Tools::safeOutput(Tools::getValue('tab')) . '&configure=' . Tools::safeOutput(Tools::getValue('configure')) . '&token=' . Tools::safeOutput(Tools::getValue('token')) . '&tab_module=' . Tools::safeOutput(Tools::getValue('tab_module')) . '&module_name=' . Tools::safeOutput(Tools::getValue('module_name')) . '&id_tab=1&action=add">' . $this->l('Añadir Abonado') . ' ?</a>)</b></p>';
            $output .= '
                        <form action="' . $_SERVER['REQUEST_URI'] . '&action=edit&id_subscriber=' . (int) (Tools::getValue('id_subscriber')) . '"  method="post" name="fmrMRW" > ';

            //Pantalla con los datos a Solicitar
            $config_name = $configSelected['id_name'];
            $sel_entorno = $configSelected['environment'];
            $sel_pre = ($sel_entorno == 'mrw_pre') ? ' selected' : '';
            $sel_pro = ($sel_entorno == 'mrw_pro') ? ' selected' : '';
            $servAct = $configSelected['service'];
            $serviceMRW = $this->getServiceMRW($servAct);

            $agency = $configSelected['agency'];
            $subscriber = $configSelected['subscriber'];
            $department = $configSelected['department'];
            $user = $configSelected['user'];
            $password = $configSelected['password'];
            $sel_default = $configSelected['is_default'];
        } else {
            //Pantalla con los datos a Solicitar
            $sel_entorno = Configuration::get($this->rename . 'ENTORNO');
            $sel_pre = ($sel_entorno == 'mrw_pre') ? ' selected' : '';
            $sel_pro = ($sel_entorno == 'mrw_pro') ? ' selected' : '';

            //Si no existen Abonados por defecto se indica que es el primero a añadir
            $sel_default = '';
            $servAct = (Configuration::get($this->rename . 'SERVICE_MRW') != '') ? Configuration::get($this->rename . 'SERVICE_MRW') : '';
            $serviceMRW = $this->getServiceMRW($servAct);

            $output .= '<p align="center"><b>' . $this->l('Añadir un Abonado') . '</b></p>';
            $output .= '
                        <form action="' . $_SERVER['REQUEST_URI'] . '&action=add&id_subscriber=' . (int) (Tools::getValue('id_subscriber')) . '"  method="post" name="fmrMRW" > ';

            $config_name = '';
            $agency = '';
            $subscriber = '';
            $department = '';
            $user = '';
            $password = '';
        }

        $output .= '
                <label>' . $this->l('Entorno de ejecución MRW') . '</label>
                <div class="margin-form">
                    <select name="mrw_entorno" id="mrw_entorno">
                        <option></option>
                        <option value="mrw_pre" ' . $sel_pre . '>' . $this->l('Entorno de pruebas') . '</option>
                        <option value="mrw_pro" ' . $sel_pro . '>' . $this->l('Entorno REAL') . '</option>
                    </select>
                    <p class="clear">' . $this->l('Tras la instalación del módulo se recomienda realizar varios envíos ficticios en el entorno de pruebas para verificar que el módulo está correctamente configurado.') . '</p>
                </div>
                <input type="hidden" name="mrw_url" value="' . Tools::getValue('mrw_url', Configuration::get($this->rename . 'URLWEBSERVICE')) . '" />
                <label>' . $this->l('Nombre Configuracion') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_configuracion" value="' . $config_name . '" />
                    <p class="clear">' . $this->l('Deberá rellenarlo para identificar la configuración. Máximo 25 caracteres') . '</p>
                </div>
                <label>' . $this->l('Franquicia') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_franquicia" value="' . trim($agency) . '" />
                    <p class="clear">' . $this->l('Ejemplo:') . ' 0XXXX</p>
                </div>
                <label>' . $this->l('Abonado') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_abonado" value="' . trim($subscriber) . '" />
                    <p class="clear">' . $this->l('Ejemplo:') . ' XXXXXX</p>
                </div>
                <label>' . $this->l('Departamento') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_departamento" value="' . trim($department) . '" />
                    <p class="clear">' . $this->l('Sólo deberá rellenarlo si se lo ha proporcionado su franquícia. Por defecto estará vacío') . '</p>
                </div>
                <label>' . $this->l('Usuario') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_usuario" value="' . trim($user) . '" />
                    <p class="clear">' . $this->l('Ejemplo:') . ' SGCXXXXXXXXXXXX</p>
                </div>
                <label>' . $this->l('Contraseña') . '</label>
                <div class="margin-form">
                    <input type="text" name="mrw_clave" value="' . trim($password) . '" />
                    <p class="clear">' . $this->l('Ejemplo:') . ' XXXXXXXXXXXXXXXX</p>
                </div>

                <label>' . $this->l('Tipo de servicio') . '</label>
                <div class="margin-form">
                    <select name="mrw_servicio" id="mrw_servicio">
                        ' . $serviceMRW . '
                    </select>
                    <p class="clear">' . $this->l('Tipo de servicio MRW por defecto por el que saldrán sus envíos. Su franquicia le indicará cuáles tiene disponibles.') . '</p>
                </div>

                <label>' . $this->l('Abonado por defecto') . '</label>
                <div class="margin-form"> ';

        if ($sel_default == 'S' || $sel_default == '') {
            $output .= '<input type="radio" name="is_default" value="S" checked="checked"/>';
        } else {
            $output .= '<input type="radio" name="is_default" value="S"/>';
        }

        $output .=
        '
                    <label class="t" for="active_on">
                         <img src="../img/admin/enabled.gif" alt="Activado" title="Activado" />
                    </label>';

        if ($sel_default == 'S' || $sel_default == '') {
            $output .= '<input type="radio" name="is_default" value="N" />';
        } else {
            $output .= '<input type="radio" name="is_default" value="N" checked= "checked" />';
        }

        $output .=
        '
                    <label class="t" for="active_off">
                         <img src="../img/admin/disabled.gif" alt="Desactivado" title="Desactivado" />
                    </label>
                    <p class="clear">' . $this->l('Abonado MRW por defecto por el que saldrán sus envíos.') . '</p>
                </div>
                <center><input type="submit" name="submitMRW" value="' . $this->l('Actualizar') . '" class="button" /></center>
            </form>';
        return $output;
    }

    /**
     * Procesa contenido al actualizar el mantenimiento de abonados
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier->Mantenimiento Abonados
     */
    private function _postProcessSubscriber()
    {
        // Init Var
        $date = date('Y-m-d H:i:s');
        $cfg = Tools::getValue('mrw_configuracion');
        $frq = trim(Tools::getValue('mrw_franquicia'));
        $abn = trim(Tools::getValue('mrw_abonado'));
        $dpt = trim(Tools::getValue('mrw_departamento'));
        $usr = trim(Tools::getValue('mrw_usuario'));
        $psw = trim(Tools::getValue('mrw_clave'));
        $service_type = Tools::getValue('mrw_servicio');
        $entorno = Tools::getValue('mrw_entorno');
        $is_default = Tools::getValue('is_default') == 'S' ? Tools::getValue('is_default') : 'N';
        if ($is_default == 'S') {
            Configuration::updateValue($this->rename . 'ENTORNO', $entorno);
            Configuration::updateValue($this->rename . 'FRANQUICIA', $frq);
            Configuration::updateValue($this->rename . 'ABONADO', $abn);
            Configuration::updateValue($this->rename . 'DEPARTAMENTO', $dpt);
            Configuration::updateValue($this->rename . 'USUARIO', $usr);
            Configuration::updateValue($this->rename . 'PASSWORD', $psw);
            Configuration::updateValue($this->rename . 'SERVICE_MRW', $service_type);
        }

        // Add Script
        if (Tools::getValue('action') == 'add') {
            if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
                $shop = 1;
            } else {
                $shop = $this->context->shop->id;
            }

            //Si existe un abonado por defecto se actualiza el resto a N
            if ($is_default == 'S') {
                $sql_mrw_subscriber = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_subs
                                       SET is_default = "N"
                                       WHERE id_shop= ' . $shop;
                if (!Db::getInstance()->Execute($sql_mrw_subscriber)) {
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('_postProcessSubscriber:-> ERROR: No se ha logrado modificar el Abonado por Defecto en la tabla ' . _DB_PREFIX_ . $this->name . '_subs');
                    }
                }
            }

            /* Realizamos el insert en la BBDD con los nuevos datos del subscriber */
            $sql_mrw_subscriber =
            'INSERT INTO ' . _DB_PREFIX_ . $this->name . '_subs
                    (
                            `id_subscriber`, `id_shop`,`id_name`, `environment`, `agency`, `subscriber`, `department`, `user`, `password`, `service`,`is_default`,`date`
                    )
                            VALUES
                    (
                            NULL , ' . $shop . ' , "' . $cfg . '", "' . $entorno . '", "' . $frq . '", "' . $abn . '", "' . $dpt . '", "' . $usr . '", "' . $psw . '", "' . $service_type . '", "' . $is_default . '", NOW()
                    )';

            if (!Db::getInstance()->Execute($sql_mrw_subscriber)) {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('_postProcessSubscriber:-> ERROR: No se ha logrado insertar el Abonado (' . $abn . ') con departamento (' . $dpt . ') en la tabla ' . _DB_PREFIX_ . $this->name . '_subs');
                }
            }

            $id_subscriber_get = Db::getInstance()->Insert_ID();
        }

        // Update Script
        if (Tools::getValue('action') == 'edit' && Tools::getValue('id_subscriber')) {
            $id_subscriber = Tools::getValue('id_subscriber');
            if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                $shop = 1;
            } else {
                $shop = $this->context->shop->id;
            }

            //Si existe un abonado por defecto se actualiza el resto a N
            if ($is_default == 'S') {
                $sql_mrw_subscriber = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_subs
                                       SET is_default = "N"
                                       where id_shop = ' . $shop;

                if (!Db::getInstance()->Execute($sql_mrw_subscriber)) {
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('_postProcessSubscriber:-> ERROR: No se ha logrado modificar el Abonado por Defecto en la tabla ' . _DB_PREFIX_ . $this->name . '_subs');
                    }
                }
            }

            $sql_mrw_subscriber = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_subs
                                   SET environment = "' . $entorno . '", id_name = "' . $cfg . '", agency = "' . $frq . '" , subscriber = "' . $abn . '",
                                       department = "' . $dpt . '",user = "' . $usr . '",password = "' . $psw . '",
                                       service = "' . $service_type . '",is_default = "' . $is_default . '"
                                    WHERE id_subscriber =' . $id_subscriber;

            if ($this->debug_activo == '1') {
                $this->writeToFileParam('_postProcessSubscriber:-> SQL = ' . $sql_mrw_subscriber . '');
            }

            if (!Db::getInstance()->Execute($sql_mrw_subscriber)) {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('_postProcessSubscriber:-> ERROR: No se ha logrado actualizar el Abonado (' . $abn . ') con departamento (' . $dpt . ') en la tabla ' . _DB_PREFIX_ . $this->name . '_subs');
                }
            }
        }

        // Delete Script
        if (Tools::getValue('action') == 'deleteSub' && Tools::getValue('id_subscriber')) {
            $resultDelete = Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . $this->name . '_subs` WHERE `id_subscriber` = ' . (int) Tools::getValue('id_subscriber'));
            // Display Results
            if ($resultDelete) {
                $this->displayConfirmation($this->l('Settings updated'));
            } else {
                $this->displayErrors($this->l('Settings failed'));
            }
        }
    }

    /**
     * Procesa contenido para mostrar en la capa "Configuración Avanzada" de la página de configuración del módulo
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier->Configuración
     */
    public function displayFrmConfig()
    {
        global $cookie;
        $employee_lang = new Employee((int) $cookie->id_employee);
        $langAct = (int) $employee_lang->id_lang;
        $lang = (isset($langAct) and $langAct != '') ? $langAct : '2';

        $statesBefore = $this->getOrderStates($lang, Configuration::get($this->rename . 'BEFORE_SENDING_MRW'));
        $statesAfter = $this->getOrderStates($lang, Configuration::get($this->rename . 'AFTER_SENDING_MRW'));
        $stateTrans = Configuration::get($this->rename . 'CARRIER_ID_MRW');
        $arrST = explode(',', $stateTrans);

        $isCarrier = "
            SELECT distinct c.* FROM " . _DB_PREFIX_ . "carrier AS c, " . _DB_PREFIX_ . "carrier_lang AS cl
            WHERE cl.id_carrier=c.id_carrier AND cl.id_lang=" . $lang . " AND c.active=1 AND c.deleted=0 AND c.name LIKE '%MRW%'";
        $myCarrier = Db::getInstance()->ExecuteS($isCarrier);

        $listChkb = '';
        if ($myCarrier != '') {
            foreach ($myCarrier as $item) {
                $checked = (in_array($item['id_carrier'], $arrST)) ? 'checked' : ''; // si esta en array
                $listChkb .= '<span style="color:#000; font-size:12px "><input type="checkbox" name="listCarrier[]" value="' . $item['id_carrier'] . '" ' . $checked . '>  ' . $item['id_carrier'] . ' - ' . $item['name'] . '</span><br>';
            }
        }

        $mail_whensent_state = (Configuration::get($this->rename . 'MAIL_WHENSENT_MRW') == '1') ? 'checked' : '';
        $dni_state = (Configuration::get($this->rename . 'DNI_REQUIRED_MRW') == '1') ? 'checked' : '';
        $ticket_state = (Configuration::get($this->rename . 'TICKET_MRW') == '1') ? 'checked' : '';
        $agr_bultos = (Configuration::get($this->rename . 'AGRUPAR_BULTOS') == '1') ? 'checked' : '';
        $ges_bultos = (Configuration::get($this->rename . 'GESTION_BULTOS') == '1') ? 'checked' : '';
        $dgl_bultos_state = (Configuration::get($this->rename . 'DESGLOSE_BULTOS') == '1') ? 'checked' : '';

        $sel_msg = Configuration::get($this->rename . 'MESSAGE_TYPE_MRW');
        $sel_nada = ($sel_msg == 'desactivo') ? 'selected' : '';
        $sel_mail = ($sel_msg == 'email') ? 'selected' : '';
        $sel_sms = ($sel_msg == 'sms') ? 'selected' : '';
        $sel_smsmail = ($sel_msg == 'sms+email') ? 'selected' : '';

        $output = '
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" name="fmrConfigMRW">
                                <p><strong>' . $this->l('Seleccione los estados de pedidos para gestionar el proceso de envio a través de MRW') . '</strong></p><br>
                <label style="font-weight:normal">' . $this->l('Pedidos listos para enviar a MRW') . '</label>
                <div class="margin-form">
                    <select name="mrw_before_sending" id="mrw_before_sending">
                      ' . $statesBefore . '
                    </select>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Cambiar a este estado cuando se haya generado la etiqueta') . '</label>
                <div class="margin-form">
                    <select name="mrw_after_sending" id="mrw_after_sending">
                      ' . $statesAfter . '
                    </select>
                </div>
                    <br>
                <label style="font-weight:normal">' . $this->l('Selecciona el/los transportistas MRW') . '</label>
                <div class="margin-form">
                    ' . $listChkb . '
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Enviar mensaje al comprador después de transmitir a MRW') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="mail_whensent_mrw" id="mail_whensent_mrw" ' . $mail_whensent_state . '>
                    <span class="clear">' . $this->l('Si está activo se enviará al comprador un email con los datos de seguimiento una vez que se haya transmitido la solicitud del servicio a MRW.') . '</span>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Campo DNI obligatorio') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="op_dni" id="op_dni" ' . $dni_state . '>
                    <span class="clear">' . $this->l('Si está activo y el pedido no tiene DNI, no se enviará la solicitud a MRW') . '</span>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Generar etiqueta de envío de forma automática') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="op_ticket" id="op_ticket" ' . $ticket_state . '>
                    <span class="clear">' . $this->l('Si está activo, cada vez que se realice la solicitud de envio se generará/n automáticamente la/s etiqueta/s de envio. Si está desactivado el administrador deberá generar manualmente la etiqueta de envío desde la ficha del pedido') . '</span>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Cómo desea que MRW avise a sus clientes') . '</label>
                <div class="margin-form">
                    <select name="mrw_message" id="mrw_message">
                      <option value="desactivo" ' . $sel_nada . '>Desactivado</option>
                      <option value="email" ' . $sel_mail . '>Email</option>
                      <option value="sms" ' . $sel_sms . '>SMS</option>
                      <option value="sms+email" ' . $sel_smsmail . '>SMS + EMAIL</option>
                    </select>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Permitir cambiar número de bultos') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="ges_bultos" id="ges_bultos" ' . $ges_bultos . '>
                    <span class="clear">' . $this->l('Si está activo, en la administración del pedido se mostrará un formulario para cambiar el número de bultos del pedido antes de transmitirlo a MRW') . '</span>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Agrupar en 1 bulto por pedido') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="agr_bultos" id="agr_bultos" ' . $agr_bultos . '>
                    <span class="clear">' . $this->l('(recomendado) Si está activo, se generarán los envíos a MRW por defecto con un bulto/paquete por pedido. Si se deja inactivo se creará el envío a MRW con un bulto/paquete por cada artículo del pedido.') . '</span>
                </div>
                <br>
                <label style="font-weight:normal">' . $this->l('Realizar desglose de bultos') . '</label>
                <div class="margin-form">
                    <input type="checkbox" name="dgl_bultos" id="dgl_bultos" ' . $dgl_bultos_state . '>
                    <span class="clear">' . $this->l('Activar solamente si se lo indica su franquicia MRW.') . '</span>
                </div>

                <div>
                  <p><strong>' . $this->l('Módulos de pago contrareembolso compatibles') . ' </strong>: cashondelivery, cashondeliveryplus, customcashondelivery, cashondeliverywithfee ,cashondeliveryfeedep, codfee,deluxecodfees, lc_paywithfee, megareembolso, maofree_cashondeliveryfee, maofree2_cashondeliveryfee, esp_contrareembolso, reembolsocargo, pago_reembolso_cargo15, contrareembolso, bacodwithfees, cashondeliverymod, pscodfee, imaxcontrareembolso, webubicod, idxcodfees y codfeeiw</p>
                </div>
                <br>

                <center><input type="submit" name="submitFrmConfig" value="' . $this->l('Actualizar') . '" class="button" /></center>
        </form>';
        return $output;
    }

    /**
     * Procesa el contenido para mostrar en la capa "Instrucciones de Instalación" de la página de configuración del módulo
     * @return String $output, html
     * @display Backoffice->Modules->MRW Carrier->Instrucciones de Instalación
     */
    public function displayInstall()
    {

        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)) {

            if (file_exists(_PS_ROOT_DIR_ . '/modules/' . $this->name . '/' . $this->name . '.log')) {
            $showlogfile = '<p><a href="../modules/' . $this->name . '/download.php?f=' . $this->name . '.log" target="_blank"><img src="../img/admin/download_page.png" alt="" class="middle" /><strong>' . $this->l('Ver archivo log del módulo') . '</strong></a></p>';

            $showlogfile .= '<p><a style="text-decoration:none;" href="../modules/' . $this->name . '/delete.php?f=' . $this->name . '.log" target="_blank"><strong style="color:#FFFFF0">' . $this->l('Borrar log') . '</strong></a></p>';

            } else {
                $showlogfile = '';
            }

        }else if (strncmp(_PS_VERSION_, "1.7", 3) == 0){
            if (file_exists(_PS_ROOT_DIR_ . '/modules/' . $this->name . '/' . $this->name . '.log')) {
            $showlogfile = '<p><a href="../modules/' . $this->name . '/download.php?f=' . $this->name . '.log" target="_blank"><img src="../img/admin/remove_stock.png" alt="" class="middle" /><strong>' . $this->l('Ver archivo log del módulo') . '</strong></a></p>';

            $showlogfile .= '<p><a style="text-decoration:none;" href="../modules/' . $this->name . '/delete.php?f=' . $this->name . '.log" target="_blank"><strong style="color:#FFFFF0">' . $this->l('Borrar log') . '</strong></a></p>';

            } else {
                $showlogfile = '';
            }
        }
        

        $selactivo = '';
        $selinactivo = '';
        if ($this->debug_activo == '1') {
            $selactivo = 'selected';
        } else {
            $selinactivo = 'selected';
        }

        $output = '
                    <div>
                        <p class="bullet">' . $this->l('¿Necesita localizar la franquicia que le corresponde?') . '
                        <form name="buscaFRQ" action="http://www.mrw.es/oficina_transporte_urgente/MRW_buscador_franquicia.asp" target="_blank">
                        <label>' . $this->l('Introduzca su Código Postal o su Provincia a continuación:') . '</label>
                        <div class="margin-form"><input type="text" name="mrw-finder-offices-code" size="8" maxlength="50">
                        <input type="submit" name="mrw-finder-offices-btn" value="' . $this->l('Encontrar') . '"></div>
                        </form>
                        </p>
                        <p class="bullet">' . $this->l('Tarifas y servicios MRW:') . ' <a href="http://www.mrw.es" target="_blank">http://www.mrw.es</a></p>
                        <p class="bullet">' . $this->l('Soporte técnico:') . ' <a href="mailto:integracion@mrw.es">integracion@mrw.es</a></p>
                        <p class="bullet">' . $this->l('Más información sobre el módulo en') . ' <a href="http://www.mrwecommerce.com" target="_blank">http://www.mrwecommerce.com</a></p>
                        <div class="warn"><h4>' . $this->l('REGISTRO AVANZADO Y DEPURACION') . '</h4>
                        ' . $this->l('Activar esta opción únicamente cuando se lo solicite el personal de soporte de MRW.') . '<br />
                        <form name="activaDBG" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Depuración avanzada:') . '</label>
                            <div class="margin-form">
                                <select name="ActivarDebug">
                                    <option value="0" ' . $selinactivo . '>' . $this->l('Desactivada') . ' </option>
                                    <option value="1" ' . $selactivo . '>' . $this->l('Activada') . ' </option>
                                </select>
                                <input type="submit" name="submitFrmDebug" value="' . $this->l('Actualizar') . '" class="button" />
                            </div>
                        </form>
                        <div>' . $showlogfile . '</div>
                        </div>
                    </div>
        ';
        return $output;
    }

    /**
     * Verifica si las variables necesarias para el proceso en envio estan registradas
     * Procesa la solicitud de envio a mrw de forma manual ó automatica
     * Verifica si la conexión con el webservice MRW se ha realizado de forma satisfactorio
     * @param $id_order
     * @return Bool True/False
     */
    public function executeSendMRW($id_order = '')
    {
        if (!$this->isSubscriber()) {
            return false;
        } else {
            $id_order_state_send = Configuration::get($this->rename . 'BEFORE_SENDING_MRW');
            $id_order_stateOK = Configuration::get($this->rename . 'AFTER_SENDING_MRW'); // una vez generado la solicitud de envio MRW creamos un nuevo estado del pedido
            if ($id_order != '') {
                // one order
                if ($this->isCarrierMRW($id_order)) {// control id carrier for send MRW
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('executeSendMRW:mono-> Vamos a generar envío MRW para 1 pedido (' . $id_order . ')');
                    }

                    if ($this->sendCarrierMRW($id_order, false) == true) {
                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam('executeSendMRW:mono-> El envío MRW para el pedido (' . $id_order . ') se ha generado');
                        }

                        $this->setNeworderstate($id_order, $id_order_stateOK); //Si se ha generado la solicitud de envio a MRW -> modificar el id_order_state a 3='Preparación en curso' template=preparacion
                    }
                }
            } else {
                // all orders
                if ($this->debug_activo == '1')
                    $this->writeToFileParam('executeSendMRW:multi-> Vamos a evaluar si existen envíos MRW para generar');
                $arrIds = $this->getArridorderstosendmrw($id_order_state_send);
                $it = 0;
                foreach ($arrIds as $idOrderAct) {
                    if ($this->isCarrierMRW($idOrderAct)) {
                        // control id carrier for send MRW
                        $it++;
                        if ($this->debug_activo == '1')
                            $this->writeToFileParam('executeSendMRW:multi(' . $it . ')-> Vamos a generar envío MRW para el pedido (' . $idOrderAct . ')');
                        if ($this->sendCarrierMRW($idOrderAct, true) == true) {
                            if ($this->debug_activo == '1')
                                $this->writeToFileParam('executeSendMRW:multi(' . $it . ')-> El envío MRW para el pedido (' . $idOrderAct . ') se ha generado' . PHP_EOL);
                            $this->setNeworderstate($idOrderAct, $id_order_stateOK); //Si se ha generado la solicitud de envio a MRW -> modificar el id_order_state a 3='Preparación en curso' template=preparacion
                        }
                    }
                }
            }
            return true;
        }
    }

    /**
     * Procesa la generación de etiquetas de forma Manual ó Automática
     * @param Int $id_ord, id del pedido
     */
    public function executeCreatePDFTicket($id_order = '')
    {
        if ($id_order != '') {
            // gen one ticket
            $rowCarrier = $this->getInfoPedidoMRW($id_order);
            if (!$rowCarrier || !$rowCarrier['send_num_mrw'] || !$rowCarrier['order_id'] || !$rowCarrier['id_shop']){
                $this->writeToFileParam('executeCreatePDFTicket:mono-> no se ha encontrado la información del pedido mrw  (e.g. cuando la peticion al SAGEC es denegada)'); 
                if ($rowCarrier)
                    $this->writeToFileParam('executeCreatePDFTicket:mono->'.$rowCarrier['send_num_mrw'] . ') del pedido (' . $rowCarrier['order_id'] . ') y con id_shop (' . $rowCarrier['id_shop'] . ')');
                return false;
            }
            
            if ($this->debug_activo == '1') 
                $this->writeToFileParam('executeCreatePDFTicket:mono-> Vamos a intentar generar la etiqueta (' . $rowCarrier['send_num_mrw'] . ') del pedido (' . $id_order . ') y con id_shop (' . $rowCarrier['id_shop'] . ')');
            

            if ($this->getEtiquetaEnvio($rowCarrier['send_num_mrw'], $rowCarrier['order_id'], $rowCarrier['id_shop'])) {
                $sql_carrier = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw SET print = "0" WHERE send_num_mrw = "' . $rowCarrier['send_num_mrw'] . '"';
                Db::getInstance()->Execute($sql_carrier);
                if ($this->debug_activo == '1') 
                    $this->writeToFileParam('executeCreatePDFTicket:mono-> Se ha generado correctamente la etiqueta (' . $rowCarrier['send_num_mrw'] . ') del pedido (' . $id_order . ') y con id_shop (' . $rowCarrier['id_shop'] . ')');
            }
        } else {
            // gen all tickets
            $query = 'SELECT * FROM  ' . _DB_PREFIX_ . $this->name . '_mrw WHERE  print=1 and send_num_mrw is not null';
            $rowCarrier = Db::getInstance()->ExecuteS($query);
            $it2 = 0;
            if (count($rowCarrier) > 0) {
                foreach ($rowCarrier as $item) {
                    $it2++;
                    $nro_ped_mrw = $item['send_num_mrw'];
                    $nid_order = $item['order_id'];
                    $nid_shop = $item['id_shop'];
                    if ($this->debug_activo == '1') 
                        $this->writeToFileParam('executeCreatePDFTicket:multi(' . $it2 . ')-> Vamos a intentar generar la etiqueta (' . $item['send_num_mrw'] . ') del pedido (' . $item['order_id'] . ') y con id_shop (' . $nid_shop . ')');

                    if ($this->getEtiquetaEnvio($nro_ped_mrw, $nid_order, $nid_shop)) {
                        $sql_carrier = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw SET print = "0" WHERE print = "1" AND send_num_mrw = "' . $item['send_num_mrw'] . '"';
                        Db::getInstance()->Execute($sql_carrier);
                        if ($this->debug_activo == '1') 
                            $this->writeToFileParam('executeCreatePDFTicket:multi(' . $it2 . ')-> Se ha generado correctamente la etiqueta (' . $item['send_num_mrw'] . ') del pedido (' . $item['order_id'] . ') y con id_shop (' . $nid_shop . ')');
                    }
                }
            }
        }
        return true;
    }

    /**
     * Registra un nuevo estado para el pedido en la table ps_order_history
     * @param Int $id_ord, id del pedido
     * @param Int $newIdState, id del nuevo estado que tendrá el pedido "Ejm Preparación en curso == 3"
     * @return Bool True/False
     */
    public function setNeworderstate($id_order, $newIdState)
    {
        $pedido = new Order($id_order);
        $pedido->setCurrentState($newIdState);
    }

    /**
     * Obtiene los pedidos que tienen como estado el valor pasado por parametro.
     *  Por compatibilidad con la versión 1.4 se usa la tabla order_history
     * @param Int $id_order_state , id del estado de pedido
     * @return Array $arrIdOrders, Listado de pedidos con estado $id_order_state
     */
    public function getArridorderstosendmrw($id_order_state)
    {
        $arrIdOrders = array();
        //Sólo se recuperan aquellos registros que sean de algún transportista MRW activo.
        $queryModif = "SELECT P.* FROM
                            (SELECT max(id_order_history) MAX_IOH, id_order AS MAX_ORDER
                            FROM " . _DB_PREFIX_ . "order_history
                            GROUP BY id_order) AS T,
                            " . _DB_PREFIX_ . "order_history AS P,
                            " . _DB_PREFIX_ . "orders AS Po,
                            " . _DB_PREFIX_ . "carrier AS Pc
                        WHERE T.MAX_ORDER=P.id_order
                            AND T.MAX_IOH = P.id_order_history
                            AND P.id_order_state = " . $id_order_state . "
                            AND Po.id_order = P.id_order
                            AND Pc.id_carrier = Po.id_carrier
                            AND Pc.name like '%MRW%'
                            AND Pc.active = 1
                            AND Pc.deleted = 0
                      ";

        if ($this->debug_activo == '1')
            $this->writeToFileParam('get_arrIdOrdersToSendMRW:-> queryModif = ' . $queryModif);
        $row = Db::getInstance()->ExecuteS($queryModif);
        if ($row && isset($row)) {
            //Si hay registros que revisar
            if ($this->debug_activo == '1')
                $this->writeToFileParam('get_arrIdOrdersToSendMRW:-> Hay registros que revisar');
            foreach ($row as $arr) {
                $id_lastOrderState = $this->getLastorderstate($arr['id_order']);
                if ($id_lastOrderState['id_order_state'] == $id_order_state) {
                    $arrIdOrders[] = $arr['id_order'];
                }
                // id_order para enviar a mrw
            }
        }
        return $arrIdOrders;
    }

    /**
     * Obtiene el ultimo ID_ORDER_STATE de un ID_ORDER de la tabla ORDER_HISTORY
     * @param Int $id_order , número de pedido
     * @return Array $lastOrder1, devuelve el ID del ultimo estado del pedido dentro la tabla ps_order_history
     */
    public function getLastorderstate($id_order)
    {
        $query = '
                SELECT h.id_order_state, h.id_order_history, h.id_order
                FROM   ' . _DB_PREFIX_ . 'order_history AS h
                WHERE  h.id_order_history=(SELECT MAX(h2.id_order_history) FROM ' . _DB_PREFIX_ . 'order_history AS h2 WHERE h2.id_order = ' . $id_order . ')
        ';
        $lastOrder1 = Db::getInstance()->getRow($query);
        return $lastOrder1;
    }

    /**
     * Obtiene el peso de producto del pedido
     * Obtiene el número de bultos para el pedido
     * @param Int $id_order , número de pedido
     * @return Array $result, Array con el peso y bulto
     */
    public function getProductweight($id_order)
    {
        $result = array();
        $weight = 0;
        $cant = 0;
        $query = '  SELECT od.product_quantity, od.product_weight
                    FROM ' . _DB_PREFIX_ . 'order_detail AS od
                    WHERE od.id_order = ' . $id_order . '
            ';
        $row = Db::getInstance()->ExecuteS($query);
        foreach ($row as $arr) {
            $weight += $arr['product_weight'] * $arr['product_quantity']; // id_order para enviar a mrw
            $cant += $arr['product_quantity'];
        }
        
        //TRiBi
        if (Configuration::get('PS_WEIGHT_UNIT') == "gr" || Configuration::get('PS_WEIGHT_UNIT') == "g"){
            $weight = $weight/1000;
        }
        //Fin TRiBi

        $result['peso'] = number_format($weight, 0, ',', '.'); // redondear el valor del peso
        $result['bultos'] = $cant;
        return $result;
    }

   /**
     * Obtiene el peso de todos los productos del pedido
     * Obtiene las dimensiones de los bultos para el pedido
     * @param Int $id_order , número de pedido
     * @return Array $result, Array con el peso y bulto
     */
    public function getProductDimensions($id_order)
    {
        $arr_bultos = array();
        $quantity=0;
        $bultos=0;
        $query = '  SELECT p.width, p.height, p.depth, p.weight, od.product_quantity
                    FROM ' . _DB_PREFIX_ . 'order_detail AS od, ' . _DB_PREFIX_ . 'product AS p
                    WHERE od.id_order = ' . $id_order . '
                    and od.product_id = p.id_product
            ';
        $row = Db::getInstance()->ExecuteS($query);
        foreach ($row as $arr) {
            $quantity = $arr['product_quantity'];

            for ($i = 0; $i < $quantity; $i++) {
                $arr_bultos['BultoRequest'][$bultos] = array('Alto' => round($arr['height']), 'Largo' => round($arr['width']), 'Ancho' => round($arr['depth']), 'Dimension' => '3', 'Referencia' => 'Bulto generico', 'Peso' => round($arr['weight']));
                $bultos ++;
            }
        }
        return $arr_bultos;
    }

    /**
     * Obtiene el método de pago del pedido
     * @param Int $id_order , número de pedido
     * @return Array $row
     */
    public function getPaymentmodule($id_order)
    {
        $query = '
                    SELECT o.module, o.payment, o.total_paid
                    FROM ' . _DB_PREFIX_ . 'orders AS o
                    WHERE o.id_order = ' . $id_order . '
            ';
        $row = Db::getInstance()->getRow($query);
        return $row;
    }
    /**
     * Conecta con webservice MRW y genera la nueva solicitud de envio a MRW
     * @param Int $id_order , número de pedido
     * @param Bool $mode    , modo automático o manual
     * @return Bool, True/False
     */
    public function sendCarrierMRW($id_order, $mode)
    {
        $result = 0;
        $subscriber = '';
        $nid_shop = '';

        //Recovering the environment for this order depending on the mode
        if ($mode) {
            //Automatic Mode
            $OrderInfo = $this->getInfoPedido($id_order);
            if ($OrderInfo) {
                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                    $nid_shop = 1;
                } else {
                    $nid_shop = $OrderInfo['id_shop'];
                }

                if ($this->debug_activo == '1')
                    $this->writeToFileParam('sendCarrierMRW:-> Entramos funcion automática con datos de ps_orders');

                $rowSubscriber = $this->getDefaultSubscriber($nid_shop);
                $subscriber = $rowSubscriber['id_subscriber'];
            } else 
                $this->writeToFileParam('sendCarrierMRW:-> No existe registro en la tabla ps_orders');
        } else 
          {
            //Manual Mode
            $OrderInfo = $this->getInfoPedidoMRW($id_order);
            if ($OrderInfo && $OrderInfo['subscriber'] != '') {
                if ($this->debug_activo == '1') 
                    $this->writeToFileParam('sendCarrierMRW:-> Entramos en la funcion por defecto');

                $subscriber = $OrderInfo['subscriber'];
                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                    $nid_shop = 1;
                } else {
                    $nid_shop = $OrderInfo['id_shop'];
                }
            } else {
                if ($this->debug_activo == '1') 
                    $this->writeToFileParam('sendCarrierMRW:-> Entramos en la funcion automática');

                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                    $nid_shop = 1;
                } else {
                    $nid_shop = $this->context->shop->id;
                }

                $rowSubscriber = $this->getDefaultSubscriber($nid_shop);
                $subscriber = $rowSubscriber['id_subscriber'];
            }
        }
        $SubscriberInfo = $this->getInfoSubscriber($subscriber);
        if ($SubscriberInfo && $SubscriberInfo['environment'] == 'mrw_pro') {
            $wsdl_url = 'http://sagec.mrw.es/MRWEnvio.asmx';
        } else {
            $wsdl_url = 'http://sagec-test.mrw.es/MRWEnvio.asmx';
        }

        $wsdl_url .= '?WSDL';
        $cabeceras = array();
        $parametros = array();
        $responseCode = array();
        if ($this->debug_activo == '1') 
            $this->writeToFileParam('sendCarrierMRW:-> Entramos en la funcion para el pedido (' . $id_order . ')');

        try {
            $clientMRW = new SoapClient(
                $wsdl_url,
                array(
                    'trace' => true,
                )
            );
        } catch (SoapFault $e) {
            $errr = "sendCarrierMRW:-> Error creando cliente SOAP: %s" . PHP_EOL . $e->__toString();
            $this->writeToFileParam($errr);
            return false;
        }

        //Cargamos las cabeceras con la información del abonado
        $cabeceras = $this->getHeaderConfigMRW($id_order, $subscriber, $nid_shop);
        $parametros = $this->getParamsMRW($id_order, $mode); // Preparamos el array de parametros con los mismos datos del ejemplo que trae la documentacion
        $header = new SoapHeader('http://www.mrw.es/', 'AuthInfo', $cabeceras); // Cargamos los headers sobre el objeto cliente SOAP
        $clientMRW->__setSoapHeaders($header);
        try {
            if (!$this->getPedidoMRW($id_order)) {
                $responseCode = $clientMRW->TransmEnvio($parametros);
            }
        } catch (SoapFault $exception) {
            $res1 = "sendCarrierMRW:-> Error llamando al metodo TransmEnvio del WebService MRW: %s" . PHP_EOL . $exception->__toString();
            $res2 = "sendCarrierMRW:-> Solicitud SOAP enviada (aparece como un XML plano):" . PHP_EOL . $clientMRW->__getLastRequest();
            $this->writeToFileParam($res1);
            $this->writeToFileParam($res2);
            return false;
        }

        if ($responseCode && isset($responseCode)) {
            if (0 == $responseCode->TransmEnvioResult->Estado) {
                // No se ha generado la orden de envio y mostramos el error generado
                $this->writeToFileParam('sendCarrierMRW:-> SAGEC no ha aceptado nuestra peticion. Esta es la respuesta recibida:' . PHP_EOL . $responseCode->TransmEnvioResult->Mensaje);
                $this->writeToFileParam('sendCarrierMRW:-> Request enviada:' . PHP_EOL . $clientMRW->__getLastRequest());
                $result = false;
            } else if (1 == $responseCode->TransmEnvioResult->Estado) {
                // La orden de envio se ha generado correctamente
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('sendCarrierMRW:-> Transmisión SAGEC generada para el pedido (' . $id_order . ')');
                    $this->writeToFileParam('sendCarrierMRW:-> Mensaje recibido desde el WS (' . $responseCode->TransmEnvioResult->Mensaje . ')');
                    $this->writeToFileParam('sendCarrierMRW:-> Solicitud XML enviada:' . PHP_EOL . $clientMRW->__getLastRequest());
                }
                $params_mrw = "?Franq=" . $cabeceras['CodigoFranquicia'] . "&Ab=" . $cabeceras['CodigoAbonado'] . "&Dep=" . $cabeceras['CodigoDepartamento'] . "&Pwd=" . $cabeceras['Password']
                . "&NumSol=" . $responseCode->TransmEnvioResult->NumeroSolicitud . "&Us=" . $cabeceras['UserName'] . "&NumEnv=" . $responseCode->TransmEnvioResult->NumeroEnvio;
                $nro_envio_mrw = $responseCode->TransmEnvioResult->NumeroEnvio; //obtenemos el número de envio
                
                if ($this->debug_activo == '1')
                  $this->writeToFileParam('sendCarrierMRW:-> La solicitud de envio MRW para el pedido (' . $id_order . ') fue satisfactoria. Id_envio: (' . $nro_envio_mrw . ')');

                $this->insertTicketCarrier($id_order, $params_mrw, $nro_envio_mrw, $nid_shop); // Registrar datos de envio en la tabla ps_carrier_mrw
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('sendCarrierMRW:-> Hemos vuelto de insertTicketCarrier()');
                }
                $this->updateTrackingCarrier($id_order, $nro_envio_mrw); //Grabamos el num_expedicion MRW en las tablas del pedido
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('sendCarrierMRW:-> Hemos vuelto de updateTrackingCarrier()');
                }
                $result = true;
            } else {
                $this->writeToFileParam("sendCarrierMRW:-> Se ha producido un error no identificado al lanzar la peticion. ¿No es un envío de MRW?"); // No sabemos que ha pasado y mostramos un mensaje
                $result = false;
            }
        } else {
            $this->writeToFileParam("sendCarrierMRW:-> No se ha lanzado la petición a MRW . Consulte al administrador de la web"); // No sabemos que ha pasado y mostramos un mensaje
            $result = false;
        }

        unset($clientMRW); // Destruimos el objeto cliente
        return $result;
    }

    public function getPedidoMRW($order_id)
    {
        /* Se busca si ya se ha lanzado previamente la petición a MRW */
        $sql_mrw_carrier =
        'SELECT * FROM ' . _DB_PREFIX_ . $this->name . '_mrw
                 where
                 send_num_mrw is not null and send_num_mrw !=\'\'
                 and order_id  =' . $order_id;

        $result = Db::getInstance()->getValue($sql_mrw_carrier);
        
        if ($order_id != '' && !empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * Registro de numero de envio mrw en las tablas order y order_carrier
     * @param Int $id_order , número de pedido
     * @return Pedido_MRW
     */
    public function getInfoPedidoMRW($order_id)
    {
        $results = array();
        /* Se busca si ya se ha lanzado previamente la petición a MRW */
        $sql_mrw_carrier =
        'SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_mrw`
                 where order_id = ' . $order_id;

        if ($order_id != '') {
            $results = Db::getInstance()->getRow($sql_mrw_carrier);
        }

        return $results;
    }

    /**
     * Registro de si hay que activar la Entrega en Franquicia por defecto
     * @param Int $order_id , número de pedido
     * @return boolean
     */
    public function getEsEntregaFranquicia($order_id)
    {
        $sql_mrw_carrier =
        'SELECT pc.id_carrier FROM `' . _DB_PREFIX_ . 'orders` ps, `' . _DB_PREFIX_ . 'carrier` pc
                 where ps.id_order = ' . $order_id .
        ' and ps.id_carrier = pc.id_carrier
                      and pc.name like \'%franquicia%\'';

        if ($order_id != '' && Db::getInstance()->getValue($sql_mrw_carrier)) {
            return true;
        }
        return false;
    }

    /**
     * Registro de pedido en las tablas de orders
     * @param Int $id_order , número de pedido
     * @return Pedido_MRW
     */
    public function getInfoPedido($order_id)
    {
        $results = array();
        /* Se busca si ya se ha lanzado previamente la petición a MRW */
        $sql_mrw_carrier =
        'SELECT * FROM `' . _DB_PREFIX_ . 'orders`
                 where id_order = ' . $order_id;

        if ($order_id != '') {
            $results = Db::getInstance()->getRow($sql_mrw_carrier);
        }

        return $results;
    }

    /**
     * Registro de numero de envio mrw en las tablas order y order_carrier
     * @param Int $id_order , número de pedido
     * @return Pedido_MRW
     */
    public function getInfoSubscriber($id_subscriber)
    {
        $results = array();
        /* Se busca si ya se ha lanzado previamente la petición a MRW */
        $sql_mrw_subscriber =
        'SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                 WHERE id_subscriber  =' . $id_subscriber;

        if ($id_subscriber != '') {
            $results = Db::getInstance()->getRow($sql_mrw_subscriber);
        }

        return $results;
    }

    /**
     * Registro de numero de envio mrw en las tablas order y order_carrier
     * @param Int $id_order , número de pedido
     * @param String $nro_envio_mrw, número de envio generado por MRW
     * @return Bool True/False
     */
    public function updateTrackingCarrier($id_order, $nro_envio_mrw)
    {
        //17-06-2013 Se actualiza desde aqui el pedido con el $nro_envio_mrw recibido de MRW
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('updateTrackingCarrier:-> Entramos en la funcion. id_order(' . $id_order . '), nro_envio_mrw(' . $nro_envio_mrw . ')');
        }

        $order = new Order($id_order);
        if (!Validate::isLoadedObject($order)) {
            throw new PrestaShopException('Can\'t load Order object');
        }

        if ($this->debug_activo == '1') {
            $this->writeToFileParam('updateTrackingCarrier:-> Objeto order instanciado');
        }

        $order->shipping_number = pSQL($nro_envio_mrw);
        $order->update();
        if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {
            //v1.5.x Actualizamos tambien OrderCarrier
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('updateTrackingCarrier:-> Efectuamos tareas para Prestashop 1.5.x en adelante');
            }

            $shipper = $order->getShipping();
            $order_carrier = new OrderCarrier($shipper[0]['id_order_carrier']);
            if (!Validate::isLoadedObject($order_carrier)) {
                $this->errors[] = Tools::displayError('The order carrier ID is invalid.');
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('updateTrackingCarrier:-> ERROR: The order carrier ID is invalid (' . $shipper[0]['id_order_carrier'] . ')');
                }
            } elseif (!Validate::isTrackingNumber($nro_envio_mrw)) {
                $this->errors[] = Tools::displayError('The tracking number is incorrect.');
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('updateTrackingCarrier:-> ERROR: The tracking number is incorrect (' . $nro_envio_mrw . ')');
                }
            } else {
                $order_carrier->tracking_number = pSQL($nro_envio_mrw);
                if ($order_carrier->update()) {
                    //Si todo ha actualizado bien, se envia mail al cliente con el $nro_envio_mrw
                    if (Configuration::get($this->rename . 'MAIL_WHENSENT_MRW') == '1') {

                        //Enviamos el mail solo si esta activado en la configuracion
                        $customer = new Customer((int) $order->id_customer);
                        $carrier = new Carrier((int) $order->id_carrier, $order->id_lang);
                        if (!Validate::isLoadedObject($customer)) {
                            throw new PrestaShopException('Can\'t load Customer object');
                        }

                        if (!Validate::isLoadedObject($carrier)) {
                            throw new PrestaShopException('Can\'t load Carrier object');
                        }

                        $templateVars = array(
                            '{followup}' => str_replace('@', $order->shipping_number, $carrier->url),
                            '{firstname}' => $customer->firstname,
                            '{lastname}' => $customer->lastname,
                            '{id_order}' => $order->id,
                            '{shipping_number}' => $order->shipping_number,
                            '{order_name}' => $order->getUniqReference(),
                        );
                        if (@Mail::Send((int) $order->id_lang, 'in_transit', Mail::l('Package in transit', (int) $order->id_lang), $templateVars, $customer->email, $customer->firstname . ' ' . $customer->lastname, null, null, null, null, _PS_MAIL_DIR_, true, (int) $order->id_shop)) {
                            Hook::exec('actionAdminOrdersTrackingNumberUpdate', array('order' => $order));
                            if ($this->debug_activo == '1') {
                                $this->writeToFileParam('updateTrackingCarrier:-> OK: Se ha actualizado correctamente el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . ') y se ha enviado un email al destinatario con esta información.');
                            }

                            return true;
                        } else {
                            $this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
                            if ($this->debug_activo == '1') {
                                $this->writeToFileParam('updateTrackingCarrier:-> ERROR: Se ha actualizado correctamente el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . ') pero no se ha podido enviar el email al destinatario con esta información.');
                            }

                            return false;
                        }
                    }
                    //No se notifica al cliente vía mail que todo ha ido bien.
                    else {
                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam('updateTrackingCarrier:-> OK: Se ha actualizado correctamente el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . '). No se envía email');
                        }

                        return true;
                    }
                } else {
                    $this->errors[] = Tools::displayError('The order carrier cannot be updated.');
                }

                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('updateTrackingCarrier:-> ERROR: No ha podido actualizar el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . ')');
                }

                return false;
            }
        } else if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
            //v1.4.x Solo enviamos el mail de pedido en transito
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('updateTrackingCarrier:-> Efectuamos tareas para Prestashop 1.4.x');
            }

            if (Configuration::get($this->rename . 'MAIL_WHENSENT_MRW') == '1') {
                //Enviamos el mail solo si esta activado en la configuracion
                global $_LANGMAIL;
                $customer = new Customer((int) ($order->id_customer));
                $carrier = new Carrier((int) ($order->id_carrier));
                if (!Validate::isLoadedObject($customer) or !Validate::isLoadedObject($carrier)) {
                    die(Tools::displayError());
                }

                $templateVars = array(
                    '{followup}' => str_replace('@', $order->shipping_number, $carrier->url),
                    '{firstname}' => $customer->firstname,
                    '{lastname}' => $customer->lastname,
                    '{id_order}' => (int) ($order->id),
                );
                if (@Mail::Send((int) $order->id_lang, 'in_transit', Mail::l('Package in transit', (int) $order->id_lang), $templateVars, $customer->email, $customer->firstname . ' ' . $customer->lastname, null, null, null, null, _PS_MAIL_DIR_, true)) {
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('updateTrackingCarrier:-> OK: Se ha actualizado correctamente el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . ') y se ha enviado un email al destinatario con esta información.');
                    }

                    return true;
                } else {
                    $this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('updateTrackingCarrier:-> ERROR: Se ha actualizado correctamente el pedido (' . $id_order . ') con el número de envío (' . $nro_envio_mrw . ') pero no se ha podido enviar el email al destinatario con esta información.');
                    }

                    return false;
                }
            }
        }
    }

    /**
     * Registro de solicitudes de envio mrw en la table carrier_mrw
     * @param Int $id_order , número de pedido
     * @param String $params_mrw, cadena de parámetros
     * @param String $nro_envio_mrw, número de envio generado por MRW
     * @param Int $nid_shop, identificador de tienda
     * @return Bool True/False
     */
    public function insertTicketCarrier($id_order, $params_mrw, $nro_envio_mrw, $nid_shop)
    {

        // Se revisa si existe el registro para ese pedido.
        $rowOrder = $this->getInfoPedidoMRW($id_order);
        if ($rowOrder && isset($rowOrder)) {
            $sql_mrw_carrier = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                SET send_num_mrw = "' . $nro_envio_mrw . '"
                                , params_mrw = "' . $params_mrw . '"
                                , print = "1" , id_shop = "' . $nid_shop . '"
                                 WHERE order_id = "' . $id_order . '" ';
        } else {
            // Se consigue el servicio por defecto por si no hubiera registro
            $servSubscriber = $this->getDefaultSubscriberService($nid_shop);
            // Se recupera si es un Carrier con Entrega En Franquicia activado por defecto.
            $agency = '0';
            if ($this->getEsEntregaFranquicia($id_order)) {
                $agency = '1';
            }

            $sql_mrw_carrier =
            'INSERT INTO ' . _DB_PREFIX_ . $this->name . '_mrw
                    (
                            `id_mrwcarrier_mrw`, `id_shop`,`order_id`, `send_num_mrw`, `params_mrw`,
                            `subscriber`, `date`, `print`, `service`, `saturday`,`agency`
                    )
                            VALUES
                    (
                            NULL , ' . $nid_shop . ' , "' . $id_order . '", "' . $nro_envio_mrw . '", "' . $params_mrw . '",
                            "' . $servSubscriber['id_subscriber'] . '", NOW(), "1", "' . $servSubscriber['Service'] . '", "0", "' . $agency . '"
                    )';
        }
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('insertTicketCarrier:-> sql_mrw_carrier = ' . $sql_mrw_carrier);
        }

        if (!Db::getInstance()->Execute($sql_mrw_carrier)) {
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('insertTicketCarrier:-> ERROR: No se ha logrado insertar el pedido (' . $id_order . ') con número de envío (' . $nro_envio_mrw . ') en la tabla ' . _DB_PREFIX_ . $this->name);
            }

            return false;
        }
        return true;
    }

    /**
     * Obtener los datos de configuración para la conexión remota a sistema MRW, datos que fueron registrados desde el backoffice
     * @return Array $cabeceras
     */
    public function getHeaderConfigMRW($id_order, $id_subscriber, $id_shop)
    {

        $rowSubscriber = array();
        $sql_subscriber = 'SELECT s.id_subscriber, s.agency,s.subscriber, s.department, s.user , s.password
                           FROM  `' . _DB_PREFIX_ . $this->name . '_mrw` m , `' . _DB_PREFIX_ . $this->name . '_subs` s
                           WHERE m.order_id = ' . $id_order . '
                             AND   m.subscriber = s.id_subscriber
                             AND   m.subscriber = ' . $id_subscriber;

        if ($this->debug_activo == '1') 
            $this->writeToFileParam('getHeaderConfigMRW con sql_subscriber= ' . $sql_subscriber);

        if ($id_order != '' && $id_subscriber != '') {
            $rowSubscriber = Db::getInstance()->getRow($sql_subscriber);
        }

        if (!$rowSubscriber || !($id_order != '')) {
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('No hay subscriber y se lanzará la query sobre uno de los abonados por defecto');
                $this->writeToFileParam('No hay subscriber y se obtiene el abonado por defecto de shop = ' . $id_shop);
            }
            $rowSubscriber = $this->getDefaultSubscriber($id_shop);
        }
        $cabeceras = array(
            'CodigoFranquicia' => $rowSubscriber['agency'], //Obligatorio
            'CodigoAbonado' => $rowSubscriber['subscriber'], //Obligatorio
            'CodigoDepartamento' => $rowSubscriber['department'], //Opcional - Se puede omitir si no se han creado departamentos en sistemas de MRW.
            'UserName' => $rowSubscriber['user'], //Obligatorio
            'Password' => $rowSubscriber['password'], //Obligatorio
        );
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getHeaderConfigMRW($id_order):-> pasamos por esta funcion');
        }

        return $cabeceras;
    }

    /**
     * Función que permite obtener la dirección de envio para el pedido
     * @param Int $id_order , número de pedido
     * @return Array, listado de datos de la dirección del pedido
     */
    public function getAddressToMRW($id_order)
    {
        $results = array();
        $sql = '
          SELECT a.address1, a.address2,
            CASE p.iso_code
            WHEN "PT" THEN SUBSTR(RTRIM(postcode),1,4)
            WHEN "ES" THEN SUBSTR(RTRIM(postcode),1,5)
            ELSE RTRIM(postcode) END as postcode,
          a.city, a.id_country, a.dni, a.company, a.phone, a.phone_mobile, a.firstname, a.lastname, a.other, a.id_customer, p.iso_code
          FROM `' . _DB_PREFIX_ . 'orders` AS o, `' . _DB_PREFIX_ . 'carrier` AS t, `' . _DB_PREFIX_ . 'address` AS a, `' . _DB_PREFIX_ . 'country` AS p
          WHERE o.id_order = ' . $id_order . '
          AND o.id_carrier = t.id_carrier
          AND a.id_country = p.id_country
          AND o.id_address_delivery = a.id_address
          ';
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getAddressToMRW:-> pasamos por esta funcion');
        }

        if ($id_order != '') {
            $results = Db::getInstance()->getRow($sql);
        }

        return $results;
    }

    /**
     * Función que permite obtener el Abonado por defecto del envío
     * @return Array, listado de datos del Abonado
     */
    private function getDefaultSubscriberService($id_shop)
    {
        $sql_count = '
          SELECT count(*) as contador
          FROM `' . _DB_PREFIX_ . $this->name . '_subs`
          WHERE is_default ="S" and id_shop = ' . $id_shop;

        $rowCount = Db::getInstance()->getRow($sql_count);
        if ($rowCount && $rowCount['contador'] > 0) {
            $sql_default = '
                SELECT id_subscriber, Service
                FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                WHERE is_default ="S" and id_shop = ' . $id_shop;

            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getDefaultSubscriberService:-> Hay un Abonado por defecto');
            }

            return Db::getInstance()->getRow($sql_default);
        } else {
            //Si no existen abonados por defecto se selecciona el primero de ellos.
            $sql_first_row = '
                SELECT id_subscriber, Service
                FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                WHERE id_shop = ' . $id_shop . '
                ORDER by id_subscriber asc';

            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getDefaultSubscriberService:-> Se selecciona el primer abonado al no haber uno por defecto');
            }

            return Db::getInstance()->getRow($sql_first_row);
        }
    }

    /**
     * Función que permite obtener el Abonado por defecto del envío
     * @return Array, listado de datos del Abonado
     */
    public function getDefaultSubscriber($id_shop)
    {
        $sql_count = '
          SELECT count(*) as contador
          FROM `' . _DB_PREFIX_ . $this->name . '_subs`
          WHERE is_default ="S"
            AND id_shop = ' . $id_shop;

        if ($this->debug_activo == '1') {
            $this->WriteToFileParam('getDefaultSubscriber sql_count = ' . $sql_count);
        }

        $rowCount = Db::getInstance()->getRow($sql_count);
        if ($rowCount && $rowCount['contador'] > 0) {
            $sql_default = '
                SELECT *
                FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                WHERE is_default ="S"
                    AND id_shop = ' . $id_shop;
            if ($this->debug_activo == '1') {
                $this->WriteToFileParam('getDefaultSubscriber sql_default = ' . $sql_default);
            }

            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getDefaultSubscriber:-> Hay un Abonado por defecto');
            }

            return Db::getInstance()->getRow($sql_default);
        } else {
            //Si no existen abonados por defecto se selecciona el primero de ellos.
            $sql_first_row = '
                SELECT *
                FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                WHERE id_shop = ' . $id_shop . '
                ORDER by id_subscriber asc';

            if ($this->debug_activo == '1') {
                $this->WriteToFileParam('getDefaultSubscriber sql_first_row = ' . $sql_first_row);
            }

            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getDefaultSubscriber:-> Se selecciona el primer abonado al no haber uno por defecto');
            }

            return Db::getInstance()->getRow($sql_first_row);
        }
    }

    /**
     * Función que permite obtener el Servicio por defecto del Abonado
     * @return Array, listado de datos del Abonado
     */
    private function getServiceSubscriber($id_subscriber)
    {
        $rowCount = array();
        $sql_default = ' SELECT service
                         FROM `' . _DB_PREFIX_ . $this->name . '_subs`
                         WHERE id_subscriber ="' . $id_subscriber . '"';
        if ($id_subscriber != '') {
            $rowCount = Db::getInstance()->getRow($sql_default);
        }

        if ($rowCount && $rowCount['service'] != '') {
            return $rowCount['service'];
        } else {
            return '';
        }
    }

    /**
     * Función que permite obtener el primer Comentario del usuario, siempre y cuando esté dado de alta en la BBDD
     * @return String, Comentario del pedido
     */
    private function getCommentOrder($id_order)
    {
        $rowCount = array();
        $sql_default = 'SELECT message
                         FROM `' . _DB_PREFIX_ . 'message`
                         WHERE id_order ="' . $id_order . '"
                         and id_customer is not null
                         and private = 0 ';

        if ($this->debug_activo == '1') {
            $this->WriteToFileParam('getCommentOrder sql_default = ' . $sql_default);
        }

        if ($id_order != '') {
            $rowCount = Db::getInstance()->getRow($sql_default);
        }

        if ($rowCount && $rowCount['message'] != '') {
            return $rowCount['message'];
        } else {
            return '';
        }
    }

    /**
     * Función que permite obtener el Abonado por defecto del envío
     * @return Array, listado de datos del Abonado
     */
    public function isSubscriber()
    {
        $sql_default = ' SELECT count(*) total_subscriber
                         FROM `' . _DB_PREFIX_ . $this->name . '_subs` ';

        $rowCount = Db::getInstance()->getRow($sql_default);
        if ($rowCount && $rowCount['total_subscriber'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Función que permite construir los parametros de datos que se enviarán al webservice MRW
     * Procesa cada uno de los parámetros que serán enviados a mrw

     * @param Int $id_order , número de pedido.
     * @param Bool $mode, Modo automático o manual de realizar pedidos.
     * @return Array $parametros es un array que contiene toda la información relacionada al pedido para que mrw pueda gestionar el envio
     * @todo Redefinir valor de $ALaAtencionDe
     */
    public function getParamsMRW($id_order, $mode)
    {
        global $cookie;
        $this->agrupar_bultos = Configuration::get($this->rename . 'AGRUPAR_BULTOS');
        $this->gestion_bultos = Configuration::get($this->rename . 'GESTION_BULTOS');
        $pedido = new Order($id_order);
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getParamsMRW:-> Entramos en la funcion. Id_Order(' . $id_order . ')');
        }

        $row = $this->getAddressToMRW($id_order);
        if ($row && isset($row) && is_array($row)) {
            $onlyAddress = '';
            $onlyNumber = '';
            $others = '';
            $prosDir = $this->addresProccess($row['address1'] . ' ' . $row['address2']);
            if ($prosDir && isset($prosDir)) {
                $onlyAddress = trim($prosDir['address']);
                if (array_key_exists('number', $prosDir)) {
                    $onlyNumber = (is_numeric($prosDir['number'])) ? $prosDir['number'] : 0;
                }

                if (array_key_exists('others', $prosDir)) {
                    $others = substr($prosDir['others'],0,50);
                }
            }

            /* Procesado del comentario a mostrar en la etiqueta. Si está el de la dirección se mostrará ese. */
            if ($row['other'] != '') {
                $orderMessage = $row['other'];
            } else {
                $orderMessage = $this->getCommentOrder($id_order);
            }
        } else {
            $onlyAddress = '';
            $onlyNumber = '';
            $others = '';
            $orderMessage = '';
        }

        $dirProc = $this->procesarTipoVia($onlyAddress);
        $peso_y_bultos = $this->getProductweight($id_order);
        $myWeightPA = $peso_y_bultos['peso'];
        $NumeroBultos = $peso_y_bultos['bultos'];
        if ($this->debug_activo == '1') 
            $this->writeToFileParam('getParamsMRW:-> Bultos paso 1 NumeroBultos:(' . $NumeroBultos . ')');

        $myPaymentModule = $this->getPaymentmodule($id_order);

        $nom_full = $row['firstname'] . ' ' . $row['lastname'];
        
        if ( array_key_exists('company', $row) || !empty($row['company']) )
        {
            $Contacto = $row['company'];
        }

        $cp = ($row['iso_code'] == 'ES') ? 'ESP' : $row['iso_code'];
        $ref_cliente = 'PEDIDO-' . $id_order;
        if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {
            $ref_cliente .= ' (' . $pedido->reference . ')';
        }


        $peso_bulto = (isset($myWeightPA) and $myWeightPA != 0) ? $myWeightPA : '1';
        $bultos = ($NumeroBultos != '' and $NumeroBultos != 0) ? $NumeroBultos : '1';
        if ($this->debug_activo == '1') 
            $this->writeToFileParam('getParamsMRW:-> Bultos paso 2 - NVL (' . $bultos . ')');
        
        $bultos = ($this->agrupar_bultos == '1' ? '1' : $NumeroBultos);
        if ($this->debug_activo == '1')
            $this->writeToFileParam('getParamsMRW:-> Bultos paso 3 - agrupados (' . $bultos . ')');
        
        if ($this->gestion_bultos == 1) {
            $rowBultos = $this->getInfoPedidoMRW($id_order);
            if ($rowBultos && $rowBultos['cant'] > 0) {
                $bultos = $rowBultos['cant'];
                if (is_array($rowBultos) and $this->debug_activo == '1') 
                    $this->writeToFileParam('getParamsMRW:-> Existe un valor de bultos modificado en la tabla de bultos (' . $rowBultos['cant'] . ')');
            }
        }

        $bultos_total = $bultos;
        $num = $bultos;
        $arr_bultos = '';
        //Solo cuando la gestión de bultos se hace desde Prestashop, se manda las dimensiones de los productos.
        if ($this->gestion_bultos == 0 && $this->agrupar_bultos == 0 && Configuration::get($this->rename . 'DESGLOSE_BULTOS') == 1 )
        {
            $arr_bultos = array();
            $arr_bultos = $this->getProductDimensions($id_order);
        }
        else if (Configuration::get($this->rename . 'DESGLOSE_BULTOS') == 1) {
            $arr_bultos = array();
            if ($num > 1) {
                //Hay mas de 1 bulto y hacemos desglose
                if ($this->debug_activo == '1')
                    $this->writeToFileParam('getParamsMRW:-> Hay mas de 1 bulto y hacemos desglose: total bultos(' . $bultos_total . ')');
                
                for ($i = 0; $i < $num; $i++) {
                    $y = $i + 1;
                    $arr_bultos['BultoRequest'][$i] = array('Alto' => '1', 'Largo' => '1', 'Ancho' => '1', 'Dimension' => '3', 'Referencia' => 'Bulto ' . $y . ' de ' . $num, 'Peso' => str_replace(".",",",round($peso_bulto / $num,2,PHP_ROUND_HALF_UP)));
                }
            } else {
                if ($this->debug_activo == '1') 
                    $this->writeToFileParam('getParamsMRW:-> Solo hay un bulto y desglosamos: bultos(' . $num . ')');
                $arr_bultos['BultoRequest'] = array('Alto' => '1', 'Largo' => '1', 'Ancho' => '1', 'Dimension' => '3', 'Referencia' => 'Ref 1 ', 'Peso' => $peso_bulto);
            }
        }
        if ($this->debug_activo == '1') 
            $this->writeToFileParam('getParamsMRW:-> El valor final de bultos es (' . $bultos_total . ')');
        

        $phone = ($row['phone_mobile'] != '') ? $row['phone_mobile'] : $row['phone'];
        if ($myPaymentModule['module'] == 'cashondelivery'
            or $myPaymentModule['module'] == 'customcashondelivery'
            or $myPaymentModule['module'] == 'cashondeliverywithfee'
            or $myPaymentModule['module'] == 'codfee'
            or $myPaymentModule['module'] == 'maofree_cashondeliveryfee'
            or $myPaymentModule['module'] == 'maofree2_cashondeliveryfee'
            or $myPaymentModule['module'] == 'megareembolso'
            or $myPaymentModule['module'] == 'cashondeliveryplus'
            or $myPaymentModule['module'] == 'cashondeliveryfeedep'
            or $myPaymentModule['module'] == 'pago_reembolso_cargo15'
            or $myPaymentModule['module'] == 'deluxecodfees'
            or $myPaymentModule['module'] == 'contrareembolso'
            or $myPaymentModule['module'] == 'reembolsocargo'
            or $myPaymentModule['module'] == 'lc_paywithfee'
            or $myPaymentModule['module'] == 'esp_contrareembolso'   
            or $myPaymentModule['module'] == 'cashondeliverymod'
            or $myPaymentModule['module'] == 'imaxcontrareembolso'
            or $myPaymentModule['module'] == 'pscodfee'
            or $myPaymentModule['module'] == 'codfeeiw'
            or $myPaymentModule['module'] == 'webubicod'
            or $myPaymentModule['module'] == 'idxcodfees'
        ) {
            $myReembolso = '';
            $myPrice = number_format($myPaymentModule['total_paid'], 2, ',', '');
            
            if($myPrice < 2.42){
                $myReembolso = 'D';
            }
            else{
                $myReembolso = 'O';
            }
            
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getParamsMRW:-> El pedido tiene reembolso (' . $myPrice . ')');
                
                if($myPrice < 2.42){
                $this->writeToFileParam('getParamsMRW:-> El importe del reembolso es inferior a 2.42:  (' . $myPrice . ') la comisión se cobrará en destino' );
                }
            }
            
        } else {
            $myReembolso = '';
            $myPrice = '';
        }

        // control de tipo de servicio personalizado o automático
        $rowServ = $this->getInfoPedidoMRW($id_order);
        if ($rowServ && isset($rowServ) && $rowServ['service'] != '') {
            $service_type = $rowServ['service'];
            $entrega_sabado = ($rowServ['saturday'] == 1) ? 'S' : 'N';
            $entrega_franquicia = ($rowServ['agency'] == 1) ? 'E' : 'N';
            $retorno_activado = ($rowServ['backReturn'] == 1) ? 'S' : 'N';
            $warehouse_change = ($rowServ['mrw_warehouse'] != 0) ? 'S' : 'N';
            $slot_change = ($rowServ['mrw_slot'] != 0) ? 'S' : 'N';
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getParamsMRW:-> El pedido tiene modificado el servicio (' . $service_type . ') o la entrega en sábado (' . $entrega_sabado . ') o la entrega en franquicia (' . $entrega_franquicia . ') o el retorno (' . $retorno_activado . '), el almacén de recogida (' . $warehouse_change . ') o el tramo horario (' . $slot_change . ')');
            }
        } else {
            if ($mode) {
                // Automático
                $rowServ = $this->getInfoPedido($id_order);
                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || !$rowServ) {
                    $shop = 1;
                } else {
                    $shop = $rowServ['id_shop'];
                }

                if ($rowServ && $shop != '') {
                    $servicio = $this->getDefaultSubscriberService($shop);
                } else {
                    if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
                        $shop = 1;
                    } else {
                        $shop = $this->context->shop->id;
                    }

                    $servicio = $this->getDefaultSubscriberService($shop);
                }
            } else {
                //Manual
                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                    $shop = 1;
                } else {
                    $shop = $this->context->shop->id;
                }
                $servicio = $this->getDefaultSubscriberService($shop);
            }

            /* Si se hace automáticamente los parámetros por defecto no se activan */
            $service_type = $servicio['Service'];
            $entrega_sabado = 'N';
            $retorno_activado = 'N';
            if ($this->getEsEntregaFranquicia($id_order)) {
                $entrega_franquicia = 'E';
            } else {
                $entrega_franquicia = 'N';
            }
        }

        //La Entrega en Franquicia no permite las notificaciones
        $msg_type = Configuration::get($this->rename . 'MESSAGE_TYPE_MRW');
        $customer_dat = $this->getCustomer($row['id_customer']);
        $arr_notificaciones = array();
        if ($entrega_franquicia == 'N') {
            if ($msg_type == 'sms' and $this->phoneValid($phone) == 1) {
                $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '2', 'TipoNotificacion' => '4', 'MailSMS' => $phone);
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS (' . $phone . ')');
                }
            } else if ($msg_type == 'email') {
                $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '4', 'MailSMS' => $customer_dat['email']);
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por EMAIL (' . $customer_dat['email'] . ')');
                }
            } else if ($msg_type == 'sms+email') {
                if ($this->phoneValid($phone) == 1) {
                    $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '2', 'TipoNotificacion' => '4', 'MailSMS' => $phone);
                    $arr_notificaciones['NotificacionRequest'][1] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '4', 'MailSMS' => $customer_dat['email']);
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS + EMAIL (' . $customer_dat['email'] . ' Y ' . $phone . ')');
                    }
                } else {
                    $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '4', 'MailSMS' => $customer_dat['email']);
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS + EMAIL (' . $customer_dat['email'] . ' Y ' . $phone . '). Teléfono no es válido');
                    }
                }
            } else {
                $arr_notificaciones = '';
            }
        }

        else {

            if ($msg_type == 'sms' and $this->phoneValid($phone) == 1) {
                $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '2', 'TipoNotificacion' => '3', 'MailSMS' => $phone);
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS (' . $phone . ')');
                }
            } else if ($msg_type == 'email') {
                $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '3', 'MailSMS' => $customer_dat['email']);
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por EMAIL (' . $customer_dat['email'] . ')');
                }
            } else if ($msg_type == 'sms+email') {
                if ($this->phoneValid($phone) == 1) {
                    $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '2', 'TipoNotificacion' => '3', 'MailSMS' => $phone);
                    $arr_notificaciones['NotificacionRequest'][1] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '3', 'MailSMS' => $customer_dat['email']);
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS + EMAIL (' . $customer_dat['email'] . ' Y ' . $phone . ')');
                    }
                } else {
                    $arr_notificaciones['NotificacionRequest'][0] = array('CanalNotificacion' => '1', 'TipoNotificacion' => '3', 'MailSMS' => $customer_dat['email']);
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('getParamsMRW:-> El pedido tiene notificacion por SMS + EMAIL (' . $customer_dat['email'] . ' Y ' . $phone . '). Teléfono no es válido');
                    }
                }
            } else {
                $arr_notificaciones = '';
            }
        }

        //Warehouses
        $rowServ = $this->getInfoPedidoMRW($id_order);
        
        //Time Slot
        $timeSlot = $rowServ['mrw_slot'];

        //$this->writeToFileParam('Warehouse:' . $rowServ['mrw_warehouse']);
        
        //Just for 1.5 and 1.6 PS versions
        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {
                    $warehouseNode = '';

                } else if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)){
                    
                        $mrw_warehouse = $rowServ['mrw_warehouse'];
        
                        if ($mrw_warehouse != '0' && !empty($mrw_warehouse)){
                            
                            $rowWarehouse = $this->getWarehouseAddressToMRW($id_order, $mrw_warehouse);

                            $prosDirWarehouse = $this->addresProccess($rowWarehouse['address1'] . ' ' . $rowWarehouse['address2']);

                            if ($prosDirWarehouse && isset($prosDirWarehouse)) {
                                $rowWarehouse['address'] = trim($prosDirWarehouse['address']);
                                $rowWarehouse['tipoVia'] = $this->procesarTipoVia($rowWarehouse['address']);

                                if (array_key_exists('number', $prosDirWarehouse)) {
                                    $rowWarehouse['number'] = (is_numeric($prosDirWarehouse['number'])) ? $prosDirWarehouse['number'] : 0;
                                }

                                if (array_key_exists('others', $prosDirWarehouse)) {
                                    $rowWarehouse['others'] = substr($prosDirWarehouse['others'],0,50);
                                }
                            }
                                $warehouseNode = $this->getWarehouseNode($rowWarehouse);
                        }
                        else {
                                $warehouseNode = '';
                             }
                        //End warehouses
                }
        

        $rowServ = $this->getInfoPedidoMRW($id_order);
     
        $hoy = date("d/m/Y", time());

        $phoneTrimed = str_replace(' ', '', $phone);

        if (!empty($warehouseNode) || $warehouseNode != ''){
            $parametros = array(
                'request' => array(
                    'DatosRecogida' => $warehouseNode,
                    'DatosEntrega' => array(
                        ## DATOS DESTINATARIO ##
                        'Direccion' => array(
                            'CodigoDireccion' => ''//Opcional - Se puede omitir. Si se indica sustituira al resto de parametros
                            , 'CodigoTipoVia' => $dirProc['CodigoTipoVia']//'CL', //Opcional - Se puede omitir aunque es recomendable usarlo
                            , 'Via' => $dirProc['Via']//Obligatorio
                            , 'Numero' => $onlyNumber//Obligatorio - Recomendable que sea el dato real. Si no se puede extraer el dato real se pondra 0 (cero)
                            , 'Resto' => $others//Opcional - Se puede omitir.
                            , 'CodigoPostal' => $row['postcode']//'08970', //Obligatorio
                            , 'Poblacion' => $row['city']//Obligatorio
                            , 'CodigoPais' => $cp, //Opcional - Se puede omitir para envios nacionales.
                        )
                        , 'Nif' => $row['dni']//Obligatorio
                        , 'Nombre' => $nom_full//Obligatorio
                        , 'Telefono' => empty($phone) ? ' ' : $phoneTrimed//Opcional - Muy recomendable
                        , 'Contacto' => $Contacto//Opcional - Muy recomendable
                        , 'ALaAtencionDe' => $Contacto//$row['firstname'], //Opcional - Se puede omitir.
                        , 'Horario' => array(//Opcional - Se puede omitir este campo y los sub-arrays
                            'Rangos' => array(// Si se indica horario, habrÃ¡ que informar al menos un rango (HorarioRangoRequest)
                                'HorarioRangoRequest' => array(
                                    'Desde' => '08:00',
                                    'Hasta' => '18:00',
                                ),
                            ),
                        )
                        , 'Observaciones' => 'Obs. : ' . $this->TildesHtml($orderMessage)  //$row['other'] //Opcional - Se puede omitir.
                        
                    )
                    ## DATOS DEL SERVICIO ##
                    , 'DatosServicio' => array(
                        'Fecha' => $hoy//Obligatorio. Debera ser fecha de hoy o posterior
                        , 'Referencia' => $ref_cliente//Obligatorio. Vuestro numero de pedido/albaran/factura
                        , 'EnFranquicia' => $entrega_franquicia
                        //   N = Entrega en domicilio (por defecto si se omite)
                        //   E = Entrega en franquicia. El destinatario recogera en delegacion mas proxima
                        , 'CodigoServicio' => $service_type//Obligatorio. 0800 -> eCommerce
                        , 'DescripcionServicio' => ''//Opcional - Se puede omitir.
                        , 'Bultos' => $arr_bultos//Opcional - Se puede omitir.
                        , 'NumeroBultos' => $bultos_total//'1', //Obligatorio. Solo puede haber un bulto por envio
                        , 'Peso' => $peso_bulto//Obligatorio. Debe ser igual al que haya en BultoRequest ya que solo habra un bulto
                        //,'NumeroPuentes' => '' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'EntregaSabado' => $entrega_sabado//'N', //Opcional - Se puede omitir.
                        //,'Entrega830' => 'N' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        //,'EntregaPartirDe' => '09:15' //Opcional - Se puede omitir.
                        //,'Gestion' => 'N' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'Retorno' => $retorno_activado//Opcional - Se debe omitir pues no se usa en eCommerce.
                        //,'ConfirmacionInmediata' => 'S' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'Reembolso' => $myReembolso//Opcional - Se puede omitir.
                        , 'ImporteReembolso' => $myPrice//Si hay reembolso es obligatorio indicar el importe. Los decimales se indican con , (coma)
                        /* Datos para aduana internacional o Canarias */
                        //,'TipoMercancia' => ''
                        //,'ValorDeclarado' => '000,00'
                        /* Datos para aduana internacional o Canarias */
                        /* Datos exclusivos para internacional */
                        //,'ServicioEspecial' => 'N'
                        //,'CodigoMoneda' => '???'
                        //,'ValorEstadistico' => '000,00'
                        //,'ValorEstadisticoEuros' => '000,00'
                        /* Datos exclusivos para internacional */
                        , 'Notificaciones' => $arr_notificaciones
                        //'SeguroOpcional' => array ( //Opcional - Se puede omitir todo el nodo y subnodos
                        //     'CodigoNaturaleza' => ''
                        //     ,'ValorAsegurado' => '000'
                        //)
                        , 'TramoHorario' => $timeSlot, //Opcional - Horario de entrega (coste adicional)
                        // 0 = Sin tramo (8:30h a 19h). Por defecto si se omite.
                        // 1 = Mañana (8:30h a 14h)
                        // 2 = Tarde (14h a 19h)
                        //,'PortesDebidos' => 'N' //Opcional - Se debe omitir si el abonado no lo tiene habilitado en el sistema
                    ),
                ),
            );
        }else{
            $parametros = array(
                'request' => array(
                    'DatosEntrega' => array(
                        ## DATOS DESTINATARIO ##
                        'Direccion' => array(
                            'CodigoDireccion' => ''//Opcional - Se puede omitir. Si se indica sustituira al resto de parametros
                            , 'CodigoTipoVia' => $dirProc['CodigoTipoVia']//'CL', //Opcional - Se puede omitir aunque es recomendable usarlo
                            , 'Via' => $dirProc['Via']//Obligatorio
                            , 'Numero' => $onlyNumber//Obligatorio - Recomendable que sea el dato real. Si no se puede extraer el dato real se pondra 0 (cero)
                            , 'Resto' => $others//Opcional - Se puede omitir.
                            , 'CodigoPostal' => $row['postcode']//'08970', //Obligatorio
                            , 'Poblacion' => $row['city']//Obligatorio
                            , 'CodigoPais' => $cp, //Opcional - Se puede omitir para envios nacionales.
                        )
                        , 'Nif' => $row['dni']//Obligatorio
                        , 'Nombre' => $nom_full//Obligatorio
                        , 'Telefono' => empty($phone) ? ' ' : $phoneTrimed//Opcional - Muy recomendable
                        , 'Contacto' => $Contacto//Opcional - Muy recomendable
                        , 'ALaAtencionDe' => $Contacto//$row['firstname'], //Opcional - Se puede omitir.
                        , 'Horario' => array(//Opcional - Se puede omitir este campo y los sub-arrays
                            'Rangos' => array(// Si se indica horario, habrÃ¡ que informar al menos un rango (HorarioRangoRequest)
                                'HorarioRangoRequest' => array(
                                    'Desde' => '08:00',
                                    'Hasta' => '18:00',
                                ),
                            ),
                        )
                        , 'Observaciones' => 'Obs. : ' . $this->TildesHtml($orderMessage)  //$row['other'] //Opcional - Se puede omitir.
                        
                    )
                    ## DATOS DEL SERVICIO ##
                    , 'DatosServicio' => array(
                        'Fecha' => $hoy//Obligatorio. Debera ser fecha de hoy o posterior
                        , 'Referencia' => $ref_cliente//Obligatorio. Vuestro numero de pedido/albaran/factura
                        , 'EnFranquicia' => $entrega_franquicia
                        //   N = Entrega en domicilio (por defecto si se omite)
                        //   E = Entrega en franquicia. El destinatario recogera en delegacion mas proxima
                        , 'CodigoServicio' => $service_type//Obligatorio. 0800 -> eCommerce
                        , 'DescripcionServicio' => ''//Opcional - Se puede omitir.
                        , 'Bultos' => $arr_bultos//Opcional - Se puede omitir.
                        , 'NumeroBultos' => $bultos_total//'1', //Obligatorio. Solo puede haber un bulto por envio
                        , 'Peso' => $peso_bulto//Obligatorio. Debe ser igual al que haya en BultoRequest ya que solo habra un bulto
                        //,'NumeroPuentes' => '' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'EntregaSabado' => $entrega_sabado//'N', //Opcional - Se puede omitir.
                        //,'Entrega830' => 'N' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        //,'EntregaPartirDe' => '09:15' //Opcional - Se puede omitir.
                        //,'Gestion' => 'N' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'Retorno' => $retorno_activado//Opcional - Se debe omitir pues no se usa en eCommerce.
                        //,'ConfirmacionInmediata' => 'S' //Opcional - Se debe omitir pues no se usa en eCommerce.
                        , 'Reembolso' => $myReembolso//Opcional - Se puede omitir.
                        , 'ImporteReembolso' => $myPrice//Si hay reembolso es obligatorio indicar el importe. Los decimales se indican con , (coma)
                        /* Datos para aduana internacional o Canarias */
                        //,'TipoMercancia' => ''
                        //,'ValorDeclarado' => '000,00'
                        /* Datos para aduana internacional o Canarias */
                        /* Datos exclusivos para internacional */
                        //,'ServicioEspecial' => 'N'
                        //,'CodigoMoneda' => '???'
                        //,'ValorEstadistico' => '000,00'
                        //,'ValorEstadisticoEuros' => '000,00'
                        /* Datos exclusivos para internacional */
                        , 'Notificaciones' => $arr_notificaciones
                        //'SeguroOpcional' => array ( //Opcional - Se puede omitir todo el nodo y subnodos
                        //     'CodigoNaturaleza' => ''
                        //     ,'ValorAsegurado' => '000'
                        //)
                        , 'TramoHorario' => $timeSlot, //Opcional - Horario de entrega (coste adicional)
                        // 0 = Sin tramo (8:30h a 19h). Por defecto si se omite.
                        // 1 = Mañana (8:30h a 14h)
                        // 2 = Tarde (14h a 19h)
                        //,'PortesDebidos' => 'N' //Opcional - Se debe omitir si el abonado no lo tiene habilitado en el sistema
                    ),
                ),
            );
        }
       
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getParamsMRW:-> Salimos de la funcion');
        }

        return $parametros;
    }

    /**
     * Procesa la dirección de entrega del pedido, dividiendo en 3 datos, nombre de calle, número y otros datos
     * @param string $cad cadena que contiene la dirección
     * @return array $arrAddress que contiene nombre, número y otros datos de la dirección
     */
    public function addresProccess($cad)
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('addresProccess:-> Pasamos por la funcion');
        }

        $sw = 0;
        $sw2 = 0;
        $cad = str_replace(array("\\", "Â¨", "~", "#", "@", "|", "!", "\"", "Â·", "$", "%", "&", "/", "(", ")", "?", "'", "Â¡", "Â¿", "[", "^", "`", "]", "+", "}", "{", "Â¨", "Â´", ">", "<", ";", ",", ":", ".", "Âº", "Âª"), '', $cad);
        $myArr = str_split($cad);
        $tam = count($myArr);
        $newArr = array();
        $arrAddress = array();

        for ($i = 0; $i < $tam; $i++) {
            if ($i >= 5 and $sw == 0) {
                if (isset($myArr[$i + 1]) and is_numeric($myArr[$i + 1])) {
                    // extraer direccion hasta antes de que aparezca un número
                    $iniNum = $i;
                    $sw = 1;
                    $arrAddress['address'] = substr($cad, 0, $i + 1);
                } else {
                    $arrAddress['address'] = substr($cad, 0, $i);
                }
            }

            if (isset($iniNum) and $i > $iniNum) {
                if (!is_numeric($myArr[$i]) and $sw2 == 0) {
                    $arrAddress['number'] = substr($cad, $iniNum + 1, $i - $iniNum - 1);
                    $arrAddress['others'] = substr($cad, $i, $tam - $i);
                    $sw2 = 1;
                }
            }
        }

        return $arrAddress;
    }

    /**
     * Procesa y obtiene CodigoTipoVia y Via a partir de la dirección pasado por parametro
     * @param string $cad cadena que contiene la dirección
     * @return array $direccion que contiene CodigoTipoVia y Via de la dirección
     */
    public function procesarTipoVia($dir)
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('procesarTipoVia:-> Pasamos por la funcion');
        }

        $direccion = array();
        $priCad = $this->getFirstString($dir);
        $_cod = $priCad['col'];
        $_nom = str_replace(".", "", $priCad['value']);
        $result = $this->verificarVia(strtoupper($_nom));
        if ($result != '') {
            $direccion['CodigoTipoVia'] = trim($result);
            $direccion['Via'] = trim($dir);
        } else {
            $direccion['CodigoTipoVia'] = '';
            $direccion['Via'] = trim($dir);
        }
        return $direccion;
    }

    /**
     * Procesa si la primera cadena de la dirección es CodigoTipoVia
     * @param string $cad cadena que contiene la dirección
     * @return array $tipoVia
     */
    public function getFirstString($cad)
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getFirstString:-> Pasamos por la funcion');
        }

        $tipoVia = array();
        $arrIni = explode(" ", $cad);
        if (count($arrIni) > 0) {
            $tipoVia['value'] = $arrIni[0];
            if (strlen($arrIni[0]) == 2) {
                $tipoVia['col'] = 'cod';
            } else {
                $tipoVia['col'] = 'nom';
            }
        }

        return $tipoVia;
    }

    /**
     * Verifica si el parametro $val es un código de VIA, para ello se utiliza el archivo mrw_parametros.php donde se encuentra el listado de tipos de via utilizadas por MRW
     * @param string $val
     * @return string $resul que contiene un código de via si existe, caso contrario cadena vacia.
     */
    public function verificarVia($val)
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('verificarVia:-> Pasamos por la funcion');
        }

        $resul = '';
        include_once dirname(__FILE__) . '/mrw_parametros.php';
        if (isset($tipovia)) {
            foreach ($tipovia as $item) {
                if ($item['cod'] == $val or $item['nom'] == $val) {
                    $resul = $item['cod']; // devolvemos el código del tipo de via
                }
            }
        }
        return $resul;
    }

    /**
     * Procesa la conexión del módulo con el webservice MRW "EtiquetaEnvio()" para generar la etiqueta de envio
     * @param string $nro_ped_mrw , número de pedido generado por mrw
     * @return Bool $ret True o False
     */
    public function getEtiquetaEnvio($nro_ped_mrw, $nid_order, $nid_shop)
    {
        $nid_subscriber = '';

        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getEtiquetaEnvio:-> Entramos en la funcion para el envío (' . $nro_ped_mrw . ', ' . $nid_order . ', ' . $nid_shop . ')');
        }

        $ret = 0;

        //Recovering the environment for this order
        $OrderInfo = $this->getInfoPedidoMRW($nid_order);
        $SubscriberInfo = array();
        if ($OrderInfo && isset($OrderInfo) && $OrderInfo['subscriber'] != '') {
            $SubscriberInfo = $this->getInfoSubscriber($OrderInfo['subscriber']);
            $nid_subscriber = $OrderInfo['subscriber'];
        }
        if ($SubscriberInfo && isset($SubscriberInfo) && $SubscriberInfo['environment'] == 'mrw_pro') {
            $wsdl_url = 'http://sagec.mrw.es/MRWEnvio.asmx';
        } else {
            $wsdl_url = 'http://sagec-test.mrw.es/MRWEnvio.asmx';
        }

        $wsdl_url .= '?WSDL';
        try {
            $clientMRW = new SoapClient($wsdl_url, array('trace' => true));
        } catch (SoapFault $e) {
            $this->writeToFileParam('getEtiquetaEnvio:-> Error creando cliente SOAP:' . PHP_EOL . $e->__toString());
            return false;
        }

        $hoy = date("d/m/Y", time());
        $cabeceras = $this->getHeaderConfigMRW($nid_order, $nid_subscriber, $nid_shop);
        $params = $this->getParamsEtiquetaMRW($nro_ped_mrw, '', $hoy);

        $header = new SoapHeader('http://www.mrw.es/', 'AuthInfo', $cabeceras);
        $clientMRW->__setSoapHeaders($header);
        try {
            $responseCode = $clientMRW->EtiquetaEnvio($params);
        } catch (SoapFault $exception) {
            $res1 = "getEtiquetaEnvio:-> Error llamando al metodo GetEtiquetaEnvio del WebService MRW:" . PHP_EOL . $exception->__toString();
            $res2 = "getEtiquetaEnvio:-> Solicitud SOAP enviada (aparece como un XML plano):" . PHP_EOL . $clientMRW->__getLastRequest();
            $this->writeToFileParam($res1);
            //$this->writeToFileParam($res2);
            return false;
        }
        if ($responseCode->GetEtiquetaEnvioResult->Estado == 0) {
            $res = 'getEtiquetaEnvio:-> ERROR:' . PHP_EOL . $responseCode->GetEtiquetaEnvioResult->Mensaje . PHP_EOL . "Solicitud SOAP enviada (aparece como un XML plano):" . PHP_EOL . $clientMRW->__getLastRequest();
            $this->writeToFileParam($res);
        } else if ($responseCode->GetEtiquetaEnvioResult->Estado == 1) {
            // etiqueta generada correctamente
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('getEtiquetaEnvio:-> Etiqueta generada para el envio (' . $nro_ped_mrw . ')');
                $this->writeToFileParam('getEtiquetaEnvio:-> Mensaje recibido desde el WS (' . $responseCode->GetEtiquetaEnvioResult->Mensaje . ')');
                $this->writeToFileParam('getEtiquetaEnvio:-> Solicitud XML enviada:' . PHP_EOL . $clientMRW->__getLastRequest());
            }
            $urlParams = $responseCode->GetEtiquetaEnvioResult->EtiquetaFile; // Contenido de la etiqueta
            $filename = $nro_ped_mrw . '.pdf'; // Nombre del archivo de la etiqueta
            $write = $this->writeTicketFile($urlParams, $filename); // escribimos el archivo de la etiqueta en una ruta determinada

            if ($this->debug_activo == '1')
              $this->writeToFileParam("getEtiquetaEnvio:-> La etiqueta de envio del pedido Nº." . $nro_ped_mrw . " fue generada correctamente");
            if ($write) {
                if ($this->debug_activo == '1')
                  $this->writeToFileParam("getEtiquetaEnvio:-> Archivo PDF de etiqueta MRW generado correctamente");
            } else {
                if ($this->debug_activo == '1')
                  $this->writeToFileParam("getEtiquetaEnvio:-> No se ha podido escribir el archivo PDF en la ruta download/ticket_mrw, verifique los permisos de escritura de esta ruta");
            }

            $ret = true;
        } else {
            // No sabemos que ha pasado y mostramos un mensaje
            $this->writeToFileParam("getEtiquetaEnvio:-> Se ha producido un error no identificado al intentar generar Etiqueta de envio (" . $nro_ped_mrw . "). Consulte al administrador de la web");
        }
        unset($clientMRW);
        return $ret;
    }

    /**
     * Construir parámetros para solicitar al webservice MRW la etiqueta de envio
     * @param string $nroEnv, Número de envio MRW
     * @param string $sep, SeparadorNumerosEnvio
     * @param string $fIni, fecha de inicio de envio
     * @param string $fFin='', fecha finalización de envio
     * @param string $tipo='0', Tipo de etique de envio
     * @param string $top='1100', Margen superior de la etiqueta de envio
     * @param string $left='650' Margen izquierdo de la etiqueta de envio
     * @return array $parametros
     */
    public function getParamsEtiquetaMRW($nroEnv, $sep, $fIni, $fFin = '', $tipo = '0', $top = '1100', $left = '650')
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getParamsEtiquetaMRW:-> Entramos en la funcion para el envío (' . $nroEnv . ')');
        }

        $parametros = array(
            'request' => array(
                'NumeroEnvio' => $nroEnv,
                'SeparadorNumerosEnvio' => $sep,
                'FechaInicioEnvio' => $fIni,
                'FechaFinEnvio' => $fFin,
                'TipoEtiquetaEnvio' => $tipo,
                'ReportTopMargin' => $top,
                'ReportLeftMargin' => $left,
            ),
        );
        return $parametros;
    }

    /**
     * Crear y escribir un archivo con los datos pasados por parametro, el nombre de archivo y contenido
     * La ruta donde se creará el archivo es /donwload/ticket_mrw/
     * @param string $content, contenido del archivo
     * @param string $filename, nombre de archivo
     * @return Bool True
     */
    public function writeTicketFile($content, $filename)
    {
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('writeTicketFile:-> Entramos en la funcion. Filename (' . $filename . ')');
        }

        $root = _PS_ROOT_DIR_ . '/download/ticket_mrw/';
        $base = $root;
        if (!is_dir($base)) {
            @mkdir($base, 0777);
        }
        $archivo = $base . $filename;
        $fp = fopen($archivo, "a");
        $write = fputs($fp, $content);
        fclose($fp);
        return true;
    }

    public function writeToFileParam($prm)
    {
        $archivo = _PS_ROOT_DIR_ . '/modules/' . $this->name . '/' . $this->name . '.log';
        $fecha = date("d-m-Y H:i:s");
        $fp = fopen($archivo, "a");
        fwrite($fp, $fecha . " \t " . $prm . PHP_EOL);
        fclose($fp);
    }

    /**
     * Construir options de formulario select con los estados de pedidos de prestashop
     * @param string $lang, idioma
     * @param string $act, estado seleccionado
     * @return string $opStates html
     */
    public function getOrderStates($lang, $act = '')
    {
        $opStates = '<option></option>';
        $query = '
        SELECT os.id_order_state, osl.name
        FROM ' . _DB_PREFIX_ . 'order_state AS os, ' . _DB_PREFIX_ . 'order_state_lang AS osl
        WHERE os.id_order_state = osl.id_order_state
        AND osl.id_lang = "' . $lang . '"
        ';

        $rowOrderState = Db::getInstance()->ExecuteS($query);
        if ($rowOrderState != '') {
            foreach ($rowOrderState as $item) {
                if (isset($act) and $act != '' and $act == $item['id_order_state']) {
                    $opStates .= '<option value="' . $item['id_order_state'] . '" selected>' . $item['name'] . '</option>';
                } else {
                    $opStates .= '<option value="' . $item['id_order_state'] . '">' . $item['name'] . '</option>';
                }
            }
        }

        return $opStates;
    }

    /**
     * Construir options de formulario select con los tipos de servicio MRW
     * @param string $act, servicio seleccionado
     * @return string $opService html
     */
    public function getServiceMRW($act = '')
    {
        $opService = '';
        $arrService = '';
        include dirname(__FILE__) . '/mrw_parametros.php';
        if ($arrService != '') {
            foreach ($arrService as $item) {
                if ($act == '' and '0200' == $item['code']) {
                    $opService .= '<option value="' . $item['code'] . '" selected>' . $item['name'] . '</option>';
                } else if (isset($act) and $act != '' and $act == $item['code']) {
                    $opService .= '<option value="' . $item['code'] . '" selected>' . $item['name'] . '</option>';
                } else {
                    $opService .= '<option value="' . $item['code'] . '">' . $item['name'] . '</option>';
                }
            }
        }

        return $opService;
    }

    /**
     * Recuperar el nombre del servicio MRW
     * @param string $act, servicio seleccionado
     * @return string $nameService
     */
    public function getServiceNameMRW($act)
    {
        $nameService = '';
        $arrService = '';
        include dirname(__FILE__) . '/mrw_parametros.php';
        if ($arrService != '') {
            foreach ($arrService as $item) {
                if ($act == $item['code']) {
                    $nameService = $item['name'] . ' (' . $item['code'] . ')';
                }
            }
        }

        return $nameService;
    }

    /**
     * Construir options de formulario select con los Abonados disponibles
     * @param string $act, estado seleccionado
     * @return string $opSubscriber html
     */
    public function getSubscribersMRW($act)
    {
        $opSubscriber = '';

        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
            $shop = 1;
        } else {
            $shop = $this->context->shop->id;
        }

        $query = ' SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_subs` where id_shop = ' . $shop;
        $rowSubscriber = Db::getInstance()->ExecuteS($query);
        if ($rowSubscriber && isset($rowSubscriber) && $rowSubscriber != '') {
            foreach ($rowSubscriber as $item) {
                if (isset($act) and $act != '' and $act == $item['id_subscriber']) {
                    $opSubscriber .= '<option value="' . $item['id_subscriber'] . '" selected> Nombre:' . $item['id_name'] . ' - Abonado:' . $item['subscriber'] . ' - Dept: ' . $item['department'] . '</option>';
                } else {
                    $opSubscriber .= '<option value="' . $item['id_subscriber'] . '"> Nombre:' . $item['id_name'] . ' - Abonado:' . $item['subscriber'] . ' - Dept:' . $item['department'] . '</option>';
                }
            }
        }

        return $opSubscriber;
    }

    /**
     * Convertir array en cadena
     * @param object/array $vector
     * @return string $opService // esta función ya no se utiliza
     */
    public function arrayIndToString($vector)
    {
        $str = '';
        foreach ($vector as $indice => $valor) {
            $str .= $indice . '=' . $valor . ', ';
        }
        return $str;
    }

    /**
     * Obtener diferencia de fechas, entre fecha pasado por parametro y fecha actual del sistema
     * @param Date $lastDate, formato de fecha utilizada "Y-m-d H:i:s"
     * @return Date $dif
     */
    public function getTimeDif($lastDate)
    {
        $now = date("Y-m-d H:i:s");
        $segundos = strtotime($now) - strtotime($lastDate);
        $yea = '';
        $mon = '';
        $day = intval($segundos / 60 / 60 / 24);
        $hrs = intval($segundos / 60 / 60);
        $min = intval($segundos / 60);
        $dif = array('year' => $yea, 'month' => $mon, 'day' => $day, 'hrs' => $hrs, 'min' => $min, 'lastTime' => $now);
        return $dif;
    }

    public function isCarrierMRW($id_order)
    {
        $stateTrans = Configuration::get($this->rename . 'CARRIER_ID_MRW');
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('isCarrierMRW -> Entramos en el enlace con valor = ' . $stateTrans . ' en el proceso');
        }

        $arrST = explode(',', $stateTrans);

        $query = 'SELECT id_carrier FROM  ' . _DB_PREFIX_ . 'orders WHERE  id_order = ' . $id_order;
        $rowCarrier = Db::getInstance()->getRow($query);
        if (in_array($rowCarrier['id_carrier'], $arrST)) {
            return true;
        } else {
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('isCarrierMRW -> No encontrado carrier ' . $stateTrans . ' para pedido '.$id_order);
            }
            return false;
        }
    }

    /*     ************************************  HOOKS ****************************************** */
    /**
     * Hook UpdateCarrier
     * Actualiza $this->rename.CARRIER_ID_MRW con el id del nuevo carrier creado
     * @param Object $params
     */
    public function hookupdateCarrier($params)
    {
        $carriers_str = Configuration::get($this->rename . 'CARRIER_ID_MRW');
        $carriers_arr = array();
        $carriers_out = '';
        $carriers_arr = explode(',', $carriers_str);
        foreach ($carriers_arr as $key => $value) {
            if ($key > 0) {
                $carriers_out .= ',';
            }

            $carriers_out .= ((int) $value == (int) $params['id_carrier'] ? (int) $params['carrier']->id : (int) $value);
        }
        if ($this->debug_activo == '1') {
            $this->writeToFileParam('hookupdateCarrier:-> Entramos en el HOOK. Carriers_in(' . $carriers_str . '), Carriers_out (' . $carriers_out . ')');
        }

        Configuration::updateValue($this->rename . 'CARRIER_ID_MRW', $carriers_out);
    }

    public function checkTime($minutes)
    {
        $time = time();
        $lastupdated = Configuration::get($this->rename . 'LAST_EXECUTE_MRW');
        if ($lastupdated < ($time - (60 * $minutes))) {
            Configuration::updateValue($this->rename . 'LAST_EXECUTE_MRW', $time);
            return true;
        }
        return false;
    }

    public function hookbackOfficeFooter($params)
    {
        $minutes = 1;
        $minutes_free = 15; /* Se libera la sesión después de 15 minutos por si hubiera bloqueos */
        $fecha = Configuration::get($this->rename . 'LAST_EXECUTE_MRW');
        global $cookie;
        if ($this->debug_activo == '1')
            $this->writeToFileParam('Entering the function with:-> $fecha=' . $fecha . '');


        $query = 'SELECT `check_field` FROM ' . _DB_PREFIX_ . $this->name .  '_test';
        $rowCarrier = Db::getInstance()->getValue($query);

        if($rowCarrier == 0){

        $query2 = 'UPDATE  ' . _DB_PREFIX_ . $this->name .  '_test SET `check_field` = 1';
        $setCheck = Db::getInstance()->Execute($query2);

            if ($this->checkTime($minutes)) {
                //$this->writeToFileParam('Executing the code');
                try {
                    if ($this->debug_activo == '1')
                    $this->writeToFileParam('Valor del check, id empleado: ' . $rowCarrier . ', ' . (int)($cookie->id_employee));

                    if (Configuration::get($this->rename . 'TICKET_MRW') == 1) {
                        $this->executeSendMRW();
                        $this->executeCreatePDFTicket();
                    }
                    $fecha = Configuration::get($this->rename . 'LAST_EXECUTE_MRW');
                } catch (Exception $e) {
                    /*Liberamos la sesión si se ha producido un error */
                    $time = time();
                    Configuration::updateValue($this->rename . 'LAST_EXECUTE_MRW', $time);
                    $query2 = 'UPDATE  ' . _DB_PREFIX_ . $this->name .  '_test SET `check_field` = 0';
                    $setCheck = Db::getInstance()->Execute($query2);

                }
            } else {
                /* No Code Executed */
                //$this->writeToFileParam('Else hookBackOfficeFooter :-> No code executed');

            }

            $query2 = 'UPDATE  ' . _DB_PREFIX_ . $this->name .  '_test SET `check_field` = 0';
            $setCheck = Db::getInstance()->Execute($query2);

        } else {

            /* Después de 15 minutos, si no se ha liberado la sesión, se libera automáticamente */
            if ($this->checkTime($minutes_free)) {
                $query2 = 'UPDATE  ' . _DB_PREFIX_ . $this->name .  '_test SET `check_field` = 0';
                $setCheck = Db::getInstance()->Execute($query2);
                $this->writeToFileParam('Liberamos sesión después de 60 minutos');
            }
            $this->writeToFileParam('Sesión activada no realizará envío');

        }

    }

    /**
     * Muestra información en la página de detalle de pedido en el Backoffice Prestashop
     * Procesa la conexión manual del módulo con el webservice MRW, pora generar las solicitudes de envio
     * Procesa cambio de número de bultos por pedido
     * Genera solicitudes de envio a mrw
     * Genera etiquetas de envio
     * @param Object $params
     * @result Object $output_mrw html
     */
    public function hookAdminOrder($params)
    {
        $id_order = $params['id_order'];
        $okticket = 0;
        $txt_resp = '';
        $btExecuteGenTincketMRW = '';
        $btExecuteSendMRW = '';     
        $style14 = '';
        $showLink = '';
        $showUpperLink = '';
        $changeLump = '';
        $errorMsg = '';

        if ($this->isCarrierMRW($id_order)) {
            // si transportista mrw
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('hookAdminOrder:-> Entramos en el HOOK. Pedido (' . $id_order . ')');
            }

            if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {
                //v1.5.x y v1.6.x
                $pedido = new Order($id_order);
                $last_id_order_state = $pedido->current_state;
            } else {
                $history = new OrderHistory();
                $items_order_state = $history->getLastOrderState($id_order);
                $last_id_order_state = $items_order_state->id;
            }
            if ($this->debug_activo == '1') {
                $this->writeToFileParam('hookAdminOrder:-> El estado del Pedido es (' . $last_id_order_state . ')');
            }

            // generar manualmente la solicitud de envio y la etiqueta
            if (Tools::isSubmit('okexecuteSendMRW')) {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('hookAdminOrder:-> Se ha forzado manualmente la generación del envío');
                }

                if ($this->executeSendMRW($id_order)) {
                    if (!$this->executeCreatePDFTicket($id_order)){
                        $this->writeToFileParam('hookAdminOrder:-> ERROR: No se pudo generar Ticket PDF');
                        $errorMsg = '<p>' . $this->l('No se pudo generar el ticket PDF') . '</p>';
                    }
                } else {
                    $this->writeToFileParam('hookAdminOrder:-> ERROR: No se pudo realizar la solicitud de envio a MRW');
                    $showLink .= '<p>' . $this->l('No se pudo realizar la solicitud de envio a MRW') . '</p>';
                }
            }
            if (Tools::isSubmit('okexecuteGenTicket')) {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('hookAdminOrder:-> Se ha forzado manualmente la generación solo de la etiqueta ???');
                }

                $this->executeCreatePDFTicket($id_order);
            }

            $rowCarrier = $this->getInfoPedidoMRW($id_order);
            $path_myroot = _PS_BASE_URL_ . __PS_BASE_URI__;
            if ($rowCarrier && $rowCarrier['send_num_mrw'] != null) {
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('hookAdminOrder:-> Tenemos numero de expedicion (' . $rowCarrier['send_num_mrw'] . ')');
                }

                if (file_exists(_PS_ROOT_DIR_ . "/download/ticket_mrw/" . $rowCarrier['send_num_mrw'] . ".pdf")) {
                    $path_download = $path_myroot . 'download/ticket_mrw/download.php?f=' . $rowCarrier['send_num_mrw'] . '.pdf';
                    $showLink = '
                    <p>' . $this->l('Pedido Nº :') . ' <strong>' . $id_order . '</strong></p>
                    <p>' . $this->l('Nro de Envio :') . ' <strong>' . $rowCarrier['send_num_mrw'] . '</strong></p>
                    <p><a href="' . $path_download . '" style="color:red">' . $this->l('Pulse aqui para descargar') . ' <strong>' . $this->l('Etiqueta de transporte') . '</strong></a></p>
                    ';
                    $okticket = 1;
                    $btExecuteGenTincketMRW = '';
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('hookAdminOrder:-> Y la etiqueta esta descargada (' . $path_download . ')');
                    }
                } else {
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('hookAdminOrder:-> Pero la etiqueta no esta descargada.');
                    }

                    if (Configuration::get($this->rename . 'AFTER_SENDING_MRW') == $last_id_order_state) {
                        $btExecuteGenTincketMRW = '
                                <form name="frmExecuteTicketMRW" method="post" action="">
                                <input type="hidden" name="genok" id="genok" value="' . $this->l('Ejecutar el envio de MRW') . '">
                                <input type="submit" name="okexecuteGenTicket" id="okexecuteGenTicket" value="' . $this->l('Generar y descargar Etiqueta MRW') . '">
                                </form>
                        ';
                        if ($this->debug_activo == '1') {
                            $this->writeToFileParam('hookAdminOrder:-> El estado es el correcto por lo que mostramos el boton para generar solo la etiqueta.');
                        }
                    }
                }

                $btExecuteSendMRW = '';
            } else {
                /*$showLink = '
                 <p>' . $this->l('El pedido no tiene registros de etiquetas de envio') . '</p>
                 <p><FONT COLOR=red> ' . $this->l('Recuerde pulsar ACTUALIZAR CAMBIOS si ha realizado modificaciones en algún valor de este envío') . '</p>
                 ';*/
                 $showLink = '';
                 $showUpperLink='
                 <p><FONT COLOR=red> ' . $errorMsg . $this->l('Aún no ha realizado la solicitud del envío') . '</FONT></p>
                 <p> ' . $this->l('En este momento puede cambiar alguno de los valores por defecto si es necesario. Si lo hace, recuerde pulsar ACTUALIZAR CAMBIOS antes de ejecutar la solicitud de envío. Si todo es correcto, se generará un enlace a la etiqueta') . '</p>
                 ';
                 
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('hookAdminOrder:-> Aun no tenemos numero de expedicion.');
                }

                if (Configuration::get($this->rename . 'BEFORE_SENDING_MRW') == $last_id_order_state) {
                    // esta en estado para enviar pero no se ha enviado la solicitud
                    $btExecuteSendMRW = '
                        <form name="frmExecuteMRW" method="post" action="">
                        <input type="hidden" name="genok" id="genok" value="' . $this->l('Ejecutar el envio de MRW') . '">
                        <input type="submit" name="okexecuteSendMRW" id="okexecuteSendMRW" value="' . $this->l('Ejecutar la solicitud de envio a MRW') . '">
                        </form>
                    ';
                    $btExecuteGenTincketMRW = '';
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('hookAdminOrder:-> El estado es el correcto para generar expedicion y etiqueta. Mostramos el boton.');
                    }
                }
            }

            // PROCCESS LUMP
            if (Configuration::get($this->rename . 'GESTION_BULTOS') == 1) {
                $txt_res = '';
                if (Tools::isSubmit('regNewLump')) {
                    $newBulto = Tools::getValue('newLump');
                    if ($this->debug_activo == '1') {
                        $this->writeToFileParam('hookAdminOrder:-> Se ha solicitado un cambio de bultos (' . $newBulto . ')');
                    }

                    $rowBultos = $this->getInfoPedidoMRW($id_order);
                    $txt_res = $this->l('Número de bultos ');
                    if ($rowBultos && isset($rowBultos) && $rowBultos['cant'] != '') {
                        $sql_bultos = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw SET cant = ' . $newBulto . ' WHERE order_id = ' . $id_order;
                        $txt_res .= ' ' . $this->l('modificado');
                    } else {
                        // Se consigue el servicio por defecto por si no hubiera registro
                        if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                            $shop = 1;
                        } else {
                            $shop = $this->context->shop->id;
                        }

                        $servSubscriber = $this->getDefaultSubscriberService($shop);
                        // Se recupera si es un Carrier con Entrega En Franquicia activado por defecto.
                        $agency = '0';
                        if ($this->getEsEntregaFranquicia($id_order)) {
                            $agency = '1';
                        }

                        $sql_bultos = 'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_mrw`
                                      (
                                           `id_mrwcarrier_mrw`,`id_shop`, `order_id`,`subscriber`,`date`,`cant`
                                           ,`service`, `saturday`,`agency`, `backReturn`
                                      )
                                      VALUES
                                      (
                                            NULL, ' . $shop . ' , ' . $id_order . ',' . $servSubscriber['id_subscriber'] . '
                                            , NOW(), ' . $newBulto . ', "' . $servSubscriber['Service'] . '", 0, ' . $agency . ', 0
                                      )';
                        $txt_res .= ' ' . $this->l('registrado');
                    }
                    Db::getInstance()->Execute($sql_bultos);
                }

                $rowBultos = $this->getInfoPedidoMRW($id_order);
                $nroLump = 1;
                if ($rowBultos && isset($rowBultos)) {
                    $nroLump = ($rowBultos['order_id'] != '') ? $rowBultos['cant'] : 1;
                }

                if ($okticket == 1) {
                    // no show boton change lump
                    $changeLump = '<p>' . $this->l('Nro de bultos') . ': <strong>' . $nroLump . '</strong></p>';
                } else {
                    $changeLump = '
                    <form name="frmChangeLump" method="post" action="">
                        <label style="width:120px; float:left; text-align:left;font-weight:normal">' . $this->l('Número de bultos') . '</label>
                        <input style="width:30px" float:left; type="text" name="newLump" id="newLump" value="' . $nroLump . '">
                        <input type="submit" name="regNewLump" id="regNewLump" value="' . $this->l('Cambiar número de bultos') . '">  <span style="color:red">' . $txt_res . '</span>
                    </form><br>
                ';
                }
            }
            if (Tools::isSubmit('regNewServ')) {
                $newServ = Tools::getValue('ch_mrw_servicio');
                $newAbonado = Tools::getValue('ch_mrw_subscriber');
                $entrega_sabado = (Tools::getValue('entrega_sabado') == 'on' or Tools::getValue('entrega_sabado') == 'ON') ? 1 : 0;
                $entrega_franquicia = (Tools::getValue('entrega_franquicia') == 'on' or Tools::getValue('entrega_franquicia') == 'ON') ? 1 : 0;
                $retorno_activado = (Tools::getValue('retorno_activado') == 'on' or Tools::getValue('retorno_activado') == 'ON') ? 1 : 0;
                $newAddress = (Tools::getValue('ch_mrw_warehouse'));
                $newSlot = (Tools::getValue('ch_mrw_slot'));

                //Se revisa si existe algún registro previos de la nueva orden
                $rowServ = $this->getInfoPedidoMRW($id_order);
                $txt_resp = $this->l('Tipo de servicio/entrega o abonado ');
                if ($this->debug_activo == '1') {
                    $this->writeToFileParam('hookAdminOrder:-> Se ha solicitado un cambio de servicio (' . $newServ . ')
                    ,la entrega en sabado (' . $entrega_sabado . '), la entrega en franquicia (' . $entrega_franquicia . ')
                    , el cambio de Abonado (' . $newAbonado . '), el cambio de Retorno (' . $retorno_activado . '), el cambio de dirección (' . $newAddress . ') o el cambio de tramo (' . $newSlot . ')');
                }

                if ($rowServ) {
                    //La query a ejecutar por defecto y se irá cambiando a medida que se vayan modificando los valores
                    $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                 SET saturday = "' . $entrega_sabado . '", agency = "' . $entrega_franquicia . '"
                                 WHERE order_id = ' . $id_order;

                    if ($rowServ['service'] != '' && $rowServ['service'] != $newServ) {
                        $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                    SET service = "' . $newServ . '", saturday = "' . $entrega_sabado . '", agency = "' . $entrega_franquicia . '"
                                    WHERE order_id = ' . $id_order;
                        $txt_resp .= ' ' . $this->l('modificado');
                    }
                    if ($rowServ['subscriber'] != '' && $rowServ['subscriber'] != $newAbonado) {
                        //Si se cambia el Abonado se cambiará el servicio por defecto
                        $newServ = $this->getServiceSubscriber($newAbonado);
                        $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                    SET service = "' . $newServ . '", saturday = "' . $entrega_sabado . '"
                                    , agency = "' . $entrega_franquicia . '", subscriber = "' . $newAbonado . '"
                                    WHERE order_id = ' . $id_order;
                        $txt_resp .= ' ' . $this->l('modificado');
                    }
                    if ($rowServ['backReturn'] != '' && $rowServ['backReturn'] != $retorno_activado) {
                        //Si se cambia el Abonado se cambiará el servicio por defecto
                        $newServ = $this->getServiceSubscriber($retorno_activado);
                        $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                    SET service = "' . $newServ . '", saturday = "' . $entrega_sabado . '"
                                    , agency = "' . $entrega_franquicia . '", subscriber = "' . $newAbonado . '"
                                    , backReturn = "' . $retorno_activado . '"
                                    WHERE order_id = ' . $id_order;
                        $txt_resp .= ' ' . $this->l('modificado');
                    }

                    $newWarehouse = Tools::getValue('ch_mrw_warehouse');

                    if ($rowServ['mrw_warehouse'] != '' && $rowServ['mrw_warehouse'] != $newWarehouse) {
                        $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                    SET mrw_warehouse = "' . $newWarehouse . '"
                                    WHERE order_id = ' . $id_order;
                        $txt_resp .= ' ' . $this->l('Dirección de recogida modificada');
                    }

                    $newSlot = Tools::getValue('ch_mrw_slot');

                    if ($rowServ['mrw_slot'] != '' && $rowServ['mrw_slot'] != $newSlot) {
                        $sql_serv = 'UPDATE ' . _DB_PREFIX_ . $this->name . '_mrw
                                    SET mrw_slot = "' . $newSlot . '"
                                    WHERE order_id = ' . $id_order;
                        $txt_resp .= ' ' . $this->l('Tramo horario modificado');
                    }

                } else {
                    if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                        $shop = 1;
                    } else {
                        $shop = $this->context->shop->id;
                    }

                    $rowSubscriber = $this->getDefaultSubscriber($shop);
                    if ($rowSubscriber['id_subscriber'] != '' && $rowSubscriber['id_subscriber'] != $newAbonado) {
                        $newServ = $this->getServiceSubscriber($newAbonado);
                    }


                    if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0))
                    {
                    
                        $sql_serv = 'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_mrw`
                                (
                                     `id_mrwcarrier_mrw`, `id_shop`,`order_id`,`date`,`subscriber`,`cant`, `service`,
                                     `saturday`,`agency`, `backReturn`, `mrw_slot`
                                )
                                     VALUES
                                (
                                     NULL, ' . $shop . ' , ' . $id_order . ', NOW(), "' . $newAbonado . '",
                                     1,"' . $newServ . '", ' . $entrega_sabado . ',' . $entrega_franquicia . '
                                     ,' . $retorno_activado . ',' . $newSlot . '
                                )';
                        $txt_resp .= ' ' . $this->l('registrado');

                    }
                    else if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)) {

                    $sql_serv = 'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_mrw`
                                (
                                     `id_mrwcarrier_mrw`, `id_shop`,`order_id`,`date`,`subscriber`,`cant`, `service`,
                                     `saturday`,`agency`, `backReturn`, `mrw_warehouse`, `mrw_slot`
                                )
                                     VALUES
                                (
                                     NULL, ' . $shop . ' , ' . $id_order . ', NOW(), "' . $newAbonado . '",
                                     1,"' . $newServ . '", ' . $entrega_sabado . ',' . $entrega_franquicia . '
                                     ,' . $retorno_activado . ',' . $newAddress . ',' . $newSlot . '
                                )';
                    $txt_resp .= ' ' . $this->l('registrado');
                    }
                }
                Db::getInstance()->Execute($sql_serv);
            }

            $rowServ = $this->getInfoPedidoMRW($id_order);
            $servAct = '';
            $entregaSabado = '';
            $val_entrega_sabado = 'NO';
            $entregaFranquicia = '';
            $val_entrega_franquicia = 'NO';
            $retornoActivado = '';
            $val_retorno_activado = 'NO';
            $val_warehouse = $rowServ['mrw_warehouse'];
            $val_slotId = $rowServ['mrw_slot'];

            //Se comprueba si por defecto el abonado tiene la entrega en franquicia
            if ($this->getEsEntregaFranquicia($id_order)) {
                $entregaFranquicia = 'checked';
                $val_entrega_franquicia = 'SI';
            }

            if ($rowServ && isset($rowServ)) {
                $servAct = ($rowServ['service'] != '') ? $rowServ['service'] : Configuration::get($this->rename . 'SERVICE_MRW');
                $entregaSabado = ($rowServ['saturday'] == 1) ? 'checked' : '';
                $val_entrega_sabado = ($rowServ['saturday'] == 1) ? 'SI' : 'NO';
                $entregaFranquicia = ($rowServ['agency'] == 1) ? 'checked' : '';
                $val_entrega_franquicia = ($rowServ['agency'] == 1) ? 'SI' : 'NO';
                $retornoActivado = ($rowServ['backReturn'] == 1) ? 'checked' : '';
                $val_retorno_activado = ($rowServ['backReturn'] == 1) ? 'SI' : 'NO';
                $val_slot = $this->getSlotDesc($rowServ['mrw_slot']);
                if ($rowServ['subscriber'] != '') {
                    $SubscriberInfo = $this->getInfoSubscriber($rowServ['subscriber']);
                }
            }

            if ($okticket == 1) {
                // no mostrar form de cambio de servicio
                $serviceNameMRW = $this->getServiceNameMRW($servAct);
                $changeServ = '
                    <p>' . $this->l('Configuracion:') . ' <strong>' . $SubscriberInfo['id_name'] . '</strong> - ' . $this->l('Abonado:') . ' <strong>' . $SubscriberInfo['subscriber'] . '</strong> - Dep: <strong>' . $SubscriberInfo['department'] . '</strong></p>
                    <p>' . $this->l('Tipo de Servicio:') . ' <strong>' . $serviceNameMRW . '</strong></p>
                    <p>' . $this->l('Entrega en Sábado:') . ' <strong>' . $val_entrega_sabado . '</strong></p>
                    <p>' . $this->l('Entrega en Franquicia:') . ' <strong>' . $val_entrega_franquicia . '</strong></p>
                    <p>' . $this->l('Envío con Retorno:') . ' <strong>' . $val_retorno_activado . '</strong></p>
                    <p>' . $this->l('Tramo horario:') . ' <strong>' . $val_slot . '</strong></p>
                        ';
            } else {
                //Miramos si hay abonado y recuperamos el que está por defecto.
                if ($rowServ['subscriber'] == '') {
                    if ((strncmp(_PS_VERSION_, "1.4", 3) == 0)) {
                        $shop = 1;
                    } else {
                        $shop = $this->context->shop->id;
                    }

                    $row_subscriber = $this->getDefaultSubscriberService($shop);
                    $id_subscriber = $row_subscriber['id_subscriber'];
                    $servAct = $row_subscriber['Service'];
                } else {
                    $id_subscriber = $rowServ['subscriber'];
                }

                $serviceMRW = $this->getServiceMRW($servAct);
                $id_Subscriber_List = $this->getSubscribersMRW($id_subscriber);

                //Form for PS 1.4 and 1.7 versions (no warehouses allowed)
                if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.7", 3) == 0)) {


                    $id_Slots_List =  $this->getSlotsList($val_slotId);

                    $changeServ = '
                    <form name="frmChangeServ" method="post" action="">
                        <label style="width:120px; text-align:left;font-weight:normal">' . $this->l('Tipo de servicio:') . '</label>
                        <select name="ch_mrw_servicio" id="ch_mrw_servicio" style="width:80%">
                            ' . $serviceMRW . '
                        </select><br>
                        <label style="width:120px; text-align:left;font-weight:normal">' . $this->l('Abonado:') . '</label>
                        <select name="ch_mrw_subscriber" id="ch_mrw_subscriber" style="width:80%">
                            ' . $id_Subscriber_List . '
                        </select><br>
                        <label style="text-align:left;font-weight:normal">' . $this->l('Tramo horario (esta opción solo se tendrá en cuenta en caso de haber seleccionado el servicio Ecommerce):') . '</label>
                        <select name="ch_mrw_slot" id="ch_mrw_slot" style="width:80%">
                                    ' . $id_Slots_List . '</select>
                        <br>
                        <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Entrega en Sábado:') . '</label>
                        <input type="checkbox" name="entrega_sabado" id="entrega_sabado" ' . $entregaSabado . ' style="margin-top:4px">
                        <br><br>
                        <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Entrega en Franquicia:') . '</label>
                        <input type="checkbox" name="entrega_franquicia" id="entrega_franquicia" ' . $entregaFranquicia . ' style="margin-bottom:2px">
                        <br><br>
                        <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Envío con Retorno:') . '</label>
                        <input type="checkbox" name="retorno_activado" id="retorno_activado" ' . $retornoActivado . ' style="margin-bottom:2px">
                        <br>
                        <input style="margin:3px" type="submit" name="regNewServ" id="regNewServ" value="' . $this->l('ACTUALIZAR CAMBIOS') . '"><br><span style="color:red">' . $txt_resp . '</span>
                    </form>
                    ';
                    } else if ((strncmp(_PS_VERSION_, "1.5", 3) == 0) || (strncmp(_PS_VERSION_, "1.6", 3) == 0)){

                        $id_Warehouses_List = $this->getWarehousesMRW($val_warehouse);

                        $id_Slots_List =  $this->getSlotsList($val_slotId);

                        $changeServ = '
                            <form name="frmChangeServ" method="post" action="">
                                <label style="width:120px; text-align:left;font-weight:normal">' . $this->l('Tipo de servicio:') . '</label>
                                <select name="ch_mrw_servicio" id="ch_mrw_servicio" style="width:80%">
                                    ' . $serviceMRW . '
                                </select><br>
                                <label style="width:120px; text-align:left;font-weight:normal">' . $this->l('Abonado:') . '</label>
                                <select name="ch_mrw_subscriber" id="ch_mrw_subscriber" style="width:80%">
                                    ' . $id_Subscriber_List . '
                                </select><br>
                                <label style="width:160px; text-align:left;font-weight:normal">' . $this->l('Dirección de recogida:') . '</label>
                                <select name="ch_mrw_warehouse" id="ch_mrw_warehouse" style="width:80%">
                                    ' . $id_Warehouses_List . '
                                </select><br>
                                <label style="text-align:left;font-weight:normal">' . $this->l('Tramo horario (esta opción solo se tendrá en cuenta en caso de haber seleccionado el servicio Ecommerce):') . '</label>
                                <select name="ch_mrw_slot" id="ch_mrw_slot" style="width:80%">
                                    ' . $id_Slots_List . '
                                </select><br>
                                <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Entrega en Sábado:') . '</label>
                                <input type="checkbox" name="entrega_sabado" id="entrega_sabado" ' . $entregaSabado . ' style="margin-top:4px">
                                <br><br>
                                <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Entrega en Franquicia:') . '</label>
                                <input type="checkbox" name="entrega_franquicia" id="entrega_franquicia" ' . $entregaFranquicia . ' style="margin-bottom:2px">
                                <br><br>
                                <label style="width:140px;text-align:left;font-weight:normal">' . $this->l('Envío con Retorno:') . '</label>
                                <input type="checkbox" name="retorno_activado" id="retorno_activado" ' . $retornoActivado . ' style="margin-bottom:2px">
                                <br>
                                <input style="margin:3px" type="submit" name="regNewServ" id="regNewServ" value="' . $this->l('ACTUALIZAR CAMBIOS') . '"><br><span style="color:red">' . $txt_resp . '</span>
                            </form>
                            ';
                    }
            }
            if ((strncmp(_PS_VERSION_, "1.4", 3) == 0) || (strncmp(_PS_VERSION_, "1.5", 3) == 0)) {
                if (strncmp(_PS_VERSION_, "1.4", 3) == 0) {
                    $style14 = 'style="width:400px;"';
                }

                $output_mrw = '<br />
                               <fieldset ' . $style14 . '>
                                <legend><img src="../modules/' . $this->name . '/logo.gif" alt="" width="80" class="middle"/>' . $this->l('Etiquetas de envio para clientes') . '</legend>
                                ' . $showUpperLink . '<br>
                                ' . $changeLump . '
                                ' . $changeServ . '
                                ' . $showLink . '<br>
                                ' . $btExecuteGenTincketMRW . '
                                ' . $btExecuteSendMRW . '
                                </fieldset>';
                return $output_mrw;
            } elseif (strncmp(_PS_VERSION_, "1.6", 3) == 0 || (strncmp(_PS_VERSION_, "1.7", 3) == 0)){
                    $output_mrw = '
                <div class="tab-content panel">
                    <div class="tab-pane active" id="mrw">
                        <div class="panel-heading"><i class="icon-truck "></i>' .$this->l('Etiquetas de envio para clientes') . ' <img src="../modules/' . $this->name . '/logo.gif" alt="" width="80" class="middle"/></div>
                        <div class="table-responsive">
                            <fieldset ' . $style14 . '>                               
                                ' . $showUpperLink . '<br>
                                ' . $changeLump . '
                                ' . $changeServ . '
                                ' . $showLink . '<br>
                                ' . $btExecuteGenTincketMRW . '
                                ' . $btExecuteSendMRW . '
                            </fieldset>
                        </div>
                    </div>
                </div>';
                return $output_mrw;
            } else {
                $output_mrw = '<div class="nobootstrap" style="padding:0"><br />
                                <fieldset style="width:60%">
                                <legend style="font-size:12px"><img src="../modules/' . $this->name . '/logo.gif" alt="" width="80" class="middle" />' . $this->l('Etiquetas de envio para clientes') . '</legend>
                                ' . $showUpperLink . '<br>
                                ' . $changeLump . '
                                ' . $changeServ . '
                                ' . $showLink . '<br>
                                ' . $btExecuteGenTincketMRW . '
                                ' . $btExecuteSendMRW . '
                                </fieldset></div>';
                return $output_mrw;
            }
        }
    }

    /**
     * Obtener valor del token prestashop (diferentes versiones) para utilizar en los enlaces de configuración del módulo
     * @param String $tab, AdminParentShipping para Prestashop 1.5 y AdminShipping prestashop 1.4
     * @param String $subtab, AdminCarriers para prestashop 1.5 y prestashop 1.4
     * @return String $token
     */
    public function getAdminTabToken($tab, $subtab)
    {
        global $cookie;
        $token = '';
        $tabs = Tab::getTabs((int) $cookie->id_lang, 0);
        foreach ($tabs as $t) {
            if ($t['class_name'] == $tab) {
                $subTabs = Tab::getTabs((int) $cookie->id_lang, (int) $t['id_tab']);
                foreach ($subTabs as $t2) {
                    if ($t2['class_name'] == $subtab) {
                        $token = Tools::getAdminToken($t2['class_name'] . (int) ($t2['id_tab']) . (int) ($cookie->id_employee));
                    }
                }
            }
        }

        return $token;
    }

    /**
     * Procesar si el número de teléfono es válido 
     * Se consideran teléfonos españoles válidos todos aquellos que tengan 9 dígitos y comiencen por 6 o 7 (móviles) u 8 o 9 (fijos) precedidos o no de +34, 0034 o 34.
     * @param String $phone, Número de telefono
     * @return int 1 correcto /0 incorrecto
     */
    function phoneValid($phone)
    {

        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);

        $re = '/^(\+34|0034|34)?[6-9][0-9]{8}$/';

        return preg_match($re, $phone);
    }

    /**
     * Obtener datos del usario/cliente registrado en prestashop
     * @param Int $id_customer, numero de usuario para que se recuperará toda su información
     * @return Array $row Lista de datos de Usuario Prestashop
     */
    public function getCustomer($id_customer)
    {
        $query = '
                SELECT *
                FROM ' . _DB_PREFIX_ . 'customer AS c
                WHERE c.id_customer = ' . $id_customer . '
        ';
        $row = Db::getInstance()->getRow($query);
        return $row;
    }

    /**
     * Verifica si el directorio y archivo pasado por parámetro existe o ha sido creado
     * @param String $pathDir, Ruta del directorio
     * @param String $file,  Nombre de archivo
     * @return Array $res Cadena de texto con información de, existe o no el directorio y html con mensaje de directorio no creado
     */
    public function verificFileDir($pathDir, $file)
    {
        if (file_exists($pathDir)) {
            // exist directory
            $res['dir'] = 'direxist'; //"Exist ".$pathDir;
            $control = fopen($pathDir . "/" . $file, "w+"); // verific is writable
            if ($control == false) {
                $res['file'] = '<div class="warn">' . $pathDir . ' ' . $this->l('Este directorio no tiene permiso de escritura.') . '<br>
                    ' . $this->l('Para generar las etiquedas de envio MRW es preciso tener permiso de escritura') . ' <br>
                    ' . $this->permsPath($pathDir) . '   ' . $pathDir . '
                        </div>';
            } else {
                $res['file'] = 'filecreateok';
            }
        } else {
            $res['dir'] = '<div class="warn">' . $this->l('Al instalar el modulo MRW no se ha creado el directorio') . ' ' . $pathDir . ' ' . $this->l('verifique los permisos de escritura y vuelva a instalar el módulo') . '</div>';
        }
        return $res;
    }

    /**
     * Procesa los permisos de lectura/escritura/ejecución de una ruta/carpeta
     * @param String $path, ruta del archivo o directorio
     * @return String $info Información de los permisos de lectura/escritura del archivo
     */
    public function permsPath($path)
    {
        // Verific perms directory or files
        $perms = fileperms($path);
        $info ='';
        // Dueño
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x'):
            (($perms & 0x0800) ? 'S' : '-'));
        // Grupo
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x'):
            (($perms & 0x0400) ? 'S' : '-'));
        // Mundo
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x'):
            (($perms & 0x0200) ? 'T' : '-'));
        return $info;
    }

    /**
     * Front Methods
     *
     * If you set need_range at true when you created your carrier (in install method), the method called by the cart will be getOrderShippingCost
     * If not, the method called will be getOrderShippingCostExternal
     *
     * @param Object $cart var contains the cart, the customer, the address
     * @param Double $shipping_cost var contains the price calculated by the range in carrier tab
     * @return $shipping_cost OR false if failed
     */

    public function getOrderShippingCost($cart, $shipping_cost)
    {
        if (!$this->active || !$cart->id_address_delivery) {
            return false;
        } else {
            return $shipping_cost;
        }
    }

    /**
     * If you set need_range as false the method called will be getOrderShippingCostExternal
     *
     * @param Object $params
     * @return boolean
     */
    public function getOrderShippingCostExternal($params)
    {
        return true;
    }
    
    /**
     * If you set need_range as false the method called will be getOrderShippingCostExternal
     *
     * @param Object $params
     * @return boolean
     */
    public function TildesHtml($cadena) 
    { 
        return str_replace(array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&aACUTE;","&eACUTE;","&iACUTE;","&oACUTE;","&uACUTE;","&nTILDE;",    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","&AACUTE;","&EACUTE;","&IACUTE;","&OACUTE;","&UACUTE;","&NTILDE;"),                                   array("á","é","í","ó","ú","ñ","á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","Á","É","Í","Ó","Ú","Ñ"), $cadena);     
    }

    /*Install MRW Tab function*/
    public function installTab($parent, $class_name, $name)
    {
        // Create new admin tab
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
        $tab->name[$lang['id_lang']] = $name;
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;
        return $tab->add();
    }

    /*Uninstall MRW Tab function*/
    public function uninstallTab($class_name)
    {
        // Retrieve Tab ID
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        // Load tab
        $tab = new Tab((int)$id_tab);
        // Delete it
        return $tab->delete();
    }

    //Hook to add css
    public function hookDisplayBackOfficeHeader()
    {
       $this->context->controller->addCss($this->_path.'css/mrwcarrier.css');
    }

    public function getWarehouseAddressToMRW($id_order, $warehouse)
    {
        $results = array();
        $sql = '
          SELECT a.address1, a.address2,
            CASE p.iso_code
            WHEN "PT" THEN SUBSTR(RTRIM(postcode),1,4)
            WHEN "ES" THEN SUBSTR(RTRIM(postcode),1,5)
            ELSE RTRIM(postcode) END as postcode,
          a.city, a.id_country, a.dni, a.alias, a.phone, a.phone_mobile, a.firstname, a.lastname, a.other, a.id_customer, p.iso_code, w.name
          FROM `' . _DB_PREFIX_ . 'orders` AS o, `' . _DB_PREFIX_ . 'carrier` AS t, `' . _DB_PREFIX_ . 'address` AS a, `' . _DB_PREFIX_ . 'country` AS p, `' . _DB_PREFIX_ . 'warehouse` AS w
          WHERE o.id_order = ' . $id_order . '
          AND o.id_carrier = t.id_carrier
          AND a.id_country = p.id_country
          AND a.id_warehouse = ' . $warehouse . '
          AND w.id_warehouse = ' . $warehouse . '
          ';

        $this->writeToFileParam('SQL Warehouse: ' . $sql);

        if ($this->debug_activo == '1') {
            $this->writeToFileParam('getWarehouseAddressToMRW:-> pasamos por esta funcion');
             //$this->writeToFileParam('sql dir almacen:-> '. $sql);
        }

        if ($id_order != '') {
            $results = Db::getInstance()->getRow($sql);
        }

        return $results;
    }

    public function getWarehousesMRW($act){

        //ToDo controlls multishop
        //$shop = $this->context->shop->id;

        $queryWarehouses = 'SELECT w.id_warehouse, w.name, a.id_address, a.id_country, a.id_state, a.alias, a.id_customer, a.id_manufacturer, a.id_supplier, a.id_warehouse, a.company, a.lastname, a.firstname, a.address1, a.address2, a.postcode, a.city, a.other, a.phone, a.phone_mobile, a.vat_number, a.alias, a.active FROM `' . _DB_PREFIX_ . 'warehouse` AS w INNER JOIN `' . _DB_PREFIX_ . 'address` AS a ON w.id_address=a.id_address AND w.deleted = 0;';

        $opWarehouses = '<option value="0">Dirección de recogida habitual</option>';

        $rowWarehouse = Db::getInstance()->ExecuteS($queryWarehouses);
       
        if ($rowWarehouse && isset($rowWarehouse) && $rowWarehouse != '') {
            foreach ($rowWarehouse as $item) {

                if (isset($act) and $act != '' and $act == $item['id_warehouse']) {
                    $opWarehouses .= '<option value="' . $item['id_warehouse'] . '" selected> Nombre dirección:  ' . $item['name'] . '  ' . 'Nombre y apellidos: ' . $item['name'] . ' - Dirección:  ' . $item['address1'] . ' ' . $item['address2'] . '</option>';
                } else {
                    $opWarehouses .= '<option value="' . $item['id_warehouse'] . '"> Nombre dirección:  ' . $item['name'] . '  ' . 'Nombre y apellidos: ' . $item['name'] . ' - Dirección:  ' . $item['address1'] . ' ' . $item['address2'] . '</option>';
                }
            }
        }
        return $opWarehouses;
    }

    public function getWarehouseNode($warehouseData) {

        $warehouseNode = array (
                    'Direccion' => array(
                        'CodigoDireccion' => ''//Opcional - Se puede omitir. Si se indica sustituira al resto de parametros
                        , 'CodigoTipoVia' => ''//'CL', //Opcional - Se puede omitir aunque es recomendable usarlo
                        , 'Via' => $warehouseData['address']//Obligatorio
                        , 'Numero' => $warehouseData['number']//Obligatorio - Recomendable que sea el dato real. Si no se puede extraer el dato real se pondra 0 (cero)
                        , 'Resto' => $warehouseData['others']//Opcional - Se puede omitir.
                        , 'CodigoPostal' => $warehouseData['postcode']//'08970', //Obligatorio
                        , 'Poblacion' => $warehouseData['city']//Obligatorio
                        , 'CodigoPais' => $warehouseData['iso_code'], //Opcional - Se puede omitir para envios nacionales.
                    )
                    , 'Nif' => $warehouseData['dni']//Obligatorio
                    , 'Nombre' => $warehouseData['name']//Obligatorio
                    , 'Telefono' => $this->getWarehouseTelephone($warehouseData)//Opcional - Muy recomendable
                    , 'Contacto' => $warehouseData['alias']//Opcional - Muy recomendable
                    , 'ALaAtencionDe' => $warehouseData['alias']//$row['firstname'], //Opcional - Se puede omitir.
                    , 'Horario' => array(//Opcional - Se puede omitir este campo y los sub-arrays
                        'Rangos' => array(// Si se indica horario, habrÃ¡ que informar al menos un rango (HorarioRangoRequest)
                            'HorarioRangoRequest' => array(
                                'Desde' => '',
                                'Hasta' => '',
                            ),
                        ),
                    )                    
                );

        return $warehouseNode;
        
    }

    public function getWarehouseTelephone($warehouseData){

        if($warehouseData['phone_mobile']){
            return str_replace(' ', '', $warehouseData['phone_mobile']);
        }else if($warehouseData['phone']){
            return str_replace(' ', '', $warehouseData['phone']);
        }else return '';
    }

    public function checkVersion(){


        if ( strncmp(_PS_VERSION_, "1.4", 3) == 0) {
            if (!parent::install()
                or !$this->registerHook('adminOrder')
                or !$this->registerHook('updateCarrier')){
                return false;
            }else return true;
        }

        else if( strncmp(_PS_VERSION_, "1.5", 3) == 0 || strncmp(_PS_VERSION_, "1.6", 3) == 0 || strncmp(_PS_VERSION_, "1.7", 3) == 0){
            if(!parent::install()
                or !$this->registerHook('backOfficeFooter')
                or !$this->registerHook('adminOrder')
                or !$this->registerHook('updateCarrier')
                or !$this->registerHook('DisplayBackOfficeHeader')){
                return false;
            }else return true;
        }
    }

    public function getSlotsList($act){

        $opSlots = '<option value="0" '.(($act==0)?'selected="selected"':"").' >Tramo estandar entre las 08:00 y las 19:00 horas</option>';
        $opSlots .= '<option value="1" '.(($act==1)?'selected="selected"':"").'>Tramo de mañana entre las 08:00 y las 14:00 horas</option>';

        $opSlots .= '<option value="2" '.(($act==2)?'selected="selected"':"").'>Tramo de tarde entre las 16:00 y las 19:00 horas</option>';

        $opSlots .= '<option value="3" '.(($act==3)?'selected="selected"':"").'>Tramo de noche entre las 20:00 y las 22:00 horas (Sólo disponible para los envíos a Barcelona y Madrid ciudad)</option>';

        return $opSlots;
    }

    public function getSlotDesc($slot){

        $slotDesc = '';

        switch ($slot){
            case 0:
                $slotDesc = 'Tramo horario estándar';
                break;
            case 1:
                $slotDesc = 'Tramo horario de mañana (08:00 - 14:00)';
                break;
            case 2:
                $slotDesc = 'Tramo horario de tarde (16:00 - 19:00)';
                break;
            case 3:
                $slotDesc = 'Tramo horario de noche (20:00 - 22:00)';
                break;
            default:
                $slotDesc = 'Tramo horario estándar';
                break;
        }

        return $slotDesc;
    }
}