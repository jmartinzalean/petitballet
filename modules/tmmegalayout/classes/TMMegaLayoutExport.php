<?php
/**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMMegalayoutExport
{
    /**
     * Generate layout settings and write it
     *
     * @param int $id_layout
     * @param string $path Export folder
     * @return string Layout name
     */
    protected function writeLayoutSettings($id_layout, $path)
    {
        //create or rewrite settings file
        $file = fopen($path . 'settings.json', 'w');
        $layout = new TMMegaLayoutLayouts($id_layout);
        $tmmegalayout = new Tmmegalayout();

        //generate array of layout settings
        $settings = array(
            'hook' => $layout->hook_name,
            'layout_name' => $layout->layout_name,
            'layout_file' => 'grid.json',
            'status' => $layout->status
        );

        if (!$pages = $layout->getAllLayoutPages()) {
            $pages = array();
        }

        $settings['pages'] = $pages;
        $settings['version'] = $tmmegalayout->version;
        //write settings in JSON format
        fwrite($file, Tools::jsonEncode($settings));
        fclose($file);

        return $layout->layout_name;
    }

    /**
     * Prepare layout for export, and copy custom styles for layout items
     *
     * @param array $map Genereted by Tmmegalayout::generateLayoutMap
     * @param string $path Module folder
     * @param int $level
     * @param array $positions
     * @return array or bool Layout array or False if $map is empty
     */
    protected function prepareLayoutGrid($map, $path, $level = null, $positions = array())
    {
        //get level if it's null
        if (is_null($level)) {
            $level = count($map) - 1;
            //if map empty, $level == -1
            if ($level < 0) {
                return false;
            }
        }

        $export_layout = array();

        //level
        foreach ($map[$level] as $id_parent => $layouts) {
            //layouts
            foreach ($layouts as $layout) {
                //check layout children
                if (isset($positions[$layout['id_item']])) {
                    $layout['child'] = $positions[$layout['id_item']];
                } else {
                    $layout['child'] = false;
                }

                $this->writeLayoutStyle($layout['id_unique'], $path);
                //check layout type
                switch ($layout['type']) {
                    case 'module':
                        $positions[$id_parent][] = array(
                            'type' => $layout['type'],
                            'sort_order' => $layout['sort_order'],
                            'specific_class' => $layout['specific_class'],
                            'module_name' => $layout['module_name'],
                            'id_unique' => $layout['id_unique'],
                            'origin_hook' => $layout['origin_hook'],
                            'child' => $layout['child']
                        );
                        break;
                    case 'wrapper':
                        $positions[$id_parent][] = array(
                            'type' => $layout['type'],
                            'sort_order' => $layout['sort_order'],
                            'specific_class' => $layout['specific_class'],
                            'id_unique' => $layout['id_unique'],
                            'child' => $layout['child']
                        );
                        break;
                    case 'row':
                        $positions[$id_parent][] = array(
                            'type' => $layout['type'],
                            'sort_order' => $layout['sort_order'],
                            'specific_class' => $layout['specific_class'],
                            'id_unique' => $layout['id_unique'],
                            'child' => $layout['child']
                        );
                        break;
                    case 'col':
                        $positions[$id_parent][] = array(
                            'type' => $layout['type'],
                            'sort_order' => $layout['sort_order'],
                            'specific_class' => $layout['specific_class'],
                            'col_xs' => $layout['col_xs'],
                            'col_sm' => $layout['col_sm'],
                            'col_md' => $layout['col_md'],
                            'col_lg' => $layout['col_lg'],
                            'id_unique' => $layout['id_unique'],
                            'child' => $layout['child']
                        );
                        break;
                    case 'block':
                        $positions[$id_parent][] = array(
                            'type' => $layout['type'],
                            'sort_order' => $layout['sort_order'],
                            'specific_class' => $layout['specific_class'],
                            'module_name' => $layout['module_name'],
                            'id_unique' => $layout['id_unique'],
                            'child' => $layout['child']
                        );
                        break;
                    default:
                        continue;
                }
            }
        }

        $level--;

        if ($level >= 0) {
            $export_layout = $this->prepareLayoutGrid($map, $path, $level, $positions);
        } else {
            $export_layout = $positions[0];
        }

        return $export_layout;
    }

    /**
     * Write 'grid.json' file
     *
     * @param int $id_layout
     * @param string $path Module folder
     */
    protected function writeLayoutGrid($id_layout, $path)
    {
        //write or rewrite grid file
        $file = fopen($path . 'grid.json', 'w');
        $map_obj = new Tmmegalayout();
        //get layouts from database
        $layout_array = $map_obj->getLayoutItems($id_layout);
        //generate map for layouts
        $map = $map_obj->generateLayoutMap($layout_array);
        //get layouts in export format
        $items = $this->prepareLayoutGrid($map, $path);
        //write layouts file in JSON format
        fwrite($file, Tools::jsonEncode($items));
        fclose($file);
    }

    /**
     * Write layout styles for export
     *
     * @param type $id_unique Style id
     * @param type $path Module folder
     * @return boolean
     */
    protected function writeLayoutStyle($id_unique, $path)
    {
        $obj = new Tmmegalayout();
        if ($obj->checkUniqueStylesExists($id_unique)) {
            if (!file_exists($path . 'styles')) {
                mkdir($path . 'styles', 0777);
            }

            if ($background_url = $obj->getItemImageUrl($id_unique)) {
                $background_image = explode('url(../../../../../img/', str_replace(')', '', $background_url));
                $this->writeLayoutImage($path, $background_image[1]);
            }

            copy($obj->style_path . $id_unique . '.css', $path . 'styles/' . $id_unique . '.css');
        }

        return true;
    }

    /**
     * Write layout js and css files
     *
     * @param string $name
     * @param string $path
     * @return bool
     */
    protected function writeLayoutFiles($name, $path)
    {
        $tmmegalayout = new Tmmegalayout();
        if (!file_exists($path . 'files')) {
            mkdir($path . 'files', 0777);
        }
        $result = true;
        $result &= copy($tmmegalayout->css_layouts_path . $name . '.css', $path . 'files/' . $name . '.css');
        $result &= copy($tmmegalayout->js_layouts_path . $name . '.js', $path . 'files/' . $name . '.js');

        return $result;
    }

    /**
     * @param $path
     * @param $image_path
     *
     * @return bool
     */
    protected function writeLayoutImage($path, $image_path)
    {
        if (!file_exists(_PS_IMG_DIR_ . $image_path)) {
            return false;
        }

        if (!file_exists($path . 'images')) {
            mkdir($path . 'images', 0777);
        }

        $this->createPath($path . 'images/', $image_path);
        copy(_PS_IMG_DIR_ . $image_path, $path . 'images/' . $image_path);

        return true;
    }

    protected function createPath($parent_path, $create_path)
    {
        $folders = explode('/', $create_path);
        $new_path = $parent_path;

        foreach ($folders as $folder) {
            if (strstr($folder, '.')) {
                continue;
            }

            if (!file_exists($new_path . $folder)) {
                mkdir($new_path . $folder, 0777);
            }

            $new_path .= $folder . '/';
        }
    }

    protected function archiveFolders($path, $ZipArchiveObj, $zip_path = '')
    {
        $files = scandir($path);

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path . $file)) {
                    $ZipArchiveObj->addEmptyDir($zip_path . $file);
                    $new_zip_path = $zip_path . $file . '/';
                    $new_path = $path . $file . '/';
                    $this->archiveFolders($new_path, $ZipArchiveObj, $new_zip_path);
                } else {
                    $ZipArchiveObj->addFile($path . $file, $zip_path . $file);
                }
            }
        }
    }

    /**
     * Make export archive
     *
     * @param string $path
     * @param string $web_path
     * @param string $file_name
     * @return string path to export archive
     */
    protected function writeLayoutZip($path, $temp_path, $web_path, $file_name)
    {
        $file_name = $file_name . '.zip';

        if (file_exists($path . $file_name)) {
            unlink($path . $file_name);
        }

        $zip = new ZipArchive();

        if ($zip->open($path . $file_name, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
            $this->errors = $this->displayError(sprintf($this->l('cannot open %s'), $file_name));
        }

        $this->archiveFolders($temp_path, $zip);

        $zip->close();

        return $web_path . $file_name;
    }

    /**
     * Generate archive to export by $id_layout
     *
     * @param int $id_layout
     * @return string Path to export archive
     */
    public function init($id_layout)
    {
        $obj = new Tmmegalayout();
        $local_path = $obj->getLocalPath() . 'export/';
        Tmmegalayout::cleanFolder($local_path);
        $web_path = $obj->getWebPath() . 'export/';
        $id_shop = $obj->getIdShop();
        $temp_path = TMMegaLayoutImport::checkTempFolder($local_path);

        $layout_name = $this->writeLayoutSettings($id_layout, $temp_path);
        $this->writeLayoutGrid($id_layout, $temp_path, $id_shop);
        $this->writeLayoutFiles($layout_name, $temp_path);
        $export_zip = $this->writeLayoutZip($local_path, $temp_path, $web_path, $layout_name);
        Tmmegalayout::cleanFolder($temp_path);
        return $export_zip;
    }
}
