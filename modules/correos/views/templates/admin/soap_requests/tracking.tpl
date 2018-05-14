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

<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <ConsultaLocalizacionEnviosFases xmlns="ServiciosWebLocalizacionMI/">
            <XMLin><![CDATA[<?xml version="1.0" encoding="utf-8" ?>
            <ConsultaXMLin Idioma="1" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <Consulta><Codigo>{$shipping_code|escape:'htmlall':'UTF-8'}</Codigo></Consulta></ConsultaXMLin>]]></XMLin>
        </ConsultaLocalizacionEnviosFases>
    </soap:Body>
</soap:Envelope>