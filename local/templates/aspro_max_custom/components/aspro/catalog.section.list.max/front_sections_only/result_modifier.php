<?php

use Bitrix\Main\Type\Collection;

if($arParams['SHOW_SUBSECTIONS'] == 'Y')
{
	$arRootItems = $arChildItems = [];
	foreach($arResult['SECTIONS'] as $key => $arSection)
	{
		if($arSection['DEPTH_LEVEL'] == 1)
			$arRootItems[$arSection['ID']] = $arSection;
		else
			$arChildItems[$arSection['ID']] = $arSection;
		unset($arResult['SECTIONS'][$key]);
	}
	foreach($arChildItems as $arSection)
		{
			$arRootSection = CMaxCache::CIBlockSection_GetList(['CACHE' => ['MULTI' =>'N', 'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])]], ['GLOBAL_ACTIVE' => 'Y', '<=LEFT_BORDER' => $arSection['LEFT_MARGIN'], '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'], 'DEPTH_LEVEL' => 1, 'IBLOCK_ID' => $arParams['IBLOCK_ID']], false, ['ID', 'NAME', 'SORT', 'SECTION_PAGE_URL', 'PICTURE']);

			if(!isset($arRootItems[$arRootSection['ID']])) {
				CMax::getFieldImageData($arRootSection, ['PICTURE'], 'SECTION');
				$arRootItems[$arRootSection['ID']] = $arRootSection;
			}
		}
	Collection::sortByColumn($arRootItems, ['SORT' => [SORT_NUMERIC, SORT_ASC], 'ID' => [SORT_NUMERIC, SORT_ASC]]);
	foreach($arRootItems as $key => $arSection)
	{
		$arSections = CMaxCache::CIBlockSection_GetList(['SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => ['MULTI' =>'Y', 'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])]], ['GLOBAL_ACTIVE' => 'Y', 'SECTION_ID' => $arSection['ID'], 'DEPTH_LEVEL' => 2, 'IBLOCK_ID' => $arParams['IBLOCK_ID']], $arParams['COUNT_ELEMENTS'], ['ID', 'NAME', 'SORT', 'SECTION_PAGE_URL', 'SectionValues']);
		$arRootItems[$key]['ITEMS'] = $arSections;
	}
	$arResult['SECTIONS'] = $arRootItems;
}
