<?php
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sale");
use \Bitrix\Sale;
use \Bitrix\Sale\Basket;
function setNewDiscountPrice($idElement,$currentQuantity,$quantity) {
    $resDiscount = \CSaleBasket::GetByID(intval($idElement));
    $resDiscount['DISCOUNT_PRICE'] = round($resDiscount['DISCOUNT_PRICE'] / $currentQuantity * $quantity);
    CSaleBasket::Update($idElement,$resDiscount);
    return $resDiscount['DISCOUNT_PRICE'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idElement = $_REQUEST['idElement'];
    $currentQuantity = $_REQUEST['currentQuantity'];
    $quantity = $_REQUEST['quantity'];
    $discPrice = setNewDiscountPrice($idElement,$currentQuantity,$quantity);
    echo json_encode(['resultDiscount'=>$discPrice]);
}


?>