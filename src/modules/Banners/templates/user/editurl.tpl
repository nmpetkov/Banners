<h2>{gt text='Banners'}</h2>
<h3>{gt text="Edit Banner URL"}</h3>
<h4>{$banner.title|safetext}</h4>
<form class="z-form" action="{modurl modname="Banners" type="user" func="changeurl"}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend>{gt text="Edit Banner URL"}</legend>
        <img class="z-formnote" src="{$banner.imageurl|safetext}" alt="" />
        <div class="z-formrow">
            <label for="changeurl">{gt text="Click URL"}</label>
            <input type="text" id="changeurl" name="url" size="50" maxlength="200" value="{$banner.clickurl|safetext}" />
            <input type="hidden" name="bid" value="{$banner.bid|safetext}" />
            <input type="hidden" name="cid" value="{$client.cid|safetext}" />
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Change URL" __title="Change URL" __text="Change URL"}
        <a class='z-btred' href="{modurl modname="Banners" type='user' func='client'}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
</form>