{*
* 2002-2017 TemplateMonster
*
* TM Slider
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
* @author     TemplateMonster
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{extends file="helpers/list/list_content.tpl"}
{block name="td_content"}
  {if $params.type == 'image_field'}
    {if $tr.type == 'image'}
      {if $tr.multi_img && $tr.img_url}
        <img style="max-height:100px;" src="{$params.img_path|escape:'html':'UTF-8'}{$tr.img_url|escape:'html':'UTF-8'}" alt="{$tr.name|escape:'html':'UTF-8'}" />
      {elseif !$tr.multi_img && $tr.single_img}
        <img style="max-height:100px;" src="{$params.img_path|escape:'html':'UTF-8'}{$tr.single_img|escape:'html':'UTF-8'}" alt="{$tr.name|escape:'html':'UTF-8'}" />
      {else}
        {l s='Slide without background content' mod='tmslider'}
      {/if}
    {elseif $tr.type == 'video'}
      {if $tr.multi_video && $tr.video_url}
        <iframe id="ytplayer" type="text/html" width="100"
              src="http://www.youtube.com/embed/{$tr.video_url|escape:'html':'UTF-8'}?autoplay=0"
              frameborder="0"></iframe>
      {elseif !$tr.multi_video && $tr.single_video}
        <iframe id="ytplayer" type="text/html" width="226" width="100"
                src="http://www.youtube.com/embed/{$tr.single_video|escape:'html':'UTF-8'}?autoplay=0"
                frameborder="0"></iframe>
      {else}
        {l s='Slide without background content' mod='tmslider'}
      {/if}
    {else}
      {l s='Slide without background content' mod='tmslider'}
    {/if}
  {/if}
  {$smarty.block.parent}
{/block}
{block name="default_field"}
  {if $params.type == 'image_field'}{else}
    --
  {/if}
{/block}