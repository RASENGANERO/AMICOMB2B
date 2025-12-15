<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $bColoredHeader;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$bColoredHeader = true;
?>
<div class="header-wrapper">
	<div class="logo_and_menu-row header__top-part">
		<div class="maxwidth-theme logo-row">
			<div class="header__top-inner">
				<div class="logo-block floated header__top-item no-shrinked">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowBufferedLogo();?>
						</div>
				</div>
				<div class="header-custom-information">
					<div class="float_wrapper header__top-item ">
						<div class="hidden-sm hidden-xs">
							<div class="top-description addr">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/top_page/slogan.php", [], [
										"MODE" => "html",
										"NAME" => "Text in title",
										"TEMPLATE" => "include_area.php",
									]
								);?>
							</div>
						</div>
					</div>
					<div class="header__top-item flex1">
						<div class="wrap_icon inner-table-block">
							<div class="phone-block-custom phone-block flexbox flexbox--row fontUp">
								<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/header/site-phone.php", [], [
										"MODE" => "html",
										"NAME" => "Phone",
										"TEMPLATE" => "include_area.php",
									]
								);?>
								<div class="inline-block">
									<span class="text-phone-block-custom callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK_HEADER")?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="header__top-item flex1">
						<div class="wrap_icon inner-table-block">
							<div class="phone-block flexbox flexbox--row fontUp">
								<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/header/site-worked.php", [], [
										"MODE" => "html",
										"NAME" => "Work",
										"TEMPLATE" => "include_area.php",
									]
								);?>
							</div>
						</div>
					</div>
				</div>

				<div class="right-icons wb header__top-item">
					<div class="line-block line-block--40 line-block--40-1200">

						<?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
						<?$countSites = count($arShowSites);?>
						<?if ($countSites > 1) :?>
							<div class="line-block__item no-shrinked">
								<div class="wrap_icon inner-table-block">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										[
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."/include/header_include/site.selector.php",
											"SITE_LIST" => $arShowSites,
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "include_area.php",
										],
										false, ["HIDE_ICONS" => "Y"]
									);?>
								</div>
							</div>
						<?endif;?>	
						<div class="line-block__item no-shrinked">
							<div class="wrap_icon">
								<button class="top-btn inline-search-show">
									<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
									<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
								</button>
							</div>
						</div>
						<div class="line-block__item no-shrinked">
							<div class="wrap_icon inner-table-block person">
								<?=CMax::showCabinetLink(true, true, 'big');?>
							</div>
						</div>
						<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
								<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
						<?endif;?>	
					</div>	
				</div>
			</div>
		</div>
	</div>
	<div class="menu-row middle-block bg<?=strtolower((string) $arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="menu-only">
						<nav class="mega-menu sliced">
						<?global $arTheme;?>
						<?$APPLICATION->IncludeComponent("bitrix:menu", "top_menu_custom", [
								"ALLOW_MULTI_SELECT" => "Y",	// Разрешить несколько активных пунктов одновременно
								"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
								"COMPONENT_TEMPLATE" => "top",
								"COUNT_ITEM" => "6",
								"DELAY" => "N",	// Откладывать выполнение шаблона меню
								"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],	// Уровень вложенности меню
								"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
								"MENU_CACHE_TIME" => "3600000",	// Время кеширования (сек.)
								"MENU_CACHE_TYPE" => "A",	// Тип кеширования
								"MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
								"CACHE_SELECTED_ITEMS" => "N",
								"ROOT_MENU_TYPE" => "top_content_multilevel",	// Тип меню для первого уровня
								"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
							],
							false
						);?>
							
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</div>