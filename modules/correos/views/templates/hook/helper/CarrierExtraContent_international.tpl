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
<div class="correos-carrer-content international">
{if $params.show_customs_message}
<div id="aduana_content" style="font-weight:bold; color:red">{l s='The shipment involves customs procedures. Shipping price may increase' mod='correos'}</div>
{/if}
{l s='Check your mobile phone' mod='correos'}: <input type="text" id="cr_international_mobile{$params.id_carrier|intval}" name="cr_international_mobile" value="{$params.mobile|escape:'htmlall':'UTF-8'}" onchange="Correos.updateInternationalMobile({$params.id_carrier|intval});" onkeyup="Correos.tooglePaymentModules();" />
</div>