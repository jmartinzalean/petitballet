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
<style>
 	p.payment_module a.payment{
      background: url('{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/obsredsys/views/img/payment_unbrand.png') 15px 12px no-repeat transparent; }
    p.payment_module a.payment:after {
      display: block;
      content: "\f054";
      position: absolute;
      right: 15px;
      margin-top: -11px;
      top: 50%;
      font-family: "FontAwesome";
      font-size: 25px;
      height: 22px;
      width: 14px;
      color: #777; }
    p.payment_module a:hover {
      background-color: #f6f6f6; }   	
</style> 
 
<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="payment"
			   title="{l s='Pay with your VISA / MasterCard / 4B' mod='obsredsys'}" 
			   href="{$link->getModuleLink('obsredsys', 'payment', ['method' => $tpv_id], true)|escape:'htmlall':'UTF-8'}">
				{if $payment_text}{$payment_text|escape:'htmlall':'UTF-8'}{else}{l s='Pay with your VISA / MasterCard / 4B' mod='obsredsys'}{/if}
			</a>
		</p>
	</div>	
</div>