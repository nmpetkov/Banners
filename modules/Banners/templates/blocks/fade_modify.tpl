{*  $Id: banners_block_banners_modify.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div class="z-formrow">
    <label for="bannerblock_type">{gt text='Banner Type'}</label>
    {gt assign="choose" text="Choose"}
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
