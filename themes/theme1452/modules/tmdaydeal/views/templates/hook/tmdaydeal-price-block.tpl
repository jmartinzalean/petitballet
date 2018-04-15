{*
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
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
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($daydeal_products_extra["data_end"]) && $daydeal_products_extra["data_end"]}
  {assign var='data_end' value=$daydeal_products_extra["data_end"]}
  <div class="block products_block daydeal-box">
    <div data-countdown="{$data_end|escape:'htmlall':'UTF-8'}"></div>
  </div>
  <script type="text/javascript">
    var tmdd_msg_days = "{l s='days' mod='tmdaydeal' js=1}";
    var tmdd_msg_hr = "{l s='hrs' mod='tmdaydeal' js=1}";
    var tmdd_msg_min = "{l s='mins' mod='tmdaydeal' js=1}";
    var tmdd_msg_sec = "{l s='secs' mod='tmdaydeal' js=1}";
      $("[data-countdown]").each(function() {
        var $this = $(this), finalDate = $(this).data("countdown");
        $this.countdown(finalDate, function(event) {
          $this.html(event.strftime('<span><span>%D</span>'+tmdd_msg_days+'</span><span><span>%H</span>'+tmdd_msg_hr+'</span><span><span>%M</span>'+tmdd_msg_min+'</span><span><span>%S</span>'+tmdd_msg_sec+'</span>'));
        });
      });
  </script>
{/if}