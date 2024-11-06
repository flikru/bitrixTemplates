<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

if(isset($arResult['NAV_ACTIVE_PAGE']) && $arResult['NAV_ACTIVE_PAGE']>1){
    $APPLICATION->AddHeadString("<meta name='yandex' content='noindex, follow'/>",false);
}
