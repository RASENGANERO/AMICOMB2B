<?
use Bitrix\Iblock\InheritedProperty\SectionValues;

global $arTheme, $arRegion;
// get all subsections of PARENT_SECTION or root sections
$arSectionsFilter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'];
$start_level = $arParams['DEPTH_LEVEL'];
$end_level = $arParams['DEPTH_LEVEL']+1;

if($arParams['PARENT_SECTION'])
{
	$arParentSection = CMaxCache::CIBLockSection_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N', "CACHE_GROUP" => [$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()]]], ['ID' => $arParams['PARENT_SECTION']], false, ['ID', 'IBLOCK_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN']);

	$arSectionsFilter = array_merge($arSectionsFilter, ['>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'], '>DEPTH_LEVEL' => '1']);
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		$arSectionsFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	}
}
else
{
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	else
		$arSectionsFilter['DEPTH_LEVEL'] = '1';
}
$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => ['ID'], 'MULTI' => 'N', "CACHE_GROUP" => [$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()]]], $arSectionsFilter, false, ['ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'PICTURE', 'DETAIL_PICTURE', 'UF_TOP_SEO', 'DESCRIPTION']);


if($arResult['SECTIONS'])
{
	$arSections = [];
	foreach($arResult['SECTIONS'] as $key => $arSection)
	{
		$ipropValues = new SectionValues($arSection['IBLOCK_ID'], $arSection['ID']);
		$arResult['SECTIONS'][$key]['IPROPERTY_VALUES'] = $ipropValues->getValues();
		CMax::getFieldImageData($arResult['SECTIONS'][$key], ['PICTURE'], 'SECTION');
	}

	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		foreach($arResult['SECTIONS'] as $arItem)
		{

			if( $arItem['DEPTH_LEVEL'] == $start_level ){
				if(!$arSections[$arItem['ID']]){
					$arSections[$arItem['ID']] = $arItem;
				}else{
					$arSections[$arItem['ID']] = array_merge($arSections[$arItem['ID']], $arItem);
				}

			}
			elseif( $arItem['DEPTH_LEVEL'] == $end_level ){
				$arSections[$arItem['IBLOCK_SECTION_ID']]['CHILD'][$arItem['ID']] = $arItem;//echo '<pre>',var_dump($arSections ),'</pre>';
			}
		}

		// add filter elements by region
		$arItemsRegionFilter = [];
		if($arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arRegion && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'){
			$arItemsRegionFilter['PROPERTY_LINK_REGION'] = $arRegion['ID'];
		}

		// fill elements
		foreach($arSections as $key => $arSection)
		{
			$arItems = CMaxCache::CIBlockElement_GetList(['SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), "CACHE_GROUP" => [$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()]]], array_merge($arSectionsFilter, ['SECTION_ID' => $arSection['ID']], $arItemsRegionFilter), false, false, ['ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL']);
			if($arItems)
			{
				if(!$arSections[$key]['CHILD'])
					$arSections[$key]['CHILD'] = $arItems;
				else
					$arSections[$key]['CHILD'] = array_merge($arSections[$key]['CHILD'], $arItems);
			}
		}
		$arResult['SECTIONS'] = $arSections;
	}
}
?>
