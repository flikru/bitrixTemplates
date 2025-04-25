<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "SUCCESS_MESSAGE" => array(
            "NAME" => "Сообщение об успешной отправке",
            "TYPE" => "STRING",
            "DEFAULT" => "Спасибо! Ваше сообщение отправлено.",
        ),
    ),
);