{*  $Id: banners_admin_new.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-container">
    <div class="z-pageicon">{img modname=core src=filenew.gif set=icons/large alt='_BANNERS_CREATE' altml=true}</div>
    {if $bannersenabled eq 0}
    <p>
        <em><strong>{gt text="Important note!"}</strong></em>
        <strong>{gt text="Banners are currently inactive."}</strong>
        {gt text="To active banners, please check your configuration."}
    </p>
    {/if}
    {if $clients}
    <h2>{gt text="Add a new banner"}</h2>
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="create"}" method="post" enctype="application/x-www-form-urlencoded">
          <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
                   <div class="z-formrow">
                <label for="clientlist">{gt text="Client Name"}</label>
                <select id="clientlist" name="banner[cid]">
			{html_options options=$clients}
                </select>
            </div>
            <div class="z-formrow">
                <label for="name">{gt text="Banner Name"}</label>
                <input type="text" id="name" name="banner[name]" size="50" maxlength="250" />
            </div>
            <div class="z-formrow">
                <label for="impressions">{gt text="Impressions Purchased"}</label>
                <input type="text" id="impressions" name="banner[imptotal]" size="12" maxlength="11" /> 0 = {gt text="Unlimited"}
            </div>
            <div class="z-formrow">
                <label for="bannertype">{gt text="Banner Type"}</label>
                <input type="text" id="bannertype" name="banner[idtype]" size="2" maxlength="2" />
            </div>
            <div class="z-formrow">
                <label for="imgurl">{gt text="Image URL"}</label>
                <input type="text" id="imgurl" name="banner[imageurl]" size="50" maxlength="250" />
            </div>
            <div class="z-formrow">
                <label for="clickurl">{gt text="Click URL"}</label>
                <input type="text" id="clickurl" name="banner[clickurl]" size="50" maxlength="250" />
            </div>
            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/small __alt="Add Client" __title="Add Client"}
                <a href="{modurl modname=Banners type=admin func=view}">{img modname=core src=button_cancel.gif set=icons/small altml=true titleml=true __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
    {/if}
    <h2>{gt text="Add a New Client"}</h2>
    <form class="z-form" action="{modurl modname="Banners" type="admin" func="createclient"}" method="post" enctype="application/x-www-form-urlencoded">
          <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
                   <div class="z-formrow">
                <label for="client">{gt text="Client Name"}</label>
                <input type="text" id="client" name="client[cname]" size="30" maxlength="60" />
            </div>
            <div class="z-formrow">
                <label for="contact">{gt text="Contact Name"}</label>
                <input type="text" id="contact" name="client[contact]" size="30" maxlength="60" />
            </div>
            <div class="z-formrow">
                <label for="email">{gt text="Contact eMail"}</label>
                <input type="text" id="email" name="client[email]" size="30" maxlength="60" />
            </div>
            <div class="z-formrow">
                <label for="login">{gt text="Client Login Name"}</label>
                <input type="text" id="login" name="client[login]" size="12" maxlength="10" />
            </div>
            <div class="z-formrow">
                <label for="pass">{gt text="Client Password"}</label>
                <input type="text" id="pass" name="client[passwd]" size="12" maxlength="10" />
            </div>
            <div class="z-formrow">
                <label for="xinfo">{gt text="Extra Information"}</label>
                <textarea id="xinfo" name="client[extrainfo]" cols="50" rows="10"></textarea>
            </div>
            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/small __alt="Add Client" __title="Add Client"}
                <a href="{modurl modname=Banners type=admin func=view}">{img modname=core src=button_cancel.gif set=icons/small altml=true titleml=true __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
</div>