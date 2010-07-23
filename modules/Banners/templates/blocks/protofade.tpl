{banners_protofadejs_init element="protofadeshow1"}
{* $banner *}
{ajaxheader ui=$hovertext}
<ul id="protofadeshow1">
    {foreach from=$banner key='k' item='v' name='loop'}
    <li{if $hovertext} class='banners_protofade_tooltips' title='{$v.hovertext|safehtml} ({$v.clickurl|safehtml})'{/if}>
        <a href='{modurl modname='Banners' func='click' bid=$v.bid}' title=''>
            <img src='{$v.imageurl|safetext}' alt='{$v.clickurl|safetext}' />
        </a>
    </li>
    {/foreach}
</ul>
{assign value=$banner.0.__CATEGORIES__.Main.__ATTRIBUTES__.width var='height'}
{assign value=$banner.0.__CATEGORIES__.Main.__ATTRIBUTES__.length var='width'}
<style type="text/css">
    #protofadeshow1 {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
        overflow: hidden;
        height: 48px;
        width: 48px;
    }
    #protofadeshow1 li {
        position: absolute;
        top: 0;
        left: 0;
        margin: 0;
        padding: 0;
        background: none;
    }
</style>
{if $hovertext}
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.banners_protofade_tooltips'));
</script>
{/if}