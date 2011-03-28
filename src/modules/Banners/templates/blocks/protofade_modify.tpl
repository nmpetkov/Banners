<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Banner Type'}</label>
    {nocache}
    <span id="bannerblock_type">{foreach from=$catregistry key='property' item='category'}
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
<div class="z-formrow">
    <label for="bannerblock_duration">{gt text='Transition duration'}</label>
    <input type="text" id="bannerblock_duration" name="duration" value="{$vars.duration}" />
    <em class='z-formnote'>({gt text='in seconds'})</em>
    <em class='z-formnote'>{gt text='Note: The display duration is set in the Category Attributes'}</em>
</div>
<div class="z-formrow">
    <label for="bannerblock_controls">{gt text='Display slideshow controls'}</label>
    <input type="checkbox" id="bannerblock_controls" name="controls" value="1" {if $vars.controls} checked="checked"{/if} />
</div>
<div class="z-formrow">
    <label for="bannerblock_autostart">{gt text='Autostart slideshow'}</label>
    <input type="checkbox" id="bannerblock_autostart" name="autostart" value="1" {if $vars.autostart} checked="checked"{/if} />
    <em class='z-formnote'>{gt text='recommended'}</em>
</div>
<div class="z-formrow">
    <label for="bannerblock_esquare">{gt text='Use eSquare transition'}</label>
    <input type="checkbox" id="bannerblock_esquare" name="esquare" value="1" {if $vars.esquare} checked="checked"{/if} />
</div>
<div class="z-formrow">
    <label for="bannerblock_erows">{gt text='Number of eSquare rows'}</label>
    <input type="text" id="bannerblock_erows" name="erows" value="{$vars.erows}" />
</div>
<div class="z-formrow">
    <label for="bannerblock_ecols">{gt text='Number of eSquare columns'}</label>
    <input type="text" id="bannerblock_ecols" name="ecols" value="{$vars.ecols}" />
</div>
<div class="z-formrow">
    <label for="bannerblock_ecolor">{gt text='Color of eSquare transition background'}</label>
    <input type="text" id="bannerblock_ecolor" name="ecolor" value="{$vars.ecolor}" />
    <em class='z-formnote'>({gt text='include hash symbol'}}</em>
</div>