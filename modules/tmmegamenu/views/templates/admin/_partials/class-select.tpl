{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="form-group">
  <label>{l s='Set column width.' mod='tmmegamenu'}</label>
  <select class="form-control" name="col-item-type" autocomplete="off">
    {for $foo=2 to 12}
      <option {if $foo == $selected}selected="selected"{/if} value="{$foo}">col-{$foo}</option>
    {/for}
  </select>
</div>