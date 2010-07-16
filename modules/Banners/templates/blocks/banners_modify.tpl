{*  $Id: banners_block_banners_modify.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Block Type'}</label>
    {nocache}
    <span id="bannerblock_type">{foreach from=$catregistry key=property item=category}
        {array_field_isset assign="selectedValue" array=$vars.type field=$property returnValue=1}
        {selector_category
            editLink=false
            category=$category
            name="type[$property]"
            field="id"
            selectedValue=$selectedValue
            defaultValue="0"}
        {/foreach}
    </span>
    {/nocache}
</div>
