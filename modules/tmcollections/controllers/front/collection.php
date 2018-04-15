<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Collections
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    TemplateMonster
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class TmCollectionsCollectionModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->context = Context::getContext();
        $token = Tools::getValue('token');

        if ($token) {
            $collection = ClassTmCollections::getByToken($token);
            $products = ClassTmCollections::getProductByIdCollection((int)$collection['id_collection']);
            $collections = ClassTmCollections::getByIdCustomer((int)$collection['id_customer']);

            foreach ($collections as $key => $item) {
                if ($item['id_collection'] == $collection['id_collection']) {
                    unset($collections[$key]);
                    break;
                }
            }

            $this->context->smarty->assign(
                array(
                    'current_collection' => $collection,
                    'token' => $token,
                    'collections' => $collections,
                    'products' => $products
                )
            );
        }

        $this->setTemplate('collection.tpl');
    }
}
