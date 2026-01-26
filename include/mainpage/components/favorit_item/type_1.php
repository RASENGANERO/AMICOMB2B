<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?
$GLOBALS['arrFilterPropFavorit'] = [
    '!PREVIEW_PICTURE' => false
];?>
<?$APPLICATION->IncludeComponent("aspro:wrapper.block.max", "b2b-wrapper", Array(
	"IBLOCK_TYPE" => "aspro_max_catalog",	// Тип инфоблока
		"IBLOCK_ID" => "29",	// Инфоблок
		"COMPONENT_TEMPLATE" => "b2b-wrapper",
		"SECTION_ID" => "",	// ID раздела
		"SECTION_CODE" => "",	// Код раздела
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "arrFilterPropFavorit",	// Имя массива со значениями фильтра для фильтрации элементов
		"FILTER_PROP_CODE" => "FAVORIT_ITEM",	// Свойство отбора
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
		"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
		"HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
		"ELEMENT_COUNT" => "30",	// Количество элементов на странице
		"CACHE_TYPE" => "N",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "N",	// Учитывать права доступа
		"DISPLAY_COMPARE" => "Y",	// Выводить кнопку сравнения
		"SHOW_MEASURE" => "N",	// Отображать единицы измерения
		"DISPLAY_WISH_BUTTONS" => "Y",	// Показывать добавление в отложенные
		"SHOW_DISCOUNT_PERCENT" => "Y",	// Отображать экономию
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",	// Отображать процент экономии
		"SHOW_DISCOUNT_TIME" => "Y",	// Отображать срок действия скидки
		"SHOW_OLD_PRICE" => "Y",	// Отображать старую цену
		"PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
		),
		"PRICE_CODE" => array(	// Тип цены
			0 => "BASE",
			1 => "",
		),
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров
		"PRODUCT_PROPERTIES" => "",	// Характеристики товара
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"SHOW_RATING" => "Y",	// Отображать рейтинг
		"STIKERS_PROP" => "HIT",	// Свойство со стикерами
		"SALE_STIKER" => "SALE_TEXT",	// Свойство со стикером акций
		"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"TITLE_BLOCK" => "Товар дня",	// Заголовок блока
		"STORES" => array(	// Склады
			0 => "",
			1 => "",
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SHOW_GALLERY" => "Y",	// Отображать галерею
		"MAX_GALLERY_ITEMS" => "5",	// Количество картинок в галерее
		"ADD_PICT_PROP" => "MORE_PHOTO",	// Свойство с дополнительными картинками
	),
	false
);?>