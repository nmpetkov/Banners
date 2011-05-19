<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Block Type'}</label>
    {nocache}
    <span id="bannerblock_blocktype">{foreach from=$catregistry key='property' item='category'}
        {array_field assign="selectedValue" array=$vars.blocktype field=$property returnValue=1}
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
    <label for="bannerblock_duration">{gt text='Transition speed'}</label>
    <input type="text" id="bannerblock_duration" name="duration" value="{$vars.duration}" />
    <em class='z-formnote'>({gt text='NOT in seconds'}) Greater is faster. recommended value: 50.</em>
    <em class='z-formnote'>{gt text='Note: The display duration is set in the Category Attributes'}</em>
</div>