<?php
AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");

function bxModifySaleMails($orderID, &$eventName, &$arFields){
    CModule::IncludeModule("sale");
    $strParamOrder="";
    $dbRes = \Bitrix\Sale\PropertyValueCollection::getList([
        'select' => ['*'],
        'filter' => [
            '=ORDER_ID' => $orderID,
        ]
    ]);
    $arFields["ORDER_MAIL"]="";
    $arFields["NAME_CLIENT"]="";
    while ($item = $dbRes->fetch()){
        $strParamOrder .= $item["NAME"].": <b>".htmlspecialchars($item["VALUE"])."</b><br>";
        if($item['CODE'] == "ORDER_NAME"){
            $arFields["NAME_CLIENT"] = "от ".$item["VALUE"];
        }
        if($item['CODE'] == "ORDER_MAIL"){
            $arFields["ORDER_MAIL"] = "от ".$item["VALUE"];
        }
    }
    /*
    $orderProps=[
        ["Имя","ORDER_NAME"],
        ["Телефон","ORDER_PHONE"],
        ["Почта","ORDER_MAIL"],
        ["Дата получения авто","ORDER_DATEFROM"],
        ["Дата сдачи авто","ORDER_DATETO"],
        ["Адрес получения авто","ORDER_ADDRES1"],
        ["Адрес сдачи авто","ORDER_ADDRES2"],
        ["Всего дней аренды","ORDER_DAYS"],
        ["Скидка за дни","DISCOUNT"],
        ["Стоимость авто со скидкой","AUTO_PRICE"],
        ["Стоимость доп.услуг со скидкой(скидка только на сут.усл)","PRICE_DOPS"],
        ["Общая стоимость из BACKEND","FULL_PRICE"],
        ["Общая стоимость бронирования с учетом скидки и доп.услуг","FULLPRICE"],
        ["Стоимость доставки","DELIVERY_PLACE"],
        ["Стоимость возврата","AWAY_PLACE"],
        ["Дополнительные услуги","DOPS"],
        //["Расстояния до места получения, м","DISTANCE_FROM",$data['distance_from']],
        //["Расстояния до места сдачи, м","DISTANCE_TO",$data['distance_to']],
        //["Каско","KASKO",$data['kasko']],
    ];*/

    $arFields["STRING_PROPS"] = $strParamOrder;
}
