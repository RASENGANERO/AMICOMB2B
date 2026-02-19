<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?><?
echo '<pre>';
print_r($_SERVER);
echo '</pre>';
?>
<div class="personal_wrapper">
	<div class="orders_wrapper">
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.personal.order.detail",
			"main-b2b",
            [
                "PATH_TO_LIST" => "/b2b/orders-history/?filter_history=Y",
                "PATH_TO_CANCEL" => "/personal/cancel/#ID#?",
                "PATH_TO_COPY" => "/personal/orders/?COPY_ORDER=Y&ID=#ID#",
                "PATH_TO_PAYMENT" => "/order/payment/",
                "SET_TITLE" => "Y",
                "ID" => 75,
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ALLOW_INNER" => "N",
                "ONLY_INNER_FULL" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 3600,
                "CACHE_GROUPS" => "Y",
                "RESTRICT_CHANGE_PAYSYSTEM" => array(0 => 0),
                "DISALLOW_CANCEL" => "N",
                "REFRESH_PRICES" => "N",
                "HIDE_USER_INFO" => array(0 => 0),
                "CUSTOM_SELECT_PROPS" => array(),
                "PROP_1" => [],
                "PROP_2" => [],
                "PICTURE_WIDTH" => 110,
                "PICTURE_HEIGHT" => 110,
                "PICTURE_RESAMPLE_TYPE" => 1,
                "AUTH_FORM_IN_TEMPLATE" => "",
                "GUEST_MODE" => "N",
            ],
			$component
		);
        ?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>