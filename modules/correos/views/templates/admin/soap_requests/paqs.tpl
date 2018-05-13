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

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cor="http://www.correos.es/paq/correospaq">
    <soapenv:Header/>
    <soapenv:Body>
    {if $params.action eq 'GetCorreosPaqs'} 
        <cor:getFavorites>
        <user>{$params.user|escape:'html':'UTF-8'}</user>
        <ip>{$ip|escape:'html':'UTF-8'}</ip>
        </cor:getFavorites>
    {elseif $params.action eq 'getCitypaqs'}
        <cor:getCitypaqs>
            <{$params.searchby|escape:'html':'UTF-8'}>{$params.searchvalue|strip|escape:'html':'UTF-8'}</{$params.searchby|escape:'html':'UTF-8'}>
        </cor:getCitypaqs>
    {/if}
    </soapenv:Body>
</soapenv:Envelope>
