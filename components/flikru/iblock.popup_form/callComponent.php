<?php
//require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$arRes = array();
$url = $APPLICATION->GetCurPage();

if(strpos($url,"/kursy/")!==false){
    $arTemp = explode("/",$url);
    if(count($arTemp)>3){
        $output = array_slice($arTemp,0,3);
        $url = implode("/",$output)."/";
    }
}

$arRes = [];
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL","PREVIEW_TEXT","PREVIEW_PICTURE","PROPERTY_URL");
$arFilter = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y","PROPERTY_URL"=>$url);
$res = CIBlockElement::GetList(Array("RAND"=>"asc"), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement()){
    $arRes = $ob->GetFields();
    $arRes["PREVIEW_PICTURE"] = CFile::GetPath($arRes["PREVIEW_PICTURE"]);
}

if($arRes && $USER->IsAdmin()){
    $APPLICATION->IncludeComponent(
        "flikru:feedback.form",
        "",
        array(
            "IBLOCK_ID" => "25",
            "TIMEOUT" => 60,
            "LOGO" => $arRes["PREVIEW_PICTURE"]??"/local/templates/cpreuro/assets/images/logo.png",
            "DESCRIPTION" => $arRes['~PREVIEW_TEXT']??"",
            "FIELDS" => [
                "SALE_TEXT"=>$arRes['NAME'],
                "SALE_ID"=>$arRes['ID'],
            ],
            "FORM_TITLE" => $arRes['NAME']??"Отправить заявку на курсы",
            "BTN_NAME" => "Оставить заявку",
            "MODAL_ID" => $arRes['ID']??0,
            "SUCCESS_MESSAGE" => "Благодарим!<br>Ваша заявка принята, скоро вам перезвоним!",
        ),
        false
    );
}

//require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>