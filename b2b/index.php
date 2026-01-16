<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?
if (!$USER->IsAuthorized()) {
    LocalRedirect('/b2b/auth/');
}
?>
<section class="dashboard-section">
    <div class="dashboard-main-info">
        <?$APPLICATION->IncludeComponent(
            "bitrix:form.result.new", 
            "b2b-appeal", 
        [
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "Y",
            "AJAX_OPTION_SHADOW" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "SHOW_LICENCE" => "N",
            "CACHE_TIME" => "3600",	// Время кеширования (сек.)
            "CACHE_TYPE" => "N",	// Тип кеширования
            "CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
            "CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
            "EDIT_URL" => "",	// Страница редактирования результата
            "IGNORE_CUSTOM_TEMPLATE" => "Y",	// Игнорировать свой шаблон
            "LIST_URL" => "",	// Страница со списком результатов
            "SEF_MODE" => "N",	// Включить поддержку ЧПУ
            "SUCCESS_URL" => "",	// Страница с сообщением об успешной отправке
            "USE_EXTENDED_ERRORS" => "N",	// Использовать расширенный вывод сообщений об ошибках
            "WEB_FORM_ID" => "22",	// ID веб-формы
            "COMPONENT_TEMPLATE" => "b2b-appeal",
            "VARIABLE_ALIASES" => [
                "WEB_FORM_ID" => "WEB_FORM_ID",
                "RESULT_ID" => "RESULT_ID",
            ]
        ],
	    false
        );?>
        <div class="dashboard-info-iblock">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/local/templates/aspro_max_custom_b2b/include/b2b/companies_b2b.php"
                )
            );?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/local/templates/aspro_max_custom_b2b/include/b2b/agreements_b2b.php"
                )
            );?>
        </div>
    </div>
    <div class="dashboard-news">
        <?$GLOBALS['filterNewsB2BMain'] = [
            'PROPERTY_SHOW_B2B_PAGE_VALUE' => 'Y'
        ];?>
        <?$APPLICATION->IncludeComponent(
        "bitrix:news.list", 
        "front_news", 
        array(
            "IBLOCK_TYPE" => "aspro_max_content",
            "USE_FILTER" => "Y",
            "FILTER_NAME" => "filterNewsB2BMain",
            "IBLOCK_ID" => "23",
            "NEWS_COUNT" => "4",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FIELD_CODE" => array(
                0 => "CODE",
                1 => "PREVIEW_PICTURE",
                2 => "DATE_ACTIVE_FROM",
                3 => "",
            ),
            "PROPERTY_CODE" => array(
                0 => "PERIOD",
                1 => "REDIRECT",
                2 => "",
            ),
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "N",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d F Y",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => "ajax",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
            "PAGER_SHOW_ALL" => "N",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "N",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPONENT_TEMPLATE" => "front_news",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "STRICT_SECTION_CHECK" => "N",
            "TITLE_BLOCK" => "Новости",
            "TITLE_BLOCK_ALL" => "Все новости",
            "ALL_URL" => "news/",
            "SIZE_IN_ROW" => "5",
            "TYPE_IMG" => "md",
            "SHOW_SUBSCRIBE" => "Y",
            "BG_POSITION" => "top left",
            "TITLE_SUBSCRIBE" => "Подписаться",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SHOW_404" => "N",
            "IS_AJAX" => CMax::checkAjaxRequest(),
            "MOBILE_TEMPLATE" => $GLOBALS["arTheme"]["MOBILE_NEWS"]["VALUE"],
            "CHECK_REQUEST_BLOCK" => CMax::checkRequestBlock("news"),
            "MESSAGE_404" => "",
            "INCLUDE_FILE" => "",
            "SHOW_SECTION_NAME" => "N",
            "HALF_BLOCK" => "N",
            "ALL_BLOCK_BG" => "N",
            "BORDERED" => "N",
            "FON_BLOCK_2_COLS" => "N",
            "USE_BG_IMAGE_ALTERNATE" => "N",
            "TITLE_SHOW_FON" => "N",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            
        ),
        false
    );?>
    </div>
</section>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>