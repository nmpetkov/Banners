{*  $Id: banners_admin_banneredit.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=edit.gif set=icons/large alt='Edit Banner' altml=true}</div>
    <h2>{gt text="Edit Banner"}</h2>
    <div style="text-align:center"><img src="{$banner.imageurl|safetext}" alt="" title="{$banner.clickurl|safetext}" /></div>
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="update"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <fieldset>
                <legend>{gt text='Edit Banner ID %s' tag1=$banner.bid|safetext}</legend>
                <input type="hidden" name="banner[bid]" value="{$banner.bid|safetext}" />
                <input type="hidden" name="banner[imptotal]" value="{$banner.imptotal|safetext}" />
                <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
                <div class="z-formrow">
                    <label for="banners_cid">{gt text="Client Name"}</label>
                    <select id="banners_cid" name="banner[cid]">
                        <option value="">&nbsp;</option>
                        {html_options options=$clients selected=$banner.cid}
                    </select>
                </div>
                {if $banner.imptotal > 0}
                    <div class="z-formrow">
                        <label for="banners_addimp">{gt text="Add more impressions"}</label>
                        <input type="text" id="banners_addimp" name="banner[impadded]" size="12" maxlength="11" />
                        {gt text="Purchased"}:{$banner.imptotal|safetext}
                        {gt text="Impressions Made"}:{$banner.impmade|safetext}
                    </div>
                {else}
                    <input type="hidden" name="banner[impadded]" value="0" />
                {/if}
                <div class="z-formrow">
                    <label for="banners_banname">{gt text="Banner Name"}</label>
                    <input type="text" id="banners_name" name="banner[title]" size="50" maxlength="250" value="{$banner.title|safetext}" />
                </div>
                {if $enablecats}
                    <div class="z-formrow">
                        <label for="banners_type">{gt text='Banner Type'}</label>
                        {nocache}
                        <span><ul id='banners_type' style='list-style:none; margin: 0;'>
                        {foreach from=$catregistry key=property item=category}
                            {array_field_isset assign="selectedValue" array=$selectedcatsarray field=$property returnValue=1}
                            <li>{selector_category
                                    category=$category
                                    name="banner[__CATEGORIES__][$property]"
                                    field="id"
                                    selectedValue=$selectedValue
                                    defaultValue="0"
                                    editLink=1}</li>
                        {/foreach}
                        </ul></span>
                        {/nocache}
                    </div>
                {/if}
                <div class="z-formrow">
                    <label for="banners_imgurl">{gt text="Image URL"}</label>
                    <input type="text" id="banners_imgurl" name="banner[imageurl]" size="40" maxlength="255" value="{$banner.imageurl|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="banners_clickurl">{gt text="Click URL"}</label>
                    <input type="text" id="banners_clickurl" name="banner[clickurl]" size="40" maxlength="255" value="{$banner.clickurl|safetext}" />
                </div>
                 <div class="z-buttons z-formbuttons">
                    {button src="button_ok.gif" set="icons/extrasmall" __alt="Update Banner" __title="Update Banner" __text="Update Banner"}
                    <a href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>
            </fieldset>
        </div>
    </form>
</div>