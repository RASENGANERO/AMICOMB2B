<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("История заказов");
	$_REQUEST["filter_history"] = "Y";
	if(!$USER->isAuthorized()){LocalRedirect('/b2b/auth/');} else {
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order",
	"template1",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ALLOW_INNER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"CUSTOM_SELECT_PROPS" => "",
		"DETAIL_HIDE_USER_INFO" => "",
		"DISALLOW_CANCEL" => "N",
		"HISTORIC_STATUSES" => array(0=>"F",1=>"N",2=>"P",),
		"NAV_TEMPLATE" => "",
		"ONLY_INNER_FULL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"ORDER_DEFAULT_SORT" => "STATUS",
		"PATH_TO_BASKET" => "/b2b/basket/",
		"PATH_TO_CATALOG" => "/b2b/catalog/",
		"PATH_TO_PAYMENT" => "/b2b/order/payment/",
		"PROP_1" => "",
		"PROP_2" => "",
		"PROP_3" => "",
		"PROP_4" => "",
		"REFRESH_PRICES" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => "",
		"SAVE_IN_SESSION" => "Y",
		"SEF_FOLDER" => "/b2b/orders-history/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array("list"=>"","detail"=>"order_detail.php?ID=#ID#","cancel"=>"order_cancel.php?ID=#ID#",),
		"SET_TITLE" => "N",
		"VARIABLE_ALIASES" => array("detail"=>array("ID"=>"ID",),"cancel"=>array("ID"=>"ID",),)
	)
);?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.list",
	"",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ALLOW_INNER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"DEFAULT_SORT" => "STATUS",
		"DISALLOW_CANCEL" => "N",
		"HISTORIC_STATUSES" => array("F"),
		"ID" => $ID,
		"NAV_TEMPLATE" => "",
		"ONLY_INNER_FULL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"PATH_TO_BASKET" => "",
		"PATH_TO_CANCEL" => "",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_COPY" => "",
		"PATH_TO_DETAIL" => "",
		"PATH_TO_PAYMENT" => "payment.php",
		"REFRESH_PRICES" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
		"SAVE_IN_SESSION" => "Y",
		"SET_TITLE" => "Y",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red"
	)
);?>
<?}?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>