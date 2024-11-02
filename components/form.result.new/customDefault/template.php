<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
$errors['captcha']="";
if ($arResult["isFormErrors"] == "Y"):
    $errors = checkErrors($arResult["FORM_ERRORS_TEXT"]);
endif;?>

<? if($arResult["isFormNote"] == "Y"):?>
    <div class="mainForm__content">
        <div class="mainForm__title" style="display: block;">Спасибо! Ваша заявка успешно отправлена</div>
        <div class="mainForm__preTitle" style="display: block;">Наш менеджер свяжется с Вами в ближайшее время</div>
    </div>
<? endif; ?>

<?//=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y"){?>

<?=$arResult["FORM_HEADER"]?>

<? if ($arResult["isFormTitle"] && !isset($arParams['SHOW_TITLE'])):?>
    <div>
    <? if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y"){?>
        <div>
            <div><?
    if ($arResult["isFormTitle"] && !isset($arParams['SHOW_TITLE'])){?>
        <div><?=$arResult["FORM_TITLE"]?></div>
    <? }
    if ($arResult["isFormImage"] == "Y") { ?>
        <a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>">
            <img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" />
        </a>
        <?//=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
        <?} ?>
                <div><?=$arResult["FORM_DESCRIPTION"]?></div>
            </div>
        </div>
    <? } ?>
    </div>
    <? endif;?>
<div class="form form_default">
<div class="field_cont">
	<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){
        $err="";
		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'){
			echo $arQuestion["HTML_CODE"];
		}else{
            if(mb_stripos("Телефон", $arQuestion["CAPTION"])!==false){
                $arQuestion["HTML_CODE"] = str_replace("inputtext","inputtext phone valid",$arQuestion["HTML_CODE"]);
            } ?>
            <div class="field">
                <div class="label-form">
                    <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                    <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                    <?endif;?>
                    <?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?>
                    <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
                        <? foreach ($errors['caption'] as $e){
                            if(mb_strtolower($e) == mb_strtolower($arQuestion["CAPTION"])){
                                $err=true;
                                echo "<div class='validation_fail_tooltip'><label class='error'>Это поле необходимо заполнить.</label></div>";
                            }
                        }?>
                </div>
                <div class="<?=$err?"invalid-field":""?>">
                    <?if(strpos($arQuestion['HTML_CODE'], 'type="file"') === false):?>
                        <?=$arQuestion["HTML_CODE"]?>
                    <?else:
                        $arQuestion["HTML_CODE"] = str_replace('type="file"','type="file" id="file_input_load"' ,$arQuestion["HTML_CODE"]); ?>
                    <div class="file-input-container">
                        <input type="text" readonly id="select-file">
                        <label for="file_input_load" class="label-for-file">
                            Выбрать файл
                        </label>
                        <div class="hidden-file">
                            <?=$arQuestion["HTML_CODE"]?>
                        </div>
                    </div>
                    <?endif;?>
                </div>
            </div>
	<?
		}
	}
	?>
    <div class="fotter-form-def">
        <?if($arResult["isUseCaptcha"] == "Y"){?>
            <div>
                <div><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" /></div>
                <div><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></div>
                <div id="captcha-error"><?=$errors['captcha']?></div>
            </div>
        <?}?>
        <div class="confidenc">
            Нажимая на кнопку "Отправить", я даю согласие на <a href="/privacy_policy/" target="_blank">обработку персональных данных</a>
        </div>
        <div class="field_cont field_cont_submit">
                <input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> class="button send" type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
        </div>
        </div>
    </div>

<?=$arResult["FORM_FOOTER"]?>
<?}?>
</div>

<script>
    const fileInput = document.getElementById('file_input_load');
    const fileLabel = document.querySelector('.label-for-file');

    fileInput.onchange = () => {
        let textInput = document.getElementById('select-file');
        textInput.value = fileInput.files.length ? fileInput.files[0].name : '';
    };
</script>

<?
function checkErrors($erFields,$field=""){
    $str = strip_tags($erFields);
    $errors = [];
    $errors['fields'] = [];
    $errors['caption'] = [];
    $errors['captcha'] = [];
    $erRes = explode("&nbsp;&nbsp;&raquo;", $str);
    foreach ($erRes as $k=>$e){
        if(mb_stripos($e,"Не пройдена проверка от автоматических сообщений") !== false){
            $errors['captcha'] = "Укажите что вы не робот";
        }
        if(mb_stripos($e,"следующие обязательные")!== false){
            $errors['fields']=[];
        }
        if(mb_stripos( $e, "Введен некорректный адрес")!== false){
            $errors['fields'][]= "Введен не верный email адрес.";
            $errors['caption'][]= "Email";
        }
        if(mb_stripos($e,"имя")!== false){
            $errors['fields'][]= "Поле имя не заполнено";
            $errors['caption'][]= "Ваше имя";
        }
        if(mb_stripos($e,"телефон")!== false){
            $errors['fields'][]= "Поле телефон не заполнено";
            $errors['caption'][]= "Телефон";
        }
        if(mb_stripos( $e, "Email")!== false){
            $errors['fields'][]= "Поле Email не заполнено";
            $errors['caption'][]= "Email";
        }
    }
    return $errors;
}
?>