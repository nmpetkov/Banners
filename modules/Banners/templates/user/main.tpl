{*  $Id: banners_user_main.htm 9 2008-11-05 21:42:16Z Guite $  *}
{insert name="getstatusmsg"}
<h1>{gt text="Client Login"}</h1>
<div class="bannersclientlogincontainer">
	<form class="z-form" action="{modurl modname="Banners" type="user" func="client"}" method="post" enctype="application/x-www-form-urlencoded">
	<div>
		<input type="hidden" name="authid" value="{insert name="generateauthkey" module="Banners"}" />
		<div class="z-formrow">
			<label for="login">{gt text="Login Name"}</label>
			<input type="text" id="login" name="login" size="15" maxlength="10" />
		</div>
		<div class="z-formrow">
			<label for="pass">{gt text="Password"}</label>
			<input type="password" id="pass" name="pass" size="15" maxlength="10" />
		</div>
		<div class="z-formrow">
			<input name="submit" type="submit" value="{gt text="Login"}" />
		</div>
	</div>
	</form>
</div>