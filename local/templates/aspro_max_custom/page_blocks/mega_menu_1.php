<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
$arRegions = CMaxRegionality::getRegions();
$bOrderView = isset($arTheme['ORDER_VIEW']) && $arTheme['ORDER_VIEW']['VALUE'] == 'Y' ? true : false;
$bCabinet = isset($arTheme['CABINET']) && $arTheme["CABINET"]["VALUE"]=='Y' ? true : false;
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>

<div class="mega_fixed_menu scrollblock">
	<div class="maxwidth-theme">
		<svg class="svg svg-close" width="14" height="14" viewBox="0 0 14 14">
		  <path data-name="Rounded Rectangle 568 copy 16" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path>
		</svg>
		<i class="svg svg-close mask arrow"></i>

		<div class="row">
			<div class="col-md-9">
				<div class="left_menu_block">
					<div class="logo_block flexbox flexbox--row align-items-normal">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowBufferedLogo();?>
						</div>
						<div class="top-description addr">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/top_page/slogan.php", [], [
									"MODE" => "html",
									"NAME" => "Text in title",
									"TEMPLATE" => "include_area.php",
								]
							);?>
						</div>
					</div>

					<div class="search_block">
						<div class="search_wrap">
							<div class="search-block">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									[
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/top_page/search.title.megamenu.php",
										"EDIT_TEMPLATE" => "include_area.php",					
									],
									false, ["HIDE_ICONS" => "Y"]
								);?>
							</div>
						</div>
					</div>
					<?if(CMax::nlo('menu-megafixed', 'class="loadings" style="height:125px;width:50px;"')):?>
					<!-- noindex -->
					<?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"menu_in_burger",
						[
							"CHILD_MENU_TYPE" => "left",
							"COMPONENT_TEMPLATE" => "top",
							"COUNT_ITEM" => "6",
							"DELAY" => "N",
							"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
							"MENU_CACHE_GET_VARS" => [],
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_USE_GROUPS" => "N",
							"CACHE_SELECTED_ITEMS" => "N",
							"ALLOW_MULTI_SELECT" => "Y",
							"ROOT_MENU_TYPE" => "top_content_multilevel",
							"USE_EXT" => "Y"
						]
					);?>
					<!-- /noindex -->
					<?endif;?>
					<?CMax::nlo('menu-megafixed');?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="right_menu_block">
					<div class="contact_wrap">
						<div class="info">
							<div class="phone blocks">
								<div class="">
								<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/header/site-phone.php", [], [
									"MODE" => "html",
									"NAME" => "Phone",
									"TEMPLATE" => "include_area.php",
								]
								);?>
									<?//CMax::ShowHeaderPhones('white sm', true);?>
								</div>
								<div class="callback_wrap">
									<span class="callback-block animate-load font_upper colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
								</div>
							</div>
							<div class="question_button_wrapper">
								<span class="btn btn-lg btn-transparent-border-color btn-wide animate-load colored_theme_hover_bg-el" data-event="jqm" data-param-form_id="ASK" data-name="ask">
									<?=GetMessage('ASK')?>
								</span>
							</div>

							<div class="person_wrap">
								<?
								// show cabinet item
								CMax::showCabinetLink(true, true, 'big');

								// show basket item
								CMax::ShowMobileMenuBasket();
								?>
							</div>
						</div>
					</div>

					<div class="footer_wrap">
						
						<div class="email blocks color-theme-hover">
							<i class="svg inline  svg-inline-email" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="11" height="9" viewBox="0 0 11 9">
									<path data-name="Rectangle 583 copy 16" class="cls-1" d="M367,142h-7a2,2,0,0,1-2-2v-5a2,2,0,0,1,2-2h7a2,2,0,0,1,2,2v5A2,2,0,0,1,367,142Zm0-2v-3.039L364,139h-1l-3-2.036V140h7Zm-6.634-5,3.145,2.079L366.634,135h-6.268Z" transform="translate(-358 -133)"></path>
								</svg>
							</i>
							<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/burger-menu/site-email.php", [], [
									"MODE" => "html",
									"NAME" => "Email",
									"TEMPLATE" => "include_area.php",
								]
							);?>
						</div>
						
						<div class="address blocks">
							<i class="svg inline  svg-inline-addr" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="9" height="12" viewBox="0 0 9 12">
									<path class="cls-1" d="M959.135,82.315l0.015,0.028L955.5,87l-3.679-4.717,0.008-.013a4.658,4.658,0,0,1-.83-2.655,4.5,4.5,0,1,1,9,0A4.658,4.658,0,0,1,959.135,82.315ZM955.5,77a2.5,2.5,0,0,0-2.5,2.5,2.467,2.467,0,0,0,.326,1.212l-0.014.022,2.181,3.336,2.034-3.117c0.033-.046.063-0.094,0.093-0.142l0.066-.1-0.007-.009a2.468,2.468,0,0,0,.32-1.2A2.5,2.5,0,0,0,955.5,77Z" transform="translate(-951 -75)"></path>
								</svg>
							</i>
							<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/burger-menu/site-address.php", [], [
									"MODE" => "html",
									"NAME" => "Address",
									"TEMPLATE" => "include_area.php",
								]
							);?>
						</div>

						<div class="social-block">
							<?$APPLICATION->IncludeComponent(
								"aspro:social.info.max",
								"",
								[
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => "3600000",
									"CACHE_GROUPS" => "N",
									"COMPONENT_TEMPLATE" => ""
								],
								false
							);?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>