<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? global $arTheme, $APPLICATION, $bShowCallBackBlock, $bShowQuestionBlock, $bShowReviewBlock, $isIndex, $isShowIndexLeftBlock;?>
<div class="left_block sticky-sidebar<?=($isIndex ? ($isShowIndexLeftBlock ? "" : " hidden") : "");?>">
	<div class="sticky-sidebar__inner">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			[
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php"
			],
			false
		);?>
		<?
		if (strpos( $APPLICATION->GetCurPage(),"b2b") !== false):?>
			<?$APPLICATION->IncludeComponent(
		"bitrix:menu", 
		"left_menu_b2b_main", 
				array(
					"CACHE_SELECTED_ITEMS" => "Y",
					"ROOT_MENU_TYPE" => "left_b2b",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_TIME" => "3600000",
					"MENU_CACHE_USE_GROUPS" => "N",
					"MENU_CACHE_GET_VARS" => array(
					),
					"MAX_LEVEL" => "2",
					"CHILD_MENU_TYPE" => "left_menub2b",
					"USE_EXT" => "Y",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
					"COMPONENT_TEMPLATE" => "left_menu_b2b_main"
				),
				false,
				array(
					"ACTIVE_COMPONENT" => "Y"
				)
			);
			$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/b2b/managers_b2b.php"
			)
			);?>
		<?endif;?>
		
		<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			[
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php"
			],
			false
		);?>

		<?$APPLICATION->ShowViewContent('left_menu');?>
		<?$APPLICATION->ShowViewContent('under_sidebar_content');?>

		<?CMax::get_banners_position('SIDE', 'Y');?>

		<?if(\Bitrix\Main\ModuleManager::isModuleInstalled("subscribe") && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				[
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => SITE_DIR."include/left_block/comp_subscribe.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php"
				],
				false
			);?>
		<?endif;?>

		<?if($bShowCallBackBlock || $bShowQuestionBlock || $bShowReviewBlock):?>
			<div class="form-action-wrapper">
				<?\Aspro\Functions\CAsproMax::showSideFormLink('CALLBACK', $bShowCallBackBlock);?>
				<?\Aspro\Functions\CAsproMax::showSideFormLink('ASK', $bShowQuestionBlock);?>
				<?\Aspro\Functions\CAsproMax::showSideFormLink('REVIEW', $bShowReviewBlock);?>
			</div>
		<?endif;?>

		<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			[
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/left_block/comp_staff.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php"
			],
			false
		);?>

		<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			[
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/left_block/comp_news.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php"
			],
			false
		);?>

		<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			[
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/left_block/comp_news_articles.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php"
			],
			false
		);?>
	</div>
</div>