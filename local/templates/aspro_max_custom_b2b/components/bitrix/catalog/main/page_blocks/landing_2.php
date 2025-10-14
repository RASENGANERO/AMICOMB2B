<?if($arParams['SHOW_LANDINGS'] !== 'N'):?>
	<?global $arTheme?>
	<?if(CMax::isSmartSeoInstalled() && $arParams['SHOW_SMARTSEO_TAGS'] !== 'N'):?>
		<?$APPLICATION->IncludeComponent(
			"aspro:smartseo.tags",
			".default",
			array(
				"MODE" => "Y",
				"FOLDER" => $arParams['SEF_FOLDER'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"SECTION_ID" => $arSection['ID'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME'],
				"URL_TEMPLATES" => $arParams['SEF_URL_TEMPLATES'],
				"SHOW_VIEW_CONTENT" => 'N',
				"CODE_VIEW_CONTENT" => 'smartseo',
				"VIEW_TYPE" => $arTheme['CATALOG_PAGE_LANDINGS_VIEW']['VALUE'],
				"SHOW_COUNT" => $arParams['SMARTSEO_TAGS_COUNT'],
				"SHOW_COUNT_MOBILE" => $arParams['SMARTSEO_TAGS_COUNT_MOBILE'],
				"SHOW_BY_GROUPS" => $arParams['SMARTSEO_TAGS_BY_GROUPS'],
				"SHOW_DEACTIVATED" => $arParams['SMARTSEO_TAGS_SHOW_DEACTIVATED'],
				"SORT" => $arParams['SMARTSEO_TAGS_SORT'],
				"LIMIT" => $arParams['SMARTSEO_TAGS_LIMIT'],
				"TITLE_BLOCK" => (!isset($arParams['LANDING_POSITION']) || $arParams['LANDING_POSITION'] !== 'BEFORE_PRODUCTS') ? $arParams['LANDING_TITLE'] : '',
				"BG_FILLED" => 'Y',
			),
			false, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>

	<?//if($arSeoItems):?>
		<?
		$arLandingFilter = array();
		if($arSeoItem)
		{
			// $arTmpRegionsLanding[] = $iLandingItemID;
			$arLandingFilter = array(
				array(
					"LOGIC" => "OR",
					array("PROPERTY_SECTION" => false),
					array("PROPERTY_SECTION" => $arSeoItem["PROPERTY_SECTION_VALUE"] ? $arSeoItem["PROPERTY_SECTION_VALUE"] : $arSection["ID"]),
				),
				"!ID" => $arTmpRegionsLanding,
			);
		}
		else
		{
			$arLandingFilter = array(
				array(
					"LOGIC" => "OR",
					array("PROPERTY_SECTION" => false),
					array("PROPERTY_SECTION" => $arSection["ID"]),
				),
				"!ID" => $arTmpRegionsLanding,
			);
		}
		if($GLOBALS['arTheme']['USE_REGIONALITY']['VALUE'] === 'Y' && $GLOBALS['arTheme']['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y' && $GLOBALS['arTheme']['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_CATALOG_INFO']['VALUE'] === 'Y') {
			$arLandingFilter['PROPERTY_LINK_REGION'] = $GLOBALS['arRegion']['ID'];
		}
		?>
		<?$GLOBALS["arLandingSections"] = $arLandingFilter;?>
		
	<?//endif;?>
<?endif;?>