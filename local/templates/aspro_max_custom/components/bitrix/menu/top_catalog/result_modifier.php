<?php

use Bitrix\Main\Config\Option;

if($arResult){
	$catalog_id=Option::get("aspro.max", "CATALOG_IBLOCK_ID", CMaxCache::$arIBlocks[SITE_ID]['aspro_max_catalog']['aspro_max_catalog'][0]);
	$arSections = CMaxCache::CIBlockSection_GetList(['SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($catalog_id), 'GROUP' => ['ID']]], ['IBLOCK_ID' => $catalog_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '<DEPTH_LEVEL' => $arParams['MAX_LEVEL']], false, ["ID", "NAME", "PICTURE", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID"]);
	if($arSections){

		$arTmpResult = [];
		$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
		$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);

		foreach($arSections as $ID => $arSection){
			$arSections[$ID]['SELECTED'] = CMenu::IsItemSelected($arSection['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index);
			if($arSection['PICTURE']){
				$img=CFile::ResizeImageGet($arSection['PICTURE'], ['width'=>50, 'height'=>50], BX_RESIZE_IMAGE_PROPORTIONAL, true);
				$arSections[$ID]['IMAGES']=$img;
			}
			if($arSection['IBLOCK_SECTION_ID']){
				if(!isset($arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'])){
					$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'] = [];
				}
				$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'][] = &$arSections[$arSection['ID']];
			}

			if($arSection['DEPTH_LEVEL'] == 1){
				$arTmpResult[] = &$arSections[$arSection['ID']];
			}
		}
		$arResult[0]["CHILD"]=$arTmpResult;
	}
}
