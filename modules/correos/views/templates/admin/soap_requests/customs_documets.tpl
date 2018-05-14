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
        <prer:SolicitudDocumentacionAduanera>
            <prer:TipoESAD>{$esad_type|escape:'html':'UTF-8'}</prer:TipoESAD>
            <prer:NumContrato>{$correos_config.contract_number|strip|escape:'html':'UTF-8'}</prer:NumContrato>
            <prer:NumCliente>{$correos_config.client_number|strip|escape:'html':'UTF-8'}</prer:NumCliente>
            <prer:CodEtiquetador>{$correos_config.correos_key|strip|escape:'html':'UTF-8'}</prer:CodEtiquetador>
            <prer:Provincia>{if $countryISO eq 'ES'}{$address->postcode|substr:0:2|escape:'html':'UTF-8'}{/if}</prer:Provincia>
            <prer:PaisDestino>{$countryISO|escape:'html':'UTF-8'}</prer:PaisDestino>
            <prer:NombreDestinatario>{$address->firstname|escape:'html':'UTF-8'} {$address->lastname|escape:'html':'UTF-8'}</prer:NombreDestinatario>
            <prer:NumeroEnvios>{$number_pieces|escape:'html':'UTF-8'}</prer:NumeroEnvios>
            <prer:LocalidadFirma>{$sender->localidad|escape:'html':'UTF-8'}</prer:LocalidadFirma>
        </prer:SolicitudDocumentacionAduanera>
    </soapenv:Body>
</soapenv:Envelope>