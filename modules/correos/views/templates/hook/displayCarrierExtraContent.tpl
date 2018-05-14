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
{if $params.carrier_type eq 'office'}
    {include file='module:correos/views/templates/hook/helper/CarrierExtraContent_office.tpl' params=$params}
{/if}
{if $params.carrier_type eq 'homepaq'}
    {include file='module:correos/views/templates/hook/helper/CarrierExtraContent_homepaq.tpl' params=$params}
{/if}
{if $params.carrier_type eq 'hourselect'}
    {include file='module:correos/views/templates/hook/helper/CarrierExtraContent_hourselect.tpl' params=$params}
{/if}
{if $params.carrier_type eq 'international'}
    {include file='module:correos/views/templates/hook/helper/CarrierExtraContent_international.tpl' params=$params}
{/if}
