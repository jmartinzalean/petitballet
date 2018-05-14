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
        <PreregistroEnvio>
            <FechaOperacion>{$smarty.now|date_format:"%d-%m-%Y %H:%M:%S"|escape:'html':'UTF-8'}</FechaOperacion>
            <CodEtiquetador>{$correos_config.correos_key|escape:'html':'UTF-8'}</CodEtiquetador>
            <ModDevEtiqueta>2</ModDevEtiqueta>
            <Care>000000</Care>
            <TotalBultos>1</TotalBultos>
            <Remitente>
                <Identificacion>
                    <Nombre>{$shipping_val.sender_firstname|escape:'html':'UTF-8'}</Nombre>
                    <Apellido1>{$shipping_val.sender_lastname1|escape:'html':'UTF-8'}</Apellido1>
                    <Apellido2>{$shipping_val.sender_lastname2|escape:'html':'UTF-8'}</Apellido2>
                    <Nif>{$shipping_val.sender_dni|escape:'html':'UTF-8'}</Nif>
                    <Empresa>{$shipping_val.sender_company|escape:'html':'UTF-8'}</Empresa>
                    <PersonaContacto>{$shipping_val.sender_contact_person|escape:'html':'UTF-8'}</PersonaContacto>
                </Identificacion>
                <DatosDireccion>
                    <Direccion>{$shipping_val.sender_address|escape:'html':'UTF-8'}</Direccion>
                    <Localidad>{$shipping_val.sender_city|escape:'html':'UTF-8'}</Localidad>
                    <Provincia>{$shipping_val.sender_state|escape:'html':'UTF-8'}</Provincia>
                </DatosDireccion>
                <CP>{$shipping_val.sender_cp|escape:'html':'UTF-8'}</CP>
                <Telefonocontacto>{$shipping_val.sender_phone|escape:'html':'UTF-8'}</Telefonocontacto>
                <Email>{$shipping_val.sender_email|escape:'html':'UTF-8'}</Email>
                <DatosSMS>
                    <NumeroSMS>{$shipping_val.sender_mobile|escape:'html':'UTF-8'}</NumeroSMS>
                    <Idioma>1</Idioma>
                </DatosSMS>
            </Remitente> 
            <Destinatario> 
                <Identificacion> 
                    {if empty($shipping_val.customer_company)}
                    <Nombre>{$shipping_val.customer_firstname|escape:'html':'UTF-8'}</Nombre>
                    <Apellido1>{$shipping_val.customer_lastname1|escape:'html':'UTF-8'}</Apellido1>
                    <Apellido2>{$shipping_val.customer_lastname2|escape:'html':'UTF-8'}</Apellido2>
                    {/if}
                    {if !empty($shipping_val.customer_company)}
                    <Empresa>{$shipping_val.customer_company|escape:'html':'UTF-8'}</Empresa>
                    <PersonaContacto>{$shipping_val.customer_firstname|escape:'html':'UTF-8'} {$shipping_val.customer_lastname1|escape:'html':'UTF-8'} {$shipping_val.customer_lastname2|escape:'html':'UTF-8'}</PersonaContacto>
                    {/if}
                </Identificacion>
                <DatosDireccion>
                    <Direccion>{$shipping_val.delivery_address|escape:'html':'UTF-8'}</Direccion>
                    <Localidad>{$shipping_val.delivery_city|escape:'html':'UTF-8'}</Localidad>
                    <Provincia>{$shipping_val.delivery_state|escape:'html':'UTF-8'}</Provincia>
                </DatosDireccion>
                <CP>{$shipping_val.delivery_postcode|escape:'html':'UTF-8'}</CP>
                <ZIP>{$shipping_val.delivery_zip|escape:'html':'UTF-8'}</ZIP>
                <Pais>{$shipping_val.country_iso|escape:'html':'UTF-8'}</Pais>
                <Telefonocontacto>{$shipping_val.phone|escape:'html':'UTF-8'}</Telefonocontacto>
                <Email>{$shipping_val.email|escape:'html':'UTF-8'}</Email>
                <DatosSMS>
                    <NumeroSMS>{$shipping_val.mobile|escape:'html':'UTF-8'}</NumeroSMS>
                    <Idioma>{$shipping_val.mobile_lang|escape:'html':'UTF-8'}</Idioma>
                </DatosSMS>
            </Destinatario> 
            <Envio>
                <CodProducto>{$carrier_code|escape:'html':'UTF-8'}</CodProducto>
                <ReferenciaCliente>{$shipping_val.order_reference|escape:'html':'UTF-8'}</ReferenciaCliente>
                <TipoFranqueo>FP</TipoFranqueo>
                <ModalidadEntrega>{$shipping_val.delivery_mode|escape:'html':'UTF-8'}</ModalidadEntrega>
                <OficinaElegida>{$shipping_val.id_office|escape:'html':'UTF-8'}</OficinaElegida>
                <Pesos>
                    <Peso>
                        <TipoPeso>R</TipoPeso>
                        <Valor>{$shipping_val.weight|escape:'html':'UTF-8'}</Valor>
                    </Peso>
                </Pesos>
                <Largo>{$shipping_val.long|escape:'html':'UTF-8'}</Largo>
                <Alto>{$shipping_val.height|escape:'html':'UTF-8'}</Alto>
                <Ancho>{$shipping_val.width|escape:'html':'UTF-8'}</Ancho>
                <ValoresAnadidos>
                    <ImporteSeguro>{$shipping_val.insurance_value|escape:'html':'UTF-8'}</ImporteSeguro>
                    <Reembolso> 
                        <TipoReembolso>{$shipping_val.cashondelivery_type|escape:'html':'UTF-8'}</TipoReembolso>
                        <Importe>{$shipping_val.cashondelivery_value|escape:'html':'UTF-8'}</Importe>
                        <NumeroCuenta>{$shipping_val.cashondelivery_bankac|escape:'html':'UTF-8'}</NumeroCuenta>
                    </Reembolso>
                    <FranjaHorariaConcertada>{$shipping_val.id_schedule|escape:'html':'UTF-8'}</FranjaHorariaConcertada>
                </ValoresAnadidos>
                <Aduana>
                    <TipoEnvio>{$shipping_val.customs_type|escape:'html':'UTF-8'}</TipoEnvio>
                    <EnvioComercial>{$shipping_val.customs_comercial|escape:'html':'UTF-8'}</EnvioComercial>
                    <FacturaSuperiora500>{$shipping_val.customs_fra500|escape:'html':'UTF-8'}</FacturaSuperiora500>
                    <DUAConCorreos>{$shipping_val.customs_duacorreos|escape:'html':'UTF-8'}</DUAConCorreos>
                    <DescAduanera>
                        <DATOSADUANA>
                            <Cantidad>{$shipping_val.customs_product_qty|escape:'html':'UTF-8'}</Cantidad>
                            <Descripcion>{$shipping_val.customs_description|escape:'html':'UTF-8'}</Descripcion>
                            <Pesoneto>{$shipping_val.customs_product_weight|escape:'html':'UTF-8'}</Pesoneto>
                            <Valorneto>{$shipping_val.customs_product_value|escape:'html':'UTF-8'}</Valorneto>
                        </DATOSADUANA>
                    </DescAduanera>
                </Aduana>
                <Observaciones1>{$shipping_val.observations|escape:'html':'UTF-8'}</Observaciones1>
                <Observaciones2>{$shipping_val.delivery_address2|escape:'html':'UTF-8'}</Observaciones2>
                <AdmisionHomepaq>{$shipping_val.homepaq_admission|escape:'html':'UTF-8'}</AdmisionHomepaq>
                <CodigoHomepaq>{$shipping_val.homepaq_code|escape:'html':'UTF-8'}</CodigoHomepaq>
                <ToquenIdCorPaq>{$shipping_val.homepaq_token|escape:'html':'UTF-8'}</ToquenIdCorPaq>
                <InstruccionesDevolucion>D</InstruccionesDevolucion>
            </Envio>
        </PreregistroEnvio>
    </soapenv:Body>
</soapenv:Envelope>