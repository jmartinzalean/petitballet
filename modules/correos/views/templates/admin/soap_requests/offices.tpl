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

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ejb="http://ejb.mauo.correos.es">
    <soapenv:Header/>
    <soapenv:Body>
        <ejb:localizadorConsulta>
            <ejb:codigoPostal>{$postcode|escape:'htmlall':'UTF-8'}</ejb:codigoPostal>
        </ejb:localizadorConsulta>
    </soapenv:Body>
</soapenv:Envelope>