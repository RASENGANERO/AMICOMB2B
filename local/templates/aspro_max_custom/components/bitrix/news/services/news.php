<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>

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
<?// intro text?>
<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		[
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		]
	);?></div>
<?if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (strtolower((string) $_REQUEST['ajax']) == 'y'))
{
	$APPLICATION->RestartBuffer();
}?>
<?
// get section items count and subsections
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, false);
$arSubSectionFilter = CMax::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, false);
$itemsCnt = CMaxCache::CIBlockElement_GetList(["CACHE" => ["TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => [$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()]]], $arItemFilter, []);
$arSubSections = CMaxCache::CIBlockSection_GetList(["CACHE" => ["TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y", "CACHE_GROUP" => [$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()]]], $arSubSectionFilter, false, ["ID"]);


?>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>

<?/* start tags */?>
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
$arTags = [];

$arElements = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y']], $arItemFilter, false, false, ['ID', 'TAGS']);

foreach($arElements as $arElement)
{
	if($arElement['TAGS'])
	{
		$arTags[] = explode(',', (string) $arElement['TAGS']);
	}
}
?>
<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
<div>
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
			"arrFILTER" => ["iblock_aspro_max_content"],
			"arrFILTER_iblock_aspro_max_content" => [$arParams["IBLOCK_ID"]]
		], $component, ['HIDE_ICONS' => 'Y']
	);?>
</div>
<?$this->__component->__template->EndViewTarget();?>
<?/* end tags */?>

<?if(!$itemsCnt && !$arSubSections):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// sections?>
	<?$sViewSectionsTemplate = $arParams["SECTIONS_TYPE_VIEW"] === 'FROM_MODULE' ? $arTheme['SERVICES_PAGE_SECTIONS']['VALUE'] : $arParams["SECTIONS_TYPE_VIEW"];?>
	<?@include_once('page_blocks/'.$sViewSectionsTemplate.'.php');?>

	<?// section elements?>
	<?if(strlen((string) $arParams["FILTER_NAME"])):?>
		<?$arTmpFilter = $GLOBALS[$arParams["FILTER_NAME"]];?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);?>
	<?else:?>
		<?$arParams["FILTER_NAME"] = "arrFilterServ";?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;?>
	<?endif;?>
	
	<?$sViewElementsTemplate = $arParams["SECTION_ELEMENTS_TYPE_VIEW"] === 'FROM_MODULE' ? $arTheme['SERVICES_PAGE']['VALUE'] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"];?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

	<?if(strlen((string) $arParams["FILTER_NAME"])):?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arTmpFilter;?>
	<?endif;?>
	
<?endif;?>