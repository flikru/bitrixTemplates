<?php

AddEventHandler('main', 'OnEndBufferContent', 'replaceCityInMetaTagsInFinalHTML');

function replaceCityInMetaTagsInFinalHTML(&$content)
{
    if (defined('ADMIN_SECTION') || PHP_SAPI === 'cli') {
        return;
    }

    $city = getCityInfo();

    foreach ($city as $key=>$cityInCase) {
        $content = str_replace("{" . $key . "}", $cityInCase, $content);
    }
}
use Bitrix\Main\Context;
function getCityInfo(){

    $data = [
        'city'=>"Саратов",
        'city_rod'=>"Саратовe",
        'city_dat'=>"Саратову",
        'city_pred'=>"в Саратове",
    ];

    $host = Context::getCurrent()->getRequest()->getHttpHost();

    $subdomain = explode('.', $host)[0];

    $arFilter = [
        'IBLOCK_ID' => 1,
        'ACTIVE' => 'Y'
    ];
    if(!empty($subdomain))
        $arFilter['PROPERTY_DOMAIN'] = $subdomain;
    else
        $arFilter['ID'] = 1;


    $res = CIBlockElement::GetList([],$arFilter,false,false, ['ID', 'NAME', 'PROPERTY_CITY_ROD', 'PROPERTY_CITY_DAT', 'PROPERTY_CITY_PRED', 'PROPERTY_CITY_IM']);
    if ($city = $res->Fetch()) {
        $data = [
            'city'=>$city['PROPERTY_CITY_IM_VALUE']??$city['NAME'],
            'city_rod'=>$city['PROPERTY_CITY_ROD_VALUE']??$city['NAME'],
            'city_dat'=>$city['PROPERTY_CITY_DAT_VALUE']??$city['NAME'],
            'city_pred'=>$city['PROPERTY_CITY_PRED_VALUE']??$city['NAME'],
        ];
    }

    return $data;

}