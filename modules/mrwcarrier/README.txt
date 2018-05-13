// ********************************************************************************* //
//  MODULO MRW - PRESTASHOP
// ********************************************************************************* //

1.  El módulo hace el uso de la libreria SOAP de PHP5 para conexion y generacion
    de envios entre Prestashop y el WebService SAGEC de MRW
2.  Al instalar el modulo mrwcarrier se crea una carpeta en la ruta 
    downloads/ticket_mrw, asegurese de que esta carpeta tiene los permisos de
    escritura, caso contrario no podrán crearse los archivos de las etiquetas.
3.  Para la generación de envios es necesario que el modulo este configurado
    debidamente con los estados de envio y posterior al envio.

// ********************************************************************************* //
//  CHANGELOG - REGISTRO DE CAMBIOS
// ********************************************************************************* //

25/01/2018 4.6.2 --> 4.6.3
 Se incluye el campo estado en el filtro para la impresión masiva.
 Corrección bugs log.

13/11/2017 4.6 --> 4.6.1
  Correción formateo de direcciones
  
30/10/2017 4.5 --> 4.6
  Correción bug en versiones 1.4 y 1.7 de Prestashop al actualizar cambios en la pantalla del pedido

09/10/2017 4.4 --> 4.5
  Corrección bug impresión masiva
  
29/09/2017 4.3 --> 4.4
  Se añade la opción de tramo horario a la pantalla del pedido.
  
08/09/2017 4.2 --> 4.3
  Se adapta el módulo para funcionar con certificados SSL.

13/07/2017 4.1 --> 4.2
  Se corrige la automatización de generación de etiquetas.

16/06/2017 4.0 --> 4.1
  Se corrige bug que no concatenaba correctamente las direcciones de los almacenes.
  Se limita la búsqueda del grid a los pedidos sin etiquetas o con etiquetas generadas en el día en curso.

13/06/2017 - 3.4.1 --> 4.0
  Se incluye la opción de impresión masiva (sólo para versiones 1.5, 1.6 y 1.7 de PS) y terceras plazas (sólo para versiones 1.5 y 1.6de PS)

04/05/2017 	- 3.4 --> 3.4.1
	Se añade compatibilidad con el módulo de contrareembolso iwcodfee.

28/02/2017  - 3.3 --> 3.4
  Corrección enviar notificaciones aunque el teléfono esté mal formateado.

08/02/2017  - 3.2 --> 3.3
  *Revisión corrección anterior.

06/02/2017  - 3.1 --> 3.2
  * Se corrige bug que impedía instalar el módulo en algunas versiones de la 1.4.x de Prestashop

28/11/2016  - 3.0 --> 3.1
	* Añadimos compatibilidad con imaxcontrareembolso
	
15/11/2016  - 2.4.2 --> 3.0
	* Adaptación del módulo a la versión 1.7 de Prestashop.

20/10/2016  - 2.4.1 --> 2.4.2
	* Mejoramos sanitizacion de los campos de configuración de abonado.

29/09/2016  - 2.4.0 --> 2.4.1
	* Nombre de la empresa en nombre si existe, y nombre de destinatario en Contacto.
	
21/09/2016  - 2.3.0 --> 2.4.0
	* Se comprueba si existe el número de teléfono del destinatario y en caso negativo devuelve cadena vacía.

14/09/2016  - 2.2.0 --> 2.3.0
	* Se elimina el campo "A la atención de" y se sustituye por "Contacto".
	* Se incluye el módulo contrareembolso "bacodwithfees".
	* Se incluye el módulo contrareembolso "cashondeliverymod".
	* Se comprueba si existe el número de teléfono del destinatario y en caso negativo se indica 000000000.
	* Se comprueban los campos de los credenciales antes de guardar.

05/05/2016  - 2.1.19 --> 2.2.0
    * Se incluyen preavisos por SMS y por Email cuando el pedido es con entrega en franquicia.

06/04/2016  - 2.1.18 --> 2.1.19
    * Controlamos duplicidad en generación automática de envíos cuando hay varios usuarios logueados

03/03/2016  - 2.1.17 --> 2.1.18
    * Cambiamos servicio por defecto de Ecommerce a Urgente19

19/02/2016  - 2.1.16 --> 2.1.17
    * Calcula el peso correcto de bultos en la etiqueta aunque el cliente indique en su tienda el peso en gramos

09/12/2015  - 2.1.15 --> 2.1.16
    * Añadidas correcciones en la ayuda del módulo
	* Añadia la transmisión de las dimensiones sólo si se indica desglose de bultos, no hay gestión de bultos y num bultos = num productos.
