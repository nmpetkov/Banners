{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="new" size="small"}
    <h3>{gt text="Add client"}</h3>
</div>

{if $modvars.Banners.banners eq 0}
<div class="z-warningmsg">
    <em><strong>{gt text="Important!"}</strong></em>
    <strong>{gt text="Banner display is currently turned off."}</strong>
    {gt text="To turn on banner display, please check your"} <a href='{modurl modname="Banners" type="admin" func="modifyconfig"}'>{gt text='settings'}</a>
</div>
{/if}
<form class="z-form" action="{modurl modname="Banners" type="admin" func="createclient"}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <div class="z-formrow">
            <label for="client">{gt text="Client Business Name"}</label>
            <input type="text" id="client" name="client[name]" size="30" maxlength="60" />
        </div>
        <div class="z-formrow">
            <label for="client_uid_">{gt text="Associate Zikula User Name"}</label>
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
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Add Client" __title="Add Client" __text="Add Client"}
        <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
</form>
{adminfooter}