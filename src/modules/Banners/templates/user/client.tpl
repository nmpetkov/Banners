{*  $Id: banners_user_config.htm 9 2008-11-05 21:42:16Z Guite $  *}
{insert name="getstatusmsg"}
<h1>{gt text='Banner Ad Statistics.'}</h1>
<h2>{gt text="Currently active banners for %s." tag1=$client.name}</h2>
<h3>{gt text="Summary"}</h3>
{configgetvar name="sitename" assign=sitename}
<p>{gt text="You have the following banners running on %s." tag1=$sitename}</p>
<table width="100%" border="1">
	<tr>
		<th>{gt text="ID"}</th>
	    <th>{gt text="Impressions Made"}</th>
	    <th>{gt text="Imp."}</th>
	    <th>{gt text="Imp. Left"}</th>
	    <th>{gt text="Clicks"}</th>
	    <th>{gt text="% Clicks"}</th>
	    <th>{gt text="Options"}</th>
	 </tr>
	 {section name="banners" loop=$banners}
     <tr>
    	<td>{$banners[banners].bid|safetext}</td>
		<td>{$banners[banners].impmade|safetext}</td>
		<td>{$banners[banners].imptotal|safetext}</td>
		<td>{$banners[banners].impleft|safetext}</td>
		<td>{$banners[banners].clicks|safetext}</td>
		<td>{$banners[banners].percent|safetext}</td>
		<td><a href="{modurl modname="Banners" type="user" func="emailstats" cid=$client.cid bid=$banners[banners].bid|safetext}">{gt text="Email Stats"}</a></td>
	 </tr>
	{/section}
</table>

<h3>{gt text="Banners Update"}</h3>
<ul style="list-style:none">
{section name="banners" loop=$banners}
<li>
<div><img src="{$banners[banners].imageurl|safetext}" alt="" /></div>
<div>{gt text="ID"}: {$banners[banners].bid|safetext}</div>
<div>{gt text='Banner URL'}: <a href="{$banners[banners].clickurl|safetext}">{$banners[banners].clickurl|safetext}</a></div>
{modurl modname="Banners" type="user" func="emailstats" cid=$client.cid bid=$banners[banners].bid assign=statsurl}
<div>{gt text="Send <a href='%s'>stats</a> for this banner." tag1=$statsurl }</div>
<form action="{modurl modname="Banners" type="user" func="changeurl"}" method="post" enctype="application/x-www-form-urlencoded">
<div>
	<label for="changeurl_{$banners[banners].bid|safetext}">{gt text="Change URL"}</label>:
	<input type="text" id="changeurl_{$banners[banners].bid|safetext}" name="url" size="50" maxlength="200" value="{$banners[banners].clickurl|safetext}" />
	<input type="hidden" name="bid" value="{$banners[banners].bid|safetext}" />
	<input type="hidden" name="cid" value="{$client.cid|safetext}" />
	<input name="submit" type="submit" value="{gt text="Submit"}" />
</div>
</form>
</li>
{/section}
</ul>