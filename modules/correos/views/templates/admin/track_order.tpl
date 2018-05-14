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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seguimiento de Env&iacute;os</title>
<style>
body { 
padding:10px; font-size:14px; font-family:Verdana, Geneva, sans-serif; text-align:center
}
table th { 
    color:#FFF; background-color:#6895C6
}
table tr { 
    background-color:#EEF3F9
    }
table tr:nth-child(2n+1) { 
    background-color:#D8E3F1
}

</style>
</head>
<body>
<table width="100%" cellpadding="3" >
{if $has_tracking}
    <tr>
		<th colspan="2">Seguimiento de Env&iacute;o<br/>{$smarty.get.codenv|escape:'htmlall':'UTF-8'}</th>
    </tr>
    <tr style="background-color:#6895C6; color:#FFF">
        <td style="width:50%">Estado</td>
        <td style="width:50%">Fecha</td>
  	</tr>
    {foreach from=$tracking item=t} 
        <tr>
            <td>{$t->Estado|escape:'htmlall':'UTF-8'}</td>
			<td>{$t->Fecha|escape:'htmlall':'UTF-8'}</td>
		</tr>   
    {/foreach}
{else}
    <tr>
		<th colspan="2">{$tracking|escape:'htmlall':'UTF-8'}</th>
	</tr>
{/if}
</table>
</body>
</html>