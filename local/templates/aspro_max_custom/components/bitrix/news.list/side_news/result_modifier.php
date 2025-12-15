<?
foreach($arResult["ITEMS"] as $key=>$arItem){
	$arTmpYear = explode(' ', (string) $arItem["ACTIVE_FROM"]);
	$year = explode('.', $arTmpYear[0]);
	$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = str_replace('#YEAR#', $year[2], $arItem["DETAIL_PAGE_URL"]);
}
?>