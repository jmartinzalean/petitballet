{*
* 2002-2016 TemplateMonster
*
* TM Header Account Block
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0

* @author     TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
<ul class="header-login-content toogle_content{if $is_logged} is-logged{/if}">
	{if $is_logged}
		<li {if $hook != 'left' && $hook != 'right'}class="{$configs.TMHEADERACCOUNT_DISPLAY_STYLE|escape:'quotes':'UTF-8'}"{/if}>
			<ul>
				<li class="user-data">
					{if $configs.TMHEADERACCOUNT_USE_AVATAR}
						<img src="{$avatar}" alt=""{if $hook != 'left' && $hook != 'right'} width="{if $configs.TMHEADERACCOUNT_DISPLAY_STYLE == "twocolumns"}150{else}90{/if}"{/if}>
					{/if}
					<p><span>{$firstname}</span> <span>{$lastname}</span></p>
				</li>
				<li>
					<a href="{$link->getPageLink('history', true)|escape:'htmlall':'UTF-8'}" title="{l s='My orders' mod='tmheaderaccount'}" rel="nofollow">
						{l s='My orders' mod='tmheaderaccount'}
					</a>
				</li>
				{if $returnAllowed}
					<li>
						<a href="{$link->getPageLink('order-follow', true)|escape:'htmlall':'UTF-8'}" title="{l s='My returns' mod='tmheaderaccount'}" rel="nofollow">
							{l s='My merchandise returns' mod='tmheaderaccount'}
						</a>
					</li>
				{/if}
				<li>
					<a href="{$link->getPageLink('order-slip', true)|escape:'htmlall':'UTF-8'}" title="{l s='My credit slips' mod='tmheaderaccount'}" rel="nofollow">
						{l s='My credit slips' mod='tmheaderaccount'}
					</a>
				</li>
				<li>
					<a href="{$link->getPageLink('addresses', true)|escape:'htmlall':'UTF-8'}" title="{l s='My addresses' mod='tmheaderaccount'}" rel="nofollow">
						{l s='My addresses' mod='tmheaderaccount'}
					</a>
				</li>
				<li>
					<a href="{$link->getPageLink('identity', true)|escape:'htmlall':'UTF-8'}" title="{l s='Manage my personal information' mod='tmheaderaccount'}" rel="nofollow">
						{l s='My personal info' mod='tmheaderaccount'}
					</a>
				</li>
				{if $voucherAllowed}
					<li>
						<a href="{$link->getPageLink('discount', true)|escape:'htmlall':'UTF-8'}" title="{l s='My vouchers' mod='tmheaderaccount'}" rel="nofollow">
							{l s='My vouchers' mod='tmheaderaccount'}
						</a>
					</li>
				{/if}
				{if isset($HOOK_BLOCK_MY_ACCOUNT) && $HOOK_BLOCK_MY_ACCOUNT !=''}
					{$HOOK_BLOCK_MY_ACCOUNT|escape:'quotes':'UTF-8'}
				{/if}
			</ul>
			<p class="logout">
				<a class="btn btn-default btn-md" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" title="{l s='Sign out' mod='tmheaderaccount'}" rel="nofollow">
					<i class="fa fa-unlock left"></i>
					{l s='Sign out' mod='tmheaderaccount'}
				</a>
			</p>
		</li>
	{else}
		<li class="login-content text-center">
			<form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post">
				<h2>{l s='Sign in' mod='tmheaderaccount'}</h2>
				<div class="alert alert-danger" style="display:none;"></div>
				<div class="form_content clearfix">
					<div class="form-group">
						<input class="is_required validate account_input form-control email" data-validate="isEmail" type="text" name="header-email" id="header-email" placeholder="{l s='Email address' mod='tmheaderaccount'}" value="{if isset($smarty.post.email)}{$smarty.post.email|stripslashes|escape:'htmlall':'UTF-8'}{/if}"/>
					</div>
					<div class="form-group">
            <span>
              <input class="is_required validate account_input form-control password" type="password" data-validate="isPasswd" name="header-passwd" id="header-passwd" placeholder="{l s='Password' mod='tmheaderaccount'}" value="{if isset($smarty.post.passwd)}{$smarty.post.passwd|stripslashes|escape:'htmlall':'UTF-8'}{/if}" autocomplete="off"/>
            </span>
					</div>
					<p>
						<a href="{$link->getPageLink('password', 'true')|escape:'html':'UTF-8'}" class="forgot-password link">
							{l s='Forgot your password?' mod='tmheaderaccount'}
						</a>
					</p>
					<p class="submit">
						<button type="submit" name="HeaderSubmitLogin" class="btn btn-default btn-md icon-right">
							<span>{l s='Sign in' mod='tmheaderaccount'}</span>
						</button>
					</p>
					<p>
						<a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}" class="create link">
							{l s='Create an account' mod='tmheaderaccount'}
						</a>
					</p>
					{hook h="displayHeaderLoginButtons"}
				</div>
			</form>
		</li>
		<li class="create-account-content hidden">
			<div class="alert alert-danger" style="display:none;"></div>
			<form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post" class="std">
				{$HOOK_CREATE_ACCOUNT_TOP}
				<div class="account_creation">
					<div class="clearfix">
						<label>{l s='Title' mod='tmheaderaccount'}</label>
						<br/>
						{foreach from=$genders key=k item=gender}
							<div class="radio-inline">
								<input type="radio" name="id_gender" id="id-gender{$gender->id}" value="{$gender->id}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
								<label class="top">{$gender->name}</label>
							</div>
						{/foreach}
					</div>
					<div class="required form-group">
						<input onkeyup="$('#firstname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" name="firstname" id="customer-firstname" placeholder="{l s='First name' mod='tmheaderaccount'} *" value="{if isset($smarty.post.customer_firstname)}{$smarty.post.customer_firstname}{/if}"/>
					</div>
					<div class="required form-group">
						<input onkeyup="$('#lastname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" name="lastname" id="customer-lastname" placeholder="{l s='Last name' mod='tmheaderaccount'} *" value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname}{/if}"/>
					</div>
					<div class="required form-group">
						<input type="email" class="is_required validate form-control" data-validate="isEmail" name="email" placeholder="{l s='Email' mod='tmheaderaccount'} *" id="email-create" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}"/>
					</div>
					<div class="required password form-group">
						<input type="password" class="is_required validate form-control" data-validate="isPasswd" name="passwd" placeholder="{l s='Password' mod='tmheaderaccount' mod='tmheaderaccount'} *" id="passwd-create"/>
						<span class="form_info">{l s='(Five characters minimum)' mod='tmheaderaccount'}</span>
					</div>
					<div class="form-group">
						<label>{l s='Date of Birth' mod='tmheaderaccount'}</label>
						<div class="row">
							<div class="col-xs-3">
								<select name="days" class="form-control">
									<option value="">-</option>
									{foreach from=$days item=day}
										<option value="{$day}" {if ($sl_day == $day)} selected="selected"{/if}>{$day}&nbsp;&nbsp;</option>
									{/foreach}
								</select>
								{*
									{l s='January' mod='tmheaderaccount'}
									{l s='February' mod='tmheaderaccount'}
									{l s='March' mod='tmheaderaccount'}
									{l s='April' mod='tmheaderaccount'}
									{l s='May' mod='tmheaderaccount'}
									{l s='June' mod='tmheaderaccount'}
									{l s='July' mod='tmheaderaccount'}
									{l s='August' mod='tmheaderaccount'}
									{l s='September' mod='tmheaderaccount'}
									{l s='October' mod='tmheaderaccount'}
									{l s='November' mod='tmheaderaccount'}
									{l s='December' mod='tmheaderaccount'}
								*}
							</div>
							<div class="col-xs-5">
								<select name="months" class="form-control">
									<option value="">-</option>
									{foreach from=$months key=k item=month}
										<option value="{$k}" {if ($sl_month == $k)} selected="selected"{/if}>{l s=$month mod='tmheaderaccount'}
											&nbsp;</option>
									{/foreach}
								</select>
							</div>
							<div class="col-xs-4">
								<select name="years" class="form-control">
									<option value="">-</option>
									{foreach from=$years item=year}
										<option value="{$year}" {if ($sl_year == $year)} selected="selected"{/if}>{$year}
											&nbsp;&nbsp;</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					{if isset($newsletter) && $newsletter}
						<div class="checkbox">
							<input type="checkbox" name="newsletter" id="newsletter-tmha-login" value="1" {if isset($smarty.post.newsletter) AND $smarty.post.newsletter == 1} checked="checked"{/if} />
							<label>{l s='Sign up for our newsletter!' mod='tmheaderaccount'}</label>
							{if array_key_exists('newsletter', $field_required)}
								<sup> *</sup>
							{/if}
						</div>
					{/if}
					{if !isset($optin) && !$optin}
						<div class="checkbox">
							<input type="checkbox" name="optin" id="optin-tmha" value="1" {if isset($smarty.post.optin) AND $smarty.post.optin == 1} checked="checked"{/if} />
							<label>{l s='Receive special offers from our partners!' mod='tmheaderaccount'}</label>
							{if array_key_exists('optin', $field_required)}
								<sup> *</sup>
							{/if}
						</div>
					{/if}
				</div>
				{if $b2b_enable}
					<div class="account_creation">
						<h3 class="page-subheading">{l s='Your company information' mod='tmheaderaccount'}</h3>
						<p class="form-group">
							<label for="company-tmha">{l s='Company' mod='tmheaderaccount'}</label>
							<input type="text" class="form-control" name="company" id="company-tmha" value="{if isset($smarty.post.company)}{$smarty.post.company}{/if}"/>
						</p>
						<p class="form-group">
							<label for="siret-tmha">{l s='SIRET' mod='tmheaderaccount'}</label>
							<input type="text" class="form-control" name="siret" id="siret-tmha" value="{if isset($smarty.post.siret)}{$smarty.post.siret}{/if}"/>
						</p>
						<p class="form-group">
							<label for="ape-tmha">{l s='APE' mod='tmheaderaccount'}</label>
							<input type="text" class="form-control" name="ape" id="ape-tmha" value="{if isset($smarty.post.ape)}{$smarty.post.ape}{/if}"/>
						</p>
						<p class="form-group">
							<label for="website-tmha">{l s='Website' mod='tmheaderaccount'}</label>
							<input type="text" class="form-control" name="website" for="website-tmha" value="{if isset($smarty.post.website)}{$smarty.post.website}{/if}"/>
						</p>
					</div>
				{/if}
				{if isset($PS_REGISTRATION_PROCESS_TYPE) && $PS_REGISTRATION_PROCESS_TYPE}
					<div class="account_creation">
						<h3 class="page-subheading">{l s='Your address' mod='tmheaderaccount'}</h3>
						{foreach from=$dlv_all_fields item=field_name}
							{if $field_name eq "company"}
								{if !$b2b_enable}
									<p class="form-group">
										<label for="company2-tmha">{l s='Company' mod='tmheaderaccount'}{if in_array($field_name, $required_fields)}
												<sup>*</sup>
											{/if}
										</label>
										<input type="text" class="form-control" name="company" id="company2-tmha" value="{if isset($smarty.post.company)}{$smarty.post.company}{/if}"/>
									</p>
								{/if}
							{elseif $field_name eq "vat_number"}
								<div style="display:none;">
									<p class="form-group">
										<label for="vat_number">{l s='VAT number' mod='tmheaderaccount'}{if in_array($field_name, $required_fields)}
												<sup>*</sup>
											{/if}</label>
										<input type="text" class="form-control" name="vat_number" id="vat_number-tmha" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{/if}"/>
									</p>
								</div>
							{elseif $field_name eq "firstname"}
								<p class="required form-group">
									<label for="firstname-tmha">{l s='First name' mod='tmheaderaccount'} <sup>*</sup></label>
									<input type="text" class="form-control" name="firstname" id="firstname-tmha" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{/if}"/>
								</p>
							{elseif $field_name eq "lastname"}
								<p class="required form-group">
									<label for="lastname-tmha">{l s='Last name' mod='tmheaderaccount'} <sup>*</sup></label>
									<input type="text" class="form-control" name="lastname" id="lastname-tmha" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{/if}"/>
								</p>
							{elseif $field_name eq "address1"}
								<p class="required form-group">
									<label for="address1-tmha">{l s='Address' mod='tmheaderaccount'} <sup>*</sup></label>
									<input type="text" class="form-control" name="address1" id="address1-tmha" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{/if}"/>
									<span class="inline-infos">{l s='Street address, P.O. Box, Company name, etc.' mod='tmheaderaccount'}</span>
								</p>
							{elseif $field_name eq "address2"}
								<p class="form-group is_customer_param">
									<label for="address2-tmha">{l s='Address (Line 2)' mod='tmheaderaccount'}{if in_array($field_name, $required_fields)}
											<sup>*</sup>
										{/if}</label>
									<input type="text" class="form-control" name="address2" id="address2-tmha" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{/if}"/>
									<span class="inline-infos">{l s='Apartment, suite, unit, building, floor, etc...' mod='tmheaderaccount'}</span>
								</p>
							{elseif $field_name eq "postcode"}
								{assign var='postCodeExist' value=true}
								<p class="required postcode form-group">
									<label for="postcode-tmha">{l s='Zip/Postal Code' mod='tmheaderaccount'} <sup>*</sup></label>
									<input type="text" class="form-control" name="postcode" data-validate="isPostCode" id="postcode-tmha" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{/if}"/>
								</p>
							{elseif $field_name eq "city"}
								<p class="required form-group">
									<label for="city-tmha">{l s='City' mod='tmheaderaccount'} <sup>*</sup></label>
									<input type="text" class="form-control" name="city" id="city-tmha" value="{if isset($smarty.post.city)}{$smarty.post.city}{/if}"/>
								</p>
								<!-- if customer hasn't update his layout address, country has to be verified but it's deprecated -->
							{elseif $field_name eq "Country:name" || $field_name eq "country"}
								<p class="required select form-group">
									<label>{l s='Country' mod='tmheaderaccount'} <sup>*</sup></label>
									<select name="id_country" class="form-control">
										<option value="">-</option>
										{foreach from=$countries item=v}
											<option value="{$v.id_country}"{if (isset($smarty.post.id_country) AND $smarty.post.id_country == $v.id_country) OR (!isset($smarty.post.id_country) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name}</option>
										{/foreach}
									</select>
								</p>
							{elseif $field_name eq "State:name" || $field_name eq 'state'}
								{assign var='stateExist' value=true}
								<p class="required id_state select form-group">
									<label>{l s='State' mod='tmheaderaccount'} <sup>*</sup></label>
									<select name="id_state" class="form-control">
										<option value="">-</option>
									</select>
								</p>
							{/if}
						{/foreach}
						{if $postCodeExist eq false}
							<p class="required postcode form-group unvisible">
								<label for="postcode2-tmha">{l s='Zip/Postal Code' mod='tmheaderaccount'} <sup>*</sup></label>
								<input type="text" class="form-control" name="postcode" id="postcode2-tmha" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{/if}"/>
							</p>
						{/if}
						{if $stateExist eq false}
							<p class="required id_state select unvisible form-group">
								<label for="id_state">{l s='State' mod='tmheaderaccount'} <sup>*</sup></label>
								<select name="id_state" class="form-control">
									<option value="">-</option>
								</select>
							</p>
						{/if}
						<p class="textarea form-group">
							<label for="other-tmha">{l s='Additional information' mod='tmheaderaccount'}</label>
							<textarea class="form-control" name="other" id="other-tmha" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{/if}</textarea>
						</p>
						<p class="form-group">
							<label for="phone-tmha">{l s='Home phone' mod='tmheaderaccount'}{if isset($one_phone_at_least) && $one_phone_at_least}
									<sup>**</sup>
								{/if}</label>
							<input type="text" class="form-control" name="phone" id="phone-tmha" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}"/>
						</p>
						<p class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}form-group">
							<label for="phone_mobile-tmha">{l s='Mobile phone' mod='tmheaderaccount'}{if isset($one_phone_at_least) && $one_phone_at_least}
									<sup>**</sup>
								{/if}</label>
							<input type="text" class="form-control" name="phone_mobile" id="phone_mobile-tmha" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{/if}"/>
						</p>
						{if isset($one_phone_at_least) && $one_phone_at_least}
							{assign var="atLeastOneExists" value=true}
							<p class="inline-infos required">
								** {l s='You must register at least one phone number.' mod='tmheaderaccount'}</p>
						{/if}
						<p class="required form-group">
							<label for="alias-tmha">{l s='Assign an address alias for future reference.' mod='tmheaderaccount'}
								<sup>*</sup></label>
							<input type="text" class="form-control" name="alias" id="alias-tmha" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{l s='My address' mod='tmheaderaccount'}{/if}"/>
						</p>
					</div>
					<div class="account_creation dni">
						<h3 class="page-subheading">{l s='Tax identification' mod='tmheaderaccount'}</h3>
						<p class="required form-group">
							<label for="dni-tmha">{l s='Identification number' mod='tmheaderaccount'} <sup>*</sup></label>
							<input type="text" class="form-control" name="dni" id="dni-tmha" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{/if}"/>
							<span class="form_info">{l s='DNI / NIF / NIE' mod='tmheaderaccount'}</span>
						</p>
					</div>
				{/if}
				{$HOOK_CREATE_ACCOUNT_FORM}
				<div class="submit clearfix">
					<input type="hidden" name="email_create" value="1"/>
					<input type="hidden" name="is_new_customer" value="1"/>
					<input type="hidden" class="hidden" name="back" value="my-account"/>
					<p class="submit">
						<button type="submit" name="submitAccount" class="btn btn-default btn-md">
            <span>
              {l s='Register' mod='tmheaderaccount'}
            </span>
						</button>
					</p>
					<p>
						<a href="#" class="btn btn-primary btn-md icon-left signin"><span>{l s='Sign in' mod='tmheaderaccount'}</span></a>
					</p>
				</div>
			</form>
		</li>
		<li class="forgot-password-content hidden">
			<p>{l s='Please enter the email address you used to register. We will then send you a new password. ' mod='tmheaderaccount'}</p>
			<form action="{$request_uri|escape:'html':'UTF-8'}" method="post" class="std">
				<fieldset>
					<div class="form-group">
						<div class="alert alert-success" style="display:none;"></div>
						<div class="alert alert-danger" style="display:none;"></div>
						<label for="email-forgot">{l s='Email address' mod='tmheaderaccount'}</label>
						<input class="form-control" type="email" name="email" id="email-forgot" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'html':'UTF-8'|stripslashes}{/if}"/>
					</div>
					<p class="submit">
						<button type="submit" class="btn btn-default btn-md">
            <span>
              {l s='Retrieve Password' mod='tmheaderaccount'}
            </span>
						</button>
					</p>
					<p>
						<a href="#" class="btn btn-default btn-md icon-right signin"><span>{l s='Sign in' mod='tmheaderaccount'}</span></a>
					</p>
				</fieldset>
			</form>
		</li>
		{strip}
			{if isset($smarty.post.id_state) && $smarty.post.id_state}
				{addJsDef idSelectedState=$smarty.post.id_state|intval}
			{elseif isset($address->id_state) && $address->id_state}
				{addJsDef idSelectedState=$address->id_state|intval}
			{else}
				{addJsDef idSelectedState=false}
			{/if}
			{if isset($smarty.post.id_state_invoice) && isset($smarty.post.id_state_invoice) && $smarty.post.id_state_invoice}
				{addJsDef idSelectedStateInvoice=$smarty.post.id_state_invoice|intval}
			{else}
				{addJsDef idSelectedStateInvoice=false}
			{/if}
			{if isset($smarty.post.id_country) && $smarty.post.id_country}
				{addJsDef idSelectedCountry=$smarty.post.id_country|intval}
			{elseif isset($address->id_country) && $address->id_country}
				{addJsDef idSelectedCountry=$address->id_country|intval}
			{else}
				{addJsDef idSelectedCountry=false}
			{/if}
			{if isset($smarty.post.id_country_invoice) && isset($smarty.post.id_country_invoice) && $smarty.post.id_country_invoice}
				{addJsDef idSelectedCountryInvoice=$smarty.post.id_country_invoice|intval}
			{else}
				{addJsDef idSelectedCountryInvoice=false}
			{/if}
			{if isset($countries)}
				{addJsDef countries=$countries}
			{/if}
			{if isset($vatnumber_ajax_call) && $vatnumber_ajax_call}
				{addJsDef vatnumber_ajax_call=$vatnumber_ajax_call}
			{/if}
			{if isset($email_create) && $email_create}
				{addJsDef email_create=$email_create|boolval}
			{else}
				{addJsDef email_create=false}
			{/if}
		{/strip}
	{/if}
</ul>