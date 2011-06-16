{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{icon type="delete" size="large"}</div>
<h2>{gt text="Delete banner"}</h2>
<form class="z-form" action="{modurl modname="Banners" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
<div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="confirmation" value="1" />
    <input type="hidden" name="bid" value="{$banner.bid|safetext}" />
	<a href="{$banner.clickurl|safetext}"><img src="{$banner.imageurl|safetext}" alt="" title="{$banner.clickurl|safetext}" /></a>
	<br /><a href="{$banner.clickurl|safetext}">{$banner.clickurl|safetext}</a>
	<table class="z-datatable">
		<thead>
		<tr>
			<th>{gt text="ID"}</th>
			<th>{gt text="Impressions"}</th>
			<th>{gt text="Impressions Left"}</th>
			<th>{gt text="Clicks"}</th>
			<th>{gt text="% Clicks"}</th>
			<th>{gt text="Client Name"}</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>{$banner.bid|safetext}</td>
			<td>{$banner.impmade|safetext}</td>
			<td>{$banner.impleft|safetext}</td>
			<td>{$banner.clicks|safetext}</td>
			<td>{$banner.percent}%</td>
			<td>{$banner.name|safetext}</td>
		</tr>
		</tbody>
	</table>
	<div class="z-formrow">
		<p><strong>{gt text="Do you really want to delete this banner?"}</strong></p>
	</div>
    <div class="z-buttons z-formbuttons">
        {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Delete Banner" __title="Delete Banner" __text="Delete Banner"}
        <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
</div>
</form>
</div>