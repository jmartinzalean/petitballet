{*
* 2002-2016 TemplateMonster
*
* TM Homepage Products Carousel
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

{if $carousel_status}
  {addJsDef carousel_status=$carousel_status|intval}
  {addJsDef carousel_item_nb=$carousel_item_nb|intval}
  {addJsDef carousel_item_width=$carousel_item_width|intval}
  {addJsDef carousel_item_margin=$carousel_item_margin|intval}
  {addJsDef carousel_auto=$carousel_auto|intval}
  {addJsDef carousel_item_scroll=$carousel_item_scroll|intval}
  {addJsDef carousel_speed=$carousel_speed|intval}
  {addJsDef carousel_auto_pause=$carousel_auto_pause|intval}
  {addJsDef carousel_random=$carousel_random|intval}
  {addJsDef carousel_loop=$carousel_loop|intval}
  {addJsDef carousel_hide_control=$carousel_hide_control|intval}
  {addJsDef carousel_pager=$carousel_pager|intval}
  {addJsDef carousel_control=$carousel_control|intval}
  {addJsDef carousel_auto_control=$carousel_auto_control|intval}
  {addJsDef carousel_auto_hover=$carousel_auto_hover|intval}
{/if}