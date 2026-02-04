<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ваши скидки");
?>
<?
if (!$USER->IsAuthorized()) {
    LocalRedirect('/b2b/auth/');
}
?>
<?
use AmikomB2B;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$discounts = [];
$discountsMAX = [];
if (!empty($userID)) {
    $discounts = \AmikomB2B\DataB2BUser::getDiscountsBrands($userID);
    $discountsMAX = \AmikomB2B\DataB2BUser::generateDiscBrands($discounts);
}
?>
<?


if (!empty($_GET['letter'])) {
	$GLOBALS['filterBrandsB2B']['NAME'] = $_GET['letter'] . '%'; 
}
if (!empty($discountsMAX)) {
    $GLOBALS['filterBrandsB2B']['ID'] = $discountsMAX['IDS'];
}
?>

<section class="dashboard-section">
	<?if (!empty($discountsMAX['IDS'])):?>
		<div class="dashboard-main-discounts">
		<?$APPLICATION->IncludeComponent(
		"bitrix:news", 
		"brands-b2b", 
		array(
			"ADD_DETAIL_TO_SLIDER" => "Y",
			"ADD_ELEMENT_CHAIN" => "Y",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "Y",
			"AJAX_FILTER_CATALOG" => "Y",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"ALT_TITLE_GET" => "NORMAL",
			"BLOCK_BLOG_NAME" => "Статьи",
			"BLOCK_BRANDS_NAME" => "Бренды",
			"BLOCK_LANDINGS_NAME" => "Коллекции",
			"BLOCK_NEWS_NAME" => "Новости",
			"BLOCK_PARTNERS_NAME" => "Партнеры",
			"BLOCK_PROJECTS_NAME" => "Проекты",
			"BLOCK_REVIEWS_NAME" => "Отзывы",
			"BLOCK_SERVICES_NAME" => "Услуги",
			"BLOCK_STAFF_NAME" => "Сотрудники",
			"BLOCK_TIZERS_NAME" => "",
			"BLOCK_VACANCY_NAME" => "Вакансии",
			"BLOG_TITLE" => "Комментарии",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "100000",
			"CACHE_TYPE" => "N",
			"CHECK_DATES" => "Y",
			"COMMENTS_COUNT" => "5",
			"COMPONENT_TEMPLATE" => "brands-b2b",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"CONVERT_CURRENCY" => "N",
			"COUNT_IN_LINE" => "3",
			"DEFAULT_LIST_TEMPLATE" => "block",
			"DEPTH_LEVEL_BRAND" => "2",
			"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
			"DETAIL_BLOCKS_ALL_ORDER" => "desc,tizers,char,docs,services,news,vacancy,blog,projects,brands,staff,gallery,partners,form_order,landings,goods_sections,reviews,goods,goods_catalog,comments",
			"DETAIL_BLOG_EMAIL_NOTIFY" => "N",
			"DETAIL_BLOG_URL" => "catalog_comments",
			"DETAIL_BLOG_USE" => "N",
			"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
			"DETAIL_DISPLAY_TOP_PAGER" => "N",
			"DETAIL_FB_USE" => "N",
			"DETAIL_FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "DETAIL_TEXT",
				4 => "DETAIL_PICTURE",
				5 => "",
			),
			"DETAIL_LINKED_GOODS_SLIDER" => "Y",
			"DETAIL_PAGER_SHOW_ALL" => "Y",
			"DETAIL_PAGER_TEMPLATE" => "",
			"DETAIL_PAGER_TITLE" => "Страница",
			"DETAIL_PROPERTY_CODE" => array(
				0 => "LINK_BRANDS",
				1 => "LINK_VACANCY",
				2 => "LINK_LANDINGS",
				3 => "LINK_NEWS",
				4 => "LINK_REVIEWS",
				5 => "LINK_PARTNERS",
				6 => "LINK_PROJECTS",
				7 => "SITE",
				8 => "LINK_STAFF",
				9 => "LINK_BLOG",
				10 => "PHONE",
				11 => "LINK_TIZERS",
				12 => "LINK_SERVICES",
				13 => "DOCUMENTS",
				14 => "PHOTOS",
				15 => "",
			),
			"DETAIL_SET_CANONICAL_URL" => "Y",
			"DETAIL_USE_COMMENTS" => "N",
			"DETAIL_VK_USE" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "Y",
			"DISPLAY_LINKED_PAGER" => "Y",
			"DISPLAY_NAME" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_WISH_BUTTONS" => "Y",
			"ELEMENT_TYPE_VIEW" => "element_1",
			"FILE_404" => "",
			"FILTER_FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"FILTER_NAME" => "filterBrandsB2B",
			"FILTER_PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"GALLERY_PRODUCTS_PROPERTY" => "PHOTOS",
			"GALLERY_TYPE" => "small",
			"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
			"HIDE_NOT_AVAILABLE" => "N",
			"HIDE_NOT_AVAILABLE_OFFERS" => "N",
			"IBLOCK_CATALOG_ID" => "29",
			"IBLOCK_CATALOG_TYPE" => "-",
			"IBLOCK_ID" => "33",
			"IBLOCK_LINK_BLOG_ID" => "20",
			"IBLOCK_LINK_BRANDS_ID" => "33",
			"IBLOCK_LINK_LANDINGS_ID" => "",
			"IBLOCK_LINK_NEWS_ID" => "23",
			"IBLOCK_LINK_PARTNERS_ID" => "",
			"IBLOCK_LINK_PROJECTS_ID" => "18",
			"IBLOCK_LINK_REVIEWS_ID" => "22",
			"IBLOCK_LINK_SERVICES_ID" => "25",
			"IBLOCK_LINK_STAFF_ID" => "19",
			"IBLOCK_LINK_TIZERS_ID" => "13",
			"IBLOCK_LINK_VACANCY_ID" => "5",
			"IBLOCK_TYPE" => "aspro_max_content",
			"IMAGE_POSITION" => "left",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"LINKED_ELEMENST_PAGE_COUNT" => "20",
			"LINKED_ELEMENT_TAB_SORT_FIELD" => "sort",
			"LINKED_ELEMENT_TAB_SORT_FIELD2" => "id",
			"LINKED_ELEMENT_TAB_SORT_ORDER" => "asc",
			"LINKED_ELEMENT_TAB_SORT_ORDER2" => "desc",
			"LINKED_PRODUCTS_PROPERTY" => "BRAND",
			"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
			"LIST_FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "DETAIL_PICTURE",
				4 => "",
			),
			"LIST_OFFERS_FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"LIST_OFFERS_LIMIT" => "5",
			"LIST_OFFERS_PROPERTY_CODE" => array(
				0 => "SIZES",
				1 => "COLOR_REF",
				2 => "",
			),
			"LIST_PROPERTY_CATALOG_CODE" => array(
				0 => "",
				1 => "",
			),
			"LIST_PROPERTY_CODE" => array(
				0 => "SITE",
				1 => "PHONE",
				2 => "",
			),
			"LIST_VIEW" => "slider",
			"MAX_GALLERY_GOODS_ITEMS" => "5",
			"MESSAGE_404" => "",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"NEWS_COUNT" => "16",
			"NUM_DAYS" => "30",
			"NUM_NEWS" => "20",
			"OFFERS_CART_PROPERTIES" => array(
			),
			"OFFERS_SORT_FIELD" => "sort",
			"OFFERS_SORT_FIELD2" => "id",
			"OFFERS_SORT_ORDER" => "asc",
			"OFFERS_SORT_ORDER2" => "desc",
			"OFFER_ADD_PICT_PROP" => "-",
			"OFFER_HIDE_NAME_PROPS" => "N",
			"OFFER_TREE_PROPS" => array(
				0 => "SIZES",
				1 => "COLOR_REF",
			),
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Новости",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PRICE_CODE" => array(
				0 => "BASE",
				1 => "OPT",
				2 => "",
			),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_PROPERTIES" => array(
			),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"SALE_STIKER" => "-",
			"SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_2",
			"SEF_FOLDER" => "/b2b/discounts/",
			"SEF_MODE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_STATUS_404" => "Y",
			"SET_TITLE" => "N",
			"SHOW_404" => "Y",
			"SHOW_ARTICLE_SKU" => "N",
			"SHOW_COUNT_ELEMENTS" => "Y",
			"SHOW_DETAIL_LINK" => "Y",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_DISCOUNT_PERCENT_NUMBER" => "N",
			"SHOW_DISCOUNT_TIME" => "Y",
			"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
			"SHOW_GALLERY" => "Y",
			"SHOW_GALLERY_GOODS" => "Y",
			"SHOW_LINKED_PRODUCTS" => "Y",
			"SHOW_MEASURE" => "N",
			"SHOW_MEASURE_WITH_RATIO" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_ONE_CLICK_BUY" => "Y",
			"SHOW_RATING" => "Y",
			"SHOW_SECTIONS_FILTER" => "Y",
			"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
			"SHOW_SORT_IN_FILTER" => "Y",
			"SHOW_UNABLE_SKU_PROPS" => "Y",
			"SIDE_LEFT_BLOCK" => "FROM_MODULE",
			"SIDE_LEFT_BLOCK_DETAIL" => "FROM_MODULE",
			"SORT_BUTTONS" => array(
				0 => "POPULARITY",
				1 => "NAME",
				2 => "PRICE",
			),
			"SORT_BY1" => "SORT",
			"SORT_BY2" => "ID",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "DESC",
			"SORT_PRICES" => "REGION_PRICE",
			"SORT_REGION_PRICE" => "BASE",
			"STAFF_TYPE_DETAIL" => "list",
			"STIKERS_PROP" => "-",
			"STORES" => array(
				0 => "",
				1 => "",
			),
			"STRICT_SECTION_CHECK" => "N",
			"TAGS_SECTION_COUNT" => "",
			"TYPE_LEFT_BLOCK" => "FROM_MODULE",
			"TYPE_LEFT_BLOCK_DETAIL" => "FROM_MODULE",
			"T_DOCS" => "",
			"T_GALLERY" => "Галерея",
			"T_GOODS" => "",
			"T_GOODS_SECTION" => "",
			"T_PROJECTS" => "",
			"T_REVIEWS" => "",
			"T_VIDEO" => "",
			"USE_CATEGORIES" => "N",
			"USE_FILTER" => "Y",
			"USE_PERMISSIONS" => "N",
			"USE_PRICE_COUNT" => "N",
			"USE_RATING" => "N",
			"USE_REVIEW" => "N",
			"USE_RSS" => "Y",
			"USE_SEARCH" => "N",
			"USE_SHARE" => "Y",
			"USE_SUBSCRIBE_IN_TOP" => "N",
			"VIEW_TYPE" => "table",
			"YANDEX" => "N",
			"DISCOUNT_VALUES" => $discountsMAX["DISCOUNTS"],
			"COUNT_ALL_DISCOUNT" => count($discountsMAX["IDS"]),
			"SEF_URL_TEMPLATES" => array(
				"news" => "",
				"section" => "#SECTION_CODE_PATH#/",
				"detail" => "#ELEMENT_CODE#/",
				"rss" => "rss/",
				"rss_section" => "#SECTION_ID#/rss/",
			)
		),
		false
	);?>  
			
	</div>
