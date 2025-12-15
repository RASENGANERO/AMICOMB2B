<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>

<?global $arTheme, $isHideLeftBlock, $isWidePage;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK_DETAIL"]) && $arParams["TYPE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK_DETAIL"];
}

if(isset($arParams["SIDE_LEFT_BLOCK_DETAIL"]) && $arParams["SIDE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK_DETAIL"];
}

if($arTheme['HIDE_SUBSCRIBE']['VALUE'] == 'Y'){
	$arParams["USE_SUBSCRIBE_IN_TOP"] = "N";
}
?>

<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_DETAIL") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
	$APPLICATION->AddViewContent('container_inner_class', ' contents_page ');
	$APPLICATION->AddViewContent('wrapper_inner_class', ' wide_page ');
	if(!$isWidePage){
		$APPLICATION->AddViewContent('right_block_class', ' maxwidth-theme ');		
	}
}
?>

<?
// get element
$arItemFilter = CMax::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);

global $APPLICATION, $arRegion;
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animation_ext.css');

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$arElement = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N']], $arItemFilter, false, false, ['ID', 'NAME', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'ACTIVE_TO', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL', 'PROPERTY_LINK_GOODS', 'PROPERTY_LINK_REGION', 'PROPERTY_LINK_GOODS_FILTER', 'PERIOD', 'DATE_CREATE']);

$CURRENT_PAGE = (CMain::IsHTTPS()) ? "https://" : "http://";
$CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
$SITE_DOMAIN = $CURRENT_PAGE;
$CURRENT_PAGE .= $APPLICATION->GetCurUri();

$arSite = \CSite::GetByID(SITE_ID)->Fetch();
$arSchema = [
	"@context" => "https://schema.org",
	"@type" => "NewsArticle",
	"url" => $CURRENT_PAGE,
	"publisher" => [
		"@type" => "Organization",
			"name" => $arSite['NAME']
	],
	"headline" => $arElement['NAME'],
	"articleBody" => $arElement['PREVIEW_TEXT'],
	"datePublished" => $arElement['DATE_CREATE']
];
if($arElement['PREVIEW_PICTURE']){
	$arSchema['image'][] = $SITE_DOMAIN.CFile::GetPath($arElement['PREVIEW_PICTURE']);
}
if($arElement['DETAIL_PICTURE']){
	$arSchema['image'][] = $SITE_DOMAIN.CFile::GetPath($arElement['DETAIL_PICTURE']);
}

?>
<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
<?

