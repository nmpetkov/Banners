{*  $Id: banners_admin_view.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large alt='_BANNERS_ACTIVE' altml=true}</div>
    {if $bannersenabled eq 0}
    <p>
        <em><strong>{gt text="Important note!"}</strong></em>
        <strong>{gt text="Banners are currently inactive."}</strong>
        {gt text="To active banners, please check your configuration."}
    </p>
    {/if}
    <h2>{gt text="Banners active"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="ID"}</th>
                <th>{gt text="Name"}</th>
                <th>{gt text="Impressions"}</th>
                <th>{gt text="Impressions Left"}</th>
                <th>{gt text="Clicks"}</th>
                <th>{gt text="Clicks Percent"}</th>
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Banner Type"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="activebanneritems" loop=$activebanneritems}
            <tr class="{cycle values=z-odd,z-even name=activebanners}">
                <td>{$activebanneritems[activebanneritems].bid|safetext}</td>
                <td>{$activebanneritems[activebanneritems].title|safetext}</td>
                <td>{$activebanneritems[activebanneritems].impmade|safetext}</td>
                <td>
			{if $activebanneritems[activebanneritems].imptotal eq 0}{gt text='Unlimited Impression'}{else}{math equation="x-y" x=$activebanneritems[activebanneritems].imptotal y=$activebanneritems[activebanneritems].impmade}{/if}
                </td>
                <td>{$activebanneritems[activebanneritems].clicks|safetext}</td>
                <td>
			{strip}
			{if $activebanneritems[activebanneritems].clicks neq 0}
			{math equation="(100*x)/y" x=$activebanneritems[activebanneritems].clicks y=$activebanneritems[activebanneritems].impmade}
			{else}
			0
			{/if}%
			{/strip}
                </td>
                <td>{$activebanneritems[activebanneritems].cname|safetext}</td>
                <td>{$activebanneritems[activebanneritems].type|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="modify" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=xedit.gif alt=_EDIT altml=true}</a>
                    <a href="{modurl modname="Banners" type="admin" func="delete" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif alt=_DELETE altml=true}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="9">{gt text='No Items Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Completed Campaigns"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="Impressions"}</th>
                <th>{gt text="Clicks"}</th>
                <th>{gt text="Click Percent"}</th>
                <th>{gt text="Date Started"}</th>
                <th>{gt text="Date Ended"}</th>
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Banner Type"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="finishedbanners" loop=$finishedbanners}
            <tr class="{cycle values=z-odd,z-even name=finishedbanners}">
                <td>{$finishedbanners[finishedbanners].impressions|safetext}</td>
                <td>{$finishedbanners[finishedbanners].clicks|safetext}</td>
                <td>{$finishedbanners[finishedbanners].percent|safetext}</td>
                <td>{$finishedbanners[finishedbanners].datestart|safetext}</td>
                <td>{$finishedbanners[finishedbanners].dateend|safetext}</td>
                <td>{$finishedbanners[finishedbanners].cname|safetext}</td>
                <td>{$activebanneritems[activebanneritems].type|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="deletefinished" authid="$authid" bid=""}{$finishedbanners[finishedbanners].bid|safetext}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif alt=_DELETE altml=true}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="8">{gt text='No Items Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Clients"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Number of Active Banners"}</th>
                <th>{gt text="Contact Name"}</th>
                <th>{gt text="Contact eMail"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="activeclients" loop=$activeclients}
            <tr class="{cycle values=z-odd,z-even name=activeclients}">
                <td>{$activeclients[activeclients].name|safetext}</td>
                <td>{$activeclients[activeclients].bannercount|safetext|default:"0"}</td>
                <td>{$activeclients[activeclients].contact|safetext}</td>
                <td>{$activeclients[activeclients].email|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="modifyclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=xedit.gif alt=_EDIT altml=true}</a>
                    <a href="{modurl modname="Banners" type="admin" func="deleteclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif alt=_DELETE altml=true}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="5">{gt text='No Items Found'}</td></tr>
	{/section}
        </tbody>
    </table>
</div>
