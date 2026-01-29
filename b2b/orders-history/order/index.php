<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?><?
echo '<pre>';
print_r($_SERVER);
echo '</pre>';
$orderID = 
/*[PATH_TO_LIST] => /personal/orders/?filter_history=Y
[PATH_TO_CANCEL] => /personal/cancel/#ID#
[PATH_TO_COPY] => /personal/orders/?COPY_ORDER=Y&ID=#ID#
[PATH_TO_PAYMENT] => /order/payment/
[SET_TITLE] => Y
[ID] => 3
[ACTIVE_DATE_FORMAT] => d.m.Y
[ALLOW_INNER] => N
[ONLY_INNER_FULL] => N
[CACHE_TYPE] => A
[CACHE_TIME] => 3600
[CACHE_GROUPS] => Y
[RESTRICT_CHANGE_PAYSYSTEM] => Array
    (
        [0] => 0
    )
[DISALLOW_CANCEL] => N
[REFRESH_PRICES] => N
[HIDE_USER_INFO] => Array
    (
        [0] => 0
    )


//LocalRedirect('/personal/');*/
?>
<div class="personal_wrapper">
	<div class="orders_wrapper">
		<?/**$APPLICATION->IncludeComponent(
			"bitrix:sale.personal.order.detail",
			"",
			array(
                'PATH_TO_LIST' => '/personal/orders/?filter_history=Y',
                'PATH_TO_CANCEL' => '/personal/cancel/#ID#',
                'PATH_TO_COPY' => '/personal/orders/?COPY_ORDER=Y&ID=#ID#',
                'PATH_TO_PAYMENT' => '/order/payment/',
                'SET_TITLE' => 'Y',
                'ID' => $orderID,
                'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                'ALLOW_INNER' => 'N',
                'ONLY_INNER_FULL' => 'N',
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 3600,
                'CACHE_GROUPS' => 'Y',
                'RESTRICT_CHANGE_PAYSYSTEM' => [0],
                'DISALLOW_CANCEL' => 'N',
                'REFRESH_PRICES' => 'N',
                'HIDE_USER_INFO' => [0],
            ),
			$component
		);*/
        ?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>