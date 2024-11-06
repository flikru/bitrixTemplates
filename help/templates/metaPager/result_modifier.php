<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/*
 * Передать данные в component_epilog.php
 * */
$cmp = $this->__component;
if(isset($arResult['NAV_RESULT']->NavPageNomer)){
    $cmp->arResult['NAV_ACTIVE_PAGE']=$arResult['NAV_RESULT']->NavPageNomer;
    $cmp->SetResultCacheKeys(['NAV_ACTIVE_PAGE']);
}

?>