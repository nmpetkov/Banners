{ajaxheader imageviewer=true}
{insert name="getstatusmsg"}
<h2>{gt text='Banners Statistics'}</h2>
<h3>{gt text="Current banners for %s" tag1=$client.name}</h3>
{configgetvar name="sitename" assign='sitename'}
<p>{gt text="You have the following banners on %s" tag1=$sitename}</p>
<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text="Banner"}</th>
            <th colspan='3'>{gt text="Impressions"}</th>
            <th colspan='2'>{gt text="Clicks"}</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>{gt text="Title (#ID)"}</th>
            <th>{gt text="Purchased"}</th>
            <th>{gt text="Made"}</th>
            <th>{gt text="Remaining"}</th>
            <th>{gt text="Registered"}</th>
            <th>{gt text="% Clicks/Imp"}</th>
            <th class="z-nowrap z-right">{gt text="Options"}</th>
        </tr>
    </thead>
    <tbody>
        {section name="banners" loop=$banners}
        <tr class='{cycle values="z-even,z-odd"}'>
            <td>
                {img src=$banners[banners].led modname='core' set='icons/extrasmall'}
                {$banners[banners].title|safetext} (#{$banners[banners].bid|safetext})
            </td>
            <td>{$banners[banners].imptotal|safetext}</td>
            <td>{$banners[banners].impmade|safetext}</td>
            <td {$banners[banners].impleft_fontstyle}>{$banners[banners].impleft|safetext}</td>
            <td>{$banners[banners].clicks|safetext}</td>
            <td>{$banners[banners].percent|safetext}%</td>
            <td class="z-nowrap z-right">
                <a href="{$banners[banners].imageurl|safetext}" rel="imageviewer">{img modname='core' set='icons/extrasmall' src='demo.png' __alt="image" __title='View Image'}</a>
                <a href="{modurl modname="Banners" type="user" func="emailstats" cid=$client.cid bid=$banners[banners].bid|safetext}">{img modname='core' set='icons/extrasmall' src='mail_send.png' __alt="Email Stats" __title="Email Stats"}</a>
                <a href="{modurl modname="Banners" type="user" func="editurl" bid=$banners[banners].bid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __alt="edit URL" __title="Edit URL"}</a>
            </td>
        </tr>
        {sectionelse}
        <tr class="z-datatableempty"><td colspan="7">{gt text='No Banners Found'}</td></tr>
        {/section}
    </tbody>
</table>

<div style='margin-left:2em;'>
    {img src='greenled.png' modname='core' set='icons/extrasmall'}={gt text="active"} | {img src='redled.png' modname='core' set='icons/extrasmall'}={gt text="inactive"}
</div>