{ajaxheader ui=true}
{assign value='base' var='jquerytheme'}

{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="edit" size="small"}
    <h3>{gt text="Edit banner"}</h3>
</div>

<div style="text-align:center"><img src="{$banner.imageurl|safetext}" alt="" title="{$banner.clickurl|safetext}" /></div>
<form class="z-form" action="{modurl modname="Banners" type="admin" func="update"}" method="post" enctype="multipart/form-data">
    <div>
        <fieldset>
            <legend>{gt text='Edit Banner ID %s' tag1=$banner.bid|safetext}</legend>
            <input type="hidden" name="banner[bid]" value="{$banner.bid|safetext}" />
            <input type="hidden" name="banner[imptotal]" value="{$banner.imptotal|safetext}" />
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <div class="z-formrow">
                <label for="banners_cid">{gt text="Client Name"}</label>
                <select id="banners_cid" name="banner[cid]">
                    <option value="">&nbsp;</option>
                    {html_options options=$clients selected=$banner.cid}
                </select>
            </div>
            {if (($banner.imptotal > 0) || (($banner.imptotal == 0) and ($banner.limit == 1)))}
            <div class="z-formrow">
                <label for="banners_addimp">{gt text="Add more impressions"}</label>
                <input type="text" id="banners_addimp" name="banner[impadded]" size="12" maxlength="11" />
                {gt text="Purchased"}:{$banner.imptotal|safetext}
                {gt text="Impressions Made"}:{$banner.impmade|safetext}
            </div>
            <div class="z-formrow">
                <label for="banners_unlimit">{gt text="Convert to unlimited"}</label>
                <input type="checkbox" id="banners_unlimit" name="banner[unlimit]" value="1" />
            </div>
            {else}
            <input type="hidden" name="banner[impadded]" value="0" />
            {/if}
            <div class="z-formrow">
                <label for="banners_name">{gt text="Banner Name"}</label>
                <input type="text" id="banners_name" name="banner[title]" size="50" maxlength="250" value="{$banner.title|safetext}" />
            </div>
            {if $modvars.Banners.enablecats}
            <div class="z-formrow">
                <label>{gt text='Banner Type'}</label>
                {nocache}
                {foreach from=$catregistry key='property' item='category'}
                {array_field assign="selectedValue" array=$selectedcatsarray field=$property}
                <div class="z-formnote">{selector_category category=$category name="banner[__CATEGORIES__][$property]" field="id" selectedValue=$selectedValue defaultValue="0" editLink=1}</div>
                {/foreach}
                {/nocache}
            </div>
            {/if}
            <div class="z-formrow">
                <label for="banners_active">{gt text="Banner is Active"}</label>
                <input type="checkbox" id="banners_active" name="banner[active]" value="1" {if $banner.active} checked="checked"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="banners_imgurl">{gt text="Image URL"}</label>
                <input type="text" id="banners_imgurl" name="banner[imageurl]" size="40" maxlength="255" value="{$banner.imageurl|safetext}" />
            </div>
            <div class="z-formrow">
                <label for="imgfile">&nbsp;</label>
                <input type="file" id="imgfile" name="imagefile"/>
                <em class="z-formnote z-sub">{gt text="Alternativelly you can upload an image here"}</em>
            </div>
            <div class="z-formrow">
                <label for="banners_clickurl">{gt text="Click URL"}</label>
                <input type="text" id="banners_clickurl" name="banner[clickurl]" size="40" maxlength="255" value="{$banner.clickurl|safetext}" />
            </div>
            <div class="z-formrow">
                <label for="banners_hovertext">{gt text="Hover text"}</label>
                <input type="text" id="banners_hovertext" name="banner[hovertext]" size="40" maxlength="255" value="{$banner.hovertext|safetext}" />
            </div>
            <div class="z-formrow">
                <label for="banners_enddate">{gt text='End Date'}</label>
                {nocache}
		{jquery_datepicker 
                    defaultdate=$banner.enddate 
                    displayelement='banners_enddate' 
                    object='banner' 
                    valuestorageelement='enddate'
                    theme=$jquerytheme 
                    autoSize='true'
                    onselectcallback='updateFields(this,dateText);'}
{/nocache}	
                <div class="z-formnote z-sub">{gt text='The expiration date. Leave empty for no expiration. Banners who reach the expiration date will become inactive automatically.'}</div>
            </div>
        </fieldset>
        <div class="z-buttons z-formbuttons">
            {button class='z-btgreen' src="button_ok.png" set="icons/extrasmall" __alt="Update Banner" __title="Update Banner" __text="Update Banner"}
            <a class='z-btred' href="{modurl modname="Banners" type="admin" func="overview"}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}