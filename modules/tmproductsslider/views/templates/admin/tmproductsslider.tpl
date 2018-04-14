{*
* 2002-2016 TemplateMonster
*
* TM Products Slider
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

<script type="text/javascript">
  theme_url='{$admin_list.theme_url|escape:"quotes":"UTF-8"}';
  shopCount =[];
</script>
<div class="panel">
  <h3>{l s='TM Products Slides' mod='tmproductsslider'}</h3>
  <div class="clearfix">
    {foreach from=$admin_list.lists item=list name=myloop}
      <div class="col-lg-4">
        <h4 class="list-title">{$admin_list.shop_name[$smarty.foreach.myloop.iteration - 1]|escape:'htmlall':'UTF-8'}</h4>
        <script type="text/javascript">shopCount.push(1)</script>
        <ul class="slides-list" id="list_{$smarty.foreach.myloop.iteration|escape:'htmlall':'UTF-8'}">
          {foreach from=$list item=item name=itemloop}
            {if $item.id_product}
              <li id="item-{$item.id_product|escape:'htmlall':'UTF-8'}" class="item">
                <form method="post" action="" enctype="multipart/form-data" class="defaultForm form-horizontal clearfix">
                  <span class="sort_order">{$item.slide_order|escape:'htmlall':'UTF-8'}</span>
                  <span class="item_name">
                    {$item.name|escape:'htmlall':'UTF-8'|truncate:45}
                    <small>({l s='product ID' mod='tmproductsslider'}
                      {$item.id_product|escape:'htmlall':'UTF-8'})
                    </small>
                  </span>
                  <button type="submit" class="list-action-enable{if $item.slide_status} action-enabled{else} action-disabled{/if} pull-right" name="updateItem">
                    {if $item.slide_status}
                      <i class="icon-check"></i>
                    {else}
                      <i class="icon-remove"></i>
                    {/if}
                  </button>
                  <input type="hidden" name="item_status" value="{$item.slide_status|escape:'htmlall':'UTF-8'}" />
                  <input type="hidden" name="id_product" value="{$item.id_product|escape:'htmlall':'UTF-8'}" />
                  <input type="hidden" name="id_shop" value="{$item.id_shop|escape:'htmlall':'UTF-8'}" />
                </form>
              </li>
            {else}
              <li class="no-slides">
                {l s='There are no slides for this shop' mod='tmproductsslider'}
              </li>
            {/if}
          {/foreach}
        </ul>
      </div>
    {/foreach}
  </div>
</div>

<form method="post" action="" enctype="multipart/form-data" class="defaultForm form-horizontal clearfix">
  <div class="panel">
    <h3>{l s='TM Products Slider Settings' mod='tmproductsslider'}</h3>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Slider Width' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <div class="input-group">
          <input name="slider_width" type="text" value="{if $settings.slider_width}{$settings.slider_width|escape:'htmlall':'UTF-8'}{/if}" />
          <span class="input-group-addon">{l s='pixels' mod='tmproductsslider'}</span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Slider Type' mod='tmproductsslider'}</label>
      <div class="col-lg-2">
        <select name="slider_effect">
          <option value="fade" {if $settings.slider_type == 'fade'}selected="sected"{/if}>fade</option>
          <option value="horizontal" {if $settings.slider_type == 'horizontal'}selected="sected"{/if}>horizontal</option>
          <option value="vertical" {if $settings.slider_type == 'vertical'}selected="sected"{/if}>vertical</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Slider Speed' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <div class="input-group">
          <input name="slider_speed" type="text" value="{if $settings.slider_speed}{$settings.slider_speed|escape:'htmlall':'UTF-8'}{/if}" />
          <span class="input-group-addon">{l s='milliseconds ' mod='tmproductsslider'}</span>
        </div>
        <p class="help-block">{l s='The duration of the transition between two slides.' mod='tmproductsslider'}</p>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Slider Pause' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <div class="input-group">
          <input name="slider_pause" type="text" value="{if $settings.slider_pause}{$settings.slider_pause|escape:'htmlall':'UTF-8'}{/if}" />
          <span class="input-group-addon">{l s='milliseconds ' mod='tmproductsslider'}</span>
        </div>
        <p class="help-block">{l s='The delay between two slides.' mod='tmproductsslider'}</p>
       </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Auto play' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <span class="switch prestashop-switch fixed-width-lg">
          <input id="slider_loop_on" type="radio" {if $settings.slider_loop}checked="checked"{/if} value="1" name="slider_loop">
          <label for="slider_loop_on">Yes</label>
          <input id="slider_loop_off" type="radio" {if !$settings.slider_loop}checked="checked"{/if} value="0" name="slider_loop">
          <label for="slider_loop_off">No</label>
          <a class="slide-button btn"></a>
        </span>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Pause on hover' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <span class="switch prestashop-switch fixed-width-lg">
          <input id="slider_pause_h_on" type="radio" {if $settings.slider_pause_h}checked="checked"{/if} value="1" name="slider_pause_h">
          <label for="slider_pause_h_on">Yes</label>
          <input id="slider_pause_h_off" type="radio" {if !$settings.slider_pause_h}checked="checked"{/if} value="0" name="slider_pause_h">
          <label for="slider_pause_h_off">No</label>
          <a class="slide-button btn"></a>
        </span>
      </div>
    </div>
    <div class="form-group">
    <label class="control-label col-lg-3">{l s='Pager' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <span class="switch prestashop-switch fixed-width-lg">
          <input id="slider_pager_on" type="radio" {if $settings.slider_pager}checked="checked"{/if} value="1" name="slider_pager">
          <label for="slider_pager_on">Yes</label>
          <input id="slider_pager_off" type="radio" {if !$settings.slider_pager}checked="checked"{/if} value="0" name="slider_pager">
          <label for="slider_pager_off">No</label>
          <a class="slide-button btn"></a>
        </span>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Controls' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <span class="switch prestashop-switch fixed-width-lg">
          <input id="slider_controls_on" type="radio" {if $settings.slider_controls}checked="checked"{/if} value="1" name="slider_controls">
          <label for="slider_controls_on">Yes</label>
          <input id="slider_controls_off" type="radio" {if !$settings.slider_controls}checked="checked"{/if} value="0" name="slider_controls">
          <label for="slider_controls_off">No</label>
          <a class="slide-button btn"></a>
        </span>
        <p class="help-block">{l s='Prev/Next buttons.' mod='tmproductsslider'}</p>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-3">{l s='Auto Controls' mod='tmproductsslider'}</label>
      <div class="col-lg-7">
        <span class="switch prestashop-switch fixed-width-lg">
          <input id="slider_auto_controls_on" type="radio" {if $settings.slider_auto_controls}checked="checked"{/if} value="1" name="slider_auto_controls">
          <label for="slider_auto_controls_on">Yes</label>
          <input id="slider_auto_controls_off" type="radio" {if !$settings.slider_auto_controls}checked="checked"{/if} value="0" name="slider_auto_controls">
          <label for="slider_auto_controls_off">No</label>
          <a class="slide-button btn"></a>
        </span>
        <p class="help-block">{l s='Play/Pause buttons (only if "Auto Play" is active).' mod='tmproductsslider'}</p>
      </div>
    </div>
    <div class="panel-footer">
      <button id="module_form_submit_btn" class="btn btn-default pull-right" name="submitSlider" value="1" type="submit">
        <i class="process-icon-save"></i>
        {l s='Save' mod='tmproductsslider'}
      </button>
    </div>
</div>
</form>