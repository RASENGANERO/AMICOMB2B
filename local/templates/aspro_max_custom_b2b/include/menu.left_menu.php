<?global $arTheme;?>

<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"left_menu", 
	array(
		"CACHE_SELECTED_ITEMS" => "Y",
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "left_menu"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
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
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left_b2b",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "left_menu_b2b_main"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>