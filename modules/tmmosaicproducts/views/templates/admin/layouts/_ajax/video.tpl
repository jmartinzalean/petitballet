{*
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @author    TemplateMonster
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="v-{$data.id_video|escape:'html':'UTF-8'}" class="tmmp-window-box tmmp-video video-{$data.id_video|escape:'html':'UTF-8'}">
  <div class="titler-row">
    <div class="tmmp-id">{$data.id_video|escape:'html':'UTF-8'}</div>
    <div class="tmmp-title">{l s='Title' mod='tmmosaicproducts'}</div>
    <div class="video-title">{$data.title|escape:'html':'UTF-8'}</div>
  </div>
</div>