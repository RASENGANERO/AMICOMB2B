<?
use Bitrix\Main\Config\Option;
use TSolution\Filter;
use Bitrix\Currency\CurrencyTable,
	Aspro\Max\SearchQuery,
	CMax as Solution,
	CMaxCache as SolutionCache,
	Aspro\Max\SearchTitle;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $arRegion;

$searchType = SearchTitle::getType();

$arResult["ELEMENTS"] = [];
$arResult["CATALOG_ELEMENTS"] = [];
$arResult["SECTIONS"] = [];
$arResult["PARENT_SECTIONS"] = [];
$arResult["SEARCH"] = [];

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arCatalogs = [];
if (CModule::IncludeModule("catalog"))
{
	$rsCatalog = CCatalog::GetList([
		"sort" => "asc",
	]);
	while ($ar = $rsCatalog->Fetch())
	{
		if ($ar["PRODUCT_IBLOCK_ID"])
			$arCatalogs[$ar["PRODUCT_IBLOCK_ID"]] = 1;
		else
			$arCatalogs[$ar["IBLOCK_ID"]] = 1;
	}
}

$bSearchLandings = $searchLandingsCategoryId = false;

foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
{
	foreach($arCategory["ITEMS"] as $i => $arItem)
	{
		if(isset($arItem["ITEM_ID"]))
		{
			if($arItem['MODULE_ID'] === 'iblock'){
				$iblockId = $arItem['PARAM2'];

				if(!str_contains($arItem["ITEM_ID"], "S")){
					if(array_key_exists($iblockId, $arCatalogs)){
						$arResult["CATALOG_ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
					}
					else{
						if(SearchQuery::isLandingSearchIblock($arItem['PARAM2'])){
							$bSearchLandings = true;
							$searchLandingsCategoryId = $category_id;
							if(!$arItem['URL']){
								unset($arResult["CATEGORIES"][$category_id]["ITEMS"][$i]);
								continue;
							}
						}
					}

					$arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
				}
				else {
					$sectionId = str_replace('S', '', $arItem["ITEM_ID"]);

					if(
						$GLOBALS['arTheme']['USE_REGIONALITY']['VALUE'] === 'Y' 
						&& $GLOBALS['arTheme']['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'
					){
						if($arSectionsIds_NotInRegion = Solution::getSectionsIds_NotInRegion($iblockId)){
							if(in_array($sectionId, $arSectionsIds_NotInRegion)){
								continue;
							}
						}
					}

					$arResult["SECTIONS"][$sectionId] = $sectionId;
				}
			}

			$arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];
		}
	}

	if (!$arCategory["ITEMS"]) {
		unset($arResult["CATEGORIES"][$category_id]);
	}
}

if(!$bSearchLandings){
	if($arLandingSearchIBlocksIDs = SearchQuery::getLandingSearchIblocksIDs(SITE_ID, 'Y')){
		if($bSearchLandings = $arParams['SHOW_OTHERS'] === 'Y'){
			$searchLandingsCategoryId = 'others';
		}

		foreach($arParams as $key => $value){
			if(preg_match('/^CATEGORY_([^_]+)_iblock_'.SearchQuery::IBLOCK_TYPE.'$/im'.BX_UTF_PCRE_MODIFIER, (string) $key, $arMatches)){
				$value = is_array($value) ? $value : [$value];
				if(count($value) == 1 && reset($value) === 'all'){
					$bSearchLandings = true;
					$searchLandingsCategoryId = $arMatches[1];
					break;
				}
				else{
					foreach($value as $iblockID){
						if(SearchQuery::isLandingSearchIblock($iblockID)){
							$bSearchLandings = true;
							$searchLandingsCategoryId = $arMatches[1];
							break 2;
						}
					}
				}
			}
		}
	}
}

if($bSearchLandings){
	if(!isset($arResult['CATEGORIES'][$searchLandingsCategoryId])){
		$arResult['CATEGORIES'][$searchLandingsCategoryId] = [
			'TITLE' => $arParams['CATEGORY_'.($searchLandingsCategoryId === 'others' ? 'OTHERS' : $searchLandingsCategoryId).'_TITLE'],
			'ITEMS' => [],
		];
	}

	// count items in category without item with type === all
	$cntItemsInSearchLandingsCategory = 0;
	if($arResult["CATEGORIES"][$searchLandingsCategoryId]['ITEMS']){
		foreach($arResult["CATEGORIES"][$searchLandingsCategoryId]['ITEMS'] as $arItem){
			if(is_array($arItem) && $arItem['TYPE'] !== 'all'){
				++$cntItemsInSearchLandingsCategory;
			}
		}
	}

	if($cntItemsInSearchLandingsCategory < $arParams['TOP_COUNT']){
		$arLandingsFilter = ['ACTIVE' => 'Y'];
		if($arRegion){
			$arLandingsFilter[] = [
				'LOGIC' => 'OR',
				['PROPERTY_LINK_REGION' => false],
				['PROPERTY_LINK_REGION' => $arRegion['ID']],
			];
		}

		$arTitleLandings = SearchQuery::getTitleLandings($arResult['query'], $arResult['alt_query'], $arLandingsFilter, $arParams['TOP_COUNT'] - $cntItemsInSearchLandingsCategory);

		foreach($arTitleLandings as $arTitleLanding){
			$arResult['ELEMENTS'][$arTitleLanding['ITEM_ID']] = $arTitleLanding['ITEM_ID'];
			array_unshift($arResult['CATEGORIES'][$searchLandingsCategoryId]['ITEMS'], $arTitleLanding);
			$arResult["SEARCH"][] = $arResult['CATEGORIES'][$searchLandingsCategoryId]['ITEMS'][0];
		}
	}
}

if (CModule::IncludeModule("iblock")) {
	if (!empty($arResult["ELEMENTS"])){
		/*convert currency*/
		$arConvertParams = [];
		if ('Y' == $arParams['CONVERT_CURRENCY'])
		{
			if (!CModule::IncludeModule('currency'))
			{
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			}
			else
			{
				$currencyIterator = CurrencyTable::getList([
					'select' => ['CURRENCY'],
					'filter' => ['=CURRENCY' => $arParams['CURRENCY_ID']]
				]);
				if ($currency = $currencyIterator->fetch())
				{
					$arParams['CURRENCY_ID'] = $currency['CURRENCY'];
					$arConvertParams['CURRENCY_ID'] = $currency['CURRENCY'];
				}
				else
				{
					$arParams['CONVERT_CURRENCY'] = 'N';
					$arParams['CURRENCY_ID'] = '';
				}
				unset($currency, $currencyIterator);
			}
		}

		$bHideNotAvailable = (Option::get(Solution::moduleID, 'SEARCH_HIDE_NOT_AVAILABLE', 'N') == 'Y');

		$strBaseCurrency = '';
		$boolConvert = isset($arConvertParams['CURRENCY_ID']);
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();

		$obParser = new CTextParser;

		if (is_array($arParams["PRICE_CODE"]))
			$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
		else
			$arResult["PRICES"] = [];

		$arSelect = [
			"ID",
			"IBLOCK_ID",
			"PREVIEW_TEXT",
			"PREVIEW_PICTURE",
			"DETAIL_PICTURE",
			"DETAIL_PAGE_URL",
			"ACTIVE_FROM",
			"PROPERTY_REDIRECT",
			"SECTION_ID",
		];
		$arFilter = [
			"IBLOCK_LID" => SITE_ID,
			"IBLOCK_ACTIVE" => "Y",
			"ACTIVE_DATE" => "Y",
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
			"MIN_PERMISSION" => "R",
		];

		if($arParams["SHOW_PREVIEW"] == "Y"){
			$arSelect[] = 'PROPERTY_CML2_LINK';
		}

		foreach($arResult["PRICES"] as $value)
		{
			$arSelect[] = $value["SELECT"];
			$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
		}

		$arFilter["=ID"] = $arResult["ELEMENTS"];

		$arDeleteIDs = $arUnDeleteIDs = [];

		if($bHideNotAvailable)
		{
			$arFilter[] =  ['LOGIC' => 'OR',['=CATALOG_AVAILABLE' => false],['=CATALOG_AVAILABLE' => 'Y']];
			if($arRegion)
			{
				$arStores = array_diff($arRegion["LIST_STORES"], ['component']);
				//if($arRegion["LIST_STORES"])
				if($arStores)
				{
					$arTmpFilter = [];
					if($arResult['CATALOG_ELEMENTS']){
						$arStoresFilter = Filter::getAvailableByStores($arStores);
						if($arStoresFilter){
							$arTmpFilter = $arStoresFilter;
							$arTmpFilter[] = ['!ID' => $arResult['CATALOG_ELEMENTS']];//not catalog items
						}						
					}
					if($arTmpFilter){
						$arFilter[] = $arTmpFilter;
					}
				}
			}
		}

		$arOffersWithoutPictureProductsIDs = $arFilterIBlocks = [];

		$rsElements = CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID']);
		while($arElement = $rsElements->Fetch())
		{
			$arFilterIBlocks[] = $arElement['IBLOCK_ID'];
		}
		if($arFilterIBlocks){
			$arFilter['IBLOCK_ID'] = array_unique($arFilterIBlocks);
		}

		$rsElements = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		while($arElement = $rsElements->Fetch())
		{
			$arRegionProps = [];
			$rsPropRegion = CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], ["sort" => "asc"], ["CODE"=>"LINK_REGION"]);
			while($arPropRegion = $rsPropRegion->Fetch())
			{
				if($arPropRegion['VALUE'])
					$arRegionProps[] = $arPropRegion['VALUE'];
			}
			if($arRegionProps && $arRegion)
			{
				if(!in_array($arRegion['ID'], $arRegionProps))
				{
					$arDeleteIDs[$arElement["ID"]] = $arElement["ID"];
					unset($arResult["ELEMENTS"][$arElement["ID"]]);
					continue;
				}
			}

			if($GLOBALS['arTheme']['USE_REGIONALITY']['VALUE'] === 'Y' && $GLOBALS['arTheme']['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'){
				if($arSectionsIds_NotInRegion = Solution::getSectionsIds_NotInRegion($arElement["IBLOCK_ID"])){
					if(in_array($arElement['IBLOCK_SECTION_ID'], $arSectionsIds_NotInRegion)){
						$arDeleteIDs[$arElement["ID"]] = $arElement["ID"];
						unset($arResult["ELEMENTS"][$arElement["ID"]]);
						continue;
					}
				}
			}

			if($bHideNotAvailable)
				$arUnDeleteIDs[$arElement["ID"]] = $arElement["ID"];

			if($arParams["SHOW_PREVIEW"] == "Y"){
				if($arElement['PROPERTY_CML2_LINK_VALUE'] && !$arElement['PREVIEW_PICTURE'] && !$arElement['DETAIL_PICTURE']){
					$arOffersWithoutPictureProductsIDs[$arElement["ID"]] = $arElement['PROPERTY_CML2_LINK_VALUE'];
				}
			}

			$arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);

			$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;

			/*offers*/
			$offersFilter = [
				'IBLOCK_ID' => $arElement['IBLOCK_ID'],
				'HIDE_NOT_AVAILABLE' => "N"
			];
			$arOffers = CIBlockPriceTools::GetOffersArray(
				$offersFilter,
				[$arElement["ID"]],
				[],
				["ID"],
				[],
				10,
				$arResult["PRICES"],
				$arParams['PRICE_VAT_INCLUDE'],
				$arConvertParams
			);
			if($arOffers){
				$arResult["ELEMENTS"][$arElement["ID"]]["OFFERS"]=$arOffers;
				$arResult["ELEMENTS"][$arElement["ID"]]["MIN_PRICE"]=Solution::getMinPriceFromOffersExt(
						$arOffers,
						$boolConvert ? $arConvertParams['CURRENCY_ID'] : $strBaseCurrency
					);
			}
		}

		$arParentSectionsIDs = array_column($arResult["ELEMENTS"], 'SECTION_ID');
		$arParentSectionsIDs = array_diff(array_unique($arParentSectionsIDs), [false, null, '', 0]);
		if ($arParentSectionsIDs) {
			$rsSections = CIBlockSection::GetList([], ['=ID' => $arParentSectionsIDs], false, ['ID', 'NAME'], false);
			while($arSection = $rsSections->Fetch()){
				$arResult['PARENT_SECTIONS'][$arSection['ID']] = $arSection;
			}
		}

		if($arOffersWithoutPictureProductsIDs){
			$rsElements = CIBlockElement::GetList([], ['ID' => array_values($arOffersWithoutPictureProductsIDs)], false, false, ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE']);
			while($arElement = $rsElements->Fetch()){
				if($arOffersIDs = array_keys($arOffersWithoutPictureProductsIDs, $arElement['ID'])){
					foreach($arOffersIDs as $id){
						$arResult["ELEMENTS"][$id]['PREVIEW_PICTURE'] = $arElement['PREVIEW_PICTURE'];
						$arResult["ELEMENTS"][$id]['DETAIL_PICTURE'] = $arElement['DETAIL_PICTURE'];
					}
				}
			}
		}

		// replace year in url
		foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
		{
			foreach($arCategory["ITEMS"] as $i => $arItem)
			{
				if(isset($arItem["ITEM_ID"]))
				{
					if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]["PROPERTY_REDIRECT_VALUE"]) && $arResult["ELEMENTS"][$arItem["ITEM_ID"]]["PROPERTY_REDIRECT_VALUE"])
					{
						$arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["URL"] = $arResult["ELEMENTS"][$arItem["ITEM_ID"]]["PROPERTY_REDIRECT_VALUE"];
					}
					elseif(str_contains((string) $arItem["URL"], "#YEAR#"))
					{
						if($arResult["ELEMENTS"][$arItem["ITEM_ID"]]["ACTIVE_FROM"])
						{
							if($arDateTime = ParseDateTime($arResult["ELEMENTS"][$arItem["ITEM_ID"]]["ACTIVE_FROM"], FORMAT_DATETIME))
							{
								$url = str_replace("#YEAR#", $arDateTime['YYYY'], $arItem['URL']);
								$arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["URL"] = $url;
							}
						}
					}
					if($bHideNotAvailable)
					{
						if(!$arUnDeleteIDs[$arItem["ITEM_ID"]])
							unset($arResult["CATEGORIES"][$category_id]["ITEMS"][$i]);
					}
					if($arDeleteIDs)
					{
						if($arDeleteIDs[$arItem["ITEM_ID"]])
							unset($arResult["CATEGORIES"][$category_id]["ITEMS"][$i]);
					}
				}
			}
		}
	}

	if (!empty($arResult["SECTIONS"])){
		$arSelect = [
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
		];

		if ($arParams["SHOW_PREVIEW"] == "Y"){
			$arSelect[] = "PICTURE";
			$arSelect[] = "DETAIL_PICTURE";
		}

		$arFilter = [
			"IBLOCK_LID" => SITE_ID,
			"IBLOCK_ACTIVE" => "Y",
			"ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
			"MIN_PERMISSION" => "R",
		];
	
		$arFilter["=ID"] = $arResult["SECTIONS"];

		$rsSections = CIBlockSection::GetList([], $arFilter, false, $arSelect, false);
		while($arSection = $rsSections->Fetch()){
			$arResult["SECTIONS"][$arSection["ID"]] = $arSection;
		}

		$arParentSectionsIDs = array_column($arResult["SECTIONS"], 'IBLOCK_SECTION_ID');
		$arParentSectionsIDs = array_diff(array_unique($arParentSectionsIDs), [false, null, '', 0]);
		if ($arParentSectionsIDs) {
			$rsSections = CIBlockSection::GetList([], ['=ID' => $arParentSectionsIDs], false, ['ID', 'NAME'], false);
			while($arSection = $rsSections->Fetch()){
				$arResult['PARENT_SECTIONS'][$arSection['ID']] = $arSection;
			}
		}
	}
}

