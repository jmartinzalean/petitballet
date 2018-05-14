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
<div style="padding-25px;">
	<p style="text-align:center"><img src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/loading.gif" alt="{l s='Loading...' mod='obsredsys'}" width="32" height="32" /></p>
	<p style="text-align:center">{l s='Espere por favor, le estamos redireccionando a la tienda...' mod='obsredsys'}</p>
</div>
<script>
	setTimeout(function() {
		window.parent.location = "{$url nofilter}";
	}, 1000);
</script>