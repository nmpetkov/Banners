{banners_protofadejs_init element="protofadeshow$blockid" banner=$banner vars=$vars}
<ul id="protofadeshow{$blockid}">
    {foreach from=$banner key='k' item='v' name='loop'}
    <li{if $vars.hovertext} class='banners_protofade_tooltips{$blockid}' title='{$v.hovertext|safehtml} ({$v.clickurl|safehtml})'{/if}>
        <a href='{modurl modname='Banners' func='click' bid=$v.bid}' title=''>
            <img src='{$v.imageurl|safetext}' alt='{$v.clickurl|safetext}' />
        </a>
    </li>
    {/foreach}
</ul>
{assign value=$banner.0.__CATEGORIES__.Main.__ATTRIBUTES__.height var='height'}
{assign value=$banner.0.__CATEGORIES__.Main.__ATTRIBUTES__.width var='width'}
{pageaddvarblock}
<style type="text/css">
    #protofadeshow{{$blockid}} {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
        overflow: hidden;
        height: {{$height}}px;
        width: {{$width}}px;
    }
    #protofadeshow{{$blockid}} li {
        position: absolute;
        top: 0;
        left: 0;
        margin: 0;
        padding: 0;
        background: none;
    }
    {{if $vars.controls eq 'true'}}
    .next,
    .previous,
    .start,
    .stop {
        cursor: pointer;
        display: block;
        margin: 5px 15px 15px 15px;
        float: left;
    }
    .controls {
        margin-bottom: 1.5em;
    }
    {{/if}}
</style>
{/pageaddvarblock}
{if $vars.hovertext}
{ajaxheader ui=true}
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.banners_protofade_tooltips{{$blockid}}'));
</script>
{else}
    {ajaxheader}
{/if}