<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$IblockNewsArticles = [];
if (strpos($_SERVER['REQUEST_URI'],'articles')) {
	$IblockNewsArticles = [
		'IBLOCK_ID' => 20,
		'ALL_URL' => 'articles/',
		'TITLE_BLOCK' => 'Статьи',
	];
}
if (strpos($_SERVER['REQUEST_URI'],'news')) {
	$IblockNewsArticles = [
		'IBLOCK_ID' => 23,
		'ALL_URL' => 'news/',
		'TITLE_BLOCK' => 'Новости',
	];
}
$GLOBALS['filterNewsLeft'] = [
	'!PREVIEW_PICTURE' => false,
];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"side_news", 
	array(
		"IBLOCK_TYPE" => "aspro_max_content",
		"IBLOCK_ID" => $IblockNewsArticles["IBLOCK_ID"],
		"NEWS_COUNT" => "4",
		"SORT_BY1" => "RAND",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "RAND",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "filterNewsLeft",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "140",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "side_news",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"TITLE_BLOCK" => $IblockNewsArticles["TITLE_BLOCK"],
		"ALL_URL" => $IblockNewsArticles["ALL_URL"],
		"SET_LAST_MODIFIED" => "N",
		"SHOW_DATE" => "Y",
		"SHOW_IMAGE" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>