<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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

class AdminTmOneClickOrderController extends ModuleAdminController
{
    /**
     * @var Tmoneclickorder
     */
    public $module;

    /**
     * AdminTmOneClickOrderController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->module = new Tmoneclickorder();
    }

    /**
     *
     */
    public function ajaxProcessGetTemplateFieldForm()
    {
        $content = $this->module->renderTemplateFieldSettings();

        die(Tools::jsonEncode(
            array(
                'status' => true,
                'content' => $content
            )
        ));
    }

    /**
     * Save template filed settings
     */
    public function ajaxProcessSaveTemplateField()
    {
        if (!$field = $this->module->saveTemplateField()) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'errors' => $this->module->getErrors()
                )
            ));
        }

        $content = $this->module->renderTemplateField(get_object_vars($field));

        die(Tools::jsonEncode(
            array(
                'status' => true,
                'msg' => $this->l('Field saved'),
                'content' => $content
            )
        ));
    }

    /**
     * Delete template field
     */
    public function ajaxProcessDeleteTemplateField()
    {
        if (!$this->module->deleteTemplateField()) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'msg' => $this->l('Unable to remove field.')
                )
            ));
        }

        die(Tools::jsonEncode(
            array(
                'status' => true,
                'msg' => $this->l('Field removed')
            )
        ));
    }

    /**
     * Update template field
     */
    public function ajaxProcessUpdateTemplateFieldsPosition()
    {
        $items = Tools::getValue('fields');
        $total = count($items);
        $id_shop = (int)$this->context->shop->id;
        $success = true;

        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmoneclickorder_fields',
                array('sort_order' => $i),
                '`id_field` = '.preg_replace('/(template_field_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_shop` = '.$id_shop
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'error' => $this->l('Update Fail')
                )
            ));
        }
        die(Tools::jsonEncode(
            array(
                'status' => true,
                'success' => 'Update Success !',
            )
        ));
    }

    /**
     * Save module settings
     */
    public function ajaxProcessSaveModuleSettings()
    {

        if (!$this->module->updateSettings()) {
            die(Tools::jsonEncode(
                array(
                    'status' => 'false',
                    'message' => $this->l('Unable to save settings')
                )
            ));
        }

        die(Tools::jsonEncode(
            array(
                'status' => 'true',
                'message' => $this->l('Settings updated')
            )
        ));
    }

    /**
     * Get order form
     */
    public function ajaxProcessGetOrderForm()
    {
        $id_order = Tools::getValue('id_order');
        $status = Tools::getValue('status');
        $tmoneclickorder = new Tmoneclickorder();

        if (!$form = $tmoneclickorder->getOrderForm($id_order, $status)) {
            die(Tools::jsonEncode(array('status' => 'false')));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'content' => $form)));
    }

    /**
     * Get order form for remove
     */
    public function ajaxProcessGetRemoveOrderForm()
    {
        $tmoneclickorder = new Tmoneclickorder();
        $id_order = Tools::getValue('id_order');

        die(Tools::jsonEncode(array(
            'status' => true,
            'content' => $tmoneclickorder->renderRemoveOrderForm($id_order)
        )));
    }

    /**
     * Update order status
     */
    public function ajaxProcessUpdateOrderStatus()
    {
        $id_order = Tools::getValue('id_order');
        $id_employee = $this->context->employee->id;
        $description = Tools::getValue('description');
        $status = Tools::getValue('status');

        if ($status == 'removed' && empty($description)) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'errors' => $this->module->displayError($this->l('Description field is empty'))
                )
            ));
        }

        $params = array(
            'id_order' => $id_order,
            'id_employee' => $id_employee,
            'description' => $description
        );

        if (!$this->module->ordersStatusUpdate($params, $status)) {
            die(Tools::jsonEncode(
                array(
                    'status' => 'false',
                    'msg' => $this->l('Unable to update')
                )
            ));
        }

        $ordersSum = $this->module->checkNewOrders();

        die(Tools::jsonEncode(
            array(
                'status' => true,
                'sum' => $ordersSum,
                'msg' => $this->l('Preorder status updated')
            )
        ));
    }

    /**
     * Remove order
     */
    public function ajaxProcessRemoveOrder()
    {
        $id_order = Tools::getValue('id_order');
        $id_employee = $this->context->employee->id;
        $description = Tools::getValue('description');

        if (empty($description)) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'errors' => $this->module->displayError($this->l('Description field is empty'))
                )
            ));
        }

        $params = array(
            'id_order' => $id_order,
            'id_employee' => $id_employee,
            'description' => $description
        );

        if (!$this->module->ordersStatusUpdate($params, 'removed')) {
            die(Tools::jsonEncode(array('status' => 'false')));
        }

        $this->ajaxProcessCheckNewOrders();
    }

    /**
     * Check for new orders
     */
    public function ajaxProcessCheckNewOrders()
    {
        $ordersSum = $this->module->checkNewOrders();
        if ($ordersSum != 0) {
            die(Tools::jsonEncode(
                array(
                    'status' => true,
                    'sum' => $ordersSum,
                )
            ));
        }

        die(Tools::jsonEncode(array('status' => 'false')));
    }

    /**
     * Set shown new orders
     */
    public function ajaxProcessShownNewOrders()
    {
        $status = Tools::getValue('status');

        $newOrders = TMOneClickOrderOrders::selectAllFields($this->context->shop->id, $status, false);
        $ordersSum = count($newOrders);
        $this->module->ordersShownStatusUpdate($newOrders);
        if ($ordersSum != 0) {
            $content = $this->module->renderNewOrders($newOrders);
            die(Tools::jsonEncode(
                array(
                    'status' => 'true',
                    'content' => $content
                )
            ));
        }
        die(Tools::jsonEncode(array('status' => 'false')));
    }

    /**
     * Create customer account
     */
    public function ajaxProcessCreateCustomerAccount()
    {
        if (!(bool)Tools::getValue('random') && !$this->module->validateCustomerFields()) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'errors' => $this->module->getErrors()
                )
            ));
        }

        $oneclickorder = new Tmoneclickorder();
        $id_cart = Tools::getValue('id_cart');
        $cart = new Cart($id_cart);
        $customer = $oneclickorder->createCustomer($cart);
        $cart->id_customer = $customer->id;

        if (!$cart->update()) {
            die(Tools::jsonEncode(array('status' => false)));
        } else {

        }

        die(Tools::jsonEncode(array(
            'status' => true,
            'id_customer' => $customer->id
        )));
    }

    /**
     * Update customer of order
     */
    public function ajaxProcessUpdateOrderCustomer()
    {
        $oneclickorder = new Tmoneclickorder();
        $id_customer = Tools::getValue('id_customer');
        $id_order = Tools::getValue('id_order');

        $customer = $oneclickorder->updateOrderCustomer($id_customer, $id_order);

        die(Tools::jsonEncode(array(
            'status' => true,
            'success' => 'Customer successfully selected.',
            'content' => $oneclickorder->renderCustomerInfo($customer)
        )));
    }

    /**
     * Generate random password
     */
    public function ajaxProcessGenerateRandomPsw()
    {
        die(Tools::jsonEncode(array(
            'status' => 'true',
            'pswd' => Tools::passwdGen()
        )));
    }

    /**
     * Load tab
     */
    public function ajaxProcessLoadTab()
    {
        $oneclickorder = new Tmoneclickorder();

        die(Tools::jsonEncode(array(
            'status' => 'true',
            'content' => $oneclickorder->renderTabContent(Tools::getValue('tab_name'))
        )));
    }

    /**
     * Set customer of order
     */
    public function ajaxProcessSetCustomer()
    {
        $oneclickorder = new Tmoneclickorder();
        $id_customer = Tools::getValue('id_customer');
        $id_cart = Tools::getValue('id_cart');
        $cart = new Cart($id_cart);
        $cart->id_customer = $id_customer;

        $customer = new Customer($id_customer);

        if (!$cart->update()) {
            die(Tools::jsonEncode(array('status' => false)));
        }

        die(Tools::jsonEncode(array('status' => true, 'content' => $oneclickorder->renderCustomerInfo($customer))));
    }

    /**
     * Search for address states
     */
    public function ajaxProcessSearchStates()
    {
        $id_country = (int)Tools::getValue('id_country');
        $states = State::getStatesByIdCountry($id_country);

        if (count($states) == 0) {
            die(Tools::jsonEncode(array(
                'status' => false
            )));
        }

        die(Tools::jsonEncode(array(
            'status' => true,
            'states' => $states
        )));
    }

    /**
     * Create order
     */
    public function ajaxProcessCreateOrder()
    {
        $id_cart = Tools::getValue('id_cart');
        $module_name = Tools::getValue('payment_module_name');
        $id_order_state = Tools::getValue('id_order_state');
        $errors = array();

        $preorder = new TMOneClickOrderOrders(Tools::getValue('id_preorder'));

        if ($this->tabAccess['edit'] === '1') {
            if (!Configuration::get('PS_CATALOG_MODE')) {
                $payment_module = Module::getInstanceByName($module_name);
            } else {
                $payment_module = new BoOrder();
            }

            $cart = new Cart((int)$id_cart);
            Context::getContext()->currency = new Currency((int)$cart->id_currency);
            Context::getContext()->customer = new Customer((int)$cart->id_customer);

            $bad_delivery = false;
            if (($bad_delivery = (bool)!Address::isCountryActiveById((int)$cart->id_address_delivery))
                || !Address::isCountryActiveById((int)$cart->id_address_invoice)) {
                if ($bad_delivery) {
                    $errors[] = $this->l('This delivery address country is not active.');
                } else {
                    $errors[] = $this->l('This invoice address country is not active.');
                }
            } else {
                $employee = new Employee((int)Context::getContext()->cookie->id_employee);
                $payment_module->validateOrder((int)$cart->id, (int)$id_order_state, $cart->getOrderTotal(true, Cart::BOTH), $payment_module->displayName, $this->l('Manual order -- Employee:').' '.Tools::substr($employee->firstname, 0, 1).'. '.$employee->lastname, array(), null, false, $cart->secure_key);
                if ($payment_module->currentOrder) {
                    $preorder->id_original_order = $payment_module->currentOrder;
                }

                $preorder->status = 'created';
                $preorder->shown = 0;
                $preorder->id_employee = $this->context->employee->id_profile;

                $preorder->save();

                die(Tools::jsonEncode(
                    array(
                        'status' => true,
                        'msg' => $this->l('Order created!')
                    )
                ));
            }

            if (count($errors) > 0) {
                die(Tools::jsonEncode(
                    array(
                        'status'=> false,
                        'errors' => $this->module->displayError($errors)
                    )
                ));
            }
        } else {
            die(Tools::jsonEncode(array(
                'status' => false,
                'error' => Tools::displayError('You do not have permission to add this.')
            )));
        }
    }

    /**
     * Search order
     */
    public function ajaxProcessSearchOrders()
    {
        $word = Tools::getValue('word');
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
        $oneclickorder = new Tmoneclickorder();

        $orders = TMOneClickOrderSearch::search($word, $date_from, $date_to);

        die(Tools::jsonEncode(array(
            'status' => true,
            'content' => $oneclickorder->renderNewOrders($orders)
        )));

    }

    /**
     * Reload sub-tab
     */
    public function ajaxProcessReloadSubTab()
    {
        $sub_tab_name = Tools::getValue('status');
        $sub_tab = $this->module->sub_tabs[$sub_tab_name];

        die(Tools::jsonEncode(
            array(
                'status' => true,
                'content' => stripcslashes($this->module->renderSubTab($sub_tab))
            )
        ));
    }

    /**
     * Create address
     */
    public function ajaxProcessCreateAddress()
    {
        $address = new AdminAddressesController();
        $oneclickorder = new Tmoneclickorder();

        if (!$address->processSave()) {
            die(Tools::jsonEncode(array(
                'status' => false,
                'errors' => $oneclickorder->displayError($address->errors)
            )));
        }

        die(Tools::jsonEncode(array(
            'status' => true,
            'id_address' => (int)$address->object->id,
            'alias' => $address->object->alias,
            'message' => $this->l('Address added successfully.')
        )));

    }

    /**
     * Create preorder
     */
    public function ajaxProcessCreatePreorder()
    {
        $reload = Tools::getValue('reload');

        if (!$id_order = $this->module->createPreorder()) {
            die(Tools::jsonEncode(
                array(
                    'status' => false,
                    'msg' => $this->l('Unable to create preorder!')
                )
            ));
        }

        if (!(bool)$reload) {
            $preorder = TMOneClickOrderOrders::selectAllFields($this->context->shop->id, 'new', false);

            $content = $this->module->renderNewOrders($preorder);

            $this->module->ordersShownStatusUpdate($preorder);
        } else {
            $sub_tab = $this->module->sub_tabs['new'];

            $content = $this->module->renderSubTab($sub_tab);
        }


        die(Tools::jsonEncode(
            array(
                'status' => true,
                'content' => $content,
                'msg' => $this->l('Preorder successfully created!')
            )
        ));

    }
}
