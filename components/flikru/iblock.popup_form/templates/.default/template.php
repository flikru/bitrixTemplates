<?php
if(isset($_COOKIE['modal_id_'.$arParams['MODAL_ID']])){
    return;
}
?>
<div id="feedbackModal" class="flk_modal_container" modal_id="modal_id_<?=$arParams['MODAL_ID']?>" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="form_page active" data-page="1">
            <form method="POST" id="feedback_site_form" modal_id="modal_id_<?=$arParams['MODAL_ID']?>" class="form flk_form_init" action="<?= POST_FORM_ACTION_URI ?>">
                <?= bitrix_sessid_post(); ?>
                <input type="hidden" id="ajax" name="ajax" value="y">
                <div class="flk-form-title">
                    <span><?=$arResult['FORM_TITLE']?></span>
                </div>
                <?if(!empty($arParams['LOGO'])):?>
                    <div class="flk-form-logo">
                        <img src="<?=$arParams['LOGO'];?>" alt="">
                    </div>
                <?endif;?>
                <?if(!empty($arParams['DESCRIPTION'])):?>
                    <div class="flk-form-description">
                        <?=$arParams['DESCRIPTION'];?>
                    </div>
                <?endif;?>
                <div class="flk-form-container">
                    <form action="">
                        <?foreach($arResult['FIELDS'] as $FIELD):?>
                        <? $pref = $FIELD["IS_REQUIRED"]=="Y"?"*":""?>
                        <div class="flk-form-item form-group <?=$FIELD["HINT"]?>">
                            <label style="<?=$FIELD["INPUT_TYPE"]=="checkbox"?"":"display: none"?>" for="id_<?=$FIELD["CODE"]?>"><?=$FIELD["NAME"].$pref?></label>
                            <input value="<?=($arParams['FIELDS'][$FIELD['CODE']])??""?>" <?=$pref?"required":""?> type="<?=$FIELD["INPUT_TYPE"]?>" placeholder="<?=$FIELD["NAME"].$pref?>" id="id_<?=$FIELD["CODE"]?>" class="styler-disabled <?=$FIELD["INPUT_TYPE"]=="checkbox"?"":"form-control"?> <?=$FIELD["HINT"]?>" name="<?=$FIELD["CODE"]?>">
                        </div>
                        <?endforeach;?>
                        <input type="submit" class="btn btn_gr" value="<?=$arParams['BTN_NAME']??"Оставить заявку"?>">
                    </form>
                </div>
            </form>
        </div>
        <div class="form_page flk-form-success" data-page="99">
            <div class="form-text" style="margin-bottom: 0px;">
                <?= $arParams['~SUCCESS_MESSAGE'] ?>
            </div>
        </div>
    </div>
</div>
<?php
$sec = isset($arParams['TIMEOUT']) ? (int) $arParams['TIMEOUT'] : 60 ;
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (getCookie("popupshowed") != "1") {
            setTimeout(function () {
                document.querySelector("#feedbackModal").style.display = "block";
                yaMetrika("feedbackform_show");
            }, 1000 * <?=$sec?>);
        }
    });
</script>