{extends file=“parent:frontend/listing/box_article.tpl“} 

{block name="frontend_index_header_css_screen" append}
    <link type="text/css" media="all" rel="stylesheet" href="{link file='frontend/_resources/css/variants_in_listing.css'}" />
{/block}
 
{block name="frontend_index_header_javascript" append}
    <script src="{link file='frontend/_resources/javascript/jquery.variants_in_listing.js'}"></script>
{/block}
 
{block name='frontend_listing_box_article_picture'}


{if $sTemplate eq 'listing-3col' || $sTemplate eq 'listing-2col'}
	{assign var=image value=$sArticle.image.src.9}
{else}
	{assign var=image value=$sArticle.image.src.7}
{/if}

{if $sArticle.swagVariantsInListing}
        <div class="swag_variants_in_listing">
            <input type="hidden" name="articleId" value="{$sArticle.articleID}">
            <input type="hidden" name="requestUrl" value='{url controller="SwagVariantsInListing" action="getCover"}'>
            {if $sTemplate eq 'listing-3col' || $sTemplate eq 'listing-2col'}
                <input type="hidden" name="src" value="3">
                {else}
                <input type="hidden" name="src" value="2">
            {/if}

        </div>
    {/if}
<div class="images-container">
<a href="{$sArticle.linkDetails|rewrite:$sArticle.articleName}" title="{$sArticle.articleName}" class="artbox_thumb">
{if isset($sArticle.image.src)} 
	<img src="{$sArticle.swagVariantsInListing.0}" onmouseover="this.src='{$sArticle.swagVariantsInListing.1}'"
        onmouseout="this.src='{$sArticle.swagVariantsInListing.0}'" style="align: center;"{/if}>
{if !isset($sArticle.image.src)}<img src="{link file='frontend/_resources/images/no_picture.jpg'}" alt="{s name='ListingBoxNoPicture'}{/s}" />{/if}</a>
</div>
{/block}
  	
		{* Article name *}
		{block name='frontend_listing_box_article_name'}
		<a href="{$sArticle.linkDetails|rewrite:$sArticle.articleName}" class="title" title="{$sArticle.articleName}">{$sArticle.articleName|truncate:47}</a>
		{/block}
		
		{* Description *}
		{block name='frontend_listing_box_article_description'}
		{if $sTemplate eq 'listing-1col'}
			{assign var=size value=270}
		{else}
			{assign var=size value=60}
		{/if}
		<p class="desc">{if $sTemplate != 'listing'}
			{$sArticle.description_long|strip_tags|truncate:$size}
			{/if}
		</p>
		{/block}
		
		{* Unit price *}
		{block name='frontend_listing_box_article_unit'}
        {if $sArticle.purchaseunit}
            <div class="{if !$sArticle.pseudoprice}article_price_unit{else}article_price_unit_pseudo{/if}">
                {if $sArticle.purchaseunit && $sArticle.purchaseunit != 0}
                    <p>
                        <strong>{se name="ListingBoxArticleContent"}{/se}:</strong> {$sArticle.purchaseunit} {$sArticle.sUnit.description}
                    </p>
                {/if}
                {if $sArticle.purchaseunit != $sArticle.referenceunit}
                    <p>
                        {if $sArticle.referenceunit}
                            <strong class="baseprice">{se name="ListingBoxBaseprice"}{/se}:</strong> {$sArticle.referenceunit} {$sArticle.sUnit.description} = {$sArticle.referenceprice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                        {/if}
                    </p>
                {/if}
            </div>
        {/if}
		{/block}    	
		
		{* Article Price *}
		{block name='frontend_listing_box_article_price'}
		<p class="{if $sArticle.pseudoprice}pseudoprice{else}price{/if}">
	        {if $sArticle.pseudoprice}
	        	<span class="pseudo">{s name="reducedPrice"}Statt: {/s}{$sArticle.pseudoprice|currency} {s name="Star"}*{/s}</span>
	        {/if}
	        <span class="price">{$sArticle.price|currency} {s name="Star"}*{/s}</span>
        </p>
        {/block}
       	
       	
	</div>
</div>