{banners_protofadejs_init element="protofadeshow1"}
{* $banner *}
<ul id="protofadeshow1" style='list-style: none; padding: 0; margin: 0;'>
    {foreach from=$banner key='k' item='v' name='loop'}
    <li{if $hovertext} class='banners_protofade_tooltips' title='{$v.hovertext|safehtml} ({$v.clickurl|safehtml})'{/if}>
        <a href='{modurl modname='Banners' func='click' bid=$v.bid}' title=''>
            <img src='{$v.imageurl|safetext}' alt='{$v.clickurl|safetext}' />
        </a>
    </li>
    {/foreach}
</ul>
{if $hovertext}
{ajaxheader module="Banners" ui=true}
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.banners_protofade_tooltips'));
</script>
{/if}