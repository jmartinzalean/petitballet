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
<p class="payment_module">
	<a href="{$link->getModuleLink('obsredsys', 'payment', ['method' => $tpv_id], true)|escape:'htmlall':'UTF-8'}" title="{l s='Pay with your VISA / MasterCard / 4B' mod='obsredsys'}">
		<img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/obsredsys/views/img/payment.png" alt="{l s='Pay with your VISA / MasterCard / 4B' mod='obsredsys'}" />
		{if $payment_text}{$payment_text|escape:'htmlall':'UTF-8'}{else}{l s='Pay with your VISA / MasterCard / 4B' mod='obsredsys'}{/if}
	</a>
</p>

