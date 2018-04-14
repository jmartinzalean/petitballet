{assign var=context value=Context::getContext()}
<form enctype="multipart/form-data" class="preorder-form-box">
		<div class="errors"></div>
		<fieldset>
				<div class="clearfix">
						{foreach from=$fields item=field}
								<div class="form-group">
										{if $field.type != 'content'}
												<div class="help-block">{$field.description|escape:'quotes':'UTF-8'}</div>
										{/if}
										{if $field.type == 'name'}
												{*<label for="name" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<input type="text" value="{if $context->customer->firstname}{$context->customer->firstname|escape:'htmlall':'UTF-8'}{/if}" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isName" name="{$field.type|escape:'htmlall':'UTF-8'}" id="name" class="form-control grey validate {if $field.required}required{/if}">
										{elseif $field.type == 'number'}
												{*<label for="number" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<input type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isPhoneNumber" name="{$field.type|escape:'htmlall':'UTF-8'}" id="number" class="form-control grey validate {if $field.required}required{/if}">
										{elseif $field.type == 'address'}
												{*<label for="address" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<input type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isAddress" name="{$field.type|escape:'htmlall':'UTF-8'}" id="address" class="form-control grey validate {if $field.required}required{/if}">
										{elseif $field.type == 'email'}
												{*<label for="email" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<input type="text" value="{if $context->customer->email}{$context->customer->email|escape:'htmlall':'UTF-8'}{/if}" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isEmail" name="{$field.type|escape:'htmlall':'UTF-8'}" id="email" class="form-control grey validate {if $field.required}required{/if}">
										{elseif $field.type == 'message'}
												{*<label for="message" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<textarea type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isMessage" name="{$field.type|escape:'htmlall':'UTF-8'}" id="message" class="form-control grey validate {if $field.required}required{/if}"/>
										{elseif $field.type == 'time'}
												{*<label for="date_from" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
												<br>
												{*{l s='From'}*}
												<input type="text" value="" placeholder="{l s='Time from' mod='tmoneclickorder'}{if $field.required}*{/if}" class="datepicker form-control grey {if $field.required}required{/if}" id="date_from" readonly/>
												{*{l s='to'}*}
												<input type="text" value="" placeholder="{l s='Time to' mod='tmoneclickorder'}{if $field.required}*{/if}" class="datepicker form-control grey {if $field.required}required{/if}" id="date_to" readonly/>
										{elseif $field.type == 'content'}
												{*<div class="content-name">{$field.name}</div>*}
												<div class="content-description">{$field.description|escape:'quotes':'UTF-8'}</div>
										{/if}
								</div>
						{/foreach}
				</div>
				<div class="submit">
						<button class="button btn btn-md btn-primary" id="submitPreorder" name="submitPreorder" type="submit" {if isset($product)}data-id-product-attribute="{$product.id_product_attribute}" data-id-product="{$product.id_product}" data-quantity="{$product.quantity}" data-customize-id="{$product.id_customization}"{/if}>
								<span>{l s='Send' mod='tmoneclickorder'}</span></button>
				</div>
		</fieldset>
</form>
