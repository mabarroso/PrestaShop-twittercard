{*
*  @author mabarroso <http://github.com/mabarroso/>
*  @copyright  2013 mabarroso
*  @license    Released under the MIT license: http://www.opensource.org/licenses/MIT
*
*}

{if $product}
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="{$site}" />
		<meta name="twitter:creator" content="{$creator}" />
		<meta name="twitter:url" content="{$product.link|escape:'htmlall':'UTF-8'}" />
		<meta name="twitter:title" content="{$product.name|escape:'htmlall':'UTF-8'}" />
		{if $description_short}
		    <meta name="twitter:description" content="{$description_short|escape:htmlall:'UTF-8'}" />
		{else}
		    <meta name="twitter:description" content="{$description|escape:htmlall:'UTF-8'}" />
		{/if}
		{if $product.id_image}
		    <meta name="twitter:image" content="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium')}" />
		{/if}
{/if}
