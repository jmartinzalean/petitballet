/*
* 2002-2016 TemplateMonster
*
* TM Collections
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
*/

$(document).ready(function(e) {
  tmcl_row_3 = '';
  tmcl_row_3 += '<li class="tmcl_popup_item col-sm-6 tmcl_row_3">';
    tmcl_row_3 += '<div class="popup_store_logo"><img class="logo img-responsive" src="'+logo_url+'" /></div>';
    tmcl_row_3 += '<h5></h5>';
    tmcl_row_3 += '<ul id="tmcl_row_3" class="clearfix tmcl_row_3 items">';
      tmcl_row_3 += '<li class="col-xs-12 col-sm-6 col-md-6 col-lg-3 item"><div class="content"></div></li>';
      tmcl_row_3 += '<li class="col-xs-12 col-sm-6 col-md-6 col-lg-3 item"><div class="content"></div></li>';
      tmcl_row_3 += '<li class="col-xs-12 col-sm-6 col-md-6 col-lg-3 item"><div class="content"></div></li>';
      tmcl_row_3 += '<li class="col-xs-12 col-sm-6 col-md-6 col-lg-3 item"><div class="content"></div></li>';
    tmcl_row_3 += '</ul>';
  tmcl_row_3 += '</li>';
  tmcl_row_3 += '<input type="hidden" name="id_layout" value="3" />';
  tmcl_layouts.push({name : 'tmcl_row_3', value : tmcl_row_3});
});