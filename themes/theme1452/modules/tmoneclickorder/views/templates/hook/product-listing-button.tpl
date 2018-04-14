<button class="add_preorder btn btn-primary btn-sm btn-tmoneclickorder {if $product.quantity == 0 || $product.customizable == 1}hidden{/if}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}" title="{l s='Buy in one click' mod='tmoneclickorder'}">
  <span>
    {l s='Buy in one click' mod='tmoneclickorder'}
  </span>
</button>