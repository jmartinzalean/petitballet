{*
* NOTA SOBRE LA LICENCIA DE USO DEL SOFTWARE
* 
* El uso de este software está sujeto a las Condiciones de uso de software que
* se incluyen en el paquete en el documento "Aviso Legal.pdf". También puede
* obtener una copia en la siguiente url:
* http://www.redsys.es/wps/portal/redsys/publica/areadeserviciosweb/descargaDeDocumentacionYEjecutables
* 
* Redsys es titular de todos los derechos de propiedad intelectual e industrial
* del software.
* 
* Quedan expresamente prohibidas la reproducción, la distribución y la
* comunicación pública, incluida su modalidad de puesta a disposición con fines
* distintos a los descritos en las Condiciones de uso.
* 
* Redsys se reserva la posibilidad de ejercer las acciones legales que le
* correspondan para hacer valer sus derechos frente a cualquier infracción de
* los derechos de propiedad intelectual y/o industrial.
* 
* Redsys Servicios de Procesamiento, S.L., CIF B85955367
*}

{if $smarty.const._PS_VERSION_ >= 1.6}

<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="bankwire" href="javascript:$('#redsys_form').submit();" title="{l s='Conectar con el TPV' mod='redsys'}">	
				<img src="{$module_dir|escape:'htmlall'}img/tarjetas.png" alt="{l s='Conectar con el TPV' mod='redsys'}" height="48" />
				{l s='Pagar con tarjeta' mod='redsys'}
			</a>
		</p>
	</div>
</div>
{else}
<p class="payment_module">
	<a class="bankwire" href="javascript:$('#redsys_form').submit();" title="{l s='Conectar con el TPV' mod='redsys'}">	
		<img src="{$module_dir|escape:'htmlall'}img/tarjetas.png" alt="{l s='Conectar con el TPV' mod='redsys'}" height="48" />
		{l s='Pagar con tarjeta' mod='redsys'}
	</a>
</p>
{/if}

<form action="{$urltpv|escape:'htmlall'}" method="post" id="redsys_form" class="hidden">	
	<input type="hidden" name="Ds_SignatureVersion" value="{$signatureVersion|escape:'htmlall'}" />
	<input type="hidden" name="Ds_MerchantParameters" value="{$parameter|escape:'htmlall'}" />
	<input type="hidden" name="Ds_Signature" value="{$signature|escape:'htmlall'}" />
</form>