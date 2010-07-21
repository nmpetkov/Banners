{*  $Id: banners_admin_modifyconfig.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{img modname=core src=configure.gif set=icons/large alt='_MODIFYCONFIG' altml=true}</div>
<h2>{gt text="Modify Banners Configuration"}</h2>
<form class="z-form" action="{modurl modname="Banners" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
        
        <fieldset>
            <legend>{gt text='General settings'}</legend>
            <div class="z-formrow">
                <label for="banners">{gt text="Banners active"}</label>
                <input type="checkbox" id="banners" name="banners" value="1" {if $banners} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="enablecats">{gt text="Enable Categorization"}</label>
                <input type="checkbox" id="enablecats" name="enablecats" value="1" {if $enablecats} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="banners">{gt text="Banner clicks launch new window"}</label>
                <input type="checkbox" id="openinnewwinow" name="openinnewwindow" value="1" {if $openinnewwindow} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="myIP">{gt text="Your IP to not count the hits"}</label>
                <input type="text" id="myIP" name="myIP" value="{$myIP|safetext}" size="30" />
            </div>
            {modcallhooks hookobject=module hookaction=modifyconfig module=Banners}
            <div class="z-buttons z-formbuttons">
                {button src="button_ok.gif" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>
</form>
</div>