<?php
\Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", "OnEndBufferWebFormAntiSpam");
function OnEndBufferWebFormAntiSpam(&$content){
    $xStyle  = '.xname{display:block;height:.1px;margin:0!important;overflow:hidden;padding:0!important;width:.1px;border:0;opacity:.01;}';
    $content = str_ireplace('', $xStyle.'',  $content);
    $findField = addEventHandler('form', 'onBeforeResultAdd', 'onBeforeResultAddWebFormAntiSpam');
}

function onBeforeResultAddWebFormAntiSpam($WEB_FORM_ID, &$arFields, &$arrVALUES){
    global $APPLICATION;
    if(!empty($arrVALUES['last_name'])){
        $APPLICATION->ThrowException('Отказать.');
    }
}
?>