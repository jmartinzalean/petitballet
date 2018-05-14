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
    <soapenv:Body>
        <cor:getUrl>
            <user>{$params.user|escape:'html':'UTF-8'}</user>
            <operationType>Add_Favorite</operationType>
            <urlCallBack>{$url_callback|escape:'html':'UTF-8'}</urlCallBack>
            <favorite>{$params.favorite|escape:'html':'UTF-8'}</favorite>
            <integrationMode>I</integrationMode>
        </cor:getUrl>
    </soapenv:Body>
</soapenv:Envelope>