if($arParams["SHOW_MAX_ELEMENT"] == "Y")
{
	$arSort=[$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]];
	$arElementNext = [];

	$arAllElements = CMaxCache::CIblockElement_GetList([$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"], 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y']], ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arElement["IBLOCK_SECTION_ID"]/*, ">ID" => $arElement["ID"]*/ ], false, false, ['ID', 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'SORT']);
	if($arAllElements)
	{
		$url_page = $APPLICATION->GetCurPage();
		$key_item = 0;
		foreach($arAllElements as $key => $arItemElement)
		{
			if($arItemElement["DETAIL_PAGE_URL"] == $url_page)
			{
				$key_item = $key;
				break;
			}
		}
		if(strlen($key_item))
		{
			$arElementNext = $arAllElements[$key_item+1];
		}
		if($arElementNext)
		{
			if($arElementNext["DETAIL_PAGE_URL"] && is_array($arElementNext["DETAIL_PAGE_URL"])){
				$arElementNext["DETAIL_PAGE_URL"]=current($arElementNext["DETAIL_PAGE_URL"]);
			}
		}
	}
}
?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>
	
	<?CMax::AddMeta(
		[
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ?: $arElement['DETAIL_PICTURE'])) : false),
		]
	);?>

	<?
	/* hide compare link from module options */
	if(CMax::GetFrontParametrValue('CATALOG_COMPARE') == 'N')
		$arParams["DISPLAY_COMPARE"] = 'N';
	/**/
	?>

	<?
	$bActiveDate = (strlen((string) $arElement['PERIOD']['VALUE'] )&& in_array('PERIOD', $arParams['DETAIL_PROPERTY_CODE'])) || ($arElement['ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['DETAIL_FIELD_CODE']));
	$bDiscountCounter = ($arElement['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['DETAIL_FIELD_CODE']));
	$bShowDopBlock = (($arElement['SALE_NUMBER']['VALUE'] && in_array('SALE_NUMBER', $arParams['DETAIL_PROPERTY_CODE'])) || $bDiscountCounter);
	$bShowPeriodLine = $bActiveDate || $bShowDopBlock;
	?>

	<?if(($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE" || $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE")
		&& $arParams["SORT_REGION_PRICE"]) {
		$arPriceSort = [];
		global $arRegion;
		if ($arRegion) {
			if (!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component") {
				$price = CCatalogGroup::GetList([], ["NAME" => $arParams["SORT_REGION_PRICE"]], false, false, ["ID", "NAME"])->GetNext();
				$arPriceSort = ["CATALOG_PRICE_".$price["ID"]];
			} else {
				$arPriceSort = ["CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"]];
			}
		}
		if ($arPriceSort) {
			if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE") {
				$arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] = $arPriceSort[0];
			}
			if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE") {
				$arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] = $arPriceSort[0];
			}
		}
	}?>

	<div class="detail <?=($templateName = $component->{'__template'}->{'__name'})?> fixed_wrapper">
		<?if($arElement):?>

			<?if(!$bShowPeriodLine):?>
				<?$this->SetViewTarget('product_share');?>
					<?if($arParams["USE_SHARE"] == "Y"):?>
						<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
					<?endif;?>
					<?if($arParams['USE_RSS'] !== 'N'):?>
						<div class="colored_theme_hover_bg-block">
							<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
						</div>
					<?endif;?>

					<?if($arParams["USE_SUBSCRIBE_IN_TOP"] == "Y"):?>
						<div><div class="colored_theme_hover_bg-block dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe" title="<?=GetMessage('SUBSCRIBE_TEXT')?>">
							<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>
						</div></div>
					<?endif;?>
				<?$this->EndViewTarget();?>
			<?else:?>
				<?$this->SetViewTarget('share_in_contents');?>

					<?if($arParams["USE_SHARE"] == "Y"):?>
						<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
					<?endif;?>
					<?if($arParams['USE_RSS'] !== 'N'):?>
						<div class="colored_theme_hover_bg-block">
							<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
						</div>
					<?endif;?>

					<?if($arParams["USE_SUBSCRIBE_IN_TOP"] == "Y"):?>
						<div><div class="colored_theme_hover_bg-block dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe" title="<?=GetMessage('SUBSCRIBE_TEXT')?>">
							<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>
						</div></div>
					<?endif;?>
				<?$this->EndViewTarget();?>
			<?endif;?>

		<?endif;?>
		<?
		if(isset($arItemFilter['CODE']))
		{
			unset($arItemFilter['CODE']);
			unset($arItemFilter['SECTION_CODE']);
		}
		if(isset($arItemFilter['ID']))
		{
			unset($arItemFilter['ID']);
			unset($arItemFilter['SECTION_ID']);
		}
		?>
		<?
		$arAllSections = $aMenuLinksExt = [];
		$arSections = CMaxCache::CIBLockSection_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => ['ID'], 'MULTI' => 'N', 'URL_TEMPLATE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section']]], ['IBLOCK_ID' => $arParams['IBLOCK_ID'],/* 'DEPTH_LEVEL' => 1,*/ 'ACTIVE' => 'Y'], false, ['ID', 'SECTION_PAGE_URL', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID']);
		$arSectionsByParentSectionID = CMaxCache::GroupArrayBy($arSections, ['MULTI' => 'Y', 'GROUP' => ['IBLOCK_SECTION_ID']]);
		if ($arSections) {
			CMax::getSectionChilds(false, $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt, true);
		}
		
		$arAllSections = CMax::getChilds2($aMenuLinksExt);
		$allElements = [
			'TEXT' => 'Все',
			'LINK' => '/news/',
			'CURRENT' => 0,
		];
		$arAllSections = array_merge([$allElements],$arAllSections);

		$arTags = [];
		
		$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
		$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);

		if($arSections)
		{
			foreach($arAllSections as $key => $arSection)
			{
				$arElements = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y']], array_merge($arItemFilter, ["SECTION_ID" => $arSection["PARAMS"]["ID"], "INCLUDE_SUBSECTIONS" => "Y"]), false, false, ['ID', 'TAGS']);
				if(!$arElements)
					unset($arAllSections[$key]);
				else
				{
					foreach($arElements as $arTmp)
					{
						if($arTmp['TAGS'])
						{
							$arTags[] = explode(',', (string) $arTmp['TAGS']);
						}
					}
					$arAllSections[$key]['ELEMENT_COUNT'] = count($arElements);
					$arAllSections[$key]['CURRENT'] = CMenu::IsItemSelected($arSection['LINK'], $cur_page, $cur_page_no_index);
					if ($arSection['CHILD']) {
						foreach ($arSection['CHILD'] as $key2 => $arChild) {
							if (CMenu::IsItemSelected($arChild['LINK'], $cur_page, $cur_page_no_index)) {
								$arAllSections[$key]['CHILD'][$key2]['CURRENT'] = 'darken bold';
								$arAllSections[$key]['CURRENT'] = false;
							}
						}
					}
				}
			}
		}
		else
		{
			$arElements = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y']], $arItemFilter, false, false, ['ID', 'TAGS']);

			foreach($arElements as $arTmp)
			{
				if($arTmp['TAGS'])
				{
					$arTags[] = explode(',', (string) $arTmp['TAGS']);
				}
			}
		}
		?>
		<? 
		for($i=0;$i<count($arAllSections);$i++) {
			if (array_key_exists('PARAMS', $arAllSections[$i])) {
				if ($arAllSections[$i]['PARAMS']['ID'] === $arElement['IBLOCK_SECTION_ID_SELECTED']) {
					$arAllSections[$i]['CURRENT'] = 1;
				}
				else {
					$arAllSections[$i]['CURRENT'] = 0;
				}
			}
		}
		if (empty($arElement['IBLOCK_SECTION_ID_SELECTED'])) {
			$arAllSections[0]['CURRENT'] = 1;
		}
		else {
			$arAllSections[0]['CURRENT'] = 0;
		}
		?>
		<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
			<?if($arAllSections):?>

				<div class="categories_block menu_top_block">
					<ul class="categories left_menu dropdown">
						<?foreach($arAllSections as $arSection):
							if(isset($arSection['TEXT']) && $arSection['TEXT']):?>
							
								<li class="categories_item  item v_bottom <?=($arSection['CHILD'] ? 'has-child' : '')?> <?= $arSection['CURRENT'] === 1 ? 'current opened' : ''; ?>">
									<a href="<?=$arSection['LINK'];?>" class="categories_link bordered rounded2">
										<span class="categories_name darken"><?=$arSection['TEXT'];?></span>
										<span class="categories_count muted"><?=$arSection['ELEMENT_COUNT'];?></span>
										<?if ($arSection['CHILD']):?>
											<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
											<span class="toggle_block"></span>
										<?endif;?>
									</a>
									<?if ($arSection['CHILD']):?>
										<div class="child_container dropdown">
											<div class="child_wrapp">
												<ul class="child">
													<?foreach ($arSection['CHILD'] as $arChild):?>
														<li class="menu_item hover_color_theme ">
															<a href="<?=$arChild['LINK'];?>">
																<span class="<?=($arChild['CURRENT'] ?: '');?>"><?=$arChild['TEXT'];?></span>
															</a>
														</li>
													<?endforeach;?>
												</ul>
											</div>
										</div>
									<?endif;?>
								</li>
							<?endif;?>
						<?endforeach;?>
					</ul>
				</div>

			<?endif;?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:search.tags.cloud",
				"main",
				[
					"CACHE_TIME" => "86400",
					"CACHE_TYPE" => "A",
					"CHECK_DATES" => "Y",
					"COLOR_NEW" => "3E74E6",
					"COLOR_OLD" => "C0C0C0",
					"COLOR_TYPE" => "N",
					"TAGS_ELEMENT" => $arTags,
					"FILTER_NAME" => "",
					"FONT_MAX" => "50",
					"FONT_MIN" => "10",
					"PAGE_ELEMENTS" => "150",
					"PERIOD" => "",
					"PERIOD_NEW_TAGS" => "",
					"SHOW_CHAIN" => "N",
					"SORT" => "NAME",
					"TAGS_INHERIT" => "Y",
					"URL_SEARCH" => SITE_DIR."search/index.php",
					"WIDTH" => "100%",
					"arrFILTER" => ["iblock_aspro_max_content"],
					"arrFILTER_iblock_aspro_max_content" => [$arParams["IBLOCK_ID"]]
				], $component
			);?>
		<?$this->__component->__template->EndViewTarget();?>

		<?/*goods block filter*/?>
		<?//$list_view = ($arParams['LIST_VIEW'] ? $arParams['LIST_VIEW'] : 'slider');?>
		<?// goods links?>
		<?if(in_array('LINK_GOODS', $arParams['DETAIL_PROPERTY_CODE'])):?>
			<?
			$catalogID = \Bitrix\Main\Config\Option::get('aspro.max', 'CATALOG_IBLOCK_ID', CMaxCache::$arIBlocks[SITE_ID]["aspro_max_catalog"]["aspro_max_catalog"][0]);
		    $dbProperty = CIBlockProperty::GetList([], ["IBLOCK_ID" => $catalogID, "CODE" => "LINK_BLOG"]);
		    if($dbProperty->SelectedRowsCount() && $arElement['ID'])
		    {
			    $arTmpElement = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($catalogID), 'MULTI' => 'Y']], ['IBLOCK_ID' => $catalogID, 'PROPERTY_LINK_BLOG' => $arElement['ID']], false, false, ['ID']);
			    if($arTmpElement)
			    {
				    foreach($arTmpElement as $arItem)
					    $arFilterElements[] = $arItem['ID'];

				    if($arElement['PROPERTY_LINK_GOODS_VALUE'])
					    $arElement['PROPERTY_LINK_GOODS_VALUE'] = array_merge((array)$arElement['PROPERTY_LINK_GOODS_VALUE'], $arFilterElements);
				    else
					    $arElement['PROPERTY_LINK_GOODS_VALUE'] = $arFilterElements;
			    }
		    }
		    $arTmpGoods = json_decode((string) $arElement["~PROPERTY_LINK_GOODS_FILTER_VALUE"], true);
		    ?>
		    <?if($arElement['PROPERTY_LINK_GOODS_VALUE'] || ($arElement['PROPERTY_LINK_GOODS_FILTER_VALUE'] && $arTmpGoods['CHILDREN'])):?>
				<?
				if(!isset($arParams["PRICE_CODE"]))
				    $arParams["PRICE_CODE"] = [0 => "BASE", 1 => "OPT"];
			    if(!isset($arParams["STORES"]))
				    $arParams["STORES"] = [0 => "1", 1 => "2"];

			    if(!($arElement['PROPERTY_LINK_GOODS_FILTER_VALUE'] && $arTmpGoods['CHILDREN']))
				    $GLOBALS['arrProductsFilter'] = ['ID' => $arElement['PROPERTY_LINK_GOODS_VALUE']];
