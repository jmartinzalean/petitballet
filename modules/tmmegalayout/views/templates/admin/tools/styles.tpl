{**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="form-wrapper tmmegalayout-styles">
  <h2>{l s='Style' mod='tmmegalayout'}</h2>
  <ul class="nav nav-tabs">
    <li id="s-tab-1" class="active">
      <a class="layouts-tab" data-toggle="tab" href="#sitems-1">{l s='Background styles' mod='tmmegalayout'}</a></li>
    <li id="s-tab-2">
      <a class="layouts-tab" data-toggle="tab" href="#sitems-2">{l s='Border styles' mod='tmmegalayout'}</a></li>
    <li id="s-tab-3">
      <a class="layouts-tab" data-toggle="tab" href="#sitems-3">{l s='Other styles' mod='tmmegalayout'}</a></li>
  </ul>
  <div class="tab-content tmmegalayout-styles-content">
    <div id="sitems-1" class="tab-pane active">
      <div class="clearfix">
        <div class="col-sm-12 col-md-3 background-color-col">
          <div class="background-color-container">
            <label class="uppercase">{l s='color' mod='tmmegalayout'}</label>
            <div class="input-group">
              <input data-hex="true" data-type="clr" class="form-control color tmml_color_input" name="background-color" value="{if isset($styles.background_color) && $styles.background_color}{$styles.background_color|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-9">
          <div class="background-settings-col">
            <div class="row">
              <div class="col-xs-12 background-image-container col-md-8">
                <label class="uppercase">{l s='Image' mod='tmmegalayout'}</label>
                <div class="form-group">
                  <div class="input-group">
                    <input disabled="disabled" class="form-control" name="background-image" value="{if isset($styles.background_image) && $styles.background_image}{$styles.background_image|escape:'quotes':'UTF-8'}{/if}"/>
                    <span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                    <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                    <span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="flmbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="uppercase">{l s='repeat' mod='tmmegalayout'}</label>
                  <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-3">
                      <div class="radio">
                        <label>
                          <input {if isset($styles.background_repeat) && $styles.background_repeat == 'no-repeat'}checked="checked"{/if} type="radio" name="background-repeat" value="no-repeat"/>
                          no-repeat
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-3">
                      <div class="radio">
                        <label>
                          <input {if isset($styles.background_repeat) && $styles.background_repeat == 'repeat-x'}checked="checked"{/if} type="radio" name="background-repeat" value="repeat-x"/>
                          repeat-x
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-3">
                      <div class="radio">
                        <label>
                          <input {if isset($styles.background_repeat) && $styles.background_repeat == 'repeat-y'}checked="checked"{/if} type="radio" name="background-repeat" value="repeat-y"/>
                          repeat-y
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-3">
                      <div class="radio">
                        <label>
                          <input {if isset($styles.background_repeat) && $styles.background_repeat == 'repeat'}checked="checked"{/if} type="radio" name="background-repeat" value="repeat"/>
                          repeat
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 background-settings-container col-md-4">
                <div class="form-group">
                  <label class="uppercase">{l s='position' mod='tmmegalayout'}</label>
                  <select name="background-position">
                    <option value=""></option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'center center'}selected="selected"{/if} value="center center">
                      center center
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'center top'}selected="selected"{/if} value="center top">
                      center top
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">
                      center bottom
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'left top'}selected="selected"{/if} value="left top">
                      left top
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'left center'}selected="selected"{/if} value="left center">
                      left center
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">
                      left bottom
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'right top'}selected="selected"{/if} value="right top">
                      right top
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'right center'}selected="selected"{/if} value="right center">
                      right center
                    </option>
                    <option {if isset($styles.background_position) && $styles.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">
                      right bottom
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="uppercase">{l s='size' mod='tmmegalayout'}</label>
                  <select name="background-size">
                    <option value=""></option>
                    <option {if isset($styles.background_size) && $styles.background_size == 'contain'}selected="selected"{/if} value="contain">
                      contain
                    </option>
                    <option {if isset($styles.background_size) && $styles.background_size == 'cover'}selected="selected"{/if} value="cover">
                      cover
                    </option>
                    <option {if isset($styles.background_size) && $styles.background_size == 'auto'}selected="selected"{/if} value="auto">
                      auto
                    </option>
                  </select>
                </div>
                <label class="uppercase">{l s='origin' mod='tmmegalayout'}</label>
                <select name="background-origin">
                  <option value=""></option>
                  <option {if isset($styles.background_origin) && $styles.background_origin == 'border-box'}selected="selected"{/if} value="border-box">
                    border-box
                  </option>
                  <option {if isset($styles.background_origin) && $styles.background_origin == 'content-box'}selected="selected"{/if} value="content-box">
                    content-box
                  </option>
                  <option {if isset($styles.background_origin) && $styles.background_origin == 'padding-box'}selected="selected"{/if} value="padding-box">
                    padding-box
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="sitems-2" class="tab-pane">
      <div class="clearfix border-settings">
        <div class="col-xs-12 col-sm-6 col-md-3">
          <label class="uppercase">{l s='type' mod='tmmegalayout'}</label>
          <div class="form-group">
            <label>{l s='top' mod='tmmegalayout'}</label>
            <select name="border-top-style">
              <option value=""></option>
              <option {if isset($styles.border_top_style) && $styles.border_top_style == 'none'}selected="selected"{/if} value="none">
                none
              </option>
              <option {if isset($styles.border_top_style) && $styles.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">
                dashed
              </option>
              <option {if isset($styles.border_top_style) && $styles.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">
                dotted
              </option>
              <option {if isset($styles.border_top_style) && $styles.border_top_style == 'double'}selected="selected"{/if} value="double">
                double
              </option>
              <option {if isset($styles.border_top_style) && $styles.border_top_style == 'solid'}selected="selected"{/if} value="solid">
                solid
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>{l s='right' mod='tmmegalayout'}</label>
            <select name="border-right-style">
              <option value=""></option>
              <option {if isset($styles.border_right_style) && $styles.border_right_style == 'none'}selected="selected"{/if} value="none">
                none
              </option>
              <option {if isset($styles.border_right_style) && $styles.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">
                dashed
              </option>
              <option {if isset($styles.border_right_style) && $styles.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">
                dotted
              </option>
              <option {if isset($styles.border_right_style) && $styles.border_right_style == 'double'}selected="selected"{/if} value="double">
                double
              </option>
              <option {if isset($styles.border_right_style) && $styles.border_right_style == 'solid'}selected="selected"{/if} value="solid">
                solid
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>{l s='bottom' mod='tmmegalayout'}</label>
            <select name="border-bottom-style">
              <option value=""></option>
              <option {if isset($styles.border_bottom_style) && $styles.border_bottom_style == 'none'}selected="selected"{/if} value="none">
                none
              </option>
              <option {if isset($styles.border_bottom_style) && $styles.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">
                dashed
              </option>
              <option {if isset($styles.border_bottom_style) && $styles.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">
                dotted
              </option>
              <option {if isset($styles.border_bottom_style) && $styles.border_bottom_style == 'double'}selected="selected"{/if} value="double">
                double
              </option>
              <option {if isset($styles.border_bottom_style) && $styles.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">
                solid
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>{l s='left' mod='tmmegalayout'}</label>
            <select name="border-left-style">
              <option value=""></option>
              <option {if isset($styles.border_left_style) && $styles.border_left_style == 'none'}selected="selected"{/if} value="none">
                none
              </option>
              <option {if isset($styles.border_left_style) && $styles.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">
                dashed
              </option>
              <option {if isset($styles.border_left_style) && $styles.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">
                dotted
              </option>
              <option {if isset($styles.border_left_style) && $styles.border_left_style == 'double'}selected="selected"{/if} value="double">
                double
              </option>
              <option {if isset($styles.border_left_style) && $styles.border_left_style == 'solid'}selected="selected"{/if} value="solid">
                solid
              </option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
          <label class="uppercase">{l s='width' mod='tmmegalayout'}</label>
          <div class="form-group">
            <label>{l s='top' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-top-width" value="{if isset($styles.border_top_width) && $styles.border_top_width}{$styles.border_top_width|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='right' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-right-width" value="{if isset($styles.border_right_width) && $styles.border_right_width}{$styles.border_right_width|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='bottom' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-bottom-width" value="{if isset($styles.border_bottom_width) && $styles.border_bottom_width}{$styles.border_bottom_width|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='left' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-left-width" value="{if isset($styles.border_left_width) && $styles.border_left_width}{$styles.border_left_width|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
          <label class="uppercase">{l s='radius' mod='tmmegalayout'}</label>
          <div class="form-group">
            <label>{l s='top right' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-top-right-radius" value="{if isset($styles.border_top_right_radius) && $styles.border_top_right_radius}{$styles.border_top_right_radius|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='bottom right' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-bottom-right-radius" value="{if isset($styles.border_bottom_right_radius) && $styles.border_bottom_right_radius}{$styles.border_bottom_right_radius|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='bottom left' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-bottom-left-radius" value="{if isset($styles.border_bottom_left_radius) && $styles.border_bottom_left_radius}{$styles.border_bottom_left_radius|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="form-group">
            <label>{l s='top left' mod='tmmegalayout'}
              <small><i>(px, em)</i></small>
            </label>
            <input class="form-control" data-type="dmns" type="text" name="border-top-left-radius" value="{if isset($styles.border_top_left_radius) && $styles.border_top_left_radius}{$styles.border_top_left_radius|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
          <label class="uppercase">{l s='color' mod='tmmegalayout'}</label>
          <div class="form-group">
            <label>{l s='top' mod='tmmegalayout'}</label>
            <div class="input-group">
              <input data-hex="true" data-type="clr" class="form-control color tmml_color_input" name="border-top-color" value="{if isset($styles.border_top_color) && $styles.border_top_color}{$styles.border_top_color|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
          </div>
          <div class="form-group">
            <label>{l s='right' mod='tmmegalayout'}</label>
            <div class="input-group">
              <input data-hex="true" data-type="clr" class="form-control color tmml_color_input" name="border-right-color" value="{if isset($styles.border_right_color) && $styles.border_right_color}{$styles.border_right_color|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
          </div>
          <div class="form-group">
            <label>{l s='bottom' mod='tmmegalayout'}</label>
            <div class="input-group">
              <input data-hex="true" data-type="clr" class="form-control color tmml_color_input" name="border-bottom-color" value="{if isset($styles.border_bottom_color) && $styles.border_bottom_color}{$styles.border_bottom_color|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
          </div>
          <div class="form-group">
            <label>{l s='left' mod='tmmegalayout'}</label>
            <div class="input-group">
              <input data-hex="true" data-type="clr" class="form-control color tmml_color_input" name="border-left-color" value="{if isset($styles.border_left_color) && $styles.border_left_color}{$styles.border_left_color|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="sitems-3" class="tab-pane">
      <div class="other-settings clearfix">
        <label class="uppercase">{l s='other' mod='tmmegalayout'}</label>
        <div class="row">
          <label class="col-xs-12 col-md-2 lh-fix">{l s='Box shadow' mod='tmmegalayout'}</label>
          <div class="col-xs-12 col-md-6">
            <input class="form-control" data-type="shdw" type="text" name="box-shadow" value="{if isset($styles.box_shadow) && $styles.box_shadow}{$styles.box_shadow|escape:'htmlall':'UTF-8'}{/if}"/>
            <p class="help-block no-indent">{l s='example: 0px 0px 0px 0px rgba(0,0,0,0.75)' mod='tmmegalayout'}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="id_unique" value="{$id_unique|escape:'htmlall':'UTF-8'}"/>
  <div class="tmmegalayout-style-btns">
    <a href="#" class="save-styles btn btn-success">{l s='Save' mod='tmmegalayout'}</a>
    <a href="#" class="clear-styles btn btn-link">{l s='Clear styles' mod='tmmegalayout'}</a>
  </div>
</div>