{*
* 2002-2016 TemplateMonster
*
* TM Product Videos
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($videos) && $videos}
  <li class="product-video-tab">
    <a href="#product-video-tab-content" data-toggle="tab">{if count($videos) > 1}{l s='Videos' mod='tmproductvideos'}{else}{l s='Video' mod='tmproductvideos'}{/if}</a>
  </li>
{/if}