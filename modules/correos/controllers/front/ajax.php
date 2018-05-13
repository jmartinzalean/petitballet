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

//header('Content-Type: application/json');
class CorreosAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        if (!$this->isXmlHttpRequest()) {
            Tools::redirect(__PS_BASE_URI__);
        }
        if (!$this->isTokenValid()) {
            die("Bad token");
        }

        if ((int)$this->context->cart->id_customer !== (int)$this->context->customer->id) {
            die("Bad token");
        }
    }
    public function displayAjaxGetPoint()
    {
        die(CorreosCommon::getOfficies(Tools::getValue('postcode'), 'front', Tools::getValue('id_carrier')));
    }
    public function displayAjaxupdateOfficeInfo()
    {
        CorreosFront::updateOfficeInfo($_POST);
        die(Tools::jsonEncode(null));
    }
    public function displayAjaxupdateHoursSelect()
    {
        CorreosFront::updateHoursSelect($_POST);
        die(Tools::jsonEncode(null));
    }
    public function displayAjaxupdateInternationalMobile()
    {
        CorreosFront::updateInternationalMobile($_POST);
        die(Tools::jsonEncode(null));
    }
    public function displayAjaxupdatePaq()
    {
        CorreosFront::updatePaq($_POST);
        die(Tools::jsonEncode(null));
    }
    public function displayAjaxGetCorreosPaqs()
    {
        die(CorreosCommon::getCorreosPaqs($_POST, 'front'));
    }
    public function displayAjaxgetStatesWithCitypaq()
    {
        die(CorreosCommon::getStatesWithCitypaq());
    }
    public function displayAjaxgetCitypaqs()
    {
        die(CorreosCommon::getCorreosPaqs($_POST, 'front'));
    }
    public function displayAjaxaddCityPaqtofavorites()
    {
        die(
            Tools::jsonEncode(
                array( 'url' => CorreosCommon::addCityPaqtofavorites($_POST))
            )
        );
    }
    public function displayAjaxGetPaqsFavourites()
    {
        die(
            Tools::jsonEncode(
                CorreosCommon::getPaqsWs(
                    array(
                        'acc' => 'GetCorreosPaqs',
                        'user' => Tools::getValue('homepaq_user')
                    )
                )
            )
        );
    }
}
