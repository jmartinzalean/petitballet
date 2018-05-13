{*
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 *}
{capture name=path}<a href="{$link->getPageLink('order.php', true)|escape:'html':'UTF-8'}">{l s='Your shopping cart' mod='obsredsys'}</a><span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Credit card payment' mod='obsredsys'}{/capture}
<p>&nbsp;</p>
<br/>
<div class="pasatDiv">
	<div class="passatBody">
		<div class="separador"></div>
		<iframe src="" name="tpv" width="{$frameWidth|escape:'htmlall':'UTF-8'}" height="650" scrolling="auto" frameborder="0" transparency>
			<p>{l s='Your browser does not support iframes.' mod='obsredsys'}</p>
		</iframe>
		<form action="{$url_tpvv|escape:'UTF-8'}" name="compra" method="post" enctype="application/xwww-form-urlencoded" {if $showInIframe}target="tpv"{/if}>
			<input name="Ds_SignatureVersion" type="hidden" value="HMAC_SHA256_V1" autocomplete="off" />
			<input name="Ds_MerchantParameters" type="hidden" value="{$merchantParameters|escape:'htmlall':'UTF-8'}" autocomplete="off" />
			<input name="Ds_Signature" type="hidden" value="{$signature|escape:'htmlall':'UTF-8'}" autocomplete="off" />
		</form>
		<script>
			$(function() {
				{* $('#left_column').hide();
				$('#right_column').hide();
				$('#center_column').css('width', '100%'); *}
				document.compra.submit();
			} );
		</script>
	</div>
</div>
<p><a href="javascript:history.go(-1);">{l s='Go back' mod='obsredsys'}</a>
<div class="clear"></div>
