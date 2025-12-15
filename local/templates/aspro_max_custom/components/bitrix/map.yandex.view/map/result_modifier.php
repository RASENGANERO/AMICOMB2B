<?
global $arRegion;

$arParams['USE_REGION_DATA'] ??= 'N';

if($arParams['USE_REGION_DATA'] == 'Y' && $arRegion && $arRegion["PROPERTY_REGION_TAG_YANDEX_MAP_VALUE"])
{
	$arCoord = explode(",", (string) $arRegion["PROPERTY_REGION_TAG_YANDEX_MAP_VALUE"]);
	$arResult['POSITION']['yandex_lat'] = $arCoord[0];
	$arResult['POSITION']['yandex_lon'] = $arCoord[1];
	$arTmpMark = [
		"LON" => $arResult['POSITION']['yandex_lon'],
		"LAT" => $arResult['POSITION']['yandex_lat'],
		"TEXT" => CMax::prepareRegionItemMapHtml($arRegion),
	];
	$arResult['POSITION']['PLACEMARKS'] = [];
	$arResult['POSITION']['PLACEMARKS'][] = $arTmpMark;
}
?>