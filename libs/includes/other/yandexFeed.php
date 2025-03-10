<?php
function createYML(){

    if (CModule::IncludeModule("iblock")){

        $IBLOCK_ID = 1;

        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $out .= '<yml_catalog date="' . date('Y-m-d H:i', strtotime('+4 hours')) . '">' . "\r\n";
        $out .= '<shop>' . "\r\n";

        $out .= '<name>Компания "ЛОС Маркет"</name>' . "\r\n";
        $out .= '<company>Компания "ЛОС Маркет"</company>' . "\r\n";
        $out .= '<url>https://los-market.ru</url>' . "\r\n";

        $out .= '<currencies>' . "\r\n";
        $out .= '<currency id="RUB" rate="1"/>' . "\r\n";
        $out .= '</currencies>' . "\r\n";

        // ================== Формирование списка категорий =================
        $arUrl        = array();
        $arSectFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'DEPTH_LEVEL' => 1);
        $rsSect       = CIBlockSection::GetList(array('ID' => 'DESC'), $arSectFilter, false, array());


        $out .= '<categories>' . "\r\n";
        while ($arSection = $rsSect->Fetch()){

            $url = 'https://los-market.ru/catalog/' . $arSection['CODE'] . '/';
            $arUrl[$arSection['ID']] = $url;

            $out .= '<category id="' . $arSection['ID'] . '">' . $arSection['NAME'] . '</category>' . "\r\n";


            // --------- Получение подразделов -----------
            $rsParentSection = CIBlockSection::GetByID($arSection['ID']);
            if ($arParentSection = $rsParentSection->GetNext()){

                $arSubsectFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']);
                $rsSubSect       = CIBlockSection::GetList(array('left_margin' => 'asc'), $arSubsectFilter, array());
                while ($arSubsection = $rsSubSect->Fetch()){

                    $sub_url = $url . $arSubsection['CODE'] . '/';
                    $arUrl[$arSubsection['ID']] = $url . $arSubsection['CODE'] . '/';

                    $out .= '<category id="' . $arSubsection['ID'] . '" parentId="' . $arSubsection['IBLOCK_SECTION_ID'] . '">' . $arSubsection['NAME'] . '</category>' . "\r\n";

                }
            }
            // --------- /Получение подразделов -----------
        }
        $out .= '</categories>' . "\r\n";
        // ================== /Формирование списка категорий =================


        // ================= Формирование списка товаров ==================
        $arElemFilter = array('IBLOCK_ID' => $IBLOCK_ID,
            "PROPERTY_brand"=>[1044,5,8,1005,999,1230,1142,924,1152]
        );
        $arElemSelect = array('ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE',"DETAIL_PICTURE", 'PREVIEW_TEXT','DETAIL_TEXT', 'PROPERTY_brand', 'PROPERTY_price', 'PROPERTY_*', 'ACTIVE');
        $rsElement    = CIBlockElement::GetList(array('ID' => 'ASC'), $arElemFilter, false, ['nTopCount'=>15111], $arElemSelect);


        $out .= '<offers>' . "\r\n";
        while ($arElement = $rsElement->Fetch()){
            $arElement['DETAIL_TEXT'] = htmlspecialchars(strip_tags($arElement['DETAIL_TEXT']));
            $arElement['NAME'] = preg_replace('/№ [0-9]{1,} /', '', $arElement['NAME']);
            $arBrand = [];
            if($arElement['PROPERTY_BRAND_VALUE']){
                $resBrand = CIBlockElement::GetByID($arElement["PROPERTY_BRAND_VALUE"]);
                $arBrand = $resBrand->GetNext();
            }

            if (array_key_exists($arElement['IBLOCK_SECTION_ID'], $arUrl) && $arElement['ACTIVE'] == 'Y'){
                $elem_price  = '';
                //Получение цены из свойства
                $arPropCodes = array('CODE' => 'price');
                $resProp     = CIBlockElement::GetProperty($IBLOCK_ID, $arElement['ID'], array(), $arPropCodes);
                while ($arProp = $resProp->Fetch()){
                    if (count(explode(' ', $arProp['VALUE'])) > 2){
                        $elem_price = explode(' ', $arProp['VALUE'])[1];
                    }else{
                        $elem_price = explode(' ', $arProp['VALUE'])[0];
                    }
                }
                //получение цены из каталога
                if($arPrice = CPrice::GetBasePrice($arElement['ID'])){
                    $elem_price = round($arPrice["PRICE"]);
                }

                //Получение всех свойств
                $propList = [
                    'cable_diameter',
                    'diameter',
                    //'count_text',
                    'count',
                    'type_stok',
                    'deep_pipe',
                    'type',
                    'performance',
                    'reset',
                    'obem_l',
                    'polezni_obem',
                    'size_obor',
                    'Proiz',
                    'wt',
                    'production_l_min',
                    'poleznii_obem_filtr',
                    'ob',
                    'weight',
                    'electrouse',
                    'size_hole',
                    'degree_treatment',
                    'equipment'
                ];
                $resProp     = CIBlockElement::GetProperty($IBLOCK_ID, $arElement['ID'], array("value_id"), ["EMPTY"=>"N", "ACTIVE "=>"Y"]);
                $props=[];
                while ($arProp = $resProp->Fetch()){

                    if(empty($arProp['VALUE'])) continue;
                    if(!in_array($arProp['CODE'], $propList)) continue;

                    if($arProp['PROPERTY_TYPE'] == "L"){
                        $arProp['VALUE'] = $arProp['VALUE_ENUM'];
                    }
                    if(is_array($arProp['VALUE'])){
                        $arProp['VALUE'] = implode(", ",$arProp['VALUE']);
                    }

                    $arProp['VALUE'] = htmlspecialchars(strip_tags($arProp['VALUE']));

                    if(!isset($props[$arProp['ID']])){
                        $props[$arProp['ID']] = $arProp;
                    }else{
                        $props[$arProp['ID']]['VALUE'] .= ", ".$arProp['VALUE'];
                    }

                }

                //Получение всех свойств end

                $elem_url = $arUrl[$arElement['IBLOCK_SECTION_ID']] . $arElement['CODE'] . '/';

                $out .= '<offer id="' . $arElement['ID'] . '" available="true">' . "\r\n";
                $out .= '<url>' . $elem_url . '</url>' . "\r\n";
                $out .= '<price>' . $elem_price . '</price>' . "\r\n";
                $out .= '<currencyId>RUB</currencyId>' . "\r\n";
                $out .= '<categoryId>' . $arElement['IBLOCK_SECTION_ID'] . '</categoryId>' . "\r\n";

                if(!empty($arElement['DETAIL_PICTURE'])){
                    $out .= '<picture>https://los-market.ru' . CFile::GetPath($arElement['DETAIL_PICTURE']) . '</picture>' . "\r\n";
                }

                $out .= '<name>'.$arElement['NAME'].'</name>' . "\r\n";
                $out .= '<description>' . stripslashes($arElement['DETAIL_TEXT']) . '</description>' . "\r\n";
                $out .= '<param name="Бренд">' . $arBrand['NAME'] . '</param>' . "\r\n";
                foreach ($props as $prop){
                    $out .= '<param name="'.$prop["NAME"].'">' . $prop['VALUE'] . '</param>' . "\r\n";
                }
                $out .= '</offer>' . "\r\n";
            }
        }
        $out .= '</offers>' . "\r\n";
        // ================= Формирование списка товаров ==================






        $out .= '</shop>' . "\r\n";
        $out .= '</yml_catalog>' . "\r\n";

        echo $out;
        return;

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($out);

        $dom->save($_SERVER["DOCUMENT_ROOT"] . '/bitrix/catalog_export/yandex_brands.xml');
    }

    return 'createYML();';
}
?>