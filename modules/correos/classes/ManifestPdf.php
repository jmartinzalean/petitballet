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

if (!defined('_PS_VERSION_')) {
    exit;
}

class HTMLTemplateManifestPdf extends HTMLTemplate
{
    public $custom_model;
    public function __construct($custom_object, $smarty)
    {
        $this->custom_model = $custom_object;
        $this->smarty = $smarty;
        $this->title = HTMLTemplateManifestPdf::l('Custom Title');
    }
    public function getContent()
    {
        return $this->smarty->fetch(_PS_MODULE_DIR_ . 'correos/views/templates/admin/manifest_content.tpl');
    }
 
    public function getLogo()
    {
        return false;
    }
 
    public function getHeader()
    {
        return $this->smarty->fetch(_PS_MODULE_DIR_ . 'correos/views/templates/admin/manifest_header.tpl');
    }

    public function getFooter()
    {
        return false;
    }
    /*Added to fix 1.6.1.5 BUG https://github.com/PrestaShop/PrestaShop/pull/5411/*/
    public function getPagination()
    {
        return false;
    }
    public function getFilename()
    {
        return 'custom_pdf.pdf';
    }

    public function getBulkFilename()
    {
        return 'custom_pdf.pdf';
    }
}
