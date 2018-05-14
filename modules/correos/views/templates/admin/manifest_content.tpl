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
 
<table style="width: 100%; border: 2px solid #000;">
   <tr>
		<th style="width:24%; font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 8pt;">N&#186; ENVÍO</th>
		<th style="width:36%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 8pt;">DEST./CONSIG</th>
		<th style="width:7%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 7pt;text-align: center">BULTOS</th>
		<th style="width:7%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 7pt;text-align: center">KILOS</th>
		<th style="width:8%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 7pt;text-align: center">REEMB.</th>
		<th style="width:8%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 7pt;text-align: center">SEGURO</th>
		<th style="width:10%;font-weight:bold;background-color:#fff;border:1px solid #fff; font-size: 7pt;text-align: center">VAL. AÑADIDOS</th>
	</tr>
</table>
<table><tr><td style="font-size: 3pt;">&nbsp;</td></tr></table>
{foreach from=$records item=record}
<table style="width: 100%" cellspacing="0" cellpadding="3" style="border: 2px solid #000;">
    <tr>
      <td style="width:24%;font-size: 7pt;background-color:#fff;border:1px solid #fff;">
        {$record.order.shipment_code|escape:'htmlall':'UTF-8'}
      </td>
      <td style="width:36%;font-size: 7pt;background-color:#fff;border:1px solid #fff;">
        {$record.order.firstname|escape:'htmlall':'UTF-8'} {$record.order.lastname|escape:'htmlall':'UTF-8'}<br>
        {$record.address->address1|escape:'htmlall':'UTF-8'} {$record.address->postcode|escape:'htmlall':'UTF-8'} {$record.address->city|escape:'htmlall':'UTF-8'}
      </td>
      <td style="width:7%;font-size: 8pt;background-color:#fff;border:1px solid #fff;text-align: center">1</td>
      <td style="width:7%;font-size: 8pt;background-color:#fff;border:1px solid #fff;text-align: center">
        {$record.order.weight|escape:'htmlall':'UTF-8'}
      </td>
      <td style="width:8%;font-size: 8pt;background-color:#fff;border:1px solid #fff;text-align: center">
        {$record.cashondelivery|escape:'htmlall':'UTF-8'}&euro;
      </td>
      <td style="width:8%;font-size: 8pt;background-color:#fff;border:1px solid #fff;text-align: center">
      {$record.order.insurance|escape:'htmlall':'UTF-8'}&euro;
      </td> 
      <td style="width:10%;font-size: 8pt;background-color:#fff;border:1px solid #fff;text-align: center"></td>
    </tr>
</table>
<table><tr><td style="font-size: 3pt;">&nbsp;</td></tr></table>
      
{/foreach}
{foreach from=$cr_carriers item=carrier}
<table style="width: 100%; border: 2px solid #000;" cellspacing="0" cellpadding="3" >
      <tr>
      <td style="width:24%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC">{$carrier.title|escape:'htmlall':'UTF-8'}</td>
      <td style="width:36%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC"></td>
      <td style="width:7%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC;text-align: center">{$carrier.total_packeges|escape:'htmlall':'UTF-8'}</td>
      <td style="width:7%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC;text-align: center">{$carrier.total_weight|floatval}</td>
      <td style="width:8%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC;text-align: center">{$carrier.total_cashondelivery|floatval}&euro;</td>
      <td style="width:8%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC;text-align: center">{$carrier.total_insurance|floatval}&euro;</td>
      <td style="width:10%;font-size:8pt;background-color:#CCCCCC;border:1px solid #CCCCCC;text-align: center"></td>
      </tr>
</table>
<table>
<tr><td style="font-size: 3pt;">&nbsp;</td></tr>
</table>
{/foreach}
	  
<table style="width: 100%; border: 2px solid #000;">
	<tr>
		<td style="width:24%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 8pt;">Totales</td>
		<td style="width:36%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 8pt;"></td>
		<td style="width:7%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 7pt;text-align: center">{$total_packeges|escape:'htmlall':'UTF-8'}</td>
		<td style="width:7%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 7pt;text-align: center">{$total_weight|escape:'htmlall':'UTF-8'}</td>
		<td style="width:8%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 7pt;text-align: center">{$total_cashondelivery|floatval}€</td>
		<td style="width:8%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 7pt;text-align: center">{$total_insurance|floatval}€</td>
		<td style="width:10%;font-weight:bold;background-color:#CCCCCC;border:1px solid #CCCCCC; font-size: 7pt;text-align: center"></td>
	</tr>
	</table>