<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Iblock;
Loader::includeModule('iblock');
$data=[];
$data['errors']=[];
if(isset($_COOKIE['popupshowed']) || $_SERVER["REMOTE_ADDR"] == "88.206.68.200"){
    //return false;
}

$res = CIBlock::GetByID($arParams['IBLOCK_ID']);
if (!$iblock = $res->GetNext())
    dd('Такого ИБ нет');

$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams['IBLOCK_ID']));

while ($props = $properties->GetNext()){
    $type="text";

    switch ($props['PROPERTY_TYPE']){
        case "L": $type="checkbox"; break;
    }
    switch ($props['CODE']){
        case "PHONE": $type="tel"; break;
        case "EMAIL": $type="email"; break;
    }

    $props["INPUT_TYPE"] = $type;
    $arResult["FIELDS"][$props["CODE"]] = $props;
}


$arData = json_decode(file_get_contents('php://input'), true);

$arResult['FORM_TITLE'] = !empty($arParams['FORM_TITLE'])?$arParams['FORM_TITLE']:$iblock['NAME'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($arData) && $arData['ajax']=="y") {
    $APPLICATION->RestartBuffer();
    $text="";
    foreach ($arData as $key=>$rqData){
        $arData[$key] = htmlspecialchars(trim(strip_tags($rqData)));
        if(
            isset($arResult["FIELDS"][$key]) &&
            $arResult["FIELDS"][$key]['IS_REQUIRED'] == "Y" &&
            $arResult["FIELDS"][$key]['PROPERTY_TYPE'] != "L" &&
            strlen($rqData)<3){
            $data['errors'][$key]="Поле должно не менее 3 символов";
        }

        if(
            isset($arResult["FIELDS"][$key]) &&
            $arResult["FIELDS"][$key]['CODE'] == "AGREE" &&
            $rqData == "Y"
        ){
            $arData[$key]='12';
        }
        if(isset($arResult["FIELDS"][$key])){
            $text .= $arResult["FIELDS"][$key]['NAME'].": ".$rqData."\n";
        }
    }

    if(!empty($data['errors'])){
        echo json_encode($data);
        die();
    }

    $el = new CIBlockElement;
    $fields = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'NAME' => 'Заявка от: '.date("d.m.Y H:i:s"),
        'PROPERTY_VALUES' => $arData,
    ];
    //dd($fields);
    if ($elId = $el->Add($fields)) {
        $data = ['success'=>true,'message'=>""];
        $link = "Заявка:\nhttps://cpkk.cpreuro.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=25&type=forms&ID=".$elId;
        $event = CEvent::SendImmediate('Заявка','s1',['TEXT' => $text,"LINK"=>$link],"Y",17);

        if($event=="Y") $data['message'].="email:ok;";

        /* b24 */
        $utmData = get_utm($_POST['utmMetriks']);

        $queryData = http_build_query(array(
            "fields" => array(
                "TITLE" => 'Сайт - Заявка с формы Скидки',
                "NAME" => $arData["FIO"],
                "ASSIGNED_BY_ID" => 2392,
                "CREATED_BY_ID" => 226,
                "UF_CRM_1629958285616" => $arData["SALE_TEXT"],
                "PHONE" => array(
                    "n0" => array(
                        "VALUE" => $arData["PHONE"],
                        "VALUE_TYPE" => "MOBILE",
                    ),
                ),
                "EMAIL" => array(
                    "n0" => array(
                        "VALUE" =>  $arData["EMAIL"],
                        "VALUE_TYPE" => "WORK",
                    ),
                ),
                "UTM_SOURCE" => $utmData["utm_source"],
                "UTM_MEDIUM" => $utmData["utm_medium"],
                "UTM_CAMPAIGN" => $utmData["utm_campaign"],
                "UTM_CONTENT" => $utmData["utm_content"],
                "UTM_TERM" => $utmData["utm_term"],
            ),
            'params' => array("REGISTER_SONET_EVENT" => "N")
        ));
        $b24 = file_get_contents("https://euro.bitrix24.ru/rest/226/pnzctg4kvlu0gewm/crm.lead.add.json?" . $queryData);

        if($b24) $data['message'].="crm:ok;";

    } else {
        $data = ['success'=>false,"message"=> $el->LAST_ERROR];
    }

    echo json_encode($data);
    die();

}

$this->arResult = $arResult;

$this->includeComponentTemplate();