<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?
$GLOBALS['filterCatalogSectionsMain'] = [
    'ID' => [722,715,729,792,783,556,799,798,711,821]
];
?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max", 
	"front_sections_only", 
	array(
		"IBLOCK_TYPE" => "aspro_max_catalog",
		"IBLOCK_ID" => "29",
		"COMPONENT_TEMPLATE" => "front_sections_only",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"TITLE_BLOCK" => "Популярные категории",
		"TITLE_BLOCK_ALL" => "Весь каталог",
		"ALL_URL" => "catalog/",
		"VIEW_MODE" => "",
		"VIEW_TYPE" => "type1",
		"NO_MARGIN" => "Y",
		"FILLED" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"TOP_DEPTH" => "4",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "filterCatalogSectionsMain",
		"SHOW_ICONS" => "N",
		"SHOW_SUBSECTIONS" => "N",
		"SCROLL_SUBSECTIONS" => "N",
		"INCLUDE_FILE" => ""
	),
	false
);?>