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

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://www.correos.es/ServicioPuertaAPuertaBackOffice" xmlns:ser1="http://www.correos.es/ServicioPuertaAPuerta">
<soapenv:Header/>
<soapenv:Body>
  <ser:SolicitudRegistroRecogida>
    <ReferenciaRelacionPaP>{$collection_reference|escape:'htmlall':'UTF-8'}</ReferenciaRelacionPaP>
    <TipoOperacion>ALTA</TipoOperacion>
    <FechaOperacion>{$smarty.now|date_format:"%d-%m-%Y %H:%M:%S"|escape:'html':'UTF-8'}</FechaOperacion>
    <NumContrato>{$correos_config.contract_number|escape:'htmlall':'UTF-8'}</NumContrato>
    <NumDetallable>{$correos_config.client_number|escape:'htmlall':'UTF-8'}</NumDetallable>
    <CodSistema></CodSistema>
    <CodUsuario>{$correos_config.correos_vuser|escape:'htmlall':'UTF-8'}</CodUsuario>
    <ser1:Recogida>
      <ReferenciaRecogida>{$collection_reference|escape:'html':'UTF-8'}</ReferenciaRecogida>
      <FecRecogida>{$collection_req_date|escape:'html':'UTF-8'}</FecRecogida>
      <HoraRecogida>{$collection_req_time|escape:'html':'UTF-8'}</HoraRecogida>
      <CodAnexo>091</CodAnexo>
      <NomNombreViaRec>{$collection_req_address|escape:'html':'UTF-8'}</NomNombreViaRec>
      <NomLocalidadRec>{$collection_req_city|escape:'html':'UTF-8'}</NomLocalidadRec>
      <CodigoPostalRecogida>{$collection_req_postalcode|escape:'html':'UTF-8'}</CodigoPostalRecogida>
      <DesPersonaContactoRec>{$collection_req_name|escape:'html':'UTF-8'}</DesPersonaContactoRec>
      <DesTelefContactoRec>{$collection_req_mobile_phone|escape:'html':'UTF-8'}</DesTelefContactoRec>
      <DesEmailContactoRec>{$collection_req_email|escape:'html':'UTF-8'}</DesEmailContactoRec>
      <DesObservacionRec>{$collection_req_comments|escape:'html':'UTF-8'}</DesObservacionRec>
      <NumEnvios>{$collection_req_pieces|escape:'html':'UTF-8'}</NumEnvios>
      <NumPeso>{$collection_req_weight|escape:'html':'UTF-8'}</NumPeso>
      <TipoPesoVol>{$collection_size|escape:'htmlall':'UTF-8'}</TipoPesoVol>
      <IndImprimirEtiquetas>{$label_print|escape:'html':'UTF-8'}</IndImprimirEtiquetas>
      <IndDevolverCodSolicitud>S</IndDevolverCodSolicitud>
      {if $label_print == 'S'}
      <ser1:ListaCodEnvios>
        {foreach from=$orders key=id_order item=shipping_code}
            <CodigoEnvio>{$shipping_code|escape:'html':'UTF-8'}</CodigoEnvio>
        {/foreach}
      </ser1:ListaCodEnvios>
      {/if}
    </ser1:Recogida>
  </ser:SolicitudRegistroRecogida>
</soapenv:Body>
</soapenv:Envelope>
