{*  $Id: banners_admin_new.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=filenew.gif set=icons/large __alt='Create Banners'}</div>
    <h2>{gt text="Add Client"}</h2>
    {if $bannersenabled eq 0}
    <p>
        <em><strong>{gt text="Important note!"}</strong></em>
        <strong>{gt text="Banners are currently inactive."}</strong>
        {gt text="To active banners, please check your configuration."}
    </p>
    {/if}
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="createclient"}" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
            <div class="z-formrow">
                <label for="client">{gt text="Client Business Name"}</label>
                <input type="text" id="client" name="client[name]" size="30" maxlength="60" />
            </div>
            <div class="z-formrow">
                <label for="uid">{gt text="Associate Zikula User Name"}</label>
                <span>{selector_user id="uid" name="client[uid]"}</span>
            </div>
            <div class="z-formrow">
                <label for="contact">{gt text="Client Real Name"}</label>
                <input type="text" id="contact" name="client[contact]" size="30" maxlength="60" />
            </div>
            <div class="z-formrow">
                <label for="xinfo">{gt text="Extra Information"}</label>
                <textarea id="xinfo" name="client[extrainfo]" cols="50" rows="10"></textarea>
            </div>
            <div class="z-buttons z-formbuttons">
                {button src="button_ok.gif" set="icons/extrasmall" __alt="Add Client" __title="Add Client" __text="Add Client"}
                <a href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </form>
</div>