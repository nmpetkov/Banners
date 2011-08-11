{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{gt text="Delete client"}</h3>
</div>

<p class="z-warningmsg">{gt text="You are about to delete the client and all its banners!<br />Do you really want to delete this client?"}</p>
            
<form class="z-form" action="{modurl modname="Banners" type="admin" func="deleteclient"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <input type="hidden" name="confirmation" value="1" />
        <input type="hidden" name="cid" value="{$client.cid|safetext}" />
        <fieldset class="z-center">
            <legend>{gt text="Delete Client"}: {$client.name|safetext}</legend>
            <p>
                {if $banners}
                <strong>{gt text="Warning!!!"}</strong>
                <br />
                {gt text="This client has the following active banners running now"}
                {else}
                {gt text="This client doesn't have any banners running now."}
                {/if}
            </p>
            {if $banners}
            {section name="banners" loop=$banners}
            <div>
                <a href="{$banners[banners].clickurl|safetext}"><img src="{$banners[banners].imageurl|safetext}" alt="" title="{$banners[banners].clickurl}" /></a>
                <br />{$banners[banners].clickurl|safetext}
                <hr />
            </div>
            {/section}
            {/if}
        </fieldset>
        <div class="z-buttons z-center">
            {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Delete Client" __title="Delete Client" __text="Delete Client"}
            <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}