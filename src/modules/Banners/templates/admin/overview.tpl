{*  $Id: banners_admin_view.htm 9 2008-11-05 21:42:16Z Guite $  *}
{ajaxheader module="Banners" ui=true}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large __alt="Active Banners"}</div>
    {if $bannersenabled eq 0}
    <p>
        <em><strong>{gt text="Important note!"}</strong></em>
        <strong>{gt text="Banners are currently inactive."}</strong>
        {gt text="To active banners, please check your configuration."}
    </p>
    {/if}
    <h2>{gt text="Active Banners"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th></th>
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
                <td>{img src=greenled.gif modname=core set=icons/extrasmall __title="Active" __alt="Active" }</td>
                <td>{$activebanneritems[activebanneritems].bid|safetext}</td>
                <td>{$activebanneritems[activebanneritems].title|safetext}</td>
                <td>{$activebanneritems[activebanneritems].impmade|safetext}</td>
                <td>{$activebanneritems[activebanneritems].impleft|safetext}
                    {if $activebanneritems[activebanneritems].imptotal eq 0}
                        <a href="{modurl modname="Banners" type="admin" func="modify" limit=1 bid=$activebanneritems[activebanneritems].bid}">
                            {img src=remove.gif modname=core set=icons/extrasmall __title="Convert to limited" __alt="Convert to limited" }
                        </a>
                    {/if}
                </td>
                <td>{$activebanneritems[activebanneritems].clicks|safetext}</td>
                <td>{$activebanneritems[activebanneritems].percent|safetext}%</td>
                <td>{$activebanneritems[activebanneritems].name|safetext}</td>
                <td>{$activebanneritems[activebanneritems].typename|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="modify" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt="edit"}</a>
                    <a href="{modurl modname="Banners" type="admin" func="delete" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif __alt="Delete"}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="10">{gt text='No Active Banners Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Inactive Banners"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th></th>
                <th>{gt text="ID"}</th>
                <th>{gt text="Name"}</th>
                <th>{gt text="Impressions"}</th>
                <th>{gt text="Impressions Left"}</th>
                <th>{gt text="Clicks"}</th>
                <th>{gt text="Click Percent"}</th>
                <!-- <th>{gt text="Date Started"}</th>
                <th>{gt text="Date Ended"}</th> -->
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Banner Type"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="finishedbanners" loop=$finishedbanners}
            <tr class="{cycle values=z-odd,z-even name=finishedbanners}">
                <td>{img src=redled.gif modname=core set=icons/extrasmall __title="Inactive" __alt="Inactive" }</td>
                <td>{$finishedbanners[finishedbanners].bid|safetext}</td>
                <td>{$finishedbanners[finishedbanners].title|safetext}</td>
                <td>{$finishedbanners[finishedbanners].impmade|safetext}</td>
                <td>{$finishedbanners[finishedbanners].impleft|safetext}
                    {if $finishedbanners[finishedbanners].imptotal eq 0}
                        <a href="{modurl modname="Banners" type="admin" func="modify" limit=1 bid=$finishedbanners[finishedbanners].bid}">
                            {img src=remove.gif modname=core set=icons/extrasmall __title="Convert to limited" __alt="Convert to limited" }
                        </a>
                    {/if}
                </td>
                <td>{$finishedbanners[finishedbanners].clicks|safetext}</td>
                <td>{$finishedbanners[finishedbanners].percent|safetext}%</td>
                <!-- <td>{$finishedbanners[finishedbanners].datestart|safetext}</td>
                <td>{$finishedbanners[finishedbanners].dateend|safetext}</td> -->
                <td>{$finishedbanners[finishedbanners].name|safetext}</td>
                <td>{$finishedbanners[finishedbanners].typename|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="modify" bid=$finishedbanners[finishedbanners].bid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt="edit"}</a>
                    <a href="{modurl modname="Banners" type="admin" func="deletefinished" authid="$authid" bid=$finishedbanners[finishedbanners].bid|safetext}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif __alt="Delete"}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="10">{gt text='No Inactive Banners Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Clients"}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="Client Name (cid)"}</th>
                <th>{gt text="Number of Active Banners"}</th>
                <th>{gt text="Zikula Username (uid)"}</th>
                <th>{gt text="Contact Name"}</th>
                <th>{gt text="Contact eMail"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="activeclients" loop=$activeclients}
            <tr class="{cycle values=z-odd,z-even name=activeclients}">
                <td class="tooltips" id="clientname_{$activeclients[activeclients].cid}" title="{$activeclients[activeclients].extrainfo}">{$activeclients[activeclients].name|safetext} ({$activeclients[activeclients].cid})</td>
                <td>{$activeclients[activeclients].bannercount|safetext|default:"0"}</td>
                <td>{$activeclients[activeclients].zuname|safetext} ({$activeclients[activeclients].uid})</td>
                <td>{$activeclients[activeclients].contact|safetext}</td>
                <td>{$activeclients[activeclients].email|safetext}</td>
                <td>
                    <a href="{modurl modname="Banners" type="admin" func="modifyclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=xedit.gif alt=_EDIT altml=true}</a>
                    <a href="{modurl modname="Banners" type="admin" func="deleteclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif alt=_DELETE altml=true}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-admintableempty"><td colspan="6">{gt text='No Clients Found'}</td></tr>
	{/section}
        </tbody>
    </table>
</div>
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips'));
</script>