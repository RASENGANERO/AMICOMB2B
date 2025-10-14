<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Техподдержка");
?>
<section class="mb-60">
	<div class="ami-main-container">
		<div class="support bg-white">
			<div class="ami-main-row">
				<div class="ami-cont-support">
					<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
				[
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/include/support/general_information_support.php"
						]
					);?>
				</div>
				
				<div class="ami-cont-support">
					<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
		"",
						[
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/include/support/application_rules_support.php"
						]
					);?>
				</div>
				<div class="ami-cont-support">
					<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
			[
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/include/support/contact_phone_support.php"
						]
					);?> 
				</div>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"docs-section-list", 
				array(
					"SHOW_TABS" => $arParams["SHOW_TABS"],
					"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => "10",
					"NEWS_COUNT" => "5",
					"SORT_BY1" => $arParams["SORT_BY1"],
					"SORT_ORDER1" => $arParams["SORT_ORDER1"],
					"SORT_BY2" => $arParams["SORT_BY2"],
					"SORT_ORDER2" => $arParams["SORT_ORDER2"],
					"FIELD_CODE" => array(
						0 => "",
						1 => $arParams["LIST_FIELD_CODE"],
						2 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "",
						1 => $arParams["LIST_PROPERTY_CODE"],
						2 => "",
					),
					"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => "N",
					"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
					"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
					"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
					"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
					"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
					"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
					"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
					"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"CHECK_DATES" => "N",
					"PARENT_SECTION" => $arResult["VARIABLES"]["SECTION_ID"],
					"PARENT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
					"INCLUDE_SUBSECTIONS" => "Y",
					"SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
					"COMPONENT_TEMPLATE" => "docs-section-list",
					"FILTER_NAME" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"SET_BROWSER_TITLE" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_META_DESCRIPTION" => "Y",
					"SET_LAST_MODIFIED" => "N",
					"STRICT_SECTION_CHECK" => "N",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"SHOW_404" => "N",
					"MESSAGE_404" => ""
				),
				$component
			);?>
		</div>
	</div>
 </section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>