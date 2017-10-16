{foreach from=$css_config  key=key item=item}
    {assign var='cssList' value={$STATIC_PRE|cat:$item} }
    {if $smarty.const.STATIC_FROM=='yes'}
        {$cssList=$cssList|regex_replace:'/\.css/':'.min.css'}
    {/if}
    <link href="{$cssList}?{file_createtime path='/Admin'|cat:$cssList}" type="text/css" rel="stylesheet">
{/foreach}