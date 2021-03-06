<div class="z-formrow">
    <label for="bannerblock_blocktype">{gt text='Banner Type'}</label>
    {nocache}
    <span id="bannerblock_blocktype">{foreach from=$catregistry key='property' item='category'}
        {array_field assign="selectedValue" array=$vars.blocktype field=$property}
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