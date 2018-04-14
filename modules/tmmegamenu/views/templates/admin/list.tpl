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

<div id="tmmegamenu-configurations-buttons" class="panel clearfix">
    <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="process-icon-edit"></i> {l s='Edit styles' mod='tmmegamenu'}</button>
    <a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addItem">
    	<i class="process-icon-new"></i> {l s='Add item' mod='tmmegamenu'}
    </a>
    <a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addHtml">
    	<i class="icon-html5"></i> {l s='Add HTML' mod='tmmegamenu'}
    </a>
    <a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addLink">
    	<i class="icon-link"></i> {l s='Add link' mod='tmmegamenu'}
    </a>
	<a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addBanner">
    	<i class="icon-image"></i> {l s='Add banner' mod='tmmegamenu'}
    </a>
	<a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addVideo">
    	<i class="icon-youtube"></i> {l s='Add video' mod='tmmegamenu'}
    </a>
	<a class="btn btn-sm btn-success" href="{$url_enable|escape:'html':'UTF-8'}&addMap">
    	<i class="icon-map-marker"></i> {l s='Add map' mod='tmmegamenu'}
    </a>
</div>
<div class="panel tmmegamenu">
    <h3>
        {l s='Items list' mod='tmmegamenu'}
        <span class="badge">{if isset($tabs) && count($tabs)}{count($tabs)}{else}0{/if}</span>
        <span class="panel-heading-action">
            <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addItem">
                <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                    <i class="process-icon-new"></i>
                </span>
            </a>
        </span>
    </h3>
    {if isset($tabs) && $tabs}
        <div class="table-responsive-row clearfix">
            <table class="table tablist table-striped">
                <thead>
                    <tr>
                        <th>{l s='Item id' mod='tmmegamenu'}</th>
                        <th>{l s='Item name' mod='tmmegamenu'}</th>
                        <th>{l s='Item code' mod='tmmegamenu'}</th>
                        <th>{l s='Sort order' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='Badge' mod='tmmegamenu'}</th>
                        <th>{l s='Type' mod='tmmegamenu'}</th>
                        <th>{l s='Status' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$tabs item=tab name='tab'}
                        <tr id="item_{$tab.id_item|escape:'htmlall':'UTF-8'}">
                            <td>{$tab.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$tab.title|escape:'htmlall':'UTF-8'}</td>
                            <td>{$tab.url|escape:'htmlall':'UTF-8'}</td>
                            <td class="pointer dragHandle center"><div class="dragGroup"><div class="positions">{$tab.sort_order|escape:'htmlall':'UTF-8'}</div></div></td>
                            <td>{if $tab.specific_class}{$tab.specific_class|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
                            <td>{if $tab.badge}{$tab.badge|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
                            <td>{if $tab.is_mega}{l s='Is mega' mod='tmmegamenu'}{elseif $tab.is_simple}{l s='Is simple' mod='tmmegamenu'}{else}-{/if}</td>
                            <td>
                                <a class="list-action-enable{if $tab.active} action-enabled{else} action-disabled{/if}" href="{$url_enable|escape:'html':'UTF-8'}&updateItemStatus&id_item={$tab.id_item|escape:'htmlall':'UTF-8'}&itemstatus={$tab.active|escape:'htmlall':'UTF-8'}" title="{if $tab.active}{l s='Enabled' mod='tmmegamenu'}{else}{l s='Disabled' mod='tmmegamenu'}{/if}">
                                    <i class="icon-check{if !$tab.active} hidden{/if}"></i>
                                    <i class="icon-remove{if $tab.active} hidden{/if}"></i>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editItem&id_item={$tab.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteItem&id_item={$tab.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-html">
	<h3>
        {l s='HTML list' mod='tmmegamenu'}
        <span class="badge">{if isset($html_items) && count($html_items)}{count($html_items)}{else}0{/if}</span>
        <span class="panel-heading-action">
        <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addHtml">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($html_items) && $html_items}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='HTML id' mod='tmmegamenu'}</th>
                        <th>{l s='HTML name' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$html_items item=item name='html'}
                        <tr {if $smarty.foreach.html.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.title|escape:'htmlall':'UTF-8'}</td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editHtml&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteHtml&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-link">
	<h3>
        {l s='Links list' mod='tmmegamenu'}
        <span class="badge">{if isset($links) && count($links)}{count($links)}{else}0{/if}</span>
        <span class="panel-heading-action">
        <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addLink">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($links) && $links}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Link id' mod='tmmegamenu'}</th>
                        <th>{l s='Link name' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='URL' mod='tmmegamenu'}</th>
                        <th>{l s='Target blank' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$links item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.title|escape:'htmlall':'UTF-8'}</td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
                            <td><a href="{$item.url|escape:'htmlall':'UTF-8'}">{$item.url|escape:'htmlall':'UTF-8'}</a></td>
                            <td>{if $item.blank}true{else}false{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editLink&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteLink&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-link">
	<h3>
        {l s='Banners list' mod='tmmegamenu'}
        <span class="badge">{if isset($banners) && count($banners)}{count($banners)}{else}0{/if}</span>
        <span class="panel-heading-action">
        <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addBanner">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($banners) && $banners}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Banner id' mod='tmmegamenu'}</th>
                        <th>{l s='Banner name' mod='tmmegamenu'}</th>
                        <th>{l s='Image' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='URL' mod='tmmegamenu'}</th>
                        <th>{l s='Target blank' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$banners item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.title|escape:'htmlall':'UTF-8'}</td>
                            <td><img class="banner-thumbnail" src="{$image_baseurl|escape:'htmlall':'UTF-8'}{$item.image|escape:'htmlall':'UTF-8'}" alt="{$item.title|escape:'htmlall':'UTF-8'}" /></td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
                            <td><a href="{$item.url|escape:'htmlall':'UTF-8'}">{$item.url|escape:'htmlall':'UTF-8'}</a></td>
                            <td>{if $item.blank}true{else}false{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editBanner&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteBanner&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-video">
	<h3>
        {l s='Videos list' mod='tmmegamenu'}
        <span class="badge">{if isset($videos) && count($videos)}{count($videos)}{else}0{/if}</span>
        <span class="panel-heading-action">
        <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addVideo">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($videos) && $videos}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Video id' mod='tmmegamenu'}</th>
                        <th>{l s='Video name' mod='tmmegamenu'}</th>
                        <th>{l s='Video' mod='tmmegamenu'}</th>
                        <th>{l s='URL' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$videos item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.title|escape:'htmlall':'UTF-8'}</td>
                            <td>
                            	{if $item.type == 'youtube'}
                                    <iframe type="text/html" 
                                        src="{$item.url|escape:'htmlall':'UTF-8'}?enablejsapi=1&version=3&html5=1&wmode=transparent"
                                        frameborder="0"
                                        wmode="Opaque"></iframe>
                                {else}
                                    <iframe 
                                        src="{$item.url|escape:'htmlall':'UTF-8'}"
                                        frameborder="0"
                                        webkitAllowFullScreen
                                        mozallowfullscreen
                                        allowFullScreen>
                                    </iframe>
                                {/if}
                            </td>
                            <td><a href="{$item.url|escape:'htmlall':'UTF-8'}">{$item.url|escape:'htmlall':'UTF-8'}</a></td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editVideo&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteVideo&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-map">
	<h3>
        {l s='Maps list' mod='tmmegamenu'}
        <span class="badge">{if isset($maps) && count($maps)}{count($maps)}{else}0{/if}</span>
        <span class="panel-heading-action">
        <a class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addMap">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($maps) && $maps}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Map id' mod='tmmegamenu'}</th>
                        <th>{l s='Map name' mod='tmmegamenu'}</th>
                        <th>{l s='Latitude' mod='tmmegamenu'}</th>
                        <th>{l s='Longitude' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$maps item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.title|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.latitude|escape:'htmlall':'UTF-8'}</td>
                            <td>{$item.longitude|escape:'htmlall':'UTF-8'}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editMap&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteMap&id_item={$item.id_item|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{l s='Menu\'s general styles' mod='tmmegamenu'}</h4>
            </div>
            <div class="modal-body">
                <div class="form-wrapper">
                	<form id="tmmegamenu-style" class="form-horizontal" action="" method="post">
                    	<fieldset>
                        	<h4>{l s='Top level' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper opened">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.color) && $top_level_menu.color}{$top_level_menu.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.background_color) && $top_level_menu.background_color}{$top_level_menu.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlbgimg" value="{if isset($top_level_menu.background_image) && $top_level_menu.background_image}{$top_level_menu.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                    	                            </div>
                        	                    </div>
                            	            </div>
                                	    </div>
                               		</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_menu.background_repeat) && $top_level_menu.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.background_repeat) && $top_level_menu.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.background_repeat) && $top_level_menu.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                         	                       <option {if isset($top_level_menu.background_repeat) && $top_level_menu.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                            	                </select>
                                	            <p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                    	    </div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                 	               <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                        	        <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                          	                      <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                            	                    <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                	                <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                        	        <option {if isset($top_level_menu.background_position) && $top_level_menu.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.border_top_color) && $top_level_menu.border_top_color}{$top_level_menu.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.border_right_color) && $top_level_menu.border_right_color}{$top_level_menu.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.border_bottom_color) && $top_level_menu.border_bottom_color}{$top_level_menu.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu.border_left_color) && $top_level_menu.border_left_color}{$top_level_menu.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu.border_top_style) && $top_level_menu.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($top_level_menu.border_top_style) && $top_level_menu.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($top_level_menu.border_top_style) && $top_level_menu.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($top_level_menu.border_top_style) && $top_level_menu.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($top_level_menu.border_right_style) && $top_level_menu.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($top_level_menu.border_right_style) && $top_level_menu.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($top_level_menu.border_right_style) && $top_level_menu.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.border_right_style) && $top_level_menu.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu.border_bottom_style) && $top_level_menu.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.border_bottom_style) && $top_level_menu.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($top_level_menu.border_bottom_style) && $top_level_menu.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($top_level_menu.border_bottom_style) && $top_level_menu.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu.border_left_style) && $top_level_menu.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.border_left_style) && $top_level_menu.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.border_left_style) && $top_level_menu.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu.border_left_style) && $top_level_menu.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu.border_top_width) && $top_level_menu.border_top_width !=''}{$top_level_menu.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu.border_right_width) && $top_level_menu.border_right_width  !=''}{$top_level_menu.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu.border_bottom_width) && $top_level_menu.border_bottom_width !=''}{$top_level_menu.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu.border_left_width) && $top_level_menu.border_left_width !=''}{$top_level_menu.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu.border_top_right_radius) && $top_level_menu.border_top_right_radius !=''}{$top_level_menu.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu.border_bottom_right_radius) && $top_level_menu.border_bottom_right_radius !=''}{$top_level_menu.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                           		<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu.border_bottom_left_radius) && $top_level_menu.border_bottom_left_radius !=''}{$top_level_menu.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu.border_top_left_radius) && $top_level_menu.border_top_left_radius !=''}{$top_level_menu.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_menu.box_shadow) && $top_level_menu.box_shadow}{$top_level_menu.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="top-level-menu" />
                        	</div>
                    	</fieldset>
                    	<fieldset>
                        	<h4>{l s='Top level element' mod='tmmegamenu'}</h4>
                       		<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                               		<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.color) && $top_level_menu_li.color}{$top_level_menu_li.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.background_color) && $top_level_menu_li.background_color}{$top_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlmlbgimg" value="{if isset($top_level_menu_li.background_image) && $top_level_menu_li.background_image}{$top_level_menu_li.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlmlbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.background_repeat) && $top_level_menu_li.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_repeat) && $top_level_menu_li.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_repeat) && $top_level_menu_li.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_repeat) && $top_level_menu_li.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                       		</div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                                               		<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.background_position) && $top_level_menu_li.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_top_color) && $top_level_menu_li.border_top_color}{$top_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_right_color) && $top_level_menu_li.border_right_color}{$top_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_bottom_color) && $top_level_menu_li.border_bottom_color}{$top_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_left_color) && $top_level_menu_li.border_left_color}{$top_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                               		</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                               		<option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-right-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                       		</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li.border_top_width) && $top_level_menu_li.border_top_width !=''}{$top_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li.border_right_width) && $top_level_menu_li.border_right_width !=''}{$top_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li.border_bottom_width) && $top_level_menu_li.border_bottom_width !=''}{$top_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li.border_left_width) && $top_level_menu_li.border_left_width !=''}{$top_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li.border_top_right_radius) && $top_level_menu_li.border_top_right_radius !=''}{$top_level_menu_li.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li.border_bottom_right_radius) && $top_level_menu_li.border_bottom_right_radius !=''}{$top_level_menu_li.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li.border_bottom_left_radius) && $top_level_menu_li.border_bottom_left_radius !=''}{$top_level_menu_li.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                       		</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li.border_top_left_radius) && $top_level_menu_li.border_top_left_radius !=''}{$top_level_menu_li.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_menu_li.box_shadow) && $top_level_menu_li.box_shadow}{$top_level_menu_li.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="top-level-menu-li" />
                        	</div>
                    	</fieldset>
                    	<fieldset>
                        	<h4>{l s='Top level element' mod='tmmegamenu'}<span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.color) && $top_level_menu_li_hover.color}{$top_level_menu_li_hover.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                   		<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                   		<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.background_color) && $top_level_menu_li_hover.background_color}{$top_level_menu_li_hover.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlmlhbgimg" value="{if isset($top_level_menu_li_hover.background_image) && $top_level_menu_li_hover.background_image}{$top_level_menu_li_hover.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlmlhbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                <label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.background_repeat) && $top_level_menu_li_hover.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_repeat) && $top_level_menu_li_hover.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_repeat) && $top_level_menu_li_hover.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_repeat) && $top_level_menu_li_hover.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.background_position) && $top_level_menu_li_hover.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                               		<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_top_color) && $top_level_menu_li_hover.border_top_color}{$top_level_menu_li_hover.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_right_color) && $top_level_menu_li_hover.border_right_color}{$top_level_menu_li_hover.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_bottom_color) && $top_level_menu_li_hover.border_bottom_color}{$top_level_menu_li_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_left_color) && $top_level_menu_li_hover.border_left_color}{$top_level_menu_li_hover.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                   		<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-right-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                               		<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_hover.border_top_width) && $top_level_menu_li_hover.border_top_width !=''}{$top_level_menu_li_hover.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                           		<input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_hover.border_right_width) && $top_level_menu_li_hover.border_right_width !=''}{$top_level_menu_li_hover.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_hover.border_bottom_width) && $top_level_menu_li_hover.border_bottom_width !=''}{$top_level_menu_li_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_hover.border_left_width) && $top_level_menu_li_hover.border_left_width !=''}{$top_level_menu_li_hover.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                           		<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" name="border-top-right-radius" value="{if isset($top_level_menu_li_hover.border_top_right_radius) && $top_level_menu_li_hover.border_top_right_radius !=''}{$top_level_menu_li_hover.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" name="border-bottom-right-radius" value="{if isset($top_level_menu_li_hover.border_bottom_right_radius) && $top_level_menu_li_hover.border_bottom_right_radius !=''}{$top_level_menu_li_hover.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" name="border-bottom-left-radius" value="{if isset($top_level_menu_li_hover.border_bottom_left_radius) && $top_level_menu_li_hover.border_bottom_left_radius !=''}{$top_level_menu_li_hover.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                       		<div class="col-lg-3">
                                            	<input class="form-control" name="border-top-left-radius" value="{if isset($top_level_menu_li_hover.border_top_left_radius) && $top_level_menu_li_hover.border_top_left_radius !=''}{$top_level_menu_li_hover.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_menu_li_hover.box_shadow) && $top_level_menu_li_hover.box_shadow}{$top_level_menu_li_hover.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="top-level-menu-li:hover" />
                            	<input type="hidden" class="classes" value="top-level-menu-li.sfHover, .tmmegamenu_item.top-level-menu-li.sfHoverForce" />
                        	</div>
                    	</fieldset>
                    	<fieldset>
                        	<h4>{l s='Top level badge' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.color) && $top_level_badge.color}{$top_level_badge.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.background_color) && $top_level_badge.background_color}{$top_level_badge.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                       		<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlbbgimg" value="{if isset($top_level_menu_li.background_image) && $top_level_menu_li.background_image}{$top_level_menu_li.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlbbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                               		<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.background_repeat) && $top_level_badge.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_repeat) && $top_level_badge.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_repeat) && $top_level_badge.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                                               		<option {if isset($top_level_badge.background_repeat) && $top_level_badge.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.background_position) && $top_level_badge.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                           		<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_top_color) && $top_level_badge.border_top_color}{$top_level_badge.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                               		<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_right_color) && $top_level_badge.border_right_color}{$top_level_badge.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                           		<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_bottom_color) && $top_level_badge.border_bottom_color}{$top_level_badge.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                               		<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_left_color) && $top_level_badge.border_left_color}{$top_level_badge.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                           		</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                           		<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-right-style">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                               		</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_badge.border_top_width) && $top_level_badge.border_top_width !=''}{$top_level_badge.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_badge.border_right_width) && $top_level_badge.border_right_width !=''}{$top_level_badge.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_badge.border_bottom_width) && $top_level_badge.border_bottom_width !=''}{$top_level_badge.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_badge.border_left_width) && $top_level_badge.border_left_width !=''}{$top_level_badge.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_badge.border_top_right_radius) && $top_level_badge.border_top_right_radius !=''}{$top_level_badge.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_badge.border_bottom_right_radius) && $top_level_badge.border_bottom_right_radius !=''}{$top_level_badge.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_badge.border_bottom_left_radius) && $top_level_badge.border_bottom_left_radius !=''}{$top_level_badge.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_badge.border_top_left_radius) && $top_level_badge.border_top_left_radius !=''}{$top_level_badge.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_badge.box_shadow) && $top_level_badge.box_shadow}{$top_level_badge.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="top-level-badge" />
                        	</div>
                    	</fieldset>
                    	<fieldset>
                        	<h4>{l s='Top level element link' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                            		<div class="col-lg-10">
                                		<div class="form-group">
                                    		<div class="col-lg-4">
                                        		<div class="row">
                                            		<div class="input-group">
                                                		<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.color) && $top_level_menu_li_a.color}{$top_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}" />
                                            		</div>
                                       			</div>
                                    		</div>
                                		</div>
                            		</div>
                        		</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                            		<div class="col-lg-10">
                                		<div class="form-group">
                                    		<div class="col-lg-4">
                                        		<div class="row">
                                            		<div class="input-group">
                                                		<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.background_color) && $top_level_menu_li_a.background_color}{$top_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}" />
                                            		</div>
                                        		</div>
                                    		</div>
                                		</div>
                            		</div>
                        		</div>
                        		<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlmlabgimg" value="{if isset($top_level_menu_li_a.background_image) && $top_level_menu_li_a.background_image}{$top_level_menu_li_a.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlmlabgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a.background_repeat) && $top_level_menu_li_a.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_repeat) && $top_level_menu_li_a.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_repeat) && $top_level_menu_li_a.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_repeat) && $top_level_menu_li_a.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a.background_position) && $top_level_menu_li_a.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                            		<div class="col-lg-10">
                                		<div class="form-group no-indent">
                                    		<div class="col-lg-3">
                                        		<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_top_color) && $top_level_menu_li_a.border_top_color}{$top_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                    		</div>
                                    		<div class="col-lg-3">
                                        	<div class="input-group">
                                           		<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_right_color) && $top_level_menu_li_a.border_right_color}{$top_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                        	</div>
                                        	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                    	</div>
                                    	<div class="col-lg-3">
                                        	<div class="input-group">
                                            	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_bottom_color) && $top_level_menu_li_a.border_bottom_color}{$top_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                        	</div>
                                        	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                    	</div>
                                    	<div class="col-lg-3">
                                        	<div class="input-group">
                                            	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_left_color) && $top_level_menu_li_a.border_left_color}{$top_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                        	</div>
                                        	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                    	</div>
                                	</div>
                            	</div>
                        	</div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group no-indent">
                                            <div class="col-lg-3">
                                                <select name="border-top-style">
                                                    <option></option>
                                                    <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                                </select>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <select name="border-right-style">
                                                    <option></option>
                                                    <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                                </select>
                                                <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <select name="border-bottom-style">
                                                    <option></option>
                                                    <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                                </select>
                                                <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <select name="border-left-style">
                                                    <option></option>
                                                    <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                    <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                                </select>
                                                <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group no-indent">
                                            <div class="col-lg-3">
                                                <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_a.border_top_width) && $top_level_menu_li_a.border_top_width !=''}{$top_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_a.border_right_width) && $top_level_menu_li_a.border_right_width !=''}{$top_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_a.border_bottom_width) && $top_level_menu_li_a.border_bottom_width !=''}{$top_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_a.border_left_width) && $top_level_menu_li_a.border_left_width !=''}{$top_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group no-indent">
                                            <div class="col-lg-3">
                                                <input class="form-control" name="border-top-right-radius" value="{if isset($top_level_menu_li_a.border_top_right_radius) && $top_level_menu_li_a.border_top_right_radius !=''}{$top_level_menu_li_a.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" name="border-bottom-right-radius" value="{if isset($top_level_menu_li_a.border_bottom_right_radius) && $top_level_menu_li_a.border_bottom_right_radius !=''}{$top_level_menu_li_a.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" name="border-bottom-left-radius" value="{if isset($top_level_menu_li_a.border_bottom_left_radius) && $top_level_menu_li_a.border_bottom_left_radius !=''}{$top_level_menu_li_a.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" name="border-top-left-radius" value="{if isset($top_level_menu_li_a.border_top_left_radius) && $top_level_menu_li_a.border_top_left_radius !=''}{$top_level_menu_li_a.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                                <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="mainclass" value="top-level-menu-li-a" />
                                <input type="hidden" class="classes" value="menu-title" />
                            </div>
                        </fieldset>
                    	<fieldset>
                        	<h4>{l s='Top level element link' mod='tmmegamenu'}<span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.color) && $top_level_menu_li_a_hover.color}{$top_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.background_color) && $top_level_menu_li_a_hover.background_color}{$top_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}" />
                                               		</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="tlmlahbgimg" value="{if isset($top_level_menu_li_a_hover.background_image) && $top_level_menu_li_a_hover.background_image}{$top_level_menu_li_a_hover.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="tlmlahbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                               		<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_repeat) && $top_level_menu_li_a_hover.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_repeat) && $top_level_menu_li_a_hover.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_repeat) && $top_level_menu_li_a_hover.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_repeat) && $top_level_menu_li_a_hover.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                               		<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.background_position) && $top_level_menu_li_a_hover.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                               		<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_top_color) && $top_level_menu_li_a_hover.border_top_color}{$top_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_right_color) && $top_level_menu_li_a_hover.border_right_color}{$top_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_bottom_color) && $top_level_menu_li_a_hover.border_bottom_color}{$top_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_left_color) && $top_level_menu_li_a_hover.border_left_color}{$top_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                       		<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-right-style">
                                               		<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                               		<option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                   		</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_a_hover.border_top_width) && $top_level_menu_li_a_hover.border_top_width !=''}{$top_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_a_hover.border_right_width) && $top_level_menu_li_a_hover.border_right_width !=''}{$top_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_a_hover.border_bottom_width) && $top_level_menu_li_a_hover.border_bottom_width !=''}{$top_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_a_hover.border_left_width) && $top_level_menu_li_a_hover.border_left_width !=''}{$top_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li_a_hover.border_top_right_radius) && $top_level_menu_li_a_hover.border_top_right_radius !=''}{$top_level_menu_li_a_hover.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li_a_hover.border_bottom_right_radius) && $top_level_menu_li_a_hover.border_bottom_right_radius !=''}{$top_level_menu_li_a_hover.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li_a_hover.border_bottom_left_radius) && $top_level_menu_li_a_hover.border_bottom_left_radius !=''}{$top_level_menu_li_a_hover.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li_a_hover.border_top_left_radius) && $top_level_menu_li_a_hover.border_top_left_radius !=''}{$top_level_menu_li_a_hover.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="top-level-menu-li-a:hover" />
                            	<input type="hidden" class="classes" value="top-level-menu-li.sfHover > a, .tmmegamenu_item.top-level-menu-li.sfHoverForce > a, .tmmegamenu_item.menu-title.active" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='First level' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.color) && $first_level_menu.color}{$first_level_menu.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.background_color) && $first_level_menu.background_color}{$first_level_menu.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="flmbgimg" value="{if isset($first_level_menu.background_image) && $first_level_menu.background_image}{$first_level_menu.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="flmbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                    	                            </div>
                        	                    </div>
                            	            </div>
                                	    </div>
                               		</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                         	                       <option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                            	                </select>
                                	            <p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                    	    </div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                 	               <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                        	        <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                          	                      <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                            	                    <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                	                <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                        	        <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_top_color) && $first_level_menu.border_top_color}{$first_level_menu.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_right_color) && $first_level_menu.border_right_color}{$first_level_menu.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_bottom_color) && $first_level_menu.border_bottom_color}{$first_level_menu.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_left_color) && $first_level_menu.border_left_color}{$first_level_menu.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu.border_top_width) && $first_level_menu.border_top_width !=''}{$first_level_menu.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu.border_right_width) && $first_level_menu.border_right_width  !=''}{$first_level_menu.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu.border_bottom_width) && $first_level_menu.border_bottom_width !=''}{$first_level_menu.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu.border_left_width) && $first_level_menu.border_left_width !=''}{$first_level_menu.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($first_level_menu.border_top_right_radius) && $first_level_menu.border_top_right_radius !=''}{$first_level_menu.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($first_level_menu.border_bottom_right_radius) && $first_level_menu.border_bottom_right_radius !=''}{$first_level_menu.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                           		<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($first_level_menu.border_bottom_left_radius) && $first_level_menu.border_bottom_left_radius !=''}{$first_level_menu.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($first_level_menu.border_top_left_radius) && $first_level_menu.border_top_left_radius !=''}{$first_level_menu.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($first_level_menu.box_shadow) && $first_level_menu.box_shadow}{$first_level_menu.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="first-level-menu" />
                                <input type="hidden" class="classes" value="top_menu > ul > li ul.is-simplemenu, .tmmegamenu_item.top_menu > ul > li ul.is-simplemenu ul, .tmmegamenu_item.column_menu > ul > li ul.is-simplemenu, .tmmegamenu_item.column_menu > ul > li ul.is-simplemenu ul" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='First level element' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.color) && $first_level_menu_li.color}{$first_level_menu_li.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.background_color) && $first_level_menu_li.background_color}{$first_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_top_color) && $first_level_menu_li.border_top_color}{$first_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_right_color) && $first_level_menu_li.border_right_color}{$first_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_bottom_color) && $first_level_menu_li.border_bottom_color}{$first_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_left_color) && $first_level_menu_li.border_left_color}{$first_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li.border_top_width) && $first_level_menu_li.border_top_width !=''}{$first_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li.border_right_width) && $first_level_menu_li.border_right_width  !=''}{$first_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li.border_bottom_width) && $first_level_menu_li.border_bottom_width !=''}{$first_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li.border_left_width) && $first_level_menu_li.border_left_width !=''}{$first_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="first-level-menu-li" />
                                <input type="hidden" class="classes" value="top_menu li > li, .tmmegamenu_item.column_menu li > li" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='First level element link' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.color) && $first_level_menu_li_a.color}{$first_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.background_color) && $first_level_menu_li_a.background_color}{$first_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_top_color) && $first_level_menu_li_a.border_top_color}{$first_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_right_color) && $first_level_menu_li_a.border_right_color}{$first_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_bottom_color) && $first_level_menu_li_a.border_bottom_color}{$first_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_left_color) && $first_level_menu_li_a.border_left_color}{$first_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li_a.border_top_width) && $first_level_menu_li_a.border_top_width !=''}{$first_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li_a.border_right_width) && $first_level_menu_li_a.border_right_width  !=''}{$first_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li_a.border_bottom_width) && $first_level_menu_li_a.border_bottom_width !=''}{$first_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li_a.border_left_width) && $first_level_menu_li_a.border_left_width !=''}{$first_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="first-level-menu-li-a" />
                                <input type="hidden" class="classes" value="top_menu li li a, .tmmegamenu_item.column_menu li li a" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='First level element link' mod='tmmegamenu'}<span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.color) && $first_level_menu_li_a_hover.color}{$first_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.background_color) && $first_level_menu_li_a_hover.background_color}{$first_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_top_color) && $first_level_menu_li_a_hover.border_top_color}{$first_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_right_color) && $first_level_menu_li_a_hover.border_right_color}{$first_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_bottom_color) && $first_level_menu_li_a_hover.border_bottom_color}{$first_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_left_color) && $first_level_menu_li_a_hover.border_left_color}{$first_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li_a_hover.border_top_width) && $first_level_menu_li_a_hover.border_top_width !=''}{$first_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li_a_hover.border_right_width) && $first_level_menu_li_a_hover.border_right_width  !=''}{$first_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li_a_hover.border_bottom_width) && $first_level_menu_li_a_hover.border_bottom_width !=''}{$first_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li_a_hover.border_left_width) && $first_level_menu_li_a_hover.border_left_width !=''}{$first_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="first-level-menu-li-a:hover" />
                                <input type="hidden" class="classes" value="top_menu li li.sfHover > a, .tmmegamenu_item.top_menu li li:hover > a, .tmmegamenu_item.top_menu li li.sfHoverForce > a, .tmmegamenu_item.column_menu li li.sfHover > a, .tmmegamenu_item.column_menu li li:hover > a, .tmmegamenu_item.column_menu li li.sfHoverForce > a" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='Next level' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.color) && $next_level_menu.color}{$next_level_menu.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.background_color) && $next_level_menu.background_color}{$next_level_menu.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                            		<label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-10">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="nlmbgimg" value="{if isset($next_level_menu.background_image) && $next_level_menu.background_image}{$next_level_menu.background_image|escape:'html':'UTF-8'}{/if}" />
                                                    	<span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                                                        <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                                    					<span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="nlmbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                    	                            </div>
                        	                    </div>
                            	            </div>
                                	    </div>
                               		</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="background-repeat">
                                                	<option></option>
                                                	<option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                         	                       <option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                            	                </select>
                                	            <p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                                    	    </div>
                                        	<div class="col-lg-3">
                                            	<select name="background-position">
                                                	<option></option>
                                                	<option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                                 	               <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                                        	        <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                          	                      <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                            	                    <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                                	                <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                                    	            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                                        	        <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_top_color) && $next_level_menu.border_top_color}{$next_level_menu.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_right_color) && $next_level_menu.border_right_color}{$next_level_menu.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_bottom_color) && $next_level_menu.border_bottom_color}{$next_level_menu.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_left_color) && $next_level_menu.border_left_color}{$next_level_menu.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu.border_top_width) && $next_level_menu.border_top_width !=''}{$next_level_menu.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu.border_right_width) && $next_level_menu.border_right_width  !=''}{$next_level_menu.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu.border_bottom_width) && $next_level_menu.border_bottom_width !=''}{$next_level_menu.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu.border_left_width) && $next_level_menu.border_left_width !=''}{$next_level_menu.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($next_level_menu.border_top_right_radius) && $next_level_menu.border_top_right_radius !=''}{$next_level_menu.border_top_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($next_level_menu.border_bottom_right_radius) && $next_level_menu.border_bottom_right_radius !=''}{$next_level_menu.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                           		<input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($next_level_menu.border_bottom_left_radius) && $next_level_menu.border_bottom_left_radius !=''}{$next_level_menu.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($next_level_menu.border_top_left_radius) && $next_level_menu.border_top_left_radius !=''}{$next_level_menu.border_top_left_radius|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-6">
                                            	<input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($next_level_menu.box_shadow) && $next_level_menu.box_shadow}{$next_level_menu.box_shadow|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px rgba(0,0,0,0.75)</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="next-level-menu" />
                                <input type="hidden" class="classes" value="top_menu > ul > li ul.is-simplemenu ul, .tmmegamenu_item.column_menu > ul > li ul.is-simplemenu ul" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='Next level element' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.color) && $next_level_menu_li.color}{$next_level_menu_li.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.background_color) && $next_level_menu_li.background_color}{$next_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_top_color) && $next_level_menu_li.border_top_color}{$next_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_right_color) && $next_level_menu_li.border_right_color}{$next_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_bottom_color) && $next_level_menu_li.border_bottom_color}{$next_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_left_color) && $next_level_menu_li.border_left_color}{$next_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li.border_top_width) && $next_level_menu_li.border_top_width !=''}{$next_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li.border_right_width) && $next_level_menu_li.border_right_width  !=''}{$next_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li.border_bottom_width) && $next_level_menu_li.border_bottom_width !=''}{$next_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li.border_left_width) && $next_level_menu_li.border_left_width !=''}{$next_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="next-level-menu-li" />
                                <input type="hidden" class="classes" value="top_menu li li > li, .tmmegamenu_item.column_menu li li > li" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='Next level element link' mod='tmmegamenu'}</h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.color) && $next_level_menu_li_a.color}{$next_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.background_color) && $next_level_menu_li_a.background_color}{$next_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_top_color) && $next_level_menu_li_a.border_top_color}{$next_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_right_color) && $next_level_menu_li_a.border_right_color}{$next_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_bottom_color) && $next_level_menu_li_a.border_bottom_color}{$next_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_left_color) && $next_level_menu_li_a.border_left_color}{$next_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li_a.border_top_width) && $next_level_menu_li_a.border_top_width !=''}{$next_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li_a.border_right_width) && $next_level_menu_li_a.border_right_width  !=''}{$next_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li_a.border_bottom_width) && $next_level_menu_li_a.border_bottom_width !=''}{$next_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li_a.border_left_width) && $next_level_menu_li_a.border_left_width !=''}{$next_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="next-level-menu-li-a" />
                                <input type="hidden" class="classes" value="top_menu li li li a, .tmmegamenu_item.column_menu li li li a" />
                        	</div>
                    	</fieldset>
                        <fieldset>
                        	<h4>{l s='Next level element link' mod='tmmegamenu'}<span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                        	<div class="fieldset-content-wrapper closed">
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.color) && $next_level_menu_li_a_hover.color}{$next_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group">
                                        	<div class="col-lg-4">
                                            	<div class="row">
                                                	<div class="input-group">
                                                    	<input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.background_color) && $next_level_menu_li_a_hover.background_color}{$next_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}" />
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                    <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_top_color) && $next_level_menu_li_a_hover.border_top_color}{$next_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}" />
                                                </div>
                                                <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_right_color) && $next_level_menu_li_a_hover.border_right_color}{$next_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_bottom_color) && $next_level_menu_li_a_hover.border_bottom_color}{$next_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<div class="input-group">
                                                	<input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_left_color) && $next_level_menu_li_a_hover.border_left_color}{$next_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}" />
                                            	</div>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<select name="border-top-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
      	    	                                    <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
        	                                        <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                	                                <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                    	                        </select>
                        	                    <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                            	            </div>
                                	        <div class="col-lg-3">
                                    	        <select name="border-right-style">
                                        	        <option></option>
                                            	    <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                      		        <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                            	    <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-bottom-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                         	                        <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            		                <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                             	</select>
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<select name="border-left-style">
                                                	<option></option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                                                	<option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                                            	</select>
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<div class="form-group">
                                	<label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                                	<div class="col-lg-10">
                                    	<div class="form-group no-indent">
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li_a_hover.border_top_width) && $next_level_menu_li_a_hover.border_top_width !=''}{$next_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li_a_hover.border_right_width) && $next_level_menu_li_a_hover.border_right_width  !=''}{$next_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li_a_hover.border_bottom_width) && $next_level_menu_li_a_hover.border_bottom_width !=''}{$next_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                                        	</div>
                                        	<div class="col-lg-3">
                                            	<input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li_a_hover.border_left_width) && $next_level_menu_li_a_hover.border_left_width !=''}{$next_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}" />
                                            	<p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                            	<input type="hidden" class="mainclass" value="next-level-menu-li-a:hover" />
                                <input type="hidden" class="classes" value="top_menu li li li.sfHover > a, .tmmegamenu_item.top_menu li li li:hover > a, .tmmegamenu_item.top_menu li li li.sfHoverForce > a, .tmmegamenu_item.column_menu li li li.sfHover > a, .tmmegamenu_item.column_menu li li li:hover > a, .tmmegamenu_item.column_menu li li li.sfHoverForce > a" />
                        	</div>
                    	</fieldset>
                    	<input type="hidden" name="cssname" value="megamenu_custom_styles" />
                	</form>
            	</div>
            </div>
            <div class="modal-footer clearfix">
                <button id="generate-styles" class="btn btn-sm btn-success" >{l s='Generate styles' mod='tmmegamenu'}</button>
                <button id="reset-styles" class="btn btn-sm btn-danger pull-left" >{l s='Reset styles' mod='tmmegamenu'}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='tmmegamenu'}</button>
            </div>
        </div>
        <div class="modal-loader"><span class="loader-gif"></span></div>
    </div>
</div>
<script type="text/javascript">
	var warning_class_text = '{l s='Not all fields are valid' mod='tmmegamenu'}';
    var theme_url = '{$theme_url}';
</script>