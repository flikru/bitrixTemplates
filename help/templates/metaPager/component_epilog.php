<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

if(isset($arResult['NAV_ACTIVE_PAGE']) && $arResult['NAV_ACTIVE_PAGE']>1){
    /*
     * Старое ядро
     * */
    $APPLICATION->AddHeadString("<meta name='yandex' content='noindex, follow'/>",false);

    /*
     * D7
     * */
    use \Bitrix\Main\Page\Asset;
    Asset::getInstance()->addString('<li nk rel="stylesheet" type="text/css" href="/css/style.css">');
}
