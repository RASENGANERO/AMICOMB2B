<?
use Amikomnew\GetSections;
$sectionsDepth = GetSections::getMainSectionsCatalog();

foreach($arResult['ITEMS'] as $key => $arItem){
	$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);
    
	if(strlen((string) $arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
		unset($arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['REDIRECT']);
}

$arResult['ITEMS'] = $sectionsDepth;
?>