<h3>{gt text="Edit Banner URL"}</h3>
<h4>{$banner.title|safetext}</h4>
<div><img src="{$banner.imageurl|safetext}" alt="" /></div>
<form action="{modurl modname="Banners" type="user" func="changeurl"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <label for="changeurl">{gt text="Change URL"}</label>:
        <input type="text" id="changeurl" name="url" size="50" maxlength="200" value="{$banner.clickurl|safetext}" />
        <input type="hidden" name="bid" value="{$banner.bid|safetext}" />
        <input type="hidden" name="cid" value="{$client.cid|safetext}" />
        <input name="submit" type="submit" value="{gt text="Submit"}" />
    </div>
    <a style='padding-left: 10em;' href="{modurl modname="Banners"}" title="{gt text="Cancel"}">
        {img style='margin-top:1em;' modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
    </a>
</form>