<?
$arResult["TITLE"] = htmlspecialchars_decode((string) $arResult["TITLE"]);
$arResult["ADDRESS"] = htmlspecialchars_decode((string) $arResult["ADDRESS"]);
$arResult["ADDRESS"] = (strlen((string) $arResult["TITLE"]) ? $arResult["TITLE"].', ' : '').$arResult["ADDRESS"];
$_SESSION['SHOP_TITLE'] = $arResult['ADDRESS'];
?>