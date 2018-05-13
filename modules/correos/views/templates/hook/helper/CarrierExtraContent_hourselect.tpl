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
<div class="correos-carrer-content">
<div id="timetable" style="font-weight:bold">
	<div id="timetable_inner">
	{l s='Select delivery time' mod='correos'}: <br>
	<input type="radio" name="cr_timetable" id="9_12" value="01" {if $params.id_schedule eq '01'}checked{/if}
        onchange="Correos.updateHoursSelect('01', {$params.id_carrier|intval});"/>
        <label for="9_12">09:00 - 12:00 </label>
	<input type="radio" name="cr_timetable" id="12_15" value="02" {if $params.id_schedule eq '02'}checked{/if}
        onchange="Correos.updateHoursSelect('02', {$params.id_carrier|intval});"/>
        <label for="12_15">12:00 - 15:00</label>
	<input type="radio" name="cr_timetable" id="15_18" value="03" {if $params.id_schedule eq '03'}checked{/if}
        onchange="Correos.updateHoursSelect('03', {$params.id_carrier|intval});"/>
        <label for="15_18">15:00 - 18:00</label> 
	<input type="radio" name="cr_timetable" id="18_21" value="04" {if $params.id_schedule eq '04'}checked{/if}
        onchange="Correos.updateHoursSelect('04', {$params.id_carrier|intval});"/>
        <label for="18_21">18:00 - 21:00</label>
	</div>
</div>
</div>