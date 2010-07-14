{*  $Id: banners_userapi_emailstats_body.htm 9 2008-11-05 21:42:16Z Guite $  *}
{configgetvar name=sitename assign=sitename}
{gt text='The following are the complete stats for your advertising investment at %site%' site=$sitename}

{gt text='Client Name'}: {$client.name}
{gt text='Banner ID'}: {$banner.bid}
{gt text='Banner Image'}: {$banner.imageurl}
{gt text='Banner Click URL}: {$banner.clickurl}

{gt text='Impressions Purchased'}: {$banner.imptotal}
{gt text='Impressions Made'}: {$banner.impmade}
{gt text='Impressions remaining'}: {$banner.left}
{gt text='Banner Clicks'}: {$banner.clicks}
{gt text='Banner Click Percentage}: {$banner.percent}%

{gt text=''Report Generated on %date%' date=$date}