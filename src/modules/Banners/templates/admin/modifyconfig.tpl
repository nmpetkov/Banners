{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text="Banners settings"}</h3>
</div>

<form class="z-form" action="{modurl modname="Banners" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        
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
                <label for="openinnewwinow">{gt text="Banner clicks launch new window"}</label>
                <input type="checkbox" id="openinnewwinow" name="openinnewwindow" value="1" {if $modvars.Banners.openinnewwindow}checked="checked"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="myIP">{gt text="List of IPs to not count hits"}</label>
                <input type="text" id="myIP" name="myIP" value="{$IPlist|safetext}" />
                <em class="z-sub z-formnote">{gt text="Enter comma-seperated value list"}</em>
                <em class="z-sub z-formnote">{gt text="Your current IP address is <span style='background-color:#ffffbb;'>%s</span>" tag1=$currentip}</em>
            </div>
        </fieldset>
        <div class="z-buttons z-formbuttons">
            {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}