{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{img modname=core src=configure.gif set=icons/large alt='_MODIFYCONFIG' altml=true}</div>
<h2>{gt text="Modify Banners Settings"}</h2>
<form class="z-form" action="{modurl modname="Banners" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
        
        <fieldset>
            <legend>{gt text='General settings'}</legend>
            <div class="z-formrow">
                <label for="banners">{gt text="Display Banners"}</label>
                <input type="checkbox" id="banners" name="banners" value="1" {if $modvars.Banners.banners}checked="checked"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="enablecats">{gt text="Enable Categorization"}</label>
                <input type="checkbox" id="enablecats" name="enablecats" value="1" {if $modvars.Banners.enablecats}checked="checked"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="banners">{gt text="Banner clicks launch new window"}</label>
                <input type="checkbox" id="openinnewwinow" name="openinnewwindow" value="1" {if $modvars.Banners.openinnewwindow}checked="checked"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="myIP">{gt text="List of IPs to not count hits"}</label>
                <input type="text" id="myIP" name="myIP" value="{$IPlist|safetext}" />
                <em class="z-sub z-formnote">Enter comma-seperated value list</em>
                <em class="z-sub z-formnote">Your current IP address is <span style='background-color:#ffffbb;'>{$currentip}</span></em>
            </div>
            {* modcallhooks hookobject=module hookaction=modifyconfig module=Banners *}
            <div class="z-buttons z-formbuttons">
                {button class='z-btgreen' src="button_ok.gif" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
                <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </fieldset>
    </div>
</form>
</div>