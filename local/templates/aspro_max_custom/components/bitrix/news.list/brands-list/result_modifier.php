<?
$arParams['USE_SECTIONS_TABS'] ??= 'N';
$arParams['USE_BG_IMAGE_ALTERNATE'] ??= 'N';

if($arResult['ITEMS'])
{
	$arSectionsIDs = [];

	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);
		if($SID = $arItem['IBLOCK_SECTION_ID'])
			$arSectionsIDs[] = $SID;
		if($arParams['TITLE_SHOW_FON'] == 'Y' && ($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] != '' || $arParams['USE_BG_IMAGE_ALTERNATE'] == 'Y'))
			$arResult['HAS_TITLE_FON'] = 'Y';

		if($arParams['USE_SECTIONS_TABS']=='Y'){
			if($arItem['IBLOCK_SECTION_ID']){
				$resGroups = CIBlockElement::GetElementGroups($arItem['ID'], true, ['ID']);
				while($arGroup = $resGroups->Fetch())
				{
					$arResult['ITEMS'][$key]['SECTIONS'][$arGroup['ID']] = $arGroup['ID'];
					$arGoodsSectionsIDs[$arGroup['ID']] = $arGroup['ID'];
				}
			}
		}

	}

	if($arSectionsIDs && $arParams['SHOW_SECTION_NAME'] == 'Y' && $arParams['USE_SECTIONS_TABS']!='Y')
	{
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N']], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], false, ['ID', 'NAME']);		
	} elseif($arGoodsSectionsIDs && $arParams['USE_SECTIONS_TABS']=='Y'){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N']], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arGoodsSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], false, ['ID', 'NAME']);
	}
}

$getElementsLetter = CIBlockElement::GetList(
	['NAME' => 'ASC'],
	[
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'ACTIVE' => 'Y'
	],
	false,
	false,
	[
		'NAME',
		'PREVIEW_PICTURE',
		'DETAIL_PICTURE'
	]
);
while ($elementsLetter = $getElementsLetter->Fetch()) {
	$arElementsLetter[] = $elementsLetter;
}

if ($arElementsLetter) {
	$arResult['SEARCH_PRODUCTS'] = $arElementsLetter;
}

foreach ($arElementsLetter as $element) {
	$sectFName = $element['NAME'];
	$sectFLetter = mb_substr((string) $sectFName, 0, 1, 'UTF-8');
	$arrayFirstletter[] =  $sectFLetter;
}

$uniuqLetter = array_unique((array)$arrayFirstletter);

if ($uniuqLetter) {
	function sortLetter($a, $b) {
		$sa = mb_substr((string) $a, 0, 1, 'UTF-8');
		$sb = mb_substr((string) $b, 0, 1, 'UTF-8');
		if (ord($sa) > 122 && ord($sb) > 122) {
			return $a > $b ? 1 : -1;
		}
		if (ord($sa) > 122 || ord($sb) > 122) {
			return $a < $b ? 1 : -1;
		}
	}
	$arResult['SEARCH_PRODUCTS_UNIQUE'] = $uniuqLetter;
	usort($arResult['SEARCH_PRODUCTS_UNIQUE'], sortLetter(...));
}

$getLetter = $_GET['letter'];

$getElements = CIBlockElement::GetList(
	['NAME' => 'ASC'],
	[
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'NAME' => $getLetter ? $getLetter . '%' : '',
		'ACTIVE' => 'Y'
	],
	false,
	false,
	[
		'NAME',
		'DETAIL_PAGE_URL',
		'PREVIEW_PICTURE'
	]
);
while ($elements = $getElements->GetNext()) {
	$arElements[] = $elements;
}

if ($arElements) {
	function sortName($a, $b) {
		$sa = mb_substr((string) $a['NAME'], 0, 1, 'UTF-8');
		$sb = mb_substr((string) $b['NAME'], 0, 1, 'UTF-8');
		if (ord($sa) > 122 && ord($sb) > 122) {
			return $a > $b ? 1 : -1;
		}
		if (ord($sa) > 122 || ord($sb) > 122) {
			return $a < $b ? 1 : -1;
		}
	}
	$arResult['SEARCH_PRODUCTS_RESULT'] = $arElements;
	usort($arResult['SEARCH_PRODUCTS_RESULT'], sortName(...));
}
//$arResult['ITEMS'] = $arResult['SEARCH_PRODUCTS_RESULT'];
?>