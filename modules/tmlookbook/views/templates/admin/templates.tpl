{**
* 2002-2016 TemplateMonster
*
* TM Look Book
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
*}
{foreach from=$templates item=template}
  <a href="#" class="thumbnail template" data-template="{$template.name|escape:'htmlall':'UTF-8'}">
    <img src="{$template.img|escape:'htmlall':'UTF-8'}" alt="{$template.name|escape:'htmlall':'UTF-8'}">
    <div class="caption">
      <p class="text-center">{$template.name|escape:'htmlall':'UTF-8'}</p>
    </div>
  </a>
{/foreach}