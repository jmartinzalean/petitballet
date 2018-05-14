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
 
<table style="border:2px solid #000; width: 100%" cellspacing="0" cellpadding="3">
	<tr>
		<td rowspan="3" style="width:33.33%; font-size: 10pt;background-color:#fff;border:1px solid #fff;">
      <br> <br> 
        <table style="width: 100%"> 
         <tr>
         <td style=" text-align: center; font-size: 14pt; font-weight:bold;background-color:#fff;border:1px solid #fff;">
         CORREOS
         </td>
        </tr>
         </table>
      </td>
		<td style="width:66.66%; text-align:left;font-size: 9pt;font-weight:bold; text-align: left;background-color:#fff;border:1px solid #fff;">CLIENTE: {$sender|escape:'htmlall':'UTF-8'}</td>
	</tr>
	<tr>
		<td style="text-align:left;font-size: 9pt; font-weight:bold; text-align: left;background-color:#fff;border:1px solid #fff;">CÓDIGO DE CLIENTE: {$client_code|escape:'htmlall':'UTF-8'}</td>
	</tr>
	<tr>
		<td style="text-align:left;font-size: 9pt; font-weight:bold; text-align: left;background-color:#fff;border:1px solid #fff;">FECHA: {$date|escape:'htmlall':'UTF-8'}</td>
	</tr>
</table>