{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="new" size="small"}
    <h3>{gt text="Add banner"}</h3>
</div>

{if $modvars.Banners.banners eq 0}
<div class="z-warningmsg">
    <em><strong>{gt text="Important!"}</strong></em>
    <strong>{gt text="Banner display is currently turned off."}</strong>
    {gt text="To turn on banner display, please check your"} <a href='{modurl modname="Banners" type="admin" func="modifyconfig"}'>{gt text='settings'}</a>
</div>
{/if}
<form class="z-form" action="{modurl modname="Banners" type="admin" func="create"}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <div class="z-formrow">
            <label for="clientlist">{gt text="Client Name"}</label>
            <select id="clientlist" name="banner[cid]">
                {html_options options=$clients}
            </select>
        </div>
        <div class="z-formrow">
            <label for="name">{gt text="Banner Name"}</label>
            <input type="text" id="name" name="banner[title]" size="50" maxlength="250" />
        </div>
        <div class="z-formrow">
            <label for="impressions">{gt text="Impressions Purchased"}</label>
            <input type="text" id="impressions" name="banner[imptotal]" size="12" maxlength="11" /> 0 = {gt text="Unlimited"}
        </div>
        {if $modvars.Banners.enablecats}
            <div class="z-formrow">
                <label for="banners_type">{gt text='Banner Type'}</label>
                {nocache}
                <span><ul id='banners_type' style='list-style:none; margin: 0;'>
                {foreach from=$catregistry key='property' item='category'}
                    {* array_field assign="selectedValue" array=$banner.__CATEGORIES__ field=$property *}
                    <li>{selector_category
                            category=$category
                            name="banner[__CATEGORIES__][$property]"
                            field="id"
                            defaultValue="0"
                            editLink=1}</li>
                {/foreach}
                </ul></span>
                {/nocache}
            </div>
        {/if}
        <div class="z-formrow">
            <label for="active">{gt text="Banner is Active"}</label>
            <input type="checkbox" id="active" name="banner[active]" value="1" checked="checked" />
        </div>
        <div class="z-formrow">
            <label for="imgurl">{gt text="Image URL"}</label>
            <input type="text" id="imgurl" name="banner[imageurl]" size="50" maxlength="250" />
        </div>
        <div class="z-formrow">
            <label for="clickurl">{gt text="Click URL"}</label>
            <input type="text" id="clickurl" name="banner[clickurl]" size="50" maxlength="250" />
        </div>
        <div class="z-formrow">
            <label for="hovertext">{gt text="Hover text"}</label>
            <input type="text" id="hovertext" name="banner[hovertext]" size="50" maxlength="250" />
        </div>
        <div class="z-buttons z-formbuttons">
           {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Add Banner" __title="Add Banner" __text="Add Banner"}
            <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </fieldset>
</form>
{adminfooter}