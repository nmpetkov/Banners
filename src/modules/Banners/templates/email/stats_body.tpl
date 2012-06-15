{gt text='The following are the complete stats for your advertising investment at %s' tag1=$modvars.ZConfig.sitename}

{gt text='Client Name'}: {$client.name}
{gt text='Banner ID'}: {$banner.bid}
{gt text='Banner Image'}: {$banner.imageurl}
{gt text='Banner Click URL'}: {$banner.clickurl}

{gt text='Impressions Purchased'}: {$banner.imptotal}
{gt text='Impressions Made'}: {$banner.impmade}
{gt text='Impressions remaining'}: {$banner.impleft}
{gt text='Banner Clicks'}: {$banner.clicks}
{gt text='Banner Click Percentage'}: {$banner.percent}%

{gt text='Report Generated on %s' tag1=$date}