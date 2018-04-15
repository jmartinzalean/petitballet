<?php

if (!defined('_PS_VERSION_'))
exit;

class PetitBalletModule extends Module {

    public function __construct() {
        $this->name = 'petitballetmodule';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'azertiumit';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min'=>'1.6','max'=>_PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Petit Ballet Module');
        $this->description = $this->l('Modulo de configuración general para petit ballet.');
        $this->confirmUninstall = $this->l('¿Desea desinstalar?');
    }

    public function install() {
        return parent::install()
            && $this->registerHook('header');
    }

    public function uninstall() {
        return parent::install()
            && $this->registerHook('header');
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/general.js');
    }
}

