{*  $Id: banners_block_banners_modify.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div class="z-adminformrow">
    <label for="bannerblock_btype">{gt text='Block Type'}</label>
    { gt assign="choose" text="Chose One" }
    { selector_field_array name="btype" modname="Banners" table="banners" field="type" sort="$tabletype" selectedValue=$btype defaultValue=0 defaultText=$choose assocKey="type" }
</div>
<div class="z-adminformrow">
<label for="bannerblock_template">{gt text='Block Template'}</label>
	<input id="bannerblock_rotate_blocktemplate" type="text" name="blocktemplate" size="30" value="{$blocktemplate|varprepfordisplay}" />
</div>