{**
* 2002-2016 TemplateMonster
*
* TM Media Parallax
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

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'filemanager_image'}
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" id="{$input.name|escape:'htmlall':'UTF-8'}" class="form-control" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{if isset($fields_value[$input.name])}{$base_url|escape:'htmlall':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" readonly/>
                    <span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id={$input.name|escape:'htmlall':'UTF-8'}" data-input-name="{$input.name|escape:'htmlall':'UTF-8'}" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                </div>
                <img class="img-responsive img-thumbnail layout_image box-indent-1 hidden" src="{if isset($fields_value[$input.name])}{$base_url|escape:'htmlall':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}">
            </div>
    {elseif $input.type == 'filemanager_video'}
        <div class="col-lg-6">
            <div class="input-group">
                <input type="text" id="{$input.name|escape:'htmlall':'UTF-8'}" class="form-control" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{if isset($fields_value[$input.name]) && $fields_value[$input.name] != ''}{$base_url|escape:'htmlall':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" readonly/>
                <span class="input-group-addon"><a href="filemanager/dialog.php?type=3&field_id={$input.name|escape:'htmlall':'UTF-8'}" data-input-name="{$input.name|escape:'htmlall':'UTF-8'}" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
            </div>
            <video class="img-responsive img-thumbnail hidden {$input.name|escape:'htmlall':'UTF-8'} layout_video box-indent-1" controls>
                <source src="{if isset($fields_value[$input.name])}{$base_url|escape:'htmlall':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
