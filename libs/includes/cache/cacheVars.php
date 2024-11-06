<?
use \Bitrix\Main\Data\Cache;
//$seoItem = dataCache('zapchasti_seo_'.$APPLICATION->GetCurPage(), "getElementList", 4*60*60, 'zapchastiSEO', 1, [$seoFilter,$seoSelect]);
function dataCache($cacheKey, $function, $cacheTime = 60*60*4, $cachePath='allCache',$reset=0,$args){
    if($reset==1) $cacheTime=1;

    $cache = Cache::createInstance(); // Служба кеширования

    $cacheTtl = $cacheTime; // срок годности кеша (в секундах)
    $cacheKey = $cacheKey; // имя кеша
    $cachePath = $cachePath; // папка, в которой лежит кеш

    if ($cache->initCache($cacheTtl, $cacheKey, $cachePath))
    {
        $vars = $cache->getVars(); // Получаем переменные
        $cache->output(); // Выводим HTML пользователю в браузер
    }
    elseif ($cache->startDataCache())
    {
        //$vars = $function($args);
        $vars = call_user_func_array($function, $args);
        $cacheInvalid = false;
        if ($cacheInvalid or empty($vars))
        {
            $cache->abortDataCache();
        }
        $cache->endDataCache($vars);
    }
    return $vars;
}
?>