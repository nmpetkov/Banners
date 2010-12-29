{ajaxheader module="Banners" ui=true imageviewer=true}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large __alt="Active Banners"}</div>
    {if $modvars.Banners.banners eq 0}
    <div class="z-warningmsg">
        <em><strong>{gt text="Important!"}</strong></em>
        <strong>{gt text="Banner display is currently turned off."}</strong>
        {gt text="To turn on banner display, please check your"} <a href='{modurl modname="Banners" type="admin" func="modifyconfig"}'>{gt text='settings'}</a>
    </div>
    {/if}
    <h2>{gt text="Active Banners"}</h2>
    <table class="z-datatable">
        <thead>
            <tr>
                <th>{gt text="Banner"}</th>
                <th colspan='3'>Impressions</th>
                <th colspan='2'>Clicks</th>
                <th colspan='3'></th>
            </tr>
            <tr>
                <th>{gt text="Name (#ID)"}</th>
                <th>{gt text="Purchased"}</th>
                <th>{gt text="Made"}</th>
                <th>{gt text="Remaining"}</th>
                <th>{gt text="Registered"}</th>
                <th>{gt text="% Clicks/Imp"}</th>
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Banner Type"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="activebanneritems" loop=$activebanneritems}
            <tr class="{cycle values=z-odd,z-even name=activebanners}">
                <td>{img src=greenled.gif modname=core set=icons/extrasmall __title="Active" __alt="Active"}
                {$activebanneritems[activebanneritems].title|safetext} (#{$activebanneritems[activebanneritems].bid|safetext})</td>
                <td>{$activebanneritems[activebanneritems].imptotal|safetext}</td>
                <td>{$activebanneritems[activebanneritems].impmade|safetext}</td>
                <td {$activebanneritems[activebanneritems].impleft_fontstyle}>{$activebanneritems[activebanneritems].impleft|safetext}
                    {if $activebanneritems[activebanneritems].imptotal eq 0}
                        <a href="{modurl modname="Banners" type="admin" func="modify" limit=1 bid=$activebanneritems[activebanneritems].bid}">
                            {img src=remove.gif modname=core set=icons/extrasmall __title="Convert to limited" __alt="Convert to limited"}
                        </a>
                    {/if}
                </td>
                <td>{$activebanneritems[activebanneritems].clicks|safetext}</td>
                <td>{$activebanneritems[activebanneritems].percent|safetext}%</td>
                <td>{$activebanneritems[activebanneritems].name|safetext}</td>
                <td>{$activebanneritems[activebanneritems].typename|safetext}</td>
                <td>
                    <a href="{$activebanneritems[activebanneritems].imageurl|safetext}" rel="imageviewer">{img modname=core set=icons/extrasmall src=demo.gif __alt="image" __title='View Image'}</a>
                    <a href="{modurl modname="Banners" type="admin" func="modify" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt="edit" __title="Edit Banner"}</a>
                    <a href="{modurl modname="Banners" type="admin" func="delete" bid=$activebanneritems[activebanneritems].bid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif __alt="Delete" __title="Delete Banner"}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-datatableempty"><td colspan="10">{gt text='No Active Banners Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Inactive Banners"}</h2>
    <table class="z-datatable">
        <thead>
            <tr>
                <th>{gt text="Banner"}</th>
                <th colspan='3'>Impressions</th>
                <th colspan='2'>Clicks</th>
                <th colspan='3'></th>
            </tr>
            <tr>
                <th>{gt text="Name (#ID)"}</th>
                <th>{gt text="Purchased"}</th>
                <th>{gt text="Made"}</th>
                <th>{gt text="Remaining"}</th>
                <th>{gt text="Registered"}</th>
                <th>{gt text="% Clicks/Imp"}</th>
                <!-- <th>{* gt text="Date Started" *}</th>
                <th>{* gt text="Date Ended" *}</th> -->
                <th>{gt text="Client Name"}</th>
                <th>{gt text="Banner Type"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {section name="finishedbanners" loop=$finishedbanners}
            <tr class="{cycle values=z-odd,z-even name=finishedbanners}">
                <td>{img src=redled.gif modname=core set=icons/extrasmall __title="Inactive" __alt="Inactive"}
                {$finishedbanners[finishedbanners].title|safetext} (#{$finishedbanners[finishedbanners].bid|safetext})</td>
                <td>{$finishedbanners[finishedbanners].imptotal|safetext}</td>
                <td>{$finishedbanners[finishedbanners].impmade|safetext}</td>
                <td {$finishedbanners[finishedbanners].impleft_fontstyle}>{$finishedbanners[finishedbanners].impleft|safetext}
                    {if $finishedbanners[finishedbanners].imptotal eq 0}
                        <a href="{modurl modname="Banners" type="admin" func="modify" limit=1 bid=$finishedbanners[finishedbanners].bid}">
                            {img src=remove.gif modname=core set=icons/extrasmall __title="Convert to limited" __alt="Convert to limited"}
                        </a>
                    {/if}
                </td>
                <td>{$finishedbanners[finishedbanners].clicks|safetext}</td>
                <td>{$finishedbanners[finishedbanners].percent|safetext}%</td>
                <!-- <td>{* $finishedbanners[finishedbanners].datestart|safetext *}</td>
                <td>{* $finishedbanners[finishedbanners].dateend|safetext *}</td> -->
                <td>{$finishedbanners[finishedbanners].name|safetext}</td>
                <td>{$finishedbanners[finishedbanners].typename|safetext}</td>
                <td>
                    <a href="{$finishedbanners[finishedbanners].imageurl|safetext}" rel="imageviewer">{img modname=core set=icons/extrasmall src=demo.gif __alt="image" __title='View Image'}</a>
                    <a href="{modurl modname="Banners" type="admin" func="modify" bid=$finishedbanners[finishedbanners].bid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt="edit" __title="Edit Banner"}</a>
                    <a href="{modurl modname="Banners" type="admin" func="deletefinished" bid=$finishedbanners[finishedbanners].bid|safetext}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif __alt="Delete" __title="Delete Banner"}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-datatableempty"><td colspan="10">{gt text='No Inactive Banners Found'}</td></tr>
	{/section}
        </tbody>
    </table>

    <h2>{gt text="Clients"}</h2>
    <table class="z-datatable">
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
                    <a href="{modurl modname="Banners" type="admin" func="modifyclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt='Edit' __title='Edit'}</a>
                    <a href="{modurl modname="Banners" type="admin" func="deleteclient" cid=$activeclients[activeclients].cid}">{img modname=core set=icons/extrasmall src=14_layer_deletelayer.gif __alt='Delete' __title='Delete'}</a>
                </td>
            </tr>
	{sectionelse}
            <tr class="z-datatableempty"><td colspan="6">{gt text='No Clients Found'}</td></tr>
	{/section}
        </tbody>
    </table>
</div>
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips'));
</script>