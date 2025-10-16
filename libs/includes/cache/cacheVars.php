<?
use \Bitrix\Main\Data\Cache;
//$result = dataCache('popular_items_'.$APPLICATION->GetCurPage(), "getPopularItems", 4, 'popular_items', [$arFilter,$arSelect]);
//$result = dataCache('popular_items', "getPopularItems", $cacheTime);;
function dataCache($cacheKey, $function, $hours = 4, $cachePath='allCache',$args=[]){

    $cacheTime = 60*60*$hours;

    $cacheTime = !isset($_GET['clear_cache'])? $cacheTime : 1 ;

    $cache = Cache::createInstance();

    if ($cache->initCache($cacheTime, $cacheKey, $cachePath)) {
        echo "cache<br>";
        $vars = $cache->getVars();
        $cache->output();
    }elseif ($cache->startDataCache()){
        //echo "load<br>";
        $vars = call_user_func_array($function, $args);
        $cacheInvalid = false;

        if ($cacheInvalid or empty($vars)){
            $cache->abortDataCache();
        }

        $cache->endDataCache($vars);
    }
    return $vars;
}
?>
