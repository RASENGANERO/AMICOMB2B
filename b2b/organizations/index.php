<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ваши реквизиты");
?>

<?
if (!$USER->IsAuthorized()) {
    LocalRedirect('/b2b/auth/');
}
?>
<section class="dashboard-section">
    <div class="dashboard-main-org">
        <div class="dashboard-info-org-container">
			<div class="org-item-active dashboard-info-org-item">
				<div class="dashboard-info-org">
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
				<div class="org-b2b-main-block">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/templates/aspro_max_custom_b2b/include/b2b/org_text_b2b.php"
						)
					);?>
				</div>
			</div>
			<div class="dashboard-info-org-item">
				<div class="dashboard-info-org">
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
				<div class="org-b2b-main-block">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/templates/aspro_max_custom_b2b/include/b2b/org_text_b2b.php"
						)
					);?>
				</div>
			</div>
		</div>
    </div>
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