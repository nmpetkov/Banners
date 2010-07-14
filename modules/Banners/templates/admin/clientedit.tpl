{*  $Id: banners_admin_clientedit.htm 9 2008-11-05 21:42:16Z Guite $  *}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{img modname=core src=edit.gif set=icons/large alt='Edit Client' altml=true}</div>
<h2>{gt text="Edit Client"}</h2>
<form class="z-adminform" action="{modurl modname="Banners" type="admin" func="updateclient"}" method="post" enctype="application/x-www-form-urlencoded">
<div>
	<input type="hidden" name="client[cid]" value="{$cid|varprepfordisplay}" />
	<input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
   	<div class="z-adminformrow">
		<label for="cname">{gt text="Client Name"}</label>
		<input type="text" id="cname" name="client[cname]" value="{$name|varprepfordisplay}" size="30" maxlength="60" />
    </div>
   	<div class="z-adminformrow">
		<label for="contact">{gt text="Contact Name"}</label>
		<input type="text" id="contact" name="client[contact]" value="{$contact|varprepfordisplay}" size="30" maxlength="60" />
    </div>
   	<div class="z-adminformrow">
		<label for="email">{gt text="Contact e-mail"}</label>
		<input type="text" id="email" name="client[email]" size="30" maxlength="60" value="{$email|varprepfordisplay}" />
    </div>
   	<div class="z-adminformrow">
		<label for="login">{gt text="Client Login Name"}</label>
		<input type="text" id="login" name="client[login]" size="12" maxlength="10" value="{$login|varprepfordisplay}" />
    </div>
   	<div class="z-adminformrow">
		<label for="passwd">{gt text="Client Password"}</label>
		<input type="text" id="passwd" name="client[passwd]" size="12" maxlength="10" value="{$passwd|varprepfordisplay}" />
    </div>
   	<div class="z-adminformrow">
		<label for="extrainfo">{gt text="Extra Information"}</label>
		<textarea id="extrainfo" name="client[extrainfo]" cols="50" rows="10">{$extrainfo|varprepfordisplay}</textarea>
    </div>
    <div class="z-buttons z-formbuttons">
    {button src=button_ok.gif set=icons/small __alt='Update Client' __title='Update'}
    <a href="{modurl modname=Banners type=admin func=view}">{img modname=core src=button_cancel.gif set=icons/small altml=true titleml=true __alt="Cancel" __title="Cancel"}</a>
    </div>
</div>
</form>
</div>