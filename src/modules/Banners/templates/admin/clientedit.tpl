{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=edit.gif set=icons/large alt='Edit Client' altml=true}</div>
    <h2>{gt text="Edit Client"}</h2>
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="updateclient"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="client[cid]" value="{$client.cid|safetext}" />
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
            <fieldset>
                <div class="z-formrow">
                    <label for="name">{gt text="Client Business Name"}</label>
                    <input type="text" id="name" name="client[name]" value="{$client.name|safetext}" size="30" maxlength="60" />
                </div>
                <div class="z-formrow">
                    <label for="contact">{gt text="Client Real Name"}</label>
                    <input type="text" id="contact" name="client[contact]" value="{$client.contact|safetext}" size="30" maxlength="60" />
                </div>
                <div class="z-formrow">
                    <label for="uid">{gt text="Associate Zikula User Name"}</label>
                    <span>{selector_user id="uid" name="client[uid]" selectedValue=$client.uid}</span>
                </div>                <div class="z-formrow">
                    <label for="extrainfo">{gt text="Extra Information"}</label>
                    <textarea id="extrainfo" name="client[extrainfo]" cols="50" rows="10">{$client.extrainfo|safetext}</textarea>
                </div>
                <div class="z-buttons z-formbuttons">
                    {button src="button_ok.gif" set="icons/extrasmall" __alt="Update Client" __title="Update Client" __text="Update Client"}
                    <a href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>
            </fieldset>
        </div>
    </form>
</div>