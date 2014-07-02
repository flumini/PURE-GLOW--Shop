{block name="frontend_detail_tabs_related" append}
    <li>
        <a href="#variantsTab" >
            {se name="VariantsTabVariantsHeadline"}Varianten{/se}
        </a>
    </li>
{/block}
 
{block name="frontend_detail_description" append}
    <div id="variantsTab" class="variants_tab">
        <div class="config">
            <input type="hidden" name="requestUrl" value="{url controller="SwagVariantsTab" action="getVariants"}">
            <input type="hidden" name="currentArticleId" value="{$sArticle.articleID}">
            <input type="hidden" name="currentArticleNumber" value="{$sArticle.ordernumber}">
            <input type="hidden" name="moreText" value="{s name="VariantsTabMore"}Mehr >>{/s}">
        </div>
    </div>
{/block}
 