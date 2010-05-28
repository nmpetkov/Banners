{*  $Id: banners_user_config.htm 9 2008-11-05 21:42:16Z Guite $  *}
{insert name="getstatusmsg"}
<h1>{gt text='Banner Ad Statistics.'}</h1>
<h2>{gt text="Currently active banners for %client%." client=$name}</h2>
<h3>{gt text="Summary"}</h3>
{configgetvar name="sitename" assign=sitename}
<p>{gt text="You have the following banners running on %sitename%." sitename=$sitename}</p>
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
    	<td>{$banners[banners].bid|varprepfordisplay}</td>
		<td>{$banners[banners].impmade|varprepfordisplay}</td>
		<td>{$banners[banners].imptotal|varprepfordisplay}</td>
		<td>{$banners[banners].impleft|varprepfordisplay}</td>
		<td>{$banners[banners].clicks|varprepfordisplay}</td>
		<td>{$banners[banners].percent|varprepfordisplay}</td>
		<td><a href="{modurl modname="Banners" type="user" func="emailstats" login="$login" pass="$pass" cid="$cid" bid=""}{$banners[banners].bid|varprepfordisplay}">{gt text="Email Stats"}</a></td>
	 </tr>
	{/section}
</table>

<h3>{gt text=_BANNERS_UPDATE}</h3>
<ul style="list-style:none">
{section name="banners" loop=$banners}
<li>
<div><img src="{$banners[banners].imageurl|varprepfordisplay}" alt="" /></div>
<div>{gt text="ID"}: {$banners[banners].bid|varprepfordisplay}</div>
<div>{gt text='Banner URL'}: <a href="{$banners[banners].clickurl|varprepfordisplay}">{$banners[banners].clickurl|varprepfordisplay}</a></div>
{modurl modname="Banners" type="user" func="emailstats" login=$login pass=$pass cid=$cid bid=$banners[banners].bid assign=statsurl}
<div>{gt text="Send <a href="%url%">stats</a> for this banner." url=$statsurl html=true }</div>
<form action="{modurl modname="Banners" type="user" func="changeurl"}" method="post" enctype="application/x-www-form-urlencoded">
<div>
	<label for="changeurl_{$banners[banners].bid|varprepfordisplay}">{gt text="Change URL"}</label>:
	<input type="text" id="changeurl_{$banners[banners].bid|varprepfordisplay}" name="url" size="50" maxlength="200" value="{$banners[banners].clickurl|varprepfordisplay}" />
	<input type="hidden" name="bid" value="{$banners[banners].bid|varprepfordisplay}" />
	<input type="hidden" name="login" value="{$login|varprepfordisplay}" />
	<input type="hidden" name="pass" value="{$pass|varprepfordisplay}" />
	<input type="hidden" name="cid" value="{$cid|varprepfordisplay}" />
	<input name="submit" type="submit" value="{gt text="Submit"}" />
</div>
</form>
</li>
{/section}
</ul>