foreach($arResult["SEARCH"] as $i => &$arItem)
{
	switch($arItem["MODULE_ID"])
	{
		case "iblock":
			if (
				is_array($arResult["ELEMENTS"])
				&& array_key_exists($arItem["ITEM_ID"], $arResult["ELEMENTS"])
				&& is_array($arResult["ELEMENTS"][$arItem["ITEM_ID"]])
			) {
				$arElement = &$arResult["ELEMENTS"][$arItem["ITEM_ID"]];
				if ($arParams["SHOW_PREVIEW"] == "Y")
				{
					if ($arElement["PREVIEW_PICTURE"] > 0)
						$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], ["width"=>80, "height"=>80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					elseif ($arElement["DETAIL_PICTURE"] > 0)
						$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], ["width"=>80, "height"=>80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					elseif ($arElement['OFFERS'] && Option::get(Solution::moduleID, 'SHOW_FIRST_SKU_PICTURE', 'N') === 'Y') {
						$bFindPicture = false;
						
						foreach ($arElement['OFFERS'] as $arOffer) {
							if (($arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE']) || (!$arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE'])) {
								$arOffer['DETAIL_PICTURE'] = $arOffer['PREVIEW_PICTURE'];
							}

							if ($arOffer['DETAIL_PICTURE'] && !$arElement['PREVIEW_PICTURE']['ID'] && !$bFindPicture) {
								$arElement['PICTURE'] = CFile::ResizeImageGet($arOffer["DETAIL_PICTURE"], ["width"=>80, "height"=>80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
								$bFindPicture = true;
								break;
							}
						}
					}
				}

				if ($parentSectionId = $arElement['SECTION_ID']) {
					if ($arResult['PARENT_SECTIONS'][$parentSectionId]) {
						$arItem['PARENT'] =& $arResult['PARENT_SECTIONS'][$parentSectionId]['NAME']; 
					}
				}
				elseif ($iblockId = $arElement['IBLOCK_ID']) {
					if ($arIBlock = SolutionCache::$arIBlocksInfo[$iblockId]) {
						$iblockListPage = trim(str_replace('#'.'SITE_DIR'.'#', SITE_DIR, $arIBlock['LIST_PAGE_URL']));
						if (strlen($iblockListPage)) {
							$arItem['PARENT'] = SearchTitle::getPageTitle($iblockListPage);
						}
					}
				}
			}
			elseif(
				str_contains((string) $arItem["ITEM_ID"], "S")
				&& ($sectionId = str_replace('S', '', $arItem["ITEM_ID"]))
				&& array_key_exists($sectionId, $arResult["SECTIONS"])
			){
				$arSection = &$arResult["SECTIONS"][$sectionId];
				if ($arParams["SHOW_PREVIEW"] == "Y"){
					if ($arSection["PICTURE"] > 0)
						$arSection["PICTURE"] = CFile::ResizeImageGet($arSection["PICTURE"], ["width"=>80, "height"=>80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					elseif ($arSection["DETAIL_PICTURE"] > 0)
						$arSection["PICTURE"] = CFile::ResizeImageGet($arSection["DETAIL_PICTURE"], ["width"=>80, "height"=>80], BX_RESIZE_IMAGE_PROPORTIONAL, true);
				}

				if ($parentSectionId = $arSection['IBLOCK_SECTION_ID']) {
					if ($arResult['PARENT_SECTIONS'][$parentSectionId]) {
						$arItem['PARENT'] =& $arResult['PARENT_SECTIONS'][$parentSectionId]['NAME']; 
					}
				}
				elseif ($iblockId = $arSection['IBLOCK_ID']) {
					if ($arIBlock = SolutionCache::$arIBlocksInfo[$iblockId]) {
						$iblockListPage = trim(str_replace('#'.'SITE_DIR'.'#', SITE_DIR, $arIBlock['LIST_PAGE_URL']));
						if (strlen($iblockListPage)) {
							$arItem['PARENT'] = SearchTitle::getPageTitle($iblockListPage);
						}
					}
				}
			}

			break;
	}

	$arResult["SEARCH"][$i]["ICON"] = true;
	if($arDeleteIDs)
	{
		if($arDeleteIDs[$arItem["ITEM_ID"]])
			unset($arResult["SEARCH"][$i]);
	}
}
unset($arItem);

if(!$arResult["SEARCH"])
	$arResult["CATEGORIES"] = [];
?>
