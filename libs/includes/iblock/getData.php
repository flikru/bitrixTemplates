<?
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>IntVal($yvalue), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    print_r($arFields);
    $arProps = $ob->GetProperties();
    print_r($arProps);
}
?>