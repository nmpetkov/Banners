{*  $Id: banners_block_banners_modify.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Block Type'}</label>
    {nocache}
    <span id="bannerblock_blocktype">{foreach from=$catregistry key=property item=category}
        {array_field_isset assign="selectedValue" array=$vars.blocktype field=$property returnValue=1}
        {selector_category
            editLink=false
            category=$category
            name="blocktype[$property]"
            field="id"
            selectedValue=$selectedValue
            defaultValue="0"}
        {/foreach}
    </span>
    {/nocache}
</div>
<div class="z-formrow">
    <label for="bannerblock_hovertext">{gt text='Show hovertext in JS tooltip'}</label>
    <input type="checkbox" id="bannerblock_hovertext" name="hovertext" value="1" {if $vars.hovertext} checked="checked"{/if} />
</div>