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

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:prer="http://www.correos.es/iris6/services/preregistroetiquetas">
    <soapenv:Header/>
    <soapenv:Body>
        <prer:SolicitudDocumentacionAduaneraCN23CP71>
            <prer:codCertificado>{$shipping_code|escape:'htmlall':'UTF-8'}</prer:codCertificado>
        </prer:SolicitudDocumentacionAduaneraCN23CP71>
    </soapenv:Body>
</soapenv:Envelope>