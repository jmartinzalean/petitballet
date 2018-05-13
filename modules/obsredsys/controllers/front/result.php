<?php
/**
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 */

class ObsredsysResultModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		$result = (int)Tools::getValue('result');
		$cartId = (int)Tools::getValue('cartId');

		if ($result == 0)
		{
			$orderId = Order::getOrderByCartId($cartId);
			$order = new Order($orderId);
			$customer = new Customer($order->id_customer);

			Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cartId.
			'&id_module='.$this->module->id.'&id_order='.$orderId.'&key='.$customer->secure_key);
		}
		else
		{
			$this->context->smarty->assign(array(
				'module_dir' => _MODULE_DIR_.$this->module->name,
				'ps_module_dir' => _PS_MODULE_DIR_,
				'clear_cart' => (int)Configuration::get('OBSREDSYS_CLEAR_CART')
			));
			
			if (version_compare(_PS_VERSION_, '1.7', '>='))
				$this->setTemplate('module:'.$this->module->name.'/views/templates/front/resultErr17.tpl');
			else
				$this->setTemplate('resultErr.tpl');
		}
	}
}
