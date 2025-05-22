
<div class="search">
    <form name="qseacrh_<?=$arParams["WIDTH"]?>" action="/search/" method="get">
        <a href="javascript:void(0)" onclick="$('form[name=qseacrh_<?=$arParams["WIDTH"]?>]').submit()"
           title="Искать"></a>
        <input class="interactive_search" type="text" name="q" placeholder="Поиск по сайту">
    </form>
    <div class="result-interactive-search" style="display: none"></div>
</div>