{*  $Id: banners_block_banners.htm 9 2008-11-05 21:42:16Z Guite $  *}
<div {if $hovertext}class='tooltips' title='{$banner.hovertext|safehtml} ({$banner.clickurl|safehtml})' {/if}style="text-align:center">{$banner.displaystring}</div>
{if $hovertext}
{ajaxheader module="Banners" ui=true}
<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips'));
</script>
{/if}