05/11/2015  - 2.1.14 --> 2.1.15
    * Añadido el módulo de Contrareembolso de EsPrestashop
02/11/2015  - 2.1.13 --> 2.1.14
    * Corregido error que permitía dejar el campo nombre de destinatario vacío.
28/10/2015  - 2.1.12 --> 2.1.13
    * Corregido error histórico de desglose de bultos, donde el número de decimales genera problemas en las peticiones.
21/10/2015 - 2.1.11 --> 2.1.12
    * Añadida la compatibilidad con el módulo de Luis Cambras
    * Añadida mejora cuando eliminan el campo empresa de la Base de Datos.
    * Eliminado el error que eliminaba un caracter en el envío de la dirección.
18/08/2015 - 2.1.10 --> 2.1.11
    * Añadidas modificaciones para el control de excepciones cuando las peticiones fallan
    * Añadida restricción para que el campo resto sólo salgan 50 caracteres.
    * Añadida mayor compatibilidad con la versión 1.6 de prestashop.
28/07/2015 - 2.1.9 --> 2.1.10
    * Añadido filtro en el módulo para que sólo salgan los mensajes públicos en el campo observaciones
    * Añadida lógica para que si se rellena el campo Empresa, aparezca como nombre y el cliente como AlaAtencionDe.
22/06/2015 - 2.1.8 --> 2.1.9
    * Añadido nuevo botón Actualizar Cambios.
    * Renombrada la tabla subscribers a subs para acortar.
16/06/2015 - 2.1.7 --> 2.1.8
    * Desactivación de la comprobación de la clase SOAP.
09/06/2015 - 2.1.6 --> 2.1.7
     * Añadido el mensaje de aviso que falta la clase SOAP
     * Modificaciones internas de mensaje de aviso.
     * Compatibilidad con el modulo 'reembolsocargo'
28/10/2014 - 2.1.5 --> 2.1.6
     * Añadida la restricción del sistema que cambia el cobro del reembolso a destino cuando la cantidad no supera 2.42 €
28/10/2014 - 2.1.4 --> 2.1.5
     * Añadidas las notificaciones al teléfono del cliente indicando la entrega en Franquicia.
28/10/2014 - 2.1.3 --> 2.1.4
     * Compatibilidad con el módulo deluxecodfees.
10/09/2014 - 2.1.2 --> 2.1.3
     * Cambios en la generación de envíos automáticos por el fallo reportado por un cliente.
     * Añadida la posibilidad de generación de envíos con retorno.
     * Compatibilidad con el módulo pago con contrareembolso (with fee) 15.
22/08/2014 - 2.1.1 --> 2.1.2
     * Mejora en la presentación de la información en la versión 1.6 de Prestashop.
05/08/2014 - 2.1.0 --> 2.1.1
     * Compatible con el módulo cashondeliveryfeedep
     * Eliminados servicios +40 kilos del módulo
     * Agregados los servicios Expedición.
23/06/2014 - 2.0.9 --> 2.1.0
     * Se añade la retrocompatibilidad con la 1.4.X
     * Se modifican las condiciones del módulo para que aparezca correctamente en la 1.6.X
     * Añadido nuevo transportista Entrega En Franquicia
     * Añadida la lógica para que el nuevo transportista tenga la opción de Entrega en Franquicia por defecto.
16/06/2014 - 2.0.8 --> 2.0.9
     * Se añade la compatibilidad con cashondeliveryplus
26/05/2014 - 2.0.7 --> 2.0.8
     * Añadida la restricción de 1 abonado - 1 tienda.
     * Mejorada la query para la generación automática de etiquetas
     * Añadida distinciones entre el modo automático y manual con multitienda, permitiendo diferentes comportamientos de abonado.
     * Optimización queries internas.     
     * Eliminados transportistas duplicados.
     * Incluída la posibilidad de meter un nombre a la configuración del módulo.
22/05/2014 - 2.0.6 --> 2.0.7
     * Se han agregado cambios para que si no se introducen los comentarios en la dirección, se coja el comentario por defecto del cliente.
02/04/2014 - 2.0.4 --> 2.0.5
     * Cambios menores para mejorar el rendimiento del módulo 
16/03/2014 - 2.0.3 --> 2.0.4
     * Añadida compatibilidad con maofree2_cashondelivery.
10/03/2014 - 2.0.1 --> 2.0.3
     * Permitir pasar a producción 
     * No genera error cuando se lanza la petición errónea
