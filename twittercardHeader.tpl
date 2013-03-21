{*
*  @author mabarroso <http://github.com/mabarroso/>
*  @copyright  2013 mabarroso
*  @license    Released under the MIT license: http://www.opensource.org/licenses/MIT
*  
*}

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@allspanishgifts" />
<meta name="twitter:creator" content="@allspanishgifts" />
<meta name="twitter:url" content="{$product.link|escape:'htmlall':'UTF-8'}" />
<meta name="twitter:title" content={$product->name|escape:'htmlall':'UTF-8'}" />
{if $product->description_short}
    <meta name="twitter:description" content="{$product->description_short}" />
{else}
    <meta name="twitter:description" content="{$product->description}" />
{/if}
{if $have_image}
    <meta name="twitter:image" content="$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')}" />
{/if}

