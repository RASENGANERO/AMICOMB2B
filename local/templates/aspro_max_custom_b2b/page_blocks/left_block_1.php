<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? global $arTheme, $APPLICATION, $bShowCallBackBlock, $bShowQuestionBlock, $bShowReviewBlock, $isIndex, $isShowIndexLeftBlock;?>
<div class="left_block sticky-sidebar<?=($isIndex ? ($isShowIndexLeftBlock ? "" : " hidden") : "");?>">
	<div class="sticky-sidebar__inner">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "left_block", Array(
			"COMPONENT_TEMPLATE" => ".default",
				"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/menu.left_menu.php",	// Путь к файлу области
				"AREA_FILE_SHOW" => "file",	// Показывать включаемую область
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "include_area.php",	// Шаблон области по умолчанию
			),
			false
		);?>

		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/b2b/managers_b2b.php"
			)
		);?>

		<?if(\Bitrix\Main\ModuleManager::isModuleInstalled("subscribe") && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				array(
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/comp_subscribe.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php"
				),
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
		<?if (in_array($APPLICATION->GetCurPage(),['/articles/','/news/'])):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				array(
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/comp_news.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php"
				),
				false
			);?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				array(
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => "/local/templates/aspro_max_custom_b2b/include/left_block/comp_news_articles.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php"
				),
				false
			);?>
		<?endif;?>
	</div>
</div>