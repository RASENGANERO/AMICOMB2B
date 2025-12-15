<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['ITEMS'])
{
	/*stores product*/
	$arStores=CMax::CCatalogStore_GetList([], ["ACTIVE" => "Y"], false, false, []);
	$arResult["STORES_COUNT"] = count($arStores);

	$arTmpItems = [];
	foreach($arResult['ITEMS'] as $arItem)
	{
		$arTmpItems[$arItem['TYPE_BANNER']]['ITEMS'][] = $arItem;
	}
	if($arParams['BANNER_TYPE_THEME'] && $arTmpItems[$arParams['BANNER_TYPE_THEME']])
		$arResult['HAS_SLIDE_BANNERS'] = true;
	if($arParams['BANNER_TYPE_THEME_CHILD'] && $arTmpItems[$arParams['BANNER_TYPE_THEME_CHILD']])
		$arResult['HAS_CHILD_BANNERS'] = true;
	if($arParams['BANNER_TYPE_THEME_CHILD2'] && $arTmpItems[$arParams['BANNER_TYPE_THEME_CHILD2']])
		$arResult['HAS_CHILD_BANNERS2'] = true;
	$arResult['ITEMS'] = $arTmpItems;

}?>