<?else:?>
	<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/templates/aspro_max_custom_b2b/include/b2b/discounts_text_b2b.php"
						)
					);?>
<?endif;?>
    <div class="dashboard-news">
        <?$GLOBALS['filterNewsB2BMain'] = [
            'PROPERTY_SHOW_B2B_PAGE_VALUE' => 'Y'
        ];?>
        <?$APPLICATION->IncludeComponent("bitrix:news.list", "front_news_b2b", Array(
	"IBLOCK_TYPE" => "aspro_max_content",	// Тип информационного блока (используется только для проверки)
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "filterNewsB2BMain",	// Фильтр
		"IBLOCK_ID" => "23",	// Код информационного блока
		"NEWS_COUNT" => "4",	// Количество новостей на странице
		"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
		"FIELD_CODE" => array(	// Поля
			0 => "CODE",
			1 => "PREVIEW_PICTURE",
			2 => "DATE_ACTIVE_FROM",
			3 => "",
		),
		"PROPERTY_CODE" => array(	// Свойства
			0 => "PERIOD",
			1 => "REDIRECT",
			2 => "",
		),
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "Y",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "N",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "d F Y",	// Формат показа даты
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => "",	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
		"PAGER_TITLE" => "",	// Название категорий
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "ajax",	// Шаблон постраничной навигации
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"COMPONENT_TEMPLATE" => "front_news",
		"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
		"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
		"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"STRICT_SECTION_CHECK" => "N",	// Строгая проверка раздела для показа списка
		"TITLE_BLOCK" => "Новости",	// Заголовок блока
		"TITLE_BLOCK_ALL" => "Все новости",	// Заголовок на все новости
		"ALL_URL" => "news/",	// Ссылка на все новости
		"SIZE_IN_ROW" => "5",	// Элементов в строке
		"TYPE_IMG" => "md",	// Тип картинки
		"SHOW_SUBSCRIBE" => "Y",	// Отображать подписку
		"BG_POSITION" => "top left",	// Расположение фоновой картинки
		"TITLE_SUBSCRIBE" => "Подписаться",	// Текст подписки
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"SHOW_404" => "N",	// Показ специальной страницы
		"IS_AJAX" => CMax::checkAjaxRequest(),
		"MOBILE_TEMPLATE" => $GLOBALS["arTheme"]["MOBILE_NEWS"]["VALUE"],
		"CHECK_REQUEST_BLOCK" => CMax::checkRequestBlock("news"),
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
		"INCLUDE_FILE" => "",	// Файл с дополнительным текстом
		"SHOW_SECTION_NAME" => "N",	// Отображать название раздела
		"HALF_BLOCK" => "N",	// Отображать в 2 блока
		"ALL_BLOCK_BG" => "N",	// Блок с фоном
		"BORDERED" => "N",	// Отображать рамку
		"FON_BLOCK_2_COLS" => "N",	// Широкий блок с фоном
		"USE_BG_IMAGE_ALTERNATE" => "N",	// Включить чередование больших блоков
		"TITLE_SHOW_FON" => "N",	// Отображать текст на фоне картинки
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
    </div>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>