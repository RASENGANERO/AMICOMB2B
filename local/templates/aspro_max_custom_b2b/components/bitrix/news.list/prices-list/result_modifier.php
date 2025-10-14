<?
$arParams['USE_SECTIONS_TABS'] = $arParams['USE_SECTIONS_TABS'] ?? 'N';
$arParams['USE_BG_IMAGE_ALTERNATE'] = $arParams['USE_BG_IMAGE_ALTERNATE'] ?? 'N';

if($arResult['ITEMS'])
{
	$arSectionsIDs = array();
	
	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);
		if($SID = $arItem['IBLOCK_SECTION_ID'])
			$arSectionsIDs[] = $SID;
		if($arParams['TITLE_SHOW_FON'] == 'Y' && ($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] != '' || $arParams['USE_BG_IMAGE_ALTERNATE'] == 'Y'))
			$arResult['HAS_TITLE_FON'] = 'Y';
		
		if($arParams['USE_SECTIONS_TABS']=='Y'){
			if($arItem['IBLOCK_SECTION_ID']){
				$resGroups = CIBlockElement::GetElementGroups($arItem['ID'], true, array('ID'));
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
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));		
	} elseif($arGoodsSectionsIDs && $arParams['USE_SECTIONS_TABS']=='Y'){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arGoodsSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));
	}
}
//Сортировка
if ($arResult['ITEMS']){
	foreach ($arResult['ITEMS'] as $item) {
		$firstLetter = mb_substr($item['NAME'], 0, 1);
		if (!ctype_upper($firstLetter)) {
			$firstLetter = strtoupper($firstLetter);
		}
		if (!isset($result[$firstLetter])) {
			$result[$firstLetter] = [];
		}
		$result[$firstLetter][] = $item;
	}
	uksort($result, function($a, $b) {
		// Проверяем, является ли ключ латинским, русским или числом
		$isLatinA = preg_match('/^[A-Za-z]$/', $a);
		$isLatinB = preg_match('/^[A-Za-z]$/', $b);
		$isCyrillicA = preg_match('/^[А-Яа-я]$/', $a);
		$isCyrillicB = preg_match('/^[А-Яа-я]$/', $b);
		$isNumericA = preg_match('/^d$/', $a);
		$isNumericB = preg_match('/^d$/', $b);
		// Сравниваем по типу
		if ($isLatinA && !$isLatinB) return -1; // Латинские впереди
		if (!$isLatinA && $isLatinB) return 1;
	
		if ($isCyrillicA && !$isCyrillicB) return -1; // Русские после латинских
		if (!$isCyrillicA && $isCyrillicB) return 1;
	
		if ($isNumericA && !$isNumericB) return -1; // Цифры в конце
		if (!$isNumericA && $isNumericB) return 1;
	
		return strcmp($a, $b);
	});
	$arResult['ITEMS'] = $result;

	//Выставляем ключи с цифрами в конец
	$arrasNumbers = [];
	foreach($arResult['ITEMS'] as $i => $arItem) {
		if (is_numeric(strval($i))) {
			$arrasNumbers[$i] = $arItem;
			unset($arResult['ITEMS'][$i]);
		}
	}
	foreach($arrasNumbers as $numb => $arItemNumber) {
		$arResult['ITEMS'][$numb] = $arItemNumber;
	}
	//$arResult['ITEMS'] = array_merge($arResult['ITEMS'],$arrasNumbers);
}
?>