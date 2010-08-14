{pnajaxheader imageviewer="true"}
{insert name="getstatusmsg"}
<h1>{gt text='Banner Ad Statistics'}</h1>
<h2>{gt text="Current banners for %s" tag1=$client.name}</h2>
<h3>{gt text="Summary"}</h3>
{configgetvar name="sitename" assign=sitename}
<p>{gt text="You have the following banners on %s" tag1=$sitename}</p>
<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text="Banner"}</th>
            <th colspan='3'>{gt text="Impressions"}</th>
            <th colspan='2'>{gt text="Clicks"}</th>
            <th></th>
        </tr>
        <tr>
            <th>{gt text="Title (#ID)"}</th>
            <th>{gt text="Purchased"}</th>
            <th>{gt text="Made"}</th>
            <th>{gt text="Remaining"}</th>
            <th>{gt text="Registered"}</th>
            <th>{gt text="% Clicks/Imp"}</th>
            <th>{gt text="Options"}</th>
        </tr>
    </thead>
    <tbody>
	{section name="banners" loop=$banners}
        <tr class='{cycle values="z-even,z-odd"}'>
            <td>
                {img src=$banners[banners].led modname=core set=icons/extrasmall}
                {$banners[banners].title|safetext} (#{$banners[banners].bid|safetext})
            </td>
            <td>{$banners[banners].imptotal|safetext}</td>
            <td>{$banners[banners].impmade|safetext}</td>
            <td {$banners[banners].impleft_fontstyle}>{$banners[banners].impleft|safetext}</td>
            <td>{$banners[banners].clicks|safetext}</td>
            <td>{$banners[banners].percent|safetext}%</td>
            <td>
                <a href="{$banners[banners].imageurl|safetext}" rel="imageviewer">{img modname=core set=icons/extrasmall src=demo.gif __alt="image" title='view image'}</a>
                <a href="{modurl modname="Banners" type="user" func="emailstats" cid=$client.cid bid=$banners[banners].bid|safetext}">{img modname=core set=icons/extrasmall src=mail_send.gif __alt="Email Stats" __title="Email Stats"}</a>
                <a href="{modurl modname="Banners" type="user" func="editurl" bid=$banners[banners].bid}">{img modname=core set=icons/extrasmall src=xedit.gif __alt="edit URL" __title="edit URL"}</a>
            </td>
        </tr>
	{/section}
    </tbody>
</table>
&nbsp;&nbsp;{img src=greenled.gif modname=core set=icons/extrasmall}={gt text="active"} | {img src=redled.gif modname=core set=icons/extrasmall}={gt text="deactivated"}