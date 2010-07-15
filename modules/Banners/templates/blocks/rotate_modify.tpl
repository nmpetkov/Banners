{*  $Id: banners_block_banners_modify.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Block Type'}</label>
    {gt assign="choose" text="Chose One"}
    {selector_field_array
        name="type"
        modname="Banners"
        table="banners"
        field="type"
        selectedValue=$vars.type
        defaultValue="0"
        defaultText=$choose
        assocKey="type"}
</div>
<div class="z-formrow">
    <label for="bannerblock_template">{gt text='Block Template'}</label>
	<input id="bannerblock_rotate_blocktemplate" type="text" name="blocktemplate" size="30" value="{$blocktemplate|safetext}" />
</div>