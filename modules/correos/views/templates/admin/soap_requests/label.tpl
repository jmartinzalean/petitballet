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

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns="http://www.correos.es/iris6/services/preregistroetiquetas">
    <soapenv:Header/>
    <soapenv:Body>
        <SolicitudEtiqueta>
            <fechaOperacion>{$operation_date|escape:'htmlall':'UTF-8'}</fechaOperacion>
            {if !empty($correos_config.correos_key)}
                <CodEtiquetador>{$correos_config.correos_key|escape:'htmlall':'UTF-8'}</CodEtiquetador>
            {elseif !empty($correos_config.contract_number) and !empty($correos_config.client_number)}
                <NumContrato>{$correos_config.contract_number|escape:'htmlall':'UTF-8'}</NumContrato>
                <NumCliente>{$correos_config.client_number|escape:'htmlall':'UTF-8'}</NumCliente>
            {/if}
            <CodEnvio>{$sipping_code|escape:'htmlall':'UTF-8'}</CodEnvio>
            <Care>000000</Care>
            <ModDevEtiqueta>2</ModDevEtiqueta>
        </SolicitudEtiqueta>
    </soapenv:Body>
</soapenv:Envelope>