19/02/2014 - 1.0.10 --> 2.0.1
     * Cambiada la logíca de gestión de bultos y de servicio
     * Eliminación campos innecesarios
     * Eliminación de la posibilidad de generar varios pedidos a la vez mientras se carga la petición
     * Añadida la entrega en Franquicia
     * Añadida la doble notificación
     * Eliminado problema con notificaciones y envíos a Franquicias.
     * Añadida la posibilidad de la gestión de Abonados
     * Preparación del módulo a multiabonado.

29/11/2013 - 1.0.9 --> 1.0.10
     * Ampliada compatibilidad con módulo "Pago contrareembolso con recargo y 
       limitación a productos y categorías" (megareembolso).
     * Mejora para hacer configurable si se envía o no el email que le notifica al
       comprador cuando el envío ha sido transmitido a MRW y le informa del número
       del envío para su seguimiento.

01/10/2013 - 1.0.8 --> 1.0.9
     * Corregido bug de cálculo de peso en desglose de bultos.

12/09/2013 - 1.0.7 --> 1.0.8
     * Ampliada compatibilidad con módulo "Pago contra reembolso con comisión" (codfee).
     * Mejora en versiones PS 1.5.x para que añada la referencia unica del pedido en el
       campo ReferenciaCliente del SOAP-XML junto con el id_order:
       - "PEDIDO-$id_order ($reference)". En PS 1.4.x enviará solo "PEDIDO-$id_order".

07/08/2013 - 1.0.6 --> 1.0.7
     * Corregido bug de compatibilidad con módulo maofree_cashondeliveryfee (sólo PS 1.4.x).
     * Mejorada la instalación del transportista MRW por defecto para que aparezca aunque
       el comprador no esté registrado.
     * Mejorada la depuración avanzada para que proporcione más información.

03/07/2013 - 1.0.5 --> 1.0.6
     * Corregido bug en la instalación para versiones de PS 1.4.x ya que no se ubicaban
       los archivos de traducciones en el sitio correcto.
     * Corregido bug en el template de los bloques del módulo MRW en la sección de
       administración del pedido para versiones de PS 1.4.x.
     * Mejora del instalador para que incluya solo aquellos Hooks realmente necesarios.
     * Mejora en la gestión de cambios de estado en el histórico del pedido.
     * Mejora en versiones PS 1.5.x para que envíe la referencia unica del pedido en el
       campo ReferenciaCliente del SOAP-XML. En PS 1.4.x seguirá enviando el id_order.
     * Mejora en desinstalador para que des-registre los Hooks registrados en la instalación
     * Optimización. Eliminación de la función sendMailMRW pues ya no es necesaria.

25/06/2013 - 1.0.4 --> 1.0.5
     * Corregido bug en la llamada al hookBackOfficeFooter para que solo genere los envios
       y las etiquetas en caso de que el valor de TICKET_MRW sea 1. Antes este parámetro de
       configuración sólo afectaba a la generación de la etiqueta, pero siempre realizaba
       la transmisión de los envíos a MRW y cambiaba el estado de los pedidos.
     * Corregido bug en control de bultos por el que se volvía a 1 bulto cuando el número
       de unidades en el pedido es muy alto, a pesar de haber configurado manualmente un
       número de bultos antes de generar el envío.
     * Mejora gestión de bultos. Nueva opción para determinar si se realiza desglose de bultos.
     * Mejora. El fichero de log tiene el mismo nombre del módulo (multi-instalaciones).
     
20/06/2013 - 1.0.3 --> 1.0.4
     * Corregido bug en la modificacion de transportistas MRW. Prestashop crea un
       nuevo registro cuando se edita un transportista y se perdían las vinculaciones
       con el módulo. Ahora se actualiza el id de transportista vinculado al módulo
       -incluso si hay más de un transportista-.
     * Mejorada la instalación del módulo para que cree el transportista usando
       el API de Prestashop y con todas las opciones necesarias según versión.
     * Añadidas trazas (debug) activables desde administración del módulo.
       Estas trazas permitirán realizar un seguimiento exhaustivo de todos los
       procesos y valores utilizados internamente en el módulo. Las trazas se
       graban en el fichero (mrwcarrier.log).
     * Optimizado el sistema de descarga del fichero de logs. Ahora se fuerza la
       descarga en lugar de visualizarse el fichero en el navegador.
     * Mejorada la compatibilidad con versiones de Prestashop 1.4.x.
     * Mejorado soporte multi-instalación para poder instalar varias instancias
       del módulo en la misma tienda (p.ej.: varios abonados o dptos.).