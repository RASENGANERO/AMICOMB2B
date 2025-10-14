<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs('/local/templates/aspro_max_custom/components/bitrix/news/glossary/gloss.js');
?>
<?global $isHideLeftBlock, $arTheme;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK"]) && $arParams["TYPE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK"];
}
if(isset($arParams["SIDE_LEFT_BLOCK"]) && $arParams["SIDE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK"];
}
?>
<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}
?>
<?$bIsHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");?>
<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?></div>
<?
$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);
if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>

	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>

	<?
	$arAllSections = $aMenuLinksExt = [];
	$arSections = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', 'URL_TEMPLATE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'])), array_merge($arItemFilter, array(/*'<=DEPTH_LEVEL' => 2,*/ 'CNT_ACTIVE' => "Y")), false, array('ID', 'SECTION_PAGE_URL', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID'));
	$arSectionsByParentSectionID = CMaxCache::GroupArrayBy($arSections, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
	if ($arSections) {
		CMax::getSectionChilds(false, $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt, true);
	}
	
	$arAllSections = CMax::getChilds2($aMenuLinksExt);
	
	if (isset($arItemFilter['CODE'])) {
		unset($arItemFilter['CODE']);
		unset($arItemFilter['SECTION_CODE']);
	}
	if (isset($arItemFilter['ID'])) {
		unset($arItemFilter['ID']);
		unset($arItemFilter['SECTION_ID']);
	}
	?>
	<?
	$arTags = array();
	if ($arAllSections) {
		foreach ($arAllSections as $key => $arSection) {
			$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array_merge($arItemFilter, array("SECTION_ID" => $arSection["PARAMS"]["ID"], "INCLUDE_SUBSECTIONS" => "Y")), false, false, array('ID', 'TAGS'));
			if (!$arElements) {
				unset($arAllSections[$key]);
			} else {
				foreach ($arElements as $arElement) {
					if ($arElement['TAGS']) {
						$arTags[] = explode(',', $arElement['TAGS']);
					}
				}
				$arAllSections[$key]['ELEMENT_COUNT'] = count($arElements);
			}
		}
	} else {
		$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arItemFilter, false, false, array('ID', 'TAGS'));

		foreach ($arElements as $arElement) {
			if ($arElement['TAGS']) {
				$arTags[] = explode(',', $arElement['TAGS']);
			}
		}
	}
	global $NavNum; 
	$context = \Bitrix\Main\Application::getInstance()->getContext();
	if($NavNum){
		$pagen = $NavNum;
	}else{
		$pagen = 2;
	}
	$numPage = $context->getRequest()->get("PAGEN_".$pagen) ?? 1;
	$arGroup =  array("iNumPage" => $numPage, "nPageSize" => $arParams['NEWS_COUNT']);
	$arSelect = array('ID', 'NAME','PREVIEW_TEXT', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DATE_CREATE');
	$arElement = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);
	if($pagen != $NavNum){
		$NavNum = $pagen - 1;
	}
	
	$CURRENT_PAGE = (CMain::IsHTTPS()) ? "https://" : "http://";
	$CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
	$SITE_DOMAIN = $CURRENT_PAGE;
	$CURRENT_PAGE .= $APPLICATION->GetCurUri();

	$arSite = \CSite::GetByID(SITE_ID)->Fetch();
	foreach($arElement as $key => $element):
		$arSchema[] = array(
			"@context" => "https://schema.org",
			"@type" => "NewsArticle",
			"url" => $CURRENT_PAGE,
			"publisher" => array(
				"@type" => "Organization",
      			"name" => $arSite['NAME']
			),
			"headline" => $element['NAME'],
			"articleBody" => $element['PREVIEW_TEXT'],
			"datePublished" => $element['DATE_CREATE']
		);
		if($element['PREVIEW_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['PREVIEW_PICTURE']);
		}
		if($element['DETAIL_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['DETAIL_PICTURE']);
		}
	endforeach;
	?>

	<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
		<?if($arAllSections):?>
			<div class="categories_block menu_top_block">
				<ul class="categories left_menu dropdown">
					<?foreach($arAllSections as $arSection):
						if(isset($arSection['TEXT']) && $arSection['TEXT']):?>
							<li class="categories_item item v_bottom <?=($arSection['CHILD'] ? 'has-child' : '')?>">
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
															<?=$arChild['TEXT'];?>
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
			Array(
				"CACHE_TIME" => "86400",
				"CACHE_TYPE" => "A",
				"CHECK_DATES" => "Y",
				"COLOR_NEW" => "3E74E6",
				"COLOR_OLD" => "C0C0C0",
				"COLOR_TYPE" => "N",
				"TAGS_ELEMENT" => $arTags,
				"FILTER_NAME" => $arParams["FILTER_NAME"],
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
				"arrFILTER" => array("iblock_aspro_max_content"),
				"arrFILTER_iblock_aspro_max_content" => array($arParams["IBLOCK_ID"])
			), $component, array('HIDE_ICONS' => 'Y')
		);?>
	<?$this->__component->__template->EndViewTarget();?>


	<?/*terms block*/?>
	<?
	$arSelect = [
		'ID', 
		'IBLOCK_ID', 
		'NAME',
		'CODE',
		'ACTIVE',
		'PROPERTY_LANG_TERM'
	];
	$arFilter = [
		'IBLOCK_ID' => 51,
	];
	$arItems = [];
	$arras = CIBlockElement::GetList([], $arFilter, false, false,$arSelect);
	while ($ob = $arras->GetNextElement()) {
		$valuesFields = $ob->GetFields();
		$property_enums = CIBlockPropertyEnum::GetList(
			[
				"DEF"=>"DESC", 
				"SORT"=>"ASC"
			], 
			[
				"IBLOCK_ID"=>51, 
				"CODE"=>"LANG_TERM",
				"ID"=>$valuesFields['PROPERTY_LANG_TERM_ENUM_ID']
			]
			)->Fetch()['XML_ID'];
		$valuesFields['PROP_LANG'] = $property_enums; 
		$arItems[] = $valuesFields;
	}
	$arLangs = [];
	
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if(!empty($arItem['PROPERTY_LANG_TERM_ENUM_ID'])){
				if(!array_key_exists($arItem['PROPERTY_LANG_TERM_ENUM_ID'],$arLangs))
				{
					$arLangs[$arItem['PROPERTY_LANG_TERM_ENUM_ID']] = [
						$arItem['PROPERTY_LANG_TERM_ENUM_ID'],
						$arItem['PROPERTY_LANG_TERM_VALUE'],
						$arItem['PROP_LANG'],
					];
				}
			}	
		}
		if($arLangs)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				$bHasTerm = strval($_GET['term_lang']);
				$term = $_GET['term_lang'];
				foreach ($arLangs as $key => $value) {
					if ($value[2] === $term) { // Проверяем последний элемент подмассива
						$term = $value[0];
						break; // Выходим из цикла, если нашли
					}
				}
				?>
				<div class="select_head_wrap">
					<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasTerm ? '' : 'active');?>">
							<div class="title">
								<?if($bHasTerm):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_TIME');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_TIME');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arLangs as $value):
							$bSelected = ($bHasTerm && $value[0] == $term);?>
							<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
								<div class="title btn-inline darken">
									<?if($bSelected):?>
										<span class="btn-inline darken"><?=$value[1];?></span>
									<?else:?>
										<a class="btn-inline dark_link" href="/glossary/?<?='term_lang='.$value[2]?>"><?=$value[1];?></a>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
						<input class="search-gloss-item" id="search-term" placeholder="Найти термин"/>
					</div>
				</div>
				<?
				if($bHasTerm)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						'PROPERTY_LANG_TERM' => $term
					);
				}
				?>
			<?}
		}
	}?>
	<?/* end terms block*/?>


	<?// section elements?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["BLOG_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
<?endif;?>
