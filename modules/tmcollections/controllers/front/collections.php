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

class TmCollectionsCollectionsModuleFrontController extends ModuleFrontController
{
    public $products = '';

    public function initContent()
    {
        parent::initContent();
        $action = Tools::getValue('action');

        if (!Tools::isSubmit('myajax')) {
            $this->assign();
        } elseif (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action))) {
            $this->{'ajaxProcess' . Tools::toCamelCase($action)}();
        } else {
            die(Tools::jsonEncode(array('error' => 'method doesn\'t exist')));
        }
    }

    public function assign()
    {
        $this->errors = array();
        $context = Context::getContext();

        if ($this->context->customer->isLogged()) {
            if (Tools::isSubmit('submitCollections')) {
                $name = Tools::getValue('name');

                if (empty($name)) {
                    $this->errors = Tools::displayError($this->module->l('You must specify a name.'));
                } elseif (ClassTmCollections::isExistsByNameForUser($name)) {
                    $this->errors = $this->module->l('This name is already used by another list.');
                }

                if (!count($this->errors)) {
                    $collections = new ClassTmCollections();
                    $collections->id_shop = $this->context->shop->id;
                    $collections->name = $name;
                    $collections->id_customer = (int)$this->context->customer->id;
                    $collections->token = Tools::strtoupper(Tools::substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$this->context->customer->id), 0, 16));
                    $collections->add();
                    $confirmation_add = true;
                    $confirmation_name = $name;
                }
            }
            if (Tools::isSubmit('changeCollection')) {
                $name = Tools::getValue('name');

                if (empty($name)) {
                    $this->errors = Tools::displayError($this->module->l('You must specify a name.'));
                }

                $id_collection = Tools::getValue('id_collection');

                if (!count($this->errors)) {
                    $collections = new ClassTmCollections($id_collection);
                    $collections->name = $name;

                    if (!$collections->update()) {
                        $this->errors = Tools::displayError($this->module->l('This name no change'));
                    } else {
                        $confirmation_change = true;
                    }
                }

            }

            if (isset($confirmation_change)) {
                $this->context->smarty->assign('confirmation_change', $confirmation_change);
            }

            if (isset($confirmation_add)) {
                $this->context->smarty->assign('confirmation_add', $confirmation_add);
                $this->context->smarty->assign('confirmation_name', $confirmation_name);
            }

            $this->context->smarty->assign('collections', ClassTmCollections::getByIdCustomer($this->context->customer->id));



            $context->smarty->assign(array(
                'img_path' => _PS_BASE_URL_ . __PS_BASE_URI__ . 'modules/tmcollections/views/tmp/',
                'id_lang' => $context->language->id,
                'tm_collection_app_id' => Configuration::get('TM_COLLECTION_APP_ID')
            ));

        } else {
            Tools::redirect('index.php?controller=authentication&back=' . urlencode($this->context->link->getModuleLink('tmcollections', 'mycollections')));
        }

        $this->context->smarty->assign(array(
            'id_customer' => (int)$this->context->customer->id,
        ));

        $this->setTemplate('collections.tpl');
    }

    /**
     * Delete collection
     * @return array
     */
    public function ajaxProcessDeleteList()
    {
        if (!$this->context->customer->isLogged()) {
            die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('You aren\'t logged in'))));
        }

        $id_collection = Tools::getValue('id_collection');
        $collection = new ClassTmCollections((int)$id_collection);

        if (Validate::isLoadedObject($collection) && $collection->id_customer == $this->context->customer->id) {
            $collection->delete();
        } else {
            die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('Cannot delete this collection'))));
        }

        die(Tools::jsonEncode(array('success' => true)));
    }

    /**
     * Edit collection name
     * @return array
     */
    public function ajaxProcessEditList()
    {
        if (!$this->context->customer->isLogged()) {
            die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('You aren\'t logged in'))));
        }

        $id_collection = Tools::getValue('id_collection');
        $collection = new ClassTmCollections((int)($id_collection));
        $name_collection = $collection->name;

        die(Tools::jsonEncode(array('success' => true, 'name_collection' => $name_collection, 'id_collection' => $id_collection)));
    }

    /**
     * Add product to collection
     * @return array
     */
    public function ajaxProcessAddProduct()
    {
        $context = Context::getContext();
        $action_add = Tools::getValue('action_add');
        $add = (!strcmp($action_add, 'action_add') ? 1 : 0);
        $id_collection = (int)Tools::getValue('id_collection');
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $quantity = (int)Tools::getValue('quantity');

        if (!$this->context->customer->isLogged()) {
            die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('You aren\'t logged in'))));
        }

        if ($id_collection && ClassTmCollections::exists($id_collection, $context->customer->id) === true) {
            $context->cookie->id_collection = (int)$id_collection;
        }

        if ((int)$context->cookie->id_collection > 0 && !ClassTmCollections::exists($context->cookie->id_collection, $context->customer->id)) {
            $context->cookie->id_collection = '';
        }

        if (empty($context->cookie->id_collection) === true || $context->cookie->id_collection == false) {
            $context->smarty->assign('error', true);
        }

        if (!isset($context->cookie->id_collection) || $context->cookie->id_collection == '') {
            $collections = new ClassTmCollections();
            $collections->id_shop = $context->shop->id;
            $mod_collections = new Tmcollections();
            $collections->name = $mod_collections->default_collection_name;
            $collections->id_customer = (int)$context->customer->id;
            list($us, $s) = explode(' ', microtime());
            srand($s * $us);
            $collections->token = Tools::strtoupper(Tools::substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$context->customer->id), 0, 16));
            $collections->add();
            $context->cookie->id_collection = (int)$collections->id;
        }

        if ($add && $quantity) {
            ClassTmCollections::addProduct($context->cookie->id_collection, $this->context->customer->id, $id_product, $id_product_attribute, $quantity);
        } else {
            die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('Cannot add this product'))));
        }

        die(Tools::jsonEncode(array('success' => true)));
    }

    /**
     * Delete product with collection
     * @return array
     */
    public function ajaxProcessDeleteProduct()
    {
        $context = Context::getContext();

        if ($context->customer->isLogged()) {
            $action = Tools::getValue('action');
            $id_collection = (int)Tools::getValue('id_collection');
            $id_product = (int)Tools::getValue('id_product');
            $id_product_attribute = (int)Tools::getValue('id_product_attribute');

            if (!strcmp($action, 'deleteproduct')) {
                ClassTmCollections::removeProduct($id_collection, $id_product, $id_product_attribute);
            } else {
                die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('Cannot delete this product'))));
            }
        }

        die(Tools::jsonEncode(array('success' => true)));
    }

    /**
     * Get product by id
     * @return array
     */
    public function ajaxProcessGetProductsById()
    {
        $context = Context::getContext();
        $id_collection = (int)Tools::getValue('id_collection');
        $products = ClassTmCollections::getProductByIdCollection($id_collection);
        $tmcollections = new Tmcollections;

        foreach ($products as $k => $pr) {
            $product= new Product((int)($pr['id_product']), false, $context->language->id);
            if ($pr['id_product_attribute'] != 0) {
                $img_combination = $product->getCombinationImages($context->language->id);
                if (isset($img_combination[$pr['id_product_attribute']][0])) {
                    $products[$k]['cover'] = $product->id.'-'.$img_combination[$pr['id_product_attribute']][0]['id_image'];
                } else {
                    $cover = Product::getCover($product->id);
                    $products[$k]['cover'] = $product->id.'-'.$cover['id_image'];
                }
            } else {
                $images = $product->getImages($context->language->id);
                foreach ($images as $image) {
                    if ($image['cover']) {
                        $products[$k]['cover'] = $product->id.'-'.$image['id_image'];
                        break;
                    }
                }
            }
            if (!isset($products[$k]['cover'])) {
                $products[$k]['cover'] = $context->language->iso_code.'-default';
            }
        }

        $this->products .= $tmcollections->getAjaxHtml('product', $context->smarty->assign(array('products' => $products)));

        die(Tools::jsonEncode(array('response' => $this->products)));
    }

    /**
     * Image path for create collection image
     */
    public static function imagesPath()
    {
        return dirname(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/..');
    }

    /**
     * Get id for number item
     * @return array
     */
    public function getID($min, $max)
    {
        $numbers = range($min, $max);
        return array_slice($numbers, 0);
    }

    /**
     * Cut string
     * @return result string
     */
    public function mbCutString($str, $length, $postfix = '...', $encoding = 'UTF-8')
    {
        if (mb_strlen($str, $encoding) <= $length) {
            return $str;
        }

        $tmp = mb_substr($str, 0, $length, $encoding);
        return mb_substr($tmp, 0, mb_strripos($tmp, ' ', 0, $encoding), $encoding) . $postfix;
    }

    /**
     * Get image by id collection
     * @return array
     */
    public function ajaxProcessGetImageById()
    {
        $id_collection = (int)Tools::getValue('id_collection');
        $name_collection = Tools::getValue('name_collection');
        $id_layout = (int)Tools::getValue('id_layout');
        $id_product = Tools::jsonDecode(Tools::getValue('id_product'));
        $products = array();
        $attributes = array();
        $prod = array();
        $attr = array();

        foreach ($id_product as $key => $value) {
            $attributes[$key]['id_product_attribute'] = explode("_", $value, 2);
            $products[$key]['id_product'] = explode("_", $value, 2);
            foreach ($products as $k => $product) {
                $prod[$k]["id_product"] = $product["id_product"][0];
            }
            foreach ($attributes as $t => $attribute) {
                $attr[$t] = $attribute["id_product_attribute"][1];
            }
        }

        $image_path = array();
        $name = array();
        $products = ClassTmCollections::getProductByIdCollection($id_collection);

        foreach ($products as $k => $product) {
            foreach ($prod as $p) {
                if ($product['id_product'] == $p['id_product']) {
                    $name[$k] = $product['name'];
                    $id_image = Product::getCover($product['id_product']);
                    if (sizeof($id_image) > 0) {
                        $image = new Image($id_image['id_image']);
                        $image_path[$k] = _PS_IMG_DIR_.'p/'.$image->getExistingImgPath().'.jpg';
                    }
                }
            }
        }
        
        ImageManager::resize(_PS_IMG_DIR_.Configuration::get('PS_LOGO'), _PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg', 140, 60);
        if (Configuration::get('PS_IMAGE_QUALITY') == 'jpg') {
            $logo = imagecreatefromjpeg(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
        } else {
            $logo = imagecreatefrompng(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
        }

        $dest = imagecreatetruecolor(487, 255);
        imagefill($dest, 0, 0, 0xFFFFFF);
        $color_black = imagecolorallocate($dest, 27, 27, 27);
        $color_grey_text = imagecolorallocate($dest, 100, 100, 100);
        $font_file_regular = _PS_MODULE_DIR_ . 'tmcollections/views/fonts/OpenSans-Semibold.ttf';
        $font_file_semibold = _PS_MODULE_DIR_ . 'tmcollections/views/fonts/OpenSans-Semibold.ttf';
        $name_product = array_values($name);
        $product_img_path = array_values($image_path);
        $product_img = array();

        if ($id_layout == 1) {
            if (file_exists(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg')) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg');
            }
            ImageManager::resize(implode($image_path), _PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_1.jpg', 153, 208);
            if (Configuration::get('PS_IMAGE_QUALITY') == 'jpg') {
                $src = imagecreatefromjpeg(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_1.jpg');
            } else {
                $src = imagecreatefrompng(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_1.jpg');
            }
            $border_color = imagecolorallocate($src, 216, 216, 216);
            imageline($src, 0, 0, 0, imagesy($src), $border_color);
            imageline($src, 0, 0, imagesx($src), 0, $border_color);
            imageline($src, imagesx($src)-1, 0, imagesx($src)-1, imagesy($src)-1, $border_color);
            imageline($src, 0, imagesy($src)-1, imagesx($src)-1, imagesy($src)-1, $border_color);
            imagecopymerge($dest, $src, 20, 20, 0, 0, imagesx($src), imagesy($src), 100);
            imagecopy($dest, $logo, 250, 50, 0, 0, 140, 60);
            imagefttext($dest, 16, 0, 250, 150, $color_black, $font_file_semibold, $this->mbCutString($name_collection, 20));
            imagefttext($dest, 12, 0, 250, 180, $color_grey_text, $font_file_regular, $this->mbCutString(implode($name), 30));
            imagejpeg($dest, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/'.$id_collection.'-collection.jpg');
            imagedestroy($dest);
            unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_1.jpg');
            unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
        } elseif ($id_layout == 2) {
            $offset_img_width = 20;
            $offset_img_height = 20;
            if (file_exists(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg')) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg');
            }
            foreach ($product_img_path as $key => $image) {
                if ($key == 0) {
                    ImageManager::resize($image, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg', 153, 208);
                } elseif ($key == 1) {
                    ImageManager::resize($image, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg', 101, 136);
                }
            }

            $dir_files = Tools::scandir($this->imagesPath(), 'jpg');

            foreach ($dir_files as $key => $result) {
                if ($result != 'logo.jpg') {
                    $product_img[] = _PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $result;
                }
            }

            $ids_img = $this->getID(0, count($product_img));

            for ($i = 0; $i < count($product_img); $i++) {
                $index = $ids_img[$i];
                if (Configuration::get('PS_IMAGE_QUALITY') == 'jpg') {
                    $src = imagecreatefromjpeg($product_img[$index]);
                } else {
                    $src = imagecreatefrompng($product_img[$index]);
                }
                $color_grey = imagecolorallocate($src, 216, 216, 216);
                imageline($src, 0, 0, 0, imagesy($src), $color_grey);
                imageline($src, 0, 0, imagesx($src), 0, $color_grey);
                imageline($src, imagesx($src)-1, 0, imagesx($src)-1, imagesy($src)-1, $color_grey);
                imageline($src, 0, imagesy($src)-1, imagesx($src)-1, imagesy($src)-1, $color_grey);
                imagecopymerge($dest, $src, $offset_img_width, $offset_img_height, 0, 0, imagesx($src), imagesy($src), 100);
                $offset_img_width = $offset_img_width + imagesx($src) + 20;
                $offset_img_height = $offset_img_height + 72;
            }

            foreach ($product_img as $key => $image) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg');
            }

            $ids = $this->getID(0, count($name_product));
            $offset = 170;

            for ($i = 0; $i < count($name_product); $i++) {
                $index = $ids[$i];
                $src = $name_product[$index];
                imagefttext($dest, 10, 0, 320, $offset, $color_grey_text, $font_file_regular, $this->mbCutString($src, 25));
                $offset = $offset  + 20;
            }

            imagefttext($dest, 16, 0, 320, 140, $color_black, $font_file_semibold, $this->mbCutString($name_collection, 15));
            imagecopy($dest, $logo, 320, 20, 0, 0, 140, 60);
            imagejpeg($dest, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/'.$id_collection.'-collection.jpg');
            imagedestroy($dest);
            unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
        } elseif ($id_layout == 3) {
            if (file_exists(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg')) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg');
            }
            $offset_img_width = 20;

            foreach ($product_img_path as $key => $image) {
                ImageManager::resize($image, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg', 97, 136);
            }

            $dir_files = Tools::scandir($this->imagesPath(), 'jpg');

            foreach ($dir_files as $key => $result) {
                if ($result != 'logo.jpg') {
                    $product_img[] = _PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $result;
                }
            }

            $ids_img = $this->getID(0, count($product_img));

            for ($i = 0; $i < count($product_img); $i++) {
                $index = $ids_img[$i];
                if (Configuration::get('PS_IMAGE_QUALITY') == 'jpg') {
                    $src = imagecreatefromjpeg($product_img[$index]);
                } else {
                    $src = imagecreatefrompng($product_img[$index]);
                }
                $color_grey = imagecolorallocate($src, 216, 216, 216);
                imageline($src, 0, 0, 0, imagesy($src), $color_grey);
                imageline($src, 0, 0, imagesx($src), 0, $color_grey);
                imageline($src, imagesx($src)-1, 0, imagesx($src)-1, imagesy($src)-1, $color_grey);
                imageline($src, 0, imagesy($src)-1, imagesx($src)-1, imagesy($src)-1, $color_grey);
                imagecopymerge($dest, $src, $offset_img_width, 92, 0, 0, imagesx($src), imagesy($src), 100);
                $offset_img_width = $offset_img_width + imagesx($src) + 20;
            }

            foreach ($product_img as $key => $image) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg');
            }

            imagecopy($dest, $logo, 325, 15, 0, 0, 140, 60);
            unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
            imagefttext($dest, 16, 0, 20, 55, $color_black, $font_file_semibold, $name_collection);
            imagejpeg($dest, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/'.$id_collection.'-collection.jpg');
            imagedestroy($dest);
        } elseif ($id_layout == 4) {
            if (file_exists(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg')) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $id_collection . '-collection.jpg');
            }
            $name_product = array_values($name);
            $product_img_path = array_values($image_path);

            foreach ($product_img_path as $key => $image) {
                ImageManager::resize($image, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg', 75, 95);
            }

            $dir_files = Tools::scandir($this->imagesPath(), 'jpg');

            foreach ($dir_files as $key => $result) {
                if ($result != 'logo.jpg') {
                    $product_img[] = _PS_MODULE_DIR_ . 'tmcollections/views/tmp/' . $result;
                }
            }

            $ids_img = $this->getID(0, count($product_img));
            $offset_img_width = 295;
            $offset_img_height = 20;

            for ($i = 0; $i < count($product_img); $i++) {
                $index = $ids_img[$i];
                if (Configuration::get('PS_IMAGE_QUALITY') == 'jpg') {
                    $src = imagecreatefromjpeg($product_img[$index]);
                } else {
                    $src = imagecreatefrompng($product_img[$index]);
                }
                $colorGrey = imagecolorallocate($src, 216, 216, 216);
                imageline($src, 0, 0, 0, imagesy($src), $colorGrey);
                imageline($src, 0, 0, imagesx($src), 0, $colorGrey);
                imageline($src, imagesx($src)-1, 0, imagesx($src)-1, imagesy($src)-1, $colorGrey);
                imageline($src, 0, imagesy($src)-1, imagesx($src)-1, imagesy($src)-1, $colorGrey);
                imagecopymerge($dest, $src, $offset_img_width, $offset_img_height, 0, 0, imagesx($src), imagesy($src), 100);
                $offset_img_width = $offset_img_width + imagesx($src) + 20;
                if ($i == 1) {
                    $offset_img_width = $offset_img_width - 285;
                    $offset_img_height = $offset_img_height + imagesy($src) + 20;
                }
            }

            foreach ($product_img as $key => $image) {
                unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/image_'.$key.'.jpg');
            }

            $ids = $this->getID(0, count($name_product));
            $offset = 70;

            for ($i = 0; $i < count($name_product); $i++) {
                $index = $ids[$i];
                $src = $name_product[$index];
                imagefttext($dest, 10, 0, 20, $offset, $color_grey_text, $font_file_regular, $this->mbCutString($src, 30));
                $offset = $offset  + 20;
            }

            imagefttext($dest, 16, 0, 20, 40, $color_black, $font_file_semibold, $name_collection);
            imagecopy($dest, $logo, 20, 170, 0, 0, 140, 60);
            imagejpeg($dest, _PS_MODULE_DIR_ . 'tmcollections/views/tmp/'.$id_collection.'-collection.jpg');
            imagedestroy($dest);
            unlink(_PS_MODULE_DIR_ . 'tmcollections/views/tmp/logo.jpg');
        }

        die(Tools::jsonEncode(array('status' => 'true')));
    }
}
