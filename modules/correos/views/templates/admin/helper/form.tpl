{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
{assign var='is_depend' value=false}
{if isset($depend)}
    {assign var=is_depend value=$depend}
{/if}
{if $option.type eq 'select'}
    <div class="">
        <select autocomplete="off" class="form-control" id="{$option.name|escape:'htmlall':'UTF-8'}"
                name="{$option.name|escape:'htmlall':'UTF-8'}{if isset($option.multiple) and $option.multiple}[]{/if}"
                {if isset($option.multiple) and $option.multiple}multiple{/if}>
            {foreach from=$option.data key="key" item="item"}
                {if isset($option.option_value)}
                    {assign var='value' value=$item[$option.option_value]}
                {elseif isset($option.reverse_option) and $option.reverse_option}
                    {assign var='value' value=$item}
                {else}
                    {assign var='value' value=$key}
                {/if}
                {if isset($option.option_text)}
                    {assign var='text' value=$item[$option.option_text]}
                {elseif (isset($option.reverse_option) and $option.reverse_option) or (isset($option.key_as_value) and $option.key_as_value)}
                    {assign var='text' value=$key}
                {else}
                    {assign var='text' value=$item}
                {/if}
                {if isset($option.condition) and is_array($option.condition) and count($option.condition)}
                    {if $option.condition.operator eq 'neq'}
                        {if $option.condition.compare neq $item[$option.condition.value]}
                           <option value="{$value|escape:'htmlall':'UTF-8'}" {if isset($option.default_option) and $option.default_option eq $value}selected="true"{/if}>{$text|escape:'htmlall':'UTF-8'}</option>
                        {/if}
                    {elseif $option.condition.operator eq 'eq'}
                        {if $option.condition.compare eq $item[$option.condition.value]}
                            <option value="{$value|escape:'htmlall':'UTF-8'}" {if isset($option.default_option) and $option.default_option eq $value}selected="true"{/if}>{$text|escape:'htmlall':'UTF-8'}</option>
                        {/if}
                    {/if}
                {else}
                    <option value="{$value|escape:'htmlall':'UTF-8'}" {if isset($option.default_option) and $option.default_option eq $value}selected="true"{/if}>{$text|escape:'htmlall':'UTF-8'}</option>
                {/if}
            {/foreach}
        </select>
    </div>
{elseif $option.type eq 'hidden'}
    <input type="hidden" {if isset($option.name)}name="{$option.name|escape:'htmlall':'UTF-8'}"{/if} {if isset($option.id)}id="{$option.id|escape:'htmlall':'UTF-8'}"{/if}
           {if isset($option.value)}value="{$option.value|escape:'htmlall':'UTF-8'}"{/if}>
{elseif $option.type eq 'switch'}
       <span class="switch prestashop-switch fixed-width-lg">
       {foreach from=$option.data key="key" item="item"}
         <input id="{$option.name|escape:'htmlall':'UTF-8'}_{$key|escape:'htmlall':'UTF-8'}" type="radio" {if $option.default_option == $key}checked="checked"{/if} value="{$key|escape:'htmlall':'UTF-8'}" 
         name="{$option.name|escape:'htmlall':'UTF-8'}">
         <label for="{$option.name|escape:'htmlall':'UTF-8'}_{$key|escape:'htmlall':'UTF-8'}">{$item|escape:'htmlall':'UTF-8'}</label>
         {/foreach}
         <a class="slide-button btn"></a>
      </span>

{elseif $option.type eq 'textbox' and isset($option.color) and $option.color}
    <div class="">
        <div class="input-group color-picker">
            <input autocomplete="off" type="text" class="form-control" name="{$option.name|escape:'htmlall':'UTF-8'}"
                   id="{$option.name|escape:'htmlall':'UTF-8'}"
            {if isset($option.placeholder)}{$option.placeholder|escape:'htmlall':'UTF-8'}{/if}
            {if isset($option.value)}value="{$option.value|escape:'htmlall':'UTF-8'}"{/if}>
            <span class="input-group-addon"><i></i></span>
        </div>
    </div>
{elseif $option.type eq 'textbox'}
    
        {if isset($option.multilang) and $option.multilang}
            {assign var='input_value' value=[]}
            {if isset($option.input_value)}
                {assign var='input_value' value=$option.input_value}
            {/if}
        
        {else}
            <input autocomplete="off" type="text" class="form-control"
                   name="{$option.name|escape:'htmlall':'UTF-8'}" id="{$option.name|escape:'htmlall':'UTF-8'}"
            {if isset($option.placeholder)}{$option.placeholder|escape:'htmlall':'UTF-8'}{/if}
            {if isset($option.value)}value="{$option.value|escape:'htmlall':'UTF-8'}"{/if}>
        {/if}
 {elseif $option.type eq 'file'}
    
        {if isset($option.multilang) and $option.multilang}
            {assign var='input_value' value=[]}
            {if isset($option.input_value)}
                {assign var='input_value' value=$option.input_value}
            {/if}
        
        {else}
         <input id="user_basic_file" class="file optional" id="{$option.name|escape:'htmlall':'UTF-8'}" type="file" name="{$option.name|escape:'htmlall':'UTF-8'}">
         
           
        {/if}
{elseif $option.type eq 'textarea'}
    <div class="">
        {if isset($option.multilang) and $option.multilang}
            {assign var='input_value' value=[]}
            {if isset($option.input_value)}
                {assign var='input_value' value=$option.input_value}
            {/if}
           
        {else}
            <textarea autocomplete="off" class="form-control" id="{$option.name|escape:'htmlall':'UTF-8'}"
                {if isset($option.placeholder)}{$option.placeholder|escape:'htmlall':'UTF-8'}{/if}
                name="{$option.name|escape:'htmlall':'UTF-8'}">{if isset($option.value)}{$option.value|escape:'htmlall':'UTF-8'}{/if}</textarea>
        {/if}
    </div>
{elseif $option.type eq 'button'}
      <button type="button" class="btn {if isset($option.class)}{$option.class|escape:'htmlall':'UTF-8'}{/if}" id="{$option.id|escape:'htmlall':'UTF-8'}">{$option.value|escape:'quotes':'UTF-8'}</button>
  {/if}