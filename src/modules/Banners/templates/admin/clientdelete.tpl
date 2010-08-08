{*  $Id: banners_admin_clientdelete.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=editdelete.gif set=icons/large alt='Delete Client' altml=true}</div>
    <h2>{gt text="Delete Client"}</h2>
    <div>{gt text="Delete Client"}: {$client.name|safetext}</div>
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="deleteclient"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="cid" value="{$client.cid|safetext}" />
            <p>
            {if $banners}
                <strong>{gt text="Warning!!!"}</strong> {gt text="This client has the following active banners running now"}

            {else}
                {gt text="This client doesn't have any banners running now."}
            {/if}
            </p>
            {if $banners}
            <ul>
                {section name="banners" loop=$banners}
                <li>
                    <a href="{$banners[banners].clickurl|safetext}"><img src="{$banners[banners].imageurl|safetext}" alt="" title="{$banners[banners].clickurl}" /></a>
                    <br />{$banners[banners].clickurl|safetext}
                </li>
                {/section}
            </ul>
            {/if}
            <p>{gt text="You are about to delete the client and all its banners!<br />Do you really want to delete this client?"}</p>
            <div class="z-buttons z-formbuttons">
                {button src="button_ok.gif" set="icons/extrasmall" __alt="Delete Client" __title="Delete Client" __text="Delete Client"}
                <a href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>