// var_dump($arElement);
			    global $arRegion;
			    if($arRegion)
			    {
				    if($arRegion['LIST_PRICES'])
				    {
					    if(reset($arRegion['LIST_PRICES']) != 'component')
						    $arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
				    }
				    if($arRegion['LIST_STORES'])
				    {
					    if(reset($arRegion['LIST_STORES']) != 'component')
						    $arParams['STORES'] = $arRegion['LIST_STORES'];
				    }
			    }

			    if($arParams['LIST_PRICES'])
			    {
				    foreach($arParams['LIST_PRICES'] as $key => $price)
				    {
					    if(!$price)
						    unset($arParams['LIST_PRICES'][$key]);
				    }
			    }

			    if($arParams['STORES'])
			    {
				    foreach($arParams['STORES'] as $key => $store)
				    {
					    if(!$store)
						    unset($arParams['STORES'][$key]);
				    }
			    }

			    if($arRegion)
			    {
				    if($arRegion["LIST_STORES"] && $arParams["HIDE_NOT_AVAILABLE"] == "Y")
				    {
						$arStoresFilter = TSolution\Filter::getAvailableByStores($arParams['STORES']);
						if($arStoresFilter){
							$GLOBALS['arrProductsFilter'][] = $arStoresFilter;
						}
				    }
			    }
			    ?>
			<?endif;?>
		<?endif;?>
		<?/*end goods filter*/?>

		<?//element?>
		<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["BLOG_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>

	</div>
	<?/*
	if(is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1){
		CMax::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}*/
	?>
	<?global $isHideLeftBlock;?>

	<?$APPLICATION->ShowViewContent('tags_content');?>

	<div class="bottom-links-block">
		<a class="muted back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>">
			<?=CMax::showIconSvg("return_to_the_list", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
		<span class="font_upper back-url-text"><?=($arParams["T_PREV_LINK"] ?: GetMessage('BACK_LINK'));?></span></a>

		<?if($arParams["SHOW_MAX_ELEMENT"] == "Y" && $arElementNext):?>
			<a class="muted next-url url-block" href="<?=$arElementNext['DETAIL_PAGE_URL']?>">
			<span class="font_upper next-url-text"><?=($arParams["T_MAX_LINK"] ?: GetMessage('MAX_LINK'));?></span>
			<?=CMax::showIconSvg("next_element", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
			</a>
		<?endif;?>

		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<?\Aspro\Functions\CAsproMax::showShareBlock('bottom')?>
		<?endif;?>
	</div>

<?endif;?>