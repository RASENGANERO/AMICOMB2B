<?
$arResult['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? $arResult['NAME'] : '');
$arResult['ADDRESS'] = (in_array('ADDRESS', $arParams['PROPERTY_CODE']) ? $arResult['PROPERTIES']['ADDRESS']['VALUE'] : '');
$arResult['ADDRESS'] = $arResult['TITLE'].((strlen((string) $arResult['TITLE']) && strlen((string) $arResult['ADDRESS'])) ? ', ' : '').$arResult['ADDRESS'];
$_SESSION['SHOP_TITLE'] = $arResult['ADDRESS'];
?>
<?$arShop=CMax::prepareShopDetailArray($arResult, $arParams);?>
<?ob_start()?>
	<?if(abs($arShop["POINTS"]["LAT"]) > 0 && abs($arShop["POINTS"]["LON"]) > 0):?>
		<?//<div class="contacts_map">?>
			<?if($arParams["MAP_TYPE"] != "0"):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.google.view",
					"map",
					[
						"INIT_MAP_TYPE" => "ROADMAP",
						"MAP_DATA" => serialize(["google_lat" => $arShop["POINTS"]["LAT"], "google_lon" => $arShop["POINTS"]["LON"], "google_scale" => 16, "PLACEMARKS" => $arShop["PLACEMARKS"]]),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "100%",
						"CONTROLS" => [
						],
						"OPTIONS" => [
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						],
						"MAP_ID" => "",
						"ZOOM_BLOCK" => [
							"POSITION" => "right center",
						],
						"COMPONENT_TEMPLATE" => "map",
						"API_KEY" => $arParams["GOOGLE_API_KEY"],
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO"
					],
					false, ["HIDE_ICONS" =>"Y"]
				);?>
			<?else:?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.yandex.view",
					"map",
					[
						"INIT_MAP_TYPE" => "ROADMAP",
						"MAP_DATA" => serialize(["yandex_lat" => $arShop["POINTS"]["LAT"], "yandex_lon" => $arShop["POINTS"]["LON"], "yandex_scale" => 17, "PLACEMARKS" => $arShop["PLACEMARKS"]]),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "100%",
						"CONTROLS" => [
							0 => "ZOOM",
							1 => "SMALLZOOM",
							3 => "TYPECONTROL",
							4 => "SCALELINE",
						],
						"OPTIONS" => [
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						],
						"MAP_ID" => "",
						"ZOOM_BLOCK" => [
							"POSITION" => "right center",
						],
						"COMPONENT_TEMPLATE" => "map",
						"API_KEY" => $arParams["GOOGLE_API_KEY"],
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO"
					],
					false, ["HIDE_ICONS" =>"Y"]
				);?>
			<?endif;?>
		<?//</div>?>
	<?endif;?>
<?$html=ob_get_clean();?>
<?$APPLICATION->AddViewContent('map_content', $html);?>
<?\Aspro\Max\Functions\Extensions::init('fancybox');?>