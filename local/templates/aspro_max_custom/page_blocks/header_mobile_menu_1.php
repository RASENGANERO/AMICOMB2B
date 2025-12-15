<?
use \Bitrix\Main\Localization\Loc;
?>
<div class="mobilemenu-v1 scroller">

	<div class="wrap">
		<?if(CMax::nlo('menu-mobile', 'class="loadings" style="height:47px;"')):?>
		<!-- noindex -->
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"top_mobile",
			[
				"COMPONENT_TEMPLATE" => "top_mobile",
				"MENU_CACHE_TIME" => "3600000",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_USE_GROUPS" => "N",
				"MENU_CACHE_GET_VARS" => [
				],
				"DELAY" => "N",
				"MAX_LEVEL" => \Bitrix\Main\Config\Option::get("aspro.max", "MAX_DEPTH_MENU", 2),
				"ROOT_MENU_TYPE" => "top_content_multilevel",
				"CHILD_MENU_TYPE" => "left",
				"CACHE_SELECTED_ITEMS" => "N",
				"ALLOW_MULTI_SELECT" => "Y",
				"USE_EXT" => "Y"
			]
		);?>
		<!-- /noindex -->
		<?endif;?>
		<?CMax::nlo('menu-mobile');?>
		<?
		// show regions
		CMax::ShowMobileRegions();

		// show sites
		$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();
		$countSites = count($arShowSites);
		if ($countSites > 1) : ?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				[
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => SITE_DIR."/include/header_include/site.selector.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php",
					"TEMPLATE_SITE_SELECTOR" => "mobile",
					"SITE_LIST" => $arShowSites,
				],
				false, ["HIDE_ICONS" => "Y"]
			);?>
		<?endif;?>

		<?
		// show cabinet item
		CMax::ShowMobileMenuCabinet();

		// show basket item
		CMax::ShowMobileMenuBasket();

		// use module options for change contacts
		//CMax::ShowMobileMenuContacts();
		?>


		
		<div class="menu middle mobile-menu-contacts">
			<ul>
				<li>
					<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/mobile-menu/site-phone.php", [], [
							"MODE" => "html",
							"NAME" => "Phone",
							"TEMPLATE" => "include_area.php",
						]
					);?>
				</li>
			</ul>
		</div>


		<div class="contacts">
			<div class="title"><?=Loc::getMessage('MAX_T_MENU_CONTACTS_TITLE')?></div>
			
			<div class="address">
				<?=CMax::showIconSvg("address", SITE_TEMPLATE_PATH."/images/svg/address.svg");?>
				<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/mobile-menu/site-address.php", [], [
						"MODE" => "html",
						"NAME" => "Address",
						"TEMPLATE" => "include_area.php",
					]
				);?>
			</div>

			<div class="email">
				<?=CMax::showIconSvg("email", SITE_TEMPLATE_PATH."/images/svg/email_footer.svg");?>
				<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/mobile-menu/site-email.php", [], [
						"MODE" => "html",
						"NAME" => "Email",
						"TEMPLATE" => "include_area.php",
					]
				);?>
			</div>
		</div>

		<?$APPLICATION->IncludeComponent(
			"aspro:social.info.max",
			"",
			[
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"CACHE_GROUPS" => "N",
				"COMPONENT_TEMPLATE" => ".default"
			],
			false
		);?>
	</div